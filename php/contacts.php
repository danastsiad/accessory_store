<?php
session_start();
include './components/header.php';
?>
<div class="contact">
    <div class="container">
    <h1 class="title">Контакты</h1>
        <div class="contact-content">
            <div class="contact-content-left">
                <p>Мы всегда готовы ответить на ваши вопросы и помочь в выборе стильных аксессуаров. <br>
                    <br> <br>
                </p>
                <p>Адрес: <span class="light">г. Дзержинский, пл.Дмитрия Донского 1А</span> <br>
                    Часы работы: <span class="light">Ежедневно с 10:00-19:00</span> <br>
                    Моб/WhatsApp: <span class="light">+7(916)999-99-99</p><br> <br>
                <p><span class="light">Пожалуйста, обратите внимание,
                        что на данный момент у нас доступен
                        только самовывоз по указанному адресу.</span></p>
            </div>
            <div class="contact-content-maps">
                <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A3fb52bab089ab6b36ec1786ed33037b166e1dc21819d0a5b30aa3aaf6e5b5ea2&amp;width=100%&amp;height=520&amp;lang=ru_RU&amp;scroll=true"></script>
            </div>
        </div>
    </div>
</div>
<?php
include './components/footer.php';
?>
<script src="/accessory_store/js/script.js"></script>
</body>

</html>