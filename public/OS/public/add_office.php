<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
try {
    $pdo = pdo();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $phone = $_POST['phone'];
    $adress = $_POST['adress_office'];
    $office = $_POST['priznak_office'];
    $pdo->beginTransaction();

    // Удаляем пробелы в начале и в конце значений
    $office = trim($office);
    $adress = trim($adress);
    $phone = trim($phone);

    // Проверяем, существует ли уже такой priznak
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM offices WHERE priznak = :priznak");
    $stmt->bindParam(':priznak', $office);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // Если такой офис уже существует, возвращаем сообщение об ошибке
        echo json_encode(['status' => 'error', 'message' => 'Такой офис уже существует.']);
    } else {
        // Если офис не существует, выполняем вставку
        $stmt = $pdo->prepare("INSERT INTO offices (priznak, adress, phone) VALUES (:priznak, :adress, :phone)");
        $stmt->bindParam(':priznak', $office);
        $stmt->bindParam(':adress', $adress);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        $pdo->commit();
        echo json_encode(['status' => 'success']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
}
