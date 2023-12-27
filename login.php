<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<?php
session_start();

if (isset($_POST['submit'])) {
    // Подключаемся к базе данных
    include 'connection.php';
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Получаем имя пользователя и пароль из формы входа
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Ищем пользователя с таким именем в таблицах users, trainers и admin
    $sql_user = "SELECT id, password FROM users WHERE username = '$username'";
    $sql_trainer = "SELECT id, password FROM trainers WHERE username = '$username'";
    $sql_admin = "SELECT id, password FROM admins WHERE username = '$username'";

    // Выполняем запрос к таблице users
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        // Получаем хэш-значение пароля пользователя из базы данных
        $row_user = $result_user->fetch_assoc();
        $password_hash_user = $row_user['password'];

        // Сверяем введенный пароль с хэш-значением из базы данных
        if (password_verify($password, $password_hash_user)) {
            // Если пароль верен, сохраняем ID пользователя в сессии и перенаправляем на страницу профиля
            $_SESSION['user_id'] = $row_user['id'];
            echo 'Вы вошли как пользователь';
            echo '<br>';
            echo '<a href="./workouts.php" class="btn btn-primary">Мои тренировки</a>';
            exit;
        } else {
            // Если пароль неверен, выводим ошибку
            $error = 'Неверный логин или пароль';
            echo $error;
            echo '<br>';
            echo '<a href="./login.html" class="btn btn-primary">Попробовать еще раз</a>';
        }
    } 
    else {
        // Выполняем запрос к таблице trainers
        $result_trainer = $conn->query($sql_trainer);

        if ($result_trainer->num_rows > 0) {
            // Получаем хэш-значение пароля тренера из базы данных
            $row_trainer = $result_trainer->fetch_assoc();
            $password_hash_trainer = $row_trainer['password'];

            // Сверяем введенный пароль с хэш-значением из базы данных
            if (password_verify($password, $password_hash_trainer)) {
                // Если пароль верен, сохраняем ID тренера в сессии и перенаправляем на страницу профиля
                $_SESSION['trainer_id'] = $row_trainer['id'];
                echo 'Вы вошли как тренер';
                echo '<br>';
                echo '<a href="./trainer_dashboard.php" class="btn btn-primary">Мои клиенты</a>';
                exit;
            } else {
                // Если пароль неверен, выводим ошибку
                $error = 'Неверный логин или пароль';
                echo $error;
                echo '<br>';
                echo '<a href="./login.html" class="btn btn-primary">Попробовать еще раз</a>';
            }
        }
        else {
            $result_admin = $conn->query($sql_admin);
            if ($result_admin->num_rows > 0) {
                // Получаем хэш-значение пароля администратора из базы данных
                $row_admin = $result_admin->fetch_assoc();
                $password_hash_admin = $row_admin['password'];
    
                // Сверяем введенный пароль с хэш-значением из базы данных
                if (password_verify($password, $password_hash_admin)) {
                    // Если пароль верен, сохраняем ID администратора в сессии и перенаправляем на страницу администратора
                    $_SESSION['admin_id'] = $row_admin['id'];
                    echo 'Вы вошли как администратор';
                    echo '<br>';
                    echo '<a href="./admin_dashboard.php" class="btn btn-primary">Панель администратора</a>';
                    exit;
                } else {
                    // Если пароль неверен, выводим ошибку
                    $error = 'Неверный логин или пароль ащд';
                    echo $error;
                    echo '<br>';
                    echo '<a href="./login.html" class="btn btn-primary">Попробовать еще раз</a>';
                }
            } else {
                // Если не найден пользователь, тренер или администратор с таким именем, выводим ошибку
                $error = 'Не найдено пользователей с таким именем ';
                echo $error;
                echo '<br>';
                echo '<a href="./login.html" class="btn btn-primary">Попробовать еще раз</a>';
            }
        }
    }
    // Закрываем соединение с базой данных
    $conn->close();
}
else{
    echo '<a href="./login.html" class="btn btn-primary">Страница входа</a>';
}
    
    ?>