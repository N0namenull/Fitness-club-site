<?php
// Подключаемся к базе данных
include 'connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

// Проверяем соединение с базой данных
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Обрабатываем данные из формы регистрации
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $fio = mysqli_real_escape_string($conn, $_POST['fio']);

    // Проверяем, существует ли пользователь с таким же логином
    $sql = "SELECT * FROM trainers WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "Тренер с таким именем пользователя уже существует, выберите другое имя пользователя";
    } else {
        // Шифруем пароль
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Генерируем уникальный идентификатор пользователя
        $user_id = uniqid();

        // Записываем данные в таблицу пользователей
        $sql = "INSERT INTO trainers (id, username, email, password, name)
                VALUES ('$user_id', '$username', '$email', '$hashed_password', '$fio')";
        if (mysqli_query($conn, $sql)) {
            echo '<a href="./login.html">Тренер успешно зарегестрирован, нажмите сюда чтобы войти</a>';
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Закрываем соединение с базой данных
mysqli_close($conn);
?>