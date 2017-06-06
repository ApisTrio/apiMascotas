<?php
use App\Model\Mascota;
use App\Model\Dueno;

$app->group('/mascotas/', function () {
    
    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('SI FUNCIONA');
    });
    
    $this->get('dueno/{id}', function ($req, $res, $args) {
        $um = new Mascota();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->UsuarioMascotas($args['id'])
            )
        );
    });

    
    $this->get('datos/{id}', function ($req, $res, $args) {
        $um = new Mascota();
        
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
        $um = new Mascota();
        $du = new Dueno();
        $data = $req->getParsedBody();

        $mascota = $um->Insert($data);

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $du->hasMascota(
                    $data['idDueno'], $mascota->idInsertado
                )
            )
        );
    });

    $this->post('perdidas/registro', function ($req, $res) {
        $um = new Mascota();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->InsertPerdida(
                    $req->getParsedBody()
                )
            )
        );
    });

    $this->post('modificar', function ($req, $res) {
        $um = new Mascota();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Update(
                    $req->getParsedBody()
                )
            )
        );
    });
    
    $this->post('borrar', function ($req, $res, $args) {
        $um = new Mascota();
        
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
        $um = new Mascota();
        
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
