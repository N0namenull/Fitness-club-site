<?php
session_start();

// Проверка наличия сессии пользователя
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.html");
  exit();
}

// Проверка наличия идентификатора тренировки в GET-параметрах
if (!isset($_GET['id'])) {
  header("Location: admin_dashboard.php");
  exit();
}

// Подключение к базе данных
include 'connection.php';
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Получение данных о тренировке
$id = $_GET['id'];
$sql = "SELECT DISTINCT * FROM directions WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
  mysqli_close($conn);
  header("Location: admin_dashboard.php");
  exit();
}

// Обновление статуса направления на "active"
$sql = "UPDATE directions SET status = 'active' WHERE id = '$id'";
if (mysqli_query($conn, $sql)) {
  mysqli_close($conn);
  header("Location: admin_dashboard.php");
  exit();
} else {
  mysqli_close($conn);
  echo "Error updating record: " . mysqli_error($conn);
}
?>