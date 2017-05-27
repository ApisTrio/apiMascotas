<?php
namespace App\Lib;

use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Tuupola\Base62;

class Token
{
    private static $secret = "$&NFJU·deruw23";
    private static $encrypt = ['HS256'];
    private static $aud = null;
    
    public static function generar($data)
    {
        $time = time();
        $payload = array(
            'iat' => $time,
            'exp' => $time + (60*60),
            'aud' => self::Aud(),
            'jti' => (new Base62)->encode(random_bytes(16)),
            'data'  => $data,
        );
        return JWT::encode($payload, self::$secret);
    }
    
    public static function verificar($token)
    {
        if(empty($token))
        {
            throw new Exception("Invalid token supplied.");
        }
        
        $decode = JWT::decode( $token, self::$secret, self::$encrypt);
        
        if($decode->aud !== self::Aud())
        {
            throw new Exception("Invalid user logged in.");
        }

        $decode_arr = (array) $decode;

        return $decode_arr;
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