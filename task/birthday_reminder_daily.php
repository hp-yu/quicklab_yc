<?php
include('..\include\includes.php');
$db_conn=db_connect();
$query="SELECT id,name, birthday FROM people WHERE birthday<>'0000-00-00' AND DATE_FORMAT(birthday,'%m-%d') = DATE_FORMAT(now(),'%m-%d')";
$rs=$db_conn->query($query);
if ($rs->num_rows>0) {
	$subject="Birthday reminder, from Quicklab";
	$message.="<p>Today is ";
	$n=1;
	while ($match=$rs->fetch_assoc()) {
		if($n>1) {$message.=", ";}
		$message.=$match['name'];
		$n++;
	}
	$message.="'s birthday.</p>";
	//send mail
	$people_id = $match['id'];
	$mail= new Mailer();
	$mail->basicSetting();
	$mail->Subject =$subject;
	$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
	$mail->MsgHTML($css.$message);

	$query="SELECT * FROM people WHERE id <> '$people_id' AND state=0";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$email=trim($match['email']);
		if ($email!=''&&valid_email($email)) {
			$mail->AddAddress($email);
			@$mail->Send();
			$mail -> ClearAddresses();
		}
	}
}
?>