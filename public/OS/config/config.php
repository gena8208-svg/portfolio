 <?php
// config.php
$isLocal = $_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1';

if ($isLocal) {
    // Настройки для локальной машины
    return [
       'db_name' => 'u3511787_demo_os',
        'db_host' => 'localhost',  // Локальный MySQL
        'db_user' => 'root',        // Пользователь XAMPP
        'db_pass' => '',            // Пароль XAMPP (пустой)
    ];
} else {
    // Настройки для сервера (хостинг)
    return [
        'db_name' => 'u3511787_demo_os',
        'db_host' => 'localhost',  // на самом сервере оставляем localhost
        'db_user' => 'u3511787_default',
        'db_pass' => 'NCPc6sQih0U907ed',
    ];
} 