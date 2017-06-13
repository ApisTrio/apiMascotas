<?php
use App\Model\Mascota;
use App\Model\Dueno;

$app->group('/duenos/', function () {

	$this->get('mascota/{id}', function($req, $res, $args){

		$model = new Dueno;

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->getBody()
			->withJson($model->mascotaDuenos($args['id']));

	});

});