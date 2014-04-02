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
	export_excel('cells',$query);
	exit;
}
if ($_REQUEST['type']=='import_template') {
	import_template();
	exit;
}
?>
<?php
  do_html_header_begin('Cells operate-Quicklab');
?>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
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

function add_form() {
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
function moveOptionToText(e1, e2) {
	for(var i=0;i<e1.options.length;i++){
		if(e1.options[i].selected) {
			var e = e1.options[i]
			if(e.value!='') {
			  e2.value=e.text;
			}
		}
	}
}
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
</script>
    <form name='add_form' id="add_form" method='post' action=''>
    <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Cells</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new cell:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
		$query= "select * from projects where state=1";
		echo query_select_choose('project', $query,'id','name',$_POST['project']);?>
        </td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols="60" rows="4"><?php echo stripslashes($_POST['description']) ?></textarea></td>
      </tr>
      <tr>
        <td>ATCC number:</td>
        <td><input type='text' name='atcc_nbr' value="<?php echo stripslashes(htmlspecialchars($_POST['atcc_nbr']))?>"/>&nbsp;
        </td>
      </tr>
      <tr>
        <td>Organism:</td>
        <td><?php
        $query="SELECT * FROM species ORDER BY name";
        echo query_select_choose('organism',$query,'id','name',$_POST['organism']);
        ?>&nbsp;
        <a href="species_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add species' border='0'/></a></td>
      </tr>
      <tr>
        <td>Source<br>(organ,disease, etc):</td>
        <td><input type='text' name='source' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['source']))?>"/></td>
      </tr>
      <tr><td>Medium:</td>
        <td><textarea name='medium' cols="60" rows="4"><?php echo stripslashes($_POST['medium']) ?></textarea></td>
      </tr>
      <tr>
        <td>Temperature:</td>
        <td><input type='text' name='temperature' value="<?php echo stripslashes(htmlspecialchars($_POST['temperature']))?>"/></td>
      </tr>
      <tr>
        <td>Atmosphere:</td>
        <td><input type='text' name='atmosphere' value="<?php echo stripslashes(htmlspecialchars($_POST['atmosphere']))?>"/></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols="60" rows="4"><?php echo stripslashes($_POST['note']) ?></textarea></td>
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
  $cell = get_record_from_id('cells',$_REQUEST['id']);
  if(!userPermission('2',$cell['created_by']))
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
function moveOptionToText(e1, e2) {
	for(var i=0;i<e1.options.length;i++){
		if(e1.options[i].selected) {
			var e = e1.options[i]
			if(e.value!='') {
			  e2.value=e.text;
			}
		}
	}
}
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
</script>
    <form name='edit_form' id="edit_form" method='post' action=''>
    <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Cells</h2></div></td></tr>
  	  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($cell['name']));?>">*</td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
		$query= "select * from projects";
		echo query_select_choose('project', $query,'id','name',$cell['project']);?>
        </td></tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols='60' rows='4'><?php
     echo $cell['description'];?></textarea></td>
      </tr>
      <tr>
        <td>ATCC number:</td>
        <td><input type='text' name='atcc_nbr' value="<?php
     echo $cell['atcc_nbr'];?>">&nbsp;</td>
      </tr>
      <tr>
        <td>Organism:</td>
        <td><?php
        $query="SELECT * FROM species ORDER BY name";
        echo query_select_choose('organism',$query,'id','name',$cell['organism']);?></td>
      </tr>
      <tr>
        <td>Source<br>(organ,disease, etc):</td>
        <td><input type='text' name='source' size="40" value="<?php
     echo $cell['source'];?>"></td>
      </tr>
      <tr>
        <td>Medium:</td>
        <td><textarea name='medium' cols='60' rows='4'><?php
     echo $cell['medium'];?></textarea></td>
      </tr>
      <tr>
        <td>Temperature:</td>
        <td><input type='text' name='temperature' value="<?php
     echo $cell['temperature'];?>"></td>
      </tr>
      <tr>
        <td>Atmosphere:</td>
        <td><input type='text' name='atmosphere' value="<?php
     echo $cell['atmosphere'];?>"></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols='60' rows='4'><?php
     echo $cell['note'];?></textarea></td>
      </tr>
      <tr>
        <td>Mask:</td>
        <td><?php
        $mask=array(array(1,'yes'),
        			array(0,'no'));
        echo option_select('mask',$mask,2,$cell['mask']);
        ?>
        </td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <?php hidden_inputs('updated_by','date_update','edit');?>
   </table> </form>
  <?php
}

function edit_relation_form()
{
  $cell = get_record_from_id('cells',$_REQUEST['id']);
  if(!userPermission('2',$cell['created_by']))
  {
  	alert();
  }
?>
  <form name="relation" method="post" action="" target="_self">
    <tr><td colspan="2"><h3>Relate cell: <?php echo $cell['name']; ?> to these items below:</h3></td></tr>
    <tr><td width="200">
  	<select name="clipboardtarget[]" style="width:190px;font-size:7pt;" size="5" multiple
  	ondblclick="removeOption(document.getElementById('clipboardtarget[]'))">
  	<?php
      $db_conn = db_connect();
      $module=get_record_from_name('modules','cells');
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
  </form></table>
<?php
}

function detail()
{
	if(!userPermission('4'))
    {
  	  alert();
    }
  $cell = get_record_from_id('cells',$_REQUEST['id']);
?>
<form name='detail_form' id="detail_form" method='post' action=''>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Cells</h2></div></td></tr>
      <tr><td colspan='2'><h3>Detail:
      <a href="cells_operate.php?type=edit&id=<?php echo $cell['id']?>"/><img src="./assets/image/general/edit.gif" border="0"/></a></h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $cell['name'];?></td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
		$project= get_record_from_id('projects',$cell['project']);
		echo $project['name'];?></td></tr>
      <tr>
        <td>Description:</td>
        <td><?php echo wordwrap($cell['description'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>ATCC number:</td>
        <td><?php echo $cell['atcc_nbr'];?></td>
      </tr>
      <tr>
        <td>Organism:</td>
        <td><?php
        $species=get_record_from_id('species',$cell['organism']);
        echo $species['name'];?></td>
      </tr>
      <tr>
        <td>Source:</td>
        <td><?php echo $cell['source'];?></td>
      </tr>
			<tr>
        <td>Medium:</td>
        <td><?php echo wordwrap($cell['medium'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>Temperature:</td>
        <td><?php echo $cell['temperature'];?></td>
      </tr>
      <tr>
        <td>Atmosphere:</td>
        <td><?php echo $cell['atmosphere'];?></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><?php echo wordwrap($cell['note'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>Created by:</td><td><?php
		$people = get_name_from_id('people',$cell['created_by']);
		echo $people['name'].'  '.$cell['date_create'];?></td></tr>
     </tr>
      <tr>
        <td>Updated by:</td><td><?php
		$people = get_name_from_id('people',$cell['updated_by']);
		echo $people['name'].'  '.$cell['date_update'];?></td></tr>
	 <?php
       $db_conn = db_connect();
       $module=get_record_from_name('modules','cells');
	   $query="SELECT * FROM items_relation
	   WHERE item_from='".$module['id']."_".$_REQUEST['id']."'";
	   $results=$db_conn->query($query);
	   $num_relateditems=$results->num_rows;
	 if($num_relateditems!=0) {
	   echo "<tr><td valign='top' rowspan=".$num_relateditems.">Related items:</td>";
	 while ($matches=$results->fetch_assoc()) {
	  	 $key_array=split("_",$matches['item_to']);
  	     $module=get_name_from_id(modules,$key_array[0]);
  	     $item=get_name_from_id($module['name'],$key_array[1]);
  	     echo "<td>".$module['name'].": <a href='".$module['name']."_operate.php?type=detail&id=".$key_array[1]."' target='_blank'/>".
  	     $item['name']."</a></td></tr>";
	   }
	 }?>
      <tr>
        <td colspan='2'><a href='<?php echo $_SESSION['url_1'];?>'><img
	 src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
        </td>
      </tr>
    </table>
<?php
}
function delete_form()
{
  $cell = get_record_from_id('cells',$_REQUEST['id']);
  if(!userPermission('2',$cell['created_by']))
  {
  	alert();
  }
  if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
  {
	$module = get_id_from_name('modules','cells');
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
		<tr><td colspan='2'><div align='center'><h2>Cells</h2></div></td></tr>";
	    echo "<tr><td colspan='2'><h3>Are you sure to delete the cell: ";
	    echo $cell['name'];
		echo "?</h3></td>
	      </tr>
	      <tr>
	        <td colspan='2'><input type='submit' name='Submit' value='Submit' />";
	    hidden_inputs('','',"delete");
	    echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
	      src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
	    echo "</table></form>";
	}
	else
	{
		echo "<table width='100%' class='operate' >
	<tr><td colspan='2'><div align='center'><h2>Cells</h2></div></td></tr>";
		echo "<tr><td><h3>This cell related to ";
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
      src='./assets/image/general/back.gif' alt='Back' border='0'/></a></td></tr>";
		echo "</table>";
	}
	
  }
  elseif($_SESSION['selecteditemDel'])//multiple delete
  {
		$num_selecteditemDel=count($_SESSION['selecteditemDel']);
	echo "<form name='edit' method='post' action=''>";
	echo "<table width='100%' class='operate' >
	<tr><td colspan='2'><div align='center'><h2>Cells</h2></div></td></tr>";
    echo "<tr><td colspan='2'><h3>Are you sure to delete the $num_selecteditemDel cell(s)?<br>
    cell related to other items, storages, orders <br>can not be deleted.</h3></td></tr> ";
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
<form name='preview' method='post' action='' enctype="multipart/form-data">
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Cells</h2></div></td></tr>
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
<tr><td colspan="2">*For the 'organism', use species id (find it in the species module).</td></tr>
<tr><td colspan="2">*For the 'project', use project id (find it in the projects module).</td></tr>
<tr><td colspan="2">*For the 'mask', use 0 represents no and 1 represents yes.</td></tr>
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
  	if(isset($_REQUEST['project'])&&$_REQUEST['project']=='') {
  		throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
  	}
  	if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
  		$db_conn=db_connect();
  		$query="DROP TABLE IF EXISTS temp_cells";
  		$result=$db_conn->query($query);

  		$query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_cells SELECT * FROM cells WHERE 0";
  		$result=$db_conn->query($query);

  		$query="ALTER TABLE temp_cells MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
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
  			$description=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
  			$atcc_nbr=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
  			$organism=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
  			$query="SELECT * FROM species WHERE id='$organism'";
  			$result=$db_conn->query($query);
  			if($result->num_rows==0) $organism=0;
  			$source=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
  			$medium=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
  			$temperature=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
  			$atmosphere=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
  			$project=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
  			$query="SELECT * FROM projects WHERE id='$project'";
  			$result=$db_conn->query($query);
  			if($result->num_rows==0) $project=1;
  			$note=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
  			$mask=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
  			if($mask != 1) $mask=0;

  			$query = "insert into temp_cells
      (name,description,atcc_nbr,organism,source,medium,temperature,atmosphere,date_create,created_by,project,note,mask)
       VALUES
      ('$name','$description','$atcc_nbr','$organism','$source','$medium','$temperature','$atmosphere','$date_create','$created_by','$project','$note','$mask')";
  			$result=$db_conn->query($query);
  			if(!$result) {
  				throw new Exception("There was a database error when executing<pre>$query</pre>") ;
  			}
  			}
  			$n++;
  		}

  		$rand=mt_rand();
  		$filename = "temp/cells_import_$rand.txt";
  		move_uploaded_file($_FILES['file']['tmp_name'],'./'.$filename);

  		$query="SELECT * FROM temp_cells";
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
atcc_nbr</td><td class='results_header'>
organism*</td><td class='results_header'>
source</td><td class='results_header'>
medium</td><td class='results_header'>
temperature</td><td class='results_header'>
atmosphere</td><td class='results_header'>
project*</td><td class='results_header'>
note</td><td class='results_header'>
mask*</td></tr>
	    <?php
	    while ($matches = $results->fetch_array()) {
	    	echo "<tr>";
	    	echo "<td class='results'>".$matches['name']."</td>";
	    	echo "<td class='results'>".$matches['description']."</td>";
	    	echo "<td class='results'>".$matches['atcc_nbr']."</td>";
	    	$query="SELECT * FROM species WHERE id='{$matches['organism']}'";
	    	$result=$db_conn->query($query);
	    	$species=$result->fetch_assoc();
	    	echo "<td class='results'>".$species['name']."</td>";
	    	echo "<td class='results'>".$matches['source']."</td>";
	    	echo "<td class='results'>".$matches['medium']."</td>";
	    	echo "<td class='results'>".$matches['temperature']."</td>";
	    	echo "<td class='results'>".$matches['atmosphere']."</td>";
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
  $atcc_nbr = trim($_REQUEST['atcc_nbr']);
  $organism = $_REQUEST['organism'];
  $source = $_REQUEST['source'];
  $medium = $_REQUEST['medium'];
  $temperature = $_REQUEST['temperature'];
  $atmosphere = $_REQUEST['atmosphere'];
  $date_create = $_REQUEST['date_create'];
  $created_by=$_REQUEST['created_by'];
  $note=$_REQUEST['note'];
  $mask=$_REQUEST['mask'];

	$db_conn = db_connect();
	$query = "insert into cells
      (name,description,atcc_nbr,organism,source,medium,temperature,atmosphere,date_create,created_by,project,note,mask)
       VALUES
      ('$name','$description','$atcc_nbr','$organism','$source','$medium','$temperature','$atmosphere','$date_create','$created_by','$project','$note','$mask')";
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	if (!$result)
    {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
     }
	header('Location: cells.php?id='.$id);
  }
  catch (Exception $e)
  {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}

function import_template() {
	$fileName ='cells_import.csv';
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName");
	echo "name,";
	echo "description,";
	echo "atcc_nbr,";
	echo "organism*,";
	echo "source,";
	echo "medium,";
	echo "temperature,";
	echo "atmosphere,";
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
			$atcc_nbr=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
			$organism=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
			$query="SELECT * FROM species WHERE id='$organism'";
			$result=$db_conn->query($query);
			if($result->num_rows==0) $organism=0;
			$source=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
			$medium=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
			$temperature=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
			$atmosphere=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
			$project=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
			$query="SELECT * FROM projects WHERE id='$project'";
			$result=$db_conn->query($query);
			if($result->num_rows==0) $project=1;
			$note=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
			$mask=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
			if($mask != 1) $mask=0;

			$query="INSERT INTO cells
       	 	(name,description,atcc_nbr,organism,source,medium,temperature,atmosphere,date_create,created_by,project,note,mask)
       		VALUES
      		('$name','$description','$atcc_nbr','$organism','$source','$medium','$temperature','$atmosphere','$date_create','$created_by','$project','$note','$mask')";
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
function edit_relation()
{
  try{
  	$id=$_REQUEST['id'];
    $module=get_record_from_name('modules','cells');
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
  $atcc_nbr = trim($_REQUEST['atcc_nbr']);
  $organism = $_REQUEST['organism'];
  $source = $_REQUEST['source'];
  $medium = $_REQUEST['medium'];
  $temperature = $_REQUEST['temperature'];
  $atmosphere = $_REQUEST['atmosphere'];
  $date_create = $_REQUEST['date_create'];
  $created_by=$_REQUEST['created_by'];
  $note=$_REQUEST['note'];
  $mask=$_REQUEST['mask'];

	$db_conn = db_connect();
	$query = "update cells
		    set name='$name',
			description='$description',
			atcc_nbr='$atcc_nbr',
			organism='$organism',
			source='$source',
			medium='$medium',
			temperature='$temperature',
			atmosphere='$atmosphere',
			date_update='$date_update',
			updated_by='$updated_by',
			project='$project',
			note='$note',
			mask='$mask'
			where id='$id'";

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
function delete()
{
  $db_conn=db_connect();
  if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
  {
    $query = "delete from cells where id = '{$_REQUEST['id']}'";
    $result = $db_conn->query($query);
  }
  elseif($_SESSION['selecteditemDel'])//multiple delete
  {
  	$selecteditemDel=$_SESSION['selecteditemDel'];
    unset($_SESSION['selecteditemDel']);
    $num_selecteditemDel=count($selecteditemDel);
    $module = get_id_from_name('modules','cells');
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
  	  if($relateditem_count==0&&$storage_count==0&&$order_count==0)
  	  {
  	  	$query = "delete from cells where id = '{$selecteditemDel[$i]}'";
    	$result = $db_conn->query($query);
  	  }
    }
  }
  header('Location: '.$_SESSION['url_1']);
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
		case "import":import();break;
		case "edit":edit();break;
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
  	$organism= get_record_from_id('species',$row['organism']);
    $project= get_record_from_id('projects',$row['project']);
    $created_by  = get_name_from_id('people',$row['created_by']);
    $updated_by = get_name_from_id('people',$row['updated_by']);
    $mask_array = array('no'=>'0','yes'=>'1');
	  foreach ($mask_array as $key=>$value) {
      if ($value == $row['mask']) {
        $mask= $key;
      }
    }
    $xls[]= ereg_replace("[\r,\n,\t]"," ",$row['id'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['description'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['atcc_nbr'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$organism['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['source'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['medium'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['temperature'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['atmosphere'])."\t".
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
  "description"."\t".
  "actt_nbr"."\t".
  "organism"."\t".
  "source"."\t".
  "medium"."\t".
  "temperature"."\t".
  "atmosphere"."\t".
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
?>