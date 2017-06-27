<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
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

		.social li{
		    margin: 4px -12px 0px;
		}

	</style>
</head>
<body>
	<div class="bodymail">
		<div class="block">
			<a href="https://www.dinbeat.com/qr/" target="_blank">
				<img src="https://www.dinbeat.com/qr/api/public/images/icons/logo.png" width="100px">
		</div>
		<div class="block">
			<ul class="list">
				<li><a target="_blank" href="https:/www.dinbeat.com/qr/">Placas QR</a></li>
				<li><a target="_blank" href="https:/www.dinbeat.com/tienda/">Tienda</a></li>
				<li><a target="_blank" href="https:/www.dinbeat.com/blog/">Blog</a></li>
			</ul>
		</div>
		<hr>
		<div class="block">
			<img src="https://www.dinbeat.com/qr/api/public/images/icons/recordatorio.png">
		</div>
		<div class="block padding text-purple">
			<p>Recordatrio</p>
		</div>
		<div class="block text-left padding">
			<p>Hola {!nombre!} {!apellido!},</p>
			<br>
			<p>Te contactamos desde DINBEAT para informarte de que {!nombreform!} ha encontrado a {!nombremascota!}</p>
			<br>
			<p>Para que os pongáis en contacto, y puedas reunirte con tu mascota, por favor llámalo al {!telefonoform!} o escríbele a su correo electrónico {!emailform!}. Además de esto {!nombreform!} te ha escrito:</p>
			<br>
			<p>{!mensajeform!}</p>
			<br>
			<p>Esperamos que esta información te sea útil, recuerda desactivar la alerta de mascota perdida desde tu menú de usuario en <a href="https://www.dinbeat.com/qr">www.dinbeat.com/qr</a> cuando recuperes a {!nombremascota!}
			</p>
			<br>
			<p>Un saludo y gracias por conﬁar en DinbeatQR</p>
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
			<ul class="list social">
				<li><a href="https://www.facebook.com/dinbeatofficial/" target="_blank"><img src="https://www.dinbeat.com/qr/api/public/images/icons/fb.png"></a></li>
				<li><a href="https://twitter.com/DinbeatOfficial" target="_blank"><img src="https://www.dinbeat.com/qr/api/public/images/icons/tw.png"></a></li>
				<li><a href="https://www.instagram.com/dinbeatofficial/" target="_blank"><img src="https://www.dinbeat.com/qr/api/public/images/icons/g.png"></a></li>
			</ul>	
		</div>
	</div>
</body>
</html>