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
    $site_id = $_POST['site_id'];
    $oldsum = $_POST['oldsum'];

    $pdo->beginTransaction();
$stmt = $pdo->prepare("SELECT  COALESCE(SUM(cash), 0) AS total_cash,
                COALESCE(SUM(kassa), 0) AS total_kassa,
                COALESCE(SUM(payment_account), 0) AS payment_account,
                COALESCE(SUM(acquiring), 0) AS total_acquiring,
                COALESCE(SUM(transfer), 0) AS total_transfer,
                COALESCE(SUM(shipment), 0) AS total_shipment
                 FROM kassa  WHERE site_id = :site_id");
    $stmt->bindParam(':site_id', $site_id);
    $stmt->execute();
    $totals = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalCash = $totals['total_cash'];
    $totalKassa = $totals['total_kassa'];
    $totalAcquiring = $totals['total_acquiring'];
    $totalTransfer = $totals['total_transfer'];
    $totalShipment = $totals['total_shipment'];
    $totalpayment_account = $totals['payment_account'];
    $totalsum = $totalCash + $totalKassa + $totalShipment + $totalTransfer + $totalAcquiring + $totalpayment_account;

    // Удалить запись из таблицы kassa по ID строки
    $stmt1 = $pdo->prepare("DELETE FROM kassa WHERE id = :id");
    $stmt1->bindParam(':id', $id);
    $stmt1->execute();

    // Обновить долг клиента
    $balance = $totalsum - (int)$oldsum;
    $stmt2 = $pdo->prepare("UPDATE users SET dolg = :dolg WHERE site_id = :site_id");
    $stmt2->bindParam(':dolg', $balance);
    $stmt2->bindParam(':site_id', $site_id);
    $stmt2->execute();
    date_default_timezone_set('Europe/Moscow');
    $todayDate = date('Y-m-d H:i:s');
    $logMessage = "";
    $logMessage = "[" . $todayDate . "] Операция: Обновление баланса клиента в таблице users\n";
    $logMessage .= "id Клиента: " . $site_id . ", Баланс_новый: " . $balance . ", старый баланс: " . $oldsum . "\n";
    error_log($logMessage, 3, 'logs/delete_row.log', FILE_APPEND);

    $pdo->commit();
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
}
