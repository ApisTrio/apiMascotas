<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
	<style type="text/css">
		
		*{font-family: 'Open Sans', sans-serif;}

		div.bodymail{ display: block; width: 100%; max-width: 800px; background-color: white; text-align: center; margin: auto;}
		
		div.block{ width: 100%; text-align: center; margin: 0 auto; }
		
		ul.list{ list-style: none; padding: 0;}
		ul.list li{ list-style: none; padding: 0; display: inline-block;}
		ul.list li a{ text-decoration: none; color: #a3b1b7; margin: 0px 13px; font-size: 20px;}
		ul.list li a:hover{ text-decoration: none; color: #B2DA9F; }

		.boton-verde {
		    color: white;
		    background-color: #B2DA9F;
		    border: none;
		    padding: 12px;
		    padding-left: 24px;
		    padding-right: 24px;
		    font-weight: bold;
		    font-size: 12px;
		    border-radius: 35px;
		    text-decoration: none;
		}

		.boton-verde:hover {
		    background-color: #A5CC93;
		}

		div.text-purple p{
			color: #6c709c;
			font-weight: bold;
			font-size: 21px;
		}
		div.text-left{ text-align: justify; } 
		div.text-left p{ color: #424242; font-size: 18px; font-weight: 300; }
		div.padding { padding: 0 30px 0 30px; box-sizing: border-box; }

		div.footer{ background-color: #8084c0; }

	</style>
</head>
<body>
	<div class="bodymail">
		<div class="block">
			<img src="http://dinbeat.com/wp-content/uploads/2016/11/Logo-dinbeat-web.jpg" width="100px">
		</div>
		<div class="block">
			<ul class="list">
				<li><a href="#">Placas QR</a></li>
				<li><a href="#">Tienda</a></li>
				<li><a href="#">Blog</a></li>
			</ul>
		</div>
		<div class="block">
			<div style="width: 140px; height: 120px; background-color: #6c709c; color: white; margin: 60px auto;">I</div>
			<!--<img src="">-->
		</div>
		<div class="block padding text-purple">
			<p>Has eliminado tu cuenta</p>
		</div>
		<div class="block text-left padding">
			<p>Hola {!nombre!} {!apellido!},</p>
			<p>Para confirmar la eliminación de tu cuenta pulsa el siguiente boton:</p>
			<br>
			<p style="text-align: center; ">
				<a href="{!enlace!}" class="boton-verde">CONFIRMAR</a>
			</p>
			<br>
			<p>¿Tienes alguna duda sobre el funcionamiento? Revisa nuestra sección de <a src="http://www.dinbeat.com/preguntas-frecuentes">Preguntas frecuentes</a>.</p>
			<br>
			<p>Gracias por haber formado parte de DinbeatQR, esperamos volver a verte pronto.</p>
		</div>
		<div class="block text-left padding" style="margin: 70px 0;">
			<p>--
			<br>
			DinbeatQR
			<br><br>
			qr@dinbeat.com
			<br>
			Calle Llacuna 162-164, oficina 208b
			<br>
			08018, Barcelona 
			<br>
			Tel. 932453655 / www.dinbeat.com
			<br>
			</p>
			<p style="color: #afb2b6; font-size: 15px;">Este mensaje de correo electrónico está dirigido exclusivamente al destinatario o destinatarios indicados en el mismo. La información en él contenida puede ser confidencial y/o privada por lo que está totalmente prohibida su difusión o reproducción. Si usted no es el destinatario de este mensaje, por favor devuélvalo inmediatamente a la dirección de envío y destrúyalo.</p>
		</div>
		<div class="block footer">
			<ul class="list">
				<li><a href="#">FB</a></li>
				<li><a href="#">TW</a></li>
				<li><a href="#">IG</a></li>
			</ul>	
		</div>
	</div>
</body>
</html>