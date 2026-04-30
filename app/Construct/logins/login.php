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
                    <div class="text-primary" style="text-transform: lowercase">
                        <h5>правильно укажите свои данные, чтобы войти в личное пространство</h5>
                    </div>
                    <form action="<?= base_url('/login'); ?>" method="post">

                        <?= get_csrf_field(); ?>

                        <input type="hidden" name="target_page" 
                            value="<?= htmlspecialchars($_SESSION['target_page'] ?? '/'); ?>">

                        <div class="form-group email">
                            <label></label>
                            <input type="email" name="email" style="text-transform: lowercase"
                                placeholder="электронная почта" class="form-control email"
                                   value="<?= session()->get('form_data'); ?>"><!-- required /-->
                        </div>
                        
                        <div class="form-group password">
                            <label></label>
                            <input type="password" name="password" style="text-transform: lowercase"
                                placeholder="пароль" class="form-control password">
                        </div><br><br>
                        
                        <div class="form-group">
                            <input type="submit" name="submit" style="text-transform: lowercase" 
                                id="login_button" class="btn btn-primary" 
                                value="     а у т е н т и ф и к а ц и я     ">
                        </div><br>

                        <p class="login" style="text-transform: lowercase">
                            <a href="/register"><span class="play-icon">&#9654;</span></a>
                            <a href="/register">&thinsp;создать аккаунт&emsp;</a>
                            <?php $back_url = htmlspecialchars(
                                \App\Controller\BaseController::safeRedirect($_SESSION['target_page'] ?? '/')
                            ); ?>
                            <a href="<?= $back_url ?>"><span class="play-icon">&#9654;</span></a>
                            <a href="<?= $back_url ?>">&thinsp;отказаться</a>
                        </p>
                    </form>

                    <?= get_alerts(); ?>
                    <?= session()->remove('form_data'); ?>

                </div><!-- /login -->
            </div>
        </div> 
    </body>
</html>
