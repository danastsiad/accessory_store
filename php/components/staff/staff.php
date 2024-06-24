<?php
include './config/db.php';

$id_user = $_SESSION['id_user'];
$query = "SELECT id_status FROM users WHERE id_user = $id_user";
$result = mysqli_query($link, $query);

if ($result) {
  $user_data = mysqli_fetch_assoc($result);
  $id_status = $user_data['id_status'];

  if ($id_status >= 2) {

?>
    <div class="staff">
      <div class="container">
        <div class="tabs-left">
          <ul class="nav nav-tabs">
            <?php
            $tab = isset($_GET['tab']) ? $_GET['tab'] : 'a';
            ?>
            <li<?= ($tab === 'a') ? ' class="active"' : ''; ?>><a href="#a" data-target="a">
                <p>Статистика</p>
              </a></li>
              <li<?= ($tab === 'b') ? ' class="active"' : ''; ?>><a href="#b" data-target="b">
                  <p>Заказы</p>
                </a></li>
                <li<?= ($tab === 'c') ? ' class="active"' : ''; ?>><a href="#c" data-target="c">
                    <p>Товары</p>
                  </a></li>
                  <li<?= ($tab === 'd') ? ' class="active"' : ''; ?>><a href="#d" data-target="d">
                      <p>Клиенты</p>
                    </a></li>
                    <li<?= ($tab === 'e') ? ' class="active"' : ''; ?>><a href="#e" data-target="e">
                        <p>Сотрудники</p>
                      </a></li>
          </ul>
        </div>
        <div class="tab-content">
          <div class="tab<?= ($tab === 'a') ? ' active' : ''; ?>" id="a">
            <?php

            $statisticsQuery = "SELECT 
                        SUM(CASE WHEN id_order_status = 5 THEN 1 ELSE 0 END) AS num_completed_orders,
                        SUM(CASE WHEN id_order_status = 5 THEN order_price ELSE 0 END) AS total_completed_price,
                        SUM(CASE WHEN id_order_status = 1 THEN 1 ELSE 0 END) AS num_new_orders,
                        SUM(CASE WHEN id_order_status = 4 THEN 1 ELSE 0 END) AS num_ready_orders
                    FROM orders";

            $statisticsResult = mysqli_query($link, $statisticsQuery);
            $statisticsData = mysqli_fetch_assoc($statisticsResult);
            ?>
            <div class="lk-title">
              <h1>Статистика заказов</h1>
              <h2><a href="./config/exit.php" class="exit">Выйти</a></h2>
            </div>
            <div class="statistics">
              <div class="">
                <h2>Выполненные:</h2><br>
                <h2> <?= $statisticsData['num_completed_orders'] ?></h2>
              </div>
              <div class="">
                <h2>Новые:</h2><br>
                <h2> <?= $statisticsData['num_new_orders'] ?></h2>
              </div>
            </div>
            <div class="statistics">
              <div class="">
                <h2>Выручка:</h2> <br>
                <h2> <?= $statisticsData['total_completed_price'] ?> ₽</h2>
              </div>

              <div class="">
                <h2>Готовые к выдаче:</h2><br>
                <h2> <?= $statisticsData['num_ready_orders'] ?></h2>
              </div>
            </div>
          </div>
          <div class="tab<?= ($tab === 'b') ? ' active' : ''; ?>" id="b">
            <div class="lk-title">
              <h1>Список заказов</h1>
            </div>
            <?php $searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; ?>
            <form id="searchForm">
              <input type="text" id="searchInput" placeholder="Поиск по номеру заказа" value="<?= $searchTerm ?>">
            </form>

            <table class="admin-table">
              <thead>
                <tr>
                  <th>Заказ</th>
                  <th>Дата</th>
                  <th>Кол-во товаров</th>
                  <th>Сумма</th>
                  <th>Статус</th>
                  <th>Действие</th>

                </tr>
              </thead>
              <tbody id="ordersTableBody">
                <?php

                $ordersQuery = "SELECT o.*, os.name_order_status 
                            FROM orders o
                            INNER JOIN order_statuses os ON o.id_order_status = os.id_order_status";

                if (!empty($searchTerm)) {
                  $ordersQuery .= " WHERE o.id_order LIKE '%$searchTerm%'";
                }

                $ordersQuery .= " ORDER BY 
                                CASE 
                                    WHEN o.id_order_status = 1 THEN 1
                                    WHEN o.id_order_status = 3 THEN 2
                                    WHEN o.id_order_status = 4 THEN 3
                                    WHEN o.id_order_status = 5 THEN 4
                                    WHEN o.id_order_status = 2 THEN 5
                                    ELSE 6
                                END,
                                o.order_date DESC,
                                o.id_order DESC";
                $ordersResult = mysqli_query($link, $ordersQuery);

                while ($order = mysqli_fetch_assoc($ordersResult)) : ?>
                  <?php

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
                    <td><a href="./components/staff/order_details.php?id=<?= $order['id_order'] ?>"><u>Посмотреть</u></a></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
          <div class="tab<?= ($tab === 'c') ? ' active' : ''; ?>" id="c">
            <div class="lk-title">
              <h1>Список товаров</h1>
              <h2><a href="./components/staff/add_product.php">Новый товар</a></h2>
            </div>

            <?php $searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; ?>
            <form id="productSearchForm">
              <input type="text" id="productSearchInput" placeholder="Поиск по названию или артикулу товара" value="<?= $searchTerm ?>">
            </form>
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Изображение</th>
                  <th>Подкатегория</th>
                  <th>Название</th>
                  <th>Артикул</th>
                  <th>Цена</th>
                  <th>Доступно</th>
                  <th>На складе</th>
                  <th>Действие</th>
                </tr>
              </thead>
              <tbody id="productTableBody">
                <?php
                $productsQuery = "SELECT p.*, pt.name_product_type, sc.name_subcategory 
            FROM products p 
            JOIN product_types pt ON p.id_product_type = pt.id_product_type 
            JOIN subcategories sc ON pt.id_subcategories = sc.id_subcategories
            ORDER BY p.id_product DESC";

                if (!empty($searchTerm)) {
                  $productsQuery .= " WHERE name_product LIKE '%$searchTerm%' OR article LIKE '%$searchTerm%'";
                }

                $productsResult = mysqli_query($link, $productsQuery);

                while ($product = mysqli_fetch_assoc($productsResult)) : ?>
                  <tr>
                    <td><img class="admin-table-img" src="<?= $product['image'] ?>" alt="<?= $item['name_product'] ?>"></td>
                    <td><?= $product['name_subcategory'] ?>: <br> <?= $product['name_product_type'] ?> </td>
                    <td><?= $product['name_product'] ?></td>
                    <td><?= $product['article'] ?></td>
                    <td><?= $product['price'] ?> ₽</td>
                    <td><?= $product['quantity'] ?> шт.</td>
                    <td><?= $product['quantity_store'] ?> шт.</td>
                    <td>
                      <a href="./components/staff/edit_product.php?id=<?= $product['id_product'] ?>"><u>Изменить</u></a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
          <div class="tab<?= ($tab === 'd') ? ' active' : ''; ?>" id="d">
            <div class="lk-title">
              <h1>Список клиентов</h1>
            </div>
            <?php $searchClient = isset($_GET['search']) ? $_GET['search'] : ''; ?>
            <form id="clientSearchForm">
              <input type="text" id="clientSearchInput" placeholder="Поиск по имени или фамилии клиента" value="<?= $searchClient ?>">
            </form>
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Имя</th>
                  <th>Фамилия</th>
                  <th>Телефон</th>
                  <th>Email</th>
                  <th>Кол-во заказов</th>
                  <th>Заказы</th>
                </tr>
              </thead>
              <tbody id="clientsTableBody">
                <?php
                $query = "SELECT  u.id_user, u.name, u.surname, u.telephone, u.email, COUNT(o.id_order) AS order_count
                      FROM users u
                      LEFT JOIN orders o ON u.id_user = o.id_user
                      WHERE u.id_status = 1
                      GROUP BY u.id_user";
                $result = mysqli_query($link, $query);
                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                  <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['surname'] ?></td>
                    <td><?= $row['telephone'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['order_count'] ?></td>
                    <td><a href="./components/staff/user_order.php?id=<?= $row['id_user'] ?>"><u>Посмотреть</u></a></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <div class="tab<?= ($tab === 'e') ? ' active' : ''; ?>" id="e">
            <div class="lk-title">
              <h1>Список сотрудников</h1>
            </div>
            <?php $searchStaff = isset($_GET['search']) ? $_GET['search'] : ''; ?>
            <form id="employeeSearchForm">
              <input type="text" id="employeeSearchInput" placeholder="Поиск по имени или фамилии сотрудника" value="<?= $searchStaff ?>">
            </form>
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Имя</th>
                  <th>Фамилия</th>
                  <th>Телефон</th>
                  <th>Email</th>
                  <th>Собрано</th>
                  <th>Выдано</th>
                </tr>
              </thead>
              <tbody id="employeesTableBody">
                <?php
                $query = "SELECT u.name, u.surname, u.telephone, u.email, 
                          COUNT(CASE WHEN oa.id_order_status = 4 THEN 1 END) AS assembled_orders,
                          COUNT(CASE WHEN oa.id_order_status = 5 THEN 1 END) AS issued_orders
                          FROM users u
                          LEFT JOIN order_actions oa ON u.id_user = oa.id_user
                          WHERE u.id_status >= 2
                          GROUP BY u.id_user";
                $result = mysqli_query($link, $query);
                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                  <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['surname'] ?></td>
                    <td><?= $row['telephone'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['assembled_orders'] ?></td>
                    <td><?= $row['issued_orders'] ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

<?php
  } else {
    header("Location: /accessory_store/php/auth.php");
  }
}
?>