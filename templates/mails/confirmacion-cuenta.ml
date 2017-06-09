<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
	<style type="text/css">
		
		*{font-family: 'Open Sans', sans-serif;}

		div.bodymail{ display: block; width: 100%; max-width: 800px; background-color: white; text-align: center;}
		
		div.block{ width: 100%; text-align: center; margin: 0 auto; }
		
		ul.list{ list-style: none; padding: 0;}
		ul.list li{ list-style: none; padding: 0; display: inline-block;}
		ul.list li a{ text-decoration: none; color: #a3b1b7; margin: 0px 13px; font-size: 20px;}
		ul.list li a:hover{ text-decoration: none; color: #B2DA9F; }

		div.text-left{ text-align: justify; } 
		div.text-left p{ color: #424242; font-size: 18px; font-weight: 600; }
		div.padding { padding: 0 150px 0 150px; box-sizing: border-box; }

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
			IMAGEN
			<!--<img src="">-->
		</div>
		<div class="block text-left padding">
			<p>Hola {!nombre!} {!apellido!},</p>
			<p>Necesitamos que verifiques tu cuenta</p>
			<p>Confirma la dirección de email con la que te has registrado para activarla. 
			<br><br>
			<a href="#" style="text-decoration: none; color: white; border: 1px solid #8BC34A; background-color: #8BC34A; padding: 3px 13px; border-radius: 28px; font-size: 14px;">CONFIRMAR</a></p>
			<br>
			<p>Gracias por registrarte. Haz click en el botón para confirmar que {!email!} es tu dirección de email y empezar a usar DinbeatQR.</p>
			<p>¿Tienes problemas con los enlaces de este email? Copia y pega este enlace en tu navegador para verificar tu cuenta:</p>
			
		</div>
	</div>
</body>
</html>