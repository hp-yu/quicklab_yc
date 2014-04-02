<?php
include('include/includes.php');
if (!check_auth_user()) {
	login();
}
if ($_REQUEST['type']=='export_excel') {
	$query=$_SESSION['query'];
	export_excel('plasmid_bank',$query);
	exit;
}
do_html_header('Plasmid bank operate-Quicklab');
?>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<?php
standard_form();
//do_rightbar();
do_html_footer();
?>
<?php
function standard_form() {
	process_request();
}

function add_form() {
	if(!userPermission('2')) {
		alert();
	}
	?>
<script type="text/javascript">
$.validator.setDefaults({	submitHandler: function() {
	document.add_form.action.value = "add";
	document.add_form.submit();
	//window.returnValue='ok';
	//window.close   ();
}});
$(document).ready(function() {
	$("#add_form").validate({
		rules: {
			volume: {required:true,min:0}
		},
		messages: {
			volume: {required: 'required'}
		}});
});
function closeSubmit() {
	window.close   ();
}
</script>
	<?php
	$plasmid=get_record_from_id("plasmids",$_REQUEST['plasmid_id']);
	?>
<form name="add_form" id="add_form" method="POST" target="">
<table width='100%'>
<tr><td colspan='2'><b>Add to plasmid bank:</b></td></tr>
<tr>
<td width='20%'>Plasmid name:</td>
<td width='80%'><?php echo $plasmid['name'];?>
</td>
</tr>
<tr>
<td>Volume:</td>
<td><input type='text' name="volume" id="volume" size="10" value="<?php echo stripslashes(htmlspecialchars($_POST['volume']))?>"/>μL</td>
</tr>
<tr>
<td>Maker:</td>
<td>
	<?php
	$query="SELECT id,name FROM people ORDER BY CONVERT(name USING GBK)";
	echo query_select_choose("maker",$query,"id","name",$plasmid['created_by']);
	?>
</td>
</tr>
<tr>
<td>Note:</td>
<td><textarea name='note' id='note' cols="40" rows="3"><?php echo stripslashes($_POST['note']) ?></textarea></td>
</tr>
<tr><td colspan='2'>
<input type='submit' name='Submit' value='Submit' />&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
</td></tr>
<?php hidden_inputs("created_by","date_create","add");?>
</table></form>
<?php
}

function export_excel($module_name,$query) {
	$db_conn=db_connect();
	$results = $db_conn->query($query);
	$num_rows=$results->num_rows;
	while($row = $results->fetch_array()) {
		$query="SELECT `name` FROM `plasmids` WHERE `id`='{$row['plasmid_id']}'";
		$result = $db_conn->query($query);
		$plasmid=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['maker']}'";
		$result = $db_conn->query($query);
		$maker=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['created_by']}'";
		$result = $db_conn->query($query);
		$created_by=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['updated_by']}'";
		$result = $db_conn->query($query);
		$updated_by=$result->fetch_assoc();

		$xls[]= ereg_replace("[\r,\n,\t]"," ",$row['id'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$plasmid['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['plasmid_id'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['volume'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$maker['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$created_by['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_create'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$updated_by['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_update'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['note']);
	}
	$title="id"."\t".
	"plasmid name"."\t".
	"plasmid id"."\t".
	"volume"."\t".
	"maker"."\t".
	"created_by"."\t".
	"date_create"."\t".
	"updated_by"."\t".
	"date_update"."\t".
	"note";

	$xls = implode("\r\n", $xls);

	$people_id=get_pid_from_username($_COOKIE['wy_user'] );
	$exportor = get_name_from_id('people',$people_id);

	$fileName ='Export_'.$module_name.'_'.date('Ymd').'.xls';
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$fileName");

	echo "Export from database: ".mb_convert_encoding($module_name,"gb2312","utf-8")."\n";
	echo "Expoet date: ".date('m/d/Y')."\n";
	echo "Export by: ".mb_convert_encoding($exportor['name'],"gb2312","utf-8")."\n";
	echo "Totally ".$num_rows." records."."\n\n";
	echo mb_convert_encoding($title,"gb2312","utf-8")."\n";
	echo mb_convert_encoding($xls,"gb2312","utf-8");
}

function cancel_form() {
	if(!userPermission('3')) {
		alert();
	}
	$request=get_record_from_id('plasmid_request',$_REQUEST['id']);
	$plasmid=get_record_from_id('plasmids',$request['plasmid_id']);
	?>
<script>
function closeSubmit() {
	window.close   ();
}
</script>
<table width='100%'>
<form id="cancel_form" name="cancel_form" method="POST" target="">
<tr><td colspan='2'><h3>Cancel?</h3></td></tr>
<tr>
<td width='20%'>Plasmid name:</td>
<td width='80%'><?php echo $plasmid['name']?></td>
</tr>
<tr><td colspan='2'>
<input type="submit" value="Submit" / >&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
</td></tr>
<?php hidden_inputs("cancelled_by","date_cancel","cancel");?>
</form></table>
<?php
}

function edit_form() {
	if(!userPermission('2')) {
		alert();
	}
	?>
<script>
$.validator.setDefaults({	submitHandler: function() {
	document.editForm.action.value = "edit";
	document.editForm.submit();
	//window.returnValue='ok';
	//window.close   ();
}});
$(document).ready(function() {
	$("#editForm").validate({
		rules: {
			volume: {required:true,min:0}
		},
		messages: {
			volume: {required: 'required'}
		}});
});
function closeSubmit() {
	window.close   ();
}
</script>
<form name="editForm" id="editForm" method="POST" target="">
<table width='100%'>
<tr><td colspan='2'><h3>Edit:</h3></td></tr>
<?php
$bank=get_record_from_id('plasmid_bank',$_REQUEST['id']);
$plasmid=get_record_from_id('plasmids',$bank['plasmid_id']);
?>
<tr>
<td width='20%'>Plasmid name:</td>
<td width='80%'><?php echo $plasmid['name']?></td>
</tr>
<tr>
<td>Volume:</td>
<td><input type='text' name='volume' id="volume" value="<?php echo $bank['volume']?>" size="10"/> μL</td>
</tr>
<tr>
<td>Maker:</td>
<td>
	<?php
	$query="SELECT id,name FROM people ORDER BY CONVERT(name USING GBK)";
	echo query_select_choose("maker",$query,"id","name",$bank['maker']);
	?>
</td>
</tr>
<tr>
<td>Note:</td>
<td><textarea name='note' id="note" cols="40" rows="3"><?php echo $bank['note'] ?></textarea></td>
</tr>
<tr><td colspan='2'>
<input type="submit" value="Submit" name="Submit"/ >&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
<?php hidden_inputs("updated_by","date_update","edit");?>
</td></tr>
</table></form>
<?php
}

function delete_form() {
	if(!userPermission('2')) {
		alert();
	}
	?>
<script>
function closeSubmit() {
	window.close   ();
}
</script>
<form name="delete_form" id="delete_form" method="POST" target="">
<table width='100%'>
<tr><td colspan='2'><h3>Delete?</h3></td></tr>
<?php
$bank=get_record_from_id('plasmid_bank',$_REQUEST['id']);
$plasmid=get_record_from_id('plasmids',$bank['plasmid_id']);
?>
<tr>
<td width='20%'>Plasmid name:</td>
<td width='80%'><?php echo $plasmid['name']?></td>
</tr>
<tr>
<td>Plasmid bank id:</td>
<td><?php echo $_REQUEST['id'];?></td>
</tr>
<tr><td colspan='2'>
<input type="submit" value="Submit" name="Submit"/ >&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
<input type="hidden" name="action" value="delete"/>
</td></tr>
</table></form>
<?php
}

function add()
{
	try {
		if (!filled_out(array($_REQUEST['volume']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$plasmid_id=$_REQUEST['plasmid_id'];
		$volume=$_REQUEST['volume'];
		$maker=$_REQUEST['maker'];
		$note = $_REQUEST['note'];
		$created_by = $_REQUEST['created_by'];
		$date_create = $_REQUEST['date_create'];
		$plasmid=get_name_from_id("plasmids",$plasmid_id);

		$db_conn=db_connect();
		$query="SELECT MAX(id) AS max_id FROM plasmid_bank";
		$result=$db_conn->query($query);
		$match=$result->fetch_assoc();
		$id=$match['max_id']+1;
		$query = "INSERT INTO plasmid_bank
 (`id`,`name`,`plasmid_id`,`volume`,`maker`,`created_by`,`date_create`,`note`)
     VALUES
     ('$id','{$plasmid['name']}','$plasmid_id','$volume','$maker','$created_by','$date_create','$note')";
		$result=$db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		//$id=$db_conn->insert_id;
		$query="UPDATE plasmids SET bank=1 WHERE id='$plasmid_id'";
		$result=$db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
?>
<script>
window.returnValue='ok';
window.close   ();
</script>
<?php
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function cancel()
{
  try {
	  $id=$_REQUEST['id'];
	  $cancelled_by = $_REQUEST['cancelled_by'];
	  $date_cancel = $_REQUEST['date_cancel'];
	  $cancel=1;
	  $db_conn=db_connect();
	  $query = "UPDATE `plasmid_request`
			SET `cancelled_by`='$cancelled_by',
			`date_cancel`='$date_cancel',
			`cancel`='$cancel'
			WHERE id='$id'";
	  $result = $db_conn->query($query);
	  if (!$result) {
		  throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
	  }
		?>
<script>
window.returnValue='ok';
window.close   ();
</script>
		<?php
  }
  catch (Exception $e) {
	  echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}

function edit() {
	try {
		if (!filled_out(array($_REQUEST['volume']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$id=$_REQUEST['id'];
		$volume=$_REQUEST['volume'];
		$maker=$_REQUEST['maker'];
		$note = $_REQUEST['note'];
		$updated_by = $_REQUEST['updated_by'];
		$date_update = $_REQUEST['date_update'];

		$db_conn=db_connect();
		$query = "UPDATE `plasmid_bank` SET
							`volume`='$volume',
							`maker`='$maker',
				  		`note`='$note',
				  		`updated_by`='$updated_by',
				  		`date_update`='$date_update'
				  		WHERE `id`='$id'";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
?>
<script>
window.returnValue='ok';
window.close   ();
</script>
<?php
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function delete() {
	try {
		$id=$_REQUEST['id'];

		$db_conn=db_connect();
		$query = "DELETE FROM `plasmid_bank` WHERE `id`='$id'";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
?>
<script>
window.returnValue='ok';
window.close   ();
</script>
<?php
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function hidden_inputs($people,$date,$action)
{
	if($people!='') {
		echo "<input type='hidden' name='$people' value='";
		$db_conn = db_connect();
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		echo $match['people_id']."'>";
	}
	if($date!='') {
		echo "<input type='hidden' name=$date value='";
		echo date('Y-m-d H:i:s')."'>";
	}
	echo "<input type='hidden' name='action' value='$action'>";
}

function process_request()
{
	$action = $_REQUEST['action'];
	switch ($action) {
		case 'approve':approve();break;
		case 'cancel':cancel();break;
		case 'add':add();break;
		case 'take':take();break;
		case 'edit':edit();break;
		case 'check':check();break;
		case 'delete_form':delete_form();break;
		case 'delete':delete();break;
		case 'add_form':add_form();break;
		case 'take_form':take_form();break;
		case 'edit_form':edit_form();break;
		//case 'detail':detail();break;
		case 'check_form':check_form();break;
	}
}
?>