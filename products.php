<?php
    include('php/connect.php');

    if (isset($_GET['category'])) {
        $categories = get_categories($db, $_GET['category']);
        $total_products = get_total_products($db, $_GET['category'], 'cat');
        $title = get_title($db, $_GET['category'], 'cat');
    } else {
        $parent_categories = get_parent_categories($db);
        $total_products = get_total_products($db, $_GET['type'], 'type');
        $title = get_title($db, $_GET['type'], 'type');
    }
    $total_products = $total_products . ' ' . conver_word_endings($total_products, ['товар', 'товара', 'товаров']);
    $types = get_types($db);

    function get_total_products($db, $key, $key_type) {
        $sql = "SELECT COUNT(1) FROM `goods`";

        if ($key_type === 'cat') {
            $sql .= ' WHERE `category_id` = :key';
        } else {
            $sql .= ' WHERE `type_id` = :key';
        }
        $query = $db->prepare($sql);
        $query->execute([':key' => $key]);

        return $query->fetch(PDO::FETCH_LAZY)[0];
    }

    function get_title($db, $key, $key_type) {
        $sql = "SELECT `label`";

        if ($key_type === 'cat') {
            $sql .= ' FROM `categories` WHERE `id` = :key';
        } else {
            $sql .= ' FROM `types` WHERE `id` = :key';
        }
        $query = $db->prepare($sql);
        $query->execute([':key' => $key]);

        return $query->fetch(PDO::FETCH_LAZY)[0];
    }

    function get_categories($db,  $category_id) {
        $sql = "SELECT `id`, `parent_id`, `label` 
                FROM `categories` 
                WHERE `parent_id` = (SELECT `parent_id` FROM `categories` WHERE `id` = :category_id)";
        
        $query = $db->prepare($sql);
        $query->execute([':category_id' => $category_id]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_parent_categories($db) {
        $sql = "SELECT `id`, `label`
                FROM `categories`
                WHERE `parent_id` = `id`";

        $query = $db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_types($db) {
        $sql = "SELECT `id`, `label`
                FROM `types`";

        $query = $db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function conver_word_endings($num, $words) {
        $cases = [2, 0, 1, 1, 1, 2];

        return $words[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Товары</title>
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
                        <?php if (isset($categories)): ?>
                            <a href="#tab_1" class="aside__tab _active">Подобрать</a>
                            <a href="#tab_2" class="aside__tab">Товары</a>
                        <?php else: ?>
                            <a href="#tab_1" class="aside__tab">Подобрать</a>
                            <a href="#tab_2" class="aside__tab _active">Товары</a>
                        <?php endif; ?>
                    </div>
                    <div class="aside__body">
                        <?php if (isset($categories)): ?>
                            <div id="tab_1" class="aside__block _active">
                                <nav class="aside__nav">
                                    <ul>
                                        <?php foreach ($categories AS $key => $value): ?>
                                            <?php if ($value['id'] === $value['parent_id']): ?>
                                                <li><a href="/4. Apteka42" class="aside__link aside__link_main"><?= $value['label'] ?></a></li>
                                            <?php elseif ($value['id'] === $_GET['category']): ?>
                                                <li><a href="products.php?category=<?= $value['id'] ?>" class="aside__link _active"><?= $value['label'] ?></a></li>
                                            <?php else: ?>
                                                <li><a href="products.php?category=<?= $value['id'] ?>" class="aside__link _hide"><?= $value['label'] ?></a></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button class="aside__more-categories">
                                        <span>все подкатегории</span>
                                        <img src="images/2.main/5.arrow.svg" alt="arrow">
                                    </button>
                                </nav>
                            </div>
                            <div id="tab_2" class="aside__block">
                                <nav class="aside__nav">
                                    <ul>
                                        <?php foreach ($types AS $key => $value): ?>
                                            <?php if (isset($_GET['type']) AND $value['id'] === $_GET['type']): ?>
                                                <li><a href="products.php?type=<?= $value['id'] ?>" class="aside__link _active"><?= $value['label'] ?></a></li>
                                            <?php else: ?>
                                                <li><a href="products.php?type=<?= $value['id'] ?>" class="aside__link"><?= $value['label'] ?></a></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </nav>
                            </div>
                        <?php else: ?>
                            <div id="tab_1" class="aside__block">
                                <nav class="aside__nav">
                                    <ul>
                                        <?php foreach ($parent_categories AS $key => $value): ?>
                                            <li><a href="categories.php?parent_category=<?= $value['id'] ?>" class="aside__link"><?= $value['label'] ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button class="aside__more-categories">
                                        <span>все подкатегории</span>
                                        <img src="images/2.main/5.arrow.svg" alt="arrow">
                                    </button>
                                </nav>
                            </div>
                            <div id="tab_2" class="aside__block _active">
                                <nav class="aside__nav">
                                    <ul>
                                        <?php foreach ($types AS $key => $value): ?>
                                            <?php if ($value['id'] === $_GET['type']): ?>
                                                <li><a href="products.php?type=<?= $value['id'] ?>" class="aside__link _active"><?= $value['label'] ?></a></li>
                                            <?php else: ?>
                                                <li><a href="products.php?type=<?= $value['id'] ?>" class="aside__link"><?= $value['label'] ?></a></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </nav>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="aside__filter">
                        <div class="aside__filter-price">
                        </div>
                    </div>
                </aside>
                <content class="content">
                    <div class="content__title"><?= $title ?> <span>(<?= $total_products ?>)</span></div>
                    <div class="content__setting">
                        <div class="content__sort">
                            <a href="#" class="content__sort-title">По популярности</a>
                            <ul class="content__sort-menu">
                                <li>
                                    <a href="#" class="content__sort-item _active" data-type-sort="byPopularity">По популярности</a>
                                    <span></span>
                                </li>
                                <li>
                                    <a href="#" class="content__sort-item" data-type-sort="byPrice">Сначала дешёвые</a>
                                    <span></span>
                                </li>
                                <li>
                                    <a href="#" class="content__sort-item" data-type-sort="byPriceDesc">Сначала дорогие</a>
                                    <span></span>
                                </li>
                                <li>
                                    <a href="#" class="content__sort-item" data-type-sort="byName">От А до Я</a>
                                    <span></span>
                                </li>
                                <li>
                                    <a href="#" class="content__sort-item" data-type-sort="byNameDesc">От Я до А</a>
                                    <span></span>
                                </li>
                            </ul>
                        </div>
                        <div class="content__checkboxes">
                            <div class="content__checkbox">
                                <input type="checkbox" name="exist" class="content__sort-item" data-filter="inStock">
                                <span></span>
                                В наличии
                            </div>
                            <div class="content__checkbox">
                                <input type="checkbox" name="with-sale" class="content__sort-item" data-filter="withSale">
                                <span></span>
                                Со скидкой
                            </div>
                        </div>
                    </div>
                    <div class="content__body content__body_no-mg">
                        <?php include('php/goods.php'); ?>
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