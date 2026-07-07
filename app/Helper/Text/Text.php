<?php
namespace App\Helper\Text;

use HTMLPurifier;
use HTMLPurifier_Config;

class Text {

    private static $purifier;

    public static function clean(string $html): string {
        if (self::$purifier === null) {

            $config = HTMLPurifier_Config::createDefault();
            $config->set('Core.Encoding', 'UTF-8');
            $config->set('Cache.SerializerPath', __DIR__ . '/cache_text'); 
            self::$purifier = new \HTMLPurifier($config);
        }
        return self::$purifier->purify($html);
    
    }

}

