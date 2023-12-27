<?php
session_start();

// Проверка наличия сессии пользователя
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['trainer_id'])) {
  header("Location: login.html");
  exit();
}

// Проверка наличия идентификатора тренировки в GET-параметрах
if (!isset($_GET['id'])) {
  header("Location: workouts.php");
  exit();
}

// Подключение к базе данных
include 'connection.php';
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Получение данных о тренировке
$id = $_GET['id'];
$user_id = $_SESSION['admin_id'];
$sql = "SELECT * FROM vacant_workouts WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
  mysqli_close($conn);
  header("Location: admin_dashboard.php");
  exit();
}

// Обновление статуса тренировки
$row = mysqli_fetch_assoc($result);
$status = $row['status'];
$new_status = ($status == 'active') ? 'cancelled' : 'active';
$query = "UPDATE vacant_workouts SET status = '$new_status' WHERE id = '$id'";
$result = mysqli_query($conn, $query) or die("Ошибка " . mysqli_error($conn));
if (mysqli_query($conn, $sql)) {
  mysqli_close($conn);

  if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
  }
  elseif (isset($_SESSION['trainer_id'])) {
    header("Location: trainer_dashboard.php");
  }
  
  else {
    header("Location: workouts.php");
  }
  exit();

} 
else {
  mysqli_close($conn);
  echo "Error updating record: " . mysqli_error($conn);
}
?>