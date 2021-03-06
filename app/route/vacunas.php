<?php
use App\Model\Vacuna;
use App\Lib\Mail;

$app->group('/vacunas/', function () {
    
    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('SI FUNCIONA');
    });
    
    $this->get('lista', function ($req, $res, $args) {
        $um = new Vacuna();
        
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
        $um = new Vacuna();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Get($args['id'])
            )
        );
    });
    
    $this->post('registro', function ($req, $res) {
        $um = new Vacuna();
        
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
        $um = new Vacuna();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Borrar( $req->getParsedBody())
            )
        );
    });

    $this->post('eliminar', function ($req, $res, $args) {
        $um = new Vacuna();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Delete( $req->getParsedBody())
            )
        );
    });

    
});