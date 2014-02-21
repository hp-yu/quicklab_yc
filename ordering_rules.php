<?php
include('include/includes.php');
?>
<?php
do_html_header('Ordering rules-Quicklab');
do_header();
do_leftnav();
?>
<?php
if(!userPermission(1)){
	echo '<table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
	do_rightbar();
	do_footer();
	do_html_footer();
	exit;
}
?> 

<?php
$action = $_REQUEST['action'];
switch ($action) {
	case 'approve':Approve();break;
	case 'add':Add();break;
	case 'mail':reminderMail();break;
	case 'edit':Edit();break;
	case 'delete':Delete();break;
}
?>
<script>
function submitDelete(f) {
	var confirmVal
	confirmVal = confirm("Are you sure to delete this order administrator?")
	if (!confirmVal) {
		return
	}
	else {
		f.action.value="delete";
		f.submit();
	}
}
</script>
<table width="100%" class="standard">
  <tr>
	  <td colspan="2" align="center"><h2>Ordering rules</h2></td>
	</tr>
<form id="approve" method="post" target="_self">
	<tr>
		<td colspan="2"><b><i>Approval:</i></b></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="checkbox" name="IsApprove"
		<?php 
		$db_conn=db_connect();
		$query="SELECT * FROM `orders_approve` WHERE `key`='is_approve'";
		$result=$db_conn->query($query);
		$match=$result->fetch_assoc();
		if ($match['value']==1) {
			echo " checked";
		}
		?>
		/>&nbsp;&nbsp;Orders need to be approved, price limit >= <input type='text' name="lowerLimit" size=5 value="<?php
		$query="SELECT * FROM `orders_approve` WHERE `key`='lower_limit'";
		$result=$db_conn->query($query);
		$match=$result->fetch_assoc();
		echo $match['value'];
		?>"/></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="submit" value=" Submit "/>
		</td>
	</tr>
	<input type="hidden" name="action" value="approve"/>
</form>
<form id="mail" method="post" target="_self">
	<tr>
		<td colspan="2"><b><i>Reminder mail setting:</i></b></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="checkbox" name="IsRequestMail"
		<?php 
		$query="SELECT * FROM `orders_mails` WHERE `key`='is_request_mail'";
		$result=$db_conn->query($query);
		$match=$result->fetch_assoc();
		if ($match['value']==1) {
			echo " checked";
		}
		?>
		/>&nbsp;&nbsp;Send reminder mail to the orders administrator after the order is requested.
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="checkbox" name="IsApproveMail"
		<?php 
		$query="SELECT * FROM `orders_mails` WHERE `key`='is_approve_mail'";
		$result=$db_conn->query($query);
		$match=$result->fetch_assoc();
		if ($match['value']==1) {
			echo " checked";
		}
		?>
		/>&nbsp;&nbsp;Send reminder mail to the people who requested this order after the order is approved.
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="checkbox" name="IsReceiveMail"
		<?php 
		$query="SELECT * FROM `orders_mails` WHERE `key`='is_receive_mail'";
		$result=$db_conn->query($query);
		$match=$result->fetch_assoc();
		if ($match['value']==1) {
			echo " checked";
		}
		?>
		/>&nbsp;&nbsp;Send mail to the people who requested this order after the product is received by other people.
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="submit" value=" Submit "/>
		</td>
	</tr>
	<input type="hidden" name="action" value="mail"/>
</form>
	<tr>
		<td colspan="2">
		<b><i>Add order administrator:</b></i>
		</td>
	</tr>
<form name="adminAdd" method="POST" target="_self"/>
	<tr>
		<td colspan="2">&nbsp;&nbsp;
		<?php
		$query="select id,name from people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
  	echo query_select_choose("admin",$query,id,name,"");
		?>
		&nbsp;&nbsp;Price limit: >= <input type='text' name=lowerLimit size=5 value='0'/>, <= <input type='text' name=upperLimit size=5 />&nbsp;&nbsp;
		<input type="submit" value=" Add "/>
		</td>
	</tr>
	<input type="hidden" name="action" value="add"/>
	</form>
	<tr>
		<td colspan="2">
		<b><i>Edit order administrator:</b></i>
		</td>
	</tr>
	<?php
		$query="SELECT * FROM orders_admin";
		$result=$db_conn->query($query);
		while($match=$result->fetch_assoc()) {
	?>
	<form name="adminEdit" method="POST" target="_self"/>
	<tr>
		<td colspan="2">&nbsp;&nbsp;
	<?php
		$query="select id,name from people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
  	echo query_select_choose("admin",$query,id,name,$match['people_id']);
	?>
		&nbsp;&nbsp;Price limit: >= <input type='text' name=lowerLimit size=5 value='<?php echo $match['lower_limit'] ?>'/>, <= <input type='text' name=upperLimit size=5 value="<?php echo $match['upper_limit'] ?>"/>&nbsp;&nbsp;
  	<input type="submit" value=" Edit "/>
  	<input type="button" value=" Delete " onclick="submitDelete(this.form)"/>
  	</td>
	</tr>
	<input type="hidden" name="id" value="<?php echo $match['id']?>"/>	
	<input type="hidden" name="action" value="edit"/>	
	</form>
	<?php
		}
	?>
</table>

<?php
function Add() {
	try {
		if (!filled_out(array($_REQUEST['admin']))) {
			throw new Exception("You have not filled the form correctly, please try again.");
		}
		$admin=$_REQUEST['admin'];
		$lowerLimit=$_REQUEST['lowerLimit'];
		$upperLimit=$_REQUEST['upperLimit'];
		if (!is_numeric($lowerLimit)||$lowerLimit<0) {
			$lowerLimit=0;
		}
		if (!is_numeric($upperLimit)||$upperLimit<=0) {
			$upperLimit="";
		}
		$db_conn=db_connect();
		$query="INSERT INTO orders_admin (`people_id`,`lower_limit`,`upper_limit`) VALUES ('$admin','$lowerLimit','$upperLimit')";
		$result=$db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function Delete() {
	try {
		$id=$_REQUEST['id'];
		$db_conn=db_connect();
		$query="DELETE FROM orders_admin WHERE id=$id";
		$result=$db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function Edit() {
	try {
		if (!filled_out(array($_REQUEST['admin']))) {
			throw new Exception("You have not filled the form correctly, please try again.");
		}
		$id=$_REQUEST['id'];
		$admin=$_REQUEST['admin'];
		$lowerLimit=$_REQUEST['lowerLimit'];
		$upperLimit=$_REQUEST['upperLimit'];
		if (!is_numeric($lowerLimit)||$lowerLimit<0) {
			$lowerLimit=0;
		}
		if (!is_numeric($upperLimit)||$upperLimit<=0) {
			$upperLimit="";
		}
		$db_conn=db_connect();
		$query="UPDATE orders_admin SET people_id='$admin', lower_limit='$lowerLimit', upper_limit='$upperLimit' WHERE id=$id";
		$result=$db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function reminderMail() {
	try {
		$is_request_mail=$_REQUEST['IsRequestMail'];
		$is_approve_mail=$_REQUEST['IsApproveMail'];
		$is_receive_mail=$_REQUEST['IsReceiveMail'];
		$db_conn=db_connect();
		if ($is_request_mail==true) {
			$query="UPDATE orders_mails SET `value`=1 WHERE `key`='is_request_mail'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		else {
			$query="UPDATE orders_mails SET `value`=0 WHERE `key`='is_request_mail'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		if ($is_approve_mail==true) {
			$query="UPDATE orders_mails SET `value`=1 WHERE `key`='is_approve_mail'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		else {
			$query="UPDATE orders_mails SET `value`=0 WHERE `key`='is_approve_mail'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		if ($is_receive_mail==true) {
			$query="UPDATE orders_mails SET `value`=1 WHERE `key`='is_receive_mail'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		else {
			$query="UPDATE orders_mails SET `value`=0 WHERE `key`='is_receive_mail'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function Approve() {
	try {
		$is_approve=$_REQUEST['IsApprove'];
		$lowerLimit=$_REQUEST['lowerLimit'];
		if (!is_numeric($lowerLimit)||$lowerLimit<0) {
			$lowerLimit=0;
		}
		$db_conn=db_connect();
		if ($is_approve==true) {
			$query="UPDATE orders_approve SET `value`=1 WHERE `key`='is_approve'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
			$query="UPDATE orders_approve SET `value`=$lowerLimit WHERE `key`='lower_limit'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		else {
			$query="UPDATE orders_approve SET `value`=0 WHERE `key`='is_approve'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
			$query="UPDATE orders_approve SET `value`=$lowerLimit WHERE `key`='lower_limit'";
			$result=$db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
?>

<?php
do_rightbar();
do_footer();
do_html_footer();
?>

