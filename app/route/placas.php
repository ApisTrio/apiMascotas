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

    
    $this->get('datos/{codigo}', function ($req, $res, $args) {
        $um = new Placa();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Datos($args['codigo'])
            )
        );
    });

    $this->get('generar/{cantidad}', function ($req, $res, $args) {
        $um = new Placa();
        $datos = $args['cantidad'];
        $codigosGenerados = array();
        $codigoPlacas =  $um->Codigos();

        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        //Obtenemos la longitud de la cadena de caracteres
        $longitudCadena=strlen($cadena);
               
        //Se define la variable que va a contener la Placa

        $longitudCodigo=6;

        for ($i=1; $i <= $datos; $i++) 
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
                  array_push($codigosGenerados, $codigoNuevo);
                  $continue =False;
               }
            }
                       
        }

        ////////////////
            $titulo = 'Placas Generadas';
             /** Error reporting */
            error_reporting(E_ALL);
            ini_set('display_errors', TRUE);
            ini_set('display_startup_errors', TRUE);
            date_default_timezone_set('Europe/London');
            if (PHP_SAPI == 'cli')
              die('This example should only be run from a Web Browser');
            /** Include PHPExcel */
            require_once dirname(__FILE__) . '/../lib/PHPExcel.php';
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Dinbeat")
                           ->setTitle($titulo)
                           ->setDescription("Placas Generada");
            // Add some data
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'CODIGO')
                        ->setCellValue('B1', 'URL');
            // Miscellaneous glyphs, UTF-8
            $y = 2;
            foreach ($codigosGenerados as $movi) {

               $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$y, $movi)
                        ->setCellValue('B'.$y, 'http://www.dinbeat.com/qr/'.
$movi);
                        $y++;
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle($titulo);
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$titulo.'.xls');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('excel/'.$titulo.'.xls');
            $objWriter->save('php://output');
          /* return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(array('response' => true, 'link' => 'excel/'.$titulo.'.xls'))
            );*/
            
            exit; 

        //////////////////


        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode($Response = array('response' => "True" ) )
        );
    });


    $this->post('bloquear', function ($req, $res, $args) {
        $um = new Placa();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Bloquear( $req->getParsedBody())
            )
        );
    });
    $this->post('desbloquear', function ($req, $res, $args) {
        $um = new Placa();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Desbloquear( $req->getParsedBody())
            )
        );
    });
/////////////////////////////////////////////////////////////////////////////////////////////
    $this->get('asignada/{codigo}', function ($req, $res, $args) {
        $um = new Placa();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->VerificarAsignada($args['codigo'])
            )
        );
    });

    $this->get('creada/{codigo}', function ($req, $res, $args) {
        $um = new Placa();

        $asignada = $um->VerificarAsignada($args["codigo"]);

        if ($asignada->response){
          $r =  array('response' => false,'msg'=> 'Placa ya esta asignada');
        }

        else{
          $existe = $um->Datos($args["codigo"]);
            if ($existe->response){

                if($existe->result->bloqueado == NULL){
                $r =  array('response' => true);
                }

                else{
                   $r =  array('response' => false,'msg'=> 'Placa bloqueada');
                }

            }
            else{
              $r =  $existe;
            }
        }

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $r
            )
        );
    });


    $this->post('asignar', function ($req, $res) {
        $um = new Placa();
        $datos = $req->getParsedBody();

        $asignada = $um->VerificarAsignada($datos["codigo"]);

        if ($asignada->response){
          $r =  array('response' => false,'msg'=> 'Placa ya esta asignada');
        }

        else{
          $existe = $um->Datos($datos["codigo"]);
            if ($existe->response){

                if($existe->result->bloqueado == NULL){
                $datos["placas_idPlaca"] = $existe->result->idPlaca;
                $r = $um->Asignar($datos);
                }

                else{
                   $r =  array('response' => false,'msg'=> 'Placa bloqueada');
                }

            }
            else{
              $r =  $existe;
            }
        }

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $r
            )
        );
    });

$this->get('mascota/{id}', function ($req, $res, $args) {
        $um = new Placa();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->PlacasMascota($args['id'])
            )
        );
    });

$this->get('mascota/desactivadas/{id}', function ($req, $res, $args) {
        $um = new Placa();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->PlacasMascotaDesactivadas($args['id'])
            )
        );
    });

$this->get('activar/{id}', function ($req, $res, $args) {
        $um = new Placa();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Activar($args['id'])
            )
        );
    });

$this->get('desactivar/{id}', function ($req, $res, $args) {
        $um = new Placa();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Desactivar($args['id'])
            )
        );
    });

    
});