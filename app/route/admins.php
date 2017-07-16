<?php
use App\Model\Admin;
use Dompdf\Dompdf;
use Dompdf\Options;

use App\Lib\Token;


$app->group('/admin/', function () {


	$this->post('login', function ($req, $res, $args) {
			
		$model = new Admin;
		$r = $model->login($req->getParsedBody());

		if($r->response){

			$token_data = ['id' => $r->result->idAdmin, 'is_admin' => true];
			$token = Token::generar($token_data);

			$data["token"] = $token;
			$data["usuario"] = $r->result;
			
			return $res->withStatus(200)
					->withHeader("Content-Type", "application/json")
					->withJson($data);
		}

		return $res->withStatus(404)
					->withHeader("Content-Type", "application/json")
					->withJson($r);
	
	});

	$this->get('datos/{id}', function ($req, $res, $args) {

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->getBody()
			->withJson($model->get($args['id']));

	});
	

	$this->get('todos-los-registros', function ($req, $res) {
			
		$model = new Admin();
		
		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($model->duenosMascotasPlacas());

	});

	$this->post('super-busqueda', function ($req, $res) {
			
		$model = new Admin();
		
		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($model->superBusqueda($req->getParsedBody()));

	});

	$this->post('exportar-registros', function ($req, $res) {

		// instantiate and use the dompdf class
		$dompdf = new Dompdf();

		$html = '<!DOCTYPE html>
		<html>
		<head>
			<title></title>
			<style>

				body{
					padding-top: 20px;
				}
				table.table-admin th,
				.table-admin td {
				    padding: 15px 0 15px 5px;
				    text-align: left;
				}

				table.table-admin {
				    border-collapse: collapse;
				}

				table.table-admin,
				table.table-admin th,
				table.table-admin td {
				    border: 1px solid #e8e8e8;
				}

				.table-admin th {
				    font-size: 11px;
				    font-weight: 600;
				    color: #424242;
				    position: relative;
				}

				.table-admin th img{
				    display:none;
				}

				.table-admin th:before {
				    font-size: 14px;
				    font-weight: 600;
				    color: #424242;
				}

				.table-admin tbody tr td {
				    font-size: 10px;
				    color: #333333;
				}

				.table-admin tbody tr td img {
				    margin-bottom:5px;
				}

				.table-admin tbody tr td.usuario {
				    color: #7eb6e7;
				    font-weight: bold;
				}

				[href]{
					text-decoration: none;
					color: #7eb6e7;
				}

			<style>
		</head>
		<body>
			<div style="width:100%; margin-top:20px;">
				<table class="table-admin" style="width:100%; top:20px;">'.$req->getParsedBody()['datos'].'</table>
			</div>
		</body>
		</html>';

		//$html = str_replace("thead", "tr", $html);


		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'landscape');

		// Render the HTML as PDF
		$dompdf->render();

		$url = 'public/pdf/'.substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6).'.pdf';

		// Output the generated PDF to Browser
		file_put_contents($url , $dompdf->output());

		return $res->withStatus(200)
			->withHeader('Content-type', 'application/json')
			->withJson($url);
	});



    $this->post('excel-busqueda', function ($req, $res, $args) {

    	$datos = $req->getParsedBody()['datos'];

        $titulo = 'Busqueda-de-Usuarios';
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
                        ->setCellValue('A1', 'USUARIO')
                        ->setCellValue('B1', 'NOMBRE')
                        ->setCellValue('C1', 'APELLIDO')
                        ->setCellValue('D1', 'ACTIVO')
                        ->setCellValue('E1', 'TELEFONO')
                        ->setCellValue('F1', 'EMAIL')
                        ->setCellValue('G1', 'PAIS')
                        ->setCellValue('H1', 'PROVINCIA')
                        ->setCellValue('I1', 'CIUDAD')
                        ->setCellValue('J1', 'C.P.')

                        ->setCellValue('K1', 'MASCOTA')
                        ->setCellValue('L1', 'ESPECIE')
                        ->setCellValue('M1', 'RAZA')
                        ->setCellValue('N1', 'FECHA NACIMIENTO')

                        ->setCellValue('O1', 'ID')
                        ->setCellValue('P1', 'FORMA')
                        ->setCellValue('Q1', 'URL MODELO')
                        ->setCellValue('R1', 'MODELO');

            // Miscellaneous glyphs, UTF-8
            $y=2;
            $activo=array(1=>"SI",0=>"NO");
            foreach ($datos as $usuario) {
		    		foreach ($usuario["mascotas"] as $mascota) {
		    			foreach ($mascota["placas"] as $placa) {
			    			$objPHPExcel->setActiveSheetIndex(0)
			    			->setCellValue('A'.$y, $usuario["usuario"])
				    		->setCellValue('B'.$y, $usuario["nombre"])
				    		->setCellValue('C'.$y, $usuario["apellido"])
				    		->setCellValue('D'.$y, $activo[$usuario["activo"]])
				    		->setCellValue('E'.$y, $usuario["telefono"])
				    		->setCellValue('F'.$y, $usuario["emailU"])
				    		->setCellValue('G'.$y, $usuario["pais"])
				    		->setCellValue('H'.$y, $usuario["provincia"])
				    		->setCellValue('I'.$y, $usuario["ciudad"])
				    		->setCellValue('J'.$y, $usuario["codigo_postal"])
				    		->setCellValue('K'.$y, $mascota["nombre"])
				    		->setCellValue('L'.$y, $mascota["especie"])
				    		->setCellValue('M'.$y, $mascota["raza"])
				    		->setCellValue('N'.$y, $mascota["fecha_nacimiento"])
				    		->setCellValue('O'.$y, $placa["codigo"])
				    		->setCellValue('P'.$y, $placa["forma"])
				    		->setCellValue('Q'.$y, 'dinbeat.com/qr/assets/images/placas/'.$placa["forma"].'/'.$placa["modelo"])
				    		->setCellValue('R'.$y, $placa["nombre"]);
				    		$y++;
		    		}
	    		}
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


            //$objWriter->save('php://output');
           return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(array('response' => true, 'archivo' => $titulo.'.xls'))
            );
            
            exit; 

        //////////////////


       /* return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode($Response = array('response' => "True" ) )
        );*/
    });
});