-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 29 2023 г., 21:16
-- Версия сервера: 5.6.51
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `fitness`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` text NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `name`) VALUES
('6403d3469ec6c', 'admin', 'admin@list.ru', '$2y$10$n7Cb1oCKOBTwY6OsJauvdOjKyfuWZ8QgybiRVKlRXNWg9hUqQOR.O', 'Иисус Христосович Бог'),
('6403d77a65fcc', 'aboba', 'kro@gmail.com', '$2y$10$/mmdmiCf/4CHzwftc6enVOJjfZiBfnLpXIiOd/EkdSJsYyYJkvxb2', 'Юрий Николаевич Никулин');

-- --------------------------------------------------------

--
-- Структура таблицы `directions`
--

CREATE TABLE `directions` (
  `id` text NOT NULL,
  `direction` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `directions`
--

INSERT INTO `directions` (`id`, `direction`, `status`) VALUES
('6656565', 'Кроссфит', 'active'),
('6656565', 'Кроссфит', 'active'),
('6403e7ec73d39', 'Пауэрлифтинг', 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `gyms`
--

CREATE TABLE `gyms` (
  `gym_id` text NOT NULL,
  `adress` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `gyms`
--

INSERT INTO `gyms` (`gym_id`, `adress`, `status`) VALUES
('215613', 'Пушкина 4', ''),
('527632', 'Гагарина 95', 'active'),
('6403eb58f2d78', 'Лермонтова 7', 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `role_id` text NOT NULL,
  `role_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
('0', 'admin'),
('1', 'trainer'),
('2', 'user');

-- --------------------------------------------------------

--
-- Структура таблицы `trainers`
--

CREATE TABLE `trainers` (
  `id` text NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `trainers`
--

INSERT INTO `trainers` (`id`, `username`, `email`, `password`, `name`) VALUES
('6403c88a402bd', 'uzver', 'alolo@mail.ru', '$2y$10$rYBHu9GhYyowUDCA9VsLo.fE3NCgxCBVpBHdCphGFvnrdJJdHydju', 'Юрий Николаевич Никулин');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` text NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `last_name` text NOT NULL,
  `name` text NOT NULL,
  `surname` text NOT NULL,
  `remaining` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `last_name`, `name`, `surname`, `remaining`) VALUES
('64138b517d6cc', 'amoba', 'ma@mail.ru', '$2y$10$vXT85eHJrpmZf5jvRQ9zL.Om2mhN6vFvFD/V5c0IxDv1Bluv4nVyi', 'Столбов', 'Марк', 'Маркович', 1488),
('6414e6af326c9', 'stolb', 'li@mail.ru', '$2y$10$DBLrPcZzX1odt5AccmFjbucHoKHw8CnjaqPle9jRdbUioCsMKF5oa', 'Столбов', 'Марк', 'Маркович', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `vacant_workouts`
--

CREATE TABLE `vacant_workouts` (
  `id` text NOT NULL,
  `trainer_id` text NOT NULL,
  `gym_id` text NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL,
  `time_end` text NOT NULL,
  `direction` text NOT NULL,
  `spots` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `vacant_workouts`
--

INSERT INTO `vacant_workouts` (`id`, `trainer_id`, `gym_id`, `date`, `time`, `time_end`, `direction`, `spots`, `status`) VALUES
('6414e719bef21', '6403c88a402bd', '6403eb58f2d78', '2023-03-24', '03:19', '03:19', '6403e7ec73d39', '2', 'active'),
('6417884d956ca', '6403c88a402bd', '215613', '2023-04-01', '04:10', '05:14', '6656565', '10', 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `workouts`
--

CREATE TABLE `workouts` (
  `id` text NOT NULL,
  `user_id` text NOT NULL,
  `trainer_id` text NOT NULL,
  `gym_id` text NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL,
  `time_end` text NOT NULL,
  `direction` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `workouts`
--

INSERT INTO `workouts` (`id`, `user_id`, `trainer_id`, `gym_id`, `date`, `time`, `time_end`, `direction`, `status`) VALUES
('6414e719bef21', '6414e6af326c9', '6403c88a402bd', '6403eb58f2d78', '2023-03-24', '03:19', '03:19', '6403e7ec73d39', 'active');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
