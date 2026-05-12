<?php
session_set_cookie_params(86400);
ini_set('session.gc_maxlifetime', 86400);
session_start();

function pdo(): PDO
{
    static $pdo;

    if (!$pdo) {
        if (file_exists(__DIR__ . '/config.php')) {
            $config = include __DIR__ . '/config.php';
            $dsn = 'mysql:dbname=' . $config['db_name'] . ';host=' . $config['db_host'];
            try {
                $pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->exec('SET NAMES "utf8mb4"');
            } catch (PDOException $e) {
                // Логируем ошибку вместо прямого перенаправления
                error_log($e->getMessage()); // Логируем ошибку в файл
                if (!headers_sent()) {
                    header('Location: /503.html');
                    exit;
                }
            }
        } else {
            // Логируем, что файл конфигурации не найден
            error_log('Config file not found: ' . __DIR__ . '/config.php');

            if (!headers_sent()) {
                header('Location: /503.html');
                exit;
            }
        }
    }

    return $pdo;
}

function check_auth(): bool
{
    return isset($_SESSION['user_id']);
}
