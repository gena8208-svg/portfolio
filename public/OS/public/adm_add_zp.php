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
    $transferzp = $_POST['transferzp'];
    $transferzpplus = $_POST['transferzpplus'];
    $clientId = $_POST['id'];
    $comment = $_POST['commentzp'];
    $transfer = 0;

    // Приведение к числовому типу
    $transferzp = intval($transferzp);
    $transferzpplus = intval($transferzpplus);
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT name, site_id FROM users WHERE id = :id");
    $stmt->bindParam(':id', $clientId);
    $stmt->execute();
    $clientData = $stmt->fetch();
    $site_id = $clientData['site_id'];
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

    $office = $_SESSION['office'];
    $manager_name = $_SESSION['username'];
    $transferzp = -abs($transferzp);
    $transferzpplus = abs($transferzpplus);
    if ($transferzp == 0) {
        $transfer = $transferzpplus;
    } else {
        $transfer = $transferzp;
    }
    $kassa = $acquiring = $cash = $shipment = 0;
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
 $logMessage = "";
    $logMessage = "[" . $todayDate . "] Операция: Внесение данных в таблицу Касса\n";
    $logMessage .= "id клиента: " . $site_id . ", Нал: " . $transfer . ", comment: " . $comment . ", office: " . $office . ", Менеджер: " . $manager_name . "\n";
    error_log($logMessage, 3, 'logs/zp.log', FILE_APPEND);
    $balance = (int)$totalsum + $transfer;
    $stmt2 = $pdo->prepare("UPDATE users SET dolg = :dolg WHERE id = :id");
    $stmt2->bindParam(':dolg', $balance);
    $stmt2->bindParam(':id', $clientId);
    $stmt2->execute();
    $logMessage = "";
    $logMessage = "[" . $todayDate . "] Операция: Обновление баланса клиента в таблице Клиенты\n";
    $logMessage .= "id Клиента: " . $site_id . ", Выплата/пополнение " . $transfer . ", Баланс_новый: " . $balance . ", Баланс_старый: " . $totalsum ."\n";
    error_log($logMessage, 3, 'logs/zp.log', FILE_APPEND);
    $pdo->commit();

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
}
