<?php
// Подключаемся к базе данных
include 'connection.php';

// Проверяем соединение с базой данных
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Обрабатываем данные из формы регистрации
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);

    // Проверяем, существует ли пользователь с таким же логином
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "Пользователь с таким именем пользователя уже существует, выберите другое имя пользователя";
    } else {
        // Шифруем пароль
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Генерируем уникальный идентификатор пользователя
        $user_id = uniqid();

        // Записываем данные в таблицу пользователей
        $sql = "INSERT INTO users (id, username, email, password, last_name, name, surname, remaining)
                VALUES ('$user_id', '$username', '$email', '$hashed_password', '$last_name', '$name', '$surname', 0)";
        if (mysqli_query($conn, $sql)) {
            echo '<a href="./login.html">Пользователь успешно зарегестрирован, нажмите сюда чтобы войти</a>';
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Закрываем соединение с базой данных
mysqli_close($conn);
?>