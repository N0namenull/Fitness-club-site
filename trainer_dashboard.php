<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<?php
session_start();

// Подключение к базе данных
include 'connection.php';

// Проверка соединения
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Проверка наличия сессии пользователя
if (isset($_SESSION['trainer_id'])) {
    // Запрос на получение данных о тренировках тренера
    $trainer_id = $_SESSION['trainer_id'];
    echo '<a href="create_workout.php" class="btn btn-primary btn-lg btn-block">Перейти к созданию тренировки</a>';
    $sql = "SELECT workouts.*, directions.direction, gyms.adress, GROUP_CONCAT(users.last_name SEPARATOR ', ') as participants, vacant_workouts.status as global_status
    FROM workouts
    INNER JOIN trainers ON workouts.trainer_id = trainers.id
    INNER JOIN directions ON workouts.direction = directions.id
    INNER JOIN gyms ON workouts.gym_id = gyms.gym_id
    INNER JOIN users ON workouts.user_id = users.id
    INNER JOIN vacant_workouts ON workouts.id = vacant_workouts.id
    WHERE trainers.id = '$trainer_id'
    GROUP BY workouts.id, vacant_workouts.status
";
    $result = mysqli_query($conn, $sql);

    // Проверка наличия тренировок
    if (mysqli_num_rows($result) == 0) {
        echo '<p>У вас еще нет назначенных тренировок</p>';
    } else {
        // Отображение карточек тренировок
        echo '<h3 class="h3 text-center" style="line-height: 2;">Тренировки</h3>';
        echo '<div class="card-columns" style="width: 75%; margin-left: auto; margin-right: auto; column-count: 4;">';
        while ($row = mysqli_fetch_assoc($result)) {
            // Определение цвета фона
            $datetime = strtotime($row['date'] . ' ' . $row['time']);
            $now = time();
            $hours_left = floor(($datetime - $now) / 3600);
            if ($row['global_status'] == 'cancelled') {
                $bg_color = 'bg-secondary';
            } else if ($hours_left >= 6) {
                $bg_color = 'bg-info';
            } else if ($hours_left < 0) {
                $bg_color = 'bg-secondary';
            } else {
                $bg_color = 'bg-warning';
            }
            // Отображение карточки
            echo '<div class="card text-light text-center ' . $bg_color . '">
      <div class="card-header">
      <h5>' . $row['direction'] . '</h5>
      </div>
      <div class="card-body">
      <h6 class="card-subtitle mb-2">' . date('d.m.Y', strtotime($row['date'])) . ' ' . date('H:i', strtotime($row['time'])) . ' - ' . date('H:i', strtotime($row['time_end'])) . '</h6>
      <p class="card-text">Тренер: ' . $row['trainer_name'] . '</p>
      <p class="card-text">Направление: ' . $row['direction'] . '</p>
      <p class="card-text">Зал: ' . $row['adress'] . '</p>
      <p class="card-text">Участники: ' . $row['participants'] . '</p>
      <p class="card-text">Статус: ' . $row['global_status'] . '</p>
      </div>
      <div class="card-footer">';
            echo '<a href="cancel_workout.php?id=' . $row['id'] . '" class="btn btn-danger my-2">Изменить статус</a>';
            echo '<a href="cancel_spec.php?id=' . $row['id'] . '" class="btn btn-danger">Изменить для пользователя</a>';

            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
} else {
    echo '<p>Вы не вошли</p>';
    echo '<a href="login.html" class="btn btn-primary">Войти</a>';
}

mysqli_close($conn);

?>