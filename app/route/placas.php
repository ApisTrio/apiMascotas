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
        $codigosGenerados = array();
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
                        ->setCellValue('B'.$y, $movi);
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
           return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(array('response' => true, 'link' => 'excel/'.$titulo.'.xls'))
            );
          //  $objWriter->save('php://output');
            exit; 

        //////////////////7


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