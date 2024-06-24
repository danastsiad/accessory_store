<?php
session_start();
include './components/header.php';
include './config/db.php';

if (isset($_SESSION['id_user'])) {
    header("Location: lk.php");
    exit();
} else {
?>
    <div class="login">
        <div class="container">
            <h1 class="title">Добро пожаловать</h1>
            <div class="form-modal">
                <div class="form-toggle">
                    <button id="login-toggle" onclick="toggleLogin()">
                        <h3>Вход</h3>
                    </button>
                    <button id="signup-toggle" onclick="toggleSignup()">
                        <h3>Регистрация</h3>
                    </button>
                </div>
                <div id="login-form">
                    <div id="login-error-message" style="color: red;"></div>
                    <form class="signup-form" action="./config/login.php" method="post">
                        <input type="text" class="input" name="user_telephone" id="login-user_telephone" autocomplete="off" placeholder="Телефон">
                        <input type="password" class="input" name="user_pass" id="user_pass" autocomplete="off" placeholder="Пароль">
                        <button class="login-button">ВОЙТИ</button>
                    </form>
                </div>
                <div id="signup-form">
                    <form class="signup-form" action="./config/register.php" method="post">
                        <div id="signup-error-message" style="color: red;"></div>
                        <div class="horizontal-inputs">
                            <input type="text" class="input" name="user_name" id="user_name" autocomplete="off" placeholder="Имя">
                            <input type="text" class="input" name="user_surname" id="user_surname" autocomplete="off" placeholder="Фамилия">
                        </div>
                        <div class="horizontal-inputs">
                            <input type="text" class="input" name="user_telephone" id="signup-user_telephone" autocomplete="off" placeholder="Телефон">
                            <input type="email" class="input" name="user_email" id="signup-user_email" autocomplete="off" placeholder="E-mail">
                        </div>
                        <input type="password" class="input" name="user_pass" id="user_pass" autocomplete="off" placeholder="Пароль">
                        <button id="register-button" class="register-button" name="register">ЗАРЕГИСТРИРОВАТЬСЯ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    include './components/footer.php';
    ?>
    <script src="https://unpkg.com/imask"></script>
    <script src="/accessory_store/js/script.js"></script>
    <script>
         ['login', 'signup'].forEach(form => {
        const input = document.getElementById(`${form}-user_telephone`);
        const maskOptions = {
            mask: '+7(000)000-00-00',
            lazy: true
        };
        const maskedInput = new IMask(input, maskOptions);

        input.addEventListener('focus', () => {
            maskedInput.updateOptions({ lazy: false });
        });

        input.addEventListener('blur', () => {
            if (input.value === '') {
                maskedInput.updateOptions({ lazy: true });
            }
        });
    });
    </script>
    </body>

    </html>
<?php
}
?>