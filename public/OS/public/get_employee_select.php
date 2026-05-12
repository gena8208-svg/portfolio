<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
$office = $_SESSION['office'];
/* $sql = "SELECT id, name, site_id, dolg, office, balance_limit FROM users ORDER BY id ASC"; */

$sql = "SELECT id, name, site_id, dolg, office, balance_limit
        FROM users  WHERE office = 'ZP' ORDER BY id ASC";
try {
    $pdo = pdo();
    $result = $pdo->query($sql);
    $clients = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $clients[] = $row;
    }
    echo json_encode($clients);
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
