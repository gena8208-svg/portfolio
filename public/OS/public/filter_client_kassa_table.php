<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$pdo = pdo();
$office = $_SESSION['office'];
$site_id = $_POST['site_id'];
if ($office != 'P0') {
    $stmt = $pdo->prepare("SELECT id, date, name, site_id, user_id, cash, kassa, acquiring, transfer, payment_account, shipment, comment, manager_name, office FROM kassa WHERE office = :office AND site_id = :site_id ORDER BY id DESC");
    $stmt->bindParam(':office', $office);
    $stmt->bindParam(':site_id', $site_id);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

     $stmt = $pdo->prepare("SELECT  COALESCE(SUM(cash), 0) AS total_cash,
                COALESCE(SUM(kassa), 0) AS total_kassa,
                COALESCE(SUM(payment_account), 0) AS payment_account,
                COALESCE(SUM(acquiring), 0) AS total_acquiring,
                COALESCE(SUM(transfer), 0) AS total_transfer,
                COALESCE(SUM(shipment), 0) AS total_shipment
                 FROM kassa  WHERE office= :office AND site_id = :site_id");
    $stmt->bindParam(':office', $office);
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
} else {
    $stmt = $pdo->prepare("SELECT id, date, name, site_id, user_id, cash, kassa, acquiring, transfer, payment_account, shipment, comment, manager_name, office FROM kassa WHERE site_id = :site_id ORDER BY id DESC");
    $stmt->bindParam(':site_id', $site_id);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
}
$response = [
    'data' =>  $data,
    'totals' => [
        'total_cash' => $totalCash,
        'total_kassa' => $totalKassa,
        'total_acquiring' => $totalAcquiring,
        'total_transfer' => $totalTransfer,
        'total_sum'=> $totalsum,
    ]
];
// возврат данных в формате JSON
header('Content-Type: application/json');
echo json_encode($response);
