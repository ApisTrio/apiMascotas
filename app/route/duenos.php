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



	$this->get('datos/{id}', function($req, $res, $args){

		$model = new Dueno;

		$r = $model->get($args['id']);

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($r);

	});


	$this->post('modificar', function($req, $res, $args){

		$model = new Dueno;

		$duenos = $req->getParsedBody();

		$r = $model->insertOrUpdate($duenos);

		if( isset($duenos['idMascota']) ){
			$rmd =  $model->hasMascota( $r->idInsertado, $duenos['idMascota'] );
		}

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($r);

	});

	$this->post('borrar', function ($req, $res, $args) {
			
		$model = new Dueno();

		$data = $req->getParsedBody();
		
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