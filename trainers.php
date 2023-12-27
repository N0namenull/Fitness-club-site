<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Список тренеров</title>
    <!-- Подключение стилей Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <a href="index.php"><img src="img/icon.jpg" alt="Fitness Club Logo" width="100" height="100"></a>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Главная</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.html">Зарегестрироваться</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.html">Войти</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="trainers.php">Тренера и тренировки</a>
            </li>

        </ul>
    </div>
</nav>


<div class="container my-5">
    <h1 class="mb-5">Список тренеров</h1>
    <div class="row">
        <?php
        // Подключение к базе данных
        include 'connection.php';

        // Получение данных из таблицы trainers
        $result = mysqli_query($conn, 'SELECT * FROM trainers');

        // Обработка данных
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="col-md-4">
              <div class="card mb-4 shadow-sm">
                <div class="card-body">
                  <h5 class="card-title">'.$row['name'].'</h5>
                </div>
              </div>
            </div>
          ';
        }

        // Освобождение памяти
        mysqli_free_result($result);

        // Закрытие соединения
        mysqli_close($conn);
        ?>
    </div>
</div>

<div class="container my-5">
    <h1 class="mb-5">Список тренировок</h1>
    <div class="row">
        <?php
        include 'connection.php';
        $sql = "SELECT DISTINCT vacant_workouts.*, trainers.name as trainer_name, directions.direction, gyms.adress
        FROM vacant_workouts
        INNER JOIN trainers ON vacant_workouts.trainer_id = trainers.id
        INNER JOIN directions ON vacant_workouts.direction = directions.id
        INNER JOIN gyms ON vacant_workouts.gym_id = gyms.gym_id
        WHERE vacant_workouts.spots > '0'";

        $result = mysqli_query($conn, $sql);

        echo '<div class="card-columns" style="width: 75%; margin-left: auto; margin-right: auto; column-count: 4;">';
        while ($row = mysqli_fetch_assoc($result)) {
          // Определение цвета фона
          $datetime = strtotime($row['date'] . ' ' . $row['time']);
          $now = time();
          $hours_left = floor(($datetime - $now) / 3600);
          if ($row['status'] == 'canceled') {
            $bg_color = 'bg-secondary';
          } else if ($hours_left >= 6) {
            $bg_color = 'bg-info';
          }
            else if ($hours_left < 0 ) {
                $bg_color = 'bg-secondary';
          } else {
            $bg_color = 'bg-warning';
          }
          // Отображение карточки

          echo '<div class="card text-light text-center ' . $bg_color . ' ">';
          echo '<div class="card-header">' . $row['trainer_name'] . '</div>';
          echo '<div class="card-body">';
          echo '<h5 class="card-title">' . $row['direction'] . '</h5>';
          echo '<p class="card-text">' . $row['adress'] . '</p>';
          echo '<p class="card-text">' . $row['date'] . ' <br> ' . $row['time'] . ' - ' . $row['time_end'] . '</p>';
          if ($hours_left > 6) {
            if ($row['status'] == 'canceled') {
                echo '<span class="badge badge-secondary">Отменено</span>';
            }
          }
          echo '</div>';
          echo '</div>';
}
    echo '</div>';
        ?>
</div>
<!-- Подключение скриптов Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-GwTZWGhjonUeTphH6zX9Sq+P9Mz2Q/h89sW3hZT6Fkto/KtpY33cy4jvjeyYu4a4QVIyBqmjUJcCfLxSJe6p1w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js" integrity="sha512-yBAtirDAvFkHgsCTKZB24fRzgRpSfiL0/lYBcVT0M/9o9ST4o+mFA4N4NwIQiv4xjB3q49dGk/NY6QepjJJzDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

