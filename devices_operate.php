<?php
include('include/includes.php');
?>
<?php
 if ($_REQUEST['type']=='export_excel') {
 	if(!userPermission(3)) {
 	  header('location:'.$_SESSION['url_1']);
 	}
 	$query=$_SESSION['query'];
 	export_excel('devices',$query);
 	exit;
 }
  if ($_REQUEST['type']=='import_template') {
 	import_template();
 	exit;
 }
?>
<?php
  do_html_header_begin('Devices operate-Quicklab');
?>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript">
function moveOptionToTextarea(e1, e2){
	for(var i=0;i<e1.options.length;i++){
		if(e1.options[i].selected) {
			var e = e1.options[i]
			if(e2.value=='') {
				e2.value=e.text;
			}
			else {
				e2.value+=",\r\n"+e.text;
			}
		}
	}
}
function showinsert() {
	var e1 = document.getElementById("insert");
	var e2 = document.getElementById("isinsert");
	if(e2.checked == true) {
		e1.style.display = "";
	}
	else {
		e1.style.display = "none";
	}
}
</script>
<?php
  do_html_header_end();
  do_header();
  //do_leftnav();
  process_request();
  do_rightbar();
  do_footer();
  do_html_footer();
?>
<?php
function add_form()
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
			name: "required"
		},
		messages: {
			name: {required: 'required'}
		}});
});
</script>
<form name='add_form' id="add_form" method='post' action='' target="_self">
<table width="100%" class="operate" >
  <tr><td colspan='2'><div align='center'><h2>Devices</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new:</h3></td>
	</tr>
	<tr>
		<td width='20%' >Name:</td>
		<td width='80%'><input type='text' name='name' id="name" size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
	</tr>
	<tr>
  	<td>Project:</td><td>
<?php
        $query= "select * from projects where state=1";
		echo query_select_choose('project', $query,'id','name',$_POST['project']);
?>
		</td>
  </tr>
  <tr>
		<td>Description:</td>
    <td><textarea name='description' cols="50" rows="3"><?php echo stripslashes($_POST['description']) ?></textarea></td>
	</tr>
	<tr>
  	<td>Device category:</td><td>
<?php
$query= "SELECT * FROM device_cat ORDER BY name";
echo query_select_choose('device_cat_id', $query,'id','name',$_POST['device_cat_id']);
?>
  	</td>
  </tr>
  <tr>
    <td>Manufacturer:</td>
    <td>
<?php
$query= "select id,name from sellers order by CONVERT(name USING GBK)";
echo query_select_choose('manufacturer', $query,'id','name',$_POST['manufacturer']);
?>
		&nbsp;<a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a></td>
  </tr>
  <tr>
    <td>Dealer:</td>
    <td>
<?php
$query= "select id,name from sellers order by CONVERT(name USING GBK)";
echo query_select_choose('dealer', $query,'id','name',$_POST['dealer']);
?>
		</td>
  </tr>
  <tr>
    <td>Cat. number:</td>
    <td><input type='text' name='cat_nbr' value="<?php echo stripslashes(htmlspecialchars($device['cat_nbr']))?>"/></td>
  </tr>
  <tr>
		<td>Serial number:</td>
    <td><input type='text' name='sn' value="<?php echo stripslashes(htmlspecialchars($_POST['sn']))?>"/></td>
	</tr>
	<tr>
    <td>Keeper:</td>
    <td>
<?php
$db_conn=db_connect();
$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
$result = $db_conn->query($query);
$people=($result->fetch_assoc());
$query= "select * from people ORDER BY CONVERT(name USING GBK)";
echo query_select_choose('keeper', $query,'id','name',$people['people_id']);
?>
		</td>
	</tr>
	<tr>
		<td>Warranty start date:</td>
    <td><input type='text' name='date_warranty_start' value="<?php echo stripslashes(htmlspecialchars($_POST['date_warranty_start']))?>"/></td>
	</tr>
	<tr>
		<td>Warranty end date:</td>
    <td><input type='text' name='date_warranty_end' value="<?php echo stripslashes(htmlspecialchars($_POST['date_warranty_end']))?>"/></td>
	</tr>
  <tr>
  	<td>Note:</td>
    <td><textarea name='note' cols="50" rows="3"><?php echo stripslashes($_POST['note']) ?></textarea></td>
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
<?php
//coustom fields
$query="SELECT * FROM custom_fields WHERE module_name='devices'";
$rs=$db_conn->query($query);
if ($rs->num_rows>0) {
?>
	<tr>
		<td colspan="2"><i>Custom field(s):</i></td>
	</tr>
<?php
}
while ($match=$rs->fetch_assoc() ) {
	echo "<tr><td>";
	echo $match['field_name'].":</td><td>";
	if ($match['field_typr']=="TEXT") {
		echo "<textarea name='".$match['field_id']."' cols='50' rows='3'>".stripslashes($_POST[$match['field_id']])."</textarea>";
	}
	else {
		echo "<input type='text' name='".$match['field_id']."' size='40' value='".stripslashes(htmlspecialchars($_POST[$match['field_id']]))."'/>";
	}
	echo "</td></tr>";
}
?>
	<tr>
		<td colspan='2'><input type='submit' name='Submit' value='Submit' />&nbsp;&nbsp;<a href='<?php
    echo $_SESSION['url_1'];?>'><img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
    </td>
	</tr>
	<?php hidden_inputs('created_by','date_create','add');?>
</table></form>
<?php
}

function edit_form()
{
	$device = get_record_from_id('devices',$_REQUEST['id']);
	if(!userPermission('2',$device['created_by']))
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
<form name='edit_form' id="edit_form" method='post' action='' enctype="multipart/form-data">
<table width="100%" class="operate" >
  <tr><td colspan='2'><div align='center'><h2>Devices</h2></div></td></tr>
	<tr><td colspan='2'><h3>Edit:</h3></td>
	</tr>
	<tr>
		<td width='20%'>Name:</td>
		<td width='80%'><input type='text' name='name' id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($device['name']));?>">*</td>
	</tr>
	<tr>
		<td>Project:</td><td>
<?php
$query= "select * from projects";
echo query_select_choose('project', $query,'id','name',$device['project']);
?>
		</td></tr>
	<tr>
  	<td>Description:</td>
    <td><textarea name='description' cols='50' rows='3'><?php echo $device['description'];?></textarea></td>
	</tr>
	<tr>
  	<td>Device category:</td><td>
<?php
$query= "SELECT * FROM device_cat ORDER BY name";
echo query_select_choose('device_cat_id', $query,'id','name',$device['device_cat_id']);
?>
  	</td>
  </tr>
  <tr>
    <td>Manufacturer:</td>
    <td>
<?php
$query= "select id,name from sellers order by CONVERT(name USING GBK)";
echo query_select_choose('manufacturer', $query,'id','name',$device['manufacturer']);
?>
		&nbsp;<a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a></td>
  </tr>
  <tr>
    <td>Dealer:</td>
    <td>
<?php
$query= "select id,name from sellers order by CONVERT(name USING GBK)";
echo query_select_choose('dealer', $query,'id','name',$device['dealer']);
?>
		</td>
  </tr>
  <tr>
    <td>Cat. number:</td>
    <td><input type='text' name='cat_nbr' value="<?php echo stripslashes(htmlspecialchars($device['cat_nbr']))?>"/></td>
  </tr>
  <tr>
		<td>Serial number:</td>
		<td><input type='text' name='sn' value="<?php echo stripslashes(htmlspecialchars($device['sn']));?>"></td>
	</tr>
  <tr>
    <td>Keeper:</td>
    <td>
<?php
$query= "select * from people ORDER BY CONVERT(name USING GBK)";
echo query_select_choose('keeper', $query,'id','name',$device['keeper']);
?>
		</td>
	</tr>
	<tr>
		<td>Warranty start date:</td>
		<td><input type='text' name='date_warranty_start' value="<?php echo stripslashes(htmlspecialchars($device['date_warranty_start']));?>"></td>
	</tr>
	<tr>
		<td>Warranty end date:</td>
		<td><input type='text' name='date_warranty_end' value="<?php echo stripslashes(htmlspecialchars($device['date_warranty_end']));?>"></td>
	</tr>
	<tr>
		<td>Note:</td>
		<td><textarea name='note' cols='50' rows='3'><?php echo $device['note'];?></textarea></td>
	</tr>
	<tr>
		<td>Mask:</td>
		<td>
<?php
$mask=array(array(1,'yes'),array(0,'no'));
echo option_select('mask',$mask,2,$device['mask']);
?>
		</td>
	</tr>
<?php
$db_conn=db_connect();
$query="SELECT * FROM custom_fields WHERE module_name='devices'";
$rs=$db_conn->query($query);
if ($rs->num_rows>0) {
?>
	<tr>
		<td><i>Custom field(s):</i></td>
	</tr>
<?php
}
while($match=$rs->fetch_assoc()) {
	echo "<tr><td>";
	echo $match['field_name'].":</td><td>";
	if ($match['field_typr']=="TEXT") {
		echo "<textarea name='".$match['field_id']."' cols='50' rows='3'>".$device[$match['field_id']]."</textarea>";
	}
	else {
		echo "<input type='text' name='".$match['field_id']."' size='40' value='".stripslashes(htmlspecialchars($device[$match['field_id']]))."'/>";
	}
	echo "</td></tr>";
}
?>
	<tr>
		<td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
	</tr>
	<?php hidden_inputs('updated_by','date_update','edit');?>
</table></form>
<?php
}

function edit_relation_form()
{
	$device = get_record_from_id('devices',$_REQUEST['id']);
	if(!userPermission('2',$device['created_by']))
	{
		alert();
	}
?>
  <form name="relation" method="post" action="" target="_self">
 <table width="100%" class="operate" >
  <tr><td colspan='2'><div align='center'><h2>Devices</h2></div></td></tr>
    <tr><td colspan="2"><h3>Relate device: <?php echo $device['name']; ?> to these items below:</h3></td></tr>
    <tr><td width="200">
  	<select name="clipboardtarget[]" style="width:190px;font-size:7pt;" size="5" multiple
  	ondblclick="removeOption(document.getElementById('clipboardtarget[]'))">
  	<?php
  	$db_conn = db_connect();
  	$module=get_record_from_name('modules','devices');
  	$query="SELECT * FROM items_relation
	  WHERE item_from='".$module['id']."_".$_REQUEST['id']."'";
  	$results=$db_conn->query($query);
  	while ($matches=$results->fetch_assoc())
  	{
  		$key_array=split("_",$matches['item_to']);
  		$module=get_name_from_id(modules,$key_array[0]);
  		$item=get_name_from_id($module['name'],$key_array[1]);
  		echo "<option value=".$matches['item_to'].">".$module['name'].": ".$item['name']."</option>";
  	}
	?>
  	</select></td>
  	<td align="left" valign="top">Doubleclick in the clipboard to transfer, <br>and doubleclick here to delete.</td></tr>
  	<tr><td ><input type='submit' onmouseover="allselected(document.getElementById('clipboardtarget[]'))" name='Submit' value='Submit' />
  	</td></tr>
  	<?php hidden_inputs('','','editrelation');?>
  </table></form>
<?php
}

function detail()	{
	if(!userPermission('4')) {
		alert();
	}
	$device = get_record_from_id('devices',$_REQUEST['id']);
	?>
<form name='detail_form' id="detail_form" method='post' action=''>
<table width="100%" class="operate" >
  <tr><td colspan='2'><div align='center'><h2>Devices</h2></div></td></tr>
  <tr><td colspan='2'><h3>Details:&nbsp;
    <a href="devices_operate.php?type=edit&id=<?php echo $device['id']?>"/>
    <img src='./assets/image/general/edit.gif' alt='edit' border='0'/></a></h3>
    </td>
  </tr>
  <tr>
    <td width='20%'>Name:</td>
    <td width='80%'><?php echo $device['name'];?></td>
  </tr>
  <tr>
    <td>Project:</td><td><?php
    $project= get_record_from_id('projects',$device['project']);
	  echo $project['name'];?></td>
	</tr>
  <tr>
		<td>Description:</td>
    <td><?php echo wordwrap($device['description'],70,"<br/>");?></td>
  </tr>
  <tr>
    <td>Device category:</td><td><?php
    $device_cat= get_record_from_id('device_cat',$device['device_cat_id']);
	  echo $device_cat['name'];?></td>
	</tr>
	<tr>
    <td>Manufacturer:</td><td><?php
    $manufacturer= get_record_from_id('sellers',$device['manufacturer']);
	  echo $manufacturer['name'];?></td>
	</tr>
	<tr>
    <td>Dealer:</td><td><?php
    $dealer= get_record_from_id('sellers',$device['dealer']);
	  echo $dealer['name'];?></td>
	</tr>
	<tr>
    <td>Cat. number:</td>
    <td><?php echo $device['cat_nbr'];?></td>
  </tr>
  <tr>
    <td>Serial number:</td>
    <td><?php echo $device['sn'];?></td>
  </tr>
  <tr>
    <td>Keeper:</td><td><?php
    $people= get_record_from_id('people',$device['keeper']);
	  echo $people['name'];?></td>
	</tr>
	<tr>
    <td>Warranty start date:</td>
    <td><?php echo $device['date_warranty_start'];?></td>
  </tr>
  <tr>
    <td>Warranty end date:</td>
    <td><?php echo $device['date_warranty_end'];?></td>
  </tr>
	<tr>
		<td>Note:</td>
    <td><?php echo wordwrap($device['note'],70,"<br/>");?></td>
  </tr>
  <tr>
		<td>Created by:</td><td>
<?php
$people = get_name_from_id('people',$device['created_by']);
echo $people['name'].'  '.$device['date_create'];
?>
		</td>
	</tr>
  </tr>
  <tr>
  	<td>Updated by:</td><td>
<?php
$people = get_name_from_id('people',$device['updated_by']);
echo $people['name'].'  '.$device['date_update'];
?>
    </td>
  </tr>
<?php
$db_conn=db_connect();
$query="SELECT * FROM custom_fields WHERE module_name='devices'";
$rs=$db_conn->query($query);
if ($rs->num_rows>0) {
?>
	<tr>
		<td colspan="2"><i>Custom field(s):</i></td>
	</tr>
<?php
}
while ($match=$rs->fetch_assoc()) {
	echo "<tr><td>";
	echo $match['field_name']."</td><td>";
	if ($match['field_type']=="TEXT") {
		echo wordwrap($device[$match['field_id']],70,"<br/>")."</td></tr>";
	}
	else {
		echo $device[$match['field_id']]."</td></tr>";
	}
}
?>
<?php
$module=get_record_from_name('modules','devices');
$query="SELECT * FROM items_relation
	  WHERE item_from='".$module['id']."_".$_REQUEST['id']."'";
$results=$db_conn->query($query);
$num_relateditems=$results->num_rows;
if($num_relateditems!=0)
{
	echo "<tr><td valign='top' rowspan=".$num_relateditems.">Related items:</td>";
	while ($matches=$results->fetch_assoc())
	{
		$key_array=split("_",$matches['item_to']);
		$module=get_name_from_id(modules,$key_array[0]);
		$item=get_name_from_id($module['name'],$key_array[1]);
		echo "<td>".$module['name'].": <a href='".$module['name']."_operate.php?type=detail&id=".$key_array[1]."' target='_blank'/>".
		$item['name']."</a></td></tr>";
	}
}
?>
	<tr>
		<td colspan='2'><a href='<?php echo $_SESSION['url_1'];?>'>
    <img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
    </td>
  </tr>
</table>
<?php
}

function delete_form() {
	$device = get_record_from_id('devices',$_REQUEST['id']);
	if(!userPermission('2',$device['created_by'])) {
		alert();
	}
	if(isset($_REQUEST['id'])&&$_REQUEST['id']!='') {//single delete
		$module = get_id_from_name('modules','devices');
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

    	if($relateditem_count==0&&$storage_count==0&&$order_count==0) {
    		echo "<form name='delete' method='post' action=''>";
    		echo "<table width='100%' class='operate' >
  <tr><td colspan='2'><div align='center'><h2>Devices</h2></div></td></tr>";
    		echo "<tr><td colspan='2'><h3>Are you sure to delete the device: ";
    		echo $device['name'];
    		echo "?</h3></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />";
    		hidden_inputs('','',"delete");
    		echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
    		echo "</table></form>";
    	} else {
    		echo "<table width='100%' class='operate' >
  <tr><td colspan='2'><div align='center'><h2>Devices</h2></div></td></tr>";
    		echo "<tr><td><h3>This device related to ";
    		if($relateditem_count!=0) {
    			echo "<br>".$relateditem_count." other items, ";
    		}
    		if($storage_count!=0) {
    			echo "<br>".$storage_count." storages, ";
    		}
    		if($order_count!=0) {
    			echo "<br>".$order_count." orders, ";
    		}
    		echo "<br>do not suggest to delete!</h3></td>
      </tr>
      <tr><td>
      <a href='". $_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
    		echo "</table>";
    	}
    	
	} elseif($_SESSION['selecteditemDel']) {//multiple delete
		$num_selecteditemDel=count($_SESSION['selecteditemDel']);
		echo "<form name='edit' method='post' action=''>";
		echo "<table width='100%' class='operate' >
  <tr><td colspan='2'><div align='center'><h2>Devices</h2></div></td></tr>";
		echo "<tr><td colspan='2'><h3>Are you sure to delete the $num_selecteditemDel device(s)?<br>
    device related to other items, storages, orders <br>can not be deleted.</h3></td></tr> ";
		echo "<tr><td colspan='2'><input type='submit' name='Submit' value='Submit' />";
		hidden_inputs('','',"delete");
		echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
		echo "</table></form>";
	}
}
function import_form() {
	if(!userPermission('3')) {
		alert();
	}
  ?>
<script>
function submit() {
	document.dowload.submit();
}
</script>
<form name='preview' method='post' action='' enctype="multipart/form-data">
<table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Devices</h2></div></td></tr>
<tr><td colspan='2'><h3>Import from file:</h3></td></tr>
<tr>
<td width='20%'>File:</td>
<td width='80%'><input type='file' name='file'/>*</td>
</tr>
<tr><td colspan='2'><input type='submit' name='Submit' value='Preview' /></td></tr>
</form>
<form name="download" id="download" method="POST" target="_self">
<tr><td colspan='2' >
<a href='#' onclick="submit()"><span style="font-size:10pt;">DOWNLOAD</span></a> the template (CSV file).</td></tr>
<input type="hidden" name="type" value="import_template"/>
</form>
<tr><td colspan="2"><span style="color:red">NOTE:</span></td></tr>
<tr><td colspan="2">*For the 'device_cat_id', use device categorie id (find it in the device categories module), default 0.</td></tr>
<tr><td colspan="2">*For the 'manufacturer' and 'dealer', use seller id (find it in the sellers module), default 0.</td></tr>
<tr><td colspan="2">*For the 'keeper', use people id (find it in the people module), default you.</td></tr>
<tr><td colspan="2">*For the 'project', use project id (find it in the projects module), default 'others'.</td></tr>
<tr><td colspan="2">*For the 'mask', use 0 represents no and 1 represents yes, default 0.</td></tr>
</table>
  <?php
  try {
  	if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
  		$type = basename($_FILES['file']['type']);
  		switch ($type) {
  			case 'octet-stream':
  			case 'plain':     break;
  			default:        throw new Exception('Invalid file format: '.
  			$_FILES['file']['type'].$type) ;
  		}
  	}
  	if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
  		$db_conn=db_connect();

  		$query="DROP TABLE IF EXISTS temp_devices";
  		$result=$db_conn->query($query);

  		$query="DROP TABLE IF EXISTS temp_device_sequences";
  		$result=$db_conn->query($query);

  		$query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_devices SELECT * FROM devices WHERE 0";
  		$result=$db_conn->query($query);

  		$query="ALTER TABLE temp_devices MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
  		$result=$db_conn->query($query);

  		$query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_device_sequences SELECT * FROM device_sequences WHERE 0";
  		$result=$db_conn->query($query);

  		$query="ALTER TABLE temp_device_sequences MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
  		$result=$db_conn->query($query);

			$fp=fopen($_FILES['file']['tmp_name'],"rb");

  		$date_create=date('Y-m-d');
  		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
  		$result = $db_conn->query($query);
  		$match=$result->fetch_assoc();
  		$created_by=$match['people_id'];
  		$keeper_default=$match['people_id'];
  		$n=0;
  		while ($data=fgetcsv($fp)) {
  			if ($n>0) {
  				$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
  				$description=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
  				$device_cat_id=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
  				$query="SELECT * FROM device_cat WHERE id='$device_cat_id'";
  				$result=$db_conn->query($query);
  				$match=$result->fetch_assoc();
  				if($result->num_rows==0) $device_cat_id=1;
  				$manufacturer=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
  				$query="SELECT * FROM sellers WHERE id='$manufacturer'";
  				$result=$db_conn->query($query);
  				$match=$result->fetch_assoc();
  				if($result->num_rows==0) $manufacturer=0;
  				$dealer=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
  				$query="SELECT * FROM sellers WHERE id='$dealer'";
  				$result=$db_conn->query($query);
  				$match=$result->fetch_assoc();
  				if($result->num_rows==0) $dealer=0;
  				$cat_nbr=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
  				$sn=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
  				$keeper=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
  				$query="SELECT * FROM people WHERE id='$keeper'";
  				$result=$db_conn->query($query);
  				$match=$result->fetch_assoc();
  				if($result->num_rows==0) {
  					$keeper = $keeper_default;
  				}
  				$date_warranty_start=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
  				$date_warranty_end=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
  				$project=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
  				$query="SELECT * FROM projects WHERE id='$project'";
  				$result=$db_conn->query($query);
  				$match=$result->fetch_assoc();
  				if($result->num_rows==0) $project=1;
  				$note=mb_convert_encoding(addslashes(trim($data[11])),"utf-8","gb2312");
  				$mask=mb_convert_encoding(addslashes(trim($data[12])),"utf-8","gb2312");
  				if($mask != 1) $mask=0;

  				$query_custom_fields="SELECT * FROM custom_fields WHERE module_name='devices' ORDER BY id";
  				$query = "insert into temp_devices
      		(name,description,device_cat_id,manufacturer,dealer,cat_nbr,sn,keeper,date_warranty_start,date_warranty_end,date_create,created_by,project,note,mask";
  				$rs=$db_conn->query($query_custom_fields);
  				while ($match=$rs->fetch_assoc()) {
  					$query.=",".$match['field_id'];
  				}
  				$query.=")
       		VALUES
      		('$name','$description','$device_cat_id','$manufacturer','$dealer','$cat_nbr','$sn','$keeper','$date_warranty_start','$date_warranty_end','$date_create','$created_by','$project','$note','$mask'";
  				$c=16;
  				$rs=$db_conn->query($query_custom_fields);
  				while ($match=$rs->fetch_assoc()) {
  					$query.=",'".mb_convert_encoding(addslashes(trim($data[$c])),"utf-8","gb2312")."'";;
  					$c++;
  				}
  				$query.=")";
  				$result=$db_conn->query($query);
  				$id=$db_conn->insert_id;
  				if(!$result) {
  					throw new Exception("There was a database error when executing<pre>$query</pre>") ;
  				}
  			}
  			$n++;
  		}
  		$rand=mt_rand();
  		$filename = "temp/devices_import_$rand.txt";
  		move_uploaded_file($_FILES['file']['tmp_name'],'./'.$filename);

  		$query="SELECT * FROM temp_devices";
  		$results=$db_conn->query($query);
  		$row_num=$results->num_rows;
  		if ($results  && $results->num_rows) {
      	?>
<form name='import' method='post' action=''>
<input type="hidden" name="action" value="import">
<input type="hidden" name="filename" value="<?php echo $filename ?>">
<table width="100%" class="alert"><tr><td>Totally <?php echo $row_num ?> records, check the data carefully before import!
<input type='submit' name='submit' value='import'></td></tr></table>
<table width='100%' class='results'>
<tr><td class='results_header'>name</td><td class='results_header'>
description</td><td class='results_header'>
device_cat_id*</td><td class='results_header'>
manufacturer*</td><td class='results_header'>
dealer*</td><td class='results_header'>
cat_nbr</td><td class='results_header'>
sn</td><td class='results_header'>
keeper*</td><td class='results_header'>
date_warranty_start</td><td class='results_header'>
date_warranty_end</td><td class='results_header'>
project*</td><td class='results_header'>
note</td><td class='results_header'>
mask*</td>
	    <?php
	    $query="SELECT * FROM custom_fields WHERE module_name='devices' ORDER BY id";
	    $rs=$db_conn->query($query);
	    while ($match=$rs->fetch_assoc()) {
	    	echo "<td class='results_header'><i>";
	    	echo $match['field_name'];
	    	echo "</i></td>";
	    }?>
	    </tr>
	    <?php
	    while ($matches = $results->fetch_array()) {
	    	echo "<tr>";
	    	echo "<td class='results'>".$matches['name']."</td>";
	    	echo "<td class='results'>".$matches['description']."</td>";
	    	$query="SELECT * FROM device_cat WHERE id='{$matches['device_cat_id']}'";
	    	$result=$db_conn->query($query);
	    	$device_cat=$result->fetch_assoc();
	    	echo "<td class='results'>".$device_cat['name']."</td>";
	    	$query="SELECT * FROM sellers WHERE id='{$matches['manufacturer']}'";
	    	$result=$db_conn->query($query);
	    	$manufacturer=$result->fetch_assoc();
	    	echo "<td class='results'>".$manufacturer['name']."</td>";
	    	$query="SELECT * FROM sellers WHERE id='{$matches['dealer']}'";
	    	$result=$db_conn->query($query);
	    	$dealer=$result->fetch_assoc();
	    	echo "<td class='results'>".$dealer['name']."</td>";
	    	echo "<td class='results'>".$matches['cat_nbr']."</td>";
	    	echo "<td class='results'>".$matches['sn']."</td>";
	    	$query="SELECT * FROM people WHERE id='{$matches['keeper']}'";
	    	$result=$db_conn->query($query);
	    	$keeper=$result->fetch_assoc();
	    	echo "<td class='results'>".$keeper['name']."</td>";
	    	echo "<td class='results'>".$matches['date_warranty_start']."</td>";
	    	echo "<td class='results'>".$matches['date_warranty_end']."</td>";
	    	$query="SELECT * FROM projects WHERE id='{$matches['project']}'";
	    	$result=$db_conn->query($query);
	    	$project=$result->fetch_assoc();
	    	echo "<td class='results'>".$project['name']."</td>";
	    	echo "<td class='results'>".$matches['note']."</td>";
	    	echo "<td class='results'>".$matches['mask']."</td>";
	    	$query="SELECT * FROM custom_fields WHERE module_name='devices' ORDER BY id";
	    	$rs=$db_conn->query($query);
	    	while ($match=$rs->fetch_assoc()) {
	    		echo "<td class='results'>";
	    		echo $matches[$match['field_id']];
	    		echo "</td>";
	    	}
	    	echo "</tr>";
	    }
	    echo "</table></form>";
  		}
  	}
  }
  catch (Exception $e) {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}
function add()
{
	try {
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['created_by'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$name = $_REQUEST['name'];
		$project = $_REQUEST['project'];
		$description = $_REQUEST['description'];
		$device_cat_id = $_REQUEST['device_cat_id'];
		$manufacturer = $_REQUEST['manufacturer'];
		$dealer = $_REQUEST['dealer'];
		$cat_nbr = $_REQUEST['cat_nbr'];
		$sn = $_REQUEST['sn'];
		$keeper = $_REQUEST['keeper'];
		$date_warranty_start = $_REQUEST['date_warranty_start'];
		$date_warranty_end = $_REQUEST['date_warranty_end'];
		$date_create = $_REQUEST['date_create'];
		$created_by=$_REQUEST['created_by'];
		$note=$_REQUEST['note'];
		$mask=$_REQUEST['mask'];

		$db_conn = db_connect();
		$query_custom_fields="SELECT * FROM custom_fields WHERE module_name='devices' ORDER BY id";
		$query = "insert into devices
      (name,description,device_cat_id,manufacturer,dealer,cat_nbr,sn,keeper,date_warranty_start,date_warranty_end,date_create,created_by,project,note,mask";
		$rs=$db_conn->query($query_custom_fields);
		while ($match=$rs->fetch_assoc() ) {
			$query.=",".$match['field_id'];
		}
		$query.=")
       VALUES
      ('$name','$description','$device_cat_id','$manufacturer','$dealer','$cat_nbr','$sn','$keeper','$date_warranty_start','$date_warranty_end','$date_create','$created_by','$project','$note','$mask'";
		$rs=$db_conn->query($query_custom_fields);
		while ($match=$rs->fetch_assoc() ) {
			$query.=",'".$_REQUEST[$match['field_id']]."'";
		}
		$query.=")";
		$result = $db_conn->query($query);
		$id=$db_conn->insert_id;
		if (!$result)
		{
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		header('Location: devices.php?id='.$id);
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function edit_relation()
{
	try{
		$id=$_REQUEST['id'];
		$module=get_record_from_name('modules','devices');
		$db_conn=db_connect();
		$query="DELETE FROM items_relation WHERE item_from='".$module['id']."_".$id."'";
		$result = $db_conn->query($query);
		if (!$result)
		{
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		for($i=0;$i<count($_REQUEST['clipboardtarget']); $i++)
		{
			$query="INSERT INTO items_relation
      (item_from,item_to)
      VALUES
      ('".$module['id']."_".$id."','".$_REQUEST['clipboardtarget'][$i]."')";
			$result = $db_conn->query($query);
			if (!$result)
			{
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		header("Location:".$_SESSION['url_1']);
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function edit()
{
	try {
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['updated_by'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$id=$_REQUEST['id'];
		$name = $_REQUEST['name'];
		$project = $_REQUEST['project'];
		$description = $_REQUEST['description'];
		$device_cat_id = $_REQUEST['device_cat_id'];
		$manufacturer = $_REQUEST['manufacturer'];
		$dealer = $_REQUEST['dealer'];
		$cat_nbr = $_REQUEST['cat_nbr'];
		$sn = $_REQUEST['sn'];
		$keeper = $_REQUEST['keeper'];
		$date_warranty_start = $_REQUEST['date_warranty_start'];
		$date_warranty_end = $_REQUEST['date_warranty_end'];
		$date_update = $_REQUEST['date_update'];
		$updated_by=$_REQUEST['updated_by'];
		$note=$_REQUEST['note'];
		$mask=$_REQUEST['mask'];

		$db_conn = db_connect();
		$query_custom_fields="SELECT * FROM custom_fields WHERE module_name='devices'";
		$query = "update devices SET
		    name='$name',
			description='$description',
			device_cat_id='$device_cat_id',
			manufacturer='$manufacturer',
			dealer='$dealer',
			cat_nbr='$cat_nbr',
			sn='$sn',
			keeper='$keeper',
			date_warranty_start='$date_warranty_start',
			date_warranty_end='$date_warranty_end',
			date_update='$date_update',
			updated_by='$updated_by',
			project='$project',
			note='$note',";
		$rs=$db_conn->query($query_custom_fields);
		while ($match=$rs->fetch_assoc()) {
			$query.=$match['field_id']."='".$_REQUEST[$match['field_id']]."', ";
		}
		$query.=" mask='$mask' where id='$id'";

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
function delete() {
	$db_conn=db_connect();

	if(isset($_REQUEST['id'])&&$_REQUEST['id']!='') {//single delete
		$query = "delete from devices where id = '{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
	} elseif($_SESSION['selecteditemDel']) {//multiple delete
		$selecteditemDel=$_SESSION['selecteditemDel'];
		unset($_SESSION['selecteditemDel']);
		$num_selecteditemDel=count($selecteditemDel);
		$module = get_id_from_name('modules','devices');
		for($i=0;$i<$num_selecteditemDel;$i++) {
			$query = "SELECT *
    	FROM items_relation WHERE item_from='".$module['id']."_".$selecteditemDel[$i].
    	"' OR item_to='".$module['id']."_".$selecteditemDel[$i]."'";
    	$relateditem = $db_conn->query($query);
    	$relateditem_count=$relateditem->num_rows;//check related items
    	$query = "SELECT id
    	FROM storages WHERE module_id='{$module['id']}'
        AND item_id ='{$selecteditemDel[$i]}'";
    	$storage = $db_conn->query($query);
    	$storage_count=$storage->num_rows;//check related storages
    	$query = "SELECT id
    	FROM orders WHERE module_id='{$module['id']}'
        AND item_id ='{$selecteditemDel[$i]}'";
    	$order = $db_conn->query($query);
    	$order_count=$order->num_rows;//check related orders
    	if($relateditem_count==0&&$storage_count==0&&$order_count==0) {
    		$query="SELECT map from devices where id='{$selecteditemDel[$i]}'";
    		$result = $db_conn->query($query);
    		$match=$result->fetch_assoc();
    		if($match['map']!='') {
    			unlink("./".$match['map']);
    		}
    		$query="DELETE from device_sequences where device_id='{$selecteditemDel[$i]}'";
    		$result = $db_conn->query($query);
    		$query = "delete from devices where id = '{$selecteditemDel[$i]}'";
    		$result = $db_conn->query($query);
    	}
		}
	}
	header('Location: '.$_SESSION['url_1']);
}

function import_template() {
	$fileName ='devices_import.csv';
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName");
	echo "name,";
	echo "description,";
	echo "device_cat_id*,";
	echo "manufacturer*,";
	echo "dealer*,";
	echo "cat_nbr,";
	echo "sn,";
	echo "keeper*,";
	echo "date_warranty_start,";
	echo "date_warranty_end,";
	echo "project*,";
	echo "note,";
	echo "mask*,";
	$db_conn=db_connect();
	$query="SELECT * FROM custom_fields WHERE module_name='devices' ORDER BY id";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		echo $match['field_name'].",";
	}
}

function import() {
	try {
		$filename = $_POST['filename'];
		$fp=fopen("./".$filename,"rb");
		unlink("./".$filename);

		$date_create=date('Y-m-d');
		$db_conn=db_connect();
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$created_by=$match['people_id'];
		$n=0;
		while($data=fgetcsv($fp)) {
			if ($n>0) {
				$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
				$description=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
				$device_cat_id=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
				$query="SELECT * FROM device_cat WHERE id='$device_cat_id'";
				$result=$db_conn->query($query);
				$match=$result->fetch_assoc();
				if($result->num_rows==0) $device_cat_id=1;
				$manufacturer=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
				$query="SELECT * FROM sellers WHERE id='$manufacturer'";
				$result=$db_conn->query($query);
				$match=$result->fetch_assoc();
				if($result->num_rows==0) $manufacturer=0;
				$dealer=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
				$query="SELECT * FROM sellers WHERE id='$dealer'";
				$result=$db_conn->query($query);
				$match=$result->fetch_assoc();
				if($result->num_rows==0) $dealer=0;
				$cat_nbr=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
				$sn=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
				$keeper=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
				$query="SELECT * FROM people WHERE id='$keeper'";
				$result=$db_conn->query($query);
				$match=$result->fetch_assoc();
				if($result->num_rows==0) {
					$keeper = $keeper_default;
				}
				$date_warranty_start=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
				$date_warranty_end=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
				$project=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
				$query="SELECT * FROM projects WHERE id='$project'";
				$result=$db_conn->query($query);
				$match=$result->fetch_assoc();
				if($result->num_rows==0) $project=1;
				$note=mb_convert_encoding(addslashes(trim($data[11])),"utf-8","gb2312");
				$mask=mb_convert_encoding(addslashes(trim($data[12])),"utf-8","gb2312");
				if($mask != 1) $mask=0;

				$query_custom_fields="SELECT * FROM custom_fields WHERE module_name='devices' ORDER BY id";
				$query = "insert into devices
      		(name,description,device_cat_id,manufacturer,dealer,cat_nbr,sn,keeper,date_warranty_start,date_warranty_end,date_create,created_by,project,note,mask";
				$rs=$db_conn->query($query_custom_fields);
				while ($match=$rs->fetch_assoc()) {
					$query.=",".$match['field_id'];
				}
				$query.=")
       		VALUES
      		('$name','$description','$device_cat_id','$manufacturer','$dealer','$cat_nbr','$sn','$keeper','$date_warranty_start','$date_warranty_end','$date_create','$created_by','$project','$note','$mask'";
				$c=16;
				$rs=$db_conn->query($query_custom_fields);
				while ($match=$rs->fetch_assoc()) {
					$query.=",'".mb_convert_encoding(addslashes(trim($data[$c])),"utf-8","gb2312")."'";;
					$c++;
				}
				$query.=")";
				$result=$db_conn->query($query);
				$id=$db_conn->insert_id;
				if(!$result) {
					throw new Exception("There was a database error when executing<pre>$query</pre>") ;
				}
			}
			$n++;
		}
		header('Location: '.$_SESSION['url_1']);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function hidden_inputs($people,$date,$action)
{
	if($people!=null)
	{
		echo "<input type='hidden' name='$people' value='";
		$db_conn = db_connect();
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		echo $match['people_id']."'>";
	}
	if($date!=null)
	{
		echo "<input type='hidden' name=$date value='";
		echo date('Y-m-d')."'>";
	}
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
function process_request() {
	$type = $_REQUEST['type'];
	switch ($type) {
		case "add":add_form();break;
		case "detail":detail();break;
		case "edit":edit_form();break;
		case "delete":delete_form();break;
		case "relation":edit_relation_form();break;
		case "import":import_form();break;
		default:break;
	}
	$action = $_POST['action'];
	switch ($action) {
		case "add":add();break;
		case "edit":edit();break;
		case "import":import();break;
		case "detail":detail();break;
		case "editrelation":edit_relation();break;
		case "delete":delete();break;
		default:break;
	}
}
function export_excel($module_name,$query)
{
	$db_conn=db_connect();
	$results = $db_conn->query($query);
	$num_rows=$results->num_rows;
	while($row = $results->fetch_array())
	{
		$query="SELECT name from device_cat WHERE id='{$row['device_cat_id']}'";
		$result = $db_conn->query($query);
		$device_cat=$result->fetch_assoc();
		$query="SELECT name from sellers WHERE id='{$row['manufacturer']}'";
		$result = $db_conn->query($query);
		$manufacturer=$result->fetch_assoc();
		$query="SELECT name from sellers WHERE id='{$row['dealer']}'";
		$result = $db_conn->query($query);
		$dealer=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['keeper']}'";
		$result = $db_conn->query($query);
		$keeper = $result->fetch_assoc();
		$query="SELECT name from projects WHERE id='{$row['project']}'";
		$result = $db_conn->query($query);
		$project=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['created_by']}'";
		$result = $db_conn->query($query);
		$created_by=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['updated_by']}'";
		$result = $db_conn->query($query);
		$updated_by=$result->fetch_assoc();
		$mask_array = array( 'no'=>'0','yes'=>'1');
		foreach ($mask_array as $key=>$value) {
			if ($value == $row['mask']) {
				$mask= $key;
			}
		}

		$data= ereg_replace("[\r,\n,\t]"," ",$row['id'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['description'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$device_cat['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$manufacturer['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$dealer['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['cat_nbr'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['sn'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$keeper['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['date_warranty_start'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['date_warranty_end'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$created_by['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['date_create'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$updated_by['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['date_update'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$project['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['note'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$mask);
		$query="SELECT * FROM custom_fields WHERE module_name='devices' ORDER BY id";
		$rs=$db_conn->query($query);
		while ($match=$rs->fetch_assoc()) {
			$data.="\t".$row[$match['field_id']];
		}
		$xls[]=$data;
	}
	$title="id"."\t".
	"name"."\t".
	"description"."\t".
	"device_cat"."\t".
	"manufacturer"."\t".
	"dealer"."\t".
	"cat_nbr"."\t".
	"sn"."\t".
	"keeper"."\t".
	"date_warranty_start"."\t".
	"date_warranty_end"."\t".
	"created_by"."\t".
	"date_create"."\t".
	"updated_by"."\t".
	"date_update"."\t".
	"project"."\t".
	"note"."\t".
	"mask";
	$query="SELECT * FROM custom_fields WHERE module_name='devices' ORDER BY id";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$title.="\t".$match['field_name'];
	}

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