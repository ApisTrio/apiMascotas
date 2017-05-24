<?php
namespace App\Lib;

use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Tuupola\Base62;

class Token
{
    private static $secret = getenv("JWT_SECRET");
    private static $encrypt = 'HS256';
    private static $aud = null;
    
    public static function generar($datos)
    {
        $tiempo = time();
        
        $payload = array(
            'iat' => $tiempo,
            'exp' => $tiempo + (60*60),
            'jti' => (new Base62)->encode(random_bytes(16)),
            'data' => $datos
        );

        return ['token' => JWT::encode($payload, self::$secret, $encrypt);
    }
    
    public static function verificar($token)
    {
        if(empty($token))
        {
            throw new Exception("Invalid token supplied.");
        }
        
        $decode = JWT::decode(
            $token,
            self::$secret,
            self::$encrypt
        );
        
        if($decode->aud !== self::Aud())
        {
            throw new Exception("Invalid user logged in.");
        }
    }
    
    public static function obtenerDatos($token)
    {
        return JWT::decode(
            $token,
            self::$secret,
            self::$encrypt
        )->data;
    }
    
    private static function Aud()
    {
        $aud = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        
        return sha1($aud);
    }
}