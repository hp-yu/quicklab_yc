<?php
include('include/includes.php');
?>
<?php
  do_html_header('Users-Quicklab');
  do_header();
?>
<?php 
  //do_leftnav();
?>
<?php
  // check permission
  $userauth=check_user_authority($_COOKIE['wy_user']);
  if ($userauth!=1&&$_REQUEST['username']!=$_COOKIE['wy_user'])
  {
   echo '<table width=100% height="500" border="0" cellpadding="0" cellspacing="0">
<tr><td valign="top"><h3>You do not have the authority to do this!</h3></td></tr></table>';
   do_footer();
   do_html_footer();
   exit;
  }
?> 
<?php
function StandardForm()
{
	echo "<form id='form1' name='form1' method='post' action=''>
		  <table width='100%' class='operate'>
	      <tr><td colspan='2'><div align='center'>
          <h2>Users</h2></div></td></tr>";
	processRequest();
	echo  "</table></form>";
}
function change_password_form()
{
  ?>
      <tr>
        <td colspan="2"><h3>Change password</h3>
        </td>
        </tr>
      <tr>
        <td width="20%">Username:</td>
        <td width="80%">
        <?php
        echo $_REQUEST['username'];?>
        </td>
      </tr>
      <tr>
        <td>Old password:</td>
        <td><input type="password" name="old_password" value=""  /></td>
      </tr>
      <tr>
        <td>New password:</td>
        <td><input type="password" name="new_password" value=""  /></td>
      </tr>
      <tr>
        <td>Repeat new password:</td>
        <td><input type="password" name="new_password2" value=""  /></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="Submit" value="Submit" />
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="destination" value="
        <?php echo $_SERVER['HTTP_REFERER'];?>
        "/>
        </td>
      </tr>
    <?php
}
function change_password()
{
    $username = $_REQUEST['username'];
    $old_password = $_REQUEST['old_password'];
    $new_password = $_REQUEST['new_password'];
    $new_password2 = $_REQUEST['new_password2'];
  	try 
  	{
    if (!filled_out(array($_REQUEST['username'],$_REQUEST['old_password'],$_REQUEST['new_password'],$_REQUEST['new_password2'])))
    {
  		throw new Exception('You have not filled the form out correctlly,</br>'
  		.'- please try again.');
    }
  	$db_conn = db_connect();
  	$query="SELECT password FROM users 
  	WHERE username='$username'";
  	$result = $db_conn->query($query);
  	$user=$result->fetch_assoc();
  	if (sha1($old_password)!=$user['password'])
       throw new Exception('Old passwords entered were not correct.  Not changed.');
  	if ($new_password!=$new_password2)
       throw new Exception('New passwords entered were not the same.  Not changed.');
    
	$query = "update users
		    set username='$username',
			password=sha1('$new_password')
			where username='$username'";
				  
  	$result = $db_conn->query($query);
	if (!$result) 
	{
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
    }
	header('Location:'. $_REQUEST['destination']);
    }
    catch (Exception $e)
    {
  	  echo '</table><table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr>';
    }
}
function HiddenInputs($action)
{
	echo "<input type='hidden' name='action' value='$action' >";
}
function processRequest()
{
	change_password_form();
	if ($_REQUEST['action']=='edit')
	{
	  change_password();
	}
}
?> 
<?PHP
  StandardForm()
?>
<?php
do_footer();
do_html_footer();
?>
