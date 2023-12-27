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
$sql = "SELECT DISTINCT workouts.*, trainers.name as trainer_name, directions.direction, gyms.adress, users.remaining
FROM workouts
INNER JOIN trainers ON workouts.trainer_id = trainers.id
INNER JOIN directions ON workouts.direction = directions.id
INNER JOIN gyms ON workouts.gym_id = gyms.gym_id
INNER JOIN users ON workouts.user_id = users.id
WHERE workouts.user_id = '$user_id'";

    $result = mysqli_query($conn, $sql);
    $result1 = mysqli_query($conn, $sql);
// Проверка наличия активных тренировок
if (mysqli_num_rows($result) == 0) {
echo '<p>Вы не записаны на тренировки</p>';
}
else {


// Отображение карточек тренировок
    $row1 = mysqli_fetch_assoc($result1);
    echo '<div class="container-fluid bg-primary text-white">
  <div class="row my-0">
    <div class="col d-flex align-items-center">
      <p class="mb-0">Тренировок осталось: ' . $row1["remaining"] . '</p>
    </div>
    <div class="col">
      <a href="workouts.php" class="btn btn-primary btn-block rounded-0">Записаться на тренировку</a>
    </div>
  </div>
</div> ';

echo '<div class="card-columns" style="width: 75%; margin-left: auto; margin-right: auto; column-count: 4;">';
while ($row = mysqli_fetch_assoc($result)) {
  // Определение цвета фона
  $datetime = strtotime($row['date'] . ' ' . $row['time']);
  $now = time();
  $hours_left = floor(($datetime - $now) / 3600);
  if ($row['status'] == 'cancelled') {
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
  echo '<p class="card-text">' . $row['date'] . '</p>';
  echo '<p class="card-text">' . $row['time'] . ' - ' . $row['time_end'] . '</p>';
  if ($hours_left > 6) {
    if ($row['status'] != 'cancelled') {
      echo '<a href="rickroll.php?id=' . $row['id'] . '" class="btn btn-danger">Отменить</a>';
    } else {
        echo '<a href="rickroll.php?id=' . $row['id'] . '" class="btn btn-danger">Перезаписаться</a>';
    }
  }
  echo '</div>';
  echo '</div>';

}
echo '</div>';
}


mysqli_close($conn);

?>
