<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/admin_panel.css">
    <title>Панель администратора</title>
</head>

<body>

    <!-- Оболочка -->
    <div class="wrapper">
        <form action="#" class="form">
            <div class="form__good">
                <a href='#' class="form__delete" onclick="deleteGood(this, event)">x</a>
                <div class="form__img">
                    <input name="file" type="file" onchange="uploadFile(this)">
                </div>
                <div class="form__item">
                    <input name="label" type="text" placeholder="Название товара">
                </div>
                <div class="form__item">
                    <input name="country" type="text" placeholder="Страна товара">
                </div>
                <div class="form__item">
                    <input name="price" type="text" placeholder="Цена товара">
                </div>
                <div class="form__item">
                    <input name="sale" type="text" placeholder="Скидка товара">
                </div>
                <div class="form__item">
                    <input name="amount" type="text" placeholder="Количество товара на складе">
                </div>
                <select name="category" class="form__item">
                    <option value="Категория" selected>Категория</option>
                    <option value="Категория_1" selected>Категория_1</option>
                    <option value="Категория_2" selected>Категория_2</option>
                    <option value="Категория_3" selected>Категория_3</option>
                </select>
            </div>
            <a href="#" class="form__add" onclick="addGood(this, event)">
                +
            </a>
            <button class="form__submit" type="submit" onclick="submitForm(this, event)">Добавить товары</button>
        </form>
    </div>

    <!-- Файл JavaScript-сценариев -->
    <script src="script/admin_panel.js"></script>

</body>

</html>