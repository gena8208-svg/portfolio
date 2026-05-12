<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
$pdo = pdo();
$office = $_SESSION['office'];

if ($office != 'P0') {
    $stmt = $pdo->prepare("SELECT id, date, name, site_id, user_id, cash, kassa, acquiring, transfer, payment_account, shipment, comment, manager_name, office FROM kassa WHERE office = :office AND shipment = 0 ORDER BY id DESC LIMIT 200");
    $stmt->bindParam(':office', $office);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT SUM(cash) AS total_cash, SUM(kassa) AS total_kassa, SUM(acquiring) AS total_acquiring, SUM(transfer) AS total_transfer FROM kassa  WHERE office = :office");
    $stmt->bindParam(':office', $office);
    $stmt->execute();
    $totals = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalCash = $totals['total_cash'];
    $totalKassa = $totals['total_kassa'];
    $totalAcquiring = $totals['total_acquiring'];
    $totalTransfer = $totals['total_transfer'];
} else {
    $stmt = $pdo->prepare("SELECT id, date, name, site_id, user_id, cash, kassa, acquiring, transfer, payment_account, shipment, comment, manager_name, office FROM kassa ORDER BY id DESC LIMIT 200");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT SUM(cash) AS total_cash, SUM(kassa) AS total_kassa, SUM(acquiring) AS total_acquiring, SUM(transfer) AS total_transfer FROM kassa");
    $stmt->execute();
    $totals = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalCash = $totals['total_cash'];
    $totalKassa = $totals['total_kassa'];
    $totalAcquiring = $totals['total_acquiring'];
    $totalTransfer = $totals['total_transfer'];
}
$response = [
    'data' =>  $data,
    'totals' => [
        'total_cash' => $totalCash,
        'total_kassa' => $totalKassa,
        'total_acquiring' => $totalAcquiring,
        'total_transfer' => $totalTransfer,
    ]
];
// возврат данных в формате JSON
header('Content-Type: application/json');
echo json_encode($response);
