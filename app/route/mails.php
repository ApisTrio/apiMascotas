<?php
use App\Model\Usuario;
use App\Model\Dueno;
use App\Model\Mascota;
use App\Model\Vacuna;

use App\Lib\Mail;


$app->group('/mail/', function () {


	$this->post('confirmacion-cuenta', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (new Usuario)->confirmarCuentaDatos( $data['id'] );

		$datamail = (array) $r->result;
		$datamail['enlace'] = 'http://localhost/appMascotas/usuarios/confirmar/'.$datamail['token'];

		$body = $mail->render('confirmacion-cuenta.ml', $datamail);

		$rm = $mail->send("Dinbeat - confirmar cuenta", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $datamail['emailU']]);

		if($rm){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(400)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});

	$this->post('cambiar-contrasena', function ($req, $res, $args) {
			
		$mail = new Mail;

		$datamail = $req->getParsedBody();

		$body = $mail->render('confirmacion-cuenta.ml', $datamail);


		if($r = $mail->send("Dinbeat - confirmar cuenta", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});

	$this->post('cuenta-eliminada', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$body = $mail->render('confirmacion-cuenta.ml', $datamail);


		if($r = $mail->send("Dinbeat - confirmar cuenta", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});

	$this->post('placa-escaneada', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (new Mascota)->nuevaMascotaDatos( $data['id'] );

		$datamail = ['fecha' => $data['fecha']];

		$datamail['nombremascota'] = $r->result->nombremascota;
		$datamail['nombre'] = $r->result->nombre;
		$datamail['apellido'] = $r->result->apellido;

		if( !empty($data['latitud']) && !empty($data['longitud']) ){

			$datamail['longitud'] = $data['longitud'];
			$datamail['latitud'] = $data['latitud'];
			$datamail['latitud'] = $data['enlace'];

			$datamail['direccion'] = ($data['direccion']) "<p>Y la posición aproximada es "$data['direccion']"</p>" : "";

			$body = $mail->render('placa-escaneada-v2.ml', $datamail);

		}else{

			$body = $mail->render('placa-escaneada-v1.ml', $datamail);
		}


		if($r = $mail->send("Dinbeat - placa escaneada", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $r->result->emailU])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});

	$this->post('alerta-activada', function ($req, $res, $args) {
			
		$mail = new Mail;

		$datamail = $req->getParsedBody();

		$body = $mail->render('confirmacion-cuenta.ml', $datamail);


		if($r = $mail->send("Dinbeat - confirmar cuenta", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});

	$this->post('alerta-desactivada', function ($req, $res, $args) {
			
		$mail = new Mail;

		$datamail = $req->getParsedBody();

		$body = $mail->render('confirmacion-cuenta.ml', $datamail);


		if($r = $mail->send("Dinbeat - confirmar cuenta", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});

	$this->post('baja-mascota', function ($req, $res, $args) {
			
		$mail = new Mail;

		$datamail = $req->getParsedBody();

		$body = $mail->render('confirmacion-cuenta.ml', $datamail);


		if($r = $mail->send("Dinbeat - confirmar cuenta", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});
	
	$this->post('ficha-agregada', function ($req, $res, $args) {
			
		$mail = new Mail;

		$datamail = $req->getParsedBody();

		$body = $mail->render('confirmacion-cuenta.ml', $datamail);


		if($r = $mail->send("Dinbeat - confirmar cuenta", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});

	$this->post('nueva-mascota', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (new Mascota)->nuevaMascotaDatos( $data['id'] );

		$datamail = (array) $r->result;

		$body = $mail->render('nueva-mascota.ml', $datamail);


		if($r = $mail->send("Dinbeat - nueva mascota", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});

	$this->post('recordar-usuario', function ($req, $res, $args) {
			
		$mail = new Mail;

		$datamail = $req->getParsedBody();

		$body = $mail->render('confirmacion-cuenta.ml', $datamail);


		if($r = $mail->send("Dinbeat - confirmar cuenta", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($r);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});

	$this->post('recordatorio-vacunas', function ($req, $res, $args) {
			
        $um = new Vacuna();
        
        $vacunas = $um->notificables();

        foreach ($vacunas->result as $v) {
          
          $mail = new Mail;

          $mail->render('recordatorio-vacuna.ml', $v);

          $mail->send("Dinbeat - Has olvidado tu contraseña?", ["xarias13@gmail.com", "danieljtorres94@gmail.com", $v->emailU]);

        }

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(json_encode($vacunas->result));

	});

});