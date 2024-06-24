document.addEventListener('DOMContentLoaded', function () {
    const menuBtn = document.querySelector('.menu-btn');
const menuList = document.querySelector('.menu-listt');
const subBtn = document.querySelector('.sub');
const subList = document.querySelector('.sub-menu');
const subImg = subBtn.querySelector('img');

menuBtn.addEventListener('click', function () {
    menuList.classList.toggle('menu-listt--active');
});

subBtn.addEventListener('click', function () {
    subList.classList.toggle('sub-menu--active');
    if (subList.classList.contains('sub-menu--active')) {
        subImg.style.transform = 'rotate(180deg)';
    } else {
        subImg.style.transform = 'rotate(0deg)';
    }
});

});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll(".addToCartForm, .addToCart").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            var productId = this.querySelector('[name="product_id"]').value;
            var quantityInput = this.querySelector('.input-number');
            var quantity = quantityInput ? quantityInput.value : 1;

            var formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            fetch('/accessory_store/php/config/add_to_cart.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });

    document.querySelectorAll('.input-number-decrement, .input-number-increment').forEach(button => {
        button.addEventListener('click', function () {
            const inputField = this.parentElement.querySelector('.input-number');
            const isIncrement = this.classList.contains('input-number-increment');
            let value = parseInt(inputField.value) || 0;
            if (isIncrement && value < parseInt(inputField.max)) {
                inputField.value = value + 1;
            } else if (!isIncrement && value > parseInt(inputField.min)) {
                inputField.value = value - 1;
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".login-button").addEventListener("click", function (event) {
        event.preventDefault();

        var formData = new FormData(document.querySelector("#login-form form"));

        fetch('/accessory_store/php/config/login.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "error") {
                    document.getElementById("login-error-message").textContent = data.message;
                } else {
                    window.location.href = data.redirect;
                }
            });
    });

    document.querySelector(".register-button").addEventListener("click", function (event) {
        event.preventDefault();

        var formData = new FormData(document.querySelector("#signup-form form"));
        var email = formData.get('user_email');

        if (!email.includes('@')) {
            document.getElementById("signup-error-message").textContent = 'Адрес электронной почты должен содержать символ "@".';
            return;
        }

        fetch('/accessory_store/php/config/register.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "error") {
                    document.getElementById("signup-error-message").textContent = data.message;
                } else {
                    window.location.href = data.redirect;
                }
            });
    });
});

function toggleSignup() {
    document.getElementById("login-toggle").style.backgroundColor = "#fff";
    document.getElementById("login-toggle").style.color = "#000";
    document.getElementById("signup-toggle").style.backgroundColor = "#262626";
    document.getElementById("signup-toggle").style.color = "#fff";
    document.getElementById("login-form").style.display = "none";
    document.getElementById("signup-form").style.display = "block";
}

function toggleLogin() {
    document.getElementById("login-toggle").style.backgroundColor = "#262626";
    document.getElementById("login-toggle").style.color = "#fff";
    document.getElementById("signup-toggle").style.backgroundColor = "#fff";
    document.getElementById("signup-toggle").style.color = "#000";
    document.getElementById("signup-form").style.display = "none";
    document.getElementById("login-form").style.display = "block";
}

document.addEventListener("DOMContentLoaded", function () {
    function updateQuantity(cartItemId, newQuantity) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/accessory_store/php/config/update_quantity.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                updateTotalPrice();
                checkCartEmpty();
            }
        };
        xhr.send("cartItemId=" + cartItemId + "&quantity=" + newQuantity);
    }

    function checkCartEmpty() {
        var cartItems = document.querySelectorAll('.cart-item');
        if (cartItems.length === 0) {
            var cartContent = document.querySelector('.cart-content');
            cartContent.innerHTML = `<p>Корзина пуста</p>`;
        }
    }

    var cartContent = document.querySelector('.cart-content');
    cartContent.addEventListener('input', function (event) {
        if (event.target.classList.contains('item-quantity')) {
            var cartItemId = event.target.getAttribute('data-cart-item-id');
            var newQuantity = event.target.value;
            updateQuantity(cartItemId, newQuantity);
            updateTotalPrice();
        }
    });
    cartContent.addEventListener('click', function (event) {
        var input = event.target.parentNode.querySelector('.item-quantity');
        if (event.target.classList.contains('item-quantity-increment')) {
            var maxQuantity = parseInt(input.getAttribute('max'));
            var currentValue = parseInt(input.value);
            var newQuantity = currentValue + 1;
            if (newQuantity <= maxQuantity) {
                input.value = newQuantity;
                updateQuantity(input.getAttribute('data-cart-item-id'), newQuantity);
                updateTotalPrice();
            }
        }
        if (event.target.classList.contains('item-quantity-decrement')) {
            var currentValue = parseInt(input.value);
            var newQuantity = currentValue - 1;
            if (newQuantity >= 1) {
                input.value = newQuantity;
                updateQuantity(input.getAttribute('data-cart-item-id'), newQuantity);
                updateTotalPrice();
            }
        }
        if (event.target.classList.contains('remove-button')) {
            var cartItemId = event.target.getAttribute('data-cart-item-id');
            var cartItem = event.target.closest('.cart-item');
            cartContent.removeChild(cartItem);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/accessory_store/php/config/update_quantity.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    updateTotalPrice();
                    checkCartEmpty();
                }
            };
            xhr.send("cartItemId=" + cartItemId);
        }
    });
    function updateTotalPrice() {
        var totalPrice = 0;
        var cartItems = document.querySelectorAll('.cart-item');
        cartItems.forEach(function (cartItem) {
            var price = parseFloat(cartItem.querySelector('.price').textContent.replace(' ₽', ''));
            var quantityInput = cartItem.querySelector('.item-quantity');
            var maxQuantity = parseInt(quantityInput.getAttribute('max'));
            var quantity = parseInt(quantityInput.value);
            if (quantity > maxQuantity) {
                quantity = maxQuantity;
                quantityInput.value = maxQuantity;
            } else if (quantity < 1) {
                quantity = 1;
                quantityInput.value = 1;
            }
            var itemTotal = price * quantity;
            totalPrice += itemTotal;
            var itemTotalElement = cartItem.querySelector('.item-total');
            itemTotalElement.textContent = Math.round(price * quantity) + ' ₽';
        });
        var totalPriceElement = document.querySelector('.total-price');
        if (totalPrice > 0) {
            totalPriceElement.textContent = 'Cтоимость заказа: ' + Math.round(totalPrice) + ' ₽';
        } else {
            totalPriceElement.textContent = '';
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.nav-tabs li a');
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function (event) {
            event.preventDefault();
            var target = this.getAttribute('data-target');
            var tabPanes = document.querySelectorAll('.tab');
            tabs.forEach(function (tab) {
                tab.parentNode.classList.remove('active');
            });
            tabPanes.forEach(function (pane) {
                pane.classList.remove('active');
            });
            this.parentNode.classList.add('active');
            document.getElementById(target).classList.add('active');

            var urlWithoutHash = window.location.href.split('#')[0];
            window.history.replaceState({}, document.title, urlWithoutHash + '#' + target);
        });
    });

    var currentHash = window.location.hash;
    if (currentHash) {
        var target = currentHash.slice(1);
        var targetTab = document.querySelector('[data-target="' + target + '"]');
        if (targetTab) {
            targetTab.click();
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    function searchAndDisplay(searchInput, tableBody, searchColumns) {
        var searchTerm = searchInput.value.trim().toLowerCase();
        var rows = tableBody.getElementsByTagName('tr');

        for (var i = 0; i < rows.length; i++) {
            var rowData = '';
            for (var j = 0; j < searchColumns.length; j++) {
                var cellData = rows[i].getElementsByTagName('td')[searchColumns[j]].textContent.toLowerCase();
                rowData += cellData + ' ';
            }
            rows[i].style.display = rowData.includes(searchTerm) ? '' : 'none';
        }
    }

    var searchInputOrders = document.getElementById('searchInput');
    var ordersTableBody = document.getElementById('ordersTableBody');
    var searchInputEmployees = document.getElementById('employeeSearchInput');
    var employeesTableBody = document.getElementById('employeesTableBody');
    var searchInputClients = document.getElementById('clientSearchInput');
    var clientsTableBody = document.getElementById('clientsTableBody');
    var searchInputProduct = document.getElementById('productSearchInput');
    var productTableBody = document.getElementById('productTableBody');

    searchInputOrders.addEventListener('input', function () {
        searchAndDisplay(this, ordersTableBody, [0]);
    });

    searchInputEmployees.addEventListener('input', function () {
        searchAndDisplay(this, employeesTableBody, [0, 1]);
    });

    searchInputClients.addEventListener('input', function () {
        searchAndDisplay(this, clientsTableBody, [0, 1]);
    });
    searchInputProduct.addEventListener('input', function () {
        searchAndDisplay(this, productTableBody, [2, 3]);
    });

});

document.addEventListener('DOMContentLoaded', function () {
    var colorSelect = document.getElementById('color');
    var newColorInput = document.getElementById('new_color');

    colorSelect.addEventListener('change', function () {
        if (colorSelect.value === 'new_color') {
            newColorInput.style.display = 'block';
        } else {
            newColorInput.style.display = 'none';
        }
    });
});

function removeFilterParams() {
    var url = window.location.href;
    url = url.replace(/&?min_price=[^&]+/g, '');
    url = url.replace(/&?max_price=[^&]+/g, '');
    url = url.replace(/&?product_type%5B%5D=[^&]+/g, '');
    url = url.replace(/&?colors%5B%5D=[^&]+/g, '');
    history.pushState({}, null, url);
}

document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form');

    form.addEventListener('submit', function () {
        removeFilterParams();
    });

    removeFilterParams();
});

document.addEventListener('DOMContentLoaded', function () {
    var subcategoryElements = document.querySelectorAll('.subcategory');

    subcategoryElements.forEach(function (subcategory) {
        subcategory.addEventListener('click', function () {
            var productTypes = this.nextElementSibling;
            if (productTypes.style.display === 'none') {
                productTypes.style.display = 'block';
                this.querySelector('.subcategory-img').style.transform = 'rotate(180deg)';
            } else {
                productTypes.style.display = 'none';
                this.querySelector('.subcategory-img').style.transform = 'rotate(0deg)';
            }
        });
    });

    var checkboxes = document.querySelectorAll('.filter-checkbox input[type="checkbox"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var productTypes = this.closest('.product-types');
            var checkedCheckboxes = productTypes.querySelectorAll('input[type="checkbox"]:checked');
            if (checkedCheckboxes.length > 0) {
                productTypes.style.display = 'block';
                var subcategory = productTypes.previousElementSibling;
                subcategory.querySelector('.subcategory-img').style.transform = 'rotate(180deg)';
            } else {
                productTypes.style.display = 'none';
                var subcategory = productTypes.previousElementSibling;
                subcategory.querySelector('.subcategory-img').style.transform = 'rotate(0deg)';
            }
        });

        if (checkbox.checked) {
            var productTypes = checkbox.closest('.product-types');
            productTypes.style.display = 'block';
            var subcategory = productTypes.previousElementSibling;
            subcategory.querySelector('.subcategory-img').style.transform = 'rotate(180deg)';
        }
    });
});



