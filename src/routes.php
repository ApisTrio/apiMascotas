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

$app->get('/public/images/mascotas/{nombre}', function ($req, $res, $args) {
    
	$data = $args['nombre'];    
	$image = file_get_contents('./public/images/mascotas/'.$data,FILE_USE_INCLUDE_PATH);    
      

	$res->write($image);    

	return $res->withHeader('Content-Type', FILEINFO_MIME_TYPE);


});

$app->get('/public/images/icons/{nombre}', function ($req, $res, $args) {
    
	$data = $args['nombre'];    
	$image = file_get_contents('./public/images/icons/'.$data,FILE_USE_INCLUDE_PATH);    
      

	$res->write($image);    

	return $res->withHeader('Content-Type', FILEINFO_MIME_TYPE);


});

$app->get('/public/images/corbatas/{forma}/{nombre}', function ($req, $res, $args) {
    
	$nombre = $args['nombre'];    
	$forma = $args['forma'];    
	$image = file_get_contents('./public/images/corbatas/'.$forma.'/'.$data,FILE_USE_INCLUDE_PATH);    
      

	$res->write($image);    

	return $res->withHeader('Content-Type', FILEINFO_MIME_TYPE);


});