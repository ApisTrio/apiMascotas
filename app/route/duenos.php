<?php
use App\Model\Mascota;
use App\Model\Dueno;

$app->group('/duenos/', function () {

	$this->get('mascota/{id}', function($req, $res, $args){

		$model = new Dueno;

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($model->mascotaDuenos($args['id']));

	});

	$this->get('lista', function($req, $res, $args){

		$model = new Dueno;

		$r = $model->getAll();

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($r);

	});


	$this->post('modificar', function($req, $res, $args){

		$model = new Dueno;

		$duenos = $req->getParseDBody();

		$r = $model->insertOrUpdate($d);

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($r);

	});

	$this->post('borrar', function ($req, $res, $args) {
			
		$model = new Dueno();

		$data = $req->getParseBody();
		
		$r = $model->softDelete($data['id']);

		if($r->response){

			return $res->withStatus(200)
				 	->withHeader('Content-type', 'application/json')
				 	->withJson($r);

		}

		return $res->withStatus(400)
				->withHeader("Content-Type", "application/json")
				->withJson($r);
			
	});



});