<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['trainer_id'])) {
    header("Location: login.html");
    exit();
}
$workout_id = $_GET['id'];

$sql = "SELECT users.*, workouts.date, workouts.time, workouts.status
        FROM users
        INNER JOIN workouts ON workouts.user_id = users.id
        WHERE workouts.id = '$workout_id'";
$result = mysqli_query($conn, $sql);

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Получение id пользователя, для которого нужно отменить/восстановить тренировку
    $user_id = $_POST['user_id'];

    // Получение текущего статуса тренировки для выбранного пользователя
    $sql = "SELECT status FROM workouts WHERE id = '$workout_id' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $status = $row['status'];

    // Обновление статуса тренировки для выбранного пользователя
    if ($status == 'active') {
        $new_status = 'cancelled';
    } else {
        $new_status = 'active';
    }
    $sql = "UPDATE workouts SET status = '$new_status'
          WHERE id = '$workout_id' AND user_id = '$user_id'";
    mysqli_query($conn, $sql);

    // Редирект на страницу со списком тренировок
    if (isset($_SESSION['admin_id'])) {
        header("Location: admin_dashboard.php");
    }
    elseif (isset($_SESSION['trainer_id'])) {
        header("Location: trainer_dashboard.php");
    }
    exit();
}
?>

<html lang="ru">
<head>
    <title>Список участников тренировки</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Подключение Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h2 class="mt-5 mb-4">Список участников</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Дата тренировки</th>
            <th>Время тренировки</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <!-- Добавление записей в таблицу -->
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['surname'] ?></td>
                <td><?= date('d.m.Y', strtotime($row['date'])) ?></td>
                <td><?= date('H:i', strtotime($row['time'])) ?></td>
                <td>
                    <!-- Отображение статуса -->
                    <?php if ($row['status'] == 'active') : ?>
                        <span class="badge badge-success"><?= ucfirst($row['status']) ?></span>
                    <?php else : ?>
                        <span class="badge badge-danger"><?= ucfirst($row['status']) ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Форма для отмены/реактивации тренировки для выбранного пользователя -->
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="status" value="<?= $row['status'] ?>">
                        <?php if ($row['status'] == 'active') : ?>
                            <button type="submit" name="cancel" class="btn btn-danger btn-sm">Отменить</button>
                        <?php else : ?>
                            <button type="submit" name="reactivate" class="btn btn-success btn-sm">Реактивировать</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>