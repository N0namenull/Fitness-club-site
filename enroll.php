<?php

include 'connection.php';
session_start();
// Проверка соединения
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Проверка наличия сессии пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
}

// получаем данные о тренировке из GET-параметров
$workout_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// получаем данные о тренировке из таблицы vacant_workouts
$query = "SELECT gym_id, trainer_id, date, time, time_end, direction, status, spots FROM vacant_workouts WHERE id = '$workout_id'";
$result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));
$row = mysqli_fetch_assoc($result);

// проверяем, записан ли уже пользователь на эту тренировку
$query = "SELECT * FROM workouts WHERE user_id = '$user_id' AND id = '$workout_id'";
$result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));
$count = mysqli_num_rows($result);

// получаем данные о пользователе из таблицы users
$query = "SELECT remaining FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));
$user_row = mysqli_fetch_assoc($result);

// проверяем, что пользователь может записаться на тренировку
$current_time = time();
$workout_time = strtotime($row['date'] . ' ' . $row['time']);
$time_diff = $workout_time - $current_time;
if ($time_diff < 21600) {
    // пользователь не может записаться на тренировку менее чем за 6 часов до ее начала
    echo "Вы не можете записаться на эту тренировку менее чем за 6 часов до ее начала";
} else if ($count > 0) {
    // пользователь уже записан на тренировку
    echo "Вы уже записаны на эту тренировку";
} else if ($user_row['remaining'] < 1) {
    // у пользователя не осталось доступных тренировок
    echo "У вас больше нет доступных тренировок";
} else {
    // добавляем запись о пользователе на тренировку
    $query = "INSERT INTO workouts (user_id, id, gym_id, trainer_id, date, time, time_end, direction, status) VALUES ('$user_id', '$workout_id', '{$row['gym_id']}', '{$row['trainer_id']}', '{$row['date']}', '{$row['time']}', '{$row['time_end']}', '{$row['direction']}', '{$row['status']}')";
    $result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));
    // убавляем количество свободных мест на тренировке
    $spots = $row['spots'] - 1;
    $query = "UPDATE vacant_workouts SET spots = '$spots' WHERE id = '$workout_id'";
    $result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));
// увеличиваем количество использованных тренировок пользователя
    $remaining = $user_row['remaining'] - 1;
    $query = "UPDATE users SET remaining = '$remaining' WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));
    echo "Вы успешно записались на тренировку!";
    echo '<br>';
    echo '<a href="my_workouts.php" class="btn btn-danger">Вернуться к моим тренировкам</a>';

}

// закрываем соединение с базой данных
mysqli_close($conn);
?>