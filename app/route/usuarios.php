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
		      
		                $$model_p->Desbloquear($data['placa']['placas_idPlaca']);

						$rmd =  $model_d->hasMascota($rd->idInsertado, $rm->idInsertado);
						if($rmd->response){

							$duenos = $data['duenos'];
							foreach ($duenos as $dueno) {

								$r	= $model_d->hasMascota($model_d->insertOrUpdate($dueno)->idInsertado, $rm->idInsertado);

							}

							return $res->withStatus(200)
							 	->withHeader('Content-type', 'application/json')
							 	->withJson($ru);

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
	
	$this->get('borrar/{token}', function ($req, $res, $args) {

		$decode = Token::verificar($args['token']);
		$data = (array) $decode['data'];
		
		$horas = intval( ($data['exp'] - strtotime('now')) /60/60 );

		if($horas > 0){
			
			$model = new Usuario();
			
			$r = $model->softDelete($data['id']);

			if($r->response){

				return $res->withStatus(200)
					 	->withHeader('Content-type', 'application/json')
					 	->withJson($r);

			}

			return $res->withStatus(400)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
				
		}

		return $res->withStatus(400)
				->withHeader("Content-Type", "application/json")
				->withJson(['response'=> false, 'result'=> 'Han pasado mas de 24 horas']);

	});

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

	$this->post('contactar', function ($req, $res, $args) {

		$data = $req->getParsedBody();

		$mail = new Mail;

		if($data['direccion']){

			$body = $mail->render('placa-escaneada-v1.ml', $data);

		}else{

			$body = $mail->render('placa-escaneada-v2.ml', $data);

		}

		if($mail->send("Dinbeat - Placa escaneada", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $data['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($data);

		}


		return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($data);

	});

	$this->post('cambiar-contrasena', function ($req, $res, $args) {
			
		$model = new Usuario();

		$datos = $req->getParsedBody();

		$decode = Token::verificar($datos['token']);
		$data = (array) $decode['data'];
		$data['pass'] = $datos['pass'];

		$r = $model->cambiarContrasena($data);

		if($r->response){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($r);

	});

	$this->post('recordar-usuario', function ($req, $res, $args) {
			
		$model = new Usuario();

		$data = $req->getParsedBody();

		$r = $model->recordarUsuario($data);

		if($r->response){

			$mail = new Mail;

			$body = $mail->render('recordar-usuario.ml', $r->result);


			if($mail->send("Dinbeat - Has olvidado tu usuario?", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $data['emailU']])){

				return $res->withStatus(200)
				 	->withHeader('Content-type', 'application/json')
				 	->withJson($r);

			}

		}

		return $res->withStatus(401)
					->withHeader("Content-Type", "application/json")
					->withJson($r);

	});


//-----------------------------------------------------------------------------



			
	
});