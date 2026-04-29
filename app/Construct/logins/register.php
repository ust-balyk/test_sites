<?php //session()->set('return_to', $_SERVER['HTTP_REFERER'] ?? '/'); ?>
<?php //session()->set('return_to', $_SERVER['HTTP_REFERER'] ?? '/');
$back = $_SERVER['HTTP_REFERER'] ?? '/';
// Проверяем: если мы пришли НЕ с логина и НЕ с регистрации, значит это "та самая" страница
if (strpos($back, '/login') === false && strpos($back, '/register') === false) {
session()->set('back_url', $back);
}; ?>
  <!DOCTYPE html>
  <html lang="ru">
    <head>
      <meta charset="UTF-8">
      <title>Регистрация</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" href="<?= base_url('/library/favicon/icon.png'); ?>" type="image/png">
      <link rel="stylesheet" href="<?= base_url('/library/bootstrap/css/bootstrap.min.css'); ?>">
      <link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/main.css'); ?>">
      <link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/media.css'); ?>">
    </head>
    <body id="register">
      <div class="container">
        <div class="row">
          <div class="col-md-12 register">

            <div class="text-primary" style="text-transform: lowercase">
              <h5>заполните все поля, чтобы создать личное пространство</h5>
            </div>

            <form action="<?= base_url('/register'); ?>" method="post">

              <?= get_csrf_field(); ?>

              <div class="form-group name">
                <label></label>
                <input type="text" name="name" style="text-transform: lowercase" placeholder="имя" 
                        class="form-control name <?= get_validation_class('name'); ?>"
                  value="<?= old('name'); ?>"> <!-- required /-->
                  <?//= get_errors('name'); ?>
              </div>

              <div class="form-group register_email">
                <label></label>
                <input type="email" name="email" style="text-transform: lowercase" placeholder="электронная почта"
                        class="form-control register_email <?= get_validation_class('email'); ?>"
                  value="<?= old('email'); ?>"> <!-- required /-->
                  <?//= get_errors('email'); ?>
              </div>

              <div class="form-group register_password">
                <label></label>
                <input type="password" name="password" style="text-transform: lowercase"
                      placeholder="пароль не менее 6 символов" class="form-control register_password
                  <?= get_validation_class('password'); ?>"> <!-- required /-->
                  <?//= get_errors('password'); ?>
              </div>

              <div class="form-group confirm_password">
                <label></label>
                <input type="password" name="confirm_password" style="text-transform: lowercase"
                      placeholder="повторите пароль" class="form-control confirm_password
                  <?= get_validation_class('password'); ?>"> <!-- required /-->
                  <?//= get_errors('confirm_password'); ?>
                </div><br><br>

              <div class="form-group">
                <input type="submit" name="submit" id="register_button" style="text-transform: lowercase"
                     class="btn btn-primary" value="     р е г и с т р а ц и я     ">
              </div><br>

              <?= get_auth_token(); ?>

              <p class="register">
                <a style="text-transform: lowercase" href="/login">&#9654; войти в систему </a>
                <a style="text-transform: lowercase" href="#" id="register_page">&#9654; отказаться</a>
              </p>
              <!--script>
                document.addEventListener("DOMContentLoaded", function() {
                  var go_back = document.getElementById("register_page");
                  go_back.addEventListener("click", function() {
                    if (localStorage.getItem('location')) {
                      window.location.href = localStorage.getItem('location');
                    } else {
                      window.location.href = '/';
                    }
                  });
                });
              </script-->

            </form>

            <?= get_alerts(); ?>
            <?= session()->remove('form_data'); session()->remove('form_errors'); ?>

          </div> <!-- class="col-md-12" -->
        </div> <!-- class="row" -->
      </div> <!-- class="container" -->
    </body>
</html>
