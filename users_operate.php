<?php
include('include/includes.php');
?>
<?php
 if ($_REQUEST['type']=='export_excel')
 {
 	if(!userPermission(2))
 	{
 	  header('location:'.$_SESSION['url_1']);
 	}
 	$query=$_SESSION['query'];
 	unset($_SESSION['query']);
 	export_excel('users',$query);
 	exit;
 }
?>
<?php
  do_html_header('Users-Quicklab');
  do_header();
  do_leftnav();
  StandardForm();
  do_rightbar();
  do_footer();
  do_html_footer();
?>

<?php
function StandardForm()
{
?>
	<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Users</h2></div></td></tr>
<?php
	processRequest();
}
function addform()
{
  if(!userPermission('2'))
  {
  	alert();
  }
  ?>
  <form name='add' method='post' action=''>
      <tr>
        <td colspan="2"><h3>Add a new user: </h3></td>
        </tr>
      <tr>
        <td width="20%">Username:</td>
        <td width="80%"><input type="text" name="username" value="<?php echo stripslashes(htmlspecialchars($_POST['username']))?>"/>*[a-z,A-Z,0-9,_]</td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="password" />*</td>
      </tr>
      <tr>
        <td>Real person: </td>
        <td><?php
		$query= "select * from people";
		echo query_select_choose('people_id', $query,'id','name',$_POST['people_id']);?>*</td>
      </tr>
      <tr>
        <td>Authority:</td>
        <td><?php
        $authority= array( array( '2', 'Administrator'),
					  array( '3', 'Staff'),
		              array( '4', 'User'));
		echo option_select_choose('authority', $authority,3,$_POST['authority']);?>*</td>
      </tr>
      <tr>
        <td>Valid:</td>
        <td><?php
        $valid= array( array( '1', 'yes'),
      				  array( '0', 'no'));
		echo option_select('valid',$valid,2,$_POST['valid']);?>*</td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="Submit" value="Submit" />
        <a href='<?php echo $_SESSION['url_1'];?>'>
        <img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
      </tr>
    <?php HiddenInputs("add");?>
    </form></table>
  <?php
}
function editform()
{
  if(!userPermission('2'))
  {
  	alert();
  }
  $user = get_record_from_id('users',$_REQUEST['id']);
  ?>
<script type="text/javascript" src="include/js/moveoptions.js"></script>
    <form name='edit' method='post' action=''>
      <tr>
        <td colspan="2"><h3>Edit:</h3></td>
        </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php
  echo stripslashes(htmlspecialchars($user['username']));?></td>
      </tr>
      <tr>
        <td width="20%">Real person: </td>
        <td width="80%"><?php
		$query= "select * from people";
		echo query_select_choose('people_id', $query,'id','name',$user['people_id']);?>*</td>
      </tr>
      <tr>
        <td>Authority:</td>
        <td>
        <?php
        $authority= array( array( '2', 'Administrator'),
					  array( '3', 'Staff'),
		              array( '4', 'User'));
		echo option_select('authority', $authority,3,$user['authority']);
        ?>*</td>
      </tr>
      <tr>
        <td>Valid:</td>
        <td><?php
        $valid= array( array( '1', 'yes'),
      				  array( '0', 'no'));
		echo option_select('valid',$valid,2,$user['valid']);?>*</td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="Submit" value="Submit" onmouseover="select_all_options('selected_roles[]')"/>
        <a href='<?php echo $_SESSION['url_1'];?>'>
        <img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
        <input type="hidden" name="oldusername" value='<?php echo $user['username'];?>'></td></tr>
    <?php HiddenInputs("edit");?>
    </form></table>
  <?php
}
function DeleteForm()
{
  if(!userPermission('2'))
  {
  	alert();
  }
    echo  "<form name='delete' method='post' action=''>";
    echo "<tr><td colspan='2'><h3>Are you sure to delete the user: ";
	$user = get_record_from_id('users',$_REQUEST['id']);
    echo $user['username'];
	echo "?</h3></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />";
    echo "&nbsp;<a href=".$_SESSION['url_1']."><img src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
    HiddenInputs("delete");
    echo "</form></table>";
}
function add()
{
	$username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $people_id = $_REQUEST['people_id'];
    $authority = $_REQUEST['authority'];
    $valid = $_REQUEST['valid'];
    try
    {
    if (!filled_out(array($_REQUEST['username'],$_REQUEST['password'],$_REQUEST['people_id'],$_REQUEST['authority'],$_REQUEST['valid'])))
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
	$query = "insert into users
            (username, password, people_id, authority,valid)
          values
            ('$username', sha1('$password'), '$people_id', '$authority','$valid')";
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	if (!$result)
    {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
    }
	  header('Location: users.php?id='.$id);
    }
    catch (Exception $e)
    {
  	  echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
    }
}
function Edit() {
	$id=$_REQUEST['id'];
	$people_id = $_REQUEST['people_id'];
	$authority = $_REQUEST['authority'];
	$valid = $_REQUEST['valid'];
	try {
		if (!filled_out(array($_REQUEST['people_id'],$_REQUEST['authority'],$_REQUEST['valid']))) {
			throw new Exception('You have not filled the form out correctlly,</br>'
			.'- please try again.');
		}
		$db_conn=db_connect();
		$query = "update users
		    set people_id='$people_id',
			authority='$authority',
			valid='$valid'
			where id='$id'";

		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		header("Location:".$_REQUEST['destination']);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function Delete()
{
  $db_conn=db_connect();
  $query = "delete from users where id = '{$_REQUEST['id']}'";
  $result = $db_conn->query($query);
  header('Location:  '.$_REQUEST['destination']);
}
function HiddenInputs($action)
{
	echo "<input type='hidden' name='action' value='$action' >";
	if($_REQUEST['destination'])
	{
		echo "<input type='hidden' name='destination' value= '";
		echo $_REQUEST['destination']."'>";
	}
	else
	{
    	echo "<input type='hidden' name='destination' value= '";
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
    $valid_array = array( array( '1', 'yes'),
		                   array( '0', 'no'));
	for ($i=0; $i < 2; $i++)
    {
      if ($valid_array[$i][0] == $row['valid'])
      {
        $valid= $valid_array[$i][1];
      }
    }
    $xls[]= ereg_replace("[\r,\n,\t]"," ",$row['id'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['username'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['description'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_start'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_finish'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['note'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$state);
  }
  $title="id"."\t".
  "name"."\t".
  "description"."\t".
  "date_start"."\t".
  "date_finish"."\t".
  "note"."\t".
  "state";

  $xls = implode("\r\n", $xls);

  $people_id=get_pid_from_username($_COOKIE['wy_user']
   );
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

