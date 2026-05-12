<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
try {
    $pdo = pdo();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $id = $_POST['id'];
    $priznak = $_POST['priznak'];
    $adress = $_POST['adress'];
    $phone = $_POST['phone'];

    // Подготавливаем SQL-запрос для обновления данных
    $sql = "UPDATE offices SET priznak = :priznak, adress = :adress, phone = :phone WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':priznak', $priznak);
    $stmt->bindParam(':adress', $adress);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':id', $id);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Произошла ошибка при обновлении данных.']);
    }
} catch (PDOException $e) {
    // Обрабатываем исключение PDO
    echo json_encode(['success' => false, 'message' => 'Произошла ошибка при обновлении данных: ' . $e->getMessage()]);
}
