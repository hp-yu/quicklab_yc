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
 	export_excel('maintenance',$query);
 	exit;
 }
?>
<?php
  do_html_header('Device maitenance-Quicklab');
  do_header();
  //do_leftnav();
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
	<tr><td colspan='2'><div align='center'><h2>Device maitenance</h2></div></td></tr>
<?php
	processRequest();
}
function addform()
{
  if(!userPermission('3'))
  {
  	alert();
  }
  ?>
<form name='add' method='post' action=''>
  <tr>
    <td colspan="2"><h3>Add:</h3></td>
  </tr>
  <tr>
   	<td width="20%">Device:</td>
<?php
$db_conn=db_connect();
$query="SELECT * FROM devices WHERE id='{$_REQUEST['device_id']}'";
$rs=$db_conn->query($query);
$match=$rs->fetch_assoc();
?>
    <td width="80%"><?php echo stripslashes(htmlspecialchars($match['name']))?>
    <input type="hidden" name="name" value="<?php echo $match['name']?>"/>
    <input type="hidden" name="device_id" value="<?php echo $_REQUEST['device_id']?>"/>
    </td>
  </tr>
  <tr>
		<td>Description:</td>
    <td><textarea name='description' cols="50" rows="3"><?php echo stripslashes($_POST['description']) ?></textarea></td>
	</tr>
  <tr>
    <td>Operator:</td>
    <td>
<?php
$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
$result = $db_conn->query($query);
$people=($result->fetch_assoc());
$query= "select * from people ORDER BY CONVERT(name USING GBK)";
echo query_select_choose('operator', $query,'id','name',$people['people_id']);
?>
		*</td>
	</tr>
	<tr>
    <td>Company:</td>
    <td>
<?php
$query= "select id,name from sellers order by CONVERT(name USING GBK)";
echo query_select_choose('company', $query,'id','name',$match['manufacturer']);
?>
		&nbsp;<a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a></td>
  </tr>
  <tr>
		<td>Price:</td>
    <td><input type='text' name='price' value="<?php echo stripslashes(htmlspecialchars($_POST['price']))?>"/></td>
	</tr>
	<tr>
		<td>Date of maintenance:</td>
    <td><input type='text' name='date_maintenance' value="<?php echo date("Y-m-d")?>"/>*</td>
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
  <tr>
    <td colspan="2"><input type="submit" name="Submit" value="Submit" />
    <a href='<?php echo $_SESSION['url_1'];?>'>
    <img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
  </tr>
<?php HiddenInputs('created_by','date_create','add');?>
  </form></table>
<?php
}
function editform() {
  if(!userPermission('3')) {
  	alert();
  }
  $maintenance = get_record_from_id('maintenance',$_REQUEST['id']);
?>
<form name='edit' method='post' action=''>
	<tr>
		<td colspan="2"><h3>Edit:</h3></td>
	</tr>
	<tr>
		<td width="20%">Device: </td>
<?php
$db_conn=db_connect();
$query="SELECT * FROM devices WHERE id='{$maintenance['device_id']}'";
$rs=$db_conn->query($query);
$device=$rs->fetch_assoc();
?>
    <td width="80%"><?php echo stripslashes(htmlspecialchars($device['name']))?>
    </td>
  </tr>
  <tr>
  	<td>Description:</td>
    <td><textarea name='description' cols='50' rows='3'><?php echo $maintenance['description'];?></textarea></td>
	</tr>
  <tr>
    <td>Operator:</td>
    <td>
<?php
$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
$result = $db_conn->query($query);
$people=($result->fetch_assoc());
$query= "select * from people ORDER BY CONVERT(name USING GBK)";
echo query_select_choose('operator', $query,'id','name',$maintenance['operator']);
?>
		*</td>
	</tr>
	<tr>
    <td>Company:</td>
    <td>
<?php
$query= "select id,name from sellers order by CONVERT(name USING GBK)";
echo query_select_choose('company', $query,'id','name',$maintenance['company']);
?>
		&nbsp;<a href="sellers_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add sellers' border='0'/></a></td>
  </tr>
  <tr>
		<td>Price:</td>
    <td><input type='text' name='price' value="<?php echo stripslashes(htmlspecialchars($maintenance['price']))?>"/></td>
	</tr>
	<tr>
		<td>Date of maintenance:</td>
    <td><input type='text' name='date_maintenance' value="<?php echo stripslashes(htmlspecialchars($maintenance['date_maintenance']))?>"/>*</td>
	</tr>
	<tr>
		<td>Note:</td>
		<td><textarea name='note' cols='50' rows='3'><?php echo $maintenance['note'];?></textarea></td>
	</tr>
	<tr>
		<td>Mask:</td>
		<td>
<?php
$mask=array(array(1,'yes'),array(0,'no'));
echo option_select('mask',$mask,2,$maintenance['mask']);
?>
		</td>
	</tr>
  <tr>
  	<td colspan="2">
  	<input type="submit" name="Submit" value="Submit" />
    <a href='<?php echo $_SESSION['url_1'];?>'>
    <img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
    </td>
  </tr>
<?php HiddenInputs('updated_by','date_update','edit');?>
</form></table>
<?php
}
function Detail()	{
	if(!userPermission('4')) {
		alert();
	}
	$maintenance = get_record_from_id('maintenance',$_REQUEST['id']);
	?>
  <tr><td colspan='2'><h3>Details:&nbsp;
    <a href="maintenance_operate.php?type=edit&id=<?php echo $maintenance['id']?>"/>
    <img src='./assets/image/general/edit.gif' alt='edit' border='0'/></a></h3>
    </td>
  </tr>
  <tr>
    <td width='20%'>Device:</td>
<?php
$db_conn=db_connect();
$query="SELECT * FROM devices WHERE id='{$maintenance['device_id']}'";
$rs=$db_conn->query($query);
$device=$rs->fetch_assoc();
?>
    <td width='80%'><?php echo $device['name'];?></td>
  </tr>
  <tr>
		<td>Description:</td>
    <td><?php echo wordwrap($maintenance['description'],70,"<br/>");?></td>
  </tr>
  <tr>
    <td>Operator:</td><td><?php
    $people= get_record_from_id('people',$maintenance['operator']);
	  echo $people['name'];?></td>
	</tr>
	<tr>
    <td>Price:</td>
    <td><?php echo $maintenance['price'];?></td>
  </tr>
	<tr>
    <td>Company:</td><td><?php
    $company= get_record_from_id('sellers',$maintenance['company']);
	  echo $company['name'];?></td>
	</tr>
	<tr>
    <td>Maintenance date:</td>
    <td><?php echo $maintenance['date_maintenance'];?></td>
  </tr>
	<tr>
		<td>Note:</td>
    <td><?php echo wordwrap($maintenance['note'],70,"<br/>");?></td>
  </tr>
  <tr>
		<td>Created by:</td><td>
<?php
$people = get_name_from_id('people',$maintenance['created_by']);
echo $people['name'].'  '.$maintenance['date_create'];
?>
		</td>
	</tr>
  </tr>
  <tr>
  	<td>Updated by:</td><td>
<?php
$people = get_name_from_id('people',$maintenance['updated_by']);
echo $people['name'].'  '.$maintenance['date_update'];
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
function add() {
	$name = $_REQUEST['name'];
	$description = $_REQUEST['description'];
	$device_id = $_REQUEST['device_id'];
	$operator = $_REQUEST['operator'];
	$company = $_REQUEST['company'];
	$price = $_REQUEST['price'];
	$date_maintenance = $_REQUEST['date_maintenance'];
	$note = $_REQUEST['note'];
	$mask=$_REQUEST['mask'];
	$date_create = $_REQUEST['date_create'];
	$created_by=$_REQUEST['created_by'];

	try {
		if (!filled_out(array($_REQUEST['operator'],$_REQUEST['date_maintenance']))) {
			throw new Exception('You have not filled the form out correctlly,</br>'
			.'- please try again.');
		}
		$db_conn = db_connect();
		$query = "insert into maintenance (`name`,`description`,`device_id`,`operator`,`company`,`price`,`date_maintenance`,`note`,`mask`,`date_create`,`created_by`) values ('$name','$description','$device_id','$operator','$company','$price','$date_maintenance','$note','$mask','$date_create','$created_by')";
		$result = $db_conn->query($query);
		$id=$db_conn->insert_id;
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		header('Location: '.$_SESSION['url_1']);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function Edit() {
	$id = $_REQUEST['id'];
	$description = $_REQUEST['description'];
	$operator = $_REQUEST['operator'];
	$company = $_REQUEST['company'];
	$price = $_REQUEST['price'];
	$date_maintenance = $_REQUEST['date_maintenance'];
	$note = $_REQUEST['note'];
	$mask=$_REQUEST['mask'];
	$date_update = $_REQUEST['date_update'];
	$updated_by=$_REQUEST['updated_by'];
	try {
		if (!filled_out(array($_REQUEST['operator'],$_REQUEST['date_maintenance']))) {
			throw new Exception('You have not filled the form out correctlly,</br>'
			.'- please try again.');
		}
		$db_conn=db_connect();
		$query = "update maintenance
		set `description`='$description',
		`operator`='$operator',
		`company`='$company',
		`price`='$price',
		`date_maintenance`='$date_maintenance',
		`note`='$note',
		`mask`='$mask',
		`date_update`='$date_update',
		`updated_by`='$updated_by'
		where id='$id'";

		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>
      	please try again.");
		}
		header('Location: '.$_SESSION['url_1']);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function HiddenInputs($people,$date,$action) {
	if($people!=null) {
		echo "<input type='hidden' name='$people' value='";
		$db_conn = db_connect();
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		echo $match['people_id']."'>";
	}
	if($date!=null) {
		echo "<input type='hidden' name=$date value='";
		echo date('Y-m-d')."'>";
	}
	echo "<input type='hidden' name='action' value='$action' >";
}
function processRequest()	{
	$type = $_REQUEST['type'];
	switch ($type) {
		case "add":AddForm();break;
		case "detail":Detail();break;
		case "edit":EditForm();break;
		case "delete":DeleteForm();break;
		case "relation":EditRelationForm();break;
		case "import":ImportForm();break;
		default:break;
	}
	$action = $_POST['action'];
	switch ($action) {
		case "add":Add();break;
		case "edit":Edit();break;
		case "import":Import();break;
		case "detail":Detail();break;
		case "editrelation":EditRelation();break;
		case "delete":Delete();break;
		default:break;
	}
}
function export_excel($module_name,$query)
{
  $db_conn=db_connect();
  $results = $db_conn->query($query);
  $num_rows=$results->num_rows;
  while($row = $results->fetch_array()) {
    $xls[]= $row['id']."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['name']);
  }
  $title="id"."\t".
  "name";

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

