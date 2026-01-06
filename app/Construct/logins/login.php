<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Вход</title>
        <link rel="icon" href="<?= base_url('/default_pocket/favicon/icon.png'); ?>" type="image/png">
        <link rel="stylesheet" href="<?= base_url('/library/bootstrap/css/bootstrap.min.css'); ?>">
    </head><?php //dump(session()->get('csrf_token')); ?>
    <body style="background-color: #eaeaea">        
        <div class="container" style="height: 97vh"><br><br>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="text-primary">
                        <i>Правильно укажите свои данные, чтобы войти в личное пространство</i></h5>
                    <form action="<?= base_url('/login'); ?>" method="post">
                        
                        <div class="form-group">
                            <label></label>
                            <input type="email" name="email" placeholder="Электронная почта"
                                   class="form-control border-success border-4"
                                   value="<?= session()->get('form_data'); ?>"><!-- required /-->
                        </div>
                        
                        <div class="form-group">
                            <label></label>
                            <input type="password" name="password" placeholder="Пароль"
                                   class="form-control border-success border-4"> <!-- required /-->
                        </div><br><br>
                        
                        <div class="form-group">
                            <input type="submit" name="submit"
                                   class="btn btn-primary"
                                   value="     а у т е н т и ф и к а ц и я     ">
                        </div><br>

                        <?= get_csrf_field(); ?>

                        <p>Нет аккаунта?
                            <a style="font-weight:bold; color:#198754; text-decoration:none"
                                href="<?= base_url('/register'); ?>">Создать аккаунт,</a>
                            <!--a href="#" onclick="window.history.back(); return false;"> или Отказаться.</a-->
                            <a style="font-weight:bold; color:#198754; text-decoration:none"
                                href="#" onclick="window.history.back(); return false;"> или Отказаться.</a>
                                <!--href="<?= base_url('/'); ?>"> или Отказаться.</a-->
                        </p> 
                    </form>

                    <?= get_alerts(); ?>
                    <?= session()->remove('form_data'); ?>

                </div>
            </div>
        </div> 
    </body>
</html>
