<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';

try {
    $pdo = pdo();
    $id = $_POST['id'];

    // Подготовленный запрос для получения данных пользователя по ID
    $stmt = $pdo->prepare("SELECT id, priznak, adress, phone FROM offices WHERE id = :id");

    // Выполняем запрос
    $stmt->execute(['id' => $id]);

    // Извлекаем данные
    $offices = $stmt->fetch(PDO::FETCH_ASSOC);

    $response = [
        'data' => $offices
    ];
    // Возвращаем данные в формате JSON
    echo json_encode($response);
} catch (PDOException $e) {
    // Обработка ошибок
    echo json_encode(['error' => $e->getMessage()]);
}
