<?php
include('include/includes.php');
check_login_status();
if ($_REQUEST['type']=='export_excel') {
	$query=$_SESSION['query'];
	export_excel('orders',$query);
	exit;
}
do_html_header('Plasmid request operate-Quicklab');
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

function request_form() {
	if(!userPermission('3')) {
		alert();
	}
	?>
<script type="text/javascript">
$.validator.setDefaults({	submitHandler: function() {
	document.request_form.action.value = "request";
	document.request_form.submit();
	//window.returnValue='ok';
	//window.close   ();
}});
$(document).ready(function() {
	$("#request_form").validate({
		rules: {
			volume: {required:true,min:0},
			note: "required"
		},
		messages: {
			volume: {required: 'required'},
			note: {required: 'required'}
		}});
});
function closeSubmit() {
	window.close   ();
}
</script>
	<?php
	$plasmid=get_name_from_id("plasmids",$_REQUEST['plasmid_id']);
	?>
<form name="request_form" id="request_form" method="POST" target="">
<table width='100%'>
<tr><td colspan='2'><b>Request:</b></td></tr>
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
<td>Note:</td>
<td><textarea name='note' id='note' cols="40" rows="3"  class="required" ><?php echo stripslashes($_POST['note']) ?></textarea>*</td>
</tr>
<tr><td colspan='2'>
<input type='submit' name='Submit' value='Submit' />&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
</td></tr>
<?php hidden_inputs("created_by","date_create","request");?>
</table></form>
<?php
}

function check_form() {
	if(!userPermission('2')) {
		alert();
	}
	$db_conn = db_connect();
	$id=$_REQUEST['id'];
  $request=get_record_from_id("plasmid_request",$id);
  $plasmid=get_record_from_id("plasmids",$request['plasmid_id']);
?>
<script>
function approveSubmit() {
	document.checkForm.action.value = "approve";
	document.checkForm.submit();
	//window.returnValue='ok';
	//window.close   ();
}
function checkSubmit() {
	document.checkForm.action.value = "check";
	document.checkForm.submit();
	//window.close   ();
}
function closeSubmit() {
	window.close   ();
}
</script>
<form method="POST" target="" name="checkForm" id="checkForm">
<table width='100%'>
<tr><td colspan='2'><h3>Check:</h3></td></tr>
<tr>
<td width='20%'>Plasmid name:</td>
<td width='80%'><?php echo $plasmid['name']?></td>
</tr>
<tr>
<td>Volume:</td>
<td><?php echo $request['volume'];?> μL</td>
<tr>
<td>Note:</td>
<td><?php echo $request['note']; ?></td>
</tr>
<tr>
<td>Created by:</td><td>
<?php
$people = get_name_from_id('people',$request['created_by']);
echo $people['name'].'  '.$request['date_create'];
?>
</td>
<tr>
<td>Comment:</td>
<td><textarea name="comment" cols="40" rows="5"></textarea></td>
</tr>
<tr>
<td colspan="2"/>&nbsp;&nbsp;
<input type="submit" name="approve" value="Approve" onclick="approveSubmit()"/ >&nbsp;&nbsp;
<input type="submit" name="check" value="Check" onclick="checkSubmit()"/>&nbsp;&nbsp;
<input type="button" name="close" value="Close" onclick="closeSubmit()"/>
</td>
</tr>
</table>
<input type='hidden' name='action' value="" >
</form>
<?php
}

function check () {
	$id=$_REQUEST['id'];
	$comment=nl2br($_REQUEST['comment']);
	$db_conn = db_connect();
	$request=get_record_from_id("plasmid_request",$id);
	$plasmid=get_name_from_id("plasmids",$request['plasmid_id']);
  //created_by
  $created_by=get_record_from_id('people',$request['created_by']);
  //checked by
  $query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
  $result = $db_conn->query($query);
  $match=$result->fetch_assoc();
  $checked_by = $match['people_id'];
  $checked_by=get_record_from_id('people',$checked_by);
  $date_check=date("Y-m-d H:i:s");
  $message = "
<p>Your plasmid request <a href='http://".IP_ADDRESS."/quicklab/plasmid_request.php?id=$id'  target='_blank'>".$plasmid['name']."</a> on ".$plasmid['date_create']."<br>
Checked by: {$checked_by['name']} at $date_check<br>
Comment: <br>
$comment<br>
reply please!</p>
";
	$mail= new Mailer();
	$mail->basicSetting();
	$mail->Subject ="Plasmid request check (id:$id)-QUICKLAB";
	$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
	$mail->MsgHTML($css.$message);
	$mail ->From = $checked_by['email'];
	$mail ->FromName = $checked_by['name'];
	$mail->AddAddress($created_by['email']);
	$mail->AddAddress("buddyfred@126.com");
	$mail->Send();
	//echo $mail->ErrorInfo;
	?>
<script>
window.close   ();
</script>
	<?php
}

function approve() {
	$id = $_REQUEST['id'];
	$comment=nl2br($_REQUEST['comment']);
	$db_conn=db_connect();
	$query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	$approved_by=get_record_from_id('people',$match['people_id']);
	$approve_date=date('Y-m-d H:i:s');

	$request=get_record_from_id("plasmid_request",$id);
	$plasmid=get_record_from_id("plasmids",$request['plasmid_id']);
	if ($request['state']=='1') {
		$query = "UPDATE plasmid_request
		  SET `approved_by`='{$approved_by['id']}',
			`date_approve`='$approve_date',
			state='2'
		  WHERE id=$id" ;
		$result = $db_conn->query($query);
		//Send reminder mail to the people who requested.
		$created_by=get_record_from_id('people',$request['created_by']);
		$subject = "Plasmid request approved (id: $id)-QUICKLAB";
		$message = "
<p>Your plasmid request <a href='http://".IP_ADDRESS."/quicklab/plasmid_request.php?id=$id'  target='_blank'>".$plasmid['name']."</a> on ".$request['date_create']."<br>
Approved by: {$approved_by['name']} at $approve_date<br>
Comment: <br>
$comment<br>
</p>
";
		$mail= new Mailer();
		$mail->basicSetting();
		$mail->Subject =$subject;
		$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
		$mail->MsgHTML($css.$message);
		$mail->AddAddress("buddyfred@126.com");
		$mail->AddAddress("mbsu@mail.shcnc.ac.cn");
		$mail->AddAddress($created_by['email']);
		@$mail->Send();
		//echo $mail->ErrorInfo;
	}
	?>
<script>
window.returnValue='ok';
window.close   ();
</script>
	<?php
}

function export_excel($module_name,$query)
{
	$db_conn=db_connect();
	$results = $db_conn->query($query);
	$num_rows=$results->num_rows;
	while($row = $results->fetch_array())
	{
		if ($row['module_id']&&$row['item_id']) {
			$query="SELECT name from modules WHERE id='{$row['module_id']}'";
			$result = $db_conn->query($query);
			$module=$result->fetch_assoc();
			$module=$module['name'];

			$query="SELECT name from `$module` WHERE id='{$row['item_id']}'";
			$result = $db_conn->query($query);
			$item=$result->fetch_assoc();
			$item=$item['name'];
		}
		else {
			$module='';
			$item='';
		}
    $query="SELECT name from sellers WHERE id='{$row['manufacturer']}'";
		$result = $db_conn->query($query);
		$manufacturer=$result->fetch_assoc();
		$query="SELECT name from sellers WHERE id='{$row['dealer']}'";
		$result = $db_conn->query($query);
		$dealer=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['created_by']}'";
		$result = $db_conn->query($query);
		$created_by=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['approved_by']}'";
		$result = $db_conn->query($query);
		$approved_by=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['ordered_by']}'";
		$result = $db_conn->query($query);
		$ordered_by=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['received_by']}'";
		$result = $db_conn->query($query);
		$received_by=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['cancelled_by']}'";
		$result = $db_conn->query($query);
		$cancelled_by=$result->fetch_assoc();
		$query="SELECT name from accounts WHERE id='{$row['account_id']}'";
		$result = $db_conn->query($query);
		$account=$result->fetch_assoc();
		$state_array = array('requested'=>'1','approved'=>'2','ordered'=>'3','received'=>'4');
		foreach ($state_array as $key=>$value) {
			if ($value == $row['state']) {
				$state= $key;
			}
		}
		$cancel_array = array( 'no'=>'0','yes'=>'1');
		foreach ($cancel_array as $key=>$value) {
			if ($value == $row['cancel']) {
				$cancel= $key;
			}
		}
		$mask_array = array( 'no'=>'0','yes'=>'1');
		foreach ($mask_array as $key=>$value) {
			if ($value == $row['mask']) {
				$mask= $key;
			}
		}
		$xls[]= ereg_replace("[\r,\n,\t]"," ",$row['id'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$module)."\t".
		ereg_replace("[\r,\n,\t]"," ",$item)."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['trade_name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$manufacturer['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$dealer['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['cat_nbr'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['lot_nbr'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['pack'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['qty'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['unit_price'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['price'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$created_by['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$approved_by['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$ordered_by['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$received_by['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$cancelled_by['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['create_date'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['approve_date'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['order_date'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['receive_date'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['cancel_date'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['invoice'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$account['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['note'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$state)."\t".
    ereg_replace("[\r,\n,\t]"," ",$cancel)."\t".
		ereg_replace("[\r,\n,\t]"," ",$mask);
	}
	$title="id"."\t".
	"module"."\t".
	"item"."\t".
	"trade_name"."\t".
	"manufacturer"."\t".
	"dealer"."\t".
	"cat_nbr"."\t".
	"lot_nbr"."\t".
	"pack"."\t".
	"qty"."\t".
	"unit_price"."\t".
	"price"."\t".
	"created_by"."\t".
	"approved_by"."\t".
	"ordered_by"."\t".
	"received_by"."\t".
	"cancelled_by"."\t".
	"create_date"."\t".
	"approve_date"."\t".
	"order_date"."\t".
	"receive_date"."\t".
	"cancel_date"."\t".
	"invoice"."\t".
	"account"."\t".
	"note"."\t".
	"state"."\t".
	"cancel"."\t".
	"mask";

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

function take_form() {
	if(!userPermission('2')) {
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
<tr>
<td>Volume:</td>
<td><?php echo $request['volume']?> μL</td>
</tr>
<tr>
<td>Plasmid bank id:</td>
<td>
	<?php
	$query="SELECT id FROM plasmid_bank WHERE plasmid_id='{$request['plasmid_id']}'";
	echo query_select("plasmid_bank_id",$query,"id","id",$_REQUEST['plasmid_bank_id']);
	?>
</td>
</tr>
<tr><td colspan='2'>
<input type="submit" value="Submit" / >&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
</td></tr>
<?php hidden_inputs("taken_by","date_take","take");?>
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
			volume: {required:true,min:0},
			note: "required"
		},
		messages: {
			volume: {required: 'required'},
			note: {required: 'required'}
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
$request=get_record_from_id('plasmid_request',$_REQUEST['id']);
$plasmid=get_record_from_id('plasmids',$request['plasmid_id']);
?>
<tr>
<td width='20%'>Plasmid name:</td>
<td width='80%'><?php echo $plasmid['name']?></td>
</tr>
<tr>
<td>Volume:</td>
<td><input type='text' name='volume' id="volume" value="<?php echo $request['volume']?>"/></td>
</tr>
<tr>
<td>Note:</td>
<td><textarea name='note' id="note" cols="40" rows="3"><?php echo $request['note'] ?></textarea></td>
</tr>
<tr><td colspan='2'>
<input type="submit" value="Submit" name="Submit"/ >&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
<?php hidden_inputs("updated_by","date_update","edit");?>
</td></tr>
</table></form>
<?php
}

function detail()
{
	if(!userPermission('3')) {
		alert();
	}
	$db_conn = db_connect();
	$request=get_record_from_id("plasmid_request",$_REQUEST['id']);
	$plasmid=get_record_from_id("plasmids",$request['plasmid_id']);
	?>
<table width='100%'>
<tr><td colspan='2'><h3>Plasmid request details.</h3></td></tr>
<tr>
<td width='10%'>Plasmid name:</td>
<td width='90%'><?php echo $plasmid['name']?></td>
</tr>
<tr>
<td>Volume:</td>
<td><?php echo $request['volume']?> μL</td>
</tr>
<tr>
<td>Note:</td>
<td><?php echo wordwrap($request['note'],70,"<br>") ?></td>
</tr>
<tr>
<td>Created by:</td>
<td>
	<?php
	$people = get_name_from_id('people',$request['created_by']);
	echo $people['name'].'  '.$request['date_create'];
	?>
</td>
</tr>
<tr>
<td>Approved by:</td>
<td>
	<?php
	if ($request['approved_by']) {
		$people = get_name_from_id('people',$request['approved_by']);
		echo $people['name'].'  '.$request['date_approve'];
	}
  ?>
</td>
</tr>
<tr>
<td>Taken by:</td>
<td>
	<?php
	if ($request['taken_by']) {
		$people = get_name_from_id('people',$request['taken_by']);
		echo $people['name'].'  '.$request['date_take'];
	}
  ?>
</td>
</tr>
<tr>
<td>Cancel by:</td>
<td>
	<?php
	if ($request['cancelled_by']) {
		$people = get_name_from_id('people',$request['cancelled_by']);
		echo $people['name'].'  '.$request['date_cancel'];
	}
	?>
</td>
</tr>
</table>
	<?php
}

function request()
{
	try {
		if (!filled_out(array($_REQUEST['volume'],$_REQUEST['note']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$plasmid_id=$_REQUEST['plasmid_id'];
		$volume=$_REQUEST['volume'];
		$note = $_REQUEST['note'];
		$created_by = $_REQUEST['created_by'];
		$date_create = $_REQUEST['date_create'];
		$state=1;

		$db_conn=db_connect();
		$query = "insert into plasmid_request
 (`plasmid_id`,`volume`,`created_by`,`date_create`,`state`,`note`)
     values
     ('$plasmid_id','$volume','$created_by','$date_create','$state','$note')";
		$result=$db_conn->query($query);
		$id=$db_conn->insert_id;
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}

		//Send reminder mail to the administrator after request.
		$plasmid=get_name_from_id("plasmids",$plasmid_id);
		$people=get_record_from_id('people',$created_by);
		$subject = "Plasmid request reminder from quicklab";
		$message = "
  <p>".$people['name']." requested ".$plasmid['name']." on ".$date_create."</p>
  <p><a href='http://".IP_ADDRESS."/quicklab/plasmid_request.php?state=1' target='_blank'>Go to quicklab and approve.</a> (If it can not link to the quicklab, copy the address below.)<br>http://".IP_ADDRESS."/quicklab/plasmid_request.php?state=1</p>
";
		$mail= new Mailer();
		$mail->basicSetting();
		$mail->Subject =$subject;
		$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
		$mail->MsgHTML($css.$message);
		$mail->AddAddress("buddyfred@126.com");
		$mail->AddAddress("jyli@mail.shcnc.ac.cn");
		$mail->AddAddress("jli@mail.shcnc.ac.cn");
		@$mail->Send();
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

function take()
{
  try {
	  $id=$_REQUEST['id'];
	  $plasmid_bank_id=$_REQUEST['plasmid_bank_id'];
	  $taken_by = $_REQUEST['taken_by'];
	  $date_take = $_REQUEST['date_take'];
	  $state=3;
	  $db_conn=db_connect();
	  $query = "UPDATE `plasmid_request` SET
	  	`plasmid_bank_id`='$plasmid_bank_id',
			`taken_by`='$taken_by',
			`date_take`='$date_take',
			`state`='$state'
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

function edit()
{
	try {
		if (!filled_out(array($_REQUEST['volume'],$_REQUEST['note']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$id=$_REQUEST['id'];
		$volume=$_REQUEST['volume'];
		$note = $_REQUEST['note'];
		$updated_by = $_REQUEST['updated_by'];
		$date_update = $_REQUEST['date_update'];

		$db_conn=db_connect();
		$query = "UPDATE `plasmid_request` SET
							`volume`='$volume',
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
		case 'request':request();break;
		case 'take':take();break;
		case 'edit':edit();break;
		case 'check':check();break;
		case 'cancel_form':cancel_form();break;
		case 'request_form':request_form();break;
		case 'take_form':take_form();break;
		case 'edit_form':edit_form();break;
		case 'detail':detail();break;
		case 'check_form':check_form();break;
	}
}
?>