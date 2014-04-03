<?php
include('include/includes.php');
check_login_status();
?>
<?php
if ($_REQUEST['type']=='export_excel')
{
	$query=$_SESSION['query'];
	export_excel('storages',$query);
	exit;
}
?>
<?php
do_html_header('Storages operate-Quicklab');
?>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<?php
process_request();

function add_form() {
	if(!userPermission('3')) {
		alert();
	}
	?>
<script type="text/javascript" src="include/phprpc/js/compressed/phprpc_client.js"></script>
<script type="text/javascript" src="include/phprpc/cascade_select.js"></script>
<script>
rpc_cascade_select.useService('include/phprpc/cascade_select.php');
</script>
<script type="text/javascript">
$.validator.setDefaults({	submitHandler: function() {
	document.addform.action.value = "add";
	document.addform.submit();
	//window.returnValue='ok';
	//window.close   ();
}});
$(document).ready(function() {
	$("#addform").validate({
		rules: {
			name: "required",
			keeper: "required",
			S1: "required"
		},
		messages: {
			name: {required: 'required'},
			keeper: {required: 'required'},
			S1: {required: 'required'}
		}});
});
function closeSubmit() {
	window.close   ();
}
</script>
<form id='addform' name='addform' method='post' target="">
<table width='100%'>
	<?php
	//single storage from materials
	if($_REQUEST['module_id']&&$_REQUEST['item_id']) {
		echo "<tr><td colspan='2'><h3>Add new: </h3></td></tr>";
		echo "<tr><td width='20%'>Name:</td>";
  	echo "<td width='80%'><input type='text' name='name' size='40' value='";storage_name();
  	echo "'/>*</td></tr>";
	}
	//multiple storages from materials
	elseif($_REQUEST['selected_items']&&$_REQUEST['module_id']) {
		$selected_items = split(",",$_REQUEST['selected_items']);
		$num_selecteditem=count($selected_items);
		echo "<tr><td colspan='2'><h3>Store those ".$num_selecteditem." items at one location:</h3></td></tr>";
		echo "<input type='hidden' name='module_id' value='".$_REQUEST['module_id']."'/>";
		echo "<input type='hidden' name='name' value='aaa'/>";
	}
	//from orders manager
	elseif ($_REQUEST['order_id']) {
		$order=get_record_from_id('orders',$_REQUEST['order_id']);
		if ($order['module_id']&&$order['item_id']) {
		  $module=get_record_from_id('modules',$order['module_id']);
		  $item=get_record_from_id($module['name'],$order['item_id']);
		  echo "<tr><td colspan='2'><h3>Add new:</h3></td></tr>";
		  echo "<input type='hidden' name='module_id' value='".$order['module_id']."'/>";
		  echo "<input type='hidden' name='item_id' value='".$order['item_id']."'/>";
		  echo "<tr><td width='20%'>Name:</td>";
  	  echo "<td width='80%'><input type='text' name='name' size='40' value='".$module['name'].":".$item['name']."'/>*</td></tr>";
		}
		else {
			echo "<tr><td colspan='2'><h3>Add ".$order['trade_name']." to a new storage:</h3></td></tr>";
		  echo "<tr><td width='20%'>Name:</td>";
  	  echo "<td width='80%'><input type='text' name='name' size='40' value='".$order['trade_name']."'/>*</td></tr>";
		}
	}
	//from storage manager directly
	else {
	  echo "<tr><td colspan='2'><h3>Add an storage:</h3></td></tr>";
	  echo "<tr><td width='20%'>Name:</td>";
  	echo "<td width='80%'><input type='text' name='name' size='40' value=''/>*</td></tr>";
	}
	?>
<tr>
<td>Keeper:</td>
<td>
	<?php
  $db_conn = db_connect();
  $query = "select * from users where username = '{$_COOKIE['wy_user']}'";
  $result = $db_conn->query($query);
  $people=($result->fetch_assoc());
  $query= "select * from people";
	echo query_select_choose('keeper', $query,'id','name',$people['people_id']);
	?>
*</td></tr>
<tr>
<td>Location:</td>
<td>
<div id="cascade_select">
<input type='hidden' id='br' value='1'/>
</div>
</td>
<tr>
<td>Location details: </td>
<td><input type='text' name='location_details' /></td>
</tr>
<tr>
<td>Expiry date: </td>
<td><input type='text' name='date_expiry' />(YYYY-MM-DD)</td>
</tr>
<tr>
<td>Note:</td>
<td><textarea name='note' cols="40" rows="4"><?php echo stripslashes($_POST['note']) ?></textarea></td>
</tr>
<tr>
<td>State:</td>
<td><select name='state'>
<option value=1 selected>In stock</option>
<option value=0>Out of stock</option>
</select></td>
</tr>
<tr>
<td>Mask:</td>
<td>
	<?php
  $mask=array(array("0","no"),array("1","yes"));
	echo option_select('mask',$mask,2,$_REQUEST['mask']);
	?>
</td>
</tr>
<tr>
<td colspan='2'>
<input type='submit' name='Submit' value='Submit' />
<input type="button" value="Close" onclick="closeSubmit()"/>
<input type='hidden' name='order_id' value="<?php echo $_REQUEST['order_id'];?>">
<input type='hidden' name='action' value="">
</td></tr>
</table></form>
  <?php
}
function edit_form() {
	if(!userPermission('2',$storage['keeper'])) {
		alert();
	}
	$storage = get_record_from_id('storages',$_REQUEST['id']);
  ?>
<script type="text/javascript" src="include/phprpc/js/compressed/phprpc_client.js"></script>
<script type="text/javascript" src="include/phprpc/cascade_select.js"></script>
<script>
rpc_cascade_select.useService('include/phprpc/cascade_select.php');
</script>
<script type="text/javascript">
$.validator.setDefaults({	submitHandler: function() {
	document.editform.action.value = "edit";
	document.editform.submit();
	//window.returnValue='ok';
	//window.close   ();
}});
$(document).ready(function() {
	$("#editform").validate({
		rules: {
			name: "required",
			keeper: "required",
			S1: "required"
		},
		messages: {
			name: {required: 'required'},
			keeper: {required: 'required'},
			S1: {required: 'required'}
		}});
});
function closeSubmit() {
	window.close   ();
}
</script>
<form id='editform' name='editform' method='post' target="">
<table width='100%'>
<tr><td colspan='2'><h3>Edit:</h3></td>
</tr>
<tr>
<td width='20%'>Name:</td>
<td width='80%'><input type="text" name="name" size="40" value='<?php storage_name();?>'/>*</td>
</tr>
<tr>
<td>Keeper:</td>
<td>
	<?php
	$query= "select * from people";
	echo query_select_choose('keeper', $query,'id','name',$storage['keeper']);
	?>
*</td></tr>
<tr>
<td>Location:</td>
<td>
<div id="cascade_select">
<input type='hidden' id='br' value='1'/>
<input type='hidden' id='location' value='<?php echo $storage['location_id'];?>'/>
</div>
</td>
</tr>
<tr>
<td>Location details: </td>
<td><input type='text' name='location_details' value='<?php echo $storage['location_details'];?>'/></td>
</tr>
<tr>
<td>Expiry date: </td>
<td><input type='text' name='date_expiry' value='<?php echo $storage['date_expiry'];?>'/>(YYYY-MM-DD)</td>
</tr>
<tr>
<td>Note:</td>
<td><textarea name='note' cols='40' rows='4'><?php echo $storage['note'];?></textarea></td>
</tr>
<tr>
<td>State:</td>
<td>
	<?php
	$state = array( array( '1', 'In stock'),
	array( '0', 'Out of stock'));
	echo '<select name="state">';
	for ($i=0; $i < 2; $i++) {
		echo"<option value='{$state[$i][0]}'";
		if ($state[$i][0] == $storage['state']) {
			echo ' selected';
		}
		echo ">{$state[$i][1]}</option>";
	}
	echo "</select>\n";
  ?>
</td></tr>
<tr>
<td>Mask:</td>
<td>
	<?php
	$mask=array(array("0","no"),array("1","yes"));
	echo option_select('mask',$mask,2,$storage['mask']);
	?>
</td></tr>
<tr>
<td colspan='2'>
<input type='submit' name='Submit' value='Submit' />
<input type="button" value="Close" onclick="closeSubmit()"/>
<input type='hidden' name='action' value="">
</td></tr>
</table></form>
	<?php
}
function detail()
{
	if(!userPermission('4')) {
		alert();
	}
	?>
<table width="100%">
<tr><td colspan='2'><h3>storage details.</h3></td>
</tr>
<tr>
<td width='20%'>Name:</td>
<td width='80%'><?php storage_name();?></td>
</tr>
<tr>
<td>Keeper:</td>
<td>
	<?php
  $storage = get_record_from_id('storages',$_REQUEST['id']);
  $people=get_record_from_id(people,$storage['keeper']);
	echo $people['name'];
	?>
</td>
</tr>
<tr>
<td>Location:</td>
<td><?php echo getPaths($storage['location_id']);?></td>
</tr>
<tr>
<td>Location details: </td>
<td><?php echo $storage['location_details'];?></td>
</tr>
<tr>
<td>Storage date: </td>
<td><?php echo $storage['date_storage'];?></td>
</tr>
</tr>
<td>Expiry date:</td>
<td><?php echo $storage['date_expiry'];?></td>
</tr>
<tr>
<td>Note:</td>
<td><?php echo $storage['note'];?></td>
</tr>
<tr>
<td>State:</td>
<td>
	<?php
  $state = array( array( '1', 'In stock'),
						      array( '0', 'Out of stock'));
  for ($i=0; $i < 2; $i++) {
  	if ($state[$i][0] == $storage['state']) {
  		echo $state[$i][1];
  	}
  }
  ?>
</td></tr>
</table>
  <?php
}
function delete_form()
{
	if(!userPermission('2'))
	{
		echo '</table><table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
		do_footer();
		do_html_footer();
		exit;
	}
	echo "<tr><td colspan='2'><h3>Are you sure to delete the storage of ";
	storage_name();
	echo "?</h3></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />";
	hidden_inputs("delete");
	echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
	src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
}
function add()
{
	try {
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['keeper'],$_REQUEST['S1']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$keeper=$_REQUEST['keeper'];
		$location_details=$_REQUEST['location_details'];
		$date_storage=date('Y-m-d');
		$date_expiry=$_REQUEST['date_expiry'];
		$note=$_REQUEST['note'];
		$order_id=$_REQUEST['order_id'];//necessary to add from the orders manamger.
		$state=$_REQUEST['state'];
		$mask=$_REQUEST['mask'];

		//Get the location id.
		$num_select = $_REQUEST['num_select'];
		for ($i=1;$i<=$num_select;$i++) {
			if ($_REQUEST['S'.$i]!="") {
				$location_id = $_REQUEST['S'.$i];
			}
		}
		$db_conn=db_connect();
		if($_REQUEST['selected_items']&&$_REQUEST['module_id']) {
			$module_id=$_REQUEST['module_id'];
			$module=get_record_from_id('modules',$module_id);
			$selecteditemStore = split(",",$_REQUEST['selected_items']);
			$num_selecteditemStore=count($selecteditemStore);
			for($i=0;$i<$num_selecteditemStore;$i++) {
				$item=get_record_from_id($module['table'],$selecteditemStore[$i]);
				$name= $module['name'].":".$item['name'];
				$query = "insert into storages
            (name,module_id,item_id,keeper,location_id,location_details,date_storage,date_expiry,note,order_id,state,mask)
            values
            ('$name','$module_id','{$selecteditemStore[$i]}','$keeper','$location_id','$location_details','$date_storage','$date_expiry','$note','$order_id','$state','$mask')";
				$result = $db_conn->query($query);
				$storage_id=$db_conn->insert_id;
				if (!$result) {
					throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
				}
			}
		}
		else {
			$name=$_REQUEST['name'];
			$module_id=$_REQUEST['module_id'];
			$item_id=$_REQUEST['item_id'];
			$query = "insert into storages
           (name,module_id,item_id,keeper,location_id,location_details,date_storage,date_expiry,note,order_id,state)
          values
           ('$name','$module_id','$item_id','$keeper','$location_id','$location_details','$date_storage','$date_expiry','$note','$order_id','$state')";
			$result = $db_conn->query($query);
			$storage_id=$db_conn->insert_id;
			if (!$result)
			{
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		//header('Location: '.$_SESSION['url_1']);
		?>
<script>
window.returnValue='ok';
window.close   ();
</script>
		<?php
	}
	catch (Exception $e) {
		echo '</table><table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr>';
	}
}
function edit() {
	try {
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['keeper'],$_REQUEST['S1']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$id=$_REQUEST['id'];
		$name=$_REQUEST['name'];
		$keeper=$_REQUEST['keeper'];
		$location_details=$_REQUEST['location_details'];
		$date_expiry=$_REQUEST['date_expiry'];
		$note=$_REQUEST['note'];
		$state=$_REQUEST['state'];
		$mask=$_REQUEST['mask'];

		//Get the location id.
		$num_select = $_REQUEST['num_select'];
		for ($i=1;$i<=$num_select;$i++) {
			if ($_REQUEST['S'.$i]!="") {
				$location_id = $_REQUEST['S'.$i];
			}
		}
		$db_conn = db_connect();
		$query = "update storages set
		      name='$name',
		      keeper='$keeper',
				  location_id='$location_id',
				  location_details='$location_details',
				  date_expiry='$date_expiry',
				  note='$note',
				  state='$state',
				  mask='$mask'
				  where id ='$id'";

		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		//header('Location:' . $_SESSION['url_1']);
		?>
<script>
window.returnValue='ok';
window.close   ();
</script>
		<?php
	}
	catch (Exception $e) {
		echo '</table><table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr>';
	}
}
function delete()
{
	$db_conn=db_connect();
	$query = "delete from storages where id = '{$_REQUEST['id']}'";
	$result = $db_conn->query($query);
	header('Location: '.$_SESSION['url_1']);
}
function storage_name()
{
	if($_REQUEST['module_id']&&$_REQUEST['item_id']) {
		$module=get_record_from_id('modules',$_REQUEST['module_id']);
		$item=get_record_from_id($module['table'],$_REQUEST['item_id']);
		echo $module['name'].":".$item['name'];
	}
	if ($_REQUEST['id']) {
		$storage=get_record_from_id('storages',$_REQUEST['id']);
		if ($storage['name']=='') {
			$module=get_record_from_id('modules',$storage['module_id']);
		  $item=get_record_from_id($module['table'],$storage['item_id']);
		  echo $module['name'].":".$item['name'];
		}
		else {
		  echo $storage['name'];
		}
	}
}
function hidden_inputs($action)
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
function process_request() {
	$action = $_REQUEST['action'];
	switch ($action) {
		case 'add_form':add_form();break;
		case 'detail':detail();break;
		case 'edit_form':edit_form();break;
		case 'change_keeper_form':change_keeper_form();break;
		case 'change_location_form':change_location_form();break;
		case 'add':add();break;
		case 'edit':edit();break;
		case 'change_keeper':change_keeper();break;
		case 'change_location':change_location();break;
	}
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

		$query="SELECT name from people WHERE id='{$row['keeper']}'";
		$result = $db_conn->query($query);
		$keeper=$result->fetch_assoc();

		$location= getPaths($row['location_id']);

		$state_array = array( array( '0', 'Out of stock'),
		array( '1', 'In stock'));
		for ($i=0; $i < 2; $i++) {
			if ($state_array[$i][0] == $row['state']) {
				$state= $state_array[$i][1];
			}
		}
		$mask_array = array( 'no'=>'0','yes'=>'1');
		foreach ($mask_array as $key=>$value) {
			if ($value == $row['mask']) {
				$mask= $key;
			}
		}
		$xls[]= ereg_replace("[\r,\n,\t]"," ",$row['id'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$module)."\t".
		ereg_replace("[\r,\n,\t]"," ",$item)."\t".
		ereg_replace("[\r,\n,\t]"," ",$keeper['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$location)."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['location_details'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['date_storage'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['date_expiry'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['note'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$state)."\t".
		ereg_replace("[\r,\n,\t]"," ",$mask);
	}
	$title="id"."\t".
	"name"."\t".
	"module"."\t".
	"item"."\t".
	"keeper"."\t".
	"location"."\t".
	"location details"."\t".
	"date_storage"."\t".
	"date_expiry"."\t".
	"note"."\t".
	"state"."\t".
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
function change_keeper_form()
{
	if(!userPermission('3'))
	{
		alert();
	}
	?>
<script type="text/javascript">
$.validator.setDefaults({	submitHandler: function() {
	document.changekeeperform.action.value = "change_keeper";
	document.changekeeperform.submit();
}});
$(document).ready(function() {
	$("#changekeeperform").validate({
		rules: {
			keeper: "required"
		},
		messages: {
			keeper: {required: 'required'}
		}});
});
function closeSubmit() {
	window.close   ();
}
</script>
<form id='changekeeperform' name='changekeeperform' method='post' target="">
<table width='100%'>
	<?php
	$selecteditemCK=split(",",$_REQUEST['selected_items']);
	$num_selecteditemCK=count($selecteditemCK);
	?>
<tr>
<td colspan="2"><h3>Change the keeper of those <?php echo $num_selecteditemCK?> storage(s):</h3></td>
</tr>
<tr>
<td width='20%'>Keeper:</td>
<td width='80%'>
	<?php
	$userpid=get_pid_from_username($_COOKIE['wy_user']);
	$query= "select * from people";
	echo query_select_choose('keeper', $query,'id','name',$userpid);
	?>
*</td>
</tr>
<tr>
<td colspan='2'>
<input type='submit' name='Submit' value='Submit' />
<input type="button" value="Close" onclick="closeSubmit()"/>
<input type='hidden' name='action' value="">
</td></tr>
</table></form>
	<?php
}
function change_keeper()
{
	try {
		if (!filled_out(array($_REQUEST['keeper']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$userauth=check_user_authority($_COOKIE['wy_user']);
		$userpid=get_pid_from_username($_COOKIE['wy_user']);
		if($userauth>2)
		{
			$str=" AND keeper='$userpid'";
		}
		else
		{
			$str="";
		}
		$db_conn=db_connect();
		$selecteditemCK=split(",",$_REQUEST['selected_items']);
		$num_selecteditemCK=count($selecteditemCK);
		for($i=0;$i<$num_selecteditemCK;$i++)
		{
			$query = "UPDATE storages SET keeper=".$_REQUEST['keeper']." WHERE id=" . $selecteditemCK[$i] . "$str";
			$result = $db_conn->query($query);
		}
		?>
<script>
window.returnValue='ok';
window.close   ();
</script>
		<?php
	}
	catch (Exception $e) {
		echo '</table><table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr>';
	}
}
function change_location_form()
{
	if(!userPermission('3'))
	{
		alert();
	}
	?>
<script type="text/javascript" src="include/phprpc/js/compressed/phprpc_client.js"></script>
<script type="text/javascript" src="include/phprpc/cascade_select.js"></script>
<script>
rpc_cascade_select.useService('include/phprpc/cascade_select.php');
</script>
<script type="text/javascript">
$.validator.setDefaults({	submitHandler: function() {
	document.changelocationform.action.value = "change_location";
	document.changelocationform.submit();
}});
$(document).ready(function() {
	$("#changelocationform").validate({
		rules: {
			S1: "required"
		},
		messages: {
			S1: {required: 'required'}
		}});
});
function closeSubmit() {
	window.close   ();
}
</script>
<form id='changelocationform' name='changelocationform' method='post' target="">
<table width='100%'>
	<?php
	$selecteditemCL=split(",",$_REQUEST['selected_items']);
	$num_selecteditemCL=count($selecteditemCL);
	?>
<tr>
<td colspan="2"><h3>Change the location of those <?php echo $num_selecteditemCL?> storage(s):</h3></td>
</tr>
<tr>
<td width="20%">Location:</td>
<td width="80%">
<div id="cascade_select">
<input type='hidden' id='br' value='1'/>
</div>
</td>
</tr>
<tr>
<td>Location details:</td>
<td><input type='text' name='location_details'/></td>
</tr>
<tr>
<td colspan='2'>
<input type='submit' name='Submit' value='Submit' />
<input type="button" value="Close" onclick="closeSubmit()"/>
<input type='hidden' name='action' value="">
</td></tr>
</table></form>
	<?php
}
function change_location()
{
	try {
		if (!filled_out(array($_REQUEST['S1']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$userauth=check_user_authority($_COOKIE['wy_user']);
		$userpid=get_pid_from_username($_COOKIE['wy_user']);
		if($userauth>2)
		{
			$str=" AND keeper='$userpid'";
		}
		else
		{
			$str="";
		}
		//Get the location id.
		$num_select = $_REQUEST['num_select'];
		for ($i=1;$i<=$num_select;$i++) {
			if ($_REQUEST['S'.$i]!="") {
				$location_id = $_REQUEST['S'.$i];
			}
		}
		$location_details=$_REQUEST['location_details'];
		$db_conn=db_connect();
		$selecteditemCL=split(",",$_REQUEST['selected_items']);
		$num_selecteditemCL=count($selecteditemCL);
		for($i=0;$i<$num_selecteditemCL;$i++)
		{
			$query = "UPDATE storages
		SET location_id='$location_id',
		location_details='$location_details'
		WHERE id=" . $selecteditemCL[$i] . "$str";
			$result = $db_conn->query($query);
		}
	?>
<script>
window.returnValue='ok';
window.close   ();
</script>
		<?php
	}
	catch (Exception $e) {
		echo '</table><table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr>';
	}
}
?>