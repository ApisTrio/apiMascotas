<?php

use App\Lib\Token;

$app->post("/api/token", function ($request, $response, $arguments) {
    $requested_scopes = $request->getParsedBody();

    $token = Token::generar($usuario);
    $data["token"] = $token;
    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
