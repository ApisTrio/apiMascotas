<?php
// Routes



$app->get('/subir-foto', function ($req, $res, $args) {
    
	return $this->renderer->render($res, 'index.phtml');
});

$app->post('/subir-imagen', function ($req, $res, $args) {
    
	$imagenes = $req->getUploadedFiles();
	$info = $req->getParsedBody();
	$p = $imagenes['imagen'];


	$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$nombre = substr(str_shuffle($chars), 0, 6);
	$p->moveTo( './public/images/'.$info['path'].'/'.$nombre.$info['tipo'] );


		return $res->withStatus(200)
				->withHeader("Content-Type", "application/json")
				->withJson($nombre.$info['tipo']);


});
