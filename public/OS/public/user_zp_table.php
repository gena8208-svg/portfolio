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
/* $name = $_SESSION['username']; */

if ($office == 'ZP') {
    /* $stmt = $pdo->prepare("SELECT id, balance_limit FROM users WHERE site_id = :site_id AND name = :name"); */
    $stmt = $pdo->prepare("SELECT id, dolg, balance_limit FROM users WHERE site_id = :site_id");
    $stmt->bindParam(':site_id', $site_id);
    /*  $stmt->bindParam(':name', $name); */
    $stmt->execute();

    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client) {
        $client_id = $client['id'];
        $balance_limit = $client['balance_limit'];
        /*  $balance = $client['dolg']; */
    }

    // Шаг 2: Подсчет итогов по всей таблице kassa
    $stmt = $pdo->prepare("SELECT SUM(cash) AS total_cash, SUM(transfer) AS total_transfer 
FROM kassa WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $client_id);
    $stmt->execute();
    $totals = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalCash = $totals['total_cash'] ?? 0; // Если нет данных, ставим 0
    $totalTransfer = $totals['total_transfer'] ?? 0;
    $totalSum = $totalCash + $totalTransfer;
    $ostatok = $totalSum - $balance_limit;
    // Шаг 3: Фильтрация по client_id в таблице kassa
    $stmt = $pdo->prepare("SELECT id, date, cash, transfer, comment 
    FROM kassa 
    WHERE user_id = :user_id
    ORDER BY id DESC");
    $stmt->bindParam(':user_id', $client_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Шаг 4: Подготовка ответа
    $response = [
        'data' => $results,
        'totals' => [
            /* 'total_cash' => $totalCash, */
            'total_sum' => $totalSum,
            /* 'total_transfer' => $totalTransfer, */
            'balance_limit' => $balance_limit,
            'ostatok' => $ostatok,
        ]
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Клиент не найден - перенаправление на login.php
    header('Location: login.php');
    exit(); // Завершение скрипта после перенаправления
}
