<?php
namespace App\Lib;

require_once __DIR__.'/../../vendor/autoload.php';

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class Mail
{
	private static $username = "danieljtorres94@gmail.com";
	private static $password = "ugbmmdejbszjycdb";
	private static $host = "smtp.gmail.com";
	private static $port = 587;
	private static $encrypt = 'tls';
	public $mailer;
	public $body;

	private static $from = ['john@doe.com' => 'Dinbeat'];

	function __construct()
	{
		$transport = (new Swift_SmtpTransport(self::$host, self::$port, self::$encrypt));
		$transport->setUsername(self::$username);
		$transport->setPassword(self::$password);	
	
		$this->mailer = new Swift_Mailer($transport);

	}

	public function send($subject = "", $to)
	{

		$message = new Swift_Message($subject);
		$message->setFrom(self::$from);
		$message->setTo($to);
		$message->setBody($this->body,  'text/html');

		$mail = $this->mailer;
		$result = $mail->send($message);
		return $result;
	}

	public function render($tpl = "", $data = [])
	{

		$body = file_get_contents(__DIR__ . '/../../templates/mails/'.$tpl);

		foreach ($data as $key => $value) {
		 	$body = str_replace("{!$key!}", $value, $body);
		}

		$this->body = $body;

		return $this->body;
	}

}
