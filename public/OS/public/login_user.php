<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';

$pdo = pdo();
$site_id = $_POST['site_id'];
$password = $_POST['password'];


$stmt = $pdo->prepare("SELECT id, name,  office, password FROM users WHERE site_id = :site_id");
$stmt->bindParam(':site_id', $site_id);
$stmt->execute();
$user = $stmt->fetch();

if ($user) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['name'];
        $_SESSION['office'] = $user['office'];
        $_SESSION['site_id'] = $_POST['site_id'];
        $_SESSION['user_id'] = $user['id'];

        if ($user['office'] == 'CL') {
            header('Location: client.php');
        } elseif ($user['office'] == 'CLU') {
            header('Location: clientclu.php');
        } elseif ($user['office'] == 'SK') {
            header('Location: sklad.php');
        } elseif ($user['office'] == 'P0') {
            header('Location: admkassa.php');
        } elseif ($user['office'] == 'ZP') {
            header('Location: user_zp.php');
        } elseif ($user['office'] == 'V1') {
            header('Location: viewer.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $_SESSION['error'] = 'Пароль неверный';
        header('Location: login.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'Пользователь не найден';
    header('Location: login.php');
    exit;
}
