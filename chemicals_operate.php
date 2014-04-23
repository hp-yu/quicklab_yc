<?php
include('include/includes.php');
?>
<?php
if ($_REQUEST['type']=='export_excel') {
	if(!userPermission(3))
	{
		header('location:'.$_SESSION['url_1']);
	}
	$query=$_SESSION['query'];
	export_excel('chemicals',$query);
	exit;
}
if ($_REQUEST['type']=='import_template') {
	import_template();
	exit;
}
?>
<?php
  do_html_header_begin('Chemicals operate-Quicklab');
?>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<script src="./jmol/Jmol.js" type="text/javascript"></script>
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
<form name='add_form' id="add_form" method='post' action='' enctype='multipart/form-data'>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Chemicals</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new chemical:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
      </tr>
      <tr>
        <td>Synonym:</td>
        <td><input type='text' name='synonym' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['synonym']))?>"/></td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
        $query= "select * from projects WHERE state=1";
		echo query_select_choose('project', $query,'id','name',$_POST['project']);?></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols="50" rows="3"><?php echo stripslashes($_POST['description']) ?></textarea></td>
      </tr>
      <tr>
        <td>CAS number:</td>
        <td><input type='text' name='cas' value="<?php echo stripslashes(htmlspecialchars($_POST['cas']))?>"/></td>
      </tr>
      <tr>
        <td>Molecular formula:</td>
        <td><input type='text' name='formula' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['formula']))?>"/></td>
      </tr>
      <tr>
        <td>Molecular weight:</td>
        <td><input type='text' name='mw' value="<?php echo stripslashes(htmlspecialchars($_POST['mw']))?>"/></td>
      </tr>
      <tr>
        <td>Purity:</td>
        <td><input type='text' name='purity' value="<?php echo stripslashes(htmlspecialchars($_POST['purity']))?>"/></td>
      </tr>
      <tr>
        <td>Form:</td>
        <td><input type='text' name='form' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['form']))?>"/></td>
      </tr>
      <tr>
        <td>Storage:</td>
        <td><input type='text' name='storage' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['storage']))?>"/></td>
      </tr>
      <tr>
        <td>Solubility:</td>
        <td><input type='text' name='solubility' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['solubility']))?>"/></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols="50" rows="3"><?php echo stripslashes($_POST['note']) ?></textarea></td>
      </tr>
      <tr>
        <td>Mask:</td>
        <td><?php
        $mask=array(array("0","no"),
        array("1","yes"));
		echo option_select('mask',$mask,2,$_POST['mask']);?>
		</td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <?php hidden_inputs('created_by','date_create','add');?>
      </table></form>
      <?php
}

function edit_form()
{
	$chemical = get_record_from_id('chemicals',$_REQUEST['id']);
	if(!userPermission('2',$chemical['created_by']))
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
    <form name='edit_form' id="edit_form" method='post' action='' enctype='multipart/form-data'>
    <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Chemicals</h2></div></td></tr>
  	  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($chemical['name']));?>">*</td>
      </tr>
      <tr>
        <td>Synonym:</td>
        <td><input type='text' name='synonym' size="40" value="<?php
     echo $chemical['synonym'];?>"></td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
        $query= "select * from projects";
		echo query_select_choose('project', $query,'id','name',$chemical['project']);?></td>
	  </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols='50' rows='3'><?php
     echo $chemical['description'];?></textarea></td>
      </tr>
      <tr>
        <td>CAS number:</td>
        <td><input type='text' name='cas' value='<?php
     echo $chemical['cas'];?>'></td>
      </tr>
      <tr>
        <td>Molecular formula:</td>
        <td><input type='text' name='formula' size="40" value='<?php
     echo $chemical['formula'];?>'></td>
      </tr>
      <tr>
        <td>Molecular weight:</td>
        <td><input type='text' name='mw' value='<?php
     echo $chemical['mw'];?>'></td>
      </tr>
      <tr>
        <td>Purity::</td>
        <td><input type='text' name='purity' value='<?php
     echo $chemical['purity'];?>'></td>
      </tr>
      <tr>
        <td>Form:</td>
        <td><input type='text' name='form' size="40" value='<?php
     echo $chemical['form'];?>'></td>
      </tr>
      <tr>
        <td>Storage:</td>
        <td><input type='text' name='storage' size="40" value='<?php
     echo $chemical['storage'];?>'></td>
      </tr>
      <tr>
        <td>Solubility:</td>
        <td><input type='text' name='solubility' size="40" value='<?php
     echo $chemical['solubility'];?>'></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols='50' rows='3'><?php
     echo $chemical['note'];?></textarea></td>
      </tr>
      <tr>
        <td>Mask:</td>
        <td><?php
        $mask=array(array(1,'yes'),
        array(0,'no'));
        echo option_select('mask',$mask,2,$chemical['mask']);
        ?>
        </td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <?php hidden_inputs('updated_by','date_update','edit');?>
    </table></form>
  <?php
}

function edit_relation_form()
{
	$chemical = get_record_from_id('chemicals',$_REQUEST['id']);
	if(!userPermission('2',$chemical['created_by']))
	{
		alert();
	}
?>
  <form name="relation" method="post" action="" target="_self">
  <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Chemicals</h2></div></td></tr>
    <tr><td colspan="2"><h3>Relate chemical: <?php echo $chemical['name']; ?> to these items below:</h3></td></tr>
    <tr><td width="200">
  	<select name="clipboardtarget[]" style="width:190px;font-size:7pt;" size="5" multiple
  	ondblclick="removeOption(document.getElementById('clipboardtarget[]'))">
  	<?php
  	$db_conn = db_connect();
  	$module=get_record_from_name('modules','chemicals');
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
 </table> </form>
<?php
}

function detail()
{
	if(!userPermission('4'))
	{
		alert();
	}
	$chemical = get_record_from_id('chemicals',$_REQUEST['id']);
?>
<form name='detail_form' id="detail_form" method='post' action=''>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Chemicals</h2></div></td></tr>
      <tr><td colspan='2'><h3>Detail:
      <a href="chemicals_operate.php?type=edit&id=<?php echo $chemical['id']?>"/><img src="./assets/image/general/edit.gif" border="0"/></a>
      </h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $chemical['name'];?></td>
      </tr>
      <tr>
        <td>Synonym:</td>
        <td><?php echo $chemical['synonym'];?></td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
        $project= get_record_from_id('projects',$chemical['project']);
		echo $project['name'];?></td></tr>
      <tr>
        <td>Description:</td>
        <td><?php echo wordwrap($chemical['description'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>CAS number:</td>
        <td><?php echo $chemical['cas'];?></td>
      </tr><?php
      $db_conn=db_connect();
      $query="SELECT * FROM chem_molstruc WHERE chem_id='{$chemical['id']}'";
      $rs_str=$db_conn->query($query);
      $num_str=$rs_str->num_rows;
      $match_str=$rs_str->fetch_assoc();
      if ($num_str>0) {
      	$mol=str_replace("\r\n","|",$match_str['struc']);
      	echo '<tr><td >Structure:';
        echo '</td><td>';?>
<applet name="JME" code="JME.class" archive="./include/JME.jar" width=200 height=200>
<param name="options" value="depict,border ">
<param name="mol" value="<?php echo $mol ?>">
</applet>
      	<?php
      	echo"</td></tr>";
      }
      ?>

      <tr>
        <td>Molecular formula:</td>
        <td><?php echo $chemical['formula'];?></td>
      </tr>
      <tr>
        <td>Molecular weight:</td>
        <td><?php echo $chemical['mw'];?></td>
      </tr>
      <tr>
        <td>Purity:</td>
        <td><?php echo $chemical['purity'];?></td>
      </tr>
      <tr>
        <td>Form:</td>
        <td><?php echo $chemical['form'];?></td>
      </tr><tr>
        <td>Storage:</td>
        <td><?php echo $chemical['storage'];?></td>
      </tr><tr>
        <td>Solubility:</td>
        <td><?php echo $chemical['solubility'];?></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><?php echo wordwrap($chemical['note'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>Created by:</td><td><?php
        $people = get_name_from_id('people',$chemical['created_by']);
		echo $people['name'].'  '.$chemical['date_create'];?></td></tr>
     </tr>
      <tr>
        <td>Updated by:</td><td><?php
        $people = get_name_from_id('people',$chemical['updated_by']);
		echo $people['name'].'  '.$chemical['date_update'];?></td></tr>
	 <?php
	 $db_conn = db_connect();
	 $module=get_record_from_name('modules','chemicals');
	 $query="SELECT * FROM items_relation
	   WHERE item_from='".$module['id']."_".$_REQUEST['id']."'";
	 $results=$db_conn->query($query);
	 $num_relateditems=$results->num_rows;
	 ?>
	 <?php
	 if($num_relateditems!=0)
	 {
	 	echo "<tr><td valign='top' rowspan=".$num_relateditems.">Related items:</td>";
	 	while ($matches=$results->fetch_assoc())
	 	{
	 		$key_array=split("_",$matches['item_to']);
	 		$module=get_name_from_id(modules,$key_array[0]);
	 		$item=get_name_from_id($module['name'],$key_array[1]);
	 		echo "<td>".$module['name'].": <a href='".$module['name']."_operate.php?type=detail&id=".$key_array[1]."' target='_blank'/>".
	 		$item['name']."</a></td></tr><tr>";
	 	}
	 }?>
      <tr>
        <td colspan='2'><a href='<?php echo $_SESSION['url_1'];?>'><img
	 src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a>
        </td>
      </tr>
    </table>
<?php
}
function delete_form()
{
	$chemical = get_record_from_id('chemicals',$_REQUEST['id']);
	if(!userPermission('2',$chemical['created_by']))
	{
		alert();
	}
	if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
	{
		$module = get_id_from_name('modules','chemicals');
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
	<tr><td colspan='2'><div align='center'><h2>Chemicals</h2></div></td></tr>";
    		echo "<tr><td colspan='2'><h3>Are you sure to delete the chemical: ";
    		echo $chemical['name'];
    		echo "?</h3></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />";
    		hidden_inputs('','',"delete");
    		echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a></td></tr>";
    		echo "</table></form>";
    	}
    	else
    	{
    		echo "<table width='100%' class='operate' >
	<tr><td colspan='2'><div align='center'><h2>Chemicals</h2></div></td></tr>";
    		echo "<tr><td><h3>This chemical related to ";
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
      src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a></td></tr>";
    		echo "</table>";
    	}
    	
	}
	elseif($_SESSION['selecteditemDel'])//multiple delete
	{
		$num_selecteditemDel=count($_SESSION['selecteditemDel']);
		echo "<form name='edit' method='post' action=''>";
		echo "<table width='100%' class='operate' >
	<tr><td colspan='2'><div align='center'><h2>Chemicals</h2></div></td></tr>";
		echo "<tr><td colspan='2'><h3>Are you sure to delete the $num_selecteditemDel chemical(s)?<br>
    chemical related to other items, storages, orders <br>can not be deleted.</h3></td></tr> ";
		echo "<tr><td colspan='2'><input type='submit' name='Submit' value='Submit' />";
		hidden_inputs('','',"delete");
		echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a></td></tr>";
		echo "</table></form>";
	}
}
function import_form() {
	if(!userPermission('3')) {
		alert();
	}
  ?>
<form name='preview' method='post' action='' enctype="multipart/form-data">
<table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Chemicals</h2></div></td></tr>
<tr><td colspan='2'><h3>Import from file:</h3></td></tr>
<tr>
<td width='20%'>File:</td>
<td width='80%'><input type='file' name='file'/>*</td>
</tr>
<tr>
<td colspan='2'><input type='submit' name='Submit' value='Preview' /></td>
</tr>
</form>
<form name="download" id="download" method="POST" target="_self">
<tr><td colspan='2' >
<a href='#' onclick="submit()"><span style="font-size:10pt;">DOWNLOAD</span></a> the template (CSV file).</td></tr>
<input type="hidden" name="type" value="import_template"/>
</form>
<tr><td colspan="2"><span style="color:red">NOTE:</span></td></tr>
<tr><td colspan="2">*For the 'project', use project id (find it in the projects module), default 'others'.</td></tr>
<tr><td colspan="2">*For the 'mask', use 0 represents no and 1 represents yes, default 0.</td></tr>
</table>
  <?php
  try	{
  	if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
  		$type = basename($_FILES['file']['type']);
  		switch ($type) {
  			case 'octet-stream':
  			case 'plain':     break;
  			default:        throw new Exception('Invalid file format: '.
  			$_FILES['file']['type'].$type) ;
  		}
  	}
  	if(isset($_REQUEST['project'])&&$_REQUEST['project']=='') {
  		throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
  	}
  	if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
  		$db_conn=db_connect();
  		$query="DROP TABLE IF EXISTS temp_chemicals";
  		$result=$db_conn->query($query);

  		$query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_chemicals SELECT * FROM chemicals WHERE 0";
  		$result=$db_conn->query($query);

  		$query="ALTER TABLE temp_chemicals MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
  		$result=$db_conn->query($query);

  		$fp=fopen($_FILES['file']['tmp_name'],"rb");

  		$date_create=date('Y-m-d');
  		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
  		$result = $db_conn->query($query);
  		$match=$result->fetch_assoc();
  		$created_by=$match['people_id'];
  		$n=0;
  		while ($data=fgetcsv($fp)) {
  			if ($n>0) {
  				$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
  				$synonym=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
  				$description=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
  				$cas=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
  				$formula=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
  				$mw=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
  				$purity=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
  				$form=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
  				$storage=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
  				$solubility=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
  				$project=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
  				$query="SELECT * FROM projects WHERE id='$project'";
  				$result=$db_conn->query($query);
  				if($result->num_rows==0) $project=1;
  				$note=mb_convert_encoding(addslashes(trim($data[11])),"utf-8","gb2312");
  				$mask=mb_convert_encoding(addslashes(trim($data[12])),"utf-8","gb2312");
  				if($mask != 1) $mask=0;

  				$query="INSERT INTO temp_chemicals
       	 	(name,synonym,description,cas,formula,mw,purity,form,storage,solubility,date_create,created_by,project,note,mask)
       		VALUES
      		('$name','$synonym','$description','$cas','$formula','$mw','$purity','$form','$storage','$solubility','$date_create','$created_by','$project','$note','$mask')";
  				$result=$db_conn->query($query);
  				if(!$result) {
  					throw new Exception("There was a database error when executing<pre>$query</pre>") ;
  				}
  			}
  			$n++;
  		}

  		$rand=mt_rand();
  		$filename = "temp/chemicals_import_$rand.txt";
  		move_uploaded_file($_FILES['file']['tmp_name'],'./'.$filename);

  		$query="SELECT * FROM temp_chemicals";
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
synonym</td><td class='results_header'>
description</td><td class='results_header'>
cas</td><td class='results_header'>
formular</td><td class='results_header'>
mw</td><td class='results_header'>
purity</td><td class='results_header'>
form</td><td class='results_header'>
storage</td><td class='results_header'>
solubility</td><td class='results_header'>
project*</td><td class='results_header'>
note</td><td class='results_header'>
mask*</td></tr>
	    <?php
	    while ($matches = $results->fetch_array()) {
	    	echo "<tr>";
	    	echo "<td class='results'>".$matches['name']."</td>";
	    	echo "<td class='results'>".$matches['synonym']."</td>";
	    	echo "<td class='results'>".$matches['description']."</td>";
	    	echo "<td class='results'>".$matches['cas']."</td>";
	    	echo "<td class='results'>".$matches['formula']."</td>";
	    	echo "<td class='results'>".$matches['mw']."</td>";
	    	echo "<td class='results'>".$matches['purity']."</td>";
	    	echo "<td class='results'>".$matches['form']."</td>";
	    	echo "<td class='results'>".$matches['storage']."</td>";
	    	echo "<td class='results'>".$matches['solubility']."</td>";
	    	$query="SELECT * FROM projects WHERE id='{$matches['project']}'";
	    	$result=$db_conn->query($query);
	    	$project=$result->fetch_assoc();
	    	echo "<td class='results'>".$project['name']."</td>";
	    	echo "<td class='results'>".$matches['note']."</td>";
	    	echo "<td class='results'>".$matches['mask']."</td>";
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

function import_template() {
	$fileName ='chemicals_import.csv';
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName");
	echo "name,";
	echo "synonym,";
	echo "description,";
	echo "cas,";
	echo "formular,";
	echo "mw,";
	echo "purity,";
	echo "form,";
	echo "storage,";
	echo "solubility,";
	echo "project*,";
	echo "note,";
	echo "mask*,";
	$db_conn=db_connect();
	$query="SELECT * FROM custom_fields WHERE module_name='primers' ORDER BY id";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		echo $match['field_name'].",";
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
		$synonym = $_REQUEST['synonym'];
		$project = $_REQUEST['project'];
		$description = $_REQUEST['description'];
		$cas = $_REQUEST['cas'];
		$formula = $_REQUEST['formula'];
		$mw = $_REQUEST['mw'];
		$purity  = $_REQUEST['purity'];
		$form = $_REQUEST['form'];
		$storage = $_REQUEST['storage'];
		$solubility = $_REQUEST['solubility'];
		$date_create = $_REQUEST['date_create'];
		$created_by=$_REQUEST['created_by'];
		$note=$_REQUEST['note'];
		$mask=$_REQUEST['mask'];

		$db_conn = db_connect();
		$query = "insert into chemicals
      (name,synonym,description,cas,formula,mw,purity,form,storage,solubility,date_create,created_by,project,note,mask)
       VALUES
      ('$name','$synonym','$description','$cas','$formula','$mw','$purity','$form','$storage','$solubility','$date_create','$created_by','$project','$note','$mask')";
		$result = $db_conn->query($query);
		$id=$db_conn->insert_id;
		if (!$result)
		{
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		header('Location: chemicals.php?id='.$id);
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
		$module=get_record_from_name('modules','chemicals');
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
		if(!filled_out(array($_REQUEST['name'],$_REQUEST['updated_by'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$id=$_REQUEST['id'];
		$name = $_REQUEST['name'];
		$synonym = $_REQUEST['synonym'];
		$project = $_REQUEST['project'];
		$description = $_REQUEST['description'];
		$cas = $_REQUEST['cas'];
		$formula = $_REQUEST['formula'];
		$mw = $_REQUEST['mw'];
		$purity = $_REQUEST['purity'];
		$form = $_REQUEST['form'];
		$storage = $_REQUEST['storage'];
		$solubility = $_REQUEST['solubility'];
		$date_update = $_REQUEST['date_update'];
		$updated_by=$_REQUEST['updated_by'];
		$note = $_REQUEST['note'];
		$mask=$_REQUEST['mask'];

		$db_conn = db_connect();
		$query = "UPDATE chemicals SET
			name='$name',
		  synonym='$synonym',
			description='$description',
			cas='$cas',
			formula='$formula',
			mw='$mw',
			purity='$purity',
			form='$form',
			storage='$storage',
			solubility='$solubility',
			date_update='$date_update',
			updated_by='$updated_by',
			project='$project',
			note='$note',
			mask='$mask'
			where id='$id'";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		header("Location:".$_SESSION['url_1']);
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function delete()
{
	$db_conn=db_connect();
	if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
	{
		$query="SELECT structure from chemicals where id='{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		if($match['structure']!=NULL)
		{
			unlink("./".$match['structure']);
		}
		$query = "delete from chemicals where id = '{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
	}
	elseif($_SESSION['selecteditemDel'])//multiple delete
	{
		$selecteditemDel=$_SESSION['selecteditemDel'];
		unset($_SESSION['selecteditemDel']);
		$num_selecteditemDel=count($selecteditemDel);
		$module = get_id_from_name('modules','chemicals');
		for($i=0;$i<$num_selecteditemDel;$i++)
		{
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
    		$query="SELECT structure from chemicals where id='{$selecteditemDel[$i]}'";
    		$result = $db_conn->query($query);
    		$match=$result->fetch_assoc();
    		if($match['structure']!=NULL) {
    			unlink("./".$match['structure']);
    		}
    		$query = "delete from chemicals where id = '{$selecteditemDel[$i]}'";
    		$result = $db_conn->query($query);
    	}
		}
	}
	header('Location: '.$_SESSION['url_1']);
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
				$synonym=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
				$description=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
				$cas=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
				$formula=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
				$mw=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
				$purity=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
				$form=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
				$storage=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
				$solubility=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
				$project=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
				$query="SELECT * FROM projects WHERE id='$project'";
				$result=$db_conn->query($query);
				if($result->num_rows==0) $project=1;
				$note=mb_convert_encoding(addslashes(trim($data[11])),"utf-8","gb2312");
				$mask=mb_convert_encoding(addslashes(trim($data[12])),"utf-8","gb2312");
				if($mask != 1) $mask=0;

				$query="INSERT INTO chemicals
       	 	(name,synonym,description,cas,formula,mw,purity,form,storage,solubility,date_create,created_by,project,note,mask)
       		VALUES
      		('$name','$synonym','$description','$cas','$formula','$mw','$purity','$form','$storage','$solubility','$date_create','$created_by','$project','$note','$mask')";
				$result=$db_conn->query($query);
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

function structure_form() {
	$chemical = get_record_from_id('chemicals',$_REQUEST['id']);
	if(!userPermission('2',$chemical['created_by'])) {
		alert();
	}
  ?>
<script language="JavaScript">
function submitMol() {
	var mol = document.JME.molFile();
	var smiles = document.JME.smiles();
	document.editor.mol.value=mol;
	document.editor.smiles.value=smiles;
	document.editor.submit();
}
</SCRIPT>
<form action='' name='editor' method='post' target="_self" enctype="multipart/form-data">
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Chemicals</h2></div></td></tr>
	<tr><td colspan='2'><h3>Edit chemical structure:</h3></td>
  </tr>
  <tr>
	<tr>
		<td width='10%'>Name:</td>
		<td width='90%'><?php echo $chemical['name'];?></td>
	</tr>
	<tr>
		<td colspan="2">
    <applet name="JME" code="JME.class" archive="./include/JME.jar" width="400" height="400">
    <?php
    if (isset($_REQUEST['id'])&&$_REQUEST['id']!='') {
    	$db_conn=db_connect();
    	$query="SELECT * FROM chem_molstruc WHERE chem_id LIKE '{$_REQUEST['id']}'";
    	$rs_str=$db_conn->query($query);
    	$match_str=$rs_str->fetch_assoc();
    	$mol=ereg_replace(chr(10),'|',$match_str['struc']);
    	if ($mol!='') {
    		echo "<param name=\"mol\" value=\"$mol\">";
    	}
    }
    ?>
		<param name=\"options\" value=\"xbutton, query, hydrogens\">
		<param name=\"options\" value=\"xbutton, hydrogens\">
		You have to enable Java in your browser.
		</applet>
    <input type="hidden" name="mol">
    <input type="hidden" name="smiles">
		</td>
	</tr>
	<tr>
		<td>Or upload file: <BR>(MOL/SDF format)</td>
    <td><input type='file' name='structure' size="40"/></td>
 	</tr>
  <tr>
  	<td colspan='2'><input type='button' value=' Submit Molecule ' onClick = "submitMol()"/></td>
  </tr>
  <input type="hidden" name="action" value="structure"/>
</table></form>
  <?php
}

function structure() {
	// configuration data for MolDB database=============================
	$drop_db       = "n";           // erase entire database before re-creating it?
	$tweakmolfiles = "y";           // "y" or "n" ("y" gives better performance)

	$prefix        = "chem_";       // this allows to have different data
	// collections in one database
	// e.g., "mb_" for Maybridge, "nci_" for NCI
	$molstructable = "${prefix}molstruc";  // structures in MDL molfile format
	$moldatatable  = "${prefix}moldata";   // any other data from imported SDF file
	$molstattable  = "${prefix}molstat";   // molecular statistics for preselection
	$molfgtable    = "${prefix}molfg";     // functional groups as 8-digit codes
	$molfgbtable   = "${prefix}molfgb";    // functional group bitstring as 32-bit integers
	$molbfptable   = "${prefix}molbfp";    // dictionary-based fingerprints
	$fpdeftable    = "${prefix}fpdef";     // the fragment dictionary
	$molhfptable   = "${prefix}molhfp";    // hash-based fingerprints

	// the following options are relevant only if you want to use bitmap
	// graphics for 2D depiction of your molecular structures, otherwise
	// set $bitmapdir to an empty string ($bitmapdir = "")

	//$bitmapdir = "c:\\wamp\\www\\screen\\bitmaps";
	//$digits        = 8;  // filenames will be 00000001.png, etc.
	//$subdirdigits  = 0;  // uses the first x digits of $digits (0 = no subdirectories)
	//$mol2psopt     = "--rotate=auto3Donly --hydrogenonmethyl=off --color=f:\\db\\sdf2db\\color.conf"; // options for mol2ps,
	// e.g. "--showmolname=on"
	// for color support, add something like " --color=/usr/local/etc/color.conf"
	//$scalingfactor = 0.22;            // 0.22 gives good results

	$verbose = 1;            // 0, 1 or 2
	$askuser = 1;            // 0 or 1, change to 0 for skipping the confirmation
	$use_fixed_fields = 1;   // 0 or 1, should be 1 for older versions of checkmol

	$CHECKMOL    = ".\\include\\checkmol";  // add full path if necessary
	$MATCHMOL    = ".\\include\\matchmol";  // add full path if necessary
	//$MOL2PS      = 'mol2ps';    // add full path if necessary
	//$GHOSTSCRIPT = 'gs';        // add full path if necessary, e.g. '/usr/bin/gs'
	//$GSDEVICE    = 'png256';   // use pnggray for anti-aliased B&W, png256 for color
	try {
		$id=$_GET['id'];
		$db_conn=db_connect();
		$query="SELECT id FROM chem_molstruc WHERE chem_id=$id";
		$rs=$db_conn->query($query);
		$num_rows=$rs->num_rows;
		//get the mol file
		if ( (isset($_FILES['structure']['name']) && is_uploaded_file($_FILES['structure']['tmp_name']))) {
			$type = basename($_FILES['structure']['type']);
			switch ($type) {
				case 'octet-stream':break;
				case 'plain':break;
				default:        throw new Exception('Invalid structure format: '.
				$_FILES['structure']['type'].$type) ;
			}
			$fp=fopen($_FILES['structure']['tmp_name'],"rb");
			$mol=fread($fp,filesize($_FILES['structure']['tmp_name']));
		}
		else {
			$mol=$_POST['mol'];
			$smiles=$_POST['smiles'];
		}
		$compound=explode("$$$$",$mol);
		$compound=$compound[0];
		$structure=explode("END", ltrim($compound));
		$structure=explode("\r\n",$structure[0]);
		$structure[0]="chem_id:".$id;
		$structure[1]="  Quicklab".date(mdyHi);
		$structure[2]="";
		$structure=implode("\r\n",$structure);
		$structure=$structure."END";
		//update the database
		//update `molstruc`
		if (isset($smiles)&&$smiles=="") {
			if ($num_rows>0) {
				$query="Delete FROM chem_molstruc WHERE chem_id=$id";
				$rs=$db_conn->query($query);
				if (!$rs) {
					throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
				}
			}
		} else {
			if ($num_rows>0) {
				$query="UPDATE chem_molstruc SET struc='$structure' WHERE chem_id=$id";
				$rs=$db_conn->query($query);
				if (!$rs){
					throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
				}
			} else {
				$query="INSERT INTO chem_molstruc(`chem_id`,`struc`) VALUES ('$id','$structure')";
				$rs=$db_conn->query($query);
				if (!$rs){
					throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
				}
			}
		}

		// create tables if they do not exist already
		// create a new molstattable table
		$createcmd = "CREATE TABLE IF NOT EXISTS $molstattable (chem_id INT(9) NOT NULL DEFAULT '0', \n";
		$MSDEF=filter_through_cmd("","$CHECKMOL -l -");
		$MSDEF = split ("\n", $MSDEF);
		$nfields = 0;
  	$row_num=count($MSDEF);
  	for($n=0;$n<$row_num;$n++) {
  		@$valid = split (":", $MSDEF[$n]);
  		$line=$valid[0];
  		if (strstr($line,'n_')) {
				$nfields ++;
				$createcmd = $createcmd . "  $line" . " SMALLINT(6) NOT NULL DEFAULT '0',\n";
			}
  	}
		$createcmd = $createcmd . "  PRIMARY KEY  (chem_id)
) TYPE=MyISAM COMMENT='Molecular statistics';";
		$rs=$db_conn->query($createcmd);
		if (!$rs){
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		// create a new molfgb table
		$createcmd="CREATE TABLE IF NOT EXISTS $molfgbtable (chem_id INT(9) NOT NULL DEFAULT '0',
fg01 INT(11) UNSIGNED NOT NULL,
fg02 INT(11) UNSIGNED NOT NULL,
fg03 INT(11) UNSIGNED NOT NULL,
fg04 INT(11) UNSIGNED NOT NULL,
fg05 INT(11) UNSIGNED NOT NULL,
fg06 INT(11) UNSIGNED NOT NULL,
fg07 INT(11) UNSIGNED NOT NULL,
fg08 INT(11) UNSIGNED NOT NULL,
n_1bits SMALLINT NOT NULL,
PRIMARY KEY compound_id (chem_id)) TYPE=MyISAM";
		$rs=$db_conn->query($createcmd);
		if (!$rs){
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		// create a new molbfp table if not yet present
$createcmd="CREATE TABLE IF NOT EXISTS $molbfptable (chem_id INT(9) NOT NULL DEFAULT '0', bfp01 BIGINT, PRIMARY KEY compound_id (chem_id)) TYPE=MyISAM";
		$rs=$db_conn->query($createcmd);
		if (!$rs){
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		// create a new molhfp table
		$createcmd="CREATE TABLE IF NOT EXISTS $molhfptable (chem_id INT(9) NOT NULL DEFAULT '0',
fp01 INT(11) UNSIGNED NOT NULL,
fp02 INT(11) UNSIGNED NOT NULL,
fp03 INT(11) UNSIGNED NOT NULL,
fp04 INT(11) UNSIGNED NOT NULL,
fp05 INT(11) UNSIGNED NOT NULL,
fp06 INT(11) UNSIGNED NOT NULL,
fp07 INT(11) UNSIGNED NOT NULL,
fp08 INT(11) UNSIGNED NOT NULL,
fp09 INT(11) UNSIGNED NOT NULL,
fp10 INT(11) UNSIGNED NOT NULL,
fp11 INT(11) UNSIGNED NOT NULL,
fp12 INT(11) UNSIGNED NOT NULL,
fp13 INT(11) UNSIGNED NOT NULL,
fp14 INT(11) UNSIGNED NOT NULL,
fp15 INT(11) UNSIGNED NOT NULL,
fp16 INT(11) UNSIGNED NOT NULL,
n_1bits SMALLINT NOT NULL, PRIMARY KEY compound_id (chem_id) )
TYPE=MyISAM";
		$rs=$db_conn->query($createcmd);
		if (!$rs){
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}

		//update `$molstattable`, `$molfgbtable`, `$molhfptable`
		$db_conn->query("DELETE FROM $molstattable WHERE chem_id = \"$id\"");
		$db_conn->query("DELETE FROM $molfgbtable WHERE chem_id = \"$id\"");
		$db_conn->query("DELETE FROM $molhfptable WHERE chem_id = \"$id\"");

		$molstatfgbhfp  = filter_through_cmd($structure,"$CHECKMOL -aXbH -");   // must be version 0.4 or higher
		@$molstatfgbhfparray = split ("\n", $molstatfgbhfp);
		$molstat  = $molstatfgbhfparray[0];
		$molfgb   = $molstatfgbhfparray[1];
		$molhfp   = $molstatfgbhfparray[2];

		if (!(strstr($molstat,"unknown")) && !(strstr($molstat,"invalid"))) {
			$molstat=trim($molstat);
			//print ("$compound_id:$molstat\n");
			if ($molstat != "") { $db_conn->query("INSERT INTO $molstattable VALUES ( \"$id\", $molstat )"); }

			$molfgb=trim($molfgb);
			$molfgb=str_replace(";",",",$molfgb);
			//$molfgb =~ s/\;/\,/g;
			//if ($molfgb != "") { $dbh->do("INSERT INTO $molfgbtable VALUES (\"$compound_id\", $molfgb )"); }
			if ($molfgb != "") { $db_conn->query("INSERT INTO $molfgbtable VALUES (\"$id\", $molfgb )");}

			$molhfp=trim($molhfp);
			$molhfp=str_replace(";",",",$molhfp);
			//$molhfp =~ s/\;/\,/g;
			//if ($molhfp != "") { $dbh->do("INSERT INTO $molhfptable VALUES ( \"$compound_id\", $molhfp )"); }
			if ($molhfp != "") { $db_conn->query("INSERT INTO $molhfptable VALUES (\"$id\", $molhfp )"); }
		}

		//update `$molbfptable`
		$rs=$db_conn->query("SELECT fpdef FROM $fpdeftable WHERE fp_id = 1");
		$match=$rs->fetch_assoc();
		$fpstruc=$match['fpdef'];
		$db_conn->query("DELETE FROM $molbfptable WHERE chem_id = \"$id\"");
		$cand = $structure . "\n" . '$$$$' ."\n" . $fpstruc;
		$cand=str_replace("$","\\$",$cand);
		//$cand =~ s/\$/\\\$/g;
		$molbfp = filter_through_cmd($cand,"$MATCHMOL -F -");
		$molbfp=trim($molbfp);
		$db_conn->query("INSERT INTO $molbfptable VALUES (\"$id\", \"$molbfp\"  )");

		header("Location:".$_SESSION['url_1']);
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
function process_request()
{
	$type = $_REQUEST['type'];
	switch ($type) {
		case 'add': add_form(); break;
		case 'detail': detail(); break;
		case 'edit': edit_form(); break;
		case 'delete': delete_form(); break;
		case 'relation': edit_relation_form(); break;
		case 'import': import_form(); break;
		case 'structure': structure_form(); break;
	}
	$action = $_POST['action'];
	switch ($action) {
		case 'add': add(); break;
		case 'edit': edit(); break;
		case 'delete': delete(); break;
		case 'relation': edit_relation(); break;
		case 'import': import(); break;
		case 'structure': structure(); break;
	}
}
function export_excel($module_name,$query)
{
	$db_conn=db_connect();
	$results = $db_conn->query($query);
	$num_rows=$results->num_rows;
	while($row = $results->fetch_array())
	{
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
		$xls[]= $row['id']."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['synonym'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['description'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['cas'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['formula'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['mw'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['purity'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['form'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['storage'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['solubility'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$created_by['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['date_create'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$updated_by['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['date_update'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$project['name'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$row['note'])."\t".
		ereg_replace("[\r,\n,\t]"," ",$mask);
	}
	$title="id"."\t".
	"name"."\t".
	"synonym"."\t".
	"description"."\t".
	"cas number"."\t".
	"molecular formula"."\t".
	"molecular weight"."\t".
	"purity"."\t".
	"form"."\t".
	"storage"."\t".
	"solubility"."\t".
	"created_by"."\t".
	"date_create"."\t".
	"updated_by"."\t".
	"date_update"."\t".
	"project"."\t".
	"note"."\t".
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

function filter_through_cmd($input, $commandLine) {
  $tmpfname = tempnam(realpath("C:/temp/"), "mdb");
  $tfhandle = fopen($tmpfname, "wb");

  $myinput = str_replace("\r","",$input);
  $myinput = str_replace("\\\$","\$",$myinput);
  $inputlines = explode("\n",$myinput);
  $newinput = implode("\r\n",$inputlines);
  fwrite($tfhandle, $newinput);

  fclose($tfhandle);
  $output = `type $tmpfname | $commandLine `;
  unlink($tmpfname);
  return $output;
}
?>