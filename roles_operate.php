<?php
include('include/includes.php');
?>
<?php
do_html_header('Users-Quicklab');
process_request();
do_html_footer();
?>
<?php
function add_form()
{
	if(!userPermission(1))
	{
		alert();
	}
  ?>
<script type="text/javascript" src="include/js/moveoptions.js"></script>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<script>
function close_submit() {
	window.close   ();
}
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
<form name="add_form" id="add_form" method="POST" target="">
<table>
<tr>
<td colspan="2"><h3>Add new: </h3></td>
</tr>
<tr>
<td width="20%">Role name:</td>
<td width="80%"><input type="text" name="name" id="name" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/></td>
</tr>
<tr>
<td>Description:</td>
<td><textarea name="description" id="description" cols="50" rows="3"><?php echo stripslashes($_POST['description']) ?></textarea></td>
</tr>
<tr>
<td>Permissions </br>inherit from:</td>
<td>
	<?php
	$query="SELECT id,name FROM rbac_roles ORDER BY id";
	echo query_select_choose('inherit',$query,'id','name',$_REQUEST['inherit']);
	?>
</td>
</tr>
<td colspan="2">
<input type="submit" value="Submit" name="Submit"/ >&nbsp;&nbsp;
<input type="button" value="Close" onclick="close_submit()"/>
<input type="hidden" name="action" value="add"/>
</td></tr>
</table></form>
  <?php
}
function add_permission_form()
{
	if(!userPermission(1))
	{
		alert();
	}
	$role = get_record_from_id('rbac_roles',$_REQUEST['id']);
	?>
<script>
function close_submit() {
	window.close   ();
}
</script>
<script type="text/javascript" src="include/js/moveoptions.js"></script>
<form name="add_permission_form" id="add_permission_form" method="post" target="">
<table>
<tr>
<td colspan="2"><h3>Add permissions:</h3></td>
</tr>
<tr>
<td width='20%'>Role name:</td>
<td width='80%'><?php echo stripslashes(htmlspecialchars($role['name']));?></td>
</tr>
<tr>
<td>Domain:</td>
<td>
	<?php
	$db_conn=db_connect();
	$query="SELECT * FROM rbac_domains ORDER BY name";
	$rs=$db_conn->query($query);
	$left_content=array();
	$right_content=array();
	while ($match=$rs->fetch_assoc()) {
		$left_content[$match['id']]=$match['name'];
	}
	echo move_options_table(array('name'=>'all_domains[]','width'=>120,'size'=>10,'content'=>$left_content),array('name'=>'selected_domains[]','width'=>120,'size'=>10,'content'=>$right_content));
	?>
</td>
</tr>
<tr>
<td>Privileges:</td>
<td>
	<?php
	$query="SELECT * FROM rbac_privileges ORDER BY id";
	$rs=$db_conn->query($query);
	$left_content=array();
	$right_content=array();
	while ($match=$rs->fetch_assoc()) {
		$left_content[$match['id']]=$match['name'];
	}
	echo move_options_table(array('name'=>'all_privileges[]','width'=>120,'size'=>8,'content'=>$left_content),array('name'=>'selected_privileges[]','width'=>120,'size'=>8,'content'=>$right_content));
	?>
</td>
</tr>
<tr>
<td>Restriction:</td>
<td>
	<?php
	$res=array('all'=>0,'own'=>1);
	echo array_select('res',$res,'');
	?>
</td>
</tr>
<tr>
<td colspan="2">
<input type="submit" value="Submit" name="Submit" onmouseover="select_all_options('selected_domains[]'),select_all_options('selected_privileges[]')"/>&nbsp;&nbsp;
<input type="button" value="Close" onclick="close_submit()"/>
<input type="hidden" name="action" value="add_permission"/>
</td></tr>
</table></form>
	<?php
}

function detail(){
	if(!userPermission(1))
	{
		alert();
	}
	$id=$_REQUEST['id'];
	$role = get_record_from_id('rbac_roles',$id);
	?>
<script>
function close_submit() {
	window.close   ();
}
</script>
<form name='edit_form' method='post' target="">
<table>
<tr>
<td colspan="2"><h3>Details:</h3></td>
</tr>
<tr>
<td width='20%'>Name:</td>
<td width='80%'><?php echo $role['name'];?></td>
</tr>
<tr>
<td width='20%'>Description:</td>
<td width='80%'><?php echo $role['description'];?></td>
</tr>
<tr>
	<?php
	$db_conn=db_connect();
	$query="SELECT COUNT(*) AS num_rows FROM rbac_roles_domains_privileges WHERE `role_id`='$id'";
	$result=$db_conn->query($query);
	$match=$result->fetch_assoc();
	?>
<td>Permissions:</br>(<?php echo $match['num_rows'] ?>)</td>
<td>
<select name="permissions[]" id="permissions[]" style="width:200px;" size="10" multiple>
	<?php
	$query="SELECT a.id as privilege_id,a.name as privilege_name,b.id as domain_id,b.name as domain_name,c.is_res_own as is_res_own FROM rbac_privileges a,rbac_domains b,rbac_roles_domains_privileges c WHERE a.id=c.privilege_id AND b.id=c.domain_id AND c.role_id='$id' ORDER BY b.name,a.id";
	$rs=$db_conn->query($query);
	$res=array(all,own);
	while ($match=$rs->fetch_assoc()) {
		echo "<option value='".$match['domain_id'].",".$match['privilege_id'].",".$match['is_res_own']."'>".$match['domain_name'].",".$match['privilege_name'].",".$res[$match['is_res_own']]."</option>";
	}
 	?>
</select>
</td>
</tr>
<tr>
<td colspan="2">
<input type="button" value="Close" onclick="close_submit()"/>
</td></tr>
</table>
	<?php
}

function edit_permission_form()
{
	if(!userPermission(1))
	{
		alert();
	}
	$id=$_REQUEST['id'];
	$role = get_record_from_id('rbac_roles',$id);
	?>
<script>
function close_submit() {
	window.close   ();
}
function   add_permission (id) {
	var   obj2   =   new   Object();
	var   b=window.showModalDialog("roles_operate.php?action=add_permission_form&id="+id,obj2   ,"dialogWidth=480px;dialogHeight=480px");
	if   (b=="ok") {
		reload.click();
	}
}
</script>
<script type="text/javascript" src="include/js/moveoptions.js"></script>
<form name='edit_permission_form' id="edit_permission_form" method='post' target="">
<table>
<tr>
<td><a id="reload" href="roles_operate.php?action=edit_permission_form&id=<?php echo $_REQUEST['id'] ?>" style="display:none">reload...</a></td>
</tr>
<tr>
<td colspan="2"><h3>Edit permission:</h3></td>
</tr>
<tr>
<td width='20%'>Name:</td>
<td width='80%'><?php echo stripslashes(htmlspecialchars($role['name']));?></td>
</tr>
<tr>
	<?php
	$db_conn=db_connect();
	$query="SELECT COUNT(*) AS num_rows FROM rbac_roles_domains_privileges WHERE `role_id`='$id'";
	$result=$db_conn->query($query);
	$match=$result->fetch_assoc();
	?>
<td>Permissions:</br>(<?php echo $match['num_rows']?>)</td>
<td>
<table>
<tr>
<td><a onclick="add_permission('<?php echo $_REQUEST['id'] ?>')" style="cursor:pointer"/>add&nbsp;&nbsp;
<a onclick="remove_options('permissions[]')" style="cursor:pointer"/>remove</a></td>
</tr>
<tr>
<td>
<select name="permissions[]" id="permissions[]" style="width:200px;" size="10" multiple ondblclick="remove_options('permissions[]')">
	<?php
	$query="SELECT a.id as privilege_id,a.name as privilege_name,b.id as domain_id,b.name as domain_name,c.is_res_own as is_res_own FROM rbac_privileges a,rbac_domains b,rbac_roles_domains_privileges c WHERE a.id=c.privilege_id AND b.id=c.domain_id AND c.role_id='$id' ORDER BY b.name,a.id";
	$rs=$db_conn->query($query);
	$res=array(all,own);
	while ($match=$rs->fetch_assoc()) {
		echo "<option value='".$match['domain_id'].",".$match['privilege_id'].",".$match['is_res_own']."'>".$match['domain_name'].",".$match['privilege_name'].",".$res[$match['is_res_own']]."</option>";
	}
 	?>
</select>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2">
<input type="submit" value="Submit" name="Submit" onmouseover="select_all_options('permissions[]')"/ >&nbsp;&nbsp;
<input type="button" value="Close" onclick="close_submit()"/>
<input type="hidden" name="action" value="edit_permission"/>
</td></tr>
</table></form>
	<?php
}

function edit_form()
{
	if(!userPermission(1))
	{
		alert();
	}
	$role = get_record_from_id('rbac_roles',$_REQUEST['id']);
	?>
<script type="text/javascript" src="include/js/moveoptions.js"></script>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<script>
function close_submit() {
	window.close   ();
}
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
<form name='edit_form' id="edit_form" method='post' target="">
<table>
<tr>
<td colspan="2"><h3>Edit:</h3></td>
</tr>
<tr>
<td width='20%'>Name:</td>
<td width='80%'><input type='text' name='name' id="name" value="<?php echo $role['name']?>"/></td>
</tr>
<tr>
<td>Description:</td>
<td><textarea name='description' id="description" cols='50' rows='3'><?php echo $role['description'];?></textarea></td>
</tr>
<tr>
<td colspan="2">
<input type="submit" value="Submit" name="Submit"/ >&nbsp;&nbsp;
<input type="button" value="Close" onclick="close_submit()"/>
<input type="hidden" name="action" value="edit"/>
</td></tr>
</table></form>
	<?php
}

function delete_form()
{
	if(!userPermission(1))
	{
		alert();
	}
	$role = get_record_from_id('rbac_roles',$_REQUEST['id']);
  ?>
<script>
function close_submit() {
	window.close   ();
}
</script>
<form name="delete_form" id="delete_form" method="POST" target="">
<table>
<tr>
<td colspan="2"><h3>Delete? </h3></td>
</tr>
<tr>
<td width="20%">Role name:</td>
<td width="80%"><?php echo stripslashes(htmlspecialchars($role['name']));?></td>
</tr>
<tr>
<td colspan="2">
	<?php
	$db_conn=db_connect();
	$query="SELECT COUNT(*) AS num_rows FROM rbac_users_roles WHERE `role_id`='{$_REQUEST['id']}'";
	$result=$db_conn->query($query);
	$match=$result->fetch_assoc();
	if ($match['num_rows']>0) {
		?>
<span style='color:red'>The role was related with users, can not been deleted.
</td>
</tr>
<tr>
<td colspan="2">
<input type="button" value="Close" onclick="close_submit()"/>
		<?php
	} else {
		?>
<input type="submit" value="Submit" name="Submit"/ >&nbsp;&nbsp;
<input type="button" value="Close" onclick="close_submit()"/>
<input type="hidden" name="action" value="delete"/>
		<?php
	}
	?>
</td></tr>
</table></form>
  <?php
}
function add()
{
	if (!filled_out(array($_REQUEST['name']))) {
		return ;
	}
	$name=$_REQUEST['name'];
	$description=$_REQUEST['description'];
	$inherit=$_REQUEST['inherit'];
	$db_conn=db_connect();
	$db_conn->autocommit(false);
	$query="INSERT INTO rbac_roles (`name`,`description`) VALUES ('$name','$description')";
	$result=$db_conn->query($query);
	$insert_id=$db_conn->insert_id;
	if ($inherit!="") {
		$query="SELECT * FROM rbac_roles_domains_privileges WHERE `role_id`='$inherit'";
		$result=$db_conn->query($query);
		while ($match=$result->fetch_assoc()) {
			$query="INSERT INTO rbac_roles_domains_privileges (`role_id`,`domain_id`,`privilege_id`,`is_res_own`) VALUES ('$insert_id','{$match['domain_id']}','{$match['privilege_id']}','{$match['is_res_own']}')";
			$db_conn->query($query);
		}
	}
	$db_conn->commit();
	?>
<script>
window.returnValue='ok';
window.close();
</script>
	<?php
}
function edit()
{
	if (filled_out(array($_REQUEST['name']))) {
		$id=$_REQUEST['id'];
		$name=$_REQUEST['name'];
		$description=$_REQUEST['description'];
		$db_conn=db_connect();
		$query="UPDATE rbac_roles SET
	`name`='$name',
	`description`='$description' WHERE
	`id`='$id'";
		$result = $db_conn->query($query);
	}
	?>
<script>
window.returnValue='ok';
window.close   ();
</script>
	<?php
}

function edit_permission()
{
	$role_id=$_REQUEST['id'];
	$db_conn=db_connect();
	//check sys role
	$query="SELECT sys FROM rbac_roles WHERE id='$role_id'";
	$result=$db_conn->query($query);
	$match=$result->fetch_assoc();
	if ($match['sys']==0) {
		//DML, start transaction
		$db_conn ->autocommit(false);
		$query="DELETE FROM rbac_roles_domains_privileges WHERE `role_id`='$role_id'";
		$result = $db_conn->query($query);
		for($i=0;$i<count($_REQUEST['permissions']); $i++) {
			$permission=split(",",$_REQUEST['permissions'][$i]);
			$domain_id=$permission[0];
			$privilege_id=$permission[1];
			$res=$permission[2];
			$query="INSERT INTO rbac_roles_domains_privileges (`role_id`,`domain_id`,`privilege_id`,`is_res_own`) VALUES ('$role_id','$domain_id','$privilege_id','$res')";
			$result = $db_conn->query($query);
		}
		$db_conn->commit();
	}
	?>
<script>
window.returnValue='ok';
window.close   ();
</script>
	<?php
}

function add_permission()
{
	$role_id=$_REQUEST['id'];
	$res=$_REQUEST['res'];
	$db_conn=db_connect();
	//check sys role
	$query="SELECT sys FROM rbac_roles WHERE id='$role_id'";
	$result=$db_conn->query($query);
	$match=$result->fetch_assoc();
	if ($match['sys']==0) {
		//check input
		if (count($_REQUEST['selected_domains'])>0&&count($_REQUEST['selected_privileges'])>0){
			for($i=0;$i<count($_REQUEST['selected_domains']); $i++) {
				for($j=0;$j<count($_REQUEST['selected_privileges']); $j++) {
					$query="SELECT COUNT(*) AS num_rows FROM rbac_roles_domains_privileges WHERE
			`role_id`='$role_id'
			AND `domain_id`='{$_REQUEST['selected_domains'][$i]}'
			AND `privilege_id`='{$_REQUEST['selected_privileges'][$j]}'";
					$result = $db_conn->query($query);
					$match=$result->fetch_assoc();
					if ($match['num_rows']==0) {
						$query="INSERT INTO rbac_roles_domains_privileges (`role_id`,`domain_id`,`privilege_id`,`is_res_own`) VALUES ('$role_id','{$_REQUEST['selected_domains'][$i]}','{$_REQUEST['selected_privileges'][$j]}','$res')";
						$result = $db_conn->query($query);
					} else {
						$query="UPDATE rbac_roles_domains_privileges SET
				`is_res_own`='$res' WHERE
				`role_id`='$role_id'
				AND `domain_id`='{$_REQUEST['selected_domains'][$i]}'
				AND `privilege_id`='{$_REQUEST['selected_privileges'][$j]}'";
						$result = $db_conn->query($query);
					}
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

function delete()
{
	$id=$_REQUEST['id'];
	$db_conn=db_connect();
	$query="SELECT sys FROM rbac_roles WHERE id='$id' ";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	if ($match['sys']==0) {
		$query="SELECT COUNT(*) AS num_rows FROM rbac_users_roles WHERE `role_id`='$id'";
		$result=$db_conn->query($query);
		$match=$result->fetch_assoc();
		if ($match['num_rows']==0) {
			$db_conn->autocommit(false);
			$query="DELETE FROM rbac_roles_domains_privileges WHERE `role_id`='$id'";
			$db_conn->query($query);
			$query="DELETE FROM rbac_roles WHERE `id`='$id'";
			$db_conn->query($query);
			$db_conn->commit();
		}
	}
	?>
<script>
window.returnValue='ok';
window.close();
</script>
	<?php
}

function process_request()
{
	$action = $_REQUEST['action'];
	switch ($action) {
		case 'add':add();break;
		case 'edit':edit();break;
		case 'edit_form':edit_form();break;
		case 'delete':delete();break;
		case 'add_form':add_form();break;
		case 'add_permission_form':add_permission_form();break;
		case 'add_permission':add_permission();break;
		case 'edit_permission_form':edit_permission_form();break;
		case 'edit_permission':edit_permission();break;
		case 'delete_form':delete_form();break;
		case 'detail':detail();break;
	}
}
?>

