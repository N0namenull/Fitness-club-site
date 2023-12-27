<?php
session_start();

// Проверка наличия сессии пользователя
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
  }

// Проверка наличия названия направления
if (!isset($_POST['direction'])) {
    header("Location: admin_dashboard.php");
    exit();
  }

// Подключаемся к базе данных
include 'connection.php';


// Проверяем соединение с базой данных
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Обрабатываем данные из формы регистрации
if (isset($_POST['submit'])) {
    $direction = mysqli_real_escape_string($conn, $_POST['direction']);
    
    // Генерируем уникальный идентификатор 
    $direction_id = uniqid();

        // Записываем данные в таблицу 
        $sql = "INSERT INTO directions (id, direction, status)
                VALUES ('$direction_id', '$direction', 'active')";
        if (mysqli_query($conn, $sql)) {
            header("Location: admin_dashboard.php");
            exit();
        }
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }


// Закрываем соединение с базой данных
mysqli_close($conn);
?>