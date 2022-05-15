<?php
    include('php/connect.php');

    $parent_categories = get_parent_categories($db);
    $types = get_types($db);
    $goods = get_most_popular_goods($db);
    
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

    function get_most_popular_goods($db) {
        $sql = "SELECT `id`, `label`, `country`, `price`
                FROM `goods`
                ORDER BY `sold`
                LIMIT 5";

        $query = $db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
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
                            <!-- <ul class="header__search-list">
                                <li><a href="#" class="header__search-link">
                                    <div class="header__search__image"></div>
                                </a></li>
                            </ul> -->
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
                        <span class="_hide"></span>
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
                                    <?php foreach ($parent_categories AS $key => $value): ?>
                                        <li><a href="categories.php?parent_category=<?= $value['id'] ?>" class="aside__link"><?= $value['label'] ?></a></li>
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
                    <div class="content__title">Ваш выбор</div>
                    <div class="content__body">
                        <?php foreach ($goods AS $key => $value): ?>
                            <div class="content__product product" data-id="<?= $value['id'] ?>">
                                <a href="#" class="product__img bgi"><img src="images/goods_image/<?= $value['id'] ?>.jpg" alt="product"></a>
                                <a href="#" class="product__label"><?= $value['label'] ?></a>
                                <div class="product__country"><?= $value['country'] ?></div>
                                <div class="product__price">
                                    <span>Цена:</span>
                                    <span class="price"><?= $value['price'] ?></span>
                                </div>
                                <div class="product__btn">
                                    <button class="product__buy">
                                        Добавить в корзину
                                    </button>
                                </div>
                            </div>
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