<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';

try {
    $pdo = pdo();
    $id = $_POST['id'];

    // Подготовленный запрос для получения данных пользователя по ID
    $stmt = $pdo->prepare("SELECT id, name, site_id, user_id, cash, kassa, acquiring, transfer, payment_account, shipment, comment FROM kassa WHERE id = :id");

    // Выполняем запрос
    $stmt->execute(['id' => $id]);

    // Извлекаем данные
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    // Возвращаем данные в формате JSON
    echo json_encode($client);
} catch (PDOException $e) {
    // Обработка ошибок
    echo json_encode(['error' => $e->getMessage()]);
}
