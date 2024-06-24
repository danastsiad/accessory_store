<?php
session_start();
include '../header.php';
include '../../config/db.php';
if (!isset($_SESSION['id_user'])) {
    header("Location: /accessory_store/php/auth.php");
    exit();
}
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $user_query = "SELECT * FROM users WHERE id_user = $user_id";
    $user_result = mysqli_query($link, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);

    $orders_query = "SELECT o.*, os.name_order_status 
    FROM orders o
    INNER JOIN order_statuses os ON o.id_order_status = os.id_order_status WHERE id_user = $user_id ORDER BY o.order_date DESC,
                                o.id_order DESC ";
    $orders_result = mysqli_query($link, $orders_query);

    if (!empty($searchTerm)) {
        $orders_query .= " WHERE o.id_order LIKE '%$searchTerm%'";
    }

?>
    <div class="user-order">
        <div class="container">
            <div class="tabs-left">
                <h1>Заказы клиента: <?= $user_data['name'] ?> <?= $user_data['surname'] ?> <?= $user_data['telephone'] ?> </h1>
                <?php $searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; ?>
                <form id="searchForm">
                    <input type="text" id="searchInput" placeholder="Поиск по номеру заказа" value="<?= $searchTerm ?>">
                </form>

                <?php if (mysqli_num_rows($orders_result) > 0) : ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Заказ</th>
                                <th>Дата</th>
                                <th>Кол-во товаров</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th>Подробности</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <?php while ($order = mysqli_fetch_assoc($orders_result)) :
                                $totalQuantityQuery = "SELECT SUM(quantity) AS total_quantity FROM order_items WHERE id_order = {$order['id_order']}";
                                $totalQuantityResult = mysqli_query($link, $totalQuantityQuery);
                                $totalQuantityData = mysqli_fetch_assoc($totalQuantityResult);
                            ?>
                                <tr>
                                    <td><?= $order['id_order'] ?></td>
                                    <td><?= $order['order_date'] ?></td>
                                    <td><?= $totalQuantityData['total_quantity'] ?> шт.</td>
                                    <td><?= $order['order_price'] ?> ₽</td>
                                    <td class="table-bold"><?= $order['name_order_status'] ?></td>
                                    <td><a href="./order_details.php?id=<?= $order['id_order'] ?>"><u>Посмотреть</u></a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p>У клиента <?= $user_data['name'] ?> <?= $user_data['surname'] ?> пока нет заказов.</p>
                <?php endif; ?>
                <script src="/accessory_store/js/script.js"></script>
                </body>

                </html>
            <?php
        }
            ?>