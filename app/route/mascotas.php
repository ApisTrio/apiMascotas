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
        $datos = $um->DuenoMascotas($args['id']);

        for ($i=0; $i < count($datos->result); $i++) { 
          $datos->result[$i]->edad = $um->Edad($datos->result[$i]->anios,$datos->result[$i]->meses);
         }
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $datos
            )
        );
    });

    
    $this->get('datos/{id}', function ($req, $res, $args) {
        $um = new Mascota();

        $datos = $um->Get($args['id']);

         if ($datos->result)
        $datos->result->edad = $um->Edad($datos->result->anios,$datos->result->meses);

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
               $datos
            )
        );
    });
    
    $this->post('registro', function ($req, $res) {
        $um = new Mascota();
        $du = new Dueno();
        $data = $req->getParsedBody();

        $mascota = $um->Insert($data);
         $du->hasMascota(
                    $data['idDueno'], $mascota->idInsertado
                );
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
               $mascota
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
    

    $this->post('asignar/duenos', function ($req, $res, $args) {
        
        $um = new Dueno();

        $data = $req->getParsedBody();

        foreach ($data['duenos'] as $d) {
          $um->hasMascota($um->insertOrUpdate($d)->idInsertado, $data['id']);
        }

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->withJson( ["response" => true, "result" => false, "message" => "", "idInsertado" => null]);
    });

});
