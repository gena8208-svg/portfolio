<?php
ob_start();
try {
    // Установка заголовков
    header('Content-Type: application/json; charset=utf-8');
    // Подключение конфигурации
    require_once __DIR__ . '/../config/boot.php';
    $pdo = pdo();

    // Получение параметров от DataTables
    $draw = (int)($_POST['draw'] ?? 1);
    $start = (int)($_POST['start'] ?? 0);
    $length = (int)($_POST['length'] ?? 10);
    $searchValue = $_POST['search']['value'] ?? '';
    $orderColumn = (int)($_POST['order'][0]['column'] ?? 1);
    $orderDir = $_POST['order'][0]['dir'] ?? 'desc';

    // Определение колонок для сортировки
    $columns = ['id', 'date', 'name', 'site_id', 'cash', 'kassa', 'acquiring', 'transfer'];
    $orderBy = $columns[$orderColumn] . ' ' . $orderDir;

    // Базовый запрос
    $sql = "SELECT SQL_CALC_FOUND_ROWS 
                id, date, name, site_id, cash, kassa, acquiring, transfer, 
                payment_account, shipment, comment, manager_name 
            FROM kassa";

    $params = [];
    $whereAdded = false;

    // Добавление условия поиска
    if (!empty($searchValue)) {
        $sql .= " WHERE (name LIKE :search 
              OR comment LIKE :search 
              OR date LIKE :search 
              OR site_id LIKE :search
              OR cash LIKE :search
              OR kassa LIKE :search
              OR acquiring LIKE :search
              OR transfer LIKE :search
              OR payment_account LIKE :search
              OR shipment LIKE :search
              OR manager_name LIKE :search)";
        $params[':search'] = '%' . $searchValue . '%';
        $whereAdded = true;
    }

    // Добавление сортировки и лимитов
    $sql .= " ORDER BY $orderBy LIMIT :start, :length";
    $params[':start'] = $start;
    $params[':length'] = $length;

    // Подготовка и выполнение запроса
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Получение общего количества записей
    $totalRecords = $pdo->query("SELECT COUNT(*) FROM kassa")->fetchColumn();

    // Получение количества отфильтрованных записей
    if (!empty($searchValue)) {
        $filterSql = "SELECT COUNT(*) FROM kassa WHERE (name LIKE :search OR comment LIKE :search)";
        $filterStmt = $pdo->prepare($filterSql);
        $filterStmt->bindValue(':search', '%' . $searchValue . '%');
        $filterStmt->execute();
        $filteredRecords = $filterStmt->fetchColumn();
    } else {
        $filteredRecords = $totalRecords;
    }

    // Формирование ответа
    $response = [
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data
    ];

    // Очистка буфера и вывод JSON
    ob_end_clean();
    echo json_encode($response);
    exit;
} catch (PDOException $e) {
    // Обработка ошибок БД
    ob_end_clean();
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
    exit;
} catch (Exception $e) {
    // Обработка других ошибок
    ob_end_clean();
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage()
    ]);
    exit;
}
