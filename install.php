<?php
include('include/includes.php');
?>
<?php
  do_html_header('Install-Quicklab');
  do_header_3();
  processRequest();
  do_html_footer();
?>
<?php
function installForm()
{
?>
  <form method='post' action=''>
    <table class='standard' width="100%" style="margin-top:3px">
   	 <tr><td colspan='2'><h3>Database initialization</h3></td></tr>
   	 <tr><td width="10%">Database Server Address:</td>
   	 <td width="90%"><input type="text" name="address" size=16 align="left" value="localhost"></td></tr>
   	 <tr><td >Database Name:</td>
   	 <td><input type="text" name="name" size=16 align="left" value="quicklab"></td></tr>
   	 <tr><td  >Database User name:</td>
   	 <td><input type="text" name="username" size=16 align="left" value="quicklab"></td></tr>
   	 <tr><td >Database Password:</td>
   	 <td><input type="password" name="password" size=16 align="left" value=""></td></tr>
  	 <tr><td colspan="2" >
   	 <input type="submit" value="Next">
   	 <input type='hidden' name='action' value='install'></td></tr>
   </table></form>
<?php
}
function adminForm()
{
  $db_conn=db_connect();
  $query="SELECT * FROM users WHERE username='admin'";
  $result = $db_conn->query($query);
  $match=$result->fetch_assoc();
  if($match['people_id']!=0)
  {
  	header('location:index.php');
  }
  ?>
  <form name='add' method='post' action='' >
    <table class='standard' width="100%" style="margin-top:3px">
	<tr><td colspan='2'><h3>Name administrator:</h3></td>
      </tr>
      <tr>
        <td width='10%'>Name:</td>
        <td width='90%'><input type='text' name='name' value="<?php echo stripslashes(htmlspecialchars($_POST['name'])) ?>"/>*</td>
      </tr>
      <tr>
        <td>Gender:</td>
        <td><?php
		$gender=array('female'=>'0','male'=>'1');
		echo array_select('gender',$gender,2,$_POST['gender']);?>
        </td>
      </tr>
      <tr>
        <td>Identity card number:</td>
        <td><input type='text' name='identity_card' size="30" value="<?php echo stripslashes(htmlspecialchars($_POST['identity_card'])) ?>"/></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input type='text' name='email' size="30" value="<?php echo stripslashes(htmlspecialchars($_POST['email'])) ?>"/>*</td>
      </tr>
      <tr>
        <td>Mobile:</td>
        <td><input type='text' name='mobile' value="<?php echo stripslashes(htmlspecialchars($_POST['mobile'])) ?>"/></td>
      </tr>
      <tr>
        <td>IM:</td>
        <td><input type='text' name='im' value="<?php echo stripslashes(htmlspecialchars($_POST['im'])) ?>"/></td>
      </tr>
      <tr>
        <td>Telephone:</td>
        <td><input type='text' name='tel' value="<?php echo stripslashes(htmlspecialchars($_POST['tel'])) ?>"/></td>
      </tr>
      <tr>
        <td>Birthday:</td>
        <td><input type='text' name='birthday' value="<?php echo stripslashes(htmlspecialchars($_POST['birthday'])) ?>"/>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Enter date:</td>
        <td><input type='text' name='date_enter' value="<?php echo stripslashes(htmlspecialchars($_POST['date_enter'])) ?>"/>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Graduate school: </td>
        <td><input type='text' name='graduate_school' value="<?php echo stripslashes(htmlspecialchars($_POST['graduate_school'])) ?>"/></td>
      </tr>
      <tr>
        <td>Homtown:</td>
        <td><input type='text' name='hometown' value="<?php echo stripslashes(htmlspecialchars($_POST['hometown'])) ?>"/></td>
      </tr>
      <tr>
        <td>Status:</td>
        <td><input type='text' name='status' value="<?php echo stripslashes(htmlspecialchars($_POST['status'])) ?>"/></td>
      </tr>
      <tr><td colspan='2'><input type='submit' name='Submit' value='Submit' /></td></tr>   
		<input type='hidden' name='action' value='admin'>
    </table></form>
  <?php
}
function admin()
{
	$name = $_REQUEST['name'];
	$gender = $_REQUEST['gender'];
	$identity_card = $_REQUEST['identity_card'];
	$email = $_REQUEST['email'];
	$mobile = $_REQUEST['mobile'];
	$im = $_REQUEST['im'];
	$tel = $_REQUEST['tel'];
	$birthday = $_REQUEST['birthday'];
	$date_enter = $_REQUEST['date_enter'];
	$graduate_school=$_REQUEST['graduate_school'];
	$hometown = $_REQUEST['hometown'];
	$status = $_REQUEST['status'];
	try
	{
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['email'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>'
			.'- please try again.');
		}
		$db_conn = db_connect();
		$query="SELECT id FROM people
  	WHERE name='$name'";
		$result = $db_conn->query($query);
		if($result->num_rows >0)
		{
			throw new Exception('The name you entered "'.$name.'" have existed,</br>'
			.'- please add a postfix and try again.');
		}
		if(isset($identity_card)&&$identity_card!=''&&strlen($identity_card)!=18 && strlen($identity_card)!=15)
		{
			throw new Exception('The identity card number must be 15 or 18 characters long,</br>'
			.'- please go back and try again.');
		}
		if(!valid_email($email))
		{
			throw new Exception("'$email' is not a valid email address,</br>"
			.'- please go back and try again.');
		}
		$query = "INSERT INTO people
         (name,gender,identity_card,email,mobile,im,tel,birthday,date_enter,graduate_school,hometown,status )
         VALUES 
         ('$name','$gender','$identity_card','$email','$mobile','$im','$tel','$birthday','$date_enter','$graduate_school','$hometown','$status')"; 
		$result = $db_conn->query($query);
		$id=$db_conn->insert_id;
		if (!$result)
		{
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		$query="UPDATE users
    SET people_id='$id'
    WHERE username='admin'";
		$result = $db_conn->query($query);
		header('Location:index.php');
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function install()
{
	$address=$_POST['address'];
	$name=$_POST['name'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	try
	{
		if (!filled_out(array($_POST['address'],$_POST['name'],$_POST['username'],$_POST['password'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>'
			.'- please try again.');
		}
		$file='./include/db_conn.php';
		$fp=fopen($file,'w');
		fwrite($fp,"<?php
  function db_connect(){
    @".'$db_conn'."= new mysqli('$address', '$username', '$password', '$name');
	if (mysqli_connect_errno()){
	  return false;
	}"
		.'$db_conn'."->query('SET NAMES utf8');
	return ".'$db_conn'.";
  }
?>");
		fclose($fp);

		@ $db_conn = new mysqli("$address", "$username", "$password");
		if (mysqli_connect_errno()) {
			throw new Exception("Can not connect the server.");
		}
		$result=$db_conn->query("SET NAMES 'utf8';");
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>SET NAMES 'utf8';</pre>.");
		}
		$result=$db_conn->query("DROP DATABASE IF EXISTS $name;");
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>DROP DATABASE IF EXISTS $name;</pre>.");
		}
		$result=$db_conn->query("CREATE DATABASE $name;");
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>CREATE DATABASE $name;</pre>.");
		}
		$result=$db_conn->query("USE $name;");
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>USE $name;</pre>.");
		}
		$sqlfile = './install/tables.sql';
		$fp = fopen($sqlfile, 'r');
		$sql = fread($fp, filesize($sqlfile));
		fclose($fp);
		$sql=split(';',$sql);
		$num_sql=count($sql);
		for($i=0;$i<$num_sql-1;$i++) {
			$result=$db_conn->query($sql[$i]);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>".$sql[$i]."</pre>.");
			}
		}
		header('location:install.php?type=admin');
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
?>
<?php
function processRequest()
{
	if($_REQUEST['type']=='')
	{
	  installForm();	
	}
	if ($_GET['type']=='admin')
	{
		adminForm();
	}
	if ($_POST['action']=='install')
	{
		install();
	}
	if ($_POST['action']=='admin')
	{
		admin();
	}
}
?>