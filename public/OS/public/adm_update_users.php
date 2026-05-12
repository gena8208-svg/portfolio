<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/boot.php';
try {
    $pdo = pdo();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получаем данные из POST-запроса
    $id = $_POST['id']; // Уникальный идентификатор клиента
    $newname = $_POST['newname'];
    $newsite_id = $_POST['newsite_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $office = $_POST['office'];
    $newpassword = $_POST['password'];
    $newlimit = $_POST['limit'];

    // Если нужно обновить пароль, то хешируем его
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $hashedPassword = null; // Если пароль не обновляется, оставляем null
    }


    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT priznak FROM offices WHERE id = :office");
    $stmt->execute([':office' => $office]);
    $office_name = $stmt->fetchColumn();

    if (!$office_name) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Офис не найден.']);
        return;
    }
    //получаем старые данные клиента
    $stmt = $pdo->prepare("SELECT name, site_id, email, phone, balance_limit, office, password FROM users WHERE id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $oldname = $result['name'];
        $oldsite_id = $result['site_id'];
        $oldemail = $result['email'];
        $oldphone = $result['phone'];
        $oldlimit = $result['balance_limit'];
        $oldoffice = $result['office'];
        $oldpassword = $result['password'];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Запись не найдена ' . $e->getMessage()]);
    }

    if ($oldname != $newname && !empty($newname)) {
        $params[':name'] = $newname;
    }
    if ($oldsite_id != $newsite_id && !empty($newsite_id)) {
        $params[':site_id'] = $newsite_id;
    }
    if ($oldlimit != $newlimit || $newlimit == 0) {
        $params[':balance_limit'] = $newlimit;
    }
    if ($oldemail != $email) {
        $params[':email'] = $email;
    }
    if ($oldphone != $phone && !empty($phone)) {
        $params[':phone'] = $phone;
    }
    if ($oldoffice != $office && !empty($office_name)) {
        $params[':office'] = $office_name;
    }

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $params[':password'] = $hashedPassword;
    }


    if (!empty($params)) {
        $sql = "UPDATE users SET ";
        $sql .= implode(", ", array_map(function ($key) {
            return substr($key, 1) . " = " . $key;
        }, array_keys($params)));
        $sql .= ($hashedPassword !== null ? ", password = :password" : "") . " WHERE id = :id";
        $params[':id'] = $id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    $kassa_sql = "UPDATE kassa SET ";
    $added_fields = false;

    if ($oldname != $newname && !empty($newname)) {
        $kassa_sql .= "name = :name";
        $kassa_params[':name'] = $newname;
        $added_fields = true;
    }
    if ($oldsite_id != $newsite_id && !empty($newsite_id)) {
        if ($added_fields) {
            $kassa_sql .= ", ";
        }
        $kassa_sql .= "site_id = :site_id";
        $kassa_params[':site_id'] = $newsite_id;
        $added_fields = true;
    }

    if ($added_fields) {
        $kassa_sql .= " WHERE user_id = :id";
        $kassa_params[':id'] = $id;

        $kassa_stmt = $pdo->prepare($kassa_sql);
        $kassa_stmt->execute($kassa_params);
    } else {
        // не было изменений, поэтому нет необходимости обновлять базу данных
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => 'Клиент успешно обновлен.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
}
