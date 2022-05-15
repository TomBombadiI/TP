<?php
    include('php/connect.php');

    $child_categories = get_child_categories($db, $_GET['parent_category']);
    $types = get_types($db);
    $title = get_title($db, $_GET['parent_category']);

    function get_child_categories($db, $parent_category_id) {
        $sql = "SELECT `id`, `label`, `parent_id`
                FROM `categories`
                WHERE `parent_id`= :parent_category_id
                ORDER BY `id`";

        $query = $db->prepare($sql);
        $query->execute([':parent_category_id' => $parent_category_id]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_types($db) {
        $sql = "SELECT `id`, `label`
                FROM `types`";

        $query = $db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_title($db, $key) {
        $sql = "SELECT `label` FROM `categories` WHERE `id` = :key";

        $query = $db->prepare($sql);
        $query->execute([':key' => $key]);

        return $query->fetch(PDO::FETCH_LAZY)[0];
    }
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Главная</title>
</head>

<body>

    <!-- Оболочка -->
    <div class="wrapper">
        <!-- Верхняя часть страницы -->
        <header class="header">
            <div class="header__body _container">
                <div class="header__top">
                    <a href="/4. Apteka42/" class="header__logo"><img src="images/1.header/1.logo.png" alt="logo"></a>
                    <a href="tel:+79502786300" class="header__tel">8 (800) 555 35-35</a>
                    <div class="header__search">
                        <div class="header__search-input">
                            <input type="text" class="search" placeholder="Поиск товара" name="search">
                            <button class="header__search-btn" type="submit">поиск</button>
                        </div>
                        <div class="header__search-example">
                            Например:
                            <ul>
                                <li><a href="#">аравия</a></li>
                                <li><a href="#">северная звезда</a></li>
                                <li><a href="#">пимекролимус</a></li>
                                <li><a href="#">вольтарен</a></li>
                                <li><a href="#">нурофен</a></li>
                            </ul>
                        </div>
                    </div>
                    <a href="cart.php" class="header__cart">
                        <span class="_hide">0</span>
                        <img src="images/1.header/2.cart.svg" alt="Cart">
                        Корзина
                    </a>
                </div>
                <div class="header__bottom">
                    <div class="header__icon menu-icon">
                        <span></span>
                    </div>
                    <div class="header__menu menu">
                        <div class="menu__body">
                        </div>
                    </div>
                    <div class="header__search-input">
                        <input type="text" class="search" placeholder="Поиск товара" name="search">
                        <button class="header__search-btn" type="submit">поиск</button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Основная часть страницы -->
        <main class="main">
            <div class="main__body _container">
                <aside class="aside">
                    <div class="aside__tabs">
                        <a href="#tab_1" class="aside__tab _active">Подобрать</a>
                        <a href="#tab_2" class="aside__tab">Товары</a>
                    </div>
                    <div class="aside__body">
                        <div id="tab_1" class="aside__block _active">
                            <nav class="aside__nav">
                                <ul>
                                     <?php foreach ($child_categories AS $key => $value): ?>
                                        <?php if ($value['id'] === $value['parent_id']): ?>
                                            <li><a href="/4. Apteka42" class="aside__link aside__link_main"><?= $value['label'] ?></a></li>
                                        <?php else: ?>
                                            <li><a href="products.php?category=<?= $value['id'] ?>" class="aside__link"><?= $value['label'] ?></a></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                        </div>
                        <div id="tab_2" class="aside__block">
                            <nav class="aside__nav">
                                <ul>
                                    <?php foreach($types AS $key => $value): ?>
                                        <li><a href="products.php?type=<?= $value['id'] ?>" class="aside__link"><?= $value['label'] ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </aside>
                <content class="content">
                    <div class="content__title"><?= $title ?></div></div>
                    <div class="content__body content__body_three">
                        <?php foreach ($child_categories AS $key => $value): ?>
                            <?php if ($value['id'] !== $value['parent_id']): ?>
                                 <a href="products.php?category=<?= $value['id'] ?>" class="content__product category">
                                    <img src="images/2.main/3.category-img.svg" alt="face">
                                    <span><?= $value['label'] ?></span>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </content>
            </div>
        </main>

        <!-- Нижняя часть страницы -->
        <footer class="footer">

        </footer>

        <!-- Модальные окна -->
    </div>

    <!-- Файл JavaScript-сценариев -->
    <script src="script/script.js"></script>

</body>

</html>