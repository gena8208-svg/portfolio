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
    /* $shipment = isset($_POST['shipment']) ? $_POST['shipment'] : 0; */
    $return =  $_POST['return_val'];
    $clientId = $_POST['id'];
    $comment = $_POST['comment'];
    $return = intval($return);
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT name, site_id, dolg FROM users WHERE id = :id");
    $stmt->bindParam(':id', $clientId);
    $stmt->execute();
    $clientData = $stmt->fetch();
    $dolg = $clientData['dolg'];

    /* if (!$clientData) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Клиент не найден.']);
        return;
    } */
    $office = $_SESSION['office'];
    $manager_name = $_SESSION['username'];
    $shipment = abs($return); // Преобразуем в положительное
    $cash = $kassa = $acquiring = $transfer = 0;
    // сохранить данные в таблицу kassa
    $stmt1 = $pdo->prepare("INSERT INTO kassa (date, name, site_id, user_id, cash, kassa, acquiring, transfer, shipment, comment, office, manager_name) VALUES (:date, :name, :site_id, :user_id, :cash, :kassa, :acquiring, :transfer, :shipment, :comment, :office, :manager_name)");
    date_default_timezone_set('Europe/Moscow');
    $todayDate = date('Y-m-d H:i:s');
    $stmt1->bindParam(':date', $todayDate);
    $stmt1->bindParam(':name', $clientData['name']);
    $stmt1->bindParam(':site_id', $clientData['site_id']);
    $stmt1->bindParam(':user_id', $clientId);
    $stmt1->bindParam(':cash', $cash);
    $stmt1->bindParam(':kassa', $kassa);
    $stmt1->bindParam(':acquiring', $acquiring);
    $stmt1->bindParam(':transfer', $transfer);
    $stmt1->bindParam(':shipment', $shipment);
    $stmt1->bindParam(':comment', $comment);
    $stmt1->bindParam(':office', $office);
    $stmt1->bindParam(':manager_name', $manager_name);
    $stmt1->execute();

    $dolg = (int)$clientData['dolg'] + $shipment;
    $stmt2 = $pdo->prepare("UPDATE users SET dolg = :dolg WHERE id = :id");
    $stmt2->bindParam(':dolg', $dolg);
    $stmt2->bindParam(':id', $clientId);
    $stmt2->execute();
    $pdo->commit();

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
}
