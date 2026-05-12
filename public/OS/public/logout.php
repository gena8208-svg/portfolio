<?php
session_start(); // Запускаем сессию
session_unset(); // Удаляем все переменные сессии
session_destroy(); // Уничтожаем сессию

header("Location: login.php"); // Перенаправляем на login.php
exit(); // Завершаем выполнение скрипта
