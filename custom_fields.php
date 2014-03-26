<?php
include('include/includes.php');
if (!check_auth_user()) {
  header('Location: '.'login.php');
  exit;
}
?>
<?php  
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
  do_html_header('Custom fields-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  if(!userPermission(2)) {
   echo '<table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
   do_rightbar();
   do_footer();
   do_html_footer();
   exit;
  }
?> 
<?php
	$action=$_REQUEST['action'];
	switch ($action) {
		case "add":
			Add();
			break;
		case "edit":
			Edit();
			break;
		default:
			break;
	}
?>
<?php 
  js_selectall();
?>
<script>
function resetform(f,pagesize) {
	for (i=0; f.elements[i]; i++) {
		if(f.elements[i].type=="select-one") {
			f.elements[i].options[0].selected=true;
		}
	}
}
function submitResultsForm(f) {
	var confirmVal
	confirmVal = confirm("Are you sure to edit this field? incorrect edit could destroy the data!")
	if (!confirmVal) {
		return
	}
	else {
		f.submit();
	}
}
</script>
<form action="custom_fields.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class="search">
    <tr><td align="center">
      <h2>Custom fields</h2></td>
    </tr> 
    <tr>
      <td >Module name:
      <?php 
      $query="SELECT * FROM modules ORDER BY name";
      echo query_select_choose('module',$query,'name','name',$_REQUEST['module']);
      ?>
      <input type="submit" name="Submit" value="Go" />
      <input type="button" onclick="resetform(document.search,10)" value="Clear"/>
      </td>
    </tr>
	</table>
</form>
 
<?php
if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

$module=$_REQUEST['module'];
$sort=$_REQUEST['sort'];
$order=$_REQUEST['order'];
if (isset($_REQUEST['module'])&&$_REQUEST['module']!='') {
	$db_conn = db_connect();
	$query =  "SELECT * FROM custom_fields WHERE module_name LIKE '$module' ORDER BY id";
	$results=$db_conn->query($query);
	$num_rows=$results->num_rows;
?>

<table width="100%" class="standard" style="margin-top:2pt">
<form action="" method="POST" name="add" target="_self">
  <input type="hidden" name="action" value="add"/>
  <input type="hidden" name="module_name" value="<?php echo $module ?>"/>
	<tr>
		<td colspan="7"><h4>Add custom field to the module <i><?php echo $module?></i>:</h4>
		</td>
	</tr>
	<tr>
		<td><b>Field id</b></td>
		<td><b>Field name</b></td>
		<td><b>Type</b></td>
		<td><b>Length/values</b></td>
		<td><b>Null</b></td>
		<td><b>Default</b></td>
		<td><b>Note</b></td>
		<td><b>Search</b></td>
		<td><b>Operate</b></td>
	</tr>
	<tr>
		<td>new</td>
		<td><input type="text" name="field_name" /></td>
		<td>
		<select name="field_type">
			<option value="VARCHAR">VARCHAR</option>
			<option value="TEXT">TEXT</option>
			<option value="INT">INTEGER</option>
			<option value="DOUBLE">DOUBLE</option>
			<option value="DATE">DATE</option>
			<option value="DATETIME">DATETIME</option>
		</select>
		</td>
		<td><input type="text" name="field_length_values" /></td>
		<td>
		<select name="is_null">
			<option value="0">NOT NULL</option>
			<option value="1">NULL</option>
		</select>
		</td>
		<td><input type="text" name="field_default" /></td>
		<td><input type="text" name="note" /></td>
		<td><input type="checkbox" name="search" /></td>
		<td><input type="submit" value="Add"/></td>
	</tr>
</form>
	<?php
  	if ($results&&$results->num_rows>0) {
	?>
	<tr>
		<td colspan="7"><h4>Edit custom field of the module <i><?php echo $module?></i>:</h4>
		</td>
	</tr>
		<?php
		while ($matches = $results->fetch_array()) {
			$form_name="edit_".$matches['id'];
			echo "<form name='".$form_name."' method='post' target='_self'/>";
			echo "<input type='hidden' name='id' value='".$matches['id']."'/>";
			echo "<input type='hidden' name='field_id' value='".$matches['field_id']."'/>";
			echo "<input type='hidden' name='module_name' value='".$module."'/>";
			echo "<input type='hidden' name='action' value='edit'/>";
			echo "<tr><td>".$matches['field_id']."</td><td>";
			echo "<input type='text' name='field_name' value='{$matches['field_name']}'/></td><td class='results'>";
			$field_type=array("VARCHAR"=>"VARCHAR","TEXT"=>"TEXT","INTEGER"=>"INT","DOUBLE"=>"DOUBLE","DATE"=>"DATE","DATETIME"=>"DATETIME");
			echo array_select('field_type',$field_type,$matches['field_type']);
			echo "</td><td class='results'>";
			echo "<input type='text' name='field_length_values' value='{$matches['field_length_values']}'/></td><td class='results'>";
			$is_null=array("NOT NULL"=>"0","NULL"=>"1");
			echo array_select('is_null',$is_null,$matches['is_null']);
			echo "</td><td class='results'>";
			echo "<input type='text' name='field_default' value='{$matches['field_default']}'/></td><td class='results'>";
			echo "<input type='text' name='note' value='{$matches['note']}'/></td><td class='results'>";
			echo "<input type='checkbox' name='search'";
			if ($matches['search']==1) {
				echo " checked";
			}
			echo "/>";
			echo "</td><td class='results'>";
			echo "<input type='button' value='Edit' onclick='submitResultsForm(document.$form_name)'/>";
			echo "</td><tr>";
			echo "</form>";
		}
	}
	echo "</table>";
}
function Add () {
	try {
		if (!filled_out(array($_REQUEST['field_name']))) {
			throw new Exception("You have not filled the form correctly, please try again.");
		}
		$module_name=$_REQUEST['module_name'];
		$field_name=$_REQUEST['field_name'];
		$field_type=$_REQUEST['field_type'];
		$field_length_values=$_REQUEST['field_length_values'];
		$is_null=$_REQUEST['is_null'];
		$field_default=$_REQUEST['field_default'];
		$note=$_REQUEST['note'];
		if ($_REQUEST['search']==true) {
			$search=1;
		}
		else {
			$search=0;
		}
		
		$db_conn=db_connect();
		$query="SELECT * FROM custom_fields WHERE module_name='$module_name' ORDER BY field_id DESC";
		$rs=$db_conn->query($query);
		$num_fields=$rs->num_rows;
		if ($num_fields==0) {
			$field_id='custom_field_1';
		}
		else {
			$match=$rs->fetch_assoc();
			$field_id_letter=ereg_replace('[0-9]','',$match['field_id']);
			$field_id_number=ereg_replace('[^0-9]','',$match['field_id']);
			$field_id=$field_id_letter.($field_id_number+1);
		}
		if ($field_type=="VARCHAR") {
			if ($field_length_values<=0||$field_length_values>255) {
				$field_length_values=255;
			}
		}
		elseif ($field_type=="INT") {
			if ($field_length_values<=0||$field_length_values>11) {
				$field_length_values=11;
			}
		}
		else {
			$field_length_values="";
		}
		if ($field_length_values!="") {
			$field_type_wl=$field_type."(".$field_length_values.")";
		}
		if ($is_null==0) {
			$is_null_string="NOT NULL";
		}
		else {
			$is_null_string="NULL";
		}
		if ($field_default=="") {
			$default="";
		}
		else {
			$default="DEFAULT '$field_default'";
		}
		$query="ALTER TABLE `$module_name` ADD `$field_id` $field_type_wl $is_null_string $default";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		$query="INSERT INTO custom_fields (`module_name`,`field_id`,`field_name`,`field_type`,`field_length_values`,`is_null`,`field_default`,`note`,`search`) VALUES ('$module_name','$field_id','$field_name','$field_type','$field_length_values','$is_null','$field_default','$note','$search')";
		$result = $db_conn->query($query);
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
		$id=$_REQUEST['id'];
		$module_name=$_REQUEST['module_name'];
		$field_id=$_REQUEST['field_id'];
		$field_name=$_REQUEST['field_name'];
		$field_type=$_REQUEST['field_type'];
		$field_length_values=$_REQUEST['field_length_values'];
		$is_null=$_REQUEST['is_null'];
		$field_default=$_REQUEST['field_default'];
		$note=$_REQUEST['note'];
		if ($_REQUEST['search']==true) {
			$search=1;
		}
		else {
			$search=0;
		}
		
		$db_conn=db_connect();
		
		if ($field_type=="VARCHAR") {
			if ($field_length_values<=0||$field_length_values>255) {
				$field_length_values=255;
			}
		}
		elseif ($field_type=="INT") {
			if ($field_length_values<=0||$field_length_values>11) {
				$field_length_values=11;
			}
		}
		else {
			$field_length_values="";
		}
		if ($field_length_values!="") {
			$field_type_wl=$field_type."(".$field_length_values.")";
		}
		if ($is_null==0) {
			$is_null_string="NOT NULL";
		}
		else {
			$is_null_string="NULL";
		}
		if ($field_default=="") {
			$default="";
		}
		else {
			$default="DEFAULT '$field_default'";
		}
		$query="ALTER TABLE `$module_name` CHANGE `$field_id` `$field_id` $field_type_wl $is_null_string $default";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		$query="UPDATE custom_fields SET 
		`module_name`='$module_name',
		`field_name`='$field_name',
		`field_type`='$field_type',
		`field_length_values`='$field_length_values',
		`is_null`='$is_null',
		`field_default`='$field_default',
		`note`='$note',
		`search`='$search'
		WHERE `id`=$id";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
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