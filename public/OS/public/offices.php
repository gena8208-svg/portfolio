<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
if (isset($_SESSION['username'])) {
    if ($_SESSION['office'] != 'P0') {
        header('Location: login.php');
    }
} else {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
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
    <title>Офисы компании</title>
    <link rel="icon" href="assets/img/office_32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="assets/img/office_16.png" type="image/png" sizes="16x16">
    <link rel="icon" href="assets/img/office.ico" type="image/x-icon">
    <style>
        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 1px 10px;
        }

        h1 {
            font-family: "Montserrat", sans-serif;
            font-weight: 600;
            font-size: 2em;
            text-decoration: none;

        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex flex-column mb-2">
            <h1 class="text-center">Офисы компании</h1>
            <div class="d-flex flex-column">
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addofficeModal" id="addofficeBtn" title="Добавить">
                        <i class="bi bi-plus-circle" style="font-size: 26px"></i>
                    </button>
                </div>
            </div>
            <div class="mt-2">
                <div class="table-responsive">
                    <table class="table  table-sm table-hover table-striped table-bordered" id="offices-table" style="text-align:center">
                        <thead class="table-dark">
                            <tr>
                                <th class="align-middle" scope="col" style="display:none;">#</th>
                                <th class="align-middle" scope="col">Признак</th>
                                <th class="align-middle" scope="col">Адрес</th>
                                <th class="align-middle" scope="col">Телефон</th>
                                <th class="align-middle" scope="col">#</th>

                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Модальное окно -->
    <div class="modal fade" id="addofficeModal" tabindex="-1" aria-labelledby="addofficeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addofficeModalLabel"><i class="bi bi-buildings"></i>&nbsp Добавить офис</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="addofficeModalForm">
                        <div class="mb-2">
                            <label for="name">Офис: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="priznak_office" name="priznak_office" placeholder="Введите признак офиса, например P12 " autocomplete="off" required>
                        </div>

                        <div class="mb-2">
                            <label for="adress_office">Адрес:</label>
                            <input type="text" class="form-control" id="adress_office" name="adress_office" placeholder="Введите адрес" autocomplete="off">
                        </div>

                        <div class="mb-2">
                            <label for="phone">Телефон:</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="+7xxxxxxxxxx" autocomplete="off" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="saveofficeBtn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Модальное окно редактирования-->
    <div class="modal fade" id="editofficeModal" tabindex="-1" aria-labelledby="editofficeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editofficeModalLabel"><i class="bi bi-buildings"></i>&nbsp Редактирование офиса</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="editofficeModalForm">
                        <input type="hidden" id="editofficeModalId" name="editofficeModalId"> <!-- Скрытое поле для ID -->
                        <div class="mb-2">
                            <label for="editname">Офис: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_priznak_office" name="edit_priznak_office" placeholder="Введите признак офиса, например P12 " autocomplete="off" required>
                        </div>

                        <div class="mb-2">
                            <label for="edit_adress_office">Адрес:</label>
                            <input type="text" class="form-control" id="edit_adress_office" name="edit_adress_office" placeholder="Введите адрес" autocomplete="off">
                        </div>

                        <div class="mb-2">
                            <label for="edit_phone">Телефон:</span></label>
                            <input type="text" class="form-control" id="edit_phone" name="edit_phone" placeholder="+7xxxxxxxxxx" autocomplete="off" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="updateofficeBtn">Сохранить</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8"></script>
    <script src="assets/js/offices.js"></script>
    <script>

    </script>
</body>

</html>