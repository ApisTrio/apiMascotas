<?php
use App\Model\Placa;

$app->group('/placas/', function () {
    

    $this->get('test', function ($req, $res, $args) {
       $um = new Placa();
        return $res->getBody()
                   ->write( print_r($um->Codigos()));
    });
    
    $this->get('lista', function ($req, $res, $args) {
        $um = new Placa();
        
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
        $um = new Placa();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Get($args['id'])
            )
        );
    });

    $this->post('generar', function ($req, $res) {
        $um = new Placa();
        $datos = $req->getParsedBody();

        $codigoPlacas =  $um->Codigos();

        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        //Obtenemos la longitud de la cadena de caracteres
        $longitudCadena=strlen($cadena);
               
        //Se define la variable que va a contener la Placa

        $longitudCodigo=6;

        for ($i=1; $i <= $datos["cantidad"]; $i++) 
        { 
          $continue = True;

          while ($continue) 
            {
               $codigoNuevo ="";

              for($u=1 ; $u<=$longitudCodigo ; $u++)
              {
                  //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
                  $pos=rand(0,$longitudCadena-1);
               
                  $codigoNuevo .= substr($cadena,$pos,1);
               }

               if (!in_array($codigoNuevo,  $codigoPlacas) )
               {
                  $um->Insert($codigoNuevo);
                  array_push($codigoPlacas, $codigoNuevo);
                  $continue =False;
               }
            }
                       
        }
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode($Response = array('response' => "True" ) )
        );
    });

    $this->post('borrar', function ($req, $res, $args) {
        $um = new Placa();
        
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
        $um = new Placa();
        
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