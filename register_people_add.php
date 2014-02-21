<?php
include('include/includes.php');
?>
<?php
do_html_header('Register people add-Quicklab');
do_header_2();
processRequest();
do_html_footer();
?>
<script type="text/javascript">
function moveOptionToText(e1, e2) {
	for(var i=0;i<e1.options.length;i++){
		if(e1.options[i].selected) {
			var e = e1.options[i]
			e2.value=e.value;
		}
	}
}
</script>
<?php
function people_add_form()
{
?>
 <form name='add' method='post' action='' >
  <table class='standard' width="100%" style="margin-top:3px">
	<tr><td colspan='2'><h3>Your information:</h3></td>
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
        <td>Identity card id:</td>
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
      <tr><td colspan='2'><input type='submit' name='Submit' value='Submit' />
      <input type='hidden' name='action' value='add'></td>
      </tr>
    </table>
  </form>
<?php
}
function people_add()
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
		if(isset($identity_card)&& $identity_card!=""&& strlen($identity_card)!=18 && strlen($identity_card)!=15)
		{
			throw new Exception('The identity card number must be 15 or 18 characters long,</br>'
			.'- please try again.');
		}
		if(!valid_email($email))
		{
			throw new Exception("'$email' is not a valid email address,</br>"
			.'- please try again.');
		}

		$query = "INSERT INTO people
         (name,gender,identity_card,email,mobile,im,tel,birthday,date_enter,graduate_school,status )
         VALUES 
         ('$name','$gender','$identity_card','$email','$mobile','$im','$tel','$birthday','$date_enter','$graduate_school','$status')"; 
		$result = $db_conn->query($query);
		if (!$result)
		{
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		header('location:register.php');
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</td></tr></table></h3>';
	}
}

?>
<?php
function processRequest()
{
	people_add_form();
	if ($_REQUEST['action']=='add')
	{
		people_add();
	}
}
?>
