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

            <div class="text-primary">
              <h5>Заполните все поля, чтобы создать личное пространство</h5>
            </div>

            <form action="<?= base_url('/register'); ?>" method="post">

              <?= get_csrf_field(); ?>

              <div class="form-group">
                <label></label>
                <input type="text" name="name" placeholder="Имя" 
                        class="form-control border-0 rounded-1
                  <?= get_validation_class('name'); ?>"
                  value="<?= old('name'); ?>"> <!-- required /-->
                  <?//= get_errors('name'); ?>
              </div>

              <div class="form-group">
                <label></label>
                <input type="email" name="email" placeholder="Электронная почта"
                        class="form-control border-0 rounded-1
                  <?= get_validation_class('email'); ?>"
                  value="<?= old('email'); ?>"> <!-- required /-->
                  <?//= get_errors('email'); ?>
              </div>

              <div class="form-group">
                <label></label>
                <input type="password" name="password" placeholder="Пароль не менее 6 символов"
                        class="form-control border-0 rounded-1
                  <?= get_validation_class('password'); ?>"> <!-- required /-->
                  <?//= get_errors('password'); ?>
              </div>

              <div class="form-group">
                <label></label>
                <input type="password" name="confirm_password" placeholder="Повторите пароль" 
                        class="form-control border-0 rounded-1
                  <?= get_validation_class('password'); ?>"> <!-- required /-->
                  <?//= get_errors('confirm_password'); ?>
                </div><br><br>

              <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary rounded-1"
                        value="     р е г и с т р а ц и я     ">
              </div><br>

              <?= get_auth_token(); ?>

              <p>
                <a style="font-weight:bold; color:#4d4d4d; text-decoration:none"
                    href="<?= base_url('/login'); ?>">Войти в систему,</a>
                <a style="font-weight:bold; color:#4d4d4d; text-decoration:none"
                    href="#" id="go_back" onclick="window.location.href"> или Отказаться.</a>
              </p>
              <script>
                document.addEventListener("DOMContentLoaded", function() {
                  var go_back = document.getElementById("go_back");
                  go_back.addEventListener("click", function() {
                    window.location.href = localStorage.getItem('previousPage');
                  });
                });
              </script>

            </form>

            <?= get_alerts(); ?>
            <?= session()->remove('form_data'); session()->remove('form_errors'); ?>

          </div> <!-- class="col-md-12" -->
        </div> <!-- class="row" -->
      </div> <!-- class="container" -->
    </body>
</html>
