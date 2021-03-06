// Открытие/закрытие меню
const menuIcon = document.querySelector('.menu-icon'),
    menu = document.querySelector('.menu'),
    body = document.body;

if (menuIcon) {
    menuIcon.addEventListener('click', function () {
        toggleMenu();
    });

    menu.addEventListener('click', function (event) {
        if ((event.target.tagName == 'A' && !event.target.classList.contains('aside__tab')) || event.target.classList.contains('menu')) {
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
function setBGI(){
    const bgi = document.querySelectorAll('.bgi');

    for (let index = 0; index < bgi.length; index++) {
        const item = bgi[index],
            img = item.firstElementChild;

        item.style.backgroundImage = "url('" + img.getAttribute('src') + "')";
    }
}

setBGI();

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

// Табы
const tabs = document.querySelectorAll('.aside__tab');

let activeTab = document.querySelector('.aside__tab._active'),
    activeTabContent = document.querySelector(activeTab.getAttribute('href'));

for (let index = 0; index < tabs.length; index++) {
    const tab = tabs[index];
    
    tab.addEventListener('click', function(e){
        e.preventDefault();

        if (!tab.classList.contains('_active')) toggleTab(tab);
    });
}

function toggleTab(tab){
    const tabContent = document.querySelector(tab.getAttribute('href'));

    activeTab.classList.remove('_active');
    tab.classList.add('_active');
    activeTab = tab;
    activeTabContent.classList.remove('_active');
    tabContent.classList.add('_active');
    activeTabContent = tabContent;
}

const parent_original = document.querySelector('.aside');
const parent = document.querySelector('.menu__body');
const item_1 = parent_original.querySelector('.aside__tabs');
const item_2 = parent_original.querySelector('.aside__body');

window.addEventListener('resize', move);

function move() {
    const viewport_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    if (viewport_width <= 992) {
        if (!item_1.classList.contains('done')) {
            parent.insertBefore(item_1, parent.children[2]);
            item_1.classList.add('done');
        }
        if (!item_2.classList.contains('done')) {
            parent.insertBefore(item_2, parent.children[2]);
            item_2.classList.add('done');
        }
    } else {
        if (item_1.classList.contains('done')) {
            parent_original.insertBefore(item_1, parent_original.children[2]);
            item_1.classList.remove('done');
        }
        if (item_2.classList.contains('done')) {
            parent_original.insertBefore(item_2, parent_original.children[2]);
            item_2.classList.remove('done');
        }
    }
}

move();

// Меню сортировки
const sortBtn = document.querySelector('.content__sort-title'),
      menuSort = document.querySelector('.content__sort-menu');

if (sortBtn) {
    let menuSortItems = menuSort.querySelectorAll('.content__sort-item'),
        menuSortActiveItem = menuSort.querySelector('.content__sort-item._active');

    sortBtn.addEventListener('click', function(){
        menuSort.classList.toggle('_active');
    });
    for (let index = 0; index < menuSortItems.length; index++) {
        const el = menuSortItems[index];
        
        el.addEventListener('click', function(e){
            e.preventDefault();

            menuSort.classList.toggle('_active');
            menuSortActiveItem.classList.remove('_active');
            el.classList.add('_active');
            menuSortActiveItem = el;
            sortBtn.innerText = el.innerText;
        });
    }
}

const moreCategories = document.querySelector('.aside__more-categories');

if (moreCategories){
    moreCategories.addEventListener('click', function(){
        const categories = moreCategories.previousElementSibling.children,
              text = moreCategories.querySelector('span');

        for (let index = 0; index < categories.length; index++) {
            const el = categories[index].querySelector('a');
            
            if (!el.classList.contains('_active') && !el.classList.contains('aside__link_main')) {
                el.classList.toggle('_hide');
            }
        }
        moreCategories.classList.toggle('_active');
        if (text.innerText == 'все подкатегории'.toUpperCase()) {
            text.innerText = 'свернуть подкатегории'
        } else {
            text.innerText = 'все подкатегории'
        }
    });
}

// Корзина

// 1. Инициализация корзины
let cart = localStorage.getItem('cart');

if (cart) {
    cart = JSON.parse(cart);
} else {
    cart = {};
    localStorage.setItem('cart', JSON.stringify(cart));
}
let countGoodsInCart = lengthObject(cart);
if (countGoodsInCart) {
    document.querySelector('.header__cart span').innerText = countGoodsInCart;
    document.querySelector('.header__cart span').classList.remove('_hide');
}
// 2. Изменение кнопки на карточке товара, если он есть в корзине при загрузке страницы
updateProducts();

function updateProducts(){
    const products = document.querySelectorAll('.product');

    for (let index = 0; index < products.length; index++) {
        const el = products[index],
              id = el.dataset.id,
              inCart = el.classList.contains('product_cart'),
              bnt = el.querySelector('.product__buy');

        if (cart['p_' + id]){
            updateBtn(bnt, el, inCart);
            el.querySelector('.count').innerText = cart['p_' + id]['count'];
        } else if (inCart) {
            el.remove();
        }
    }
}

function updateBtn(btn, product, inCart = false) {
    const productCountEl = '<div class="product__count product__count_center"><button id="plus" class="product__count-plus plus plus_minus"></button><span class="count">1</span><button id="minus" class="product__count-minus plus"></button></div>',
          productBuyEl = '<button class="product__buy">Добавить в корзину</button >',
          productBtnContainer = product.querySelector('.product__btn');

    if (!inCart){
        if (cart['p_' + product.dataset.id]) {
            productBtnContainer.innerHTML = productCountEl;
        } else {
            productBtnContainer.innerHTML = productBuyEl;
        }
    } else {
        if (cart['p_' + product.dataset.id]) {
            product.querySelector('.price').innerText = (cart['p_' + product.dataset.id]['count'] * cart['p_' + product.dataset.id]['price']).toFixed(2);
        } else {
            product.remove();
        }
    }
}

// 3. Добавление товара в корзину, увеличение и уменьшение количества товара
const content = document.querySelector('.content'),
    totalContainer = document.querySelector('.content__total');

let totalCountBlock, totalPriceBlock, totalBlock, countProductBlock;
if (totalContainer) {
    totalCountBlock = totalContainer.querySelector('#totalCount'),
    totalPriceBlock = totalContainer.querySelector('#totalPrice'),
    totalBlock = totalContainer.querySelector('#total'),
    countProductBlock = document.querySelector('.content__title .count');
}
    
content.addEventListener('click', function (e) {
    const target = e.target;

    if (target.classList.contains('product__buy')) {
        const product = target.closest('.product');

        addProductToCart(product);
        renderProductsInCart();
        updateBtn(target, product)
    } else if (target.classList.contains('plus')) {
        const product = target.closest('.product'),
              inCart = product.classList.contains('product_cart'),
              price = product.querySelector('.price');

        if (target.classList.contains('plus_minus')) {
            const count = plusCountProduct('-', product.dataset.id),
                countEl = product.querySelector('.count');

            if (count == 0){
                updateBtn(target.closest('.product__count'), product, inCart);
                if (!lengthObject(cart)) {
                    document.querySelector('.header__cart span').classList.add('_hide');
                } else {
                    document.querySelector('.header__cart span').innerText = lengthObject(cart);
                }
                updateHistoryProducts(); 
                if (totalContainer) updateTotal();

            } else {
                countEl.innerText = count;
                if (inCart) {
                    price.innerText = (cart['p_' + product.dataset.id]['price'] * count).toFixed(2);
                    totalPriceBlock.innerText = (+totalPriceBlock.innerText - +cart['p_' + product.dataset.id]['price']).toFixed(2);
                    totalBlock.innerText = (+totalBlock.innerText - +cart['p_' + product.dataset.id]['price']).toFixed(2);
                };
            }
        } else {
            const count = plusCountProduct('+', product.dataset.id),
                countEl = product.querySelector('.count');

            countEl.innerText = count;
            if (inCart) {
                price.innerText = (cart['p_' + product.dataset.id]['price'] * count).toFixed(2);
                totalPriceBlock.innerText = (+totalPriceBlock.innerText + +cart['p_' + product.dataset.id]['price']).toFixed(2);
                totalBlock.innerText = (+totalBlock.innerText + +cart['p_' + product.dataset.id]['price']).toFixed(2);
            };
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateProducts();
        if (totalContainer) {
            updateTotal();
        }
    } else if (target.classList.contains('product__delete')) {
        const product = target.closest('.product'),
              id = product.dataset.id,
              lenghtHistoryCart = lengthObject(historyCart);
        
        product.remove();
        if (lenghtHistoryCart == 4 && !historyCart['p_' + id]) {
            deleteLastElObject(historyCart, lenghtHistoryCart);
            historyCart['p_' + id] = cart['p_' + id];
        } else if (lenghtHistoryCart == 5) {
            delete historyCart['p_' + id];
            historyCart['p_' + id] = cart['p_' + id];
        } else {
            historyCart['p_' + id] = cart['p_' + id];
        }
        historyCart['p_' + id]['count'] = 0;
        localStorage.setItem('historyCart', JSON.stringify(historyCart));
        delete cart['p_' + id];
        updateTotal();
        localStorage.setItem('cart', JSON.stringify(cart));
        updateHistoryProducts();
    } else if (target.tagName == 'INPUT' && !target.parentNode.classList.contains('content__checkbox')) {
        const productID = target.closest('.product').dataset.id;

        if (cart['p_' + productID]['selected']){
            cart['p_' + productID]['selected'] = false;
        } else{
            cart['p_' + productID]['selected'] = true;
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateTotal();
    } else if (target.classList.contains('content__sort-item')) {
        const filters = document.querySelectorAll('input.content__sort-item:checked');
        const typeSort = document.querySelector('.content__sort-item._active').dataset.typeSort;

        if (filters.length > 1) {
            sortingGoods(typeSort, 'all');
        } else if(filters.length === 0) {
            sortingGoods(typeSort);
        } else {
            sortingGoods(typeSort, filters[0].dataset.filter);
        }
    }
});

async function sortingGoods(typeSort, filter = 'none') {
    const contentBody = document.querySelector('.content__body');
    let key, key_type;
    if (get('category')) {
        key = get('category');
        key_type = 'cat';
    } else {
        key = get('type');
        key_type = 'type';
    }

    contentBody.classList.add('_load');
    let response = await fetch('/4. Apteka42/php/goods.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `type_sort=${typeSort}&key=${key}&key_type=${key_type}&filter=${filter}`,
    });
    if (response.ok) {
        let result = await response.text();
        contentBody.innerHTML = result;
        setBGI();
    } else {
        alert('Ошибка!');
    }
    contentBody.classList.remove('_load');
}

function get(key) {
    let url = window.location.search;

    url = url.match(new RegExp(key + '=([^&=]+)'));

    return url ? url[1] : false;
}

function addProductToCart(product){
    const id = product.dataset.id,
          img = product.querySelector('.product__img img').getAttribute('src'),
          label = product.querySelector('.product__label').innerText,
          country = product.querySelector('.product__country').innerText,
          price = product.querySelector('.product__price .price').innerText;

    cart['p_' + id] = {
        'img': img,
        'label': label,
        'price': price,
        'count': 1,
        'selected': true,
        'country': country,
    };
    localStorage.setItem('cart', JSON.stringify(cart));
    if (totalContainer) {
        updateTotal();
    }
    document.querySelector('.header__cart span').innerText = lengthObject(cart);
    document.querySelector('.header__cart span').classList.remove('_hide');
}

function plusCountProduct(operator = '+', productID) {
    if (operator == '+'){
        return ++cart['p_' + productID]['count'];
    } else if (operator == '-') {
        if (cart['p_' + productID]['count'] == 1){
            delete cart['p_' + productID];
            return '0';
        } else {
            return --cart['p_' + productID]['count'];
        }
    } else {
        alert('Ошибка');
    }
}

// 4. Вывод товара в корзине
function renderProductsInCart(){
    const cartContainer = document.querySelector('.content__cart');
    
    if (cartContainer) {
        cartContainer.innerHTML = '';
        for (const id in cart) {
            const product = cart[id],
                  productHTML = renderProductInCart(id.replace('p_', ''), product);

            cartContainer.insertAdjacentHTML('afterbegin', productHTML);
        }
        setBGI();
    }
}

function renderProductInCart(id, data){
    const checked = (data['selected']) ? 'checked' : '',
          HTML = `
                <div data-id="${id}" class="content__product product product_cart">
                    <div class="product__aria-info">
                        <a href="#" class="product__img bgi"><img src="${data['img']}" alt="product"></a>
                        <a href="#" class="product__label">${data['label']}</a>
                    </div>
                    <div class="product__aria-price">
                        <div class="product__count product-count">
                            <button id="plus" class="product__count-plus plus plus_minus"></button>
                            <span class="count">${data['count']}</span>
                            <button id="minus" class="product__count-minus plus"></button>
                        </div>
                        <div class="product__price">
                            <span class="price">${data['price'] * data['count']}.00</span>
                        </div>
                    </div>
                    <div class="product__select">
                        <input type="checkbox" ${checked}>
                    </div>
                    <a href="#" class="product__delete">удалить</a>
                </div>
               `
    return HTML;
}

renderProductsInCart();

// 5. Обновление итогов
function updateTotal(){
    let totalPrice = 0,
        totalCount = 0,
        totalContainer = document.querySelector('.content__total');

    if (cart){
        for (const i in cart) {
            const el = cart[i];

            if (el['selected']){
                totalCount++;
                totalPrice += el['count'] * el['price'];
            }
        }
        totalCountBlock.innerText = totalCount;
        totalPriceBlock.innerText = totalPrice.toFixed(2);
        totalBlock.innerText = totalPrice.toFixed(2);
        countProductBlock.innerText = totalCount + ' ' + converWordEndings(totalCount, ['товар', 'товара', 'товаров']);
        if (totalCount == 0) {
            document.querySelector('.content__cart').classList.add('_zero');
            document.querySelector('.content__sidebar').classList.add('_hide');
            document.querySelector('.header__cart span').classList.add('_hide');
        } else {
            document.querySelector('.content__cart').classList.remove('_zero');
            document.querySelector('.content__sidebar').classList.remove('_hide');
            document.querySelector('.header__cart span').classList.remove('_hide');
            document.querySelector('.header__cart span').innerText = totalCount;
        }
    }
}

function converWordEndings(num, words) {
    const cases = [2, 0, 1, 1, 1, 2];

    return words[(num % 100 > 4 && num % 100 < 20) ? 2 : cases[Math.min(num % 10, 5)]];
}

if (totalContainer){
    updateTotal();
}

// 6. История добавленных товаров
let historyCart = localStorage.getItem('historyCart');

if (historyCart) {
    historyCart = JSON.parse(historyCart);
} else {
    historyCart = {};
    localStorage.setItem('historyCart', JSON.stringify(historyCart));
}

function lengthObject(obj){
    let count = 0;
    for (let key in obj){
        count++;
    }
    return count;
}

function deleteLastElObject(obj, length){
    let count = 0;

    for (let key in obj) {
        count++;
        if (count == length){
            delete obj[key];
        }
    }
}

renderProductsInHistory();

function renderProductsInHistory() {
    const historyContent = document.querySelector('.content_history');
    let toRemove = true;
    let historyBlock;

    if (historyContent) {
        historyBlock = historyContent.querySelector('.content__body');

        for (const key in historyCart) {
            toRemove = false;
            const el = historyCart[key];
            historyBlock.insertAdjacentHTML('afterbegin', renderProductInHistory(key.replace('p_', ''), el));
        }
        if (toRemove) historyContent.classList.add('_hide');
        updateProducts();
    }
}

function renderProductInHistory(id, data) {
    return `<div class="content__product product product_history" data-id="${id}">
                <a href="#" class="product__img bgi" style="background-image: url(${data['img']});"><img src="${data['img']}" alt="product"></a>
                <a href="#" class="product__label">${data['label']}</a>
                <div class="product__country">${data['country']}</div>
                <div class="product__price">
                    <span>Цена:</span>
                    <span class="price">${data['price']}</span>
                </div>
                <div class="product__btn">
                    <button class="product__buy">
                        Добавить в корзину
                    </button>
                </div>
            </div>`;
}

function updateHistoryProducts() {
    const historyContent = document.querySelector('.content_history');

    if (historyContent) {
        historyContent.classList.remove('_hide');
        historyContent.querySelector('.content__body').innerHTML = '';
        renderProductsInHistory();
    }
}

window.onstorage = function(event) {
    if (event.key == 'cart') {
        cart = JSON.parse(event.newValue);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateProducts();
        renderProductsInCart();
        if (document.querySelector('.content__total')) {
            updateTotal();
        }
    }
}