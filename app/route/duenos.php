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

		$r = $model->insertOrUpdate($req->getParseBody());

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($r);

	});



});