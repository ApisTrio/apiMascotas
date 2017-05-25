<?php

use App\Lib\Token;

$jwtAuth = new \Slim\Middleware\JwtAuthentication([
	"path" => "/",
	"passthrough" => "/token",
    "algorithm" => ["HS256", "HS384"],
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]);

$app->post("/token", function ($request, $response, $arguments) {

    $token = Token::generar($usuario);
    $datos["token"] = $token;
    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
