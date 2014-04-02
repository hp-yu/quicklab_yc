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
 	export_excel('accounts',$query);
 	exit;
 }
?>
<?php
  do_html_header_begin('Accounts operate-Quicklab');
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
	if(!userPermission('1'))
    {
  	  alert();
    }
	?>
<script type="text/javascript">
$(document).ready(function() {
	$("#add_form").validate({
		rules: {
			name: "required"
		},
		messages: {
			name: {required: 'required'}
		}});
});
</script>
  <form name='add_form' id="add_form" method='post' action=''>
  <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Accounts</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new accounts:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols="50",rows="3"><?php echo stripslashes($_POST['description']) ?></textarea></td>
      </tr>
      <tr>
        <td>Start date:</td>
        <td><input type='text' name='date_start' value="<?php echo stripslashes(htmlspecialchars($_POST['date_start']))?>"/>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Finish date:</td>
        <td><input type='text' name='date_finish' value="<?php echo stripslashes(htmlspecialchars($_POST['date_finish']))?>"/>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Money total:</td>
        <td><input type='text' name='money_total' value="<?php echo stripslashes(htmlspecialchars($_POST['money_total']))?>"/></td>
      </tr>
      <tr>
        <td>Money available:</td>
        <td><input type='text' name='money_available' value="<?php echo stripslashes(htmlspecialchars($_POST['money_available']))?>"/></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols="50" rows="3"><?php echo stripslashes($_POST['note']) ?></textarea></td>
      </tr>
      <tr>
        <td>State:</td>
        <td><?php
		$state=array(array("1","on"),
					array("0","off"));
		echo option_select('state',$state,2,$_POST['state']);?>
		</td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />&nbsp;&nbsp;<a href='<?php 
        echo $_SESSION['url_1'];?>'><img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
    	</td>
      </tr>
      <input type="hidden" name="action" value="add">
      </table></form>
      <?php
}

function EditForm()
{
  $account = get_record_from_id('accounts',$_REQUEST['id']);
  if(!userPermission('1',$account['created_by']))
  {
  	alert();
  }
  ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#edit_form").validate({
		rules: {
			name: "required"
		},
		messages: {
			name: {required: 'required'}
		}});
});
</script>
    <form name='edit_form' id="edit_form" method='post' action=''>
    <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Accounts</h2></div></td></tr>
  	  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($account['name']));?>">*</td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols='50' rows='3'><?php
     echo $account['description'];?></textarea></td>
      </tr>
      <tr>
        <td>Start date:</td>
        <td><input type='text' name='date_start' value="<?php
     echo $account['date_start'];?>"></td>
      </tr>
      <tr>
        <td>Finish date:</td>
        <td><input type='text' name='date_finish' value='<?php
     echo $account['date_finish'];?>'></td>
      </tr>
      <tr>
        <td>Money total:</td>
        <td><input type='text' name='money_total' value='<?php
     echo $account['money_total'];?>'></td>
      </tr>
      <tr>
        <td>Money available:</td>
        <td><input type='text' name='money_available' value='<?php
     echo $account['money_available'];?>'></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols='50' rows='3'><?php
     echo $account['note'];?></textarea></td>
      </tr>
      <tr>
        <td>State:</td>
        <td><?php
        $state=array(array(1,'on'),
        			 array(0,'off'));
        echo option_select('state',$state,2,$account['state']);
        ?>
        </td>
      </tr>  
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <input type="hidden" name="action" value="edit"/>
    </table> </form>
  <?php
}

function Detail()
{
	if(!userPermission('1'))
    {
  	  alert();
    }
  $account = get_record_from_id('accounts',$_REQUEST['id']);
?>
<form name='detail_form' id="detail_form" method='post' action=''>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Accounts</h2></div></td></tr>
      <tr><td colspan='2'><h3>Details:&nbsp;
    <a href="accounts_operate.php?type=edit&id=<?php echo $account['id']?>"/>
    <img src='./assets/image/general/edit.gif' alt='edit' border='0'/></a></h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $account['name'];?></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols='50' rows='3'><?php
     echo $account['description'];?></textarea></td>
      </tr>
      <tr>
        <td>Start date:</td>
        <td><?php echo $account['date_start'];?></td>
      </tr>
      <tr>
        <td>Finish date:</td>
        <td><?php echo $account['date_finish'];?></td>
      </tr>
      <tr>
        <td>Money total:</td>
        <td><?php echo $account['money_total'];?></td>
      </tr>
      <tr>
        <td>Money available:</td>
        <td><?php echo $account['money_available'];?></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols='50' rows='3'><?php
     echo $account['note'];?></textarea></td>
      </tr>
	  <tr>
	    <td>State:</td>
	    <td><?php
	      $state = array( array( '1', 'On'),
		                  array( '0', 'Off'));
	 	  for ($i=0; $i < 2; $i++) {
            if ($state[$i][0] == $account['state']) 
          	{
              echo $state[$i][1];
            }
          }?>
	    </td>
	  </tr>
      <tr>
        <td colspan='2'><a href='<?php echo $_SESSION['url_1'];?>'><img 
	 src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
        </td>
      </tr>
    </table>
<?php
}
function DeleteForm()
{
  $account = get_record_from_id('accounts',$_REQUEST['id']);
  if(!userPermission('1'))
  {
  	alert();
  }
  if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
  {
	$module = get_id_from_name('modules','accounts');
	$db_conn=db_connect();
	$query = "SELECT *
    	FROM items_relation WHERE item_from='".$module['id']."_".$_REQUEST['id'].
        "' OR item_to='".$module['id']."_".$_REQUEST['id']."'";
  	$relateditem = $db_conn->query($query);
  	$relateditem_count=$relateditem->num_rows; 	
  	
	$query = "SELECT id
    	FROM storages WHERE module_id='{$module['id']}'
        AND item_id ='{$_REQUEST['id']}'";
  	$storage = $db_conn->query($query);
  	$storage_count=$storage->num_rows;
  	
  	$query = "SELECT id
    	FROM orders WHERE module_id='{$module['id']}'
        AND item_id ='{$_REQUEST['id']}'";
  	$order = $db_conn->query($query);
  	$order_count=$order->num_rows;
  	
	if($relateditem_count==0&&$storage_count==0&&$order_count==0)
	{
	echo "<form name='delete' method='post' action=''>";
	echo "<table width='100%' class='operate' >
	<tr><td colspan='2'><div align='center'><h2>Accounts</h2></div></td></tr>";
    echo "<tr><td colspan='2'><h3>Are you sure to delete the account: ";
    echo $account['name'];
	echo "?</h3></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />";
    HiddenInputs('','',"delete");
    echo "&nbsp;<a href='".$_SESSION['url_1']."'><img 
      src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
    echo "</table></form>";
	}
	else 
	{
		echo "<table width='100%' class='operate' >
	<tr><td colspan='2'><div align='center'><h2>Accounts</h2></div></td></tr>";
		echo "<tr><td><h3>This account related to ";
		if($relateditem_count!=0)
		{
			echo "<br>".$relateditem_count." other items, ";
		}
		if($storage_count!=0)
		{
			echo "<br>".$storage_count." storages, ";
		}
		if($order_count!=0)
		{
			echo "<br>".$order_count." orders, ";
		}
		echo "<br>do not suggest to delete!</h3></td>
      </tr>
      <tr><td>
      <a href='". $_SESSION['url_1']."'><img 
      src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
		echo "</table>";
	}
	
  }
  elseif($_SESSION['selecteditemDel'])//multiple delete
  {
		$num_selecteditemDel=count($_SESSION['selecteditemDel']);
	echo "<form name='edit' method='post' action=''>";
	echo "<table width='100%' class='operate' >
	<tr><td colspan='2'><div align='center'><h2>Accounts</h2></div></td></tr>";
    echo "<tr><td colspan='2'><h3>Are you sure to delete the $num_selecteditemDel account(s)?<br>
    account related to other items, storages, orders <br>can not be deleted.</h3></td></tr> ";
	echo "<tr><td colspan='2'><input type='submit' name='Submit' value='Submit' />";
    HiddenInputs('','',"delete");
    echo "&nbsp;<a href='".$_SESSION['url_1']."'><img 
      src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
	echo "</table></form>";
  }
}
function Add()
{
  try {
  if (!filled_out(array($_REQUEST['name']))) 
  {
  	throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
  }
    $name = $_REQUEST['name'];
    $description = $_REQUEST['description'];
    $date_start = $_REQUEST['date_start'];
    $date_finish = $_REQUEST['date_finish'];
    $money_total = $_REQUEST['money_total'];
    $money_available = $_REQUEST['money_available'];
    $note=$_REQUEST['note'];
    $state=$_REQUEST['state'];
    
	$db_conn = db_connect();
	$query = "insert into accounts 
      (name,description,date_start,date_finish,money_total,money_available,note,state)
       VALUES 
      ('$name','$description','$date_start','$date_finish','$money_total','$money_available','$note','$state')"; 
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	if (!$result) 
    {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
     }
	header('Location: accounts.php?id='.$id);
  }
  catch (Exception $e)
  {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}
function Edit()
{
  try {
  if (!filled_out(array($_REQUEST['name']))) 
  {
  	throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
  }
    $id=$_REQUEST['id'];
    $name = $_REQUEST['name'];
    $description = $_REQUEST['description'];
    $date_start = $_REQUEST['date_start'];
    $date_finish = $_REQUEST['date_finish'];
    $money_total = $_REQUEST['money_total'];
    $money_available = $_REQUEST['money_available'];
    $note=$_REQUEST['note'];
    $state=$_REQUEST['state'];

	$db_conn = db_connect();
	$query = "update accounts 
		    set name='$name',
		    description='$description',
			date_start='$date_start',
			date_finish='$date_finish',
			money_total='$money_total',
			money_available='$money_available',
			note='$note',
			state='$state'
			where id='$id'";
				  
  	$result = $db_conn->query($query);
	if (!$result) 
      {
        throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
       }
	header("Location:".$_SESSION['url_1']);
  }
    catch (Exception $e)
  {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
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
	if ($type == "relation") 
		{
			EditRelationForm();
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
	if ($action == "editrelation") 
		{
			EditRelation();
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
    $state_array = array( array( '0', 'off'),
		                   array( '1', 'on'));
	for ($i=0; $i < 2; $i++) 
    {
      if ($state_array[$i][0] == $row['state']) 
      {
        $state= $state_array[$i][1];
      }
    }
    $xls[]= ereg_replace("[\r,\n,\t]"," ",$row['id'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['description'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_start'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_finish'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['money_total'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['money_available'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['note'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$state);
  }
  $title="id"."\t".
  "name"."\t".
  "description"."\t".
  "date_start"."\t".
  "date_finish"."\t".
  "money_total"."\t".
  "money_available"."\t".
  "note"."\t".
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