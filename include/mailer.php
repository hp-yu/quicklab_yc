<?php
include('includes.php');
include("phpmailer/class.phpmailer.php");
class Mailer extends PHPMailer {
	function basicSetting() {
		$db_conn=db_connect();
		$query="SELECT * FROM mail";
		$result=$db_conn->query($query);
		$match=$result->fetch_assoc();

		$server = $match['server'];
		$auth = $match['auth'];
		$security = $match['security'];
  	$port =$match['port'];
  	$username = $match['username'];
  	$password = $match['password'];
  	$mailtype = $match['mailtype'];
  	$from = $match['from'];
  	$fromname = $match['fromname'];

		$this->IsSMTP();
		if ($security=="ssl") {
			$this->SMTPSecure = "ssl";                 // sets the prefix to the servier
		}
		$this->Host       = $server;      // sets GMAIL as the SMTP server
		$this->Port       = $port;                   // set the SMTP port for the GMAIL server
		if ($auth=="TRUE") {
			$this->SMTPAuth   = true;                  // enable SMTP authentication
			$this->Username 	= $username;  // SMTP username
			$this->Password 	= $password; // SMTP password
		}

		$this->From       = $from;
		$this->FromName   = $fromname;
		$this->CharSet		='utf-8';

		if ($mailtype=='HTML') {
			$this->IsHTML(true);
		}
		else {
			$this->IsHTML(false);
		}
	}
}
?>