<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
try {
    $pdo = pdo();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_POST['id'];
    $clientId = $_POST['site_id'];
    $shipment = isset($_POST['shipment']) ? $_POST['shipment'] : 0;
    $return = isset($_POST['return_val']) ? $_POST['return_val'] : 0;
    $comment = $_POST['comment'];
    $oldsum = $_POST['oldbalance'];
    $oldshipment =  $_POST['oldshipment'];
    $shipment = is_numeric($shipment) ? intval($shipment) : 0;
    $return = is_numeric($return) ? intval($return) : 0;

    if (empty($shipment)) $shipment = 0;



    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT dolg FROM users WHERE site_id = :site_id");
    $stmt->bindParam(':site_id', $clientId);
    $stmt->execute();
    $clientData = $stmt->fetch();
    $dolg = $clientData['dolg'];
    if ($return != 0) {
        // Если значение возврата не равно 0, используем его как положительное
        $shipment = abs($return); // Преобразуем в положительное
    } elseif ($shipment != 0) {
        // Если значение отгрузки не равно 0, используем его как отрицательное
        $shipment = -abs($shipment); // Преобразуем в отрицательное
    } else {
        // Если оба значения равны 0, то оставляем shipment как 0
        $shipment = 0;
    }
    // сохранить данные в таблицу kassa
    $stmt1 = $pdo->prepare("UPDATE kassa SET  shipment = :shipment, comment = :comment WHERE id = :id");
    $stmt1->bindParam(':id', $id); // ID записи, которую вы хотите обновить

    $stmt1->bindParam(':shipment', $shipment);
    $stmt1->bindParam(':comment', $comment);
    $stmt1->execute();

    $newsum = (int) $oldsum  - ($oldshipment - $shipment);
    $stmt2 = $pdo->prepare("UPDATE users SET dolg = :dolg WHERE site_id = :site_id");
    $stmt2->bindParam(':dolg', $newsum);
    $stmt2->bindParam(':site_id', $clientId);
    $stmt2->execute();

    $pdo->commit();
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
}
