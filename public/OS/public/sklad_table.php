<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
$pdo = pdo();
// $office = $_SESSION['office'];
$site_id = $_SESSION['site_id'];
$name = $_SESSION['username'];


// Шаг 1: Получение client_id из таблицы клиентов
$stmt = $pdo->prepare("SELECT id FROM users WHERE site_id = :site_id AND name = :name");
$stmt->bindParam(':site_id', $site_id);
$stmt->bindParam(':name', $name);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if ($client) {
    $client_id = $client['id'];
}
$stmt = $pdo->prepare("SELECT id, date, name, site_id, shipment, comment, manager_name 
    FROM kassa 
    WHERE shipment != 0
    ORDER BY id DESC 
    LIMIT 500");
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Шаг 4: Подготовка ответа
$response = [
    'data' => $results
];
header('Content-Type: application/json');
echo json_encode($response);
