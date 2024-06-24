<?php
session_start();
include 'php/components/header.php';
?>
<div class="home">
    <div class="body_slides">
        <ul class="home-background">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>

    <div class="home-content">
        <h1>Создай свой образ с нами</h1>
        <h2>Игра стиля – где каждый <br> аксессуар – твоя уникальность!</h2>
        <button class="button-catalog"><a href="/accessory_store/php/catalog.php">КАТАЛОГ <img src="img/but.svg" alt=""></a></button>
    </div>
</div>
<div class="catalog-sections">
    <ul class="categories">
        <a href="/accessory_store/php/catalog.php?id=1">
            <li>
                <h1>Украшения</h1>
            </li>
        </a>
        <a href="/accessory_store/php/catalog.php?id=2">
            <li>
                <h1>Аксессуары</h1>
            </li>
        </a>
        <a href="/accessory_store/php/catalog.php?id=3">
            <li>
                <h1>Сумки</h1>
            </li>
        </a>
    </ul>
</div>
<div class="about">
    <h1 class="title-index">О нас</h1>
    <div class="about-content">
        <div class="about-left">
            <h2 class="about-text">Игра стиля - <span>это место, где стиль и индивидуальность встречаются.
                    <br>
                    <br>
                    Мы предоставляем широкий выбор аксессуаров, включая очки, сумки, бижутерию, головные уборы и
                    многое другое.
                    <br>
                    <br>
                    Наша цель - помочь вам выразить себя и создать уникальный облик.</span>
            </h2>
        </div>
        <div class="about-right">
        </div>
    </div>
</div>
<div class="why">
    <h1 class="title-index">Почему мы?</h1>
    <div class="why-content">
        <div class="why-row">
            <div class="why-item">
                <img src="/accessory_store/img/why1.png" alt="">
                <p>Мы предлагаем разнообразный ассортимент аксессуаров, позволяя каждому клиенту найти что-то
                    уникальное для своего стиля. </p>
            </div>
            <div class="why-item">
                <p>Наш магазин находится в самом центре города, что делает посещение удобным и легким.</p>
                <img src="/accessory_store/img/why3.png" alt="">
            </div>
        </div>
        <div class="why-row">
            <div class="why-item">
                <img src="/accessory_store/img/why2.png" alt="">
                <p>Мы помогаем клиентам создавать уникальные образы и подчеркивать свою индивидуальность с помощью
                    наших
                    аксессуаров.</p>
            </div>
            <div class="why-item">
                <p>Несмотря на высокое качество наших товаров, мы предлагаем их по доступным ценам.</p>
                <img src="/accessory_store/img/why4.png" alt="">
            </div>
        </div>
    </div>
</div>
<div class="catalog-link">
    <div class="catalog-link-content">
        <button class="button-catalog"><a href="/accessory_store/php/catalog.php">КАТАЛОГ <img src="img/but.svg" alt=""></a></button>
    </div>
</div>
<?php
include 'php/components/footer.php';
?>

<script src="js/script.js"></script>
</body>

</html>