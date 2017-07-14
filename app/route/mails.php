<?php
use App\Model\Usuario;
use App\Model\Dueno;
use App\Model\Mascota;
use App\Model\Vacuna;
use App\Model\Placa;

use App\Lib\Mail;
use App\Lib\Token;


$app->group('/mail/', function () {


	$this->post('confirmacion-cuenta', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (new Usuario)->confirmarCuentaDatos( $data['id'] );

		$datamail = (array) $r->result;
		$datamail['enlace'] = 'https://www.dinbeat.com/qr/confirmar/'.$data['id'].'/'.$datamail['token'];

		$body = $mail->render('confirmacion-cuenta.ml', $datamail);

		$rm = $mail->sendMail("Dinbeat - Confirmar cuenta", [$datamail['emailU']]);

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

		$data = $req->getParsedBody();

		$usuario = (new Usuario)->check('emailU', $data['emailU'])->result;
		$dueno = (new Dueno)->get($usuario->duenos_idDueno)->result;

		$token_data = ['id' => $usuario->idUsuario];
		$token = Token::generar($token_data);

		$datamail = ['nombre'=> $dueno->nombre, 'apellido' => $dueno->apellido,'enlace' => 'https://www.dinbeat.com/qr/cambiar-contrasena/'.$token];


		$body = $mail->render('cambiar-contrasena.ml', $datamail);

		$email = $usuario->emailU;

		if($rm = $mail->sendMail("Dinbeat - Cambiar clave", [$email])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});

	$this->post('cuenta-eliminada', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$usuario = (new Usuario)->get($data['id'])->result;
		$dueno = (new Dueno)->get($usuario->duenos_idDueno)->result;

		$token_data = ['id' => $data['id'], 'exp' => strtotime("+1 day")];
		$token = Token::generar($token_data);

		$datamail = ['nombre'=> $dueno->nombre, 'apellido' => $dueno->apellido,'enlace' => 'https://www.dinbeat.com/qr/eliminar-cuenta/'.$token];

		$body = $mail->render('cuenta-eliminada.ml', $datamail);

		if($rm = $mail->sendMail("Dinbeat - Cuenta eliminada", [$usuario->emailU])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
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
			$datamail['enlace'] = $data['enlace'];

			$datamail['direccion'] = ($data['direccion']) ? "<p>Y la posición aproximada es ".$data['direccion']."</p>" : "";

			$body = $mail->render('placa-escaneada-v2.ml', $datamail);

		}else{

			$body = $mail->render('placa-escaneada-v1.ml', $datamail);
		}


		if($rm = $mail->sendMail("Dinbeat - Placa escaneada", [$r->result->emailU])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});

	$this->post('alerta-activada', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (new Mascota)->nuevaMascotaDatos( $data['id'] );

		$datamail = (array) $r->result;
		$datamail['enlace'] = 'https://www.dinbeat.com/qr/perfil/desactivar-alerta?idMascota='.$data['id'];

		$body = $mail->render('alerta-activada.ml', $datamail);


		if($rm = $mail->sendMail("Dinbeat - Alerta activada", [$datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});

	$this->post('alerta-desactivada', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (new Mascota)->nuevaMascotaDatos( $data['id'] );

		$datamail = (array) $r->result;

		$body = $mail->render('alerta-desactivada.ml', $datamail);


		if($rm = $mail->sendMail("Dinbeat - Alerta desactivada", [$datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});

	$this->post('baja-mascota', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (new Mascota)->nuevaMascotaDatos( $data['id'] );

		$datamail = (array) $r->result;

		$body = $mail->render('baja-mascota.ml', $datamail);


		if($rm = $mail->sendMail("Dinbeat - Baja a mascota", [$datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});
	
	$this->post('ficha-agregada', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (new Placa)->placaAsignadaDatos($data['id']);

		$datamail = (array) $r->result;

		$body = $mail->render('ficha-agregada.ml', $datamail);


		if($rm = $mail->sendMail("Dinbeat - Placa registrada", [$datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});

	$this->post('nueva-mascota', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (new Mascota)->nuevaMascotaDatos( $data['id'] );

		$datamail = (array) $r->result;

		$body = $mail->render('nueva-mascota.ml', $datamail);

		if($rm = $mail->sendMail("Dinbeat - Nueva mascota", [$datamail['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});

	$this->post('recordar-usuario', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$usuario = (new Usuario)->check('emailU', $data['emailU'])->result;
		$dueno = (new Dueno)->get($usuario->duenos_idDueno)->result;

		$datamail = ['nombre' => $dueno->nombre, 'apellido' => $dueno->apellido, 'usuario' => $usuario->usuario, 'enlace' => 'https://www.dinbeat.com/qr/login'];

		$body = $mail->render('recordar-usuario.ml', $datamail);

		if($rm = $mail->sendMail("Dinbeat - Recordar usuario", [$usuario->emailU])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});

	$this->get('recordatorio-vacunas', function ($req, $res, $args) {
			
        $um = new Vacuna();
        
        $vacunas = $um->notificables();

        foreach ($vacunas->result as $v) {

        	if ($v->recordatorio == date('d/m/Y')) {
        		$v->recordatorio = 'hoy';
        	}
          
         	$mail = new Mail;

          	$mail->render('recordatorio-vacuna.ml', $v);

          	$mail->sendMail("Dinbeat - Has olvidado tu contraseña?", [$v->emailU]);

        }

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(json_encode($vacunas->result));

	});

	$this->post('contacto', function ($req, $res, $args) {
			
		$mail = new Mail;

		$data = $req->getParsedBody();

		$r = (array) (new Mascota)->nuevaMascotaDatos( $data['id'] )->result;

		$data['nombre'] =  $r['nombre'];
		$data['apellido'] =  $r['apellido'];
		$data['nombremascota'] =  $r['nombremascota'];

		$body = $mail->render('formulario-contacto.ml', $data);


		if($rm = $mail->sendMail("Dinbeat - Han encontrado tu mascota", [$r['emailU']])){

			return $res->withStatus(200)
			 	->withHeader('Content-type', 'application/json')
			 	->withJson($rm);

		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($rm);
	
	});

});