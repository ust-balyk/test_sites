<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница не найдена</title>
    <!--link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/main.css'); ?>"-->
    <style>
        html {
            min-height: 100vh; /*height: 100%;*/
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #fffffd;
            background-image: url('/start_pocket/assets/errors/404.webp');
            /*background-image: url('/start_pocket/assets/errors/404.png');*/
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            box-sizing: border-box;
        }

        .container {
            position: absolute;
            top: 26vh;
            text-align: center;
        }

        .error-wrapper {
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.9); /* Добавлена подложка для читаемости текста */
            padding: 30px 42px 40px 42px;
            border-radius: 6px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .error-description {
            font-size: 20px;
            font-weight: 600;
            color: #e74c3c;
            margin: 0px 0px 30px 0px;
        }

        /* Стили формы поиска */
        .search-form {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-form input {
            padding: 12px;
            width: 60%;
            border: 2px solid #ddd;
            border-radius: 4px;
            outline: none;
            transition: 0.3s;
            box-sizing: border-box;
        }

        .search-form input:focus {
            border-color: #3498db;
        }

        .search-form button {
            padding: 12px 25px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .search-form button:hover {
            background-color: #2980b9;
        }

        /* Кнопка домой */
        .back-button {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
            border: 2px solid #3498db;
            padding: 10px 20px;
            border-radius: 4px;
            transition: 0.3s;
            display: inline-block;
        }

        .back-button:hover {
            background-color: #3498db;
            color: white;
        }

        /* Адаптивность для телефонов */
        @media (max-width: 480px) {
            .error-code { font-size: 100px; }
            .error-message { font-size: 22px; }
            .error-wrapper { padding: 20px; }
            .search-form { flex-direction: column; }
            .search-form input { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-wrapper">
            <p class="error-description">
                Извините, но запрашиваемая страница перемещена или удалена. Попробуйте воспользоваться поиском.
            </p>
            
            <!-- Поле поиска -->
            <form action="/search" method="get" class="search-form">
                <input type="text" name="q" placeholder="" aria-label="Поиск" autofocus required>
                <button type="submit">НАЙТИ</button>
            </form>
            <?php $back_url =
                hsc(\App\Controller\BaseController::safeRedirect($_SESSION['target_page'] ?? '/')
            ); ?>
            <a href="<?= $back_url ?>" class="back-button">ВЕРНУТЬСЯ</a>
        </div>
    </div>
</body>
</html>

