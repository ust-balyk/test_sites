<!DOCTYPE html>
<html lang="form-group">
    <head>
        <meta charset="UTF-8">
        <title>Регистрация</title>
        <link rel="icon" href="<?= base_url('/library/favicon/icon.png'); ?>" type="image/png">
        <link rel="stylesheet" href="<?= base_url('/library/bootstrap/css/bootstrap.min.css'); ?>">
    </head><?php //dump(session()->get('csrf_token')); ?>
    <body style="background-color: #eaeaea">
        <div class="container" style="height: 90vh"><br>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="text-primary"><i>Заполните все поля, чтобы создать личное пространство</i></h5>
                    <form action="<?= base_url('/register'); ?>" method="post">

                      <div class="form-group">
                        <label></label>
                        <input type="text" name="name"
                               placeholder="Имя" class="form-control border-success border-4
                               <?= get_validation_class('name'); ?>"
                               value="<?= old('name'); ?>"> <!-- required /-->
                               <?//= get_errors('name'); ?>
                      </div>

                      <div class="form-group">
                        <label></label>
                        <input type="email" name="email" placeholder="Электронная почта"
                               class="form-control border-success border-4
                               <?= get_validation_class('email'); ?>"
                               value="<?= old('email'); ?>"> <!-- required /-->
                               <?//= get_errors('email'); ?>
                      </div>

                      <div class="form-group">
                        <label></label>
                        <input type="password" name="password" placeholder="Пароль не менее 6 символов"
                               class="form-control border-success border-4
                               <?= get_validation_class('password'); ?>"> <!-- required /-->
                               <?//= get_errors('password'); ?>
                      </div>

                      <div class="form-group">
                        <label></label>
                        <input type="password" name="confirm_password"
                               placeholder="Повторите пароль" class="form-control border-success border-4
                               <?= get_validation_class('password'); ?>"> <!-- required /-->
                               <?//= get_errors('confirm_password'); ?>
                      </div><br><br>

                      <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-primary"
                               value="     р е г и с т р а ц и я     ">
                      </div><br>

                      <?= get_csrf_field(); ?>

                      <p>Уже зарегистрированы?
                        <a style="font-weight:bold; color:#198754; text-decoration:none"
                            href="<?= base_url('/login'); ?>">Войти в систему,</a>
                        <a style="font-weight:bold; color:#198754; text-decoration:none"
                            href="#" onclick="window.history.go(-2); return false;"> или Отказаться.</a>
                            <!--href="<?= base_url('/'); ?>"> или Отменить.</a-->
                      </p>

                    </form>

                    <?= get_alerts(); ?>
                    <?= session()->remove('form_data'); session()->remove('form_errors'); ?>

                </div> <!-- class="col-md-12" -->
            </div> <!-- class="row" -->
        </div> <!-- class="container" -->
    </body>
</html>
