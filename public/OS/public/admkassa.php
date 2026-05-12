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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow">
    <title>Касса-Админ</title>
      <!-- Favicons -->
    <link rel="icon" href="assets/img/kassa_32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="assets/img/kassa_16.png" type="image/png" sizes="16x16">
    <link rel="icon" href="assets/img/kassa.ico" type="image/x-icon">

    <!-- CSS Libraries -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap-icons.min.css">
    <link href="assets/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/buttons.dataTables.min.css">
    <link href="assets/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap-table.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Fonts (локально) -->
   <style>
  @font-face {
    font-family: 'Montserrat';
    src: url('assets/fonts/Montserrat-Regular.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
}
@font-face {
    font-family: 'Montserrat';
    src: url('assets/fonts/Montserrat-Bold.ttf') format('truetype');
    font-weight: 700;
    font-style: normal;
}
</style>

    <style>
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
            margin-right: 50px;
        }
        .bi-cash { color: green; }
        .bi-credit-card-2-back { color: red; }
        .bi-arrow-left-right { color: #0d6efd; }
        .btn { white-space: nowrap; }
        .modal-dialog {
            font-size: 0.8rem;
            max-width: 400px;
        }
        .modal-dialog label {
            font-size: 0.8rem;
            padding: 0;
            margin: 0.8rem 0 0 0;
        }
        .modal-dialog input, select {
            padding: 0;
            font-size: 0.8rem;
        }
        #moneyModal label {
            font-size: 0.8rem;
            padding: 0;
            margin: 0 0 0 0;
        }
        #moneyModal input, select {
            padding: 0;
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex flex-column mb-2">
            <nav class="navbar fixed-top">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Меню</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><i class="bi bi-calculator fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Касса</a></li>
                                <li class="nav-item"><a class="nav-link" href="adm_users.php" target="_blank"><i class="bi bi-person-circle fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Клиенты</a></li>
                                <li class="nav-item"><a class="nav-link" href="sklad.php" target="_blank"><i class="bi bi-scooter fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Отгрузка</a></li>
                                <li class="nav-item"><a class="nav-link" href="offices.php" target="_blank"><i class="bi bi-buildings" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Офисы</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex align-items-center ms-auto">
                        <span class="me-2"><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <button type="button" class="btn btn-outline-primary" id="logout">
                            <i class="bi bi-door-open-fill"></i> Выход
                        </button>
                    </div>
                </div>
            </nav>
        </div>
        <div class="d-flex flex-column">
            <div class="mt-5 d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#Operation-Modal" id="addDataBtn" title="Внесение">
                        <i class="bi bi-plus-circle"></i>&nbsp; Внесение
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#Operation-Modal-Minus" id="minusDataBtn" title="Списание">
                        <i class="bi bi-dash-circle"></i>&nbsp; Списание
                    </button>
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#Operation-Modal-Zp" id="ZpDataBtn" title="Зарплата">
                        <i class="bi bi-coin"></i>&nbsp; Зарплата
                    </button>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#moneyModal"><i class="bi bi-cash-stack"></i> Купюрка</button>
                </div>
                <div class="d-flex justify-content-center flex-grow-1">
                    <div class="d-flex align-items-center border p-1 rounded" style="background-color: #f8f9fa; border-color: #007bff;">
                        <select class="form-select" id="selectfilter" name="selectfilter"></select>
                        <div class="btn-group me-3">
                            <div class="me-1"></div>
                            <button type="button" class="btn btn-sm btn-primary" id="clientfilterbtn" title="Фильтр"><i class="bi bi-funnel-fill"></i>&nbsp; Фильтр</button>
                            <button type="button" class="btn btn-sm btn-danger" id="clientfilterresetbtn" title="Отмена фильтра"><i class="bi bi-x-circle-fill"></i></button>
                        </div>
                        <label for="datetimePickerStart" class="me-1">С:</label>
                        <input type="text" class="form-control me-3" id="datetimePickerStart" placeholder="Выберите дату" style="max-width: 150px" />
                        <label for="datetimePickerEnd" class="me-1">По:</label>
                        <input type="text" class="form-control me-1" id="datetimePickerEnd" placeholder="Выберите дату" style="max-width: 150px" />
                        <div class="btn-group me-3">
                            <button type="button" class="btn btn-sm btn-primary" id="datefilterbtn" title="Фильтр"><i class="bi bi-funnel-fill"></i>&nbsp; Фильтр</button>
                            <button type="button" class="btn btn-sm btn-danger me-3" title="Сброс" id="resetFilters"><i class="bi bi-x-circle-fill"></i></button>
                        </div>
                        <select class="form-select me-1" id="officeSelect" name="officeSelect" style="max-width: 100px">
                            <option value="" disabled selected>Офис</option>
                            <?php
                            $sql = " SELECT id, priznak FROM offices WHERE priznak NOT IN ('CL', 'CLU') ORDER BY id ASC ";
                            try {
                                $pdo = pdo();
                                $result = $pdo->query($sql);
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                                    $priznak = htmlspecialchars($row['priznak'], ENT_QUOTES, 'UTF-8');
                                    echo "<option value='$id'>$priznak</option>";
                                }
                            } catch (PDOException $e) {
                                echo "Ошибка: " . $e->getMessage();
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-2">
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
                            <th class="align-middle" scope="col">Действие</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="text-itogi">
            <p><i class="bi bi-cash"></i> Нал: <span id="cashTotal">0</span></p>
            <p><i class="bi bi-calculator"></i> Касса: <span id="kassaTotal">0</span></p>
            <p><i class="bi bi-credit-card-2-back"></i> Экв: <span id="acquiringTotal">0</span></p>
            <p><i class="bi bi-arrow-left-right"></i> Перевод: <span id="transferTotal">0</span></p>
            <p>Баланс клиента: <span id="balance_user">0</span></p>
        </div>
    </div>

    <!-- Modal внесение -->
    <div class="modal fade" id="Operation-Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center bg-primary text-white">
                    <h2 class="modal-title fs-5" id="exampleModalLabel"><i class="bi bi-plus-circle fa-fw" style="font-size: 1.2rem;"></i>&nbsp; Внесение в кассу</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="addDataForm">
                        <label for="client" class="col-form-label">Клиент:</label>
                        <div class="mb-0">
                            <select class="form-select" id="client" name="client" style="width:100%">
                                <option value="" selected>Выберите клиента</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label for="officeSelectplus" class="col-form-label d-block">Офис:</label>
                            <select class="form-select" id="officeSelectplus" name="officeSelectplus" style="width: 100px">
                                <?php
                                $sql = " SELECT id, priznak FROM offices WHERE priznak NOT IN ('CL', 'CLU') ORDER BY id ASC ";
                                try {
                                    $pdo = pdo();
                                    $result = $pdo->query($sql);
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                                        $priznak = htmlspecialchars($row['priznak'], ENT_QUOTES, 'UTF-8');
                                        echo "<option value='$id'>$priznak</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "Ошибка: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-0"><label for="nal" class="col-form-label">Нал:</label><input type="number" step="1" class="form-control form-control-sm" id="nal" name="nal" /></div>
                        <div class="mb-0"><label for="kassa" class="col-form-label">Касса:</label><input type="number" step="1" class="form-control form-control-sm" id="kassa" name="kassa" /></div>
                        <div class="mb-0"><label for="ekv" class="col-form-label">Экв:</label><input type="number" step="1" class="form-control form-control-sm" id="ekv" name="ekv" /></div>
                        <div class="mb-0"><label for="transfer" class="col-form-label">Перевод:</label><input type="number" step="1" class="form-control form-control-sm" id="transfer" name="transfer" /></div>
                        <div class="mb-0"><label for="payment_account" class="col-form-label">Расчетный счет:</label><input type="number" step="1" class="form-control form-control-sm" id="payment_account" name="payment_account" /></div>
                        <div class="mb-0"><label for="comment" class="col-form-label">Коммент:</label><textarea class="form-control form-control-sm" id="comment" name="comment"></textarea></div>
                        <div class="mb-0"><label>Баланс клиента: <span id="totalSum"><strong>0</strong></span></label></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="saveOperationBtn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal списание -->
    <div class="modal fade" id="Operation-Modal-Minus" tabindex="-1" aria-labelledby="ModalMinusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center bg-danger text-white">
                    <h2 class="modal-title fs-5" id="ModalMinusLabel"><i class="bi bi-dash-circle fa-fw" style="font-size: 1.2rem;"></i>&nbsp; Списание по кассе</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="WriteoffDataForm">
                        <label for="clientminus" class="col-form-label">Клиент:</label>
                        <div class="mb-0">
                            <select class="form-select" id="clientminus" name="clientminus" style="width:100%">
                                <option value="" selected>Выберите клиента</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label for="officeSelectminus" class="col-form-label d-block">Офис:</label>
                            <select class="form-select" id="officeSelectminus" name="officeSelectminus" style="width: 100px">
                                <?php
                                $sql = " SELECT id, priznak FROM offices WHERE priznak NOT IN ('CL', 'CLU') ORDER BY id ASC ";
                                try {
                                    $pdo = pdo();
                                    $result = $pdo->query($sql);
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                                        $priznak = htmlspecialchars($row['priznak'], ENT_QUOTES, 'UTF-8');
                                        echo "<option value='$id'>$priznak</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "Ошибка: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-0"><label for="nalminus" class="col-form-label">Нал:</label><input type="number" step="1" class="form-control form-control-sm" id="nalminus" name="nalminus" /></div>
                        <div class="mb-0"><label for="kassaminus" class="col-form-label">Касса:</label><input type="number" step="1" class="form-control form-control-sm" id="kassaminus" name="kassaminus" /></div>
                        <div class="mb-0"><label for="ekvminus" class="col-form-label">Экв:</label><input type="number" step="1" class="form-control form-control-sm" id="ekvminus" name="ekvminus" /></div>
                        <div class="mb-0"><label for="transferminus" class="col-form-label">Перевод:</label><input type="number" step="1" class="form-control form-control-sm" id="transferminus" name="transferminus" /></div>
                        <div class="mb-0"><label for="payment_accountminus" class="col-form-label">Расчетный счет:</label><input type="number" step="1" class="form-control form-control-sm" id="payment_accountminus" name="payment_accountminus" /></div>
                        <div class="mb-0"><label for="commentminus" class="col-form-label">Коммент:</label><textarea class="form-control form-control-sm" id="commentminus" name="commentminus"></textarea></div>
                        <div class="mb-0"><label>Баланс клиента: <span id="totalSumMinus"><strong>0</strong></span></label></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="saveMinusOperationBtn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal редактирование -->
    <div class="modal fade" id="Operation-Modal-Edit" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center bg-warning">
                    <h2 class="modal-title fs-5" id="EditModalLabel"><i class="bi bi-plus-circle fa-fw" style="font-size: 1.2rem;"></i>&nbsp; Редактирование данных в кассе</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form id="editsumModalForm">
                        <input type="hidden" id="editrowId" name="editrowId">
                        <div class="mb-0"><label for="editclient" class="col-form-label">Клиент:</label><input type="text" class="form-control form-control-sm" id="editclient" name="editclient" disabled /></div>
                        <div class="mb-0"><label for="editsite_id" class="col-form-label">ID клиента:</label><input type="text" class="form-control form-control-sm" id="editsite_id" name="editsite_id" disabled /></div>
                        <div class="mb-0"><label for="editnal" class="col-form-label">Нал:</label><input type="number" step="1" class="form-control form-control-sm" id="editnal" name="editnal" /></div>
                        <div class="mb-0"><label for="editkassa" class="col-form-label">Касса:</label><input type="number" step="1" class="form-control form-control-sm" id="editkassa" name="editkassa" /></div>
                        <div class="mb-0"><label for="editekv" class="col-form-label">Экв:</label><input type="number" step="1" class="form-control form-control-sm" id="editekv" name="editekv" /></div>
                        <div class="mb-0"><label for="edittransfer" class="col-form-label">Перевод:</label><input type="number" step="1" class="form-control form-control-sm" id="edittransfer" name="edittransfer" /></div>
                        <div class="mb-0"><label for="editpayment_account" class="col-form-label">Расчетный счет:</label><input type="number" step="1" class="form-control form-control-sm" id="editpayment_account" name="editpayment_account" /></div>
                        <div class="mb-0"><label for="editshipment" class="col-form-label">Отгрузка:</label><input type="number" step="1" class="form-control form-control-sm" id="editshipment" name="editshipment" /></div>
                        <div class="mb-0"><label for="editcomment" class="col-form-label">Коммент:</label><textarea class="form-control form-control-sm" id="editcomment" name="editcomment"></textarea></div>
                        <div class="mb-0"><label>Баланс клиента: <span id="edittotalSum"><strong>0</strong></span></label></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="updaterowkassaBtn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно купюрки -->
    <div class="modal fade" id="moneyModal" tabindex="-1" aria-labelledby="moneyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center bg-success text-white">
                    <h2 class="modal-title fs-5" id="moneyModalLabel"><i class="bi bi-cash-stack fa-fw" style="font-size: 1.2rem;"></i>&nbsp; Купюрка</h2>
                    <button type="button" class="btn-close" style="color:ffffff" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="container text-center">
                            <div class="row justify-content-md-center align-items-center"><div class="col"><h6><i class="bi bi-cash"></i> Купюры</h6></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber5000">5000:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber5000" placeholder="Введите число"></div><div class="col"><label id="result5000">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber2000">2000:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber2000" placeholder="Введите число"></div><div class="col"><label id="result2000">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber1000">1000:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber1000" placeholder="Введите число"></div><div class="col"><label id="result1000">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber500">500:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber500" placeholder="Введите число"></div><div class="col"><label id="result500">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber200">200:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber200" placeholder="Введите число"></div><div class="col"><label id="result200">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber100">100:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber100" placeholder="Введите число"></div><div class="col"><label id="result100">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber50">50:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber50" placeholder="Введите число"></div><div class="col"><label id="result50">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber10">10:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber10" placeholder="Введите число"></div><div class="col"><label id="result10">0</label></div></div>
                            <div class="row justify-content-md-center"><div class="col"><hr></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><h6><i class="bi bi-coin"></i> Монеты</h6></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber11">10:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber11" placeholder="Введите число"></div><div class="col"><label id="result11">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber5">5:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber5" placeholder="Введите число"></div><div class="col"><label id="result5">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber2">2:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber2" placeholder="Введите число"></div><div class="col"><label id="result2">0</label></div></div>
                            <div class="row justify-content-md-center align-items-center"><div class="col"><label for="inputNumber1">1:</label></div><div class="col-6"><input type="number" class="form-control" id="inputNumber1" placeholder="Введите число"></div><div class="col"><label id="result1">0</label></div></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row justify-content-md-center align-items-center">
                        <div class="col"><label>Всего:</label><label id="summcoin">0</label></div>
                        <div class="col-auto"><button type="button" id="resetcash" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="bi bi-x-circle"></i> Очистить</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal зарплата -->
    <div class="modal fade" id="Operation-Modal-Zp" tabindex="-1" aria-labelledby="zpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center bg-body-tertiary">
                    <h2 class="modal-title fs-5" id="zpModalLabel"><i class="bi bi-coin fa-fw" style="font-size: 1.2rem;"></i>&nbsp; Выдача зарплаты</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <label for="employee" class="col-form-label">Сотрудник:</label>
                        <div class="mb-0"><select class="form-select" id="employee" name="employee" style="width:100%"><option value="" selected>Выберите сотрудника</option></select></div>
                        <div class="mb-0"><label for="transferzp" class="col-form-label">Выплата:</label><input type="number" step="1" class="form-control form-control-sm" id="transferzp" name="transferzp" /></div>
                        <div class="mb-0"><label for="transferzpplus" class="col-form-label">Пополнение:</label><input type="number" step="1" class="form-control form-control-sm" id="transferzpplus" name="transferzpplus" /></div>
                        <div class="mb-0"><label for="commentzp" class="col-form-label">Коммент:</label><textarea class="form-control form-control-sm" id="commentzp" name="commentzp"></textarea></div>
                        <div class="mb-0"><label id="excessAmountLabel" style="color:red"></label></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="saveZpBtn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Тосты -->
    <div class="toast" id="saveToast" style="position: absolute; top: 2%; right: 10%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header"><strong class="me-auto">Информация!</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div>
        <div class="toast-body"><strong>Данные успешно внесены!</strong></div>
    </div>
    <div class="toast" id="updateToast" style="position: absolute; top: 2%; right: 10%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header"><strong class="me-auto">Информация!</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div>
        <div class="toast-body"><strong>Данные успешно обновлены!</strong></div>
    </div>
    <div class="toast bg-danger" id="errorToast" style="position: absolute; top: 2%; right: 10%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header"><strong class="me-auto">ОШИБКА</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div>
        <div class="toast-body text-white"><strong>Ошибка при обновлении данных.</strong></div>
    </div>

    <!-- Скрипты -->
    <!-- jQuery и плагины -->
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/select2.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/dataTables.buttons.min.js"></script>
<script src="assets/js/buttons.html5.min.js"></script>
<script src="assets/js/buttons.print.min.js"></script>
<script src="assets/js/jszip.min.js"></script>

<!-- Bootstrap и зависимости -->
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Datepicker -->
<script src="assets/js/bootstrap-datepicker.min.js"></script>
<script src="assets/js/locales/bootstrap-datepicker.ru.min.js"></script>

<!-- SweetAlert2 -->
<script src="assets/js/sweetalert2.all.min.js"></script>

    <script>
    // ========== ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ ==========
    var table;
    var clientsData = [];

    // ========== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ==========
    function loadClients(selector, callback) {
        $.ajax({
            type: "POST",
            url: "get_user_select.php",
            data: {},
            dataType: "json",
            success: function(data) {
                if (selector) {
                    $(selector).empty().append("<option value=''>Выберите клиента</option>");
                    $.each(data, function(index, value) {
                        $(selector).append("<option value='" + value.id + "'>" + value.name + " (" + value.site_id + ")" + "</option>");
                    });
                }
                if (callback) callback(data);
            },
            error: function(xhr, status, error) {
                console.error('Ошибка при загрузке данных клиентов:', error);
            }
        });
    }

    function updateTotalSum(selector, clientId, dataSource) {
        var dolg = '';
        if (clientId) {
            $.each(dataSource, function(index, client) {
                if (client.id == clientId) dolg = client.dolg;
            });
        }
        $(selector).text(dolg || '0');
        return dolg;
    }

    function updateBalanceClass(selector, balance) {
        var $el = $(selector);
        if (balance >= 0) {
            $el.addClass('text-success').removeClass('text-danger');
        } else {
            $el.addClass('text-danger').removeClass('text-success');
        }
    }

    function showToast(toastId) {
        var toastEl = document.getElementById(toastId);
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    // ========== ИНИЦИАЛИЗАЦИЯ ==========
    $(document).ready(function() {
        // SELECT2
        $('#client').select2({ dropdownParent: $('#Operation-Modal') });
        $('#officeSelectplus').select2({ dropdownParent: $('#Operation-Modal') });
        $('#employee').select2({ dropdownParent: $('#Operation-Modal-Zp') });
        $('#clientminus').select2({ dropdownParent: $('#Operation-Modal-Minus') });
        $('#officeSelectminus').select2({ dropdownParent: $('#Operation-Modal-Minus') });
        $('#selectfilter').select2({ width: 'auto' });

        // DATEPICKER
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            language: 'ru'
        });
        $('#datetimePickerStart, #datetimePickerEnd').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            language: 'ru'
        });

        // ЗАГРУЗКА КЛИЕНТОВ В ФИЛЬТР
        loadClients("#selectfilter", function(data) {
            clientsData = data;
        });

        // ========== ВНЕСЕНИЕ ==========
       $("#addDataBtn").on("click", function() {
    $('#Operation-Modal').on('show.bs.modal', function() {
        $('#nal, #kassa, #ekv, #transfer, #comment, #payment_account').val('');
        $('#totalSum').text('0');
        $('#officeSelectplus').val($('#officeSelectplus option:first').val()).trigger('change');
    });
    loadClients("#client", function(data) {
        clientsData = data;  // ← ЭТО КЛЮЧЕВАЯ СТРОКА
    });
});

        $("#client").change(function() {
            var dolg = updateTotalSum('#totalSum', $(this).val(), clientsData);
            updateBalanceClass('#totalSum', dolg);
        });

        // ========== СПИСАНИЕ ==========
        $("#minusDataBtn").on("click", function() {
    $('#Operation-Modal-Minus').on('show.bs.modal', function() {
        $('#nalminus, #kassaminus, #ekvminus, #transferminus, #commentminus, #payment_accountminus').val('');
        $('#totalSumMinus').text('0');
        $('#officeSelectminus').val($('#officeSelectminus option:first').val()).trigger('change');
    });
    loadClients("#clientminus", function(data) {
        clientsData = data;  // ← ТОЖЕ ДОБАВЬТЕ
    });
});

$("#clientminus").change(function() {
    var dolg = updateTotalSum('#totalSumMinus', $(this).val(), clientsData);
    updateBalanceClass('#totalSumMinus', dolg);
});

        // ========== ЗАРПЛАТА ==========
        var selectedClientDolg = 0;
        var selectedClientIdZp = null;

        $('#Operation-Modal-Zp').on('show.bs.modal', function() {
            $('#transferzp, #transferzpplus, #commentzp').val('');
            $("#excessAmountLabel").text("");
            selectedClientDolg = 0;
            selectedClientIdZp = null;
        });

        $("#ZpDataBtn").on("click", function() {
            $.ajax({
                type: "POST",
                url: "get_employee_select.php",
                data: {},
                dataType: "json",
                success: function(data) {
                    $("#employee").empty().append("<option value=''>Выберите сотрудника...</option>");
                    $.each(data, function(index, value) {
                        $("#employee").append("<option value='" + value.id + "'>" + value.name + " (" + value.site_id + ")" + "</option>");
                    });

                    $("#employee").off('change').on('change', function() {
                        selectedClientIdZp = $(this).val();
                        if (selectedClientIdZp) {
                            var selectedClient = data.find(client => client.id == selectedClientIdZp);
                            if (selectedClient) {
                                selectedClientDolg = parseInt(selectedClient.dolg) || 0;
                                var limit = parseInt(selectedClient.balance_limit) || 0;
                                if (selectedClientDolg < 0 && Math.abs(selectedClientDolg) < limit) {
                                    $("#excessAmountLabel").text("Превышение лимита!");
                                } else {
                                    $("#excessAmountLabel").text("");
                                }
                            }
                        }
                    });

                    $("#transferzp").off('input').on('input', function() {
                        var transferValue = -parseInt($(this).val()) || 0;
                        var totalValue = selectedClientDolg + transferValue;
                        var limit = parseInt(data.find(c => c.id == selectedClientIdZp)?.balance_limit) || 0;
                        $("#excessAmountLabel").text(totalValue < limit ? "Превышение лимита!" : "");
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Ошибка при загрузке данных сотрудников:', error);
                }
            });
        });

        $('#saveZpBtn').on('click', function() {
            $.ajax({
                type: 'POST',
                url: 'adm_add_zp.php',
                data: {
                    id: $('#employee').val(),
                    transferzp: $('#transferzp').val(),
                    transferzpplus: $('#transferzpplus').val(),
                    commentzp: $('#commentzp').val(),
                    office: '<?php echo htmlspecialchars($_SESSION['office'], ENT_QUOTES, 'UTF-8'); ?>',
                },
                success: function() {
                    if (table) table.ajax.reload();
                    $('#Operation-Modal-Zp').modal('hide');
                    showToast('saveToast');
                }
            });
        });

        // ========== ТАБЛИЦА KASSA ==========
        var scrollY = $(window).height() - $("#kassa-table").offset().top - 200;
        table = $("#kassa-table").DataTable({
            language: { url: "assets/js/ru.json" },
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Все"]],
            order: [[1, 'desc']],
            ordering: false,
            dom: "Blfrtip",
            buttons: ["copy", "excel"],
            scrollY: scrollY + 'px',
            paging: true,
            scrollCollapse: true,
            pageLength: 50,
            autoWidth: true,
            ajax: {
                url: "kassa_table.php",
                type: "POST",
                dataType: "json",
                dataSrc: "data",
                data: function(d) { return $.extend({}, d, {}); },
                success: function(response) {
                    table.clear().rows.add(response.data).draw();
                    $('#cashTotal').text(response.totals.total_cash || 0);
                    $('#kassaTotal').text(response.totals.total_kassa || 0);
                    $('#acquiringTotal').text(response.totals.total_acquiring || 0);
                    $('#transferTotal').text(response.totals.total_transfer || 0);
                    disableEditButtonIfNotToday();
                }
            },
            columns: [
                { data: "id", visible: false },
                { data: "date", width: "10%" },
                { data: "name" },
                { data: "site_id" },
                { data: "cash" },
                { data: "kassa" },
                { data: "acquiring" },
                { data: "transfer" },
                { data: "payment_account" },
                { data: "shipment" },
                { data: "comment" },
                { data: "manager_name" },
                { data: null, defaultContent: `<button class="btn btn-primary btn-sm edit-btn"><i class="bi bi-pencil-square"></i></button><button class="btn btn-danger btn-sm delete-btn"><i class="bi bi-trash-fill"></i></button>`, width: "5%" }
            ]
        });

        $('#resetFilters').on('click', function() {
            $('#datetimePickerStart, #datetimePickerEnd').val('');
            table.ajax.reload();
        });

        // ========== РЕДАКТИРОВАНИЕ ==========
        $('#kassa-table tbody').on('click', '.edit-btn', function() {
            var id = table.row($(this).parents('tr')).data().id;
            getUserData(id);
        });

        function getUserData(id) {
            $.ajax({
                url: 'get_operation_data.php',
                method: 'POST',
                data: { id: id },
                success: function(response) {
                    var data = response;
                    $('#editrowId').val(data.id);
                    $('#editclient').val(data.name);
                    $('#editsite_id').val(data.site_id);
                    $('#editnal').val(data.cash || 0);
                    $('#editkassa').val(data.kassa || 0);
                    $('#editekv').val(data.acquiring || 0);
                    $('#edittransfer').val(data.transfer || 0);
                    $('#editpayment_account').val(data.payment_account || 0);
                    $('#editshipment').val(data.shipment || 0);
                    $('#editcomment').val(data.comment || '');

                    loadClients(null, function(clients) {
                        var dolg = 0;
                        $.each(clients, function(index, client) {
                            if (client.name == data.name) dolg = client.dolg;
                        });
                        $('#edittotalSum').text(dolg);
                        updateBalanceClass('#edittotalSum', dolg);

                        var $oldsum = parseInt($('#editnal').val()) + parseInt($('#editkassa').val()) + parseInt($('#editekv').val()) +
                            parseInt($('#edittransfer').val()) + parseInt($('#editshipment').val()) + parseInt($('#editpayment_account').val());

                        $('#Operation-Modal-Edit').modal('show');
                        $('#updaterowkassaBtn').off('click').on('click', function() {
                            $.ajax({
                                url: 'update_kassa.php',
                                method: 'POST',
                                data: {
                                    id: $('#editrowId').val(),
                                    site_id: $('#editsite_id').val(),
                                    cash: $('#editnal').val(),
                                    kassa: $('#editkassa').val(),
                                    acquiring: $('#editekv').val(),
                                    transfer: $('#edittransfer').val(),
                                    payment_account: $('#editpayment_account').val(),
                                    shipment: $('#editshipment').val(),
                                    comment: $('#editcomment').val(),
                                    oldsum: $oldsum
                                },
                                success: function(response) {
                                    if (response.status === 'success') {
                                        table.ajax.reload();
                                        $('#Operation-Modal-Edit').modal('hide');
                                        showToast('updateToast');
                                    } else {
                                        showToast('errorToast');
                                    }
                                }
                            });
                        });
                    });
                }
            });
        }

        // ========== СОХРАНЕНИЕ ВНЕСЕНИЯ ==========
     $('#saveOperationBtn').on('click', function() {
    var client = $('#client').val();
    if (!client || client === 'Выберите клиента') { alert('Выберите клиента!'); return; }
    if (!$('#nal').val() && !$('#kassa').val() && !$('#ekv').val() && !$('#transfer').val() && !$('#payment_account').val()) {
        alert('Заполните хотя бы одно поле суммы!');
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'adm_add_kassa_operation.php',
        data: {
            id: client,
            cash: $('#nal').val(),
            kassa: $('#kassa').val(),
            acquiring: $('#ekv').val(),
            transfer: $('#transfer').val(),
            payment_account: $('#payment_account').val(),
            comment: $('#comment').val(),
            manager_name: '<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>',
            office: $('#officeSelectplus option:selected').text()
        },
        success: function() {
            if (table) table.ajax.reload();
            $('#Operation-Modal').modal('hide');
            showToast('saveToast');
            
            // 🔄 ОБНОВЛЕНИЕ БАЛАНСА ПОСЛЕ ВНЕСЕНИЯ
            $.ajax({
                type: "POST",
                url: "get_user_select.php",
                data: {},
                dataType: "json",
                success: function(data) {
                    clientsData = data;
                    // Обновляем отображение баланса в модальном окне (если оно ещё как-то видно)
                    $.each(data, function(index, clientData) {
                        if (clientData.id == client) {
                            $('#totalSum').text(clientData.dolg);
                            updateBalanceClass(clientData.dolg);
                            return false;
                        }
                    });
                }
            });
        }
    });
});

        // ========== СОХРАНЕНИЕ СПИСАНИЯ ==========
        $('#saveMinusOperationBtn').on('click', function() {
    var client = $('#clientminus').val();
    if (!client || client === 'Выберите клиента') { alert('Выберите клиента!'); return; }
    if (!$('#nalminus').val() && !$('#kassaminus').val() && !$('#ekvminus').val() && !$('#transferminus').val() && !$('#payment_accountminus').val()) {
        alert('Заполните хотя бы одно поле суммы!');
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'adm_add_kassa_operation_minus.php',
        data: {
            id: client,
            nalminus: $('#nalminus').val(),
            kassaminus: $('#kassaminus').val(),
            ekvminus: $('#ekvminus').val(),
            transferminus: $('#transferminus').val(),
            payment_accountminus: $('#payment_accountminus').val(),
            commentminus: $('#commentminus').val(),
            manager_name: '<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>',
            office: $('#officeSelectminus option:selected').text()
        },
        success: function() {
            if (table) table.ajax.reload();
            $('#Operation-Modal-Minus').modal('hide');
            showToast('saveToast');
            
            // 🔄 ОБНОВЛЕНИЕ БАЛАНСА ПОСЛЕ СПИСАНИЯ
            $.ajax({
                type: "POST",
                url: "get_user_select.php",
                data: {},
                dataType: "json",
                success: function(data) {
                    clientsData = data;
                    $.each(data, function(index, clientData) {
                        if (clientData.id == client) {
                            $('#totalSumMinus').text(clientData.dolg);
                            updateBalanceMinusClass(clientData.dolg);
                            return false;
                        }
                    });
                }
            });
        }
    });
});

        // ========== УДАЛЕНИЕ ==========
        $('#kassa-table tbody').on('click', '.delete-btn', function() {
    var row = table.row($(this).parents('tr'));
    var data = row.data();
    var oldsum = parseInt(data.cash) + parseInt(data.kassa) + parseInt(data.acquiring) + parseInt(data.transfer) + parseInt(data.shipment) + parseInt(data.payment_account);
    Swal.fire({
        title: 'Вы уверены?',
        text: 'Вы действительно хотите удалить строку?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Да, удалить',
        cancelButtonText: 'Нет, отменить'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_row_kassa.php',
                method: 'POST',
                data: { id: data.id, site_id: data.site_id, oldsum: oldsum },
                success: function(response) {
                    if (response.status === 'success') {
                        table.ajax.reload();
                        Swal.fire({ title: 'Успешно', text: 'Строка удалена', icon: 'success' });
                        
                        // 🔄 ОБНОВЛЕНИЕ БАЛАНСА ПОСЛЕ УДАЛЕНИЯ
                        $.ajax({
                            type: "POST",
                            url: "get_user_select.php",
                            data: {},
                            dataType: "json",
                            success: function(clientsData) {
                                // Обновляем баланс для клиента из удалённой строки
                                $.each(clientsData, function(index, client) {
                                    if (client.site_id == data.site_id) {
                                        // Если модальное окно внесения открыто
                                        if ($('#Operation-Modal').hasClass('show')) {
                                            $('#totalSum').text(client.dolg);
                                            updateBalanceClass(client.dolg);
                                        }
                                        // Если модальное окно списания открыто
                                        if ($('#Operation-Modal-Minus').hasClass('show')) {
                                            $('#totalSumMinus').text(client.dolg);
                                            updateBalanceMinusClass(client.dolg);
                                        }
                                        return false;
                                    }
                                });
                            }
                        });
                        
                    } else {
                        Swal.fire({ title: 'Ошибка', text: 'Не удалось удалить строку', icon: 'error' });
                    }
                }
            });
        }
    });
});

        // ========== ФИЛЬТР ПО ОФИСУ ==========
        $('#officeSelect').change(function() {
            var officePriznak = $(this).find('option:selected').text();
            $.ajax({
                url: 'filter_kassa_office_table.php',
                type: 'POST',
                data: { office: officePriznak },
                dataType: 'json',
                success: function(response) {
                    if (response.data) {
                        table.clear().rows.add(response.data).draw();
                        $('#cashTotal').text(response.totals.total_cash || 0);
                        $('#kassaTotal').text(response.totals.total_kassa || 0);
                        $('#acquiringTotal').text(response.totals.total_acquiring || 0);
                        $('#transferTotal').text(response.totals.total_transfer || 0);
                    }
                }
            });
        });

        // ========== ФИЛЬТР ПО КЛИЕНТУ ==========
        $("#clientfilterbtn").click(function() {
            var selectedClientId = $("#selectfilter").val();
            if (selectedClientId) {
                $.each(clientsData, function(index, client) {
                    if (client.id == selectedClientId) {
                        $.ajax({
                            type: "POST",
                            url: "filter_client_kassa_table.php",
                            data: { site_id: client.site_id },
                            success: function(data) {
                                table.clear().rows.add(data.data || []).draw();
                                $('#cashTotal').text(data.totals?.total_cash || 0);
                                $('#kassaTotal').text(data.totals?.total_kassa || 0);
                                $('#acquiringTotal').text(data.totals?.total_acquiring || 0);
                                $('#transferTotal').text(data.totals?.total_transfer || 0);
                                $('#balance_user').text(data.totals?.total_sum || 0);
                                updateBalanceClass('#balance_user', data.totals?.total_sum);
                            }
                        });
                    }
                });
            }
        });

        $("#clientfilterresetbtn").click(function() {
            $('#balance_user').text('0');
            loadClients("#selectfilter", function(data) {
                clientsData = data;
            });
            table.ajax.reload();
        });

        // ========== ФИЛЬТР ПО ДАТАМ ==========
        $('#datefilterbtn').on('click', function() {
            $.ajax({
                url: "filter_date_kassa_table.php",
                method: "POST",
                data: {
                    datetimePickerStart: $('#datetimePickerStart').val(),
                    datetimePickerEnd: $('#datetimePickerEnd').val()
                },
                dataType: "json",
                success: function(response) {
                    table.clear().rows.add(response.data).draw();
                    $('#cashTotal').text(response.totals.total_cash || 0);
                    $('#kassaTotal').text(response.totals.total_kassa || 0);
                    $('#acquiringTotal').text(response.totals.total_acquiring || 0);
                    $('#transferTotal').text(response.totals.total_transfer || 0);
                }
            });
        });

        // ========== ВЫХОД ==========
        document.getElementById('logout').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("Вы уверены, что хотите выйти?")) {
                fetch('logout.php').then(response => {
                    if (response.redirected) window.location.href = response.url;
                }).catch(error => console.error('Ошибка:', error));
            }
        });

        // ========== ОТКЛЮЧЕНИЕ КНОПОК ДЛЯ СТАРЫХ ДАТ ==========
        function disableEditButtonIfNotToday() {
            table.column(12).nodes().to$().find('.edit-btn, .delete-btn').each(function() {
                var row = table.row($(this).closest('td').parent());
                var data = row.data();
                var date = new Date(data["date"]);
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                date.setHours(0, 0, 0, 0);
                $(this).prop('disabled', date.getTime() !== today.getTime());
            });
        }
    });

    // ========== ФУНКЦИИ ЦВЕТА БАЛАНСА (вынесены наружу для доступности) ==========
    function updateBalancClientaClass(balancevkasse) {
        var balanceElement = $('#balance_user');
        if (balancevkasse >= 0) {
            balanceElement.addClass('text-success').removeClass('text-danger');
        } else {
            balanceElement.addClass('text-danger').removeClass('text-success');
        }
    }

    // ========== КУПЮРКА (вынесена наружу, не зависит от DOM готовности) ==========
    function updateResults() {
        const nominals = {
            5000: document.getElementById('inputNumber5000'),
            2000: document.getElementById('inputNumber2000'),
            1000: document.getElementById('inputNumber1000'),
            500: document.getElementById('inputNumber500'),
            200: document.getElementById('inputNumber200'),
            100: document.getElementById('inputNumber100'),
            50: document.getElementById('inputNumber50'),
            10: document.getElementById('inputNumber10'),
            11: document.getElementById('inputNumber11'),
            5: document.getElementById('inputNumber5'),
            2: document.getElementById('inputNumber2'),
            1: document.getElementById('inputNumber1')
        };
        const resultElements = {
            5000: document.getElementById('result5000'),
            2000: document.getElementById('result2000'),
            1000: document.getElementById('result1000'),
            500: document.getElementById('result500'),
            200: document.getElementById('result200'),
            100: document.getElementById('result100'),
            50: document.getElementById('result50'),
            10: document.getElementById('result10'),
            11: document.getElementById('result11'),
            5: document.getElementById('result5'),
            2: document.getElementById('result2'),
            1: document.getElementById('result1')
        };
        let sum = 0;
        for (const nominal in nominals) {
            const inputValue = nominals[nominal].value;
            if (inputValue !== '') {
                const result = parseInt(inputValue) * (nominal === '11' ? 10 : parseInt(nominal));
                resultElements[nominal].textContent = result;
                sum += result;
            } else {
                resultElements[nominal].textContent = '0';
            }
        }
        const sumElement = document.getElementById('summcoin');
        if (sum.toString().length > 8) {
            alert('Богатая фантазия у Вас! Введите правильное число');
            sumElement.textContent = '';
        } else {
            sumElement.textContent = sum;
        }
    }

    function resetCash() {
        const inputs = document.querySelectorAll('#moneyModal input[type="number"]');
        const resultIds = ['result5000', 'result2000', 'result1000', 'result500', 'result200', 'result100', 'result50', 'result10', 'result11', 'result5', 'result2', 'result1'];
        inputs.forEach(input => input.value = '');
        resultIds.forEach(id => document.getElementById(id).textContent = '0');
        document.getElementById('summcoin').textContent = '0';
    }

    // Добавляем обработчики после загрузки DOM
    $(document).ready(function() {
        // Купюрка
        document.getElementById('inputNumber5000').addEventListener('input', updateResults);
        document.getElementById('inputNumber2000').addEventListener('input', updateResults);
        document.getElementById('inputNumber1000').addEventListener('input', updateResults);
        document.getElementById('inputNumber500').addEventListener('input', updateResults);
        document.getElementById('inputNumber200').addEventListener('input', updateResults);
        document.getElementById('inputNumber100').addEventListener('input', updateResults);
        document.getElementById('inputNumber50').addEventListener('input', updateResults);
        document.getElementById('inputNumber10').addEventListener('input', updateResults);
        document.getElementById('inputNumber11').addEventListener('input', updateResults);
        document.getElementById('inputNumber5').addEventListener('input', updateResults);
        document.getElementById('inputNumber2').addEventListener('input', updateResults);
        document.getElementById('inputNumber1').addEventListener('input', updateResults);
        document.getElementById('resetcash').addEventListener('click', resetCash);
    });
</script>
</body>

</html>