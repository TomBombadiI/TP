<?php
    $data = convert_post_data($_POST);
    add_data_in_db($data);

    function convert_post_data($post_date) {
        $result = [];
        $counter = 0;

        for ($i = 0; $i < count($_FILES); $i++) {
            if ($i == 0) {
                $result[$i] = [
                    'label' => $post_date['label'],
                    'country' => $post_date['country'],
                    'price' => $post_date['price'],
                    'sale' => $post_date['sale'],
                    'amount' => $post_date['amount'],
                    'category' => $post_date['category']
                ];
            } else {
                $counter = $i-1;
                $result[$i] = [
                    'label' => $post_date['label' . '_' . $counter],
                    'country' => $post_date['country' . '_' . $counter],
                    'price' => $post_date['price' . '_' . $counter],
                    'sale' => $post_date['sale' . '_' . $counter],
                    'amount' => $post_date['amount' . '_' . $counter],
                    'category' => $post_date['category' . '_' . $counter]
                ];
            }
        }

        return $result;
    }

    function add_data_in_db($db, $data) {
        $sql = '';

        foreach ($data as $key => $value) {
            $sql .= "INSERT INTO `goods` (`label`, `country`, `price`, `sale`, `amount`, `category_id`)
                     VALUES (:label, :country, :price, :sale, :amount, :category_id);";
        }

        $query = $db->prepare($sql);
    }



    var_dump($data);
    var_dump($_FILES);
?>