<?php
session_start();
include 'connection.php';
// Получение ID пользователя из GET-параметра
$user_id = $_GET['id'];

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $new_name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $new_last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $new_surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
    $new_remaining = isset($_POST['remaining']) ? intval($_POST['remaining']) : null;

    // Проверка, что хотя бы одно поле было заполнено
    if (!empty($new_name) || !empty($new_last_name) || !empty($new_surname) || $new_remaining !== null) {
        // Подключение к базе данных


        // Проверка подключения к базе данных
        if (mysqli_connect_errno()) {
            echo 'Не удалось подключиться к базе данных: ' . mysqli_connect_error();
            exit;
        }


        // Проверка, что ID пользователя был передан и является числом
        if ($user_id !== null) {
            // Получение данных о пользователе из базы данных
            $query = "SELECT DISTINCT * FROM users WHERE id = '$user_id'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                // Обновление данных о пользователе
                if (!empty($new_name)) {
                    $user['name'] = $new_name;
                }
                if (!empty($new_last_name)) {
                    $user['last_name'] = $new_last_name;
                }
                if (!empty($new_surname)) {
                    $user['surname'] = $new_surname;
                }
                if ($new_remaining !== null) {
                    $user['remaining'] = $new_remaining;
                }

                // Подготовка и выполнение запроса для обновления данных о пользователе в базе данных
                $query = "UPDATE users SET 
                            name = '" . mysqli_real_escape_string($conn, $user['name']) . "', 
                            last_name = '" . mysqli_real_escape_string($conn, $user['last_name']) . "', 
                            surname = '" . mysqli_real_escape_string($conn, $user['surname']) . "', 
                            remaining = " . intval($user['remaining']) . " 
                          WHERE id = '$user_id'";

                if (mysqli_query($conn, $query)) {
                    header("Location: admin_dashboard.php");
                    exit();
                }
                    else {
                    echo 'Ошибка при обновлении данных пользователя: ' . mysqli_error($conn);
                }
            } else {
                echo 'Пользователь с указанным ID не найден';
            }
        } else {
            echo 'Укажите ID пользователя';
        }

        // Закрытие подключения к базе данных
        mysqli_close($conn);
    } }
else {
    echo 'Хотя бы одно поле должно быть заполнено';
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить данные</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Фамилия</label>
                            <input type="text" name="last_name" id="last_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="surname">Отчество</label>
                            <input type="text" name="surname" id="surname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="remaining">Количество оставшихся тренировок</label>
                            <input type="text" name="remaining" id="remaining" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>