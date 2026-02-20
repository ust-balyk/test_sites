<!DOCTYPE html>            
<html lang="ru" class="notranslate">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Оплата*Доставка*Возврат</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url(POCKET_STYLE.'/favicon/icon.png'); ?>" type="image/png">
    <link rel="preload" href="<?= base_url('/library/fontawesome/css/all.min.css'); ?>" as="style">
    <link rel="stylesheet" href="<?= base_url('/library/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/library/jquery-ui/jquery-ui.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url(POCKET_STYLE.'/css/main.css'); ?>">
</head>
<body>
    <style>
    .general_terms {
        h6, p, ul, li {
                padding: 0px 40px;
        }
        p, li {
                font-weight: 500;
        }
        ul {
            list-style: none; /* Убираем стандартные маркеры */
                    /*padding-left: 20px;*/
        }

        ul > li::before {
            content: "*"; /* Задаем символ маркера */
            color: orange; /*var(--blue-color); /* Цвет маркера */
            font-size: 20px;
            display: inline-block;
            /*width: 1em; /* Дистанция до текста */
            margin-left: -1em;
        }
        .block {
            border: 1px solid var(--light-grey-color);
            border-radius: 4px;
        }
    }
    </style>
    <div class="container general_terms" style="margin-top: 60px">
        <p>&nbsp;</p>
        <div class="block">
            <h6 style="color: #4295e4; margin-top: 20px">ДОСТАВКА</h6>
            <p>
            Мы сотрудничаем с логистической компанией «Служба Доставки Экспресс-Курьер».<br> 
            <strong>
            Стоимость и сроки доставки рассчитываются автоматически и соответствуют тарифам перевозчика.
            </strong><br>
                <ul>
                    <li>
                    <strong>Сроки отгрузки:</strong> 
                    Отправка заказа осуществляется в течение 3 рабочих дней после оформления.
                    </li>
                    <li>
                    <strong>Отслеживание:</strong>
                    После передачи заказа в службу доставки вы получите трек-номер 
                    для отслеживания посылки на указанный при регистрации e-mail или в мессенджер.
                    </li>
                    <li>
                    <strong>Важно:</strong> 
                    При заказе за пределы России или не входящие в географию присутствия «СДЭК» регионы,
                    пожалуйста, свяжитесь с нами в WhatsApp для согласования способа доставки.
                    </li>
                </ul>
            </p>
            <p style="margin-bottom: 40px">
        </div>

        <div class="block" style="margin-top: 10px">
            <h6 style="color: #4295e4; margin-top: 20px">ОПЛАТА</h6>
            <p><strong>
            Платежи по банковским картам проводятся в строгом соответствии с требованиями платежных систем.
            </strong><br>
            При оплате на сайте вы будете перенаправлены на защищённый платежный шлюз АО «Тинькофф Банк». 
            Оплата происходит через зашифрованный протокол SSL 
            <strong>
            без комиссии картой любого банка.
            </strong>
            <p style="color: orange; margin-bottom: 40px">
            Мы не получаем и не сохраняем данные вашей карты, равно как и не несём ответственности 
            за несоблюдение сроков доставки по вине перевозчика.
            </p>
        </div>

        <div class="block" style="margin-top: 10px">
            <h6 style="color: #4295e4; margin-top: 20px">ВОЗВРАТ</h6>
            <p style="margin-bottom: 40px"><strong>
            Если какие либо товары приобретённые в нашем магазине вызвали аллергию, 
            просрочены или не соответствуют описанию — возврат возможен в любое время в пределах срока годности 
            при условии что товар не использовался, сохранён товарный вид и упаковка.  
            </strong></p>
        </div>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>

    </div>
</body>
</html>

