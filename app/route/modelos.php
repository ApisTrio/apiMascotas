<?php
use App\Model\Modelo;

$app->group('/modelos/', function () {
    
    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('SI FUNCIONA');
    });
    
    $this->get('lista', function ($req, $res, $args) {
        $um = new Modelo();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });

    
    $this->get('datos/{id}', function ($req, $res, $args) {
        $um = new Modelo();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Get($args['id'])
            )
        );
    });

    $this->get('forma/{forma}', function ($req, $res, $args) {
        $um = new Modelo();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetForma($args['forma'])
            )
        );
    });
    
    $this->post('registro', function ($req, $res) {
        $um = new Modelo();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->InsertOrUpdate(
                    $req->getParsedBody()
                )
            )
        );
    });
    
    $this->post('borrar', function ($req, $res, $args) {
        $um = new Modelo();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Borrar( $req->getParsedBody())
            )
        );
    });

    
});