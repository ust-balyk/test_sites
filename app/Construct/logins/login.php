<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Вход</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="<?= base_url(POCKET_STYLE .'/favicon/icon.png'); ?>" type="image/png">
        <link rel="stylesheet" href="<?= base_url('/library/bootstrap/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url(POCKET_STYLE .'/css/main.css'); ?>">
        <link rel="stylesheet" href="<?= base_url(POCKET_STYLE .'/css/media.css'); ?>">
    </head>
    <body id="login">
        <div class="container">
            <div class="row">
                <div class="col-md-12 login">
                    <div class="text-primary">
                        <h5>правильно укажите свои данные, чтобы войти в личное пространство</h5>
                    </div>
                    <form action="<?= base_url('/login'); ?>" method="post">

                        <?= get_csrf_field(); ?>

                        <div class="form-group">
                            <label></label>
                            <input type="email" name="email" placeholder="электронная почта"
                                class="form-control"
                                   value="<?= session()->get('form_data'); ?>"><!-- required /-->
                        </div>
                        
                        <div class="form-group">
                            <label></label>
                            <input type="password" name="password" placeholder="пароль" class="form-control">
                        </div><br><br>
                        
                        <div class="form-group">
                            <input type="submit" name="submit" id="login_button"
                                   class="btn btn-primary rounded-1"
                                   value="     а у т е н т и ф и к а ц и я     ">
                        </div><br>

                        <p class="login">
                            <a href="/register">&#9654; создать аккаунт </a>
                            <a href="#" id="login_page"> &#9654; отказаться</a>
                        </p>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var go_back = document.getElementById("login_page");
                                go_back.addEventListener("click", function() {
                                    window.location.href = localStorage.getItem('location');
                                });
                            });
                        </script> 
                    </form>

                    <?= get_alerts(); ?>
                    <?= session()->remove('form_data'); ?>

                </div><!-- /login -->
            </div>
        </div> 
    </body>
</html>
