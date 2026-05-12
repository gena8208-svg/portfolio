<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
try {
    $pdo = pdo();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $name = $_POST['name'];
    $site_id = $_POST['site_id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $office = $_POST['office'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $pdo->beginTransaction();

    // Проверяем, есть ли клиент с таким же site_id и номером телефона
    $stmt = $pdo->prepare("SELECT id FROM users WHERE site_id = :site_id");
    $stmt->bindParam(':site_id', $site_id);
    $stmt->execute();
    $existingClient = $stmt->fetchColumn();

    if ($existingClient) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Клиент с таким site_id уже есть в базе']);
        return;
    }

    $stmt = $pdo->prepare("SELECT priznak FROM offices WHERE id = :office");
    $stmt->execute([':office' => $office]);
    $office_name = $stmt->fetchColumn();

    if (!$office_name) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Офис не найден.']);
        return;
    }
    // Удаляем пробелы в начале и в конце значений
    $site_id = trim($site_id);
    $email = trim($email);
    $phone = trim($phone);
    $dolg = 0;

    $stmt = $pdo->prepare("INSERT INTO users (name, site_id, email, phone, dolg, office, password) VALUES (:name, :site_id, :email, :phone, :dolg, :office, :password)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':site_id', $site_id);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':dolg', $dolg);
    $stmt->bindParam(':office', $office_name);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();


    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => 'Клиент успешно добавлен.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
}
