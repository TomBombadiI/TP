// Открытие/закрытие меню
const menuIcon = document.querySelector('.menu-icon'),
    menu = document.querySelector('.menu'),
    body = document.body;

if (menuIcon) {
    menuIcon.addEventListener('click', function () {
        toggleMenu();
    });

    menu.addEventListener('click', function (event) {
        if (event.target.tagName == 'A' || event.target.classList.contains('menu')) {
            toggleMenu();
        }
    });
}

function toggleMenu() {
    menuIcon.classList.toggle('_active');
    menu.classList.toggle('_active');
    body.classList.toggle('_lock');
}

// Адаптивные изображения
const bgi = document.querySelectorAll('.bgi');

for (let index = 0; index < bgi.length; index++) {
    const item = bgi[index],
        img = item.firstElementChild;

    item.style.backgroundImage = "url('" + img.getAttribute('src') + "')";
    img.remove();
}

// Простая валидация
const validateForms = document.querySelectorAll('.validate');

for (let index = 0; index < validateForms.length; index++) {
    const item = validateForms[index],
        inputs = item.querySelectorAll('input, textarea');

    item.addEventListener('submit', function (event) {
        for (let index = 0; index < inputs.length; index++) {
            const input = inputs[index];

            if (input.value == 0) input.classList.add('error');
        }
        if (item.querySelectorAll('.error')) event.preventDefault();
    });
}

function uploadFile(item) {
    const file = item.files[0];
    const imgBlock = item.parentNode;

    if (!['image/jpeg'].includes(file.type)) {
        alert('Разрешены только изображения формата JPG/JPEG.');
        item.value = '';
        return;
    } else if (file.size > 5 * 1024 * 1024) {
        alert('Файл должен быть менее 5 МБ.');
        return;
    }

    let reader = new FileReader();
    reader.onload = function (e) {
        imgBlock.insertAdjacentHTML('afterbegin', `<img src="${e.target.result}" alt='Фото'>`)
    };
    reader.onerror = function (e) {
        alert('Ошибка!');
    }
    reader.readAsDataURL(file);
}

const goodBlock = document.querySelector('.form__good');
const form = document.querySelector('.form');

let number = 0;

function renderGood(number) {
    return `<div class="form__good">
                <a href='#' class="form__delete" onclick="deleteGood(this, event)">x</a>
                <div class="form__img">
                    <input name="file_${number}" type="file" onchange="uploadFile(this)">
                </div>
                <div class="form__item">
                    <input name="label_${number}" type="text" placeholder="Название товара">
                </div>
                <div class="form__item">
                    <input name="country_${number}" type="text" placeholder="Страна товара">
                </div>
                <div class="form__item">
                    <input name="price_${number}" type="text" placeholder="Цена товара">
                </div>
                <div class="form__item">
                    <input name="sale_${number}" type="text" placeholder="Скидка товара">
                </div>
                <div class="form__item">
                    <input name="amount_${number}" type="text" placeholder="Количество товара на складе">
                </div>
                <select name="category_${number}" class="form__item">
                    <option value="Категория" selected>Категория</option>
                    <option value="Категория_1" selected>Категория_1</option>
                    <option value="Категория_2" selected>Категория_2</option>
                    <option value="Категория_3" selected>Категория_3</option>
                </select>
            </div>`
}

function addGood(item, event) {
    event.preventDefault();

    item.insertAdjacentHTML('beforebegin', renderGood(number));
    number++;
}

function deleteGood(item, event) {
    event.preventDefault();

    item.parentNode.remove();
}

async function submitForm(item, event){
    event.preventDefault();

    const inputs = document.querySelectorAll('input');

    for (let index = 0; index < inputs.length; index++) {
        const input = inputs[index];
        
        if (input.value === '') {
            input.parentNode.classList.add('error');
        } else {
            input.parentNode.classList.remove('error');
        }
    }

    if (!document.querySelectorAll('.error').length) {
        const goods = document.querySelectorAll('.form__good');
        const dataSend = new FormData(item.closest('form'));

        let response = await fetch('/4. Apteka42/php/add_goods.php', {
            method: 'POST',
            body: dataSend,
        });
        if (response.ok) {
            // location.reload();
            console.log(await response.text());
        } else {
            alert('Возникла ошибка!');
        }
    }
}