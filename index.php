<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <style>
        .carousel-item img {
            height: 300px;
            width: 75%;
            object-fit: cover;
        }
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 10px;
            color: #fff;
        }
    </style>
    <title>Фитнес клуб</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <a href="index.php"><img src="img/icon.jpg" alt="Fitness Club Logo" width="75" height="75"></a>
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
<div class="container mt-5">
    <!-- Карусель -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Первый слайд">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Акция</h5>
                    <p>Приведи друга и получи годовой запас стероидов</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="https://images.unsplash.com/photo-1554284126-aa88f22d8b74?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1594&q=80" alt="Второй слайд">
                <div class="carousel-caption d-none d-md-block">
                    <h5>При покупке протеина</h5>
                    <p>Тренировка от Максима Марцинкевича бесплатно</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="https://images.unsplash.com/photo-1521805103424-d8f8430e8933?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Третий слайд">
                <div class="carousel-caption d-none d-md-block">
                    <h5>У самурая нет цели</h5>
                    <p>Есть только путь</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Назад</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Вперед</span>
        </a>
        </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>About Us</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vitae lectus in ante consequat faucibus
                nec at nulla. Fusce in interdum tortor, vel elementum orci. Nunc ac convallis quam, id pellentesque velit.
                Aenean in bibendum odio. Curabitur sit amet justo ante. Donec volutpat dolor et lectus varius luctus.</p>
        </div>
        <div class="col-md-6">
            <h2>Наши направления</h2>
            <?php
        include 'connection.php';
        $sql = "SELECT DISTINCT * from directions";

            $result = mysqli_query($conn, $sql);

            echo '<div class="card-columns" style="width: 100%; margin-left: auto; margin-right: auto; column-count: 4;">';
            while ($row = mysqli_fetch_assoc($result)) {
            // Отображение карточки
                echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row['direction'] . '</h5>';
                    echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            ?>
        </div>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>