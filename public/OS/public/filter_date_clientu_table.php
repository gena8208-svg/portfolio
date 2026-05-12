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

$stmt = $pdo->prepare("SELECT  SUM(payment_account) AS total_payment,
                                      SUM(shipment) AS total_shipment 
                                FROM kassa WHERE user_id = :user_id 
                                AND DATE(date) >= DATE(:start_date) 
                                AND DATE(date) < DATE(DATE_ADD(:end_date, INTERVAL 1 DAY)) ");
$stmt->bindParam(':user_id', $client_id);
$stmt->bindParam(':start_date', $startDate);
$stmt->bindParam(':end_date', $endDate);
$stmt->execute();

$totals = $stmt->fetch(PDO::FETCH_ASSOC);
$totalPayment = $totals['total_payment'] ?? 0;
$totalShipment = $totals['total_shipment'] ?? 0;

// Шаг 3: Фильтрация по client_id в таблице kassa
$stmt = $pdo->prepare("SELECT id, date, payment_account, shipment, comment 
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
$totalSum = $totalPayment + $totalShipment;

$response = [
    'data' => $results,
    'totals' => [
        'total_payment' => $totalPayment,
        'total_shipment' => $totalShipment,
        'total_sum' => $totalSum
    ],
];
header('Content-Type: application/json');
echo json_encode($response);
