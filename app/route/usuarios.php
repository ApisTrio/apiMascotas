<?php
use App\Model\Usuario;
use App\Model\Dueno;


use App\Lib\Token;


$app->group('/usuarios/', function () {


	$this->post('login', function ($req, $res, $args) {
		
		$model = new Usuario;
		$r = $model->login($req->getParsedBody());

		if($r->response){

			$token_data = ['id' => $r->result->idUsuario, 'is_admin' => false];
			$token = Token::generar($token_data);

			$data["token"] = $token;
			$data["usuario"] = $r->result;
			
			return $res->withStatus(200)
					->withHeader("Content-Type", "application/json")
					->withJson($data);
		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($result);

	});
	
	$this->get('lista', function ($req, $res, $args) {

		$decode = Token::verificar(explode(' ', $req->getHeader('Authorization')[0])[1]);
		$data = (array) $decode['data'];
		
		if($data['is_admin']){

			$model = new Usuario;
			
			return $res->withStatus(200)
					->withHeader('Content-type', 'application/json')
					->withJson( $model->getAll() );
						
		}

		return $res->withStatus(401);


	})->add(new \Slim\Middleware\JwtAuthentication([
			"path" => "/",
			"secret" => '$&NFJU·deruw23',
			"passthrough" => "/token",
			"algorithm" => ["HS256", "HS384"],
			"error" => function ($request, $response, $arguments) {
				$data["status"] = "error";
				$data["message"] = $arguments["message"];
				return $response->withStatus(401)
						->withHeader("Content-Type", "application/json")
						->withJson($data);
			}
		]));
	
	$this->get('datos/{id}', function ($req, $res, $args) {

		$decode = Token::verificar(explode(' ', $req->getHeader('Authorization')[0])[1]);
		$data = (array) $decode['data'];
		
		if(!$data['is_admin'] || $data['id'] == $args['id']){

			$model = new Usuario;

			return $res->withStatus(200)
				 ->withHeader('Content-type', 'application/json')
				 ->withJson($model->get($args['id']));
				 
		}

		return $res->withStatus(401);

	})->add(new \Slim\Middleware\JwtAuthentication([
			"path" => "/",
			"secret" => '$&NFJU·deruw23',
			"passthrough" => "/token",
			"algorithm" => ["HS256", "HS384"],
			"error" => function ($request, $response, $arguments) {
				$data["status"] = "error";
				$data["message"] = $arguments["message"];
				return $response->withStatus(401)
						->withHeader("Content-Type", "application/json")
						->withJson($data);
			}
		]));
	
	$this->post('registro', function ($req, $res) {
			
		$model_d = new Dueno;
		$model_u = new Usuario;

		$data = $req->getParsedBody();

		$model_d->insertOrUpdate($data);
		$data['idDueno'] = $model_d->idInsertado;
		
		return $res->withStatus(200)
			 ->withHeader('Content-type', 'application/json')
			 ->getBody()
			 ->withJson($model_u->insertOrUpdate($data));

	});
	
	$this->get('borrar/{id}', function ($req, $res, $args) {

		$decode = Token::verificar(explode(' ', $req->getHeader('Authorization')[0])[1]);
		$data = (array) $decode['data'];
		
		if($data['is_admin']){
			
			$model = new Usuario();
			
			return $res->withStatus(200)
				 ->withHeader('Content-type', 'application/json')
				 ->getBody()
				 ->withJson($model->delete($args['id']));
		}

		return $res->withStatus(401);

	})->add(new \Slim\Middleware\JwtAuthentication([
			"path" => "/",
			"secret" => '$&NFJU·deruw23',
			"passthrough" => "/token",
			"algorithm" => ["HS256", "HS384"],
			"error" => function ($request, $response, $arguments) {
				$data["status"] = "error";
				$data["message"] = $arguments["message"];
				return $response->withStatus(401)
						->withHeader("Content-Type", "application/json")
						->withJson($data);
			}
		]));
	
});