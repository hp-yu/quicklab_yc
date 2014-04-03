<?php
include('include/includes.php');
check_login_status();
if ($_REQUEST['type']=='export_excel') {
	$query=$_SESSION['query'];
	export_excel('orders',$query);
	exit;
}

//do_html_header('Oders operate-Quicklab');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Oders operate-Quicklab</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<BASE target='_self'>
<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
</head>
<body leftmargin="5" topmargin="5">
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
			trade_name: "required",
			dealer: "required",
			qty: {required: true,number:true},
			price: {required:true,number:true}
		},
		messages: {
			trade_name: {required: 'required'},
			dealer: {required: 'required'},
			qty: {required: 'required'},
			price: {required: 'required'}
		}});
});
function closeSubmit() {
	window.close   ();
}
</script>
<form name="request_form" id="request_form" method="POST" target="">
<table width='100%'>
<tr><td colspan='2'><b>Request an order:</b></td></tr>
	<?php
	$db_conn=db_connect();
	//check the history records
	if (isset($_GET['module_id'])&&isset($_GET['item_id'])) {
		$query="SELECT * FROM orders WHERE
		module_id='{$_REQUEST['module_id']}'
		AND item_id='{$_REQUEST['item_id']}'
		ORDER BY id DESC";
		$result=$db_conn->query($query);
		$order=$result->fetch_assoc();
		if ($result->num_rows>0) {
			$_REQUEST['manufacturer']=$order['manufacturer'];
			$_REQUEST['dealer']=$order['dealer'];
			$_REQUEST['pack']=$order['pack'];
			$_REQUEST['qty']=$order['qty'];
			$_REQUEST['unit_price']=$order['unit_price'];
			$_REQUEST['price']=$order['price'];
		}
	}
  ?>
<tr>
<td width='20%'>Trade Name:</td>
<td width='80%'>
<input type='text' name='trade_name' id='trade_name' size="40" value="<?php order_name()?>"/>*
</td>
</tr>
<tr>
<td>Manufacturer:</td>
<td>
<?php
    $query= "select id,name from sellers order by CONVERT(name USING GBK)";
    echo query_select_choose('manufacturer', $query,'id','name',$_REQUEST['manufacturer']);
?>&nbsp;<a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a></td>
</tr>
<tr>
<td>Dealer:</td>
<td>
<?php
$query= "select id,name from sellers order by CONVERT(name USING GBK)";
echo query_select_choose('dealer', $query,'id','name',$_REQUEST['dealer']);
?>
*&nbsp;<a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a>
</td>
</tr>
<tr>
<td>Cat. number:</td>
<td><input type='text' name='cat_nbr' value="<?php echo stripslashes(htmlspecialchars($_REQUEST['cat_nbr']))?>"/></td>
</tr>
<tr>
<td>Lot. number:</td>
<td><input type='text' name='lot_nbr' value="<?php echo stripslashes(htmlspecialchars($_POST['lot_nbr']))?>"/></td>
</tr>
<tr>
<td>Pack:</td>
<td><input type='text' name='pack' value="<?php echo stripslashes(htmlspecialchars($_REQUEST['pack']))?>"/></td>
</tr>
<tr>
<td>Quantity:</td>
<td><input type='text' name='qty' id='qty' value="<?php echo stripslashes(htmlspecialchars($_REQUEST['qty']))?>"  class="required" />*</td>
</tr>
<tr>
<td>Unit price:</td>
<td><input type='text' name='unit_price' value="<?php echo stripslashes(htmlspecialchars($_REQUEST['unit_price']))?>"/></td>
</tr>
<tr>
<td>Price:</td>
<td><input type='text' name='price' id='price' value="<?php echo stripslashes(htmlspecialchars($_REQUEST['price']))?>"  class="required" />*</td>
</tr>
<tr>
<td>Note:</td>
<td><textarea name='note' id='note' cols="40" rows="3"  class="" ><?php echo stripslashes($_POST['note']) ?></textarea></td>
</tr>
<tr>
<td>Mask:</td>
<td>
<?php
$mask=array(array("0","no"),array("1","yes"));
echo option_select('mask',$mask,2,$_POST['mask']);
?>
</td>
</tr>
<tr><td colspan='2'>
<input type='submit' name='Submit' value='Submit' />&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
</td></tr>
<?php hidden_inputs("created_by","create_date","request");?>
</table></form>
<?php
}

function check_form() {
	$db_conn = db_connect();
  $query = "SELECT * FROM orders WHERE id =".$_REQUEST['id'];
  $result = $db_conn->query($query);
  $order=$result->fetch_assoc();
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
<tr><td colspan='2'><h3>Check order:</h3></td></tr>
<tr>
<td width='20%'>Trade Name:</td>
<td width='80%'><?php echo $order['trade_name']?></td>
</tr>
<tr>
<td>Manufacturer:</td>
<td>
<?php
$manufacturer=get_name_from_id('sellers',$order['manufacturer']);
echo $manufacturer['name'];
?>
</td>
</tr>
<tr>
<td>Dealer:</td>
<td>
<?php
$dealer=get_name_from_id('sellers',$order['dealer']);
echo $dealer['name'];
?>
</td>
</tr>
<tr>
<td>Cat. number:</td>
<td><?php echo $order['cat_nbr']?></td>
</tr>
<tr>
<td>Pack:</td>
<td><?php echo $order['pack']?></td>
</tr>
<tr>
<td>Qty:</td>
<td><?php echo $order['qty']?></td>
</tr>
<tr>
<td>Unit price:</td>
<td><?php echo $order['unit_price']?></td>
</tr>
<tr>
<td>Price:</td>
<td><?php echo $order['price']?></td>
</tr>
<tr>
<td>Note:</td>
<td><?php echo wordwrap($order['note'],70,"<br>") ?></td>
</tr>
<tr>
<td>Created by:</td><td>
<?php
$people = get_name_from_id('people',$order['created_by']);
echo $people['name'].'  '.$order['create_date'];
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
  $query = "SELECT * FROM orders WHERE id =$id";
  $result = $db_conn->query($query);
  $order=$result->fetch_assoc();
  $trade_name=$order['trade_name'];
  $price=$order['price'];
  $create_date=$order['create_date'];
  //created_by
  $created_by=get_record_from_id('people',$order['created_by']);
  //checked by
  $query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
  $result = $db_conn->query($query);
  $match=$result->fetch_assoc();
  $checked_by = $match['people_id'];
  $checked_by=get_record_from_id('people',$checked_by);
  $date_check=date("Y-m-d H:i:s");
  $message = "
<body>
<p>Your order <a href='http://".IP_ADDRESS."/quicklab/orders.php?id=$id'  target='_blank'>".$trade_name."</a> (price:".$price.") on ".$create_date."<br>
Checked by: {$checked_by['name']} at $date_check<br>
Comment: <br>
$comment<br>
reply please!</p>
";
	$mail= new Mailer();
	$mail->basicSetting();
	$mail->Subject ="Order check (order id:$id)-QUICKLAB";
	$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
	$mail->MsgHTML($css.$message);
	$mail ->From = $checked_by['email'];
	$mail ->FromName = $checked_by['name'];
	$mail->AddAddress($created_by['email']);
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

	$query="SELECT * FROM orders WHERE id=$id";
	$result = $db_conn->query($query);
	$order=$result->fetch_assoc();
	$trade_name=$order['trade_name'];
  $price=$order['price'];
  $create_date=$order['create_date'];
	if ($order['state']=='1') {
		$query = "UPDATE orders
		  SET approved_by='{$approved_by['id']}',
			approve_date='$approve_date',
			state='2'
		  WHERE id=$id" ;
		$result = $db_conn->query($query);
		//Send reminder mail to the people who requested this order after the order is approved.
		$query="SELECT * FROM `orders_mails` WHERE `key`='is_approve_mail_to_requester'";
		$result=$db_conn->query($query);
		$match_orders_mails_approve=$result->fetch_assoc();
		if ($match_orders_mails_approve['value']==1) {
			$created_by=$order['created_by'];
			$people=get_record_from_id('people',$created_by);
			$to=trim($people['email']);
			$subject = "Order approved (order id: $id)-QUICKLAB";
			$message = "
<p>Your order <a href='http://".IP_ADDRESS."/quicklab/orders.php?id=$id'  target='_blank'>".$trade_name."</a> (price:".$price.") on ".$create_date."<br>
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
			$mail->AddAddress($to);
			@$mail->Send();
			echo $mail->ErrorInfo;
		}
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
		$xls[]= ereg_replace("[\r,\n,\,]"," ",$row['id']).",".
		ereg_replace("[\r,\n,\,]"," ",$module).",".
		ereg_replace("[\r,\n,\,]"," ",$item).",".
		ereg_replace("[\r,\n,\,]"," ",$row['trade_name']).",".
    ereg_replace("[\r,\n,\,]"," ",$manufacturer['name']).",".
    ereg_replace("[\r,\n,\,]"," ",$dealer['name']).",".
    ereg_replace("[\r,\n,\,]"," ",$row['cat_nbr']).",".
    ereg_replace("[\r,\n,\,]"," ",$row['lot_nbr']).",".
    ereg_replace("[\r,\n,\,]"," ",$row['pack']).",".
    ereg_replace("[\r,\n,\,]"," ",$row['qty']).",".
    ereg_replace("[\r,\n,\,]"," ",$row['unit_price']).",".
    ereg_replace("[\r,\n,\,]"," ",$row['price']).",".
    ereg_replace("[\r,\n,\,]"," ",$created_by['name']).",".
    ereg_replace("[\r,\n,\,]"," ",$approved_by['name']).",".
    ereg_replace("[\r,\n,\,]"," ",$ordered_by['name']).",".
    ereg_replace("[\r,\n,\,]"," ",$received_by['name']).",".
    ereg_replace("[\r,\n,\,]"," ",$cancelled_by['name']).",".
    ereg_replace("[\r,\n,\,]"," ",date("Y-m-d",strtotime($row['create_date']))).",".
    ereg_replace("[\r,\n,\,]"," ",date("Y-m-d",strtotime($row['approve_date']))).",".
    ereg_replace("[\r,\n,\,]"," ",date("Y-m-d",strtotime($row['order_date']))).",".
    ereg_replace("[\r,\n,\,]"," ",date("Y-m-d",strtotime($row['receive_date']))).",".
    ereg_replace("[\r,\n,\,]"," ",date("Y-m-d",strtotime($row['cancel_date']))).",".
    ereg_replace("[\r,\n,\,]"," ",$row['invoice']).",".
    ereg_replace("[\r,\n,\,]"," ",$account['name']).",".
    ereg_replace("[\r,\n,\,]"," ",$row['note']).",".
    ereg_replace("[\r,\n,\,]"," ",$state).",".
    ereg_replace("[\r,\n,\,]"," ",$cancel).",".
		ereg_replace("[\r,\n,\,]"," ",$mask);
	}
	$title="id".",".
	"module".",".
	"item".",".
	"trade_name".",".
	"manufacturer".",".
	"dealer".",".
	"cat_nbr".",".
	"lot_nbr".",".
	"pack".",".
	"qty".",".
	"unit_price".",".
	"price".",".
	"created_by".",".
	"approved_by".",".
	"ordered_by".",".
	"received_by".",".
	"cancelled_by".",".
	"create_date".",".
	"approve_date".",".
	"order_date".",".
	"receive_date".",".
	"cancel_date".",".
	"invoice".",".
	"account".",".
	"note".",".
	"state".",".
	"cancel".",".
	"mask";

	$xls = implode("\r\n", $xls);

	$people_id=get_pid_from_username($_COOKIE['wy_user'] );
	$exportor = get_name_from_id('people',$people_id);

	$fileName ='Export-'.$module_name.'-'.date('Ymd').'.csv';
	header("Content-type: application/csv");
	header("Content-Disposition: attachment; filename=$fileName");

	echo "Export from database: ".mb_convert_encoding($module_name,"gb2312","utf-8")."\n";
	echo "Expoet date: ".date('m/d/Y')."\n";
	echo "Export by: ".mb_convert_encoding($exportor['name'],"gb2312","utf-8")."\n";
	echo "Totally ".$num_rows." records."."\n\n";
	echo mb_convert_encoding($title,"gb2312","utf-8")."\n";
	echo mb_convert_encoding($xls,"gb2312","utf-8");
}
function order_form()
{
	do_header();
	//do_leftnav();
	if(!userPermission('3')) {
		alert();
	}
	?>
	<table width='100%'  class='operate'>
  <tr><td colspan='2' align="center"><h2>Orders operate</h2></td></tr>
	<form id="order" method="POST" target="_self">
	<tr><td colspan='2'><h3>You have ordered <?php order_name();?> ?</h3></td>
  </tr>
  <tr>
    <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
  </tr>
  <?php hidden_inputs("ordered_by","order_date","order");?>
	</form></table>
	<?php
	do_footer();
}
function quick_order_form()
{
	do_header();
	//do_leftnav();
	if(!userPermission('2'))
	{
		alert();
	}
	?>
	<table width='100%'  class='operate'>
  <tr><td colspan='2' align="center"><h2>Orders operate</h2></td></tr>
	<form id="request" method="POST" target="_self">
	<?php
	if($_REQUEST['module_id']&&$_REQUEST['item_id']) {
		?>
		<tr><td colspan='2'><h3>Quick order for <?php order_name()?></h3></td></tr>
		<?php
	}
	else {
		?>
		<tr><td colspan='2'><h3>Quick order:</h3></td></tr>
		<?php
	}
	/*
	elseif ($_SESSION['selecteditemRequest']) {
		$num_selecteditem=count($_SESSION['selecteditemRequest']);
		echo "<tr><td colspan='2'><h3>Request an order for those ".$num_selecteditem." items:</h3></td></tr>";
		echo "<input type='hidden' name='module_id' value='".$_REQUEST['module_id']."'/>";
	}
	*/
  ?>
  <tr>
    <td width='20%'>Trade Name:</td>
    <td width='80%'><input type='text' name='trade_name' size="40" value="<?php order_name()?>"/>*</td>
  </tr>
  <tr>
    <td>Manufacturer:</td>
    <td>
    <?php
    $query= "select id,name from sellers order by CONVERT(name USING GBK)";
    echo query_select_choose('manufacturer', $query,'id','name',$_POST['manufacturer']);
    ?>&nbsp;
        <a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a>
    </td>
  </tr>
  <tr>
    <td>Dealer:</td>
    <td><?php
    $query= "select id,name from sellers order by CONVERT(name USING GBK)";
    echo query_select_choose('dealer', $query,'id','name',$_POST['dealer']);
    ?>
    *&nbsp;
        <a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a></td>
  </tr>
  <tr>
    <td>Cat. number:</td>
    <td><input type='text' name='cat_nbr' value="<?php echo stripslashes(htmlspecialchars($_POST['cat_nbr']))?>"/></td>
  </tr>
  <tr>
    <td>Lot. number:</td>
    <td><input type='text' name='lot_nbr' value="<?php echo stripslashes(htmlspecialchars($_POST['lot_nbr']))?>"/></td>
  </tr>
  <tr>
    <td>Pack:</td>
    <td><input type='text' name='pack' value="<?php echo stripslashes(htmlspecialchars($_POST['pack']))?>"/></td>
  </tr>
  <tr>
    <td>Quantity:</td>
    <td><input type='text' name='qty' value="<?php echo stripslashes(htmlspecialchars($_POST['qty']))?>"/>*</td>
  </tr>
  <tr>
    <td>Unit price:</td>
    <td><input type='text' name='unit_price' value="<?php echo stripslashes(htmlspecialchars($_POST['unit_price']))?>"/></td>
  </tr>
  <tr>
    <td>Price:</td>
    <td><input type='text' name='price' value="<?php echo stripslashes(htmlspecialchars($_POST['price']))?>"/>*</td>
  </tr>
  <tr>
    <td>Invoice(8 characters):</td>
    <td><input type='text' name='invoice' value="<?php echo $order['invoice']?>"/></td>
  </tr>
  <?php
    if (userPermission('2')) {
    	?>
  <tr>
    <td>Account:</td>
    <td>
        <?php
    $query="SELECT * FROM accounts ORDER BY name";
    //echo query_select_choose('account_id',$query,id,name,$order['account_id']);
    $db_conn = db_connect();
    $result = $db_conn->query($query);
    if (!$result) {
      return('');
    }
    $select  = "<select name='account_id'>";
    $select .= '<option value=""';
    if($order['account_id'] == '') $select .= ' selected ';
    $select .= '>- Choose -</option>';
    while ($option = $result->fetch_assoc()) {
    	$query="SELECT SUM(price) FROM orders WHERE account_id='{$option['id']}'";
	    $result_order=$db_conn->query($query);
	    $match=$result_order->fetch_assoc();
	    $momey_used=number_format($match['SUM(price)']);
	    $money_available=number_format($option['money_available']);
	    @$percent=number_format($match['SUM(price)']/$option['money_available']*100,2)."%";
      $select .= "<option value='{$option['id']}'";
      if ($option['id'] == $order['account_id'])  {
        $select .= ' selected';
      }
      $select .=  ">".$option['name']." ".$momey_used."/".$money_available." ".$percent."</option>";
    }
    $select .= "</select>\n";
    echo $select;
    ?>
    </td>
  </tr>
  <?php
    }
  ?>
  <tr>
    <td>Note:</td>
    <td><textarea name='note' cols="40" rows="3"><?php echo stripslashes($_POST['note']) ?></textarea></td>
  </tr>
  <tr>
    <td>Mask:</td>
    <td><?php
    $mask=array(array("0","no"),array("1","yes"));
	  echo option_select('mask',$mask,2,$_POST['mask']);?></td>
  </tr>
  <tr>
    <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
  </tr>
<?php hidden_inputs("created_by","create_date","quickorder");?>
</form></table>
<?php
do_footer();
}

function cancel_form() {
	if(!userPermission('3')) {
		alert();
	}
	$order=get_record_from_id('orders',$_REQUEST['id']);
	?>
<script>
function closeSubmit() {
	window.close   ();
}
</script>
<table width='100%'>
<form id="cancel_form" name="cancel_form" method="POST" target="">
<tr><td colspan='2'><h3>Cancel order?</h3></td></tr>
<tr>
<td width='20%'>Trade Name:</td>
<td width='80%'><?php echo $order['trade_name']?></td>
</tr>
<tr><td colspan='2'>
<input type="submit" value="Submit" / >&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
</td></tr>
<?php hidden_inputs("cancelled_by","cancel_date","cancel");?>
</form></table>
<?php
}

function receive_form() {
	if(!userPermission('3')) {
		alert();
	}
	$order=get_record_from_id('orders',$_REQUEST['id']);
	?>
<script>
function closeSubmit() {
	window.close   ();
}
</script>
<table width='100%'>
<form id="receive" method="POST" target="">
<tr><td colspan='2'><h3>Receive order?</h3></td></tr>
<tr>
<td width='20%'>Trade Name:</td>
<td width='80%'><?php echo $order['trade_name']?></td>
<input type="hidden" name="name" value="<?php echo $order['trade_name']?>"/>
</tr>
<tr><td colspan='2'>
<input type="submit" value="Submit"/ >&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
</td></tr>
<?php hidden_inputs("received_by","receive_date","receive");?>
</form>
</table>
<?php
}

function edit_form() {
	if(!userPermission('3')) {
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
			trade_name: "required",
			dealer: "required",
			qty: {required: true,number:true},
			price: {required:true,number:true}
		},
		messages: {
			trade_name: {required: 'required'},
			dealer: {required: 'required'},
			qty: {required: 'required'},
			price: {required: 'required'}
		}});
});
function closeSubmit() {
	window.close   ();
}
</script>
<form name="editForm" id="editForm" method="POST" target="">
<table width='100%'>
<?php
if($_REQUEST['id']) {
?>
<tr><td colspan='2'><h3>Edit order:</h3></td></tr>
<?php
}
elseif ($_SESSION['selecteditemRequest']) {
	$num_selecteditem=count($_SESSION['selecteditemRequest']);
?>
<tr><td colspan='2'><h3>Order for those <?php echo $num_selecteditem?> items:</h3></td></tr>
<input type='hidden' name='module_id' value="<?php echo $_REQUEST['module_id']?>"/>
<?php
}
$order=get_record_from_id('orders',$_REQUEST['id']);
?>
<tr>
<td width='20%'>Trade Name:</td>
<td width='80%'><input type='text' name='trade_name' size="40" value="<?php echo $order['trade_name']?>"/>*</td>
</tr>
<tr>
<td>Manufacturer:</td>
<td>
<?php
$query= "select id,name from sellers ORDER BY CONVERT(name USING GBK)";
echo query_select_choose('manufacturer', $query,'id','name',$order['manufacturer']);
?>
&nbsp;<a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a>
</td>
</tr>
<tr>
<td>Dealer:</td>
<td>
<?php
$query= "select id,name from sellers ORDER BY CONVERT(name USING GBK)";
echo query_select_choose('dealer', $query,'id','name',$order['dealer']);
?>
*&nbsp;<a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a></td>
</tr>
<tr>
<td>Cat. number:</td>
<td><input type='text' name='cat_nbr' value="<?php echo $order['cat_nbr']?>"/></td>
</tr>
<tr>
<td>Lot. number:</td>
<td><input type='text' name='lot_nbr' value="<?php echo $order['lot_nbr']?>"/></td>
</tr>
<tr>
<td>Pack:</td>
<td><input type='text' name='pack' value="<?php echo $order['pack']?>"/></td>
</tr>
<tr>
<td>Quantity:</td>
<td><input type='text' name='qty' value="<?php echo $order['qty']?>"/>*</td>
</tr>
<tr>
<td>Unit price:</td>
<td><input type='text' name='unit_price' value="<?php echo $order['unit_price']?>"/></td>
</tr>
<tr>
<td>Price:</td>
<td><input type='text' name='price' value="<?php echo $order['price']?>"/>*</td>
</tr>
<tr>
<td>Invoice:</td>
<td><input type='text' name='invoice' value="<?php echo $order['invoice']?>"/></td>
</tr>
<?php
if (userPermission('2')) {
?>
<tr>
<td>Account:</td>
<td>
<?php
$query="SELECT * FROM accounts ORDER BY name";
//echo query_select_choose('account_id',$query,id,name,$order['account_id']);
$db_conn = db_connect();
$result = $db_conn->query($query);
if (!$result) {
	return('');
}
$select  = "<select name='account_id'>";
$select .= '<option value=""';
if($order['account_id'] == '') $select .= ' selected ';
$select .= '>- Choose -</option>';
while ($option = $result->fetch_assoc()) {
	$query="SELECT SUM(price) FROM orders WHERE account_id='{$option['id']}'";
	$result_order=$db_conn->query($query);
	$match=$result_order->fetch_assoc();
	$momey_used=number_format($match['SUM(price)']);
	$money_available=number_format($option['money_available']);
	@$percent=number_format($match['SUM(price)']/$option['money_available']*100,2)."%";
	$select .= "<option value='{$option['id']}'";
	if ($option['id'] == $order['account_id'])  {
		$select .= ' selected';
	}
	$select .=  ">".$option['name']." ".$momey_used."/".$money_available." ".$percent."</option>";
}
$select .= "</select>\n";
echo $select;
?>
</td>
</tr>
<?php
}
?>
<tr>
<td>Note:</td>
<td><textarea name='note' cols="40" rows="3"><?php echo $order['note'] ?></textarea></td>
</tr>
<?php
if (userPermission('2',$order['created_by'])) {
?>
<tr>
<td>Mask:</td>
<td>
<?php
$mask=array(array("0","no"),array("1","yes"));
echo option_select('mask',$mask,2,$order['mask']);
?>
</td></tr>
<?php
}
?>
<tr><td colspan='2'>
<input type="submit" value="Submit" name="Submit"/ >&nbsp;&nbsp;
<input type="button" value="Close" onclick="closeSubmit()"/>
<input type="hidden" name="action" value="edit"/>
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
  $query = "SELECT *,
  DATE_FORMAT(create_date,'%m/%d/%y')AS create_date,
  DATE_FORMAT(approve_date ,'%m/%d/%y')AS approve_date ,
  DATE_FORMAT(order_date ,'%m/%d/%y')AS order_date ,
  DATE_FORMAT(receive_date ,'%m/%d/%y')AS receive_date,
  DATE_FORMAT(cancel_date ,'%m/%d/%y')AS cancel_date
  FROM orders WHERE id =".$_REQUEST['id'];
  $result = $db_conn->query($query);
  $order=$result->fetch_assoc();
	?>
	<table width='100%'>
  <tr><td colspan='2'><h3>Order details.</h3></td></tr>
  <tr>
    <td width='20%'>Trade Name:</td>
    <td width='80%'><?php echo $order['trade_name']?></td>
  </tr>
  <tr>
    <td>Manufacturer:</td>
    <td><?php
    $manufacturer=get_name_from_id('sellers',$order['manufacturer']);
    echo $manufacturer['name'];
    ?></td>
  </tr>
  <tr>
    <td>Dealer:</td>
    <td><?php
    $dealer=get_name_from_id('sellers',$order['dealer']);
    echo $dealer['name'];
    ?></td>
  </tr>
  <tr>
    <td>Cat. number:</td>
    <td><?php echo $order['cat_nbr']?></td>
  </tr>
  <tr>
    <td>Lot. number:</td>
    <td><?php echo $order['lot_nbr']?></td>
  </tr>
  <tr>
    <td>Pack:</td>
    <td><?php echo $order['pack']?></td>
  </tr>
  <tr>
    <td>Qty:</td>
    <td><?php echo $order['qty']?></td>
  </tr>
  <tr>
    <td>Unit price:</td>
    <td><?php echo $order['unit_price']?></td>
  </tr>
  <tr>
    <td>Price:</td>
    <td><?php echo $order['price']?></td>
  </tr>
  <tr>
    <td>Invoice:</td>
    <td><?php echo $order['invoice']?></td>
  </tr>
  <?php
    if (userPermission('2')) {
    	?>
  <tr>
    <td>Account:</td>
    <td><?php
    $account=get_name_from_id('accounts',$order['account_id']);
    echo $account['name'];
    ?>
    </td>
  </tr>
  <?php
    }
  ?>
  <tr>
    <td>Note:</td>
    <td><?php echo wordwrap($order['note'],70,"<br>") ?></td>
  </tr>
  <tr>
    <td>Created by:</td><td><?php
		$people = get_name_from_id('people',$order['created_by']);
		echo $people['name'].'  '.$order['create_date'];?></td>
	</tr>
	<tr>
    <td>Approved by:</td><td><?php
    if ($order['approved_by']) {
    	$people = get_name_from_id('people',$order['approved_by']);
		  echo $people['name'].'  '.$order['approve_date'];
    }?></td>
	</tr>
	<tr>
    <td>Ordered by:</td><td><?php
    if ($order['ordered_by']) {
		  $people = get_name_from_id('people',$order['ordered_by']);
		  echo $people['name'].'  '.$order['order_date'];
    }?></td>
	</tr>
	<tr>
    <td>Received by:</td><td><?php
    if ($order['received_by']) {
		  $people = get_name_from_id('people',$order['received_by']);
		  echo $people['name'].'  '.$order['receive_date'];
    }?></td>
	</tr>
	<tr>
    <td>Cancel by:</td><td><?php
    if ($order['cancelled_by']) {
		  $people = get_name_from_id('people',$order['cancelled_by']);
		  echo $people['name'].'  '.$order['cancel_date'];
    }?></td>
	</tr>
</table>
	<?php
}
function request()
{
	try {
		if (!filled_out(array($_REQUEST['trade_name'],$_REQUEST['dealer'],$_REQUEST['qty'],$_REQUEST['price'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$module_id=$_REQUEST['module_id'];
		$item_id=$_REQUEST['item_id'];
		$trade_name=$_REQUEST['trade_name'];
		$manufacturer=$_REQUEST['manufacturer'];
		$dealer = $_REQUEST['dealer'];
		$cat_nbr=$_REQUEST['cat_nbr'];
		$lot_nbr = $_REQUEST['lot_nbr'];
		$pack=$_REQUEST['pack'];
		$qty = $_REQUEST['qty'];
		$unit_price=$_REQUEST['unit_price'];
		$price = $_REQUEST['price'];
		$note = $_REQUEST['note'];
		$created_by = $_REQUEST['created_by'];
		$create_date = $_REQUEST['create_date'];
		$mask=$_REQUEST['mask'];
		$state=1;

		$db_conn=db_connect();
		$query = "insert into orders
 (module_id,item_id,trade_name,manufacturer,dealer,cat_nbr,lot_nbr,pack,qty,unit_price,price,note,created_by,create_date,state,mask)
     values
     ('$module_id','$item_id','$trade_name','$manufacturer','$dealer','$cat_nbr','$lot_nbr','$pack','$qty','$unit_price','$price','$note','$created_by','$create_date','$state','$mask')";
		$result = $db_conn->query($query);
		$order_id=$db_conn->insert_id;
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}

		//Send reminder mail to the administrator after the order is requested.
		$query="SELECT * FROM `orders_approve` WHERE `key`='is_approve'";
		$result=$db_conn->query($query);
		$match_orders_approve=$result->fetch_assoc();
		$query="SELECT * FROM `orders_approve` WHERE `key`='lower_limit'";
		$result=$db_conn->query($query);
		$match_orders_approve_limit=$result->fetch_assoc();
		$query="SELECT * FROM `orders_mails` WHERE `key`='is_request_mail_to_administrator'";
		$result=$db_conn->query($query);
		$match_orders_mails_administrator=$result->fetch_assoc();
		if ($match_orders_approve['value']==1&&$price>=$match_orders_approve_limit['value']) {
			if ($match_orders_mails_administrator['value']==1) {
				$query="SELECT * FROM orders_admin";
				$result=$db_conn->query($query);
				while ($match=$result->fetch_assoc()) {
					$people=get_record_from_id('people',$match['people_id']);
					$subject = 'Order reminder from quicklab';
					if ($match['upper_limit']==""&&$price>=$match['lower_limit']) {
						$to=$people['email'];
						$price_limit_str="lower_limit={$match['lower_limit']}";
						$people=get_record_from_id('people',$created_by);
						$message = "
	  <p>".$people['name']." requested ".$trade_name." (price:".$price.") on ".$create_date."</p>
	  <p><a href='http://".IP_ADDRESS."/quicklab/orders.php?state=1&".$price_limit_str."' target='_blank'>Go to quicklab and approve.</a> (If it can not link to the quicklab, copy the address below.)<br>http://".IP_ADDRESS."/quicklab/orders.php?state=1&".$price_limit_str."</p>
	";
						$mail= new Mailer();
						$mail->basicSetting();
						$mail->Subject =$subject;
						$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
						$mail->MsgHTML($css.$message);
						$mail->AddAddress($to);
						@$mail->Send();
					}
					if ($match['lower_limit']<>""&&$match['upper_limit']<>""&&$price>=$match['lower_limit']&&$price<=$match['upper_limit']) {
						$to=$people['email'];
						$price_limit_str="lower_limit={$match['lower_limit']}&upper_limit={$match['upper_limit']}";
						$people=get_record_from_id('people',$created_by);
						$message = "
	  <p>".$people['name']." requested ".$trade_name." (price:".$price.") on ".$create_date."</p>
	  <p><a href='http://".IP_ADDRESS."/quicklab/orders.php?state=1&".$price_limit_str."' target='_blank'>Go to quicklab and approve.</a> (If it can not link to the quicklab, copy the address below.)<br>http://".IP_ADDRESS."/quicklab/orders.php?state=1&".$price_limit_str."</p>
	";
						$mail= new Mailer();
						$mail->basicSetting();
						$mail->Subject =$subject;
						$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
						$mail->MsgHTML($css.$message);
						$mail->AddAddress($to);
						@$mail->Send();
					}
				}
			}		
 		}
?>
<script>
window.returnValue='ok';
window.close   ();
</script>
<?php
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function order()
{
	try {
		$id=$_REQUEST['id'];
		$ordered_by = $_REQUEST['ordered_by'];
		$order_date = $_REQUEST['order_date'];
		$state=3;

		$db_conn=db_connect();
		$query = "UPDATE orders SET
				  ordered_by='$ordered_by',
				  order_date='$order_date',
				  state='$state'
				  WHERE id='$id'";

		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		header("Location:".$_SESSION['url_1']);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function quick_order()
{
	$trade_name=$_REQUEST['trade_name'];
	$manufacturer=$_REQUEST['manufacturer'];
	$dealer=$_REQUEST['dealer'];
	$cat_nbr=$_REQUEST['cat_nbr'];
	$lot_nbr=$_REQUEST['lot_nbr'];
	$pack=$_REQUEST['pack'];
	$qty = $_REQUEST['qty'];
	$unit_price = $_REQUEST['unit_price'];
	$price = $_REQUEST['price'];
	$invoice = $_REQUEST['invoice'];
	$account_id = $_REQUEST['account_id'];
	$note = $_REQUEST['note'];
	$created_by = $_REQUEST['created_by'];
	$create_date = $_REQUEST['create_date'];
	$received_by = $_REQUEST['created_by'];
	$receive_date = $_REQUEST['create_date'];
	$mask=$_REQUEST['mask'];
	$state=4;
	try
	{
		if (!filled_out(array($_REQUEST['trade_name'],$_REQUEST['dealer'],$_REQUEST['qty'],$_REQUEST['price'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}

		$db_conn=db_connect();
		$query = "INSERT INTO orders
	(trade_name,manufacturer,dealer,cat_nbr,lot_nbr,pack,qty,unit_price,price,invoice,account_id,
	note,created_by,create_date,received_by,receive_date,state,mask)
	VALUES
	('$trade_name','$manufacturer','$dealer','$cat_nbr','$lot_nbr','$pack','$qty','$unit_price','$price','$invoice','$account_id',
	'$note','$created_by','$create_date','$received_by','$receive_date','$state','$mask')";
		$result = $db_conn->query($query);
		$order_id=$db_conn->insert_id;
		if (!$result)
		{
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		header("Location:".$_SESSION['url_1']);
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function cancel()
{
  try {
	  $order_id=$_REQUEST['id'];
	  $cancelled_by = $_REQUEST['cancelled_by'];
	  $cancel_date = $_REQUEST['cancel_date'];
	  $cancel=1;
	  $db_conn=db_connect();
	  $query = "UPDATE orders
			SET cancelled_by='$cancelled_by',
			cancel_date='$cancel_date',
			cancel='$cancel'
			WHERE id='$order_id'";
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

function receive() {
	try {
		$id=$_REQUEST['id'];
		$db_conn=db_connect();
		$query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$received_by=$match['people_id'];
		$receive_date=date('Y-m-d');

		$query="SELECT * FROM orders WHERE id=$id";
		$result = $db_conn->query($query);
		$order=$result->fetch_assoc();
		if ($order['state']=='2'||$order['state']=='3') {
			$query = "UPDATE orders
		  SET received_by='$received_by',
			receive_date='$receive_date',
			state='4'
		  WHERE id=$id" ;
			$result = $db_conn->query($query);
			if (!$result) {
		  	throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
	  	}

			if ($order['module_id']=='10') {
				$date_arrival=date('Y-m-d');
				$query="UPDATE animals SET
			  state=1,
			  date_arrival='$date_arrival'
			  WHERE id='{$order['item_id']}'";
				$result = $db_conn->query($query);
				if (!$result) {
		  		throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
	  		}
			}
			//add a storage
			if ($_REQUEST['storage_check']==1) {
				$num_select = $_REQUEST['num_select'];
				for ($i=1;$i<=$num_select;$i++) {
					if ($_REQUEST['S'.$i]!="") {
						$location = $_REQUEST['S'.$i];
					}
				}
				if ($location!="") {
					$keeper = $_REQUEST['keeper'];
					$location_details=$_REQUEST['location_details'];
					$date_storage=date('Y-m-d');
					$date_expiry=$_REQUEST['date_expiry'];
					$note=$_REQUEST['note'];
					$state=1;
					$mask=$_REQUEST['mask'];
					$name=$_REQUEST['name'];
					$module_id=$order['module_id'];
					$item_id=$order['item_id'];
					$query = "insert into storages
           (name,module_id,item_id,keeper,location_id,location_details,date_storage,date_expiry,note,order_id,state)
          values
           ('$name','$module_id','$item_id','$keeper','$location','$location_details','$date_storage','$date_expiry','$note','$id','$state')";
					$result = $db_conn->query($query);
					if (!$result) {
						throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
					}
				}
			}
			//Send mail to the people who requested this order after the product is received by other people.
			$created_by=$order['created_by'];
			$query="SELECT * FROM `orders_mails` WHERE `key`='is_receive_mail_to_requester'";
			$result=$db_conn->query($query);
			$match_orders_mails_receive=$result->fetch_assoc();
			if ($match_orders_mails_receive['value']==1&&$received_by!=$created_by) {
				$people=get_record_from_id('people',$created_by);
				$to=trim($people['email']);
				$subject = 'Your order received';
				$people=get_record_from_id('people',$received_by);
				$message = "
<table>
<tr>
<td>".$people['name']." received your order ".$order['trade_name']." on ".$receive_date."</td>
</tr>
<tr>
<td><a href='http://".IP_ADDRESS."/quicklab/orders.php?id={$order['id']}' target='_blank'>Go to quicklab</a> (If it can not link to the quicklab, copy the address below.)</td>
</tr>
<tr>
<td>http://".IP_ADDRESS."/quicklab/orders.php?id={$order['id']}</td>
</tr>
</table>
";
				$mail= new Mailer();
				$mail->basicSetting();
				$mail->Subject =$subject;
				$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
				$mail->MsgHTML($css.$message);
				$mail->AddAddress($to);
				@$mail->Send();
			}
		}
		?>
<script>
window.returnValue='ok';
window.close   ();
</script>
		<?php
	}
	catch (Exception $e) {
		echo '</table><table class="alert" width = "100%"><tr><td><h3>'.$e->getMessage().'</h3></td></tr>';
	}
}

function edit()
{
	try {
		if (!filled_out(array($_REQUEST['trade_name'],$_REQUEST['dealer'],$_REQUEST['qty'],$_REQUEST['price'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$order_id=$_REQUEST['id'];
		$trade_name=$_REQUEST['trade_name'];
		$manufacturer=$_REQUEST['manufacturer'];
		$dealer=$_REQUEST['dealer'];
		$cat_nbr=$_REQUEST['cat_nbr'];
		$lot_nbr=$_REQUEST['lot_nbr'];
		$pack=$_REQUEST['pack'];
		$qty = $_REQUEST['qty'];
		$unit_price = $_REQUEST['unit_price'];
		$price = $_REQUEST['price'];
		$invoice = $_REQUEST['invoice'];
		$account_id = $_REQUEST['account_id'];
		$note = $_REQUEST['note'];
		$mask=$_REQUEST['mask'];

		$db_conn=db_connect();
		$query = "UPDATE orders SET
							trade_name='$trade_name',
				  	  manufacturer='$manufacturer',
							dealer='$dealer',
				  		cat_nbr='$cat_nbr',
				  		lot_nbr='$lot_nbr',
				 			pack='$pack',
				  		qty='$qty',
				  		unit_price='$unit_price',
				  		price='$price',
				  		invoice='$invoice',
				  		account_id='$account_id',
				  		note='$note',
				  		mask='$mask'
				  		WHERE id='$order_id'";

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
		//header("Location:".$_SESSION['url_1']);
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function order_name()
{
	if($_REQUEST['module_id']&&$_REQUEST['item_id']) {
		$module=get_record_from_id('modules',$_REQUEST['module_id']);
		$item=get_record_from_id($module['name'],$_REQUEST['item_id']);
		echo $module['name'].":".$item['name'];
	}
	if ($_REQUEST['id']) {
		$order=get_record_from_id('orders',$_REQUEST['id']);
		echo $order['trade_name'];
	}
}
function hidden_inputs($people,$date,$action)
{
	if($people!='')
	{
		echo "<input type='hidden' name='$people' value='";
		$db_conn = db_connect();
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		echo $match['people_id']."'>";
	}
	if($date!='')
	{
		echo "<input type='hidden' name=$date value='";
		echo date('Y-m-d')."'>";
	}
	echo "<input type='hidden' name='action' value='$action' >";
	echo "<input type='hidden' name='destination' value= '";
	echo $_SERVER['HTTP_REFERER']."'>";
}

function process_request()
{
	$action = $_REQUEST['action'];
	switch ($action) {
		case 'approve':approve();break;
		case 'cancel':cancel();break;
		case 'request':request();break;
		case 'receive':receive();break;
		case 'edit':edit();break;
		case 'check':check();break;
		case 'cancel_form':cancel_form();break;
		case 'request_form':request_form();break;
		case 'receive_form':receive_form();break;
		case 'edit_form':edit_form();break;
		case 'detail':detail();break;
		case 'check_form':check_form();break;
	}
}
?>