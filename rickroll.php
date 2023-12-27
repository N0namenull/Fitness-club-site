<?php

include 'connection.php';
session_start();
// Проверка соединения
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Проверка наличия сессии пользователя
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.html");
}

// получаем идентификатор тренировки из GET-параметров
$workout_id = $_GET['id'];

// получаем текущий статус тренировки и количество доступных мест
$query = 'SELECT workouts.status, vacant_workouts.spots
FROM workouts
INNER JOIN vacant_workouts ON workouts.id = vacant_workouts.id';

$result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));
$row = mysqli_fetch_assoc($result);
$status = $row['status'];
$spots = $row['spots'];

// получаем идентификатор пользователя из сессии
$user_id = $_SESSION['user_id'];

// получаем текущее количество доступных тренировок пользователя
$query = "SELECT remaining FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));
$row = mysqli_fetch_assoc($result);
$remaining = $row['remaining'];

// обновляем статус тренировки на противоположный
$new_status = ($status == 'active') ? 'cancelled' : 'active';
$query = "UPDATE workouts SET status = '$new_status' WHERE id = '$workout_id'";
$result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));

// обновляем количество доступных мест для тренировки
$new_spots = ($status == 'active') ? ($spots - 1) : ($spots + 1);
$query = "UPDATE vacant_workouts SET spots = '$new_spots' WHERE id = '$workout_id'";
$result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));

// обновляем количество доступных тренировок для пользователя
$new_remaining = ($status == 'active') ? ($remaining - 1) : ($remaining + 1);
$query = "UPDATE users SET remaining = $new_remaining WHERE id = '$user_id'";
$result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));

// выводим сообщение об успешном изменении статуса тренировки
echo "Статус тренировки успешно изменен на '$new_status'!";
echo '<br>';
echo '<a href="my_workouts.php" class="btn btn-danger">Вернуться к моим тренировкам</a>';

// закрываем соединение с базой данных
mysqli_close($conn);

