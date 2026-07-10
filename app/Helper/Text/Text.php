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
            $config->set('HTML.Allowed', 'strong,em,u,s,h3,h4,h5,ul,li,a[href|target|rel],span,code,blockquote');
            $config->set('Attr.AllowedFrameTargets', ['_blank','_self','_parent','_top']);
            $config->set('Attr.AllowedRel', 'noopener,noreferrer,follow');
    
            self::$purifier = new \HTMLPurifier($config);
        }
    
        return self::$purifier->purify($html);
    }
    
}


