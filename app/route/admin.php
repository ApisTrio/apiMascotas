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
        
        $mo = new Admin;
        $args = $req->getParsedBody();
        $result = $mo->login($args);

        if($result->response){

          $token = Token::generar($result->result);
          $datos["token"] = $token;
          $datos["usuario"] = $result->result;
          
          return $res->withStatus(200)
              ->withHeader("Content-Type", "application/json")
              ->withJson($datos);
        }

        return $res->withStatus(404)
              ->withHeader("Content-Type", "application/json")
              ->withJson($result);
    });
    
    $this->get('lista', function ($req, $res, $args) {
        $um = new Admin();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getAll()
            )
        );
    });

    
    $this->get('datos/{id}', function ($req, $res, $args) {
        $um = new PruebaModel();
        
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