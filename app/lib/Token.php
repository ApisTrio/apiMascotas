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
            'id'  => $datos->idAdmin,
            'rol' => 'admin'
        );
        return JWT::encode($payload, self::$secret, self::$encrypt);
    }
    
    public static function Check($token)
    {
        if(empty($token))
        {
            throw new Exception("Invalid token supplied.");
        }
        
        $decode = JWT::decode( $token, self::$secret_key, self::$encrypt );
        
        if($decode->aud !== self::Aud())
        {
            throw new Exception("Invalid user logged in.");
        }
    }
}