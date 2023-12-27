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
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT DISTINCT vacant_workouts.*, trainers.name as trainer_name, directions.direction, gyms.adress
    FROM vacant_workouts
    INNER JOIN trainers ON vacant_workouts.trainer_id = trainers.id
    INNER JOIN directions ON vacant_workouts.direction = directions.id
    INNER JOIN gyms ON vacant_workouts.gym_id = gyms.gym_id
    WHERE vacant_workouts.spots > '0'";

    $result = mysqli_query($conn, $sql);

// Проверка наличия активных тренировок
if (mysqli_num_rows($result) == 0) {
echo '<p>Нет доступных к записи тренировок</p>';
}
else {


// Отображение карточек тренировок
echo '<a href="my_workouts.php" class="btn btn-primary btn-lg btn-block mb-4 rounded-0">Мои тренировки</a>';
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
  echo '<p class="card-text">' . $row['date'] . ' ' . $row['time'] . $row['time_end'] . '</p>';
  if ($hours_left > 6) {
    if ($row['status'] != 'canceled') {
      echo '<a href="enroll.php?id=' . $row['id'] . '" class="btn btn-success">Записаться</a>';
    } else {
      echo '<span class="badge badge-secondary">Отменено</span>';
    }
  }
  echo '</div>';
  echo '</div>';

}
echo '</div>';
}


mysqli_close($conn);

?>
