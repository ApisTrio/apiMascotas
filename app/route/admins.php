<?php
use App\Model\Admin;

use App\Lib\Token;


$app->group('/admin/', function () {


	$this->post('login', function ($req, $res, $args) {
			
		$model = new Admin;
		$r = $model->login($req->getParsedBody());

		if($r->response){

			$token_data = ['id' => $r->result->idAdmin, 'is_admin' => true];
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

	$this->get('datos/{id}', function ($req, $res, $args) {

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->getBody()
			->withJson($model->get($args['id']));

	});
	

	$this->get('todos-los-registros', function ($req, $res) {
			
		$model = new Admin();
		
		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($model->duenosMascotasPlacas());

	});

	$this->post('super-busqueda', function ($req, $res) {
			
		$model = new Admin();
		
		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($model->superBusqueda($req->getParsedBody()));

	});


});