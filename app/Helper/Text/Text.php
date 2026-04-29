<?php
namespace App\Helper\Text;
use HTMLPurifier_Config;
//use HTMLPurifier;
//Если вы не хотите добавлять use, поставьте \ перед названием класса. 
//Это скажет PHP: «Ищи этот класс в глобальном пространстве, а не в текущей папке».
//использование use (Лучший выбор) 🏆
//Это стандарт современного программирования (стандарт PSR).

class Text {

    private static $purifier;

    public static function clean(string $html): string {
        if (self::$purifier === null) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Core.Encoding', 'UTF-8');
            
            // Укажите путь к папке, куда PHP разрешена запись (напр. /tmp или /cache)
            // Это ускорит работу в десятки раз
            $config->set('Cache.SerializerPath', __DIR__ . '/cache_text'); 

            self::$purifier = new \HTMLPurifier($config);
        }
        return self::$purifier->purify($html);
    
    }

}

