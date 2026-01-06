<style>
html { height: 100%; }
body {
    background-image: url("../../public/default_pocket/favicon/404.jpg");
    background-size: cover; /* Масштабирует картинку сохраняя пропорции */
}
.page-404 {
    background: rgba(0, 0, 0, 0); /* Цвет фона и значение прозрачности */
    position: absolute; /* Абсолютное позиционирование */
    left: 0; right: 0; top: 0; bottom: 0; /* Отступы от краев браузера */
}
.text {                    
    top: 35vh;               
    left: 11vw;
    text-align: left;        
    color: rgba(0, 0, 0, 0.7);             
    text-shadow: 1px 1px 2px rgba(0, 0, 200, 0.6); 
    font-size: 4.7vh;        
    position: relative; /* Относительное позиционирование */
    z-index: 2; /* Порядок наложения элемента по слоям в глубину */
}
.return {
    color: white;
    text-decoration: none;
    text-shadow: 1px 1px 2px blue;
    font-size: 3.7vh;           
}
.return:hover {  
    color: rgba(0, 200, 0, 0.5);
    text-decoration: none;
}
</style>
<?php 
echo <<<_404
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="icon" href="favicon.ico/s.svg" sizes="any" type="image/svg+xml">
        <link rel="stylesheet" href="../css/404.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>404</title>
    </head>
    <body>
        <div class="page-404">
            <div class="text">
                <h3>4 0 4</h3><br>
                <p>мелкие капли дождя<br>
                кружат боятся упасть<br>
                путают мысли<br>
                и нет ответа..<br>
                <a class="return" href="../index.php">japan-in-ru</a></p>
            </div>
        </div>
    </body>
</html>
_404;
