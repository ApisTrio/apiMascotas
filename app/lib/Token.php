<?php
namespace App\Lib;

use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Tuupola\Base62;

class Token
{
    private static $secret = "$&NFJU·deruw23";
    private static $encrypt = 'HS256';
    
    public static function generar($datos)
    {
        $tiempo = time();
        
        $payload = array(
            'iat' => $tiempo,
            'exp' => $tiempo + (60*60),
            'jti' => (new Base62)->encode(random_bytes(16)),
        );

        return JWT::encode($payload, self::$secret, $encrypt);
    }
}