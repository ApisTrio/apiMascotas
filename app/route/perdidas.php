<?php
use App\Model\Perdida;
use App\Model\Mascota;

$app->group('/perdidas/', function () {
    
    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('SI FUNCIONA');
    });
    
    $this->get('lista/{limit}/{offset}', function ($req, $res, $args) {
        $um = new Perdida();
        $m = new Mascota();
        $datos = $um->GetAll($args["limit"],$args["offset"]);
       
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
        $m = new Mascota();
          $datos = $um->GetDueno($args["id"]);

           if ($datos->result)
        $datos->result->edad = $m->Edad($datos->result->anios,$datos->result->meses);
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $datos
            )
        );
    });

    $this->get('encontradas/lista/{limit}/{offset}', function ($req, $res, $args) {
        $um = new Perdida();
        $m = new Mascota();
        $datos = $um->Encontradas($args["limit"],$args["offset"]);
       
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

    $this->get('encontradas/dueno/{id}', function ($req, $res, $args) {
        $um = new Perdida();
        $m = new Mascota();
          $datos = $um->EncontradasDueno($args["id"]);

          if ($datos->result)
        $datos->result->edad = $m->Edad($datos->result->anios,$datos->result->meses);
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
        $um = new Perdida();
        $data = $req->getParsedBody();
        $verificar = $um->Verificar($data["idMascota"]);

        if($verificar->result){
          $r = $um->NuevaPerdida($data);
        }
        else{
          $r = $um->Insert($data);
        }

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode($r)
        );
    });

    $this->post('cambiar/encontrada', function ($req, $res) {
        $um = new Perdida();
        $data = $req->getParsedBody();
        $verificar = $um->Verificar($data["idMascota"]);

        if($verificar->result){
          $r = $um->Encontrado($data);
        }
        else{
           $r =  array('response' => false, 'message'=>'Mascota no registrada como perdida');
        }

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode($r)
        );
    });

    $this->post('aviso/nuevo', function ($req, $res) {
        $um = new Perdida();
        $data = $req->getParsedBody();
        $verificar = $um->Verificar($data["idMascota"]);

        if($verificar->result){
          $r = $um->Aviso($data);
        }
        else{
          $r =  array('response' => true, 'message'=>'Correo de aviso enviado');
        }

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode($r)
        );
    });

    $this->get('avisos/dueno/{id}', function ($req, $res, $args) {
    $um = new Perdida();
    
    return $res
       ->withHeader('Content-type', 'application/json')
       ->getBody()
       ->write(
        json_encode(
            $um->Avisadas($args["id"])
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