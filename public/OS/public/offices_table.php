<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
try {
    $pdo = pdo();
    // Подготовленный запрос для получения всех сотрудников
    $stmt = $pdo->prepare("SELECT * FROM offices ORDER BY id");
    // Выполняем запрос
    $stmt->execute();

    // Извлекаем данные
    $offices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Возвращаем данные в формате JSON
    echo json_encode($offices);
} catch (PDOException $e) {
    // Обработка ошибок
    echo json_encode(['error' => $e->getMessage()]);
}
