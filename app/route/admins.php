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

});