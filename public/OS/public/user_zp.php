<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
        crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&ampdisplay=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.18.3/bootstrap-table.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <link rel="icon" href="assets/img/favicon-32x32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="assets/img/favicon-16x16.png" type="image/png" sizes="16x16">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <title>Мой баланс</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;

        }

        .container-fluid {
            flex: 1;

        }


        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 1px 10px;
        }

        p,
        span {
            font-family: "Montserrat", sans-serif;
            font-weight: 600;
            font-size: 1em;
            text-decoration: none;

        }

        .btn {
            white-space: nowrap;
            /* Запретить перенос текста */


        }

        @media (max-width: 767px) {
            .container-fluid {
                width: 100%;
            }
        }

        @media (max-width: 380px) {

            table.dataTable tbody th,
            table.dataTable tbody td,
            table.dataTable thead th {
                padding: 1px 5px;
                font-size: 0.7rem;
            }

            p,
            span {
                font-family: "Montserrat", sans-serif;
                font-weight: 600;
                font-size: 0.7rem;
                text-decoration: none;

            }

            #logout {
                font-size: 0.7rem;
                padding: 0.2rem 0.5rem;
            }

            #logout i {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar">
            <span><?php echo $_SESSION['username']; ?></span>
            <div class="d-flex justify-content-end ">

                <button
                    type="button"
                    class="btn btn-outline-primary"
                    id="logout">
                    <i class="bi bi-door-open-fill"></i>&nbsp; Выход
                </button>
            </div>

        </nav>

        <div class="mb-2">
            <span class="border p-1 rounded border-success" style="background-color:#ffffff;">Можно взять: <span id="ostatok">0</span></span>
        </div>
        <div class="mb-2">
            <span class="border p-1 rounded border-danger" style="background-color:#f8f9fa; ">Лимит: <span id="limit">0</span></span>
        </div>
        <div class="mb-2">
            <span class="border p-1 rounded border-primary" style="background-color: #f8f9fa; ">Баланс: <span id="totalSumup">0</span></span>
        </div>


        <div class="table-responsive">
            <table class="table table-sm table-hover table-striped table-bordered" id="client-table" style="text-align: center">
                <thead class="table-dark">
                    <tr>
                        <th class="align-middle" scope="col" style="display:none;">#</th>
                        <th class="align-middle" scope="col">Дата</th>
                        <th class="align-middle" scope="col">Нал</th>
                        <th class="align-middle" scope="col">Перевод</th>
                        <th class="align-middle" scope="col">Коммент</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ru.min.js"></script>
    <script>
        var table;
        var scrollY = $(window).height() - $("#client-table").offset().top - 250;
        $(document).ready(function() {
            table = $("#client-table").DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/2.1.8/i18n/ru.json",
                },
                order: [
                    [1, 'desc']
                ],
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Все"],
                ],
                ordering: true,
                dom: "lfrtip", // Удалены буквы 'B' (кнопки)
                scrollY: scrollY + 'px',
                paging: false,
                scrollCollapse: true,
                autoWidth: true,
                ajax: {
                    url: "user_zp_table.php",
                    dataType: "json",
                    dataSrc: "data",
                    data: function(d) {
                        return $.extend({}, d, {});
                    },
                    success: function(response) {
                        table.clear().rows.add(response.data).draw();
                        $('#limit').text(response.totals.balance_limit || 0);
                        $('#totalSumup').text(response.totals.total_sum || 0);
                        $('#ostatok').text(response.totals.ostatok || 0);
                    },

                },
                columns: [{
                        data: "id",
                        visible: false
                    },

                    {
                        data: "date",
                        width: "15%"
                    },
                    {
                        data: "cash",
                        width: "10%"
                    },
                    {
                        data: "transfer",
                        width: "10%"
                    },

                    {
                        data: "comment",
                        width: "30%"
                    }

                ],

            });

        });

        document.getElementById('logout').addEventListener('click', function() {
            event.preventDefault();
            // Запрашиваем подтверждение у пользователя
            if (confirm("Вы уверены, что хотите выйти?")) {
                // Если пользователь нажал "OK", выполняем выход
                fetch('logout.php')
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url; // Перенаправляем на login.php
                        }
                    })
                    .catch(error => console.error('Ошибка:', error));
            } else {
                // Если пользователь нажал "Отмена", ничего не делаем
                console.log("Выход отменён");
            }
        });
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $(window).on('orientationchange', function() {
                window.location.reload();
            });
        }
    </script>
</body>

</html>