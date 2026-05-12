<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
if (isset($_SESSION['username']) && ($_SESSION['office'] == 'CL' || $_SESSION['office'] == 'CLU' || $_SESSION['office'] == 'SK' || $_SESSION['office'] == 'P0'
  || $_SESSION['office'] == 'V1')) {
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
  <title>Касса</title>
  <link rel="icon" href="assets/img/kassa_32.png" type="image/png" sizes="32x32">
  <link rel="icon" href="assets/img/kassa_16.png" type="image/png" sizes="16x16">
  <link rel="icon" href="assets/img/kassa.ico" type="image/x-icon">
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

    .modal-dialog input,
    select {
      padding: 0;
      font-size: 0.8rem;
    }

    #moneyModal label {
      font-size: 0.8rem;
      padding: 0;
      margin: 0 0 0 0;
    }

    #moneyModal input,
    select {
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
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Меню</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
            </div>
            <div class="offcanvas-body">
              <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="#"><i class="bi bi-calculator fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Касса</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="users.php" target="_blank"><i class="bi bi-person-circle fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Клиенты</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="sklad.php" target="_blank"><i class="bi bi-scooter fa-fw" style="font-size: 26px; vertical-align: middle;"></i>&nbsp; Отгрузка</a>
                </li>
              </ul>
            </div>
          </div>

          <div class="d-flex align-items-center ms-auto">
            <span class="me-2"><?php echo $_SESSION['username']; ?></span>
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
            <i class="bi bi-dash-circle"></i></i>&nbsp; Списание
          </button>
          <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#Operation-Modal-Zp" id="ZpDataBtn" title="Зарплата">
            <i class="bi bi-coin"></i></i>&nbsp; Зарплата
          </button>
          <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" и data-bs-target="#moneyModal"><i class="bi bi-cash-stack"></i> Купюрка</button>
        </div>
        <div class="d-flex justify-content-center flex-grow-1">
          <div class="d-flex align-items-center border p-1 rounded" style="background-color: #f8f9fa; border-color: #007bff;">
            <select class="form-select" id="selectfilter" name="selectfilter">

            </select>
            <div class="btn-group me-3">
              <div class="me-1"></div>
              <button type="button" class="btn btn-sm btn-primary" id="clientfilterbtn" title="Фильтр">
                <i class="bi bi-funnel-fill"></i>&nbsp; Фильтр
              </button>
              <button type="button" class="btn btn-sm btn-danger" id="clientfilterresetbtn" title="Отмена фильтра">
                <i class="bi bi-x-circle-fill"></i>
              </button>
            </div>
            <label for="datetimePickerStart" class="me-1">С:</label>
            <input type="text" class="form-control me-3" id="datetimePickerStart" placeholder="Выберите дату" style="max-width: 150px" />
            <label for="datetimePickerEnd" class="me-1">По:</label>
            <input type="text" class="form-control me-1" id="datetimePickerEnd" placeholder="Выберите дату" style="max-width: 150px" />
            <div class="btn-group me-1">
              <button type="button" class="btn btn-sm btn-primary" id="datefilterbtn" title="Фильтр">
                <i class="bi bi-funnel-fill"></i>&nbsp; Фильтр
              </button>
              <button type="button" class="btn btn-sm btn-danger" title="Сброс" id="resetFilters"><i class="bi bi-x-circle-fill"></i></button>
            </div>
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
      <p>
        <i class="bi bi-credit-card-2-back"></i> Экв:
        <span id="acquiringTotal">0</span>
      </p>

    </div>
  </div>
  <!-- Modal внесение -->
  <div class="modal fade" id="Operation-Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header justify-content-center bg-primary text-white">
          <h2 class="modal-title fs-5" id="exampleModalLabel"><i class="bi bi-plus-circle fa-fw" style="font-size: 1.2rem;"></i>&nbsp;
            Внесение в кассу
          </h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body">
          <form id="addDataForm">
            <label for="client" class="col-form-label">Клиент:</label>
            <div class="mb-0">
              <select class="form-select " id="client" name="client" style="width:100%">
                <option value="" selected>Выберите клиента</option>

              </select>
            </div>
            <div class="mb-0">
              <label for="nal" class="col-form-label">Нал:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="nal" name="nal" />
            </div>
            <div class="mb-0">
              <label for="kassa" class="col-form-label">Касса:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="kassa" name="kassa" />
            </div>
            <div class="mb-0">
              <label for="ekv" class="col-form-label">Экв:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="ekv" name="ekv" />
            </div>
            <div class="mb-0">
              <label for="transfer" class="col-form-label">Перевод:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="transfer" name="transfer" />
            </div>
            <div class="mb-0">
              <label for="payment_account" class="col-form-label">Расчетный счет:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="payment_account" name="payment_account" />
            </div>
            <div class="mb-0">
              <label for="comment" class="col-form-label">Коммент:</label>
              <textarea class="form-control form-control-sm" id="comment" name="comment"></textarea>
            </div>
            <div class="mb-0">
              <label>Баланс клиента: <span id="totalSum"><strong>0</strong></span></label>
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
          <button type="button" class="btn btn-primary" id="saveOperationBtn">
            Сохранить
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal списание -->
  <div class="modal fade" id="Operation-Modal-Minus" tabindex="-1" aria-labelledby="ModalMinusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header justify-content-center bg-danger text-white">
          <h2 class="modal-title fs-5" id="ModalMinusLabel"><i class="bi bi-dash-circle fa-fw" style="font-size: 1.2 rem;"></i>&nbsp;
            Списание по кассе
          </h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body">
          <form id="WriteoffDataForm">
            <label for="clientminus" class="col-form-label">Клиент:</label>
            <div class="mb-0">
              <select class="form-select " id="clientminus" name="clientminus" style="width:100%">
                <option value="" selected>Выберите клиента</option>

              </select>
            </div>
            <div class="mb-0">
              <label for="nalminus" class="col-form-label">Нал:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="nalminus" name="nalminus" />
            </div>
            <div class="mb-0">
              <label for="kassaminus" class="col-form-label">Касса:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="kassaminus" name="kassaminus" />
            </div>
            <div class="mb-0">
              <label for="ekvminus" class="col-form-label">Экв:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="ekvminus" name="ekvminus" />
            </div>
            <div class="mb-0">
              <label for="transferminus" class="col-form-label">Перевод:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="transferminus" name="transferminus" />
            </div>
            <div class="mb-0">
              <label for="payment_accountminus" class="col-form-label">Расчетный счет:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="payment_accountminus" name="payment_accountminus" />
            </div>
            <div class="mb-0">
              <label for="commentminus" class="col-form-label">Коммент:</label>
              <textarea class="form-control form-control-sm" id="commentminus" name="commentminus"></textarea>
            </div>
            <div class="mb-0">
              <label>Баланс клиента: <span id="totalSumMinus"><strong>0</strong></span></label>

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
          <button type="button" class="btn btn-primary" id="saveMinusOperationBtn">
            Сохранить
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal редактирование-->
  <div class="modal fade" id="Operation-Modal-Edit" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header justify-content-center bg-warning">
          <h2 class="modal-title fs-5" id="EditModalLabel"><i class="bi bi-plus-circle fa-fw" style="font-size: 1.2rem;"></i>&nbsp;
            Редактирование данных в кассе
          </h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body">
          <form id="editsumModalForm">
            <input type="hidden" id="editrowId" name="editrowId"> <!-- Скрытое поле для ID -->
            <div class="mb-0">
              <label for="editclient" class="col-form-label">Клиент:</label>
              <input type="text" class="form-control form-control-sm" id="editclient" name="editclient" disabled />
            </div>
            <div class="mb-0">
              <label for="editsite_id" class="col-form-label">ID клиента:</label>
              <input type="text" class="form-control form-control-sm" id="editsite_id" name="editsite_id" disabled />
            </div>
            <div class="mb-0">
              <label for="editnal" class="col-form-label">Нал:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="editnal" name="editnal" />
            </div>
            <div class="mb-0">
              <label for="editkassa" class="col-form-label">Касса:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="editkassa" name="editkassa" />
            </div>
            <div class="mb-0">
              <label for="editekv" class="col-form-label">Экв:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="editekv" name="editekv" />
            </div>
            <div class="mb-0">
              <label for="edittransfer" class="col-form-label">Перевод:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="edittransfer" name="edittransfer" />
            </div>
            <div class="mb-0">
              <label for="editpayment_account" class="col-form-label">Расчетный счет:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="editpayment_account" name="editpayment_account" />
            </div>
            <div class="mb-0">
              <label for="editshipment" class="col-form-label">Отгрузка:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="editshipment" name="editshipment" />
            </div>
            <div class="mb-0">
              <label for="editcomment" class="col-form-label">Коммент:</label>
              <textarea class="form-control form-control-sm" id="editcomment" name="editcomment"></textarea>
            </div>
            <div class="mb-0">
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
  <!-- Модальное окно купюрки-->
  <div class="modal fade" id="moneyModal" tabindex="-1" aria-labelledby="moneyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header justify-content-center bg-success text-white">
          <h2 class="modal-title fs-5" id="moneyModalLabel"><i class="bi bi-cash-stack fa-fw" style="font-size: 1.2rem;"></i>&nbsp;
            Купюрка
          </h2>
          <button type="button" class="btn-close" style="color:ffffff" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="container text-center">
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <h6><i class="bi bi-cash"></i> Купюры</h6>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber5000">5000:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber5000" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result5000">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber2000">2000:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber2000" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result2000">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber1000">1000:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber1000" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result1000">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber500">500:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber500" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result500">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber200">200:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber200" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result200">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber100">100:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber100" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result100">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber50">50:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber50" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result50">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber10">10:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber10" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result10">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center">
                <div class="col">
                  <hr>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <h6><i class="bi bi-coin"></i> Монеты</h6>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber11">10:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber11" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result11">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber5">5:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber5" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result5">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber2">2:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber2" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result2">0</label>
                </div>
              </div>
              <div class="row justify-content-md-center align-items-center">
                <div class="col">
                  <label for="inputNumber1">1:</label>
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" id="inputNumber1" placeholder="Введите число">
                </div>
                <div class="col">
                  <label id="result1">0</label>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <div class="row justify-content-md-center align-items-center">
            <div class="col">
              <label>Всего:</label>
              <label id="summcoin"> 0</label>
            </div>
            <div class="col-auto">
              <button type="button" id="resetcash" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="bi bi-x-circle"></i> Очистить</button>
            </div>
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
          <h2 class="modal-title fs-5" id="zpModalLabel"><i class="bi bi-coin fa-fw" style="font-size: 1.2rem;"></i>&nbsp;
            Выдача зарплаты
          </h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body">
          <form>
            <label for="employee" class="col-form-label">Сотрудник:</label>
            <div class="mb-0">
              <select class="form-select " id="employee" name="employee" style="width:100%">
                <option value="" selected>Выберите сотрудника</option>

              </select>
            </div>
            <div class="mb-0">
              <label for="nalzp" class="col-form-label">Нал:</label>
              <input type="number" step="1" class="form-control form-control-sm" id="nalzp" name="nalzp" />
            </div>
            <div class="mb-0">
              <label for="commentzp" class="col-form-label">Коммент:</label>
              <textarea class="form-control form-control-sm" id="commentzp" name="commentzp"></textarea>
            </div>
            <div class="mb-0">
              <label id="excessAmountLabel" style="color:red"></label>
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
          <button type="button" class="btn btn-primary" id="saveZpBtn">
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
  <!-- ИНФО ОКНО ОШИБКА  -->
  <div class="toast bg-danger" id="errorToast" style="position: absolute; top: 2%; right: 10%; z-index: 1090;" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">ОШИБКА</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close">>
      </button>
    </div>
    <div class="toast-body text-white">
      <strong> Ошибка при обновлении данных.</strong>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ru.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8"></script>

  <script>
    //список в Select поверх модального окна внесения
    $(function() {
      $('#client').select2({
        dropdownParent: $('#Operation-Modal')
      });
    });
    $(function() {
      $('#clientminus').select2({
        dropdownParent: $('#Operation-Modal-Minus')
      });
    });
    $(function() {
      $('#employee').select2({
        dropdownParent: $('#Operation-Modal-Zp')
      });
    });
    $(function() {
      $('#selectfilter').select2({
        width: 'auto',
      });
    });
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

    $(document).ready(function() {
      $.ajax({
        type: "POST",
        url: "get_user_select.php",
        data: {},
        dataType: "json",
        success: function(data) {
          clientsData = data;
          $("#selectfilter").empty();
          $("#selectfilter").append("<option value=''>Выберите клиента</option>");
          $.each(data, function(index, value) {
            $("#selectfilter").append("<option value='" + value.id + "'>" + value.name + " (" + value.site_id + ")" + "</option>");
          });
        },
        error: function(xhr, status, error) {
          console.error('Ошибка при загрузке данных клиентов:', error);
        }
      });
    });
    //заполнение селект и при выборе клиента отображение долга клиента в окне внесение.
    $(document).ready(function() {
      var clientsData = [];

      $("#addDataBtn").on("click", function() {
        $('#Operation-Modal').on('show.bs.modal', function() {
          // Очистка каждого поля по отдельности

          $('#nal').val('');
          $('#kassa').val('');
          $('#ekv').val('');
          $('#transfer').val('');
          $('#comment').val('');
          $('#payment_account').val('');
          $('#totalSum').text('0');
        });
        $.ajax({
          type: "POST",
          url: "get_user_select.php",
          data: {},
          dataType: "json",
          success: function(data) {
            clientsData = data;
            $("#client").empty();
            $("#client").append("<option value=''>Выберите клиента</option>");
            $.each(data, function(index, value) {
              $("#client").append("<option value='" + value.id + "'>" + value.name + " (" + value.site_id + ")" + "</option>");
            });
          },
          error: function(xhr, status, error) {
            console.error('Ошибка при загрузке данных клиентов:', error);
          }
        });
      });

      // Обработчик события для селекта
      $("#client").change(function() {
        var selectedClientId = $(this).val();
        var selectedClientDolg = '';


        // Ищем долг клиента в сохраненных данных
        if (selectedClientId) {
          $.each(clientsData, function(index, client) {
            if (client.id == selectedClientId) {
              selectedClientDolg = client.dolg; // Получаем долг клиента
            }
          });


          $('#totalSum').text(selectedClientDolg);

          updateBalanceClass(selectedClientDolg);

        } else {
          // Если ничего не выбрано, очищаем долг
          $('#totalSum').text('0');
        }
      });
    });

    //заполнение селект и при выборе клиента отображение долга клиента в окне списание.
    $(document).ready(function() {
      var clientsDataMinus = [];

      $("#minusDataBtn").on("click", function() {
        $('#Operation-Modal-Minus').on('show.bs.modal', function() {
          // Очистка каждого поля по отдельности

          $('#nalminus').val('');
          $('#kassaminus').val('');
          $('#ekvminus').val('');
          $('#transferminus').val('');
          $('#commentminus').val('');
          $('#payment_accountminus').val('');
          $('#totalSumMinus').text('0');

        });
        $.ajax({
          type: "POST",
          url: "get_user_select.php",
          data: {},
          dataType: "json",
          success: function(data) {
            clientsDataMinus = data;
            $("#clientminus").empty();
            $("#clientminus").append("<option value=''>Выберите клиента</option>");
            $.each(data, function(index, value) {
              $("#clientminus").append("<option value='" + value.id + "'>" + value.name + " (" + value.site_id + ")" + "</option>");
            });
          },
          error: function(xhr, status, error) {
            console.error('Ошибка при загрузке данных клиентов:', error);
          }
        });
      });

      // Обработчик события для селекта
      $("#clientminus").change(function() {
        var selectedClientId = $(this).val();
        var selectedClientDolg = '';


        // Ищем долг клиента в сохраненных данных
        if (selectedClientId) {
          $.each(clientsDataMinus, function(index, clientminus) {
            if (clientminus.id == selectedClientId) {
              selectedClientDolg = clientminus.dolg; // Получаем долг клиента
            }
          });


          $('#totalSumMinus').text(selectedClientDolg);

          updateBalanceMinusClass(selectedClientDolg);

        } else {
          // Если ничего не выбрано, очищаем долг
          $('#totalSumMinus').text('0');
        }
      });
    });

    //окно zp.
    $(document).ready(function() {
      var clientsDataZp = []; // Массив для хранения данных клиентов
      var selectedClientDolg = 0; // Переменная для хранения долга выбранного клиента
      var selectedClientId; // Переменная для хранения ID выбранного клиента
      $('#Operation-Modal-Zp').on('show.bs.modal', function() {
        // Очистка каждого поля по отдельности
        $('#nalzp').val('');
        $('#commentzp').val('');
        $("#excessAmountLabel").text("");
        selectedClientDolg = 0; // Сброс долга при открытии модального окна
        clientBalanceLimit = 0;
      });

      $("#ZpDataBtn").on("click", function() {

        $("#nalzp").prop("disabled", false);
        $.ajax({
          type: "POST",
          url: "get_employee_select.php",
          data: {},
          dataType: "json",
          success: function(data) {
            clientsDataZp = data; // Сохраняем данные клиентов
            $("#employee").empty();
            $("#employee").append("<option value=''>Выберите сотрудника...</option>");
            $.each(data, function(index, value) {
              $("#employee").append("<option value='" + value.id + "'>" + value.name + " (" + value.site_id + ")" + "</option>");
            });

            $("#employee").change(function() {
              selectedClientId = $(this).val(); // Получаем выбранный ID клиента
              if (selectedClientId) {
                var selectedClient = clientsDataZp.find(clientzp => clientzp.id == selectedClientId);
                if (selectedClient) {
                  selectedClientDolg = parseInt(selectedClient.dolg) || 0; // Получаем долг
                  var clientBalanceLimit = parseInt(selectedClient.balance_limit) || 0; // Получаем лимит
                  if (selectedClientDolg < 0 && Math.abs(selectedClientDolg < clientBalanceLimit)) {
                    $("#saveZpBtn").prop("disabled", true);
                    $("#excessAmountLabel").text("Превышение лимита!");
                    $("#nalzp").prop("disabled", true);

                  } else {
                    $("#saveZpBtn").prop("disabled", false);
                    $("#excessAmountLabel").text("");
                    $("#nalzp").prop("disabled", false);

                  }
                }
              }
            });

            $("#nalzp").on("input", function() {
              var nalzpValue = -parseInt($(this).val()) || 0; // Обработка NaN
              var totalValue = selectedClientDolg + nalzpValue;
              /*      var clientBalanceLimit = selectedClientId ? parseInt(clientsDataZp.find(clientzp => clientzp.id == selectedClientId).balance_limit) || 0 : 0; */
              var clientBalanceLimit = parseInt(clientsDataZp.find(clientzp => clientzp.id == selectedClientId).balance_limit) || 0;
              if (totalValue < clientBalanceLimit) {
                $("#saveZpBtn").prop("disabled", true);
                $("#excessAmountLabel").text("Превышение лимита!");
                /* $("#nalzp").prop("disabled", true); */
              } else {
                $("#saveZpBtn").prop("disabled", false);
                $("#excessAmountLabel").text("");
                /*  $("#nalzp").prop("disabled", false); */
              }
            });
          },
          error: function(xhr, status, error) {
            console.error('Ошибка при загрузке данных клиентов:', error);
          }
        });
      });
    });
    //Сохраняем зп
    $('#saveZpBtn').on('click', function() {
      var employee = $('#employee').val();
      var nalzp = $('#nalzp').val();
      var comment = $('#commentzp').val();


      if (employee === '' || employee === 'Выберите сотрудника') {
        alert('Пожалуйста, выберите клиента!');
        return;
      }
      if (nalzp === '' || nalzp === '0') {
        var toastEl = document.getElementById('warning');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
        return;
      }
      $.ajax({
        type: 'POST',
        url: 'add_zp.php',
        data: {
          id: employee,
          nalzp: nalzp,
          comment: comment,
          manager_name: '<?php echo $_SESSION['username']; ?>',
          office: '<?php echo $_SESSION['office']; ?>'

        },
        success: function(data) {
          table.ajax.reload();
          $('#Operation-Modal-Zp').modal('hide');
          var toastEl = document.getElementById('saveToast');
          var toast = new bootstrap.Toast(toastEl);
          toast.show();
        }
      });
    });

    //таблица касса
    var table;
    var scrollY = $(window).height() - $("#kassa-table").offset().top - 200;
    $(document).ready(function() {
      table = $("#kassa-table").DataTable({
        language: {
          url: "https://cdn.datatables.net/plug-ins/2.1.8/i18n/ru.json",
        },
        lengthMenu: [
          [5, 10, 25, 50, -1],
          [5, 10, 25, 50, "Все"],
        ],

        order: [
          [1, 'desc']
        ],
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

          data: function(d) {
            return $.extend({}, d, {});
          },
          success: function(response) {
            table.clear().rows.add(response.data).draw();
            $('#cashTotal').text(response.totals.total_cash || 0);
            $('#kassaTotal').text(response.totals.total_kassa || 0);
            $('#acquiringTotal').text(response.totals.total_acquiring || 0);
            /* $('#transferTotal').text(response.totals.total_transfer || 0); */
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
            data: "site_id",
          },

          {
            data: "cash",
          },
          {
            data: "kassa",
          },
          {
            data: "acquiring",
          },
          {
            data: "transfer",
          },
          {
            data: "payment_account",
          },
          /*  {
              data: "shipment",
            },*/
          {
            data: "comment",
          },
          {
            data: "manager_name"
          },
          {
            data: null,
            defaultContent: `<button class="btn btn-primary btn-sm edit-btn"><i class="bi bi-pencil-square"></i></button>
                   <button class="btn btn-danger btn-sm delete-btn"><i class="bi bi-trash-fill"></i></button>`,
            width: "5%"
          },
        ],
        // функция отображения столбца по офису. офис определяется в скрипте ниже.
        drawCallback: function() {
          disableEditButtonIfNotToday();
        },


      });
      $('#resetFilters').on('click', function() {
        $('#datetimePickerStart').val('');
        $('#datetimePickerEnd').val('');
        table.ajax.reload();
      });
    });

    // Редактирование сумм в строке
    $(document).ready(function() {
      // Обработчик клика по кнопке редактирования
      $('#kassa-table tbody').on('click', '.edit-btn', function() {
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
            $('#editnal').val(data.cash || 0);
            $('#editkassa').val(data.kassa || 0);
            $('#editekv').val(data.acquiring || 0);
            $('#edittransfer').val(data.transfer || 0);
            $('#editpayment_account').val(data.payment_account || 0);
            $('#editshipment').val(data.shipment || 0);
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

                var $oldsum = parseInt($('#editnal').val()) + parseInt($('#editkassa').val()) + parseInt($('#editekv').val()) +
                  parseInt($('#edittransfer').val()) + parseInt($('#editshipment').val()) + parseInt($('#editpayment_account').val());

                $('#Operation-Modal-Edit').modal('show');
                $('#updaterowkassaBtn').off('click').on('click', function() {
                  const updatedData = {

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
                  };
                  $.ajax({
                    url: 'update_kassa.php',
                    method: 'POST',
                    data: updatedData,
                    success: function(response) {
                      if (response.status === 'success') {
                        table.ajax.reload();
                        $('#Operation-Modal-Edit').modal('hide');
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

    // сохранение операции внесение
    $('#saveOperationBtn').on('click', function() {
      var client = $('#client').val();
      var nal = $('#nal').val();
      var kassa = $('#kassa').val();
      var ekv = $('#ekv').val();
      var transfer = $('#transfer').val();
      var payment_account = $('#payment_account').val();
      var comment = $('#comment').val();

      if (client === '' || client === 'Выберите клиента') {
        alert('Пожалуйста, выберите клиента!');
        return;
      }
      if (nal === '' && kassa === '' && ekv === '' && transfer === '' && payment_account === '') {
        alert('Пожалуйста, заполните хотябы одно поле для суммы!');
        return;
      }


      $.ajax({
        type: 'POST',
        url: 'add_kassa_operation.php',
        data: {
          id: client,
          site_id: '',
          user_id: '',
          cash: nal,
          kassa: kassa,
          acquiring: ekv,
          transfer: transfer,
          payment_account: payment_account,
          comment: comment,
          manager_name: '<?php echo $_SESSION['username']; ?>',
          office: '<?php echo $_SESSION['office']; ?>'


        },
        success: function(data) {
          table.ajax.reload();
          $('#Operation-Modal').modal('hide');
          var toastEl = document.getElementById('saveToast');
          var toast = new bootstrap.Toast(toastEl);
          toast.show();
        }
      });
    });

    // сохранение операции СПИСАНИЕ
    $('#saveMinusOperationBtn').on('click', function() {
      var clientminus = $('#clientminus').val();
      var nalminus = $('#nalminus').val();
      var kassaminus = $('#kassaminus').val();
      var ekvminus = $('#ekvminus').val();
      var transferminus = $('#transferminus').val();
      var commentminus = $('#commentminus').val();
      var payment_accountminus = $('#payment_accountminus').val();

      if (clientminus === '' || clientminus === 'Выберите клиента') {
        alert('Пожалуйста, выберите клиента!');
        return;
      }
      if (nalminus === '' && kassaminus === '' && ekvminus === '' && transferminus === '' && payment_accountminus === '') {
        alert('Пожалуйста, заполните хотябы одно поле для суммы!');
        return;
      }


      $.ajax({
        type: 'POST',
        url: 'add_kassa_operation_minus.php',
        data: {
          id: clientminus,
          site_id: '',
          user_id: '',
          nalminus: nalminus,
          kassaminus: kassaminus,
          ekvminus: ekvminus,
          transferminus: transferminus,
          payment_accountminus: payment_accountminus,
          /* shipment: shipment, */
          commentminus: commentminus,
          manager_name: '<?php echo $_SESSION['username']; ?>',
          office: '<?php echo $_SESSION['office']; ?>'


        },
        success: function(data) {
          table.ajax.reload();
          $('#Operation-Modal-Minus').modal('hide');
          var toastEl = document.getElementById('saveToast');
          var toast = new bootstrap.Toast(toastEl);
          toast.show();
        }
      });
    });

    // Обработчик клика по кнопке удаления
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
          var deleteData = {
            id: data.id,
            site_id: data.site_id,
            oldsum: oldsum
          };

          $.ajax({
            url: 'delete_row_kassa.php',
            method: 'POST',
            data: deleteData,
            success: function(response) {
              if (response.status === 'success') {
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
    // ФИЛЬТРАЦИЯ ПО ОФИСУ
    $(document).ready(function() {
      // Инициализация таблицы DataTables
      var table = $('#kassa-table').DataTable();

      // Обработчик события изменения выбора
      $('#officeSelect').change(function() {
        var office = $(this).val(); // Получаем выбранный ID офиса
        var selectedOption = $(this).find('option:selected'); // Получаем выбранный элемент
        var officeId = $(this).val(); // Получаем ID офиса
        var officePriznak = selectedOption.text();

        $.ajax({
          url: 'filter_kassa_office_table.php',
          type: 'POST', // Или 'POST', в зависимости от вашего подхода
          data: {
            office: officePriznak
          },
          dataType: 'json',
          success: function(response) {
            // Проверяем, есть ли данные в ответе
            if (response.data) {
              // Обновляем таблицу
              table.clear().rows.add(response.data).draw(); // Если вы получаете новые данные
              // или
              /*  table.ajax.reload(); */
              $('#cashTotal').text(response.totals.total_cash || 0);
              $('#kassaTotal').text(response.totals.total_kassa || 0);
              $('#acquiringTotal').text(response.totals.total_acquiring || 0);
              /*  $('#transferTotal').text(response.totals.total_transfer || 0); */
            }
          },
          error: function(xhr, status, error) {
            console.error('Ошибка AJAX:', error);
          }
        });

      });
    });
    // фильтр по клиенту 
    $("#clientfilterbtn").click(function() {
      var selectedClientId = $("#selectfilter").val();

      if (selectedClientId) {
        // Ищем клиента в сохраненных данных
        $.each(clientsData, function(index, client) {
          if (client.id == selectedClientId) {
            var siteId = client.site_id;

            // Отправляем данные на сервер для фильтрации
            $.ajax({
              type: "POST",
              url: "filter_client_kassa_table.php",
              data: {
                site_id: siteId
              },
              success: function(data) {
                // Проверяем наличие данных
                if (data.data && data.data.length > 0) {
                  table.clear().rows.add(data.data).draw();
                  // Обновляем итоговые значения
                  $('#cashTotal').text(data.totals.total_cash || 0);
                  $('#kassaTotal').text(data.totals.total_kassa || 0);
                  $('#acquiringTotal').text(data.totals.total_acquiring || 0);
                  /* $('#transferTotal').text(data.totals.total_transfer || 0); */
                } else {
                  $('#cashTotal').text(data.totals.total_cash || 0);
                  $('#kassaTotal').text(data.totals.total_kassa || 0);
                  $('#acquiringTotal').text(data.totals.total_acquiring || 0);
                  table.clear().draw();
                }


              }
            });
          }
        });
      }
    });

    $("#clientfilterresetbtn").click(function() {
      $.ajax({
        type: "POST",
        url: "get_user_select.php",
        data: {},
        dataType: "json",
        success: function(data) {
          clientsData = data;
          $("#selectfilter").empty();
          $("#selectfilter").append("<option value=''>Выберите клиента</option>");
          $.each(data, function(index, value) {
            $("#selectfilter").append("<option value='" + value.id + "'>" + value.name + " (" + value.site_id + ")" + "</option>");
          });
        },
        error: function(xhr, status, error) {
          console.error('Ошибка при загрузке данных клиентов:', error);
        }
      });
      table.ajax.reload();
    })
    $('#datefilterbtn').on('click', function() {
      var startDate = $('#datetimePickerStart').val();
      var endDate = $('#datetimePickerEnd').val();

      // AJAX-запрос для получения отфильтрованных данных
      $.ajax({
        url: "filter_date_kassa_table.php",
        method: "POST",
        data: {
          datetimePickerStart: startDate,
          datetimePickerEnd: endDate
        },
        dataType: "json",
        success: function(response) {
          table.clear().rows.add(response.data).draw();

          $('#cashTotal').text(response.totals.total_cash || 0);
          $('#kassaTotal').text(response.totals.total_kassa || 0);
          $('#acquiringTotal').text(response.totals.total_acquiring || 0);
          $('#transferTotal').text(response.totals.total_transfer || 0);
        },
        error: function(xhr, status, error) {
          console.error("Ошибка при загрузке данных: ", error);
        }
      });
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
    // меняем цвет текста баланса клиента
    const balanceElement = document.getElementById('totalSum');

    function updateBalanceClass(balance) {
      var balanceElement = $('#totalSum');
      if (balance >= 0) {
        balanceElement.addClass('text-success');
        balanceElement.removeClass('text-danger');
      } else {
        balanceElement.addClass('text-danger');
        balanceElement.removeClass('text-success');
      }
    }
    // смена цвета баланса при списании
    function updateBalanceMinusClass(balance) {
      var balanceElement = $('#totalSumMinus');
      if (balance >= 0) {
        balanceElement.addClass('text-success');
        balanceElement.removeClass('text-danger');
      } else {
        balanceElement.addClass('text-danger');
        balanceElement.removeClass('text-success');
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
      table.column(12).nodes().to$().find('.edit-btn, .delete-btn').each(function() {
        var row = table.row($(this).closest('td').parent());
        var data = row.data();
        var date = new Date(data["date"]);
        var today = new Date();
        today.setHours(0, 0, 0, 0); // сбрасываем время до 00:00:00
        date.setHours(0, 0, 0, 0); // сбрасываем время до 00:00:00
        if (date.getTime() !== today.getTime()) {
          $(this).prop('disabled', true);
        } else {
          $(this).prop('disabled', false);
        }
      });
    }


    // Купюрка

    function updateResults() {
      // Объект с номиналами и соответствующими им элементами
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

      // Объект с элементами для вывода результатов
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

      // Сумма для вывода
      let sum = 0;

      // Обновляем результаты
      for (const nominal in nominals) {
        const inputValue = nominals[nominal].value;
        if (inputValue !== '') {
          const result = parseInt(inputValue) * (nominal === '11' ? 10 : nominal);
          resultElements[nominal].textContent = result;
          sum += result;
        } else {
          resultElements[nominal].textContent = '0';
        }
      }

      // Обновляем общую сумму
      const sumElement = document.getElementById('summcoin');
      if (sum.toString().length > 8) {
        alert('Богатая фантазия у Вас! Введите правильное число');
        sumElement.textContent = '';
      } else {
        sumElement.textContent = sum;
      }
    }

    // Добавляем обработчики событий для каждого поля ввода
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


    // функция сброса формы
    function resetCash() {
      const sumElement = document.getElementById('summcoin');
      const inputs = document.querySelectorAll('input[type="number"]');
      const results = {
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

      inputs.forEach(function(input) {
        input.value = '';
      });

      Object.keys(results).forEach(function(key) {
        results[key].textContent = '0';
      });

      sumElement.textContent = '0';
    }
    // очистка полей при нажатии на кнопку очистка
    document.getElementById('resetcash').addEventListener('click', resetCash);
  </script>
</body>

</html>