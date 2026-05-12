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
    $nal = $_POST['cash'];
    $kassa = $_POST['kassa'];
    $ekv = $_POST['acquiring'];
    $transfer = $_POST['transfer'];
    $shipment = $_POST['shipment'];
    $payment_account = $_POST['payment_account'];
    $comment = $_POST['comment'];
    $oldsum = $_POST['oldsum'];

    if (empty($nal)) $nal = 0;
    if (empty($kassa)) $kassa = 0;
    if (empty($ekv)) $ekv = 0;
    if (empty($transfer)) $transfer = 0;
    if (empty($shipment)) $shipment = 0;
    if (empty($payment_account)) $payment_account = 0;


    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT dolg FROM users WHERE site_id = :site_id");
    $stmt->bindParam(':site_id', $clientId);
    $stmt->execute();
    $clientData = $stmt->fetch();
    $dolg = $clientData['dolg'];

    // сохранить данные в таблицу kassa
    $stmt1 = $pdo->prepare("UPDATE kassa SET cash = :cash, kassa = :kassa, acquiring = :acquiring, transfer = :transfer, payment_account = :payment_account, shipment = :shipment, comment = :comment WHERE id = :id");
    $stmt1->bindParam(':id', $id); // ID записи, которую вы хотите обновить
    $stmt1->bindParam(':cash', $nal);
    $stmt1->bindParam(':kassa', $kassa);
    $stmt1->bindParam(':acquiring', $ekv);
    $stmt1->bindParam(':transfer', $transfer);
    $stmt1->bindParam(':payment_account', $payment_account);
    $stmt1->bindParam(':shipment', $shipment);
    $stmt1->bindParam(':comment', $comment);
    $stmt1->execute();

    $newsum = (int)$nal + (int)$kassa + (int)$ekv + (int)$transfer + (int)$shipment + (int)$payment_account;
    $sum = $newsum - $oldsum;
    $dolg = $dolg + $sum;
    $stmt2 = $pdo->prepare("UPDATE users SET dolg = :dolg WHERE site_id = :site_id");
    $stmt2->bindParam(':dolg', $dolg);
    $stmt2->bindParam(':site_id', $clientId);
    $stmt2->execute();

    $pdo->commit();
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
}
