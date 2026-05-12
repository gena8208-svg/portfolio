<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';

/* if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
} */
/*if (isset($_SESSION['timeout'])) {
    $_SESSION['error'] = 'Вы не были активны на сайте более 5 мин.';
    unset($_SESSION['timeout']);
} else {
    $_SESSION['timeout'] = true;
}*/
/*if (isset($_SESSION['timeout'])) {
    $_SESSION['error'] = 'Вы не были активны на сайте более 5 мин.';
    unset($_SESSION['timeout']);
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-cache');
    header('Pragma: no-cache');
} else {
    $_SESSION['timeout'] = true;
}*/
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>АВТОРИЗАЦИЯ</title>
    <!-- Подключение Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:wght@700&display=swap"
        rel="stylesheet">
    <link rel="icon" href="assets/img/favicon-32x32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="assets/img/favicon-16x16.png" type="image/png" sizes="16x16">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <style>
        body {
            background-color: #f1f1f1;
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }

        .container {
            background-color: #f1f1f1;
        }

        .full-height {
            height: 100vh;
            /* Высота контейнера на 100% от высоты окна */
        }

        h2,
        form {
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
            border-radius: 10px;
            background-color: #ffffff;
            padding: 15px;
        }


        .form-group input {
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }

        button {
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }

        /* Стили для центрирования формы по вертикали */
    </style>
</head>

<body>
    <div class="container full-height d-flex justify-content-center align-items-center">
        <div class="w-100" style="max-width: 400px">
            <div class="w-100 shadow-lg" style="max-width: 400px;">
                <form action="login_user.php" method="post">
                    <h1 class="text-center text-primary ">
                        <i class="bi bi-person-circle" style="font-size: 60px;"></i>
                    </h1>
                    <h2 class="text-center">Вход в систему</h2>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" style="font-size:0.8rem" role="alert">
                            <?php
                            echo $_SESSION['error']; // Отображаем сообщение об ошибке
                            unset($_SESSION['error']); // Удаляем сообщение об ошибке из сессии
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="site_id">Сайт ID</label>
                        <input type="text" class="form-control" id="site_id" name="site_id" required
                            autocomplete="off" placeholder="Введите сайт ID" />
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password" placeholder="Пароль" />
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="togglePassword">
                                    <i class="bi bi-eye-slash" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="submit " class="btn btn-primary btn-block"
                        style="margin-top:30px; margin-bottom:20px; font-family: Montserrat, sans-serif;font-optical-sizing: auto;font-weight: 700;font-style: normal;font-size: 1.2rem;">Войти</button>

                </form>
            </div>
        </div>
        <script>
            const passwordInput = document.getElementById('password');
            const togglePasswordButton = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePasswordButton.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.className = 'bi bi-eye';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.className = 'bi bi-eye-slash';
                }
            });

            const form = document.querySelector('form');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                if (navigator.onLine) {
                    form.submit(); // Если соединение есть, отправляем форму
                } else {
                    alert('Нет соединения с интернетом'); // Если соединения нет, выводим сообщение
                }
            });
        </script>
</body>

</html>