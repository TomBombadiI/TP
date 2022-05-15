<?php
    include_once('connect.php');

    if (isset($_GET['category'])) {
        $key = $_GET['category'];
        $key_type = 'cat';
    } elseif (isset($_GET['type'])) {
        $key = $_GET['type'];
        $key_type = 'type';
    }

    if (isset($_POST['type_sort'])) {
        $key_type = $_POST['key_type'];
        $goods = get_goods($db, $_POST['key'], $key_type, $_POST['type_sort'], $_POST['filter']);
    } else {
        $goods = get_goods($db, $key, $key_type);
    }

    function get_goods($db, $key, $key_type, $type_sort = 'byPopularity', $filter = 'none') {
        $sql = "SELECT `id`, `label`, `country`, `price`
                FROM `goods`";

        if ($key_type === 'cat') {
            $sql .= ' WHERE `category_id` = :key';
        } else {
             $sql .= ' WHERE `type_id` = :key';
        }

        switch ($filter) {
            case 'inStock':
                $sql .= ' AND `amount` > 0';
                break;
            case 'withSale':
                $sql .= ' AND `sale` > 0';
                break;
            case 'all':
                $sql .= ' AND `amount` > 0 AND `sale` > 0';
                break;
        }
 
        switch ($type_sort) {
            case 'byPopularity':
                $sql .= ' ORDER BY `amount`';
                break;
            case 'byPrice':
                $sql .= ' ORDER BY `price`';
                break;
            case 'byPriceDesc':
                $sql .= ' ORDER BY `price` DESC';
                break;
            case 'byName':
                $sql .= ' ORDER BY `label`';
                break;
            case 'byNameDesc':
                $sql .= ' ORDER BY `label` DESC';
                break;
        }   

        $query = $db->prepare($sql);
        $query->execute([':key' => $key]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
?>

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
