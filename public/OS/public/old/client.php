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

        footer {
            position: fixed;
            bottom: 0;
            background-color: #343a40;
            color: white;
            text-align: center;
            /*  padding: 0.5rem 0; */
            width: 100%;
            left: 0px;


        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 1px 10px;
        }

        .text-itogi {
            display: flex;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .text-itogi p {
            font-family: "Montserrat", sans-serif;
            font-weight: 600;
            font-size: medium;
            text-decoration: none;
            margin-right: 20px;
        }

        p,
        span {
            font-family: "Montserrat", sans-serif;
            font-weight: 600;
            font-size: 1em;
            text-decoration: none;

        }


        .bi-cash {
            color: green;
        }

        .bi-credit-card-2-back {
            color: red;
        }

        .bi-arrow-left-right {
            color: #0d6efd;
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

            .dataTables_wrapper .dataTables_filter,
            p {
                float: none;
                text-align: left;
                font-size: 0.7rem;
                margin-top: 0;
            }

            .dataTables_wrapper .dataTables_filter input {
                border: 1px solid #aaa;
                border-radius: 3px;
                padding: 0;
                background-color: transparent;
                margin-left: 0px;
                margin-bottom: 2px;

            }

            table.dataTable tbody th,
            table.dataTable tbody td,
            table.dataTable thead th {
                padding: 1px 5px;
                font-size: 0.7rem;
            }

            label {
                width: 30px;
            }

            span {
                font-family: "Montserrat", sans-serif;
                font-weight: 600;
                font-size: 0.7rem;
                text-decoration: none;

            }

            p {
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

            footer {
                font-size: 1rem;

            }

            footer p,
            span {
                font-size: 0.7rem;
            }

            */ .d-flex.flex-column.flex-sm-row {
                align-items: flex-start;
            }

            .me-1,
            .ms-2 {
                font-size: 0.7rem;
            }

            .form-control {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
                max-width: 120px;
            }

            .btn-group {
                flex-wrap: nowrap;
            }

            .btn-group .btn {
                font-size: 0.7rem;
                padding: 0.2rem 0.5rem;
            }

            .btn-group .btn i {
                font-size: 0.8rem;
            }

            .text-itogi p {
                font-size: 0.8rem;
                margin-bottom: 0.5rem;
            }

            .text-itogi {
                font-size: 0.8rem;

            }

            .text-itogi i {
                font-size: 1rem;
            }

            .text-itogi strong {
                font-size: 0.9rem;
            }

            .bi-info-square {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar ">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <span class="me-2"><?php echo $_SESSION['username'] . ' (' . $_SESSION['site_id'] . ')'; ?></span>
                </a>
                <i class="bi bi-info-square text-primary" data-bs-toggle="modal" data-bs-target="#myModal" role="button"></i>
                <div class="d-flex align-items-center ms-auto">
                    <button
                        type="button"
                        class="btn btn-outline-primary"
                        id="logout">
                        <i class="bi bi-door-open-fill"></i> Выход
                    </button>
                </div>
            </div>
        </nav>
        <div class="mb-1">
            <span class="border p-1 rounded border-success" style="background-color: #f8f9fa; ">Баланс: <span id="totalSumup">0</span></span>
        </div>
        <div class="mb-1  border-danger">
            <span class="border p-1 rounded border-danger" style="background-color:#f8f9fa;">Лимит: <span id="limit">0</span></span>
        </div>

        <div class="d-flex flex-column">
            <div class="mt-1 mb-1">
                <div class="row">
                    <div class="col-6 col-md-4">
                        <div class="d-flex flex-column flex-sm-row align-items-center">
                            <div class="d-flex align-items-center">
                                <label for="datetimePickerStart" class="me-1">С:</label>
                                <input type="text" class="form-control me-2" id="datetimePickerStart" placeholder="Выберите дату" style="max-width: 150px" />
                            </div>
                            <div class="d-flex align-items-center">
                                <label for="datetimePickerEnd" class="me-1">ПО:</label>
                                <input type="text" class="form-control me-2" id="datetimePickerEnd" placeholder="Выберите дату" style="max-width: 150px" />
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary" id="datefilterbtn" title="Фильтр"><i class="bi bi-funnel-fill"></i>&nbsp; Фильтр</button>
                                <button type="button" class="btn btn-sm btn-danger me-3" title="Сброс" id="resetFilters"><i class="bi bi-x-circle-fill"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-hover table-striped table-bordered" id="client-table" style="text-align: center">
                <thead class="table-dark">
                    <tr>
                        <th class="align-middle" scope="col" style="display:none;">#</th>
                        <th class="align-middle" scope="col">Дата</th>
                        <th class="align-middle" scope="col">Оплата</th>
                        <th class="align-middle" scope="col">Перевод</th>
                        <th class="align-middle" scope="col">Отгрузка</th>
                        <th class="align-middle" scope="col">Коммент</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

        <div class="text-itogi">
            <p><i class="bi bi-cash"></i> Оплата: <span id="cashTotal">0</span></p>
            <p>
                <i class="bi bi-arrow-left-right"></i> Перевод:
                <span id="transferTotal">0</span>
            </p>
            <p>
                <i class="bi bi-cart4 text-success"></i> Отгрузки:
                <span id="shipmentTotal">0</span>
            </p>
            <p>
                <strong>Баланс:</strong>
                <span id="totalSum"><strong>0</strong></span>
            </p>
        </div>
        <footer>
            <div class="container">
                <p>&copy; 2024 - <span id="current-year"></span></p>
            </div>
        </footer>
    </div>
    <!--информер-->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="adduserModalLabel"><i class="bi bi-info-circle-fill text-primary"></i>&nbsp Информация</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    "Уважаемые покупатели! Сообщаем вам, что с 1 января 2025 года для всех наших клиентов устанавливается лимит баланса.
                    При достижении баланса лимита, отгрузка товара осуществляться не будет.
                    Просим вас учитывать это при планировании своих закупок и своевременно пополнять свой баланс,
                    чтобы избежать задержек в поставках. Спасибо за понимание и сотрудничество!"
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ru.min.js"></script>
    <script>
        // дата начало
        $(document).ready(function() {
            $('#datetimePickerStart').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'ru'
            });
        });
        // дата конец
        $(document).ready(function() {
            $('#datetimePickerEnd').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'ru'
            });
        });



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
                dom: 'lfrtip', // Удалены буквы 'B' (кнопки)
                scrollY: scrollY + 'px',
                paging: false,
                scrollCollapse: true,
                pageLength: 50,
                autoWidth: true,
                ajax: {
                    url: "client_table.php",
                    dataType: "json",
                    dataSrc: "data",
                    data: function(d) {
                        return $.extend({}, d, {});
                    },
                    success: function(response) {
                        table.clear().rows.add(response.data).draw();
                        $('#cashTotal').text(response.totals.total_cash || 0);
                        $('#transferTotal').text(response.totals.total_transfer || 0);
                        $('#shipmentTotal').text(response.totals.total_shipment || 0);
                        $('#totalSum').text(response.totals.total_sum || 0);
                        $('#totalSumup').text(response.totals.total_sum || 0);
                        $('#limit').text(response.totals.balance_limit || 0);
                        var totalSum = response.totals.total_sum || 0;
                        /* $('#totalSum').text(totalSum);
                        $('#totalSumup').text(totalSum);
                        $('#limit').text(balance_limit);  */
                        updateBalanceClass(totalSum);
                        updateBalanceClassup(totalSum);
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
                        data: "shipment",
                        width: "10%"
                    },
                    {
                        data: "comment",
                        width: "20%"
                    }

                ],

            });
            $('#resetFilters').on('click', function() {
                $('#datetimePickerStart').val('');
                $('#datetimePickerEnd').val('');
                table.ajax.reload();


            });
        });



        // Обработчик клика по кнопке фильтрации
        $('#datefilterbtn').on('click', function() {
            var startDate = $('#datetimePickerStart').val();
            var endDate = $('#datetimePickerEnd').val();

            // AJAX-запрос для получения отфильтрованных данных
            $.ajax({
                url: "filter_date_client_table.php",
                method: "POST",
                data: {
                    datetimePickerStart: startDate,
                    datetimePickerEnd: endDate
                },
                dataType: "json",
                success: function(response) {
                    table.clear().rows.add(response.data).draw();

                    $('#cashTotal').text(response.totals.total_cash || 0);
                    $('#transferTotal').text(response.totals.total_transfer || 0);
                    $('#shipmentTotal').text(response.totals.total_shipment || 0);
                    var totalSum = response.totals.total_sum || 0;
                    $('#totalSum').text(totalSum);
                    updateBalanceClass(totalSum);

                },
                error: function(xhr, status, error) {
                    console.error("Ошибка при загрузке данных: ", error);
                }
            });
        });


        document.getElementById('current-year').textContent = new Date().getFullYear();

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

        //цвет баланса
        const balanceElement = document.getElementById('totalSum');

        function updateBalanceClass(balance) {
            if (balance >= 0) {
                balanceElement.classList.add('text-success');
                balanceElement.classList.remove('text-danger');
            } else {
                balanceElement.classList.add('text-danger');
                balanceElement.classList.remove('text-success');
            }
        }
        const balanceElementup = document.getElementById('totalSumup');

        function updateBalanceClassup(balance) {
            if (balance >= 0) {
                balanceElementup.classList.add('text-success');
                balanceElementup.classList.remove('text-danger');
            } else {
                balanceElementup.classList.add('text-danger');
                balanceElementup.classList.remove('text-success');
            }
        }
    </script>
</body>

</html>