<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
/*
$app->add(function ($request, $response, $next) {
	$response->getBody()->write($request->getUri()->getPath());
	//$response = $next($request, $response);

	return $response;
});
*/

$app->add(new \Slim\Middleware\JwtAuthentication([
	"path" => "/",
	"passthrough" => "/api/token",
    "secret" => "Sdw1s9x8@",
    "algorithm" => ["HS256", "HS384"],
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));
