<!-- Подключение стилей Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!-- PHP-код для выборки данных из базы данных -->
<?php
session_start();
// Подключение к базе данных
include 'connection.php';

// Проверка соединения
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Проверка наличия сессии администратора
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    }
// Запрос на получение данных о тренировках пользователей
$sql = "SELECT workouts.*, trainers.name as trainer_name, directions.direction, gyms.adress, GROUP_CONCAT(users.last_name SEPARATOR ', ') as participants, vacant_workouts.status as global_status
    FROM workouts
    INNER JOIN trainers ON workouts.trainer_id = trainers.id
    INNER JOIN directions ON workouts.direction = directions.id
    INNER JOIN gyms ON workouts.gym_id = gyms.gym_id
    INNER JOIN users ON workouts.user_id = users.id
    INNER JOIN vacant_workouts ON workouts.id = vacant_workouts.id
    GROUP BY workouts.id, vacant_workouts.status";
$result = mysqli_query($conn, $sql);

  // Проверка наличия тренировок
  if (mysqli_num_rows($result) == 0) {
    echo '<p>Нет тренировок</p>';
  }
  else {
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

// Запрос на получение данных о направлениях тренировок
$sql_directions = "SELECT * FROM directions";
$result_directions = mysqli_query($conn,$sql_directions);

// Проверка наличия направлений
if (mysqli_num_rows($result_directions) == 0) {
echo '<p>Нет направлений</p>';
} else {
  $sql1 = "SELECT * FROM DIRECTIONS";
        $result = mysqli_query($conn, $sql1);

        if (mysqli_num_rows($result) == 0) {
          echo '<p>Нет направлений</p>';
        } else {
      
          
          // Отображение направлений
          echo'<h3 class="h3 text-center" style="line-height: 2;">Направления тренировок</h3>';
          echo '<div class="card-columns" style="width: 75%; margin-left: auto; margin-right: auto; column-count: 4;">';
          while ($row = mysqli_fetch_assoc($result)) {
      
            // Отображение карточки
          
            echo '<div class="card text-light text-center bg-info">';
            echo '<div class="card-body">';
            echo '<h5 class="card-header">' . "Направление" . '</h5>';
            echo '<p class="card-text">' . $row['direction'] . '</p>';
            if ($row['status'] != 'canceled') {
              echo '<a href="cancel_direction.php?id=' . $row['id'] . '" class="btn btn-danger">Отменить</a>';
            } else {
              echo '<a href="re_active_direction.php?id=' . $row['id'] . '" class="btn btn-primary">Реактивировать</a>';
            }
            
            echo '</div>';
            echo '</div>';
      
          }
          echo '</div>';
        }
}
// Запрос на получение данных о направлениях тренировок
$sql_gyms = "SELECT * FROM gyms";
$result_gyms = mysqli_query($conn,$sql_gyms);
if (mysqli_num_rows($result_gyms) == 0) {
  echo '<p>Нет залов</p>';
  }
else {
    $sql1 = "SELECT * FROM gyms";
          $result = mysqli_query($conn, $sql1);
  
          if (mysqli_num_rows($result) == 0) {
            echo '<p>Нет залов</p>';
          } else {
        
            
            // Отображение направлений
            echo'<h3 class="h3 text-center" style="line-height: 2;">Залы</h3>';
            echo '<div class="card-columns" style="width: 75%; margin-left: auto; margin-right: auto; column-count: 4;">';
            while ($row = mysqli_fetch_assoc($result)) {
        
              // Отображение карточки
            
              echo '<div class="card text-light text-center bg-info">';
              echo '<div class="card-body">';
              echo '<h5 class="card-header">' . "Зал" . '</h5>';
              echo '<p class="card-text">' . $row['adress'] . '</p>';
              if ($row['status'] != 'canceled') {
                echo '<a href="cancel_gym.php?gym_id=' . $row['gym_id'] . '" class="btn btn-danger">Отменить</a>';
              } else {
                echo '<a href="re_active_gym.php?gym_id=' . $row['gym_id'] . '" class="btn btn-primary">Реактивировать</a>';
              }
              
              echo '</div>';
              echo '</div>';
        
            }
            echo '</div>';
          }
  }

// Запрос на получение данных о направлениях тренировок
    $sql_users = "SELECT * FROM users";
    $result_users = mysqli_query($conn,$sql_users);
    if (mysqli_num_rows($result_users) == 0) {
        echo '<p>Нет пользователей</p>';
    }
    else {
        $sql1 = "SELECT * FROM users";
        $result = mysqli_query($conn, $sql1);

        // Отображение направлений
        echo'<h3 class="h3 text-center" style="line-height: 2;">Пользователи</h3>';
        echo '<div class="card-columns" style="width: 75%; margin-left: auto; margin-right: auto; column-count: 4;">';
        while ($row = mysqli_fetch_assoc($result)) {

            // Отображение карточки

            echo '<div class="card text-light text-center bg-info">';
            echo '<div class="card-body">';
            echo '<p class="card-text">Фамилия: ' . $row['last_name'] . '</p>';
            echo '<p class="card-text">Имя: ' . $row['name'] . '</p>';
            echo '<p class="card-text">Отчество: ' . $row['surname'] . '</p>';
            echo '<p class="card-text">Тренировок осталось: ' . $row['remaining'] . '</p>';
            echo '<a href="change_data.php?id=' . $row['id'] . '" class="btn btn-primary">Изменить данные</a>';

            echo '</div>';
            echo '</div>';

        }
        echo '</div>';

    }



// Закрытие соединения с базой данных
mysqli_close($conn);
?>
<a href="create_workout.php" class="btn btn-primary btn-lg btn-block">Перейти к созданию тренировки</a>
<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Добавить направление</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="add_direction.php">
                        <div class="form-group">
                            <label for="direction">Название направления</label>
                            <input type="text" name="direction" id="direction" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Добавить зал</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="add_gym.php">
                        <div class="form-group">
                            <label for="gym">Адрес зала</label>
                            <input type="text" name="gym" id="gym" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>