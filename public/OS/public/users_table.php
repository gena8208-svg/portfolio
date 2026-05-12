<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
$office = $_SESSION['office'];
try {
    $pdo = pdo();
    // Подготовленный запрос для получения всех сотрудников
    if ($office !== 'P0') {
        $stmt = $pdo->prepare("SELECT id, name, site_id, email, phone, dolg, office, balance_limit FROM users WHERE office IN ('CL', 'CLU') ORDER BY dolg");
    } else {
        $stmt = $pdo->prepare("SELECT id, name, site_id, email, phone, dolg, office, balance_limit FROM users ORDER BY dolg");
    }
    // Выполняем запрос
    $stmt->execute();

    // Извлекаем данные
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Возвращаем данные в формате JSON
    echo json_encode($clients);
} catch (PDOException $e) {
    // Обработка ошибок
    echo json_encode(['error' => $e->getMessage()]);
}
