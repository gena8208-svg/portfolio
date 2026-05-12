<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';

if (isset($_SESSION['username'])) {
    if ($_SESSION['office'] == 'CL') {
        header('Location: client.php');
    } elseif ($_SESSION['office'] == 'CLU') {
        header('Location: clientclu.php');
    } elseif ($_SESSION['office'] == 'SK') {
        header('Location: sklad.php');
    }
} else {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&ampdisplay=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.18.3/bootstrap-table.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <link rel="icon" href="assets/img/user_32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="assets/img/user_16.png" type="image/png" sizes="16x16">
    <link rel="icon" href="assets/img/users.ico" type="image/x-icon">
    <title>Клиенты-Админ</title>
    <style>
        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 1px 10px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar fixed-top">
            <div class="container-fluid">
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div
                    class="offcanvas offcanvas-start"
                    tabindex="-1"
                    id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Меню</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="offcanvas"
                            aria-label="Закрыть"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link " aria-current="page" href="index.php"><i class="bi bi-calculator fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Касса</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#"><i class="bi bi-person-circle fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Клиенты</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="sklad.php" target="_blank"><i class="bi bi-scooter fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Отгрузка</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="#"><i class="bi bi-boxes  fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Офисы</a>
                            </li> -->
                        </ul>
                        </li>
                        </ul>

                    </div>
                </div>
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
        <div class="container mt-5">
            <div class="buttons-line justify-content-center">
                <button type="button" id="users_table" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#adduserModal" id="adduserBtn">
                    <i class="bi bi-person-plus-fill"></i> &nbsp; Добавить клиента
                </button>
            </div>
        </div>
        <div class="table-responsive ">
            <table class="table  table-sm table-hover table-striped table-bordered" id="users-table" style="text-align:center">
                <thead class="table-dark">
                    <tr>
                        <th class="align-middle" scope="col" style="display:none;">#</th>
                        <th class="align-middle" scope="col">Клиент</th>
                        <th class="align-middle" scope="col">ID</th>
                        <th class="align-middle" scope="col">Email</th>
                        <th class="align-middle" scope="col">Телефон</th>
                        <th class="align-middle" scope="col">Баланс</th>
                        <th class="align-middle" scope="col">Лимит</th>
                        <th class="align-middle" scope="col">Офис</th>
                        <th class="align-middle" scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Модальное окно -->
    <div class="modal fade" id="adduserModal" tabindex="-1" aria-labelledby="adduserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="adduserModalLabel"><i class="bi bi-person-plus-fill"></i>&nbsp Добавить клиента</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="adduserModalForm">
                        <!-- <input type="hidden" id="adduserModalId" name="adduserModalId">  Скрытое поле для ID клиента -->

                        <div class="mb-2">
                            <label for="name">Фамилия Имя: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Введите Фамилию Имя" autocomplete="off" required>
                        </div>

                        <div class="mb-2">
                            <label for="site_id">Сайт id: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="site_id" name="site_id" placeholder="Введите сайт id" autocomplete="off" required pattern="\d*" onkeypress="return isNumberKey(event)">
                        </div>

                        <div class="mb-2">
                            <label for="phone">Телефон: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="7xxxxxxxxxx" autocomplete="off" required>
                        </div>
                        <div class="mb-2">
                            <label for="email">Email: </label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Введите email" autocomplete="off" required>
                        </div>

                        <div class="mb-2">
                            <label for="office" class="col-form-label">Офис:</label>
                            <div>
                                <select class="form-select" id="office" name="office" style="width: 50%" required>
                                    <?php
                                    $sql = " SELECT id, priznak FROM offices ORDER BY id ASC ";
                                    try {
                                        $pdo = pdo();
                                        $result = $pdo->query($sql);
                                        echo "<option value='' disabled selected>Выберите офис</option>";
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['priznak']) . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo "Ошибка: " . $e->getMessage();
                                    }

                                    ?>

                                </select>
                            </div>
                        </div>
                        <label for="password" class="col-form-label">Пароль:</label>
                        <div class="d-flex mb-2">
                            <input type="text" class="form-control me-2" id="password" name="password" autocomplete="off">
                            <button class="btn btn-sm btn-danger ms-2" title="Копировать" id="copyPasswordBtn">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="saveuserBtn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно редактирования-->
    <div class="modal fade" id="edituserModal" tabindex="-1" aria-labelledby="edituserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h1 class="modal-title fs-5" id="edituserModalLabel">Редактирование клиента</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="edituserModalForm">
                        <input type="hidden" id="edituserModalId" name="edituserModalId"> <!-- Скрытое поле для ID -->
                        <div class=" border p-3 bg-light">
                            <div class="mb-2 row">
                                <div class="col-md-6">
                                    <label for="editname">Старое имя:</label>
                                    <input type="text" class="form-control" id="editname" name="editname" autocomplete="off" required readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="newname">Новое имя:</label>
                                    <input type="text" class="form-control" id="newname" name="newname" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <div class="col-md-6">
                                    <label for="site_id">Старый ID:</label>
                                    <input type="text" class="form-control" id="editsite_id" name="editsite_id" autocomplete="off" required pattern="\d*" onkeypress="return isNumberKey(event)" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="newsite_id">Новый ID:</label>
                                    <input type="text" class="form-control" id="newsite_id" name="newsite_id" autocomplete="off" required pattern="\d*" onkeypress="return isNumberKey(event)">
                                </div>

                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="editlimit">Лимит</label>
                            <input type="text" class="form-control" id="editlimit" name="editlimit" autocomplete="off" onkeypress="return isNumberKeylimit(event)">
                        </div>
                        <div class="mb-2">
                            <label for="phone">Телефон</label>
                            <input type="text" class="form-control" id="editphone" name="editphone" autocomplete="off" required pattern="\d*" onkeypress="return isNumberKey(event)">
                        </div>
                        <div class="mb-2">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="editemail" name="editemail" autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label for="editoffice" class="col-form-label">Офис:</label>
                            <select class="form-select" id="editoffice" name="editoffice" required>
                                <option value="" disabled selected>Выберите офис</option>
                                <?php
                                $sql = " SELECT id, priznak FROM offices ORDER BY id ASC ";
                                try {
                                    $pdo = pdo();
                                    $result = $pdo->query($sql);
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . htmlspecialchars($row['id']) . "' data-type='office'>" . htmlspecialchars($row['id']) . " - " . htmlspecialchars($row['priznak']) . "</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "Ошибка: " . $e->getMessage();
                                }

                                ?>
                            </select>
                        </div>
                        <label for="password" class="col-form-label">Пароль:</label>
                        <div class="d-flex mb-2">
                            <input type="text" class="form-control me-2" id="editpassword" name="editpassword" value="<?php echo $editpassword; ?>" autocomplete="off" required>
                            <button class="btn btn-sm btn-primary" title="Генерировать" id="editgenerator-btn" name="editgenerator"><i class="bi bi-bootstrap-reboot"></i></button>
                            <button class="btn btn-sm btn-danger ms-2" title="Копировать" id="editcopyPasswordBtn">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="updateuserBtn">Сохранить</button>
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
    <!-- ИНФО ОКНО ПРЕДУПРЕЖДЕНИЕ  -->
    <div class="toast bg-warning" id="warning" style="position: absolute; top: 2%; right: 10%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">ВНИМАНИЕ!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <strong> Заполните обязательные поля!</strong>
        </div>
    </div>
    <!-- ИНФО ОКНО ОШИБКА  -->
    <div class="toast bg-danger" id="errorToast" style="position: absolute; top: 2%; right: 10%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">ОШИБКА</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close">>
            </button>
        </div>
        <div class="toast-body text-white">
            <strong> Клиент с таким site_id и номером телефона уже существует.</strong>
        </div>
    </div>
    <script>
        $(function() {
            $('#office').select2({
                dropdownParent: $('#adduserModal')

            });
        });

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        function isNumberKeylimit(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode === 43 || charCode === 45 || (charCode >= 48 && charCode <= 57)) {
                return true;
            }
            return false;
        }


        document.getElementById('editgenerator-btn').addEventListener('click', function(event) {
            event.preventDefault();

            const characters = '0123456789';
            const specialCharacters = '@#';
            let newPassword = '';


            for (let i = 0; i < 8; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                newPassword += characters[randomIndex];
            }


            const startSymbol = specialCharacters[Math.floor(Math.random() * specialCharacters.length)];
            newPassword = startSymbol + newPassword;


            const endSymbol = specialCharacters[Math.floor(Math.random() * specialCharacters.length)];
            newPassword += endSymbol;


            document.getElementById('editpassword').value = newPassword;
        });

        var table;
        var scrollY = $(window).height() - $("#users-table").offset().top - 200;
        $(document).ready(function() {
            table = $("#users-table").DataTable({
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
                    url: "users_table.php",
                    type: "POST",
                    dataType: "json",
                    dataSrc: "",

                    data: function(d) {
                        return $.extend({}, d, {});
                    }
                },
                columns: [{
                        data: "id",
                        visible: false
                    },

                    {
                        data: "name",
                        width: "20%"
                    },
                    {
                        data: "site_id",
                        width: "10%"
                    },

                    {
                        data: "email",
                        width: "10%"
                    },
                    {
                        data: "phone",
                        width: "10%"
                    },
                    {
                        data: "dolg",
                        width: "10%"
                    },
                    {
                        data: "balance_limit",
                        width: "10%"
                    },
                    {
                        data: "office",
                        width: "5%"
                    },
                    {
                        data: null,
                        defaultContent: `<button class="btn btn-primary btn-sm edit-btn"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-danger btn-sm delete-btn"><i class="bi bi-trash-fill"></i></button>`,
                        width: "10%"
                    },
                ],
                // скрываем столбец редактирования
                /*  drawCallback: function() {
                     if (office !== 'P0') {
                         table.column(7).visible(false);
                     } else {
                         table.column(7).visible(true);
                     }
                 } */
            });
        });

        $(document).ready(function() {
            $('#adduserModal').on('shown.bs.modal', function() {
                $('#adduserModalForm')[0].reset();
                const characters = '0123456789';
                let password = '';

                const startSymbol = Math.random() < 0.5 ? '@' : '#';
                password += startSymbol;

                for (let i = 0; i < 8; i++) {
                    const randomIndex = Math.floor(Math.random() * characters.length);
                    password += characters[randomIndex];
                }

                const endSymbol = startSymbol === '@' ? '#' : '@';
                password += endSymbol;

                document.getElementById('password').value = password;

            });
            $('#copyPasswordBtn').click(function(event) {
                event.preventDefault();
                // генерация пароля
                const passwordField = document.getElementById('password');
                passwordField.select();
                passwordField.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(passwordField.value)
                    .then(() => {
                        alert('Пароль скопирован в буфер обмена!');
                    })
                    .catch(err => {
                        console.error('Ошибка при копировании: ', err);
                    });
            });
            $('#saveuserBtn').click(function() {
                var name = $('#name').val().trim();
                var phone = $('#phone').val().trim();
                var password = $('#password').val().trim();
                var office = $('#office').val();

                if (!validatePhoneNumber(phone)) {
                    return;
                }
                if (name === '' || phone === '' || password === '') {
                    var toastEl = document.getElementById('warning');
                    var toast = new bootstrap.Toast(toastEl);
                    toast.show();
                    return;
                }
                if (office === null || office === '') {
                    alert('Пожалуйста, выберите офис!');
                    return;
                }
                var formData = $('#adduserModalForm').serialize();
                $.ajax({
                    type: 'POST',
                    url: 'adduser.php',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'error') {
                            var toastEl = document.getElementById('errorToast');
                            toastEl.querySelector('.toast-body').innerHTML = response.message;
                            var toast = new bootstrap.Toast(toastEl);
                            toast.show();
                        } else {
                            table.ajax.reload();
                            $('#adduserModal').modal('hide');
                            var toastEl = document.getElementById('saveToast');
                            var toast = new bootstrap.Toast(toastEl);
                            toast.show();
                        }
                    }
                });
            });
        });

        $(document).ready(function() {
            // Обработчик клика по кнопке редактирования
            $('#users-table tbody').on('click', '.edit-btn', function() {
                var id = table.row($(this).parents('tr')).data().id;
                getUserData(id);
            });

            function getUserData(id) {
                $.ajax({
                    url: 'adm_get_user_data.php',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        /*  var data = JSON.parse(response); */
                        // Заполняем поля формы данными пользователя
                        var data = response;
                        $('#edituserModalId').val(data.id);
                        $('#editname').val(data.name);
                        $('#editsite_id').val(data.site_id);
                        $('#editemail').val(data.email);
                        $('#editlimit').val(data.balance_limit)
                        $('#editphone').val(data.phone);
                        var officeId = '';
                        $('#editoffice option[data-type="office"]').each(function() {
                            if ($(this).text().includes(data.office)) {
                                officeId = $(this).val();
                                return false;
                            }
                        });
                        $('#editoffice').val(officeId);
                        $('#edituserModal').modal('show');
                        $('#editpassword').val('');
                        $('#newname').val('');
                        $('#newsite_id').val('');
                        $('#updateuserBtn').off('click').on('click', function() {
                            const updatedData = {
                                id: $('#edituserModalId').val(),
                                oldname: $('#editname').val(),
                                newname: $('#newname').val(),
                                oldsite_id: $('#editsite_id').val(),
                                newsite_id: $('#newsite_id').val(),
                                limit: $('#editlimit').val(),
                                email: $('#editemail').val(),
                                phone: $('#editphone').val(),
                                password: $('#editpassword').val(),
                                office: $('#editoffice').val(),
                            };
                            $.ajax({
                                url: 'adm_update_users.php',
                                method: 'POST',
                                data: updatedData,
                                success: function(response) {
                                    table.ajax.reload();
                                    $('#edituserModal').modal('hide');
                                    var toastEl = document.getElementById('updateToast');
                                    var toast = new bootstrap.Toast(toastEl);
                                    toast.show();
                                },
                                error: function(xhr, status, error) {
                                    console.error('Ошибка при обновлении данных:', error);
                                }
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Ошибка при получении данных пользователя:', error);
                    }
                });
            }
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

        function validatePhoneNumber() {
            const phoneInput = document.getElementById('phone');
            const phoneValue = phoneInput.value.trim();

            // Проверяем длину номера телефона
            if (phoneValue.startsWith('7')) {
                if (phoneValue.length !== 11) {
                    alert('Номер должен содержать 11 символов.');
                    return false;
                }
            } else {
                alert('Номер телефона должен начинаться с 7.');
                return false;
            }

            return true;
        }
        //определяем офис из сессии для видимости столбца редактированияв таблице
        var office = '<?php echo $_SESSION['office']; ?>';
    </script>
</body>

</html>