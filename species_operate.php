<?php
include('include/includes.php');
?>
<?php
 if ($_REQUEST['type']=='export_excel')
 {
 	if(!userPermission(1))
 	{
 	  header('location:'.$_SESSION['url_1']);
 	}
 	$query=$_SESSION['query'];
 	unset($_SESSION['query']);
 	export_excel('species',$query);
 	exit;
 }
?>
<?php
  do_html_header_begin('Species operate-Quicklab');
?>
<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
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

function addform()
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
			latin: "required"
		},
		messages: {
			name: {required: 'required'},
			latin: {required: 'required'}
		}});
});
</script>
  <form name='add_form' id="add_form" method='post' action=''>
  <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Species</h2></div></td></tr>
      <tr>
        <td colspan="2"><h3>Add a species: </h3></td>
        </tr>
      <tr>
        <td width="20%">Common name:</td>
        <td width="80%"><input type="text" name="name" id="name" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
      </tr>
      <tr>
        <td>Scientific name:</td>
        <td><input type="text" name="latin" id="latin" value="<?php echo stripslashes(htmlspecialchars($_POST['latin']))?>"/>*</td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="Submit" value="Submit" />
        <a href='<?php echo $_SESSION['url_1'];?>'>
        <img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
      </tr>
    <?php HiddenInputs("add");?>
    </table></form>
  <?php
}
function editform()
{
  if(!userPermission('3'))
  {
  	alert();
  }
  $species = get_record_from_id('species',$_REQUEST['id']);
  ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#edit_form").validate({
		rules: {
			name: "required",
			latin: "required"
		},
		messages: {
			name: {required: 'required'},
			latin: {required: 'required'}
		}});
});
</script>
    <form name='edit_form' id="edit_form" method='post' action=''>
    <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Species</h2></div></td></tr>
      <tr>
        <td colspan="2"><h3>Edit:</h3></td>
        </tr>
      <tr>
        <td width="20%">Common name: </td>
        <td width="80%"><input type='text' name='name' id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($species['name']));?>">*</td>
      </tr>
      <tr>
        <td width="20%">Scientific name: </td>
        <td width="80%"><input type='text' name='latin' id="latin" size="40" value="<?php
  echo stripslashes(htmlspecialchars($species['latin']));?>">*</td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="Submit" value="Submit" />
        <a href='<?php echo $_SESSION['url_1'];?>'>
        <img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
        <input type="hidden" name="oldusername" value='<?php echo $user['username'];?>'></td></tr>
    <?php HiddenInputs("edit");?>
    </table></form>
  <?php
}
function add()
{
	$name = $_REQUEST['name'];
    $latin = $_REQUEST['latin'];
    try 
    {
    if (!filled_out(array($_REQUEST['name']))) 
    {
  		throw new Exception('You have not filled the form out correctlly,</br>'
  		.'- please try again.');
    }
  	$db_conn = db_connect();
	$query = "insert into species 
            (name,latin)
          values 
            ('$name','$latin')"; 
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	if (!$result) 
    {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
    }
	  header('Location: species.php?id='.$id);
    }
    catch (Exception $e)
    {
  	  echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
    }
}
function Edit()
{
	$id=$_REQUEST['id'];
    $name = $_REQUEST['name'];
  	$latin = $_REQUEST['latin'];
  	try 
  	{
    if (!filled_out(array($_REQUEST['name']))) 
    {
  		throw new Exception('You have not filled the form out correctlly,</br>'
  		.'- please try again.');
    }
    $db_conn=db_connect();
	$query = "update species
		    set name='$name',
			latin='$latin'
			where id='$id'";
				  
  	$result = $db_conn->query($query);
	if (!$result) 
	{
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
    }
	header("Location:".$_REQUEST['destination']);
    }
    catch (Exception $e)
    {
  	  echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
    }
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
  while($row = $results->fetch_array()) {    
    $xls[]= $row['id']."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['latin']);
  }
  $title="id"."\t".
  "name"."\t".
  "latin";

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

