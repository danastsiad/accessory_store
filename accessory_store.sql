-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июн 11 2024 г., 09:31
-- Версия сервера: 10.4.25-MariaDB
-- Версия PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `accessory_store`
--

-- --------------------------------------------------------

--
-- Структура таблицы `carts`
--

CREATE TABLE `carts` (
  `id_cart` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `carts`
--

INSERT INTO `carts` (`id_cart`, `id_user`) VALUES
(4, 1),
(1, 3),
(2, 4),
(3, 5),
(5, 6),
(6, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `cart_items`
--

CREATE TABLE `cart_items` (
  `id_cart_item` int(11) NOT NULL,
  `id_cart` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `name_category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id_category`, `name_category`) VALUES
(1, 'Украшения'),
(2, 'Аксессуары'),
(3, 'Сумки');

-- --------------------------------------------------------

--
-- Структура таблицы `colors`
--

CREATE TABLE `colors` (
  `id_color` int(11) NOT NULL,
  `name_color` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `colors`
--

INSERT INTO `colors` (`id_color`, `name_color`) VALUES
(1, 'Черный'),
(2, 'Белый'),
(3, 'Бежевый'),
(4, 'Золото'),
(5, 'Серебро'),
(6, 'Зеленый'),
(7, 'Красный'),
(8, 'Синий'),
(9, 'Голубой'),
(10, 'Розовый'),
(11, 'Фиолетовый'),
(12, 'Желтый'),
(13, 'Пурпурный');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `order_price` int(11) NOT NULL,
  `id_order_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `order_date`, `order_price`, `id_order_status`) VALUES
(1, 3, '2024-06-10', 1980, 5),
(2, 3, '2024-06-10', 390, 4),
(3, 4, '2024-06-10', 790, 4),
(4, 4, '2024-06-10', 1890, 2),
(5, 4, '2024-06-10', 790, 2),
(6, 5, '2024-06-10', 2280, 5),
(7, 5, '2024-06-10', 4580, 3),
(8, 6, '2024-06-10', 990, 1),
(9, 6, '2024-06-10', 990, 2),
(10, 6, '2024-06-10', 1700, 1),
(11, 6, '2024-06-10', 1690, 1),
(12, 6, '2024-06-10', 490, 4),
(13, 7, '2024-06-10', 990, 2),
(14, 7, '2024-06-10', 1700, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `order_actions`
--

CREATE TABLE `order_actions` (
  `id_order_actions` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_order_status` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `time_order_actions` time NOT NULL,
  `date_order_actions` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_actions`
--

INSERT INTO `order_actions` (`id_order_actions`, `id_order`, `id_order_status`, `id_user`, `time_order_actions`, `date_order_actions`) VALUES
(1, 5, 2, 4, '17:16:22', '2024-06-10'),
(2, 1, 4, 1, '17:21:54', '2024-06-10'),
(3, 1, 5, 1, '17:22:01', '2024-06-10'),
(4, 2, 4, 1, '17:22:09', '2024-06-10'),
(5, 9, 2, 6, '18:40:27', '2024-06-10'),
(6, 3, 4, 2, '19:04:45', '2024-06-10'),
(7, 4, 2, 2, '19:04:56', '2024-06-10'),
(8, 6, 4, 2, '19:12:35', '2024-06-10'),
(9, 6, 5, 2, '19:12:41', '2024-06-10'),
(10, 12, 4, 2, '19:22:45', '2024-06-10'),
(11, 13, 2, 7, '23:05:19', '2024-06-10'),
(12, 14, 4, 1, '23:06:37', '2024-06-10'),
(13, 14, 5, 1, '23:06:45', '2024-06-10');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id_order_item` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `id_order_item_action` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id_order_item`, `id_order`, `id_product`, `quantity`, `id_order_item_action`) VALUES
(1, 1, 1, 1, 4),
(2, 1, 7, 1, 4),
(3, 2, 11, 1, 2),
(4, 3, 5, 1, 2),
(5, 4, 19, 1, 3),
(6, 5, 28, 1, 3),
(7, 6, 14, 1, 4),
(8, 7, 23, 1, 1),
(9, 8, 1, 1, 1),
(10, 9, 9, 1, 3),
(11, 10, 3, 1, 1),
(12, 11, 18, 1, 1),
(13, 12, 31, 1, 2),
(14, 13, 1, 1, 3),
(15, 14, 3, 1, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `order_items_actions`
--

CREATE TABLE `order_items_actions` (
  `id_order_item_action` int(11) NOT NULL,
  `name_order_item_action` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_items_actions`
--

INSERT INTO `order_items_actions` (`id_order_item_action`, `name_order_item_action`) VALUES
(1, 'Резерв'),
(2, 'Собран'),
(3, 'Отменен'),
(4, 'Выдан');

-- --------------------------------------------------------

--
-- Структура таблицы `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id_order_status` int(11) NOT NULL,
  `name_order_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_statuses`
--

INSERT INTO `order_statuses` (`id_order_status`, `name_order_status`) VALUES
(1, 'Оформлен'),
(2, 'Отменен'),
(3, 'В обработке'),
(4, 'Готов к выдаче'),
(5, 'Выполнен');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id_product` int(11) NOT NULL,
  `id_product_type` int(11) NOT NULL,
  `name_product` varchar(50) NOT NULL,
  `article` varchar(11) DEFAULT NULL,
  `description` varchar(300) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(300) NOT NULL,
  `material` varchar(100) NOT NULL,
  `id_color` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `quantity_store` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id_product`, `id_product_type`, `name_product`, `article`, `description`, `price`, `image`, `material`, `id_color`, `quantity`, `quantity_store`) VALUES
(1, 1, 'Кепка', '00001', 'Бежевая кепка займет почетное место в вашей коллекции головных уборов. Для пошива изделия была выбрана приятная на ощупь хлопковая ткань. Модель с регулируемым ремешок сзади.', 990, '/accessory_store/img/products/01.png', 'Хлопок 100% ', 3, 8, 9),
(2, 1, 'Кепка', '00002', 'Бейсболка с широким изогнутым козырьком изготовлена из мягкой вискозы и прочного полиэстера. Изделие легко дополнит повседневный образ, сделая его трендовым. Головной убор комфортно садится по голове, а за фиксацию отвечает ремешок с металлическим зажимом.', 890, '/accessory_store/img/products/02.png', 'Вискоза 20%, полиэстер 80%', 1, 10, 10),
(3, 2, 'Шапка', '00003', 'Шапка  — беспроигрышный вариант для создания стильных аутфитов с акцентом на тепло. Модель фактурной вязки выполнена из смесовой пряжи: за шелковистость отвечает вискоза, а за эластичность — нейлон.', 1700, '/accessory_store/img/products/03.png', 'Вискоза 75%, нейлон 25%', 1, 8, 9),
(4, 2, 'Шапка', '00004', 'Шапка из мягкой полушерстяной пряжи станет незаменимой спутницей аутфитов в сезон низких температур. Модель в фактурный рубчик мягко облегает головку и уши, надёжно защищая от холода и ветра.', 1700, '/accessory_store/img/products/04.png', 'Шерсть 30%, акрил 70%', 2, 10, 10),
(5, 3, 'Шляпа', '00005', 'Плетеная шляпка из бумажной лозы точно станет вашим любимым летним аксессуаром!\r\nВ этом головном уборе не будет жарко, поскольку он отлично пропускает воздух. Поля средней длины надежно защитят лицо от солнца, при этом не ограничивая обзор.', 790, '/accessory_store/img/products/05.png', 'Бумага 100% ', 2, 9, 10),
(6, 3, 'Шляпа', '00006', 'Нестареющая классика — шляпка-федора, которая идеально впишется и в пляжный, и в casual-образ. Черная лента внизу тульи добавляет модели утонченности и изящества.', 990, '/accessory_store/img/products/06.png', 'Бумага 100% ', 3, 5, 5),
(7, 4, 'Очки', '00007', 'Очки, которые не только защитят ваши глаза от яркого солнечного света, но и станут ярким акцентом вашего образа. Изготовлены из лёгкого и прочного пластика, обеспечивают комфортное ношение в течение всего дня. ', 990, '/accessory_store/img/products/7.png', 'Пластик', 1, 3, 3),
(8, 4, 'Очки', '00008', 'Изысканные и элегантные солнцезащитные очки в металлической оправе - идеальное сочетание стиля и защиты от солнечных лучей. Оправа из легкого металла обеспечивает прочность и комфорт, а тонкие линзы с защитой от УФ-лучей делают ношение этих очков не только стильным, но и безопасным для глаз. ', 1290, '/accessory_store/img/products/8.png', 'Пластик, металл', 5, 4, 4),
(9, 5, 'Очки', '00009', 'Функциональный и стильный аксессуар, призванный защищать глаза и нежную кожу вокруг от вредного ультрафиолета, станут идеальным аксессуаром для завершения вашего образа.', 990, '/accessory_store/img/products/9.png', 'Пластик, металл', 4, 5, 5),
(10, 5, 'Очки', '00010', 'Функциональный и стильный аксессуар, призванный защищать глаза и нежную кожу вокруг от вредного ультрафиолета, станут идеальным аксессуаром для завершения вашего образа.', 990, '/accessory_store/img/products/10.png', 'Пластик, металл', 1, 5, 5),
(11, 6, 'Цепочка для очков', '00011', 'Элегантная и практичная цепочка для очков станет стильным аксессуаром и поможет не потерять ваши очки. Изготовлена из прочных и качественных материалов, цепочка идеально подходит для повседневного использования. ', 390, '/accessory_store/img/products/11.png', 'Пластик, металл', 1, 2, 3),
(12, 6, 'Чехол для очков', '00012', 'Надёжный и стильный чехол для очков защитит ваши очки от царапин, пыли и повреждений. Компактный и лёгкий, легко помещается в сумке или кармане, что делает его идеальным для повседневного использования. ', 590, '/accessory_store/img/products/12.png', 'Экокожа 100%', 3, 5, 5),
(13, 9, 'Зонт', '00013', 'Бежевый автоматический зонт с широким куполом из быстросохнущего гладкого текстиля станет незаменимым аксессуаром в дождливую погоду. Каркас с гибкими спицами изготовлен из прочного легкого металла.', 1290, '/accessory_store/img/products/13.png', 'Текстиль, металл, пластик', 1, 5, 5),
(14, 9, 'Зонт', '00014', 'Черный автоматический зонт с широким куполом из быстросохнущего гладкого текстиля станет незаменимым аксессуаром в дождливую погоду. Каркас с гибкими спицами изготовлен из прочного легкого металла.', 1290, '/accessory_store/img/products/14.png', 'Текстиль, металл, пластик', 3, 4, 4),
(15, 8, 'Зонт', '00015', 'Надежный и стильный зонт, который сочетает в себе функциональность и элегантный дизайн. Полуавтоматический механизм открывания обеспечивает удобство в использовании, а прочная конструкция гарантирует защиту от дождя и ветра.', 990, '/accessory_store/img/products/15.png', 'Текстиль, металл, пластик', 1, 8, 10),
(16, 7, 'Зонт', '00016', 'Миниатюрный складной зонт с широким куполом из быстросохнущего гладкого текстиля выполнен в нежном голубом цвете. Каркас с гибкими спицами изготовлен из прочного легкого металла, благодаря чему зонт защитит от ветра и сильного дождя.', 890, '/accessory_store/img/products/16.png', 'Текстиль, металл, пластик', 9, 8, 8),
(17, 7, 'Зонт', '00017', 'Миниатюрный складной зонт с широким куполом из быстросохнущего гладкого текстиля выполнен в нежном розовом цвете. Каркас с гибкими спицами изготовлен из прочного легкого металла, благодаря чему зонт защитит от ветра и сильного дождя.', 890, '/accessory_store/img/products/17.png', 'Текстиль, металл, пластик', 10, 8, 8),
(18, 14, 'Сумка', '00018', 'Универсальная и практичная модель. Яркой акцентной деталью являются две цепочки, которые по желанию можно использовать в качестве ручки, убрав длинный ремешок внутрь сумки.', 1690, '/accessory_store/img/products/18.png', 'Экокожа 100%', 1, 3, 4),
(19, 14, 'Сумка', '00019', 'Универсальная модель, которая впишется в любой образ: от повседневного до вечернего.\r\nСъемный ремешок регулируется по длине, сумку можно носить как кроссбоди, оставив руки свободными.\r\n', 1890, '/accessory_store/img/products/19.png', 'Экокожа 100%', 3, 5, 5),
(20, 15, 'Сумка', '00020', 'Вместительная сумка отвечает всем требованиям актуальных трендов: объёмный силуэт, широкий текстильный плечевой ремень и стёганый дизайн. Модель сшили из мягкой, но плотной экокожи, которая не боится влаги и проста в уходе.', 2290, '/accessory_store/img/products/20.png', 'Экокожа', 1, 4, 4),
(21, 15, 'Сумка', '00021', 'Изготовлена из высококачественного материала, эта сумка обеспечивает прочность и долговечность. Большой вместительный отсек с дополнительными карманами позволяет удобно организовать хранение всех ваших вещей. ', 2690, '/accessory_store/img/products/21.png', 'Экокожа 100%', 1, 3, 3),
(22, 17, 'Рюкзак', '00022', 'В рюкзаке одно отделение, вмещающее формат А4, и два внутренних кармана, в том числе органайзер для принадлежностей. Снаружи — вместительный карман на молнии для самых важных мелочей и небольшой боковой кармашек без застежки, в который можно положить бутылку воды или телефон.', 2990, '/accessory_store/img/products/22.png', 'Экокожа 100%', 1, 7, 7),
(23, 17, 'Рюкзак', '00023', 'Интересный акцент этого рюкзака — контрастная черная заливка и тесьма для молнии. Внутри есть дополнительные карманы, поэтому вы легко сможете организовать небольшое, но вместительное пространство. Также есть внешний карман для мелочей, которые хочется всегда иметь под рукой.', 2690, '/accessory_store/img/products/23.png', 'Экокожа 100%', 3, 4, 5),
(24, 16, 'Рюкзак', '00024', 'Рюкзак с декором в виде металлической цепочки на лицевой стороне прекрасно дополнит casual образ, сделав его более ярким и стильным. Модель выполнена из гладкого текстильного материала, спереди карман с застежкой на молнии, а также несколько отделений внутри.', 1890, '/accessory_store/img/products/24.png', 'Текстиль 100%', 1, 10, 10),
(25, 16, 'Рюкзак', '00025', 'Бежевый рюкзак с контрастными чёрными вставками сшили из прочного текстиля и дополнили двумя накладными карманами. За комфортную посадку отвечают регулируемые лямки и мягкая ортопедическая спинка.', 1890, '/accessory_store/img/products/25.png', 'Текстиль 100%', 3, 8, 8),
(26, 11, 'Колье', '00026', 'Длина цепочки с учетом застежки 37 см + цепочка удлинитель 6,5 см', 890, '/accessory_store/img/products/26.png', 'Металл, стекло', 4, 8, 8),
(27, 11, 'Колье', '00027', 'Колье — акцентный аксессуар, который добавит образу стильную завершённость. Удлинённый силуэт, трендовый дизайн в виде цепи и оригинальный кулон. Украшение изготовили из плотного металла золотого цвета.\r\n', 1290, '/accessory_store/img/products/27.png', 'Металл', 4, 6, 6),
(28, 10, 'Серьги', '00028', 'Изысканные и стильные, эти серьги в виде переплетенных колец в золотом цвете создадут завершающий штрих вашему образу.', 790, '/accessory_store/img/products/28.png', 'Металл', 4, 6, 6),
(29, 10, 'Серьги', '00029', 'Эти нежные серьги в виде небольших колец в золотом цвете созданы для того, чтобы подчеркнуть вашу женственность и стиль.', 690, '/accessory_store/img/products/29.png', 'Металл', 4, 6, 6),
(30, 13, 'Заколка-краб', '00030', 'Элегантная и изысканная, эта заколка-краб с бабочкой на цепочке станет прекрасным акцентом в вашей прическе. ', 790, '/accessory_store/img/products/30.png', 'Металл', 5, 7, 7),
(31, 13, 'Заколки', '00031', 'Изысканный и стильный набор заколок, вдохновленный красотой звездного неба. В комплекте представлены несколько заколок, выполненных в форме звезд, что делает их идеальным выбором для создания уникальных причесок.', 490, '/accessory_store/img/products/31.png', 'Пластик, металл', 1, 4, 5),
(32, 12, 'Ободок', '00032', 'Стильный и современный, этот пластиковый ободок в виде цепочки станет замечательным аксессуаром для вашей прически. ', 490, '/accessory_store/img/products/32.png', 'Пластик', 1, 5, 5),
(33, 12, 'Ободок', '00033', 'Утонченный дизайн с бантом добавляет нотку нежности и шарма вашему стилю. Идеальный выбор как для повседневного использования, так и для особых случаев.', 690, '/accessory_store/img/products/33.png', 'Текстиль, пластик', 1, 10, 10);

--
-- Триггеры `products`
--
DELIMITER $$
CREATE TRIGGER `before_product_insert` BEFORE INSERT ON `products` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SET new_id = (SELECT MAX(id_product) + 1 FROM products);
    IF new_id IS NULL THEN
        SET new_id = 1;
    END IF;
    SET NEW.article = CONCAT('000', LPAD(new_id, 2, '0'));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_quantity` BEFORE UPDATE ON `products` FOR EACH ROW BEGIN
    IF NEW.quantity_store > OLD.quantity_store THEN
        SET NEW.quantity = OLD.quantity + (NEW.quantity_store - OLD.quantity_store);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_quantity_store` BEFORE INSERT ON `products` FOR EACH ROW BEGIN
    SET NEW.quantity = NEW.quantity_store;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `product_types`
--

CREATE TABLE `product_types` (
  `id_product_type` int(11) NOT NULL,
  `id_subcategories` int(11) NOT NULL,
  `name_product_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `product_types`
--

INSERT INTO `product_types` (`id_product_type`, `id_subcategories`, `name_product_type`) VALUES
(1, 3, 'Кепки'),
(2, 3, 'Шапки'),
(3, 3, 'Шляпы'),
(4, 4, 'Солнцезащитные очки'),
(5, 4, 'Имиджевые очки'),
(6, 4, 'Аксессуары для очков'),
(7, 5, 'Механические'),
(8, 5, 'Полуавтоматические'),
(9, 5, 'Автоматические'),
(10, 1, 'Серьги'),
(11, 1, 'Колье'),
(12, 2, 'Ободки'),
(13, 2, 'Заколки'),
(14, 6, 'Кросс-боди'),
(15, 6, 'Тоут'),
(16, 7, 'Текстильные'),
(17, 7, 'Кожаные');

-- --------------------------------------------------------

--
-- Структура таблицы `subcategories`
--

CREATE TABLE `subcategories` (
  `id_subcategories` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `name_subcategory` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `subcategories`
--

INSERT INTO `subcategories` (`id_subcategories`, `id_category`, `name_subcategory`) VALUES
(1, 1, 'Бижутерия'),
(2, 1, 'Украшения для волос'),
(3, 2, 'Головные уборы'),
(4, 2, 'Очки'),
(5, 2, 'Зонты'),
(6, 3, 'Сумки'),
(7, 3, 'Рюкзаки');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `name`, `surname`, `telephone`, `email`, `password`, `id_status`) VALUES
(1, 'Вероника', 'Каратеева', '+7(999)999-99-99', 'veronika1984@mail.ru', 'fcea920f7412b5da7be0cf42b8c93759', 2),
(2, 'Татьяна', 'Пескова', '+7(888)888-88-88', 'peskova0506@ya.ru', 'fcea920f7412b5da7be0cf42b8c93759', 2),
(3, 'Елена', 'Сергеева', '+7(916)123-45-67', '123@mail.ru', 'a8e0e342e0a5563fb53bdbb1d7691096', 1),
(4, 'Наталия', 'Лачкова', '+7(916)789-27-82', 'nataliya6941@gmail.com', '6585bd0553ae9c9d479ed9dbf98176c4', 1),
(5, 'Виктория', 'Шашкова', '+7(916)767-97-47', 'viktoriya27091961@mail.ru', '0dc88555a54d3b06db15a679fe5ae1a0', 1),
(6, 'Алена', 'Баева', '+7(999)679-99-99', 'alena1577@rambler.ru', 'a8b7195782a5531bc603dc7823b6bd9b', 1),
(7, 'Мария', 'Сергеевна', '+7(999)994-99-99', '1234@mail.ru', '5ed682ffa48b116ffda4d3b30aebf75a', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_statuses`
--

CREATE TABLE `user_statuses` (
  `id_status` int(11) NOT NULL,
  `name_status_user` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `user_statuses`
--

INSERT INTO `user_statuses` (`id_status`, `name_status_user`) VALUES
(1, 'Клиент'),
(2, 'Сотрудник');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `R_3` (`id_user`);

--
-- Индексы таблицы `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id_cart_item`),
  ADD KEY `R_14` (`id_cart`),
  ADD KEY `R_27` (`id_product`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Индексы таблицы `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id_color`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `R_26` (`id_order_status`),
  ADD KEY `R_35` (`id_user`);

--
-- Индексы таблицы `order_actions`
--
ALTER TABLE `order_actions`
  ADD PRIMARY KEY (`id_order_actions`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `order_actions_ibfk_2` (`id_order_status`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id_order_item`),
  ADD KEY `R_36` (`id_order`),
  ADD KEY `R_37` (`id_product`),
  ADD KEY `id_order_items_status` (`id_order_item_action`);

--
-- Индексы таблицы `order_items_actions`
--
ALTER TABLE `order_items_actions`
  ADD PRIMARY KEY (`id_order_item_action`);

--
-- Индексы таблицы `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id_order_status`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `id_color` (`id_color`),
  ADD KEY `id_product_type` (`id_product_type`);

--
-- Индексы таблицы `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id_product_type`),
  ADD KEY `id_subcategories` (`id_subcategories`);

--
-- Индексы таблицы `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id_subcategories`),
  ADD KEY `id_category` (`id_category`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_status` (`id_status`);

--
-- Индексы таблицы `user_statuses`
--
ALTER TABLE `user_statuses`
  ADD PRIMARY KEY (`id_status`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `carts`
--
ALTER TABLE `carts`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id_cart_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `colors`
--
ALTER TABLE `colors`
  MODIFY `id_color` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `order_actions`
--
ALTER TABLE `order_actions`
  MODIFY `id_order_actions` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id_order_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `order_items_actions`
--
ALTER TABLE `order_items_actions`
  MODIFY `id_order_item_action` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id_order_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id_product_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id_subcategories` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `user_statuses`
--
ALTER TABLE `user_statuses`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `R_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `R_14` FOREIGN KEY (`id_cart`) REFERENCES `carts` (`id_cart`),
  ADD CONSTRAINT `R_27` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `R_26` FOREIGN KEY (`id_order_status`) REFERENCES `order_statuses` (`id_order_status`),
  ADD CONSTRAINT `R_35` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `order_actions`
--
ALTER TABLE `order_actions`
  ADD CONSTRAINT `order_actions_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `order_actions_ibfk_2` FOREIGN KEY (`id_order_status`) REFERENCES `order_statuses` (`id_order_status`),
  ADD CONSTRAINT `order_actions_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `R_36` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `R_37` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`),
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`id_order_item_action`) REFERENCES `order_items_actions` (`id_order_item_action`);

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`id_color`) REFERENCES `colors` (`id_color`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`id_product_type`) REFERENCES `product_types` (`id_product_type`);

--
-- Ограничения внешнего ключа таблицы `product_types`
--
ALTER TABLE `product_types`
  ADD CONSTRAINT `product_types_ibfk_1` FOREIGN KEY (`id_subcategories`) REFERENCES `subcategories` (`id_subcategories`);

--
-- Ограничения внешнего ключа таблицы `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_status`) REFERENCES `user_statuses` (`id_status`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
