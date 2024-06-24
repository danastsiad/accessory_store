<?php
session_start();
include '../header.php';
include '../../config/db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: /accessory_store/php/auth.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$query = "SELECT id_status FROM users WHERE id_user = $id_user";
$result = mysqli_query($link, $query);

if ($result) {
    $user_data = mysqli_fetch_assoc($result);
    $id_status = $user_data['id_status'];

    if ($id_status >= 2) {

        if (isset($_GET['id'])) {
            $order_id = $_GET['id'];

            if (isset($_POST['assemble_order'])) {
                $update_order_item_action = "UPDATE order_items SET id_order_item_action = 2 WHERE id_order = $order_id";
                mysqli_query($link, $update_order_item_action);

                $updateOrderStatusQuery = "UPDATE orders SET id_order_status = 4 WHERE id_order = $order_id";
                mysqli_query($link, $updateOrderStatusQuery);

                $assembleActionQuery = "INSERT INTO order_actions (id_order, id_order_status, id_user, time_order_actions, date_order_actions) 
                            VALUES ($order_id, 4, $id_user, NOW(), CURDATE())";
                mysqli_query($link, $assembleActionQuery);

                header("Location: /accessory_store/php/lk.php?tab=b");
                exit();
            }

            $order_query = "SELECT o.*, os.name_order_status, u.name, u.surname, u.telephone, u.email 
                    FROM orders o
                    INNER JOIN order_statuses os ON o.id_order_status = os.id_order_status
                    INNER JOIN users u ON o.id_user = u.id_user
                    WHERE o.id_order = $order_id";
            $order_result = mysqli_query($link, $order_query);
            $order = mysqli_fetch_assoc($order_result);

            if ($order['id_order_status'] == 1) {
                $update_status_query = "UPDATE orders SET id_order_status = 3 WHERE id_order = $order_id";
                mysqli_query($link, $update_status_query);
            }

            $items_query = "SELECT oi.*, p.name_product, p.article, p.price, p.image, p.quantity_store
                    FROM order_items oi
                    INNER JOIN products p ON oi.id_product = p.id_product
                    WHERE oi.id_order = $order_id";
            $items_result = mysqli_query($link, $items_query);
        }
        if (isset($order)) : ?>
            <div class="order-details">
                <div class="container">
                    <h1>Заказ № <?= $order['id_order'] ?> от <?= $order['order_date'] ?></h1>
                    <div class="order-info">
                        <div class="order-details-left">
                            <h2>Информация о заказе:</h2> <br>
                            <p>Заказ <?= $order['name_order_status'] ?></p>
                            <?php

                            $action_query = "SELECT u.name AS assembler_name, u.surname AS assembler_surname, oa.time_order_actions, oa.date_order_actions, oa.id_order_status
                                        FROM order_actions oa
                                        INNER JOIN users u ON oa.id_user = u.id_user
                                        WHERE oa.id_order = $order_id AND oa.id_order_status IN (4, 5, 2)";
                            $action_result = mysqli_query($link, $action_query);
                            while ($action_data = mysqli_fetch_assoc($action_result)) {
                                if ($action_data['id_order_status'] == 4) : ?>
                                    <p>Собрано: <span class="light"> <?= $action_data['assembler_name'] ?> <?= $action_data['assembler_surname'] ?> <?= $action_data['date_order_actions'] ?> в <?= $action_data['time_order_actions'] ?></span></p>
                                <?php elseif ($action_data['id_order_status'] == 5) : ?>
                                    <p>Выдано: <span class="light"><?= $action_data['assembler_name'] ?> <?= $action_data['assembler_surname'] ?> <?= $action_data['date_order_actions'] ?> в <?= $action_data['time_order_actions'] ?></span></p>
                                <?php elseif ($action_data['id_order_status'] == 2) : ?>
                                    <p>Отменено: <span class="light"><?= $action_data['assembler_name'] ?> <?= $action_data['assembler_surname'] ?> <?= $action_data['date_order_actions'] ?> в <?= $action_data['time_order_actions'] ?></span></p>
                            <?php endif;
                            } ?>
                        </div>
                        <div class="order-details-right">

                            <h2>Информация о клиенте:</h2> <br>
                            <p> <?= $order['name'] ?> <?= $order['surname'] ?></p>
                            <p> <?= $order['telephone'] ?></p>
                            <p> <?= $order['email'] ?></p>
                        </div>
                    </div>
                    <div class="order-details-products">
                        <form class="details-products-table" method="post" action="order_details.php?id=<?= $order_id ?>">
                            <input type="hidden" name="order_id" value="<?= $order['id_order'] ?>">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Изображение</th>
                                        <th>Название</th>
                                        <th>Артикул</th>
                                        <th>Цена</th>
                                        <th>В заказе</th>
                                        <?php if ($order['id_order_status'] == 1 || $order['id_order_status'] == 3) : ?> 
                                            <th>На складе</th>
                                            <th>Собрать</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = mysqli_fetch_assoc($items_result)) : ?>
                                        <tr>
                                            <td><img class="img-table" src="<?= $item['image'] ?>" alt="<?= $item['name_product'] ?>"></td>
                                            <td><?= $item['name_product'] ?></td>
                                            <td><?= $item['article'] ?></td>
                                            <td><?= $item['price'] ?> ₽</td>
                                            <td><?= $item['quantity'] ?> шт.</td>
                                            <?php if ($order['id_order_status'] == 1 || $order['id_order_status'] == 3) : ?>
                                                <td><?= $item['quantity_store'] ?> шт.</td>
                                                <td>
                                                    <input class="checkbox" type="checkbox" value="<?= $item['id_order_item'] ?>" id="assemble<?= $item['id_order_item'] ?>">
                                                </td>

                                            <?php endif; ?>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </form>
                        <div class="order-action">
                            <?php if ($order['id_order_status'] == 1 || $order['id_order_status'] == 3) : ?>
                                <form class="details-products-table" method="post" action="order_details.php?id=<?= $order_id ?>">
                                    <input type="hidden" name="order_id" value="<?= $order['id_order'] ?>">
                                    <button id="assembleButton" class="button-catalog" type="submit" name="assemble_order">Собрать заказ</button>
                                </form>
                            <?php endif; ?>
                            <form method="post" action="../../config/order_action.php">
                                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                                <?php if ($order['id_order_status'] == 4) { ?>
                                    <button class="button-catalog" type="submit" name="issue">Выдать заказ</button>
                                <?php } ?>
                                <?php if ($order['id_order_status'] != 2 && $order['id_order_status'] != 5) { ?>
                                    <button class="button-reset" type="submit" name="cancel">Отменить заказ</button>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
<?php endif;
    } else {
        header("Location: /accessory_store/php/auth.php");
    }
}
include '../footer.php';
?>