<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$pdo = pdo();
$office = $_SESSION['office'];
$site_id = $_SESSION['site_id'];
$name = $_SESSION['username'];
$startDate = isset($_POST['datetimePickerStart']) ? $_POST['datetimePickerStart'] : null;
$endDate = isset($_POST['datetimePickerEnd']) ? $_POST['datetimePickerEnd'] : null;

$stmt = $pdo->prepare("SELECT id FROM users WHERE site_id = :site_id AND name = :name");
$stmt->bindParam(':site_id', $site_id);
$stmt->bindParam(':name', $name);
$stmt->execute();

$client = $stmt->fetch(PDO::FETCH_ASSOC);

if ($client) {
    $client_id = $client['id'];
}

$stmt = $pdo->prepare("SELECT SUM(cash) AS total_cash, 
                                      SUM(kassa) AS total_kassa, 
                                      SUM(acquiring) AS total_acquiring, 
                                      SUM(transfer) AS total_transfer, 
                                      SUM(shipment) AS total_shipment 
                                FROM kassa WHERE user_id = :user_id 
                                AND DATE(date) >= DATE(:start_date) 
                                AND DATE(date) < DATE(DATE_ADD(:end_date, INTERVAL 1 DAY)) ");
$stmt->bindParam(':user_id', $client_id);
$stmt->bindParam(':start_date', $startDate);
$stmt->bindParam(':end_date', $endDate);
$stmt->execute();

$totals = $stmt->fetch(PDO::FETCH_ASSOC);

$totalCash = $totals['total_cash'] ?? 0; // Если нет данных, ставим 0
$totalKassa = $totals['total_kassa'] ?? 0;
$totalAcquiring = $totals['total_acquiring'] ?? 0;
$totalTransfer = $totals['total_transfer'] ?? 0;
$totalShipment = $totals['total_shipment'] ?? 0;

// Шаг 3: Фильтрация по client_id в таблице kassa
$stmt = $pdo->prepare("SELECT id, date, cash, kassa, acquiring, transfer, shipment, comment 
                                FROM kassa 
                                WHERE user_id = :user_id 
                                AND DATE(date) >= DATE(:start_date) 
                                AND DATE(date) < DATE(DATE_ADD(:end_date, INTERVAL 1 DAY)) 
                                ORDER BY id DESC 
                                LIMIT 500");

$stmt->bindParam(':user_id', $client_id);
$stmt->bindParam(':start_date', $startDate);
$stmt->bindParam(':end_date', $endDate);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalSum = $totalCash + $totalKassa + $totalAcquiring + $totalTransfer + $totalShipment;

$response = [
    'data' => $results,
    'totals' => [
        'total_cash' => $totalCash,
        'total_kassa' => $totalKassa,
        'total_acquiring' => $totalAcquiring,
        'total_transfer' => $totalTransfer,
        'total_shipment' => $totalShipment,
        'total_sum' => $totalSum
    ],
];
header('Content-Type: application/json');
echo json_encode($response);
