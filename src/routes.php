<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->post('/subir-foto', function ($req, $res, $args) {
    
	return $res->withStatus(200)
					->withHeader("Content-Type", "application/json")
					->withJson($req->getUploadedFiles()['foto']->moveTo(__DIR__.'/public/templates'));

});
