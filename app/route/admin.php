<?php
use App\Model\Admin;
use App\Lib\Token;

$jwtAuth = new \Slim\Middleware\JwtAuthentication([
  "path" => "/",
  "passthrough" => "/token",
    "algorithm" => ["HS256", "HS384"],
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]);

$app->group('/admin/', function () {
    $this->post('login', function ($req, $res, $args) {
     $um = new Admin();
        $DD = $req->getParsedBody();
        if($um->login($DD)){
          $token = Token::generar($usuario);
          $datos["token"] = $token;
          return $response->withStatus(200)
              ->withHeader("Content-Type", "application/json")
              ->withJson($data);
        }
    });
    
    $this->get('lista', function ($req, $res, $args) {
        $um = new Admin();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });

    
    $this->get('datos/{id}', function ($req, $res, $args) {
        $um = new Admin();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Get($args['id'])
            )
        );
    });
    
    $this->post('registro', function ($req, $res) {
        $um = new PruebaModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->InsertOrUpdate(
                    $req->getParsedBody()
                )
            )
        );
    });
    
    $this->get('borrar/{id}', function ($req, $res, $args) {
        $um = new PruebaModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Delete($args['id'])
            )
        );
    });
    
});