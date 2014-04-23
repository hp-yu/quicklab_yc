<?php
include('include/includes.php');
?>
<?php
 if ($_REQUEST['type']=='export_excel')
 {
 	if(!userPermission(3))
 	{
 	  header('location:'.$_SESSION['url_1']);
 	}
 	$query=$_SESSION['query'];
 	export_excel('people',$query);
 	exit;
 }
?>
<?php
  do_html_header_begin('People operate-Quicklab');
?>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<?php
  do_html_header_end();
  do_header();
  //do_leftnav();
  processRequest();
  do_rightbar();
  do_footer();
  do_html_footer();
?>
<?php

function AddForm()
{
	if(!userPermission('3'))
    {
  	  alert();
    }
    ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#add_form").validate({
		rules: {
			name: "required",
			email: "required"
		},
		messages: {
			name: {required: 'required'},
			email: {required: 'required'}
		}});
});
</script>
    <form name='add_form' id="add_form" method='post' action='' enctype='multipart/form-data'>
    <table width='100%'  class='operate'>
	      <tr><td colspan='2'><div align='center'>
          <h2>People</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new people:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" value="<?php echo stripslashes(htmlspecialchars($_POST['name'])) ?>"/>*</td>
      </tr>
      <tr>
        <td >Photo:<br>(JPEG format)</td>
        <td><input type='file' name='photo' size="30"/></td>
      </tr>
      <tr>
        <td >Signature:<br>(JPEG format)</td>
        <td><input type='file' name='signature' size="30"/></td>
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
        <td><input type='text' name='email' id="email" size="30" value="<?php echo stripslashes(htmlspecialchars($_POST['email'])) ?>"/>*</td>
      </tr>
      <tr>
        <td>Mobile:</td>
        <td><input type='text' name='mobile' value="<?php echo stripslashes(htmlspecialchars($_POST['mobile'])) ?>"/></td>
      </tr>
      <tr>
        <td>MSN:</td>
        <td><input type='text' name='im' size="30" value="<?php echo stripslashes(htmlspecialchars($_POST['im'])) ?>"/></td>
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
        <td><input type='text' name='graduate_school' size="30" value="<?php echo stripslashes(htmlspecialchars($_POST['graduate_school'])) ?>"/></td>
      </tr>
      <tr>
        <td>Homtown:</td>
        <td><input type='text' name='hometown' size="30" value="<?php echo stripslashes(htmlspecialchars($_POST['hometown'])) ?>"/></td>
      </tr>
      <tr>
        <td>Status:</td>
        <td><input type='text' name='status' id="status" value="<?php echo stripslashes(htmlspecialchars($_POST['status'])) ?>"/></td>
      </tr>
      <tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td></tr>
    <?php HiddenInputs("add");?>
    </table></form>
  <?php
}
function EditForm()
{
  if(!userPermission('2',$_REQUEST['id']))
  {
  	alert();
  }
  $people = get_record_from_id('people',$_REQUEST['id']);
  ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#edit_form").validate({
		rules: {
			name: "required",
			email: "required"
		},
		messages: {
			name: {required: 'required'},
			email: {required: 'required'}
		}});
});
</script>
  <form name='edit_form' id="edit_form" method='post' action='' enctype='multipart/form-data'>
  <table width='100%'  class='operate'>
	      <tr><td colspan='2'><div align='center'>
          <h2>People</h2></div></td></tr>
  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" value='<?php echo $people['name'];?>'>*</td>
      </tr>
      <tr>
        <td >Photo:</br>(JPEG format)
        <?php if ($people['photo']!=NULL)
        {echo '</br>
        <input type="checkbox" value="1" name="delete_image">Delte this image?';
        echo '</td><td height="155">';
      	echo '<img src="resize_image.php?image=';
      	echo urlencode($people['photo']);
      	echo '&max_width=150&max_height=150"  align = left/>';
      	echo '</td></tr><tr><td >Repalce!';}
    	?>
        </td>
        <td><input type='file' name='photo' size="30"/></td>
      </tr>
       <tr>
        <td >Signature:</br>(JPEG format)
        <?php if ($people['signature']!=NULL)
        {echo '</br>
        <input type="checkbox" value="1" name="delete_image_signature">Delte this signature?';
        echo '</td><td height="55">';
      	echo '<img src="resize_image.php?image=';
      	echo urlencode($people['signature']);
      	echo '&max_width=100"  align = left/>';
      	echo '</td></tr><tr><td >Repalce!';}
    	?>
        </td>
        <td><input type='file' name='signature' size="30"/></td>
      </tr>
      <tr>
        <td >Gender:</td>
        <td ><?php
        $gender=array('female'=>'0','male'=>'1');
        echo array_select('gender',$gender,$people['gender']);?></td>
      </tr>
      <tr>
        <td >Identity card number:</td>
        <td ><input type='text' name='identity_card' size="30" value='<?php echo $people['identity_card'];?>'></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input type='text' name='email' id="email" size="30" value='<?php echo $people['email'];?>'>*</td>
      </tr>
      <tr>
        <td>Mobile:</td>
        <td><input type='text' name='mobile' value='<?php echo $people['mobile'];?>'></td>
      </tr>
<!--      <tr>
        <td>MSN:</td>
        <td><input type='text' name='im' size="30" value='<?php echo $people['im'];?>'></td>
      </tr>-->
      <tr>
        <td>Telephone:</td>
        <td><input type='text' name='tel' value='<?php echo $people['tel'];?>'></td>
      </tr>
      <tr>
        <td>Birthday:</td>
        <td><input type='text' name='birthday' value='<?php echo $people['birthday'];?>'>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Enter date:</td>
        <td><input type='text' name='date_enter' value='<?php echo $people['date_enter'];?>'>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Leave date:</td>
        <td><input type='text' name='date_leave' value='<?php echo $people['date_leave'];?>'>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Graduate school: </td>
        <td><input type='text' name='graduate_school' size="30" value='<?php echo $people['graduate_school'];?>'></td>
      </tr>
      <tr>
        <td>Homtown:</td>
        <td><input type='text' name='hometown' size="30" value='<?php echo $people['hometown'];?>'></td>
      </tr>
      <tr>
        <td>Status:</td>
        <td><input type='text' name='status' id="status" value='<?php echo $people['status'];?>'></td>
      </tr>
      <tr>
        <td >State:</td>
        <td ><?php
        $state=array('In lab'=>'0','Leave lab'=>'1');
        echo array_select('state',$state,$people['state']);?></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />
    	<input type="hidden" name="oldname" value='<?php echo $people['name'];?>'></td>	
    	</tr>
   
	<?php HiddenInputs("edit");?>
	</table></from>
  <?php
}
function Detail()
{
	if(!userPermission('4'))
    {
  	  echo '</table><table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
      do_footer();
      do_html_footer();
  	  exit;
    }
    $people = get_record_from_id('people',$_REQUEST['id']);
	?>
<form name='detail_form' id="detail_form" method='post' action=''>
	<table width='100%'  class='operate'>
	      <tr><td colspan='2'><div align='center'>
          <h2>People</h2></div></td></tr>
  <tr><td colspan='2'><h3>Details:&nbsp;
    <a href="people_operate.php?type=edit&id=<?php echo $people['id']?>"/>
    <img src='./assets/image/general/edit.gif' alt='edit' title='edit' border='0'/></a></h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $people['name'];?></td>
      </tr>
      <?php if ($people['photo'])
        {
        echo '<tr><td>Photo:';
        echo '</td><td height="155">';
      	echo '<img src="resize_image.php?image=';
      	echo urlencode($people['photo']);
      	echo '&max_width=150&max_height=150"  align = left/>';
      	echo "</td></tr><tr>";}?>

      <?php if ($people['signature'])
        {
        echo '<tr><td>Signature:';
        echo '</td><td height="55">';
      	echo '<img src="resize_image.php?image=';
      	echo urlencode($people['signature']);
      	echo '&max_width=100"  align = left/>';
      	echo "</td></tr><tr>";}?>

      <tr>
        <td >Gender:</td>
        <td ><?php
     $gender = array('female'=>'0','male'=>'1');
	 foreach ($gender as $key=>$value) {
       if ($value == $people['gender']) {
         echo $key;
         }
     }?></td>
      </tr>
      <tr>
        <td >Identity card number:</td>
        <td ><?php echo $people['identity_card'];?></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><?php echo $people['email'];?></td>
      </tr>
      <tr>
        <td>Mobile:</td>
        <td><?php echo $people['mobile'];?></td>
      </tr>
      <tr>
        <td>MSN:</td>
        <td><?php echo $people['im'];?></td>
      </tr>
      <tr>
        <td>Telephone:</td>
        <td><?php echo $people['tel'];?></td>
      </tr>
      <tr>
        <td>Birthday:</td>
        <td><?php echo $people['birthday'];?></td>
      </tr>
      <tr>
        <td>Enter date:</td>
        <td><?php echo $people['date_enter'];?></td>
      </tr>
      <tr>
        <td>Leave date:</td>
        <td><?php echo $people['date_leave'];?></td>
      </tr>
      <tr>
        <td>Graduate school: </td>
        <td><?php echo $people['graduate_school'];?></td>
      </tr>
      <tr>
        <td>Homtown:</td>
        <td><?php echo $people['hometown'];?></td>
      </tr>
      <tr>
        <td>Status:</td>
        <td><?php echo $people['status'];?></td>
      </tr>
      <tr><td colspan='2'>
      <a href='<?php echo $_SESSION['url_1'];?>'>
      <img src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a></td>
      </tr></table>
<?php
}
function DeleteForm()
{
	if(!userPermission('2'))
    {
  	  alert();
    }
    echo "<form name='delete' method='post' action=''>";
    echo "<table width='100%'  class='operate'>
	      <tr><td colspan='2'><div align='center'>
          <h2>People</h2></div></td></tr>";
    echo "<tr><td colspan='2'><h3>Are you sure to delete the people: ";
	$people = get_record_from_id('people',$_REQUEST['id']);
    echo $people['name'];
	echo "?</h3></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />";
    echo "&nbsp;<a href=".$_SESSION['url_1']."><img src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a></td></tr>";
    HiddenInputs("delete");
    echo "</form></table>";
}
function Add() {
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
		if(strlen($identity_card)>0 and strlen($identity_card)!=18 and strlen($identity_card)!=15)
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

		if ( (isset($_FILES['photo']['name']) && is_uploaded_file($_FILES['photo']['tmp_name']))) {
			$type = basename($_FILES['photo']['type']);
			switch ($type) {
				case 'jpeg':
				case 'pjpeg':   $filename = "../quicklab_data/people/photo_$id.jpg";
				move_uploaded_file($_FILES['photo']['tmp_name'],$filename);
				$query = "update people
                                  set photo = '$filename'
                                  where id = $id";
				$result = $db_conn->query($query);
				break;
				default:        throw new Exception('Invalid picture format: '.
				$_FILES['photo']['type']) ;
			}
		}

		if ( (isset($_FILES['signature']['name']) && is_uploaded_file($_FILES['signature']['tmp_name']))) {
			$type = basename($_FILES['signature']['type']);
			switch ($type) {
				case 'jpeg':
				case 'pjpeg':   $filename = "../quicklab_data/people/signature_$id.jpg";
				move_uploaded_file($_FILES['signature']['tmp_name'],$filename);
				$query = "update people
                                  set signature = '$filename'
                                  where id = $id";
				$result = $db_conn->query($query);
				break;
				default:        throw new Exception('Invalid picture format: '.
				$_FILES['signature']['type']) ;
			}
		}

		header('Location: people.php?id='.$id);
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function Edit() {
	$id=$_REQUEST['id'];
	$name = $_REQUEST['name'];
	$gender = $_REQUEST['gender'];
	$identity_card = $_REQUEST['identity_card'];
	$email = $_REQUEST['email'];
	$mobile = $_REQUEST['mobile'];
	$im = $_REQUEST['im'];
	$tel = $_REQUEST['tel'];
	$birthday = $_REQUEST['birthday'];
	$date_enter = $_REQUEST['date_enter'];
	$date_leave = $_REQUEST['date_leave'];
	$graduate_school=$_REQUEST['graduate_school'];
	$hometown = $_REQUEST['hometown'];
	$status = $_REQUEST['status'];
	$state = $_REQUEST['state'];
	try {
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['email'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>'
			.'- please try again.');
		}
		$db_conn = db_connect();
		$query="SELECT id FROM people
  	WHERE name='$name'";
		$result = $db_conn->query($query);
		if($result->num_rows >0&& $name!=$_REQUEST['oldname'])
		{
			throw new Exception('The name you entered "'.$name.'" have existed,</br>'
			.'- please add a postfix and try again.');
		}
		if(strlen($identity_card)>0 && strlen($identity_card)!=18 && strlen($identity_card)!=15)
		{
			throw new Exception('The identity card number must be 15 or 18 characters long,</br>'
			.'- please go back and try again.');
		}
		if(!valid_email($email))
		{
			throw new Exception("'$email' is not a valid email address,</br>"
			.'- please go back and try again.');
		}

		$query = "update people
		    set name='$name',
		    gender='$gender',
		    identity_card='$identity_card',
			email='$email',
			mobile='$mobile',
			im='$im',
			tel='$tel',
			birthday='$birthday',
			date_enter='$date_enter',
			date_leave='$date_leave',
			graduate_school='$graduate_school',
			hometown='$hometown',
			status='$status',
			state='$state'
			where id='$id'";

		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		if ( (isset($_FILES['photo']['name']) && is_uploaded_file($_FILES['photo']['tmp_name']))) {
			$type = basename($_FILES['photo']['type']);
			switch ($type) {
				case 'jpeg':
				case 'pjpeg':   $filename = "../quicklab_data/people/photo_$id.jpg";
				move_uploaded_file($_FILES['photo']['tmp_name'],$filename);
				$query = "update people
                                  set photo = '$filename'
                                  where id = '$id'";
				$result = $db_conn->query($query);
				break;
				default:        throw new Exception('Invalid picture format: '.
				$_FILES['photo']['type']) ;
			}
		}
		else {
			if($_REQUEST['delete_image']=='1') {
				$query = "update people
                  set photo = NULL
                  where id = '$id'";
				$result = $db_conn->query($query);
				if (!$result) {
					throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
				}
			}
		}

		if ( (isset($_FILES['signature']['name']) && is_uploaded_file($_FILES['signature']['tmp_name']))) {
			$type = basename($_FILES['signature']['type']);
			switch ($type) {
				case 'jpeg':
				case 'pjpeg':   $filename = "../quicklab_data/people/signature_$id.jpg";
				move_uploaded_file($_FILES['signature']['tmp_name'],$filename);
				$query = "update people
                                  set signature = '$filename'
                                  where id = '$id'";
				$result = $db_conn->query($query);
				break;
				default:        throw new Exception('Invalid picture format: '.
				$_FILES['signature']['type']) ;
			}
		}
		else {
			if($_REQUEST['delete_image_signature']=='1') {
				$query = "update people
                  set signature = NULL
                  where id = '$id'";
				$result = $db_conn->query($query);
				if (!$result) {
					throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
				}
			}
		}

		header("Location:".$_SESSION['url_1']);
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function Delete()
{
  $db_conn=db_connect();
  $query = "delete from people where id = '{$_REQUEST['id']}'";
  $result = $db_conn->query($query);
  header('Location: '.$_REQUEST['url_1']);
}
function HiddenInputs($action)
{
	echo "<input type='hidden' name='action' value='$action' >";
	if($_REQUEST['destination'])
	{
		echo "<input type='hidden' name='destination' value='";
		echo $_REQUEST['destination']."'>";
	}
	else
	{
    	echo "<input type='hidden' name='destination' value='";
    	echo $_SERVER['HTTP_REFERER']."'>";
	}
}
function processRequest()
{
	$type = $_REQUEST['type'];
	if ($type == "add")
		{
			AddForm();
		}
	if ($type == "detail")
		{
			Detail();
		}
	if ($type == "edit")
		{
			EditForm();
		}
	if ($type == "delete")
		{
			DeleteForm();
		}
	$action = $_POST['action'];
	if ($action == "add")
		{
			Add();
		}
	if ($action == "detail")
		{
			Detail();
		}
	if ($action == "edit")
		{
			Edit();
		}
	if ($action == "delete")
		{
			Delete();
		}
}
function export_excel($module_name,$query)
{
  $db_conn=db_connect();
  $results = $db_conn->query($query);
  $num_rows=$results->num_rows;
  while($row = $results->fetch_array())
  {
    $gender = array('female'=>'0','male'=>'1');
    foreach ($gender as $key=>$value) {
      if ($value == $row['gender']) {
        $gender= $key;
      }
    }
	$state = array('in lab'=>'0','leave lab'=>'1');
    foreach ($state as $key=>$value) {
      if ($value == $row['state']) {
        $state= $key;
      }
    }
    $xls[]= $row['id']."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$gender)."\t".
    ereg_replace("[\r,\n,\t]"," ","'".$row['identity_card'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['email'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['im'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['mobile'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['tel'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['graduate_school'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['hometown'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['birthday'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_enter'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_leave'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['status'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$state);
  }
  $title="id"."\t".
  "name"."\t".
  "gender"."\t".
  "identity_card"."\t".
  "email"."\t".
  "msn"."\t".
  "mobile"."\t".
  "tel"."\t".
  "graduate_school"."\t".
  "hometown"."\t".
  "birthday"."\t".
  "date_enter"."\t".
  "date_leave"."\t".
  "status"."\t".
  "state";

  $xls = implode("\r\n", $xls);

  $people_id=get_pid_from_username($_COOKIE['wy_user'] );
  $exportor = get_name_from_id('people',$people_id);

  $fileName ='Export-'.$module_name.'-'.date('Ymd').'.xls';
  header("Content-type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=$fileName");

  echo "Export from database: ".mb_convert_encoding($module_name,"gb2312","utf-8")."\n";
  echo "Expoet date: ".date('m/d/Y')."\n";
  echo "Export by: ".mb_convert_encoding($exportor['name'],"gb2312","utf-8")."\n";
  echo "Totally ".$num_rows." records."."\n\n";
  echo mb_convert_encoding($title,"gb2312","utf-8")."\n";
  echo mb_convert_encoding($xls,"gb2312","utf-8");
}
?>

