<?php
include_once('include/includes.php');
?>
<?php
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
  do_html_header('Mail setting-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  if(!userPermission(1)){
   echo '<table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
   do_rightbar();
   do_footer();
   do_html_footer();
   exit;
  }
  $action = $_POST['action'];
  switch ($action) {
  	case 'add': add();break;
  	case 'edit': edit();break;
  }
?>
<form id="addedit" method="POST" action="" target="_self">
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>SMTP setting</h2></div></td></tr>
<?php
$db_conn=db_connect();
$query="SELECT * FROM mail";
$result=$db_conn->query($query);
$match=$result->fetch_assoc();
if (!$match) {
?>
	<tr>
		<td width='20%'>Server:</td>
  	<td width='80%'><input type='text' name='server' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['server']))?>"/></td>
	</tr>
	<tr>
		<td>Need authentication:</td>
		<td>
<?php
$auth=array('YES'=>'TRUE','NO'=>'FALSE');
echo array_select('auth',$auth,$_POST['auth']);
?>
		</td>
	</tr>
	<tr>
		<td>Security:</td>
		<td>
<?php
$security=array('NONE'=>'','SSL'=>'ssl');
echo array_select('security',$security,$_POST['security']);
?>
		</td>
	</tr>
	<tr>
		<td>Server port:</td>
  	<td><input type='text' name='serverport' size="40" value="25"/></td>
	</tr>
	<tr>
		<td>Username:</td>
  	<td><input type='text' name='username' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['username']))?>"/></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type='password' name='password' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['password']))?>"/></td>
	</tr>
	<tr>
		<td>Mail type:</td>
		<td>
<?php
$mailtype=array('HTML'=>'HTML','TXT'=>'TXT');
echo array_select('mailtype',$mailtype,$_POST['mailtype']);
?>
		</td>
	</tr>
	<tr>
		<td>Mail from:</td>
		<td><input type='text' name='from' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['from']))?>"/></td>
	</tr>
	<tr>
		<td>Mail from name:</td>
		<td><input type='text' name='fromname' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['fromname']))?>"/></td>
	</tr>
	<tr>
		<td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
	</tr>
	<input type="hidden" name="action" value="add">
<?php
}
else {
?>
	<tr>
		<td width='20%'>Server:</td>
  	<td width='80%'><input type='text' name='server' size="40" value="<?php echo stripslashes(htmlspecialchars($match['server']))?>"/></td>
	</tr>
	<tr>
		<td>Need authentication:</td>
		<td>
<?php
$auth=array('YES'=>'TRUE','NO'=>'FALSE');
echo array_select('auth',$auth,$match['auth']);
?>
		</td>
	</tr>
	<tr>
		<td>Security:</td>
		<td>
<?php
$security=array('NONE'=>'','SSL'=>'ssl');
echo array_select('security',$security,$match['security']);
?>
		</td>
	</tr>
	<tr>
		<td>Server port:</td>
  	<td><input type='text' name='port' size="40" value="<?php echo stripslashes(htmlspecialchars($match['port']))?>"/></td>
	</tr>
	<tr>
		<td>Username:</td>
  	<td><input type='text' name='username' size="40" value="<?php echo stripslashes(htmlspecialchars($match['username']))?>"/></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type='password' name='password' size="40" value="<?php echo stripslashes(htmlspecialchars($match['password']))?>"/></td>
	</tr>
	<tr>
		<td>Mail type:</td>
		<td>
<?php
$mailtype=array('HTML'=>'HTML','TXT'=>'TXT');
echo array_select('mailtype',$mailtype,$match['mailtype']);
?>
		</td>
	</tr>
	<tr>
		<td>Mail from:</td>
		<td><input type='text' name='from' size="40" value="<?php echo stripslashes(htmlspecialchars($match['from']))?>"/></td>
	</tr>
	<tr>
		<td>Mail from name:</td>
		<td><input type='text' name='fromname' size="40" value="<?php echo stripslashes(htmlspecialchars($match['fromname']))?>"/></td>
	</tr>
	<tr>
		<td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
	</tr>
  <input type="hidden" name="action" value="edit">
<?php
}
?>
</table>
</form>
<?php
  if ($match) {
	?>
	<form id="mailtest" method="post" action="" target="_self">
	<table width="100%" class="operate" style="margin-top:3pt">
	  <tr><td colspan='2'><div align='center'><h2>Mail test</h2></div></td></tr>
	  <tr><td width='20%'>Mail to:</td>
        <td width='80%'><input type='text' name='smtpmailto' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['smtpmailto']))?>"/></td>
    </tr>
	  <tr><td width='20%'>Mail subject:</td>
        <td width='80%'><input type='text' name='mailsubject' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['mailsubject']))?>"/></td>
    </tr>
    <tr><td width='20%'>Mail body:</td>
        <td><textarea name='mailbody' cols="50" rows="3"><?php echo stripslashes($_POST['mailbody']) ?></textarea></td>
    </tr>
    <tr>
      <td colspan='2'><input type='submit' value='Submit' /></td>
    </tr>
    <input type="hidden" name="action" value="test">
	</table>
	</form>
	<?php
  }
?>
<?php
  $action = $_POST['action'];
  switch ($action) {
  	case 'test': test();break;
  }
  function add() {
  	try {
  		if (!filled_out(array($_REQUEST['server'],$_REQUEST['port'],$_REQUEST['from'],$_REQUEST['fromname']))) {
  			throw new Exception('You have not filled the form out correctlly, please try again.');
  		}
  		$server = $_REQUEST['server'];
  		$auth = $_REQUEST['auth'];
  		$security = $_REQUEST['security'];
  		$port = $_REQUEST['port'];
  		$username = $_REQUEST['username'];
  		$password = $_REQUEST['password'];
  		$mailtype = $_REQUEST['mailtype'];
  		$from = $_REQUEST['from'];
  		$fromname = $_REQUEST['fromname'];

  		if(!valid_email($from)) {
  			throw new Exception("$from is not a valid email address, please go back and try again.");
  		}

  		$db_conn = db_connect();
  		$query = "insert into mail
      (server,auth,security,port,username,password,mailtype,from,fromname)
       VALUES
      ('$server','$auth','$security','$port','$username','$password','$mailtype','$from','$fromname')";
  		$result = $db_conn->query($query);
  		$id=$db_conn->insert_id;
  		if (!$result) {
  			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
  		}
  	}
  	catch (Exception $e) {
  		echo '<table class="alert" width="100%"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  	}
  }
  function edit() {
  	try {
  		if (!filled_out(array($_REQUEST['server'],$_REQUEST['port'],$_REQUEST['from'],$_REQUEST['fromname']))) {
  			throw new Exception('You have not filled the form out correctlly, please try again.');
  		}
  		$server = $_REQUEST['server'];
  		$auth = $_REQUEST['auth'];
  		$security = $_REQUEST['security'];
  		$port = $_REQUEST['port'];
  		$username = $_REQUEST['username'];
  		$password = $_REQUEST['password'];
  		$mailtype = $_REQUEST['mailtype'];
  		$from = $_REQUEST['from'];
  		$fromname = $_REQUEST['fromname'];

  		if(!valid_email($from)) {
  			throw new Exception("$from is not a valid email address, please go back and try again.");
  		}

  		$db_conn=db_connect();
  		$query = "UPDATE mail SET
  		`server`='$server',
  		`auth`='$auth',
  		`security`='$security',
			`port`='$port',
			`username`='$username',
			`password`='$password',
			`mailtype`='$mailtype',
			`from`='$from',
			`fromname`='$fromname'";
  		$result = $db_conn->query($query);
  		if (!$result) {
  			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
  		}
  	}
  	catch (Exception $e) {
  		echo '<table class="alert" width="100%"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  	}
  }
function test() {
	try {
		if (!filled_out(array($_REQUEST['smtpmailto'],$_REQUEST['mailsubject'],$_REQUEST['mailbody']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$smtpmailto = $_REQUEST['smtpmailto'];
		$mailsubject = mb_convert_encoding($_REQUEST['mailsubject'],"utf-8","utf-8");
		$mailbody = mb_convert_encoding($_REQUEST['mailbody'],"utf-8","utf-8");

		if(!valid_email($smtpmailto)) {
			throw new Exception("$smtpmailto is not a valid email address, please go back and try again.");
		}
		$mail             = new Mailer();
		$mail->basicSetting();
		$body							=nl2br($mailbody);
		$body             =eregi_replace("[\]",'',$body);
		$mail->Subject    =$mailsubject;
		$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
		$mail->MsgHTML($css.$body);
		$mail->AddAddress($smtpmailto);
		if(!@$mail->Send()) {
			throw new Exception("$mail->ErrorInfo");
		}
		else {
			echo "<p>Message has been sent</p>";
		}
	}
	catch (Exception $e) {
		echo "<p>Mailer Error: ".$e->getMessage()."</p>";
	}
}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
