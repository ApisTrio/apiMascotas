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
					->withJson($r);

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

		return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($data);


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

		$model = new Usuario;

		$r = $model->get($args['id'])
		
		if($r->response){

			return $res->withStatus(200)
				 ->withHeader('Content-type', 'application/json')
				 ->withJson($r);
				 
		}


		return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($r);

	});
	
	$this->post('registro', function ($req, $res) {
			
		$model_d = new Dueno;
		$model_u = new Usuario;

		$data = $req->getParsedBody();

		$rd = $model_d->insertOrUpdate($data);

		if($rd->response){
			
			$data['idDueno'] = $rd->idInsertado;

			$ru = $model_u->insertOrUpdate($data);

			if($ru->response){

				return $res->withStatus(200)
					 ->withHeader('Content-type', 'application/json')
					 ->withJson($ru);

			}

			$model_d->delete($model_d->idInsertado);

			return $res->withStatus(401)
				->withHeader("Content-Type", "application/json")
				->withJson($ru);

		}

		return $res->withStatus(401)
				->withHeader("Content-Type", "application/json")
				->withJson($rd);

	});
	
	$this->get('borrar/{id}', function ($req, $res, $args) {

		$decode = Token::verificar(explode(' ', $req->getHeader('Authorization')[0])[1]);
		$data = (array) $decode['data'];
		
		if($data['is_admin'] || $data['id' == $args['id']]){
			
			$model = new Usuario();
			
			$r = $model->delete($args['id'])

			if($r->response){

				return $res->withStatus(200)
					 	->withHeader('Content-type', 'application/json')
					 	->getBody()
					 	->withJson($r);

			}

			return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($data);
				
		}

			return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($data);

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


	$this->get('check/{usuario}', function ($req, $res, $args) {
			
		$model = new Usuario();

		$r = $model->check($args['usuario']);

		if($r->response){
			
			return $res->withStatus(200)
				 ->withHeader('Content-type', 'application/json')
				 ->withJson($r);
		}

		return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($r);

	});
	
});