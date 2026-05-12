<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
if (isset($_SESSION['username'])) {
    if ($_SESSION['office'] != 'V1') {
        header('Location: login.php');
    }
} else {
    header('Location: login.php');
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
    <title>Мониторинг</title>
    <link rel="icon" href="assets/img/kassa_32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="assets/img/kassa_16.png" type="image/png" sizes="16x16">
    <link rel="icon" href="assets/img/kassa.ico" type="image/x-icon">
    <style>
        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 1px 10px;
        }


        #loading-indicator {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .loading-spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

</head>

<body>
    <div class="mt-3">
        <div class="container-fluid ">
            <div class="d-flex flex-column">
                <nav class="navbar fixed-top">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center ms-auto">
                            <span class="me-2"><?php echo $_SESSION['username']; ?></span>
                            <button type="button" class="btn btn-outline-primary" id="logout">
                                <i class="bi bi-door-open-fill"></i> Выход
                            </button>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="mt-5">
            </div>
            <div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-striped" id="kassa-table" style="text-align: center">
                        <thead class="table-dark">
                            <tr>
                                <th class="align-middle" scope="col" style="display:none;">#</th>
                                <th class="align-middle" scope="col">Дата</th>
                                <th class="align-middle" scope="col">Клиент</th>
                                <th class="align-middle" scope="col">ID</th>
                                <th class="align-middle" scope="col">Нал</th>
                                <th class="align-middle" scope="col">Касса</th>
                                <th class="align-middle" scope="col">Экв</th>
                                <th class="align-middle" scope="col">Перевод</th>
                                <th class="align-middle" scope="col">Р/С</th>
                                <th class="align-middle" scope="col">Отгрузка</th>
                                <th class="align-middle" scope="col">Коммент</th>
                                <th class="align-middle" scope="col">Менеджер</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Индикатор  -->
    <div id="loading-indicator" class="text-center" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Загрузка...</span>
        </div>
        <p class="mt-2 text-white bg-dark p-2 rounded">Загрузка данных...</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ru.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8"></script>
    <script>
        //таблица касса
        var table;
        var scrollY = $(window).height() - $("#kassa-table").offset().top - 200;

        $(document).ready(function() {
            // Проверяем, есть ли таблица на странице
            if ($("#kassa-table").length) {
                table = $("#kassa-table").DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/2.1.8/i18n/ru.json",
                    },
                    lengthMenu: [
                        [25, 100, 250, 500, 1000],
                        [25, 100, 250, 500, 1000],
                    ],
                    order: [
                        [1, 'desc']
                    ],
                    ordering: true,
                    dom: "Blfrtip",
                    buttons: ["copy", "excel"],
                    scrollY: scrollY + 'px',
                    paging: true,
                    scrollCollapse: false,
                    pageLength: 1000,
                    autoWidth: true,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: "kassa-table-viewer.php",
                        type: "POST",
                        dataType: "json",
                        data: function(d) {
                            return {
                                draw: d.draw,
                                start: d.start,
                                length: d.length,
                                search: d.search, // передаем весь объект поиска
                                order: d.order
                            };
                        },
                        beforeSend: function() {
                            $("#loading-indicator").show();
                        },
                        complete: function() {
                            $("#loading-indicator").hide();
                        },
                        error: function(xhr, error, thrown) {
                            $("#loading-indicator").hide();
                            console.error("AJAX error:", error, thrown);
                            $("#kassa-table").before(
                                '<div class="alert alert-danger">Ошибка загрузки данных.</div>'
                            );
                        }
                    },
                    columns: [{
                            data: "id",
                            visible: false
                        },
                        {
                            data: "date",
                            width: "10%"
                        },
                        {
                            data: "name"
                        },
                        {
                            data: "site_id"
                        },
                        {
                            data: "cash"
                        },
                        {
                            data: "kassa"
                        },
                        {
                            data: "acquiring"
                        },
                        {
                            data: "transfer"
                        },
                        {
                            data: "payment_account"
                        },
                        {
                            data: "shipment"
                        },
                        {
                            data: "comment"
                        },
                        {
                            data: "manager_name"
                        }
                    ],
                    initComplete: function() {
                        console.log("DataTable initialization complete");
                    }
                });

            } else {
                console.error("Table #kassa-table not found on page");
            }
        });
        
        //ВЫХОД
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
    </script>
</body>

</html>