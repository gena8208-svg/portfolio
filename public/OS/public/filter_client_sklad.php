<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$clientId = $_POST['site_id'];
$pdo = pdo();
$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("SELECT dolg FROM users WHERE site_id = :site_id");
    $stmt->bindParam(':site_id', $clientId);
    $stmt->execute();
    $clientData = $stmt->fetch();
    $dolg = isset($clientData['dolg']) ? $clientData['dolg'] : 0;

    $stmt = $pdo->prepare("SELECT id, date, name, site_id, shipment, comment, manager_name 
FROM kassa 
WHERE site_id = :site_id AND shipment!= 0 ORDER BY id DESC");
    $stmt->bindParam(':site_id', $clientId);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pdo->commit();

    $response = [
        'data' => $results,
        'dolg' => $dolg
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['error' => 'Ошибка при выполнении запроса: ' . $e->getMessage()]);
}
