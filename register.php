<?php
include('include/includes.php');
?>
<?php
  do_html_header('Register-Quicklab');
  do_header();
  processRequest();
  do_html_footer();
?>
<?php
function register_form()
{
	?>
	<form id="form1" name="form1" method="post" action="">
   <table class="search" style="margin-top:3px"width="100%">
      <tr>
        <td colspan="2"><h3>User Registration:</h3></td>
        </tr>
      <tr>
        <td width="10%">Username:</td>
        <td ><input type="text" size=16 name="username" /></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" size=16 name="password" />*</td>
      </tr>
      <tr>
        <td>Confirm password:</td>
        <td><input type="password" size=16 name="password2" />*</td>
      </tr>
      <tr>
        <td>Real person: </td>
        <td><?php
		$query= "SELECT * FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
		echo query_select_choose('people_id', $query,'id','name','');?>
		*&nbsp;<a href="register_people_add.php"><img src="./assets/image/general/user-add.gif" alt="Add new" border="0"/></a></td>
      </tr>
      <input type='hidden' name='type' value='result'>
      <tr>
        <td colspan="2"><input type="submit" name="Submit" value="Submit" />
     </table>
     </form>
    </table>
     <?php
}

function register_result()
{
  $username=$_POST['username'];
  $password=$_POST['password'];
  $password2=$_POST['password2'];
  $people_id=$_POST['people_id'];
  try
  {
  	if(!filled_out(array($_POST['username'],$_POST['password'],$_POST['password2'],$_POST['people_id'])))
  	{
  		throw new Exception('You have not filled the form out correctlly,</br>'
  		.'- please try again.');
  	}
  	$db_conn = db_connect();
  	$query="SELECT id FROM users
  	WHERE username='$username'";
  	$result = $db_conn->query($query);
  	if($result->num_rows >0 )
  	{
  		throw new Exception('The username you entered "'.$username.'" have been used,</br>'
  		.'- please try again.');
  	}
  	if(!valid_username($username))
  	{
  		throw new Exception("'$username' is not a valid username,</br>"
  		.'- please try again.');
  	}
  	if($password!=$password2)
  	{
  		throw new Exception('The passwords you entered do not match,</br>'
  		.'- please try again.');
  	}
  	register($username,$password,$people_id);
  	echo '<h3>Your registration was successful.</br>
  	Please contact with the administrator to check your registration.';
  }
  catch (Exception $e)
  {
    echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</td></tr></table></h3>';
  }
}

function register($username,$password,$people_id)
{
	$db_conn = db_connect();
	$query = "insert into users
            (username, password, people_id)
            values
            ('$username', sha1('$password'), '$people_id')";
  	$result = $db_conn->query($query);
	if (!$result)
    {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please go back and try again.");
    }
}

?>
<?php
function processRequest()
{
	register_form();
	if ($_REQUEST['type']=='result')
	{
		register_result();
	}
}
?>