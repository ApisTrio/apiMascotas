<?php
use App\Model\Perdida;
use App\Model\Mascota;

$app->group('/perdidas/', function () {
    
    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('SI FUNCIONA');
    });
    
    $this->get('lista', function ($req, $res, $args) {
        $um = new Perdida();
        $m = new Mascota();
        $datos = $um->GetAll();
       
        for ($i=0; $i < count($datos->result); $i++) { 
          $datos->result[$i]->edad = $m->Edad($datos->result[$i]->anios,$datos->result[$i]->meses);
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

    $this->get('dueno/{id}', function ($req, $res, $args) {
        $um = new Perdida();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetDueno($args["id"])
            )
        );
    });

    $this->get('encontradas', function ($req, $res, $args) {
    $um = new Perdida();
    
    return $res
       ->withHeader('Content-type', 'application/json')
       ->getBody()
       ->write(
        json_encode(
            $um->GetAll()
        )
    );
});

  $this->get('encontradas/dueno', function ($req, $res, $args) {
    $um = new Perdida();
    
    return $res
       ->withHeader('Content-type', 'application/json')
       ->getBody()
       ->write(
        json_encode(
            $um->GetAll()
        )
    );
  });

  $this->get('aviso', function ($req, $res, $args) {
    $um = new Perdida();
    
    return $res
       ->withHeader('Content-type', 'application/json')
       ->getBody()
       ->write(
        json_encode(
            $um->GetAll()
        )
    );
  });

  $this->post('aviso/nuevo', function ($req, $res) {
        $um = new Perdida();
        
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
   
    
    $this->post('registro', function ($req, $res) {
        $um = new Perdida();
        
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
        $um = new Perdida();
        
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