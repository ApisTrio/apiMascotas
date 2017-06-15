<?php
use App\Model\Admin;

use App\Lib\Token;


$app->group('/mail/', function () {


	$this->post('confirmacion-cuenta', function ($req, $res, $args) {
			
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

	$this->post('placa-escaneada', function ($req, $res, $args) {
			
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