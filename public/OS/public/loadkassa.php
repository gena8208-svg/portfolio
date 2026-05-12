<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
$pdo = PDO();

// запрос данных из базы данных
$sql = "SELECT id,  date, client_id, order_id, client, cash, acquiring, transfercard, summazakaza, summazakupki, summanakl, supdolg, comment, itogprofit, client_type FROM kassa ORDER BY id DESC";
$stmt = $pdo->query($sql);
$data = $stmt->fetchAll();

// возврат данных в формате JSON
header('Content-Type: application/json');
echo json_encode($data);
