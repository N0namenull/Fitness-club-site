<?php
session_start();
// Подключение к базе данных
include 'connection.php';

// Проверка подключения к базе данных
if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

// Получение списка залов из базы данных
$sql = "SELECT * FROM gyms";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

$gyms = array();

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $gyms[$row["gym_id"]] = $row["adress"];
    }
}

// Получение списка тренеров из базы данных
$sql = "SELECT * FROM trainers";
$result = mysqli_query($conn, $sql);

$trainers = array();

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $trainers[$row["id"]] = $row["name"];
    }
}

// Получение списка направлений тренировок из базы данных
$sql = "SELECT * FROM directions  where status ='active'";
$result = mysqli_query($conn, $sql);

$directions = array();

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $directions[$row["id"]] = $row["direction"];
    }
}

// Обработка отправленной формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gym_id = $_POST["gym"];
    $trainer_id = $_POST["trainer"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $time_end = $_POST["time_end"];
    $direction_id = $_POST["direction"];
    $spots = $_POST["spots"];

    // Валидация данных
    if (empty($gym_id) || empty($trainer_id) || empty($date) || empty($time) || empty($direction_id)) {
        echo "Заполните все поля";
    } else {
        // Проверка на дату
        $current_date_time = strtotime(date('Y-m-d H:i:s'));
        $selected_date_time = strtotime($date . ' ' . $time);
        if ($selected_date_time < ($current_date_time + 86400)) {
            echo "Выбранная дата и время должны быть не ранее, чем через 1 день от текущего времени";
        } else {
            // Добавление тренировки в базу данных
            $workout_id = uniqid();
            $userid = $_SESSION['user_id'];
            $sql = "INSERT INTO vacant_workouts (id, gym_id, trainer_id, date, time, time_end, direction, status, spots) VALUES ('$workout_id', '$gym_id', '$trainer_id', '$date', '$time', '$time_end', '$direction_id', 'active', '$spots')";

            if (mysqli_query($conn, $sql)) {
                echo "Тренировка добавлена успешно";
            } else {
                echo "Ошибка добавления тренировки: " . mysqli_error($conn);
            }
        }
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Добавление тренировки</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Добавление тренировки</h1>
        <form method="POST">
            <div class="form-group">
                <label for="gym">Выберите зал:</label>
                <select class="form-control" id="gym" name="gym">
                    <?php foreach ($gyms as $id => $name) : ?>
                        <option value="<?= $id ?>"><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="trainer">Выберите тренера:</label>
                <div class="form-group">
                <select class="form-control" id="trainer" name="trainer">
                    <?php foreach ($trainers as $id => $name) : ?>
                        <option value="<?= $id ?>"><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="direction">Выберите направление:</label>
                <div class="form-group">
                <select class="form-control" id="direction" name="direction">
                    <?php foreach ($directions as $id => $name) : ?>
                        <option value="<?= $id ?>"><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Выберите дату:</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group">
                <label for="time">Выберите время:</label>
                <input type="time" class="form-control" id="time" name="time">
            </div>
            <div class="form-group">
                <label for="time_end">Время окончания:</label>
                <input type="time" class="form-control" id="time_end" name="time_end">
            </div>
            <div class="form-group">
                <label for="spots">Количество свободных мест:</label>
                <input type="text" class="form-control" id="spots" name="spots">
            </div>
            <button type="submit" class="btn btn-primary">Добавить тренировку</button>
        </form>
    </div>
</body>
</html>