<?php
use App\Model\Informacion;

$app->group('/informacion/', function () {
    
    $this->post('test', function ($req, $res, $args) {
          $um = new Informacion();
        $datos = $req->getParsedBody();
        foreach ($datos["vacunas"] as $vacuna) {
          print_r($um->InsertVacuna($vacuna,$datos["idMascota"]));
        }  
    });

    $this->get('datos/{idMascota}', function ($req, $res, $args) {
        $um = new Informacion();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll($args["idMascota"])
            )
        );
    });

    $this->get('vacunas/{idMascota}', function ($req, $res, $args) {
        $um = new Informacion();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->VacunasMascota($args["idMascota"])
            )
        );
    });
        
    $this->post('registro', function ($req, $res) {
        $um = new Informacion();
        $datos = $req->getParsedBody();
        foreach ($datos["vacunas"] as $vacuna) {
          $um->InsertVacuna($vacuna,$datos["idMascota"]);
        }   

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Insert(
                    $req->getParsedBody()
                )
            )
        );
    });

    $this->post('modificar', function ($req, $res) {
        $um = new Informacion();

        $datos = $req->getParsedBody();
        foreach ($datos["vacunas"] as $vacuna) {
          $um->UpdateVacuna($vacuna,$datos["idMascota"]);
        }  
        
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
        
});
