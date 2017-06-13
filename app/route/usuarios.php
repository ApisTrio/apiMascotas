<?php
use App\Model\Usuario;
use App\Model\Dueno;
use App\Model\Mascota;
use App\Model\Placa;
use App\Lib\Mail;


use App\Lib\Token;


$app->group('/usuarios/', function () {

	$this->post('login', function ($req, $res, $args) {
		
		$model = new Usuario;
		$r = $model->login($req->getParsedBody());

		if($r->response){

			$token_data = ['id' => $r->result['usuario']->idUsuario, 'is_admin' => false];
			$token = Token::generar($token_data);

			$data['token'] = $token;
			$data['usuario'] = $r->result['usuario'];
			$data['dueno'] = $r->result['dueno'];
			
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

		$r = $model->get($args['id']);
		
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
		$model_m = new Mascota;
		$model_p = new Placa;

		$data = $req->getParsedBody();

		$rd = $model_d->insertOrUpdate($data['dueno']);
		if($rd->response){
			
			$data['usuario']['duenos_idDueno'] = $rd->idInsertado;

			$ru = $model_u->insertOrUpdate($data['usuario']);
			if($ru->response){

				$rm = $model_m->Insert($data['mascota']);
				if ($rm->response) {
					
					$data['placa']['placas_idPlaca'] = $model_p->Datos($data['placa']['codigo'])->result->idPlaca;
					$data['placa']['mascotas_idMascota'] = $rm->idInsertado;


					$rmp =  $model_p->Asignar($data['placa']);
					if($rmp->response){

						$rmd =  $model_d->hasMascota($rd->idInsertado, $rm->idInsertado);
						if($rmd->response){

							$duenos = $data['duenos'];
							foreach ($duenos as $dueno) {

								$r	= $model_d->hasMascota($model_d->insertOrUpdate($dueno)->idInsertado, $rm->idInsertado);

							}

							$mail = new Mail;

							$datamail = [
								'nombre' => $data['dueno']['nombre'],
								'apellido' => $data['dueno']['apellido'],
								'email' => $data['usuario']['emailU'],
								'enlace' => 'localhost/appMascotas/confirmar/'.$ru->idInsertado.'/'.$model_u->get($ru->idInsertado)->result->token,
							];

							$body = $mail->render('confirmacion-cuenta.ml', $datamail);


							if($mail->send("Hola desde Mascotas", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $model_u->get($ru->idInsertado)->result->emailU ])){

								return $res->withStatus(200)
								 	->withHeader('Content-type', 'application/json')
								 	->withJson($ru);

							}

						}

					}


					

				}

				$model_u->delete($ru->idInsertado);

			}

			$model_d->delete($rd->idInsertado);

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
			
			$r = $model->delete($args['id']);

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

//MISC----------------------------------------------------------------------------

	$this->get('check/{field}/{value}', function ($req, $res, $args) {
			
		$model = new Usuario();

		$r = $model->check($args['field'],$args['value']);

		if($r->response){
			
			return $res->withStatus(200)
				 ->withHeader('Content-type', 'application/json')
				 ->withJson($r);
		}

		return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($r);

	});

	$this->get('confirmar/{id}/{token}', function ($req, $res, $args) {
			
		$model = new Usuario();

		$data = ['token' => $args['token'], 'idUsuario' => $args['id']];

		$ru = $model->activar($data);

		if($ru->response){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($ru);

		}

		return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($ru);

	});

	$this->post('nueva-contrasena', function ($req, $res, $args) {
			
		$model = new Usuario();

		$data = $req->getParsedBody();

		$r = $model->cambiarContrasena($data);

		if($r->response){

			$mail = new Mail;

			$body = $mail->render('cambiar-contrasena.ml', $r->result);


			if($mail->send("Hola desde Mascotas", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $data['emailU']])){

				return $res->withStatus(200)
				 	->withHeader('Content-type', 'application/json')
				 	->withJson($r);

			}

		}

		return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($r);

	});

	$this->get('testmail', function ($req, $res, $args) {

		$mail = new Mail;

		$body = $mail->render('alerta-activada.ml', ['nombre' => 'Daniel', 'apellido' => 'Torres', 'nombremascota' => 'Batman']);

		return $res->withStatus(200)->write($body);

		if($mail->send("Hola desde Mascotas", ["xarias13@gmail.com", "danieljtorres94@gmail.com"])){

			return $res->withStatus(200)->write($body);

		}

	});


			
	
});