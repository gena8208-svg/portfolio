<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';

if (isset($_SESSION['username'])) {
    if ($_SESSION['office'] == 'CL') {
        header('Location: client.php');
    } elseif ($_SESSION['office'] == 'CLU') {
        header('Location: clientclu.php');
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
    <title>Склад</title>
    <link rel="icon" href="assets/img/warehouse_32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="assets/img/warehouse_16.png" type="image/png" sizes="16x16">
    <link rel="icon" href="assets/img/warehouse.ico" type="image/x-icon">
    <style>
        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 3px 3px;
        }

        .text-itogi p {
            font-weight: bold;
            /* Сделать текст жирным */
            font-size: 1.5rem;
            /* Установить размер шрифта 2 rem */
        }

        .modal-dialog {
            font-size: 0.8rem;
            max-width: 400px;
        }

        .modal-dialog label {
            font-size: 0.8rem;
            padding: 0;
            margin: 0.8rem 0 0 0;
        }

        #selectfilter {
            width: auto;
            min-width: 200px;
        }



        /* .modal-dialog input,
        select {
            padding: 0;
            font-size: 0.8rem;
        } */
    </style>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar fixed-top">
            <div class="container-fluid">
                <div class="d-flex align-items-center ms-auto">
                    <span class="me-2"><?php echo $_SESSION['username']; ?></span>
                    <button
                        type="button"
                        class="btn btn-outline-primary"
                        id="logout">
                        <i class="bi bi-door-open-fill"></i>&nbsp; Выход
                    </button>
                </div>
            </div>
        </nav>

        <div class="d-flex flex-column mb-3">
            <div class="mt-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-primary" style="font-size: 1.2rem;" data-bs-toggle="modal" data-bs-target="#Shipment-Modal" id="addshipmentBtn">
                            <i class="bi bi-scooter" style="font-size: 1.2rem; font-weight: bold;"></i> &nbsp; Отгрузка
                        </button>
                        <button type="button" class="btn btn-sm btn-success" style="font-size: 1.2rem;" data-bs-toggle="modal" data-bs-target="#Return-Modal" id="returnBtn">
                            <i class="bi bi-box-arrow-left" style="font-size: 1.2rem; font-weight: bold;"></i> &nbsp; Возврат
                        </button>
                    </div>
                    <!-- Центрируемый блок -->
                    <div class="d-flex flex-grow-1 justify-content-center">
                        <div class="d-flex align-items-center border p-2 rounded" style="background-color: #f8f9fa; border-color: #007bff;">

                            <select class="form-select" id="selectfilter" name="selectfilter" style="width: 250px;">
                                <option value="" selected>Фильтр по клиенту</option>
                            </select>
                            <div class="me-2"></div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary" id="clientfilterbtn" title="Фильтр">
                                    <i class="bi bi-funnel-fill"></i>&nbsp; Фильтр
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" id="clientfilterresetbtn" title="Отмена фильтра">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="table-responsive ">
            <table class="table  table-sm table-hover table-striped table-bordered" id="sklad-table" style="text-align:center">
                <thead class="table-dark">
                    <tr>
                        <th class="align-middle" scope="col" style="display:none;">#</th>
                        <th class="align-middle" scope="col">Дата</th>
                        <th class="align-middle" scope="col">Клиент</th>
                        <th class="align-middle" scope="col">ID</th>
                        <th class="align-middle" scope="col">Отгрузка</th>
                        <th class="align-middle" scope="col">Коммент</th>
                        <th class="align-middle" scope="col">Сотрудник</th>
                        <th class="align-middle" scope="col">Действие</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="text-itogi">
            <p>
                <i class="bi bi-cash-coin"></i> Баланс клиента:
                <span id="shipmentTotal">0</span>
            </p>
        </div>
    </div>


    <!-- Modal отгрузка -->
    <div class="modal fade" id="Shipment-Modal" tabindex="-1" aria-labelledby="shipmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="shipmentModalLabel"><i class="bi bi-scooter fa-fw" style="font-size: 24px; vertical-align: middle;"></i>&nbsp;
                        Отгрузка товара
                    </h1>
                    <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Закрыть"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <form id="addDataForm">
                        <label for="client" class="col-form-label">Клиент <span class="text-danger">*</span></label>
                        <div class="mb-1">
                            <select class="form-select " id="client" name="client" style="width:100%">
                                <option value="" selected>Выберите клиента</option>

                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="shipment" class="col-form-label">Сумма отгрузки <span class="text-danger">*</span></label>
                            <input type="number" step="1" class="form-control" id="shipment" name="shipment" />
                        </div>
                        <!--  <div class="mb-1">
                            <label for="return_val" class="col-form-label text-success">Сумма возврата </label>
                            <input type="number" step="1" class="form-control" id="return_val" name="return_val" />
                        </div> -->

                        <div class="mb-1">
                            <label for="commentshipment" class="col-form-label">Коммент:</label>
                            <textarea class="form-control" id="commentshipment" name="commentshipment"></textarea>
                        </div>
                        <div class="mb-1">
                            <label>Текущий баланс клиента: <span id="dolgmodal">0</span></label>
                        </div>
                        <div class="mb-1">
                            <label>Баланс после отгрузки: <span id="dolgmodalshipment">0</span></label>&nbsp;

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy text-primary" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V2Zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6ZM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1H2Z" />
                            </svg>

                        </div>
                        <label id="excessAmountLabel" style="color:red"></label>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Отмена
                    </button>
                    <button type="button" class="btn btn-primary" id="saveShipmentBtn">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal возврат -->
    <div class="modal fade" id="Return-Modal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h1 class="modal-title fs-5" id="returnModalLabel"><i class="bi bi-box-arrow-left fa-fw" style="font-size: 24px; vertical-align: middle;"></i>&nbsp;
                        Возврат товара
                    </h1>
                    <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Закрыть"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <form id="addDataForm">
                        <label for="clientreturn" class="col-form-label">Клиент <span class="text-danger">*</span></label>
                        <div class="mb-1">
                            <select class="form-select " id="clientreturn" name="clientreturn" style="width:100%">
                                <option value="" selected>Выберите клиента</option>

                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="return_val" class="col-form-label">Сумма возврата <span class="text-danger">*</span></label>
                            <input type="number" step="1" class="form-control" id="return_val" name="return_val" />
                        </div>

                        <div class="mb-1">
                            <label for="commentreturn" class="col-form-label">Коммент:</label>
                            <textarea class="form-control" id="commentreturn" name="commentreturn"></textarea>
                        </div>
                        <div class="mb-1">
                            <label>Текущий баланс клиента: <span id="dolgmodalreturn">0</span></label>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Отмена
                    </button>
                    <button type="button" class="btn btn-primary" id="saveReturnBtn">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal редактирование-->
    <div class="modal fade" id="Shipment-Modal-Edit" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center bg-warning">
                    <h2 class="modal-title fs-5" id="EditModalLabel"><i class="bi bi-pencil-square fa-fw" style="font-size: 1.2rem;"></i>&nbsp;
                        Редактирование данных
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="editsumModalForm">
                        <input type="hidden" id="editrowId" name="editrowId"> <!-- Скрытое поле для ID -->
                        <div class="mb-1">
                            <label for="editclient" class="col-form-label">Клиент:</label>
                            <input type="text" class="form-control " id="editclient" name="editclient" disabled />
                        </div>
                        <div class="mb-1">
                            <label for="editsite_id" class="col-form-label">ID клиента:</label>
                            <input type="text" class="form-control " id="editsite_id" name="editsite_id" disabled />
                        </div>
                        <div class="mb-1">
                            <label for="editshipment" class="col-form-label text-danger">Сумма отгрузки</label>
                            <input type="number" step="1" class="form-control " id="editshipment" name="editshipment" />
                        </div>
                        <div class="mb-1">
                            <label for="editreturn_val" class="col-form-label text-success">Сумма возврата </label>
                            <input type="number" step="1" class="form-control" id="editreturn_val" name="editreturn_val" />
                        </div>
                        <div class="mb-1">
                            <label for="editcomment" class="col-form-label">Коммент:</label>
                            <textarea class="form-control" id="editcomment" name="editcomment"></textarea>
                        </div>
                        <div class="mb-1">
                            <label>Баланс клиента: <span id="edittotalSum"><strong>0</strong></span></label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Отмена
                    </button>
                    <button type="button" class="btn btn-primary" id="updaterowkassaBtn">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- ИНФО ОКНО О СОХРАНЕНИИ ДАННЫХ -->
    <div class="toast" id="saveToast" style="position: absolute; top: 2%; right: 10%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Информация!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <strong>Данные успешно внесены!</strong>
        </div>
    </div>
    <!-- ИНФО ОКНО ОБ ОБНОВЛЕННЫХ ДАННЫХ -->
    <div class="toast" id="updateToast" style="position: absolute; top: 2%; right: 10%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Информация!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <strong> Данные успешно обновлены!</strong>
        </div>
    </div>

    <!-- ИНФО ОКНО ОБ ОШИБКЕ  -->
    <div class="toast bg-warning" id="warning" style="position: absolute; top: 2%; right: 30%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">ВНИМАНИЕ!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <strong> Введите данные в обязательные поля!</strong>
        </div>
    </div>
    <!--инфо о копировании в буфер -->
    <div class="toast " id="copytoast" style="position: absolute; top: 2%; right: 30%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Копирование!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <strong>Баланс скопирован в буфер!</strong>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8"></script>
    <script>
        $(function() {
            $('#client').select2({
                dropdownParent: $('#Shipment-Modal')

            });
        });
        $(function() {
            $('#clientreturn').select2({
                dropdownParent: $('#Return-Modal')

            });
        });
        $(function() {
            $('#selectfilter').select2();
        });

        //Окно отгрузки
        $('#Shipment-Modal').on('show.bs.modal', function() {
            $("#shipment").prop("disabled", true);
            $("#saveShipmentBtn").prop("disabled", true);
            const dolgClientElement = document.querySelector('#dolgmodal');
            dolgClientElement.textContent = '0';
            dolgmodalshipment.textContent = '0';

            var selectedClientDolg = 0;
            $('#shipment').val('');
            $('#commentshipment').val('');
            $("#excessAmountLabel").text("");

        });
        $("#addshipmentBtn").on("click", function() {
            loadClients("client"); // загрузка списка клиентов     
            // Обработчик события для селекта
            $("#client").change(function() {
                $("#shipment").prop("disabled", false);
                var selectedClientId = $(this).val();
                dolgmodalshipment.textContent = '0';
                $('#shipment').val('');
                /*  var selectedClientDolg = ''; */
                // Ищем долг клиента в сохраненных данных
                if (selectedClientId) {
                    $.each(clientsData, function(index, client) {
                        if (client.id == selectedClientId) {
                            selectedClientDolg = parseInt(client.dolg);
                            var clientBalanceLimit = parseInt(client.balance_limit);

                            if (selectedClientDolg < 0 && Math.abs(selectedClientDolg) > Math.abs(clientBalanceLimit)) {
                                $("#saveShipmentBtn").prop("disabled", true);
                                $("#excessAmountLabel").text("Превышение лимита на: " + (Math.abs(selectedClientDolg) - Math.abs(clientBalanceLimit)));
                                $("#shipment").prop("disabled", true);

                            } else {
                                $("#saveShipmentBtn").prop("disabled", false);
                                $("#excessAmountLabel").text("");

                            }
                            /*  if (clientBalanceLimit === 0) {
                                 $("#saveShipmentBtn").prop("disabled", true);
                                 $("#excessAmountLabel").text("Лимит не установлен. Отгрузка заперещена!");
                                 $("#shipment").prop("disabled", true);

                             } else {
                                 $("#saveShipmentBtn").prop("disabled", false);
                                 $("#excessAmountLabel").text("");

                             } */
                        }
                    });
                    if (selectedClientDolg >= 0) {
                        $('#dolgmodal').text(selectedClientDolg);
                        $('#dolgmodal').addClass('text-success');
                        $('#dolgmodal').removeClass('text-danger');
                    } else {
                        $('#dolgmodal').text(selectedClientDolg);
                        $('#dolgmodal').addClass('text-danger');
                        $('#dolgmodal').removeClass('text-success');
                    }
                    // блокировка кнопки сохранить при превышении лимита ВРЕМЕННО ОТКЛЮЧЕНО
                    $("#shipment").on("input", function() {
                        var shipmentValue = -parseInt($(this).val());
                        var totalValue = parseInt(shipmentValue + selectedClientDolg);
                        var clientBalanceLimit = parseInt(clientsData.find(client => client.id == selectedClientId).balance_limit);

                        if ((selectedClientDolg + shipmentValue) < clientBalanceLimit) {
                            $("#saveShipmentBtn").prop("disabled", true);
                            $("#excessAmountLabel").text("Превышение лимита на: " + (clientBalanceLimit - totalValue));
                        } else {
                            $("#saveShipmentBtn").prop("disabled", false);
                            $("#excessAmountLabel").text("");
                        }
                    });
                }
            });
        });
        // конец окна отгрузки
        $('#Return-Modal').on('shown.bs.modal', function() {
            const dolgClientElement = document.querySelector('#dolgmodalreturn');
            dolgClientElement.textContent = '0';
            $('#return_val').val('');
            $('#commentreturn').val('');
            var selectedClientDolg = 0;
        });
        //Окно возврата
        $("#returnBtn").on("click", function() {
            loadClients("clientreturn"); // загрузка списка клиентов     
            // Обработчик события для селекта
            $("#clientreturn").change(function() {
                var selectedClientId = $(this).val();
                var selectedClientDolg = '';

                // Ищем долг клиента в сохраненных данных
                if (selectedClientId) {
                    $.each(clientsData, function(index, client) {
                        if (client.id == selectedClientId) {
                            selectedClientDolg = client.dolg;

                        }
                    });
                    if (selectedClientDolg >= 0) {
                        $('#dolgmodalreturn').text(selectedClientDolg);
                        $('#dolgmodalreturn').addClass('text-success');
                        $('#dolgmodalreturn').removeClass('text-danger');
                    } else {
                        $('#dolgmodalreturn').text(selectedClientDolg);
                        $('#dolgmodalreturn').addClass('text-danger');
                        $('#dolgmodalreturn').removeClass('text-success');
                    }
                }
            });
        });
        // конец окна возврата

        // Функция для загрузки клиентов
        function loadClients(selectId) {
            $.ajax({
                type: "POST",
                url: "get_user_select.php",
                data: {},
                success: function(data) {
                    clientsData = data; // Сохраняем данные в глобальную переменную
                    $("#" + selectId).empty();
                    $("#" + selectId).append("<option value=''>Выберите клиента</option>");
                    $.each(data, function(index, value) {
                        if (value.office === 'CL' || value.office === 'CLU') {
                            $("#" + selectId).append("<option value='" + value.id + "'>" + value.name + " (" + value.site_id + ")" + "</option>");
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Ошибка загрузки клиентов: ", error);
                }
            });
        }
        // Заполнение списка клиентов в селект на странице и вывод долга при выборе клиента
        $(document).ready(function() {
            loadClients("selectfilter");
            $("#selectfilter").change(function() {
                var selectedClientId = $(this).val();
                selectedClientDolg = '';

                // Ищем долг клиента в сохраненных данных
                if (selectedClientId) {
                    $.each(clientsData, function(index, client) {
                        if (client.id == selectedClientId) {
                            selectedClientDolg = client.dolg; // Получаем долг клиента
                        }
                    });
                    $('#shipmentTotal').text(selectedClientDolg);
                    updateShipmentTotal(selectedClientDolg);
                } else {
                    // Если ничего не выбрано, очищаем долг
                    $('#shipmentTotal').text('0');
                    updateShipmentTotal(0);
                }
            });
        });

        // Загрузка таблицы при открытии страницы
        var table;
        var scrollY = $(window).height() - $("#sklad-table").offset().top - 200;
        $(document).ready(function() {
            table = $("#sklad-table").DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/2.1.8/i18n/ru.json",
                },
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Все"],
                ],

                order: [
                    [0, 'desc']
                ],
                ordering: true,
                dom: "Blfrtip",
                buttons: ["copy", "excel"],
                scrollY: scrollY + 'px',
                paging: true,
                scrollCollapse: true,
                pageLength: 50,
                autoWidth: true,
                ajax: {
                    url: "sklad_table.php",
                    type: "POST",
                    dataType: "json",
                    dataSrc: "data",

                    data: function(d) {
                        return $.extend({}, d, {});
                    }
                },
                columns: [{
                        data: "id",
                        visible: false
                    },
                    {
                        data: 'date',
                        width: "15%"
                    },
                    {
                        data: "name",
                        width: "20%"

                    },
                    {
                        data: "site_id",
                        width: "5%"
                    },


                    {
                        data: "shipment",
                        width: "5%"
                    },
                    {
                        data: "comment",
                        width: "25%"
                    },
                    {
                        data: "manager_name",
                        width: "20%"

                    },
                    {
                        data: null,
                        defaultContent: `<button class="btn btn-primary btn-sm edit-btn"><i class="bi bi-pencil-square"></i></button>
                       <button class="btn btn-danger btn-sm delete-btn"><i class="bi bi-trash-fill"></i></button>`,
                        width: "5%"
                    },
                ],
                drawCallback: function() {
                    disableEditButtonIfNotToday();
                    /*  visibledelbutton(); */
                }
            });

        });
        /*<button class="btn btn-danger btn-sm delete-btn"><i class="bi bi-trash-fill"></i></button>*/
        //Сохраняем отгрузку
        $('#saveShipmentBtn').on('click', function() {
            var client = $('#client').val();
            var shipment = $('#shipment').val();
            /* var return_val = $('#return_val').val(); */
            var comment = $('#commentshipment').val();
            if (client === '' || client === 'Выберите клиента') {
                alert('Пожалуйста, выберите клиента!');
                return;
            }
            if (shipment === '' || shipment === '0') {
                var toastEl = document.getElementById('warning');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
                return;
            }
            $.ajax({
                type: 'POST',
                url: 'add_shipment.php',
                data: {
                    id: client,
                    site_id: '',
                    user_id: '',
                    shipment: shipment,
                    /* return_val: return_val, */
                    comment: comment,
                    manager_name: '<?php echo $_SESSION['username']; ?>',
                    office: '<?php echo $_SESSION['office']; ?>'

                },
                success: function(data) {
                    table.ajax.reload();
                    $('#Shipment-Modal').modal('hide');
                    loadClients("selectfilter");
                    $('#shipmentTotal').text('0');
                    var toastEl = document.getElementById('saveToast');
                    var toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            });
        });

        //Сохраняем возврат
        $('#saveReturnBtn').on('click', function() {
            var client = $('#clientreturn').val();
            /* var shipment = $('#shipment').val(); */
            var return_val = $('#return_val').val();
            var comment = $('#commentreturn').val();
            if (client === '' || client === 'Выберите клиента') {
                alert('Пожалуйста, выберите клиента!');
                return;
            }
            if (return_val === '' || return_val === '0') {
                var toastEl = document.getElementById('warning');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
                return;
            }
            $.ajax({
                type: 'POST',
                url: 'return_sklad.php',
                data: {
                    id: client,
                    site_id: '',
                    user_id: '',
                    return_val: return_val,
                    comment: comment,
                    manager_name: '<?php echo $_SESSION['username']; ?>',
                    office: '<?php echo $_SESSION['office']; ?>'

                },
                success: function(data) {
                    table.ajax.reload();
                    $('#Return-Modal').modal('hide');
                    loadClients("selectfilter");
                    $('#shipmentTotal').text('0');
                    var toastEl = document.getElementById('saveToast');
                    var toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            });
        });

        // Редактирование в таблице склад
        $(document).ready(function() {
            // Обработчик клика по кнопке редактирования
            $('#sklad-table tbody').on('click', '.edit-btn', function() {
                var id = table.row($(this).parents('tr')).data().id;
                getUserData(id);
            });

            function getUserData(id) {
                $.ajax({
                    url: 'get_operation_data.php',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {

                        // Заполняем поля формы данными пользователя
                        var data = response;

                        $('#editrowId').val(data.id);
                        $('#editclient').val(data.name);
                        $('#editsite_id').val(data.site_id);
                        if (data.shipment < 0) {
                            // $('#editshipment').val(data.shipment);
                            $('#editshipment').val(Math.abs(data.shipment));
                            $('#editreturn_val').val(0);

                        } else {
                            $('#editreturn_val').val(data.shipment);
                            $('#editshipment').val(0);

                        }
                        var $oldshipment = data.shipment;
                        $('#editcomment').val(data.comment || '');

                        // Получаем долг клиента из клиентских данных
                        var clientsData = [];
                        $.ajax({
                            type: "POST",
                            url: "get_user_select.php",
                            data: {},
                            dataType: "json",
                            success: function(data) {
                                clientsData = data;
                                var selectedClientId = $('#editclient').val();
                                var selectedClientDolg = 0;

                                // Ищем долг клиента в сохраненных данных
                                $.each(clientsData, function(index, client) {
                                    if (client.name == selectedClientId) {
                                        selectedClientDolg = client.dolg; // Получаем долг клиента
                                    }
                                });

                                $('#edittotalSum').text(selectedClientDolg);
                                updateBalanceeditClass(selectedClientDolg);

                                // var $oldsum = parseInt($('#editshipment').val()) + parseInt($('#editreturn_val').val());

                                $('#Shipment-Modal-Edit').modal('show');
                                $('#updaterowkassaBtn').off('click').on('click', function() {
                                    const updatedData = {

                                        id: $('#editrowId').val(),
                                        site_id: $('#editsite_id').val(),
                                        shipment: $('#editshipment').val(),
                                        return_val: $('#editreturn_val').val(),
                                        comment: $('#editcomment').val(),
                                        oldshipment: $oldshipment,
                                        oldbalance: selectedClientDolg,
                                        // oldsum: $oldsum
                                    };

                                    if (($('#editshipment').val() === '' || $('#editshipment').val() === '0') && ($('#editreturn_val').val() === '' || $('#editreturn_val').val() === '0')) {
                                        var toastEl = document.getElementById('warning');
                                        var toast = new bootstrap.Toast(toastEl);
                                        toast.show();
                                        return;
                                    }


                                    $.ajax({
                                        url: 'update_kassa_sklad.php',
                                        method: 'POST',
                                        data: updatedData,
                                        success: function(response) {
                                            if (response.status === 'success') {
                                                table.ajax.reload();
                                                $('#Shipment-Modal-Edit').modal('hide');
                                                loadClients("selectfilter");
                                                var toastEl = document.getElementById('updateToast');
                                                var toast = new bootstrap.Toast(toastEl);
                                                toast.show();
                                            } else {
                                                var toastEl = document.getElementById('errorToast');
                                                var toast = new bootstrap.Toast(toastEl);
                                                toast.show();
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Ошибка при обновлении данных:', error);
                                        }
                                    });
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Ошибка при загрузке данных клиентов:', error);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Ошибка при получении данных пользователя:', error);
                    }
                });
            }
        });
        // Обработчик клика по кнопке удаления
        $('#sklad-table tbody').on('click', '.delete-btn', function() {
            var row = table.row($(this).parents('tr'));
            var data = row.data();
            var oldsum = parseInt(data.shipment);
            Swal.fire({
                title: 'Вы уверены?',
                text: 'Вы действительно хотите удалить строку?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Да, удалить',
                cancelButtonText: 'Нет, отменить'
            }).then((result) => {
                if (result.isConfirmed) {
                    var deleteData = {
                        id: data.id,
                        site_id: data.site_id,
                        oldsum: oldsum,

                    };
                    $.ajax({
                        url: 'delete_row_kassa.php',
                        method: 'POST',
                        data: deleteData,
                        success: function(response) {
                            if (response.status === 'success') {
                                loadClients("selectfilter");
                                table.ajax.reload();
                                Swal.fire({
                                    title: 'Успешно',
                                    text: 'Строка удалена',
                                    icon: 'success'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Ошибка',
                                    text: 'Не удалось удалить строку',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Ошибка при удалении данных:', error);
                        }
                    });
                }
            });
        });


        $("#clientfilterbtn").click(function() {
            var selectedClientId = $("#selectfilter").val();

            if (selectedClientId) {
                // Ищем клиента в сохраненных данных
                $.each(clientsData, function(index, client) {
                    if (client.id == selectedClientId) {
                        var officeId = client.office_id;
                        var siteId = client.site_id;

                        // Отправляем данные на сервер для фильтрации
                        $.ajax({
                            type: "POST",
                            url: "filter_client_sklad.php",
                            data: {
                                office_id: officeId,
                                site_id: siteId
                            },
                            success: function(data) {
                                if (data.data && data.data.length > 0) {
                                    $('#shipmentTotal').text(data.dolg);
                                    updateShipmentTotal(data.dolg);
                                    table.clear().rows.add(data.data).draw();
                                } else {
                                    $('#shipmentTotal').text(0);
                                    updateShipmentTotal(0);
                                    table.clear().draw();
                                }

                            }
                        });
                    }
                });
            }
        });
        // Сброс фильтра по клиенту
        $("#clientfilterresetbtn").click(function() {

            loadClients("selectfilter");
            table.ajax.reload();
            $('#shipmentTotal').text('0');

        })

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

        function updateShipmentTotal(Dolg) {
            if (Dolg >= 0) {
                $('#shipmentTotal').text(Dolg);
                $('#shipmentTotal').addClass('text-success');
                $('#shipmentTotal').removeClass('text-danger');
            } else {
                $('#shipmentTotal').text(Dolg);
                $('#shipmentTotal').addClass('text-danger');
                $('#shipmentTotal').removeClass('text-success');
            }
        }
        // смена цвета баланса при редактировнаии
        function updateBalanceeditClass(balance) {
            var balanceElement = $('#edittotalSum');
            if (balance >= 0) {
                balanceElement.addClass('text-success');
                balanceElement.removeClass('text-danger');
            } else {
                balanceElement.addClass('text-danger');
                balanceElement.removeClass('text-success');
            }
        }
        // ф-ция отключения кнопки редактирования если дата не равна сегодняшней
        function disableEditButtonIfNotToday() {
            table.column(7).nodes().to$().find('.edit-btn, .delete-btn').each(function() {
                var row = table.row($(this).closest('td').parent());
                var data = row.data();
                var date = new Date(data["date"]);
                var today = new Date();

                today.setHours(0, 0, 0, 0); // сбрасываем время до 00:00:00
                date.setHours(0, 0, 0, 0); // сбрасываем время до 00:00:00
                if (date.getTime() !== today.getTime()) {
                    $(this).prop('disabled', true);
                    // $(this).removeClass('btn-primary').addClass('btn-secondary');
                } else {
                    $(this).prop('disabled', false);
                    // $(this).removeClass('btn-secondary').addClass('btn-primary');
                }

            });
        }

        function visibledelbutton() {
            table.column(7).nodes().to$().find('.delete-btn').each(function() {

                if (office !== 'P0') {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }

            });
        }

        //подсчет баланса с отгрузкой
        const shipmentInput = document.getElementById('shipment');
        const dolgModal = document.getElementById('dolgmodal');
        const dolgModalShipment = document.getElementById('dolgmodalshipment');

        shipmentInput.addEventListener('input', () => {
            const currentBalance = parseInt(dolgModal.textContent);
            let shipmentValue;
            if (shipmentInput.value === '') {
                shipmentValue = 0;
            } else {
                shipmentValue = parseInt(shipmentInput.value) * -1;
            }
            const newBalance = parseInt(currentBalance + shipmentValue);
            if (shipmentInput.value === '') {
                dolgModalShipment.textContent = currentBalance.toString();
            } else {
                dolgModalShipment.textContent = newBalance.toString();
            }
        });
        // копирование в буфер
        const copyIcon = document.querySelector('svg.bi.bi-copy');
        copyIcon.addEventListener('click', () => {
            const textToCopy = dolgModalShipment.textContent;
            navigator.clipboard.writeText(textToCopy).then(() => {
                const toast = new bootstrap.Toast(copytoast);
                toast.show();
            }).catch(err => {
                console.error('Ошибка копирования текста:', err);
            });
        });
        var office = '<?php echo $_SESSION['office']; ?>';
    </script>
</body>

</html>