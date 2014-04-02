<?php
include('include/includes.php');
include('include/bioinfo/sm.php');
?>
<?php
if ($_REQUEST['type']=='export_excel') {
	if(!userPermission(3)) {
		header('location:'.$_SESSION['url_1']);
	}
	$query=$_SESSION['query'];
	export_excel('primers',$query);
	exit;
}
if ($_REQUEST['type']=='import_template') {
	import_template();
	exit;
}
?>
<?php
  do_html_header_begin('Primers operate-Quicklab');
?>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript" src="include/bioinfo/sm_common.js"></script>
<script>
function cal_seq_len(){
	e1=document.getElementById('raw_seq');
	e2=document.getElementById('seq_len');
	len=removeuseless(e1.value).length;
	e2.innerHTML=len+' bp';
}
</script>
<script>
$(document).ready(function() {
	cal_seq_len();
});
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
    <form name='add_form' id="add_form" method='post' action=''>
    <table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Primers</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new primer:</h3></td>
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
        <td><textarea name='description' cols="50" rows="3"><?php echo stripslashes($_POST['description']) ?></textarea></td>
      </tr>
      <tr>
        <td>Orientation:</td>
        <td><?php
		$orientation=array(array("0","forward"),
					array("1","reverse"));
		echo option_select('orientation',$orientation,2,$_POST['orientation']);?>
        </td>
      </tr>
      <tr>
        <td>Concentration:</td>
        <td><input type='text' name='concentration' value="<?php echo stripslashes(htmlspecialchars($_POST['concentration']))?>"/></td>
      </tr>
      <tr>
        <td>Purity:</td>
        <td><input type='text' name='purity' value="<?php echo stripslashes(htmlspecialchars($_POST['purity']))?>"/></td>
      </tr>
      <tr><td>Sequence:</br><span id="seq_len" style="background-color:yellow"></span></td>
      <td><textarea id="raw_seq"  name='raw_seq' class="sequence" cols="80" rows="4" onchange="tidyup('raw_seq');cal_seq_len()"><?php echo stripslashes($_POST['raw_seq'])?></textarea></td>
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
  $primer = get_record_from_id('primers',$_REQUEST['id']);
  if(!userPermission('2',$primer['created_by']))
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
    <form name='edit_form' id="edit_form" method='post' action=''>
    <table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Primers</h2></div></td></tr>
  	  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($primer['name']));?>">*</td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
		$query= "select * from projects";
		echo query_select_choose('project', $query,'id','name',$primer['project']);?>
        </td></tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols='50' rows='3'><?php
     echo $primer['description'];?></textarea></td>
      </tr>
      <tr>
        <td>Orientation:</td>
        <td><?php
     $orientation = array( array( '0', 'Forward'),
		                   array( '1', 'Reverse'));
	echo option_select('orientation',$orientation,2,$primer['orientation'])?>
        </td>
      </tr>
      <tr>
        <td>Concentration:</td>
        <td><input type='text' name='concentration' value="<?php
     echo $primer['concentration'];?>"></td>
      </tr>
      <tr>
        <td>Purity:</td>
        <td><input type='text' name='purity' value='<?php
     echo $primer['purity'];?>'></td>
      </tr>
      <tr><td>Sequence:</br><span id="seq_len" style="background-color:yellow"></span></td>
      <td><textarea id="raw_seq"  name='raw_seq' class="sequence" cols='80' rows='4' onchange="tidyup('raw_seq');cal_seq_len()"><?php
     echo sm_tidyup($primer['sequence']);?></textarea></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols='50' rows='3'><?php
     echo $primer['note'];?></textarea></td>
      </tr>
      <tr>
        <td>Mask:</td>
        <td><?php
        $mask=array(array(1,'yes'),
        			array(0,'no'));
        echo option_select('mask',$mask,2,$primer['mask']);
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
  $primer = get_record_from_id('primers',$_REQUEST['id']);
  if(!userPermission('2',$primer['created_by']))
  {
  	alert();
  }
?>
  <form name="relation" method="post" action="" target="_self">
  
  <table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Primers</h2></div></td></tr>
    <tr><td colspan="2"><h3>Relate primer: <?php echo $primer['name']; ?> to these items below:</h3></td></tr>
    <tr><td width="200">
  	<select name="clipboardtarget[]" style="width:190px;font-size:7pt;" size="5" multiple
  	ondblclick="removeOption(document.getElementById('clipboardtarget[]'))">
  	<?php
      $db_conn = db_connect();
      $module=get_record_from_name('modules','primers');
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

function detail()
{
	if(!userPermission('4'))
    {
  	  alert();
    }
$primer = get_record_from_id('primers',$_REQUEST['id']);
?>
<form name='detail_form' id="detail_form" method='post' action=''>
<table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Primers</h2></div></td></tr>
      <tr><td colspan='2'><h3>Detail:
      <a href="primers_operate.php?type=edit&id=<?php echo $primer['id']?>"/><img src="./assets/image/general/edit.gif" border="0"/></a></h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $primer['name'];?></td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
		$project= get_record_from_id('projects',$primer['project']);
		echo $project['name'];?></td></tr>
      <tr>
        <td>Description:</td>
        <td><?php echo wordwrap($primer['description'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>Orientation:</td>
        <td><?php
     $orientation = array( array( '0', 'Forward'),
		                   array( '1', 'Reverse'));
	 for ($i=0; $i < 2; $i++)
      {
        if ($orientation[$i][0] == $primer['orientation'])
          {
            echo $orientation[$i][1];
          }
       }?>
        </td>
      </tr>
      <tr>
        <td>Concentration:</td>
        <td><?php echo $primer['concentration'];?></td>
      </tr>
      <tr>
        <td>Purity:</td>
        <td><?php echo $primer['purity'];?></td>
      </tr>
      <tr><td colspan="2">Sequence:</td></tr>
      <tr><td colspan="2" class="sequence"><?php echo nl2br(sm_tidyup($primer['sequence']));?></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><?php echo wordwrap($primer['note'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>Created by:</td><td><?php
		$people = get_name_from_id('people',$primer['created_by']);
		echo $people['name'].'  '.$primer['date_create'];?></td></tr>
     </tr>
      <tr>
        <td>Updated by:</td><td><?php
		$people = get_name_from_id('people',$primer['updated_by']);
		echo $people['name'].'  '.$primer['date_update'];?></td></tr>
	 <?php
       $db_conn = db_connect();
       $module=get_record_from_name('modules','primers');
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
  $primer = get_record_from_id('primers',$_REQUEST['id']);
  if(!userPermission('2',$primer['created_by']))
  {
  	alert();
  }
  if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
  {
	$module = get_id_from_name('modules','primers');
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
<tr><td colspan='2'><div align='center'><h2>Primers</h2></div></td></tr>";
    echo "<tr><td colspan='2'><h3>Are you sure to delete the primer: ";
    echo $primer['name'];
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
<tr><td colspan='2'><div align='center'><h2>Primers</h2></div></td></tr>";
		echo "<tr><td><h3>This primer related to ";
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
<tr><td colspan='2'><div align='center'><h2>Primers</h2></div></td></tr>";
    echo "<tr><td colspan='2'><h3>Are you sure to delete the $num_selecteditemDel primer(s)?<br>
    primer related to other items, storages, orders <br>can not be deleted.</h3></td></tr> ";
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
	document.download.submit();
}
</script>
<form name='preview' method='post' action='' enctype="multipart/form-data">
<table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Primers</h2></div></td></tr>
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
<tr><td colspan="2">*For the 'orientatioin', use 0 represents forward and 1 represents reverse, default 0.</td></tr>
<tr><td colspan="2">*For the 'project', use project id (find it in the projects module), default 'others'.</td></tr>
<tr><td colspan="2">*For the 'mask', use 0 represents no and 1 represents yes, default 0.</td></tr>
</table>
  <?php
  try{
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
  		$query="DROP TABLE IF EXISTS temp_primers";
  		$result=$db_conn->query($query);

  		$query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_primers SELECT * FROM primers WHERE 0";
  		$result=$db_conn->query($query);

  		$query="ALTER TABLE temp_primers MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
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
  				$orientation=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
  				if($orientation != 1) $orientation=0;
  				$concentration=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
  				$purity=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
  				$sequence=sm_remove_useless(mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312"));
  				$project=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
  				$query="SELECT * FROM projects WHERE id='$project'";
  				$result=$db_conn->query($query);
  				if($result->num_rows==0) $project=1;
  				$note=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
  				$mask=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
  				if($mask != 1) $mask=0;

  				$query="INSERT INTO temp_primers
       	 	(name,description,orientation,concentration,purity,sequence,date_create,created_by,project,note,mask)
       		VALUES
      		('$name','$description','$orientation','$concentration','$purity','$sequence','$date_create','$created_by','$project','$note','$mask')";
  				$result=$db_conn->query($query);
  				if(!$result) {
  					throw new Exception("There was a database error when executing<pre>$query</pre>") ;
  				}
  			}
  			$n++;
  		}
			//upload the file temporatory, delete it after import.
  		$rand=mt_rand();
  		$filename = "temp/primers_import_$rand.csv";
  		move_uploaded_file($_FILES['file']['tmp_name'],'./'.$filename);
			//query the records imported into the temp table
  		$query="SELECT * FROM temp_primers";
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
orientation*</td><td class='results_header'>
concentration</td><td class='results_header'>
purity</td><td class='results_header'>
sequence</td><td class='results_header'>
project*</td><td class='results_header'>
note</td><td class='results_header'>
mask*</td></tr>
	    <?php
	    //display the records in the temp table
	    while ($matches = $results->fetch_array()) {
	    	echo "<tr>";
	    	echo "<td class='results'>".$matches['name']."</td>";
	    	echo "<td class='results'>".$matches['description']."</td>";
	    	echo "<td class='results'>".$matches['orientation']."</td>";
	    	echo "<td class='results'>".$matches['concentration']."</td>";
	    	echo "<td class='results'>".$matches['purity']."</td>";
	    	if(strlen($matches['sequence'])<=30) {
	    		echo "<td class='results'>".chunk_split($matches['sequence'],10,"<br/>")."</td>";
	    	} else {
	    		echo "<td class='results'>".chunk_split(substr($matches['sequence'],0,30),10,"<br/>");
	    		echo "...[".strlen($matches['sequence'])." bp]</td>";
	    	}
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
	$fileName ='primers_import.csv';
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName");
	echo "name,";
	echo "description,";
	echo "orientation*,";
	echo "concentration,";
	echo "purity,";
	echo "sequence,";
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
    $project = $_REQUEST['project'];
    $description = $_REQUEST['description'];
	$orientation = $_REQUEST['orientation'];
  	$concentration = $_REQUEST['concentration'];
	$purity = $_REQUEST['purity'];
  	$raw_seq = $_REQUEST['raw_seq'];
	$date_create = $_REQUEST['date_create'];
	$created_by=$_REQUEST['created_by'];
    $note=$_REQUEST['note'];
    $mask=$_REQUEST['mask'];

    $sequence=sm_remove_useless($raw_seq);

	$db_conn = db_connect();
	$query = "insert into primers
      (name,description,orientation,concentration,purity,sequence,date_create,created_by,project,note,mask)
       VALUES
      ('$name','$description','$orientation','$concentration','$purity','$sequence','$date_create','$created_by','$project','$note','$mask')";
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	if (!$result)
    {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
     }
	header('Location: primers.php?id='.$id);
  }
  catch (Exception $e)
  {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
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
				$orientation=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
				if($orientation != 1) $orientation=0;
				$concentration=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
				$purity=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
				$sequence=sm_remove_useless(mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312"));
				$project=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
				$query="SELECT * FROM projects WHERE id='$project'";
				$result=$db_conn->query($query);
				if($result->num_rows==0) $project=1;
				$note=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
				$mask=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
				if($mask != 1) $mask=0;

				$query="INSERT INTO primers
       	 	(name,description,orientation,concentration,purity,sequence,date_create,created_by,project,note,mask)
       		VALUES
      		('$name','$description','$orientation','$concentration','$purity','$sequence','$date_create','$created_by','$project','$note','$mask')";
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
    $module=get_record_from_name('modules','primers');
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
  $orientation = $_REQUEST['orientation'];
  $concentration = $_REQUEST['concentration'];
  $purity = $_REQUEST['purity'];
  $raw_seq = $_REQUEST['raw_seq'];
  $date_update = $_REQUEST['date_update'];
  $updated_by = $_REQUEST['updated_by'];
  $note = $_REQUEST['note'];
  $mask = $_REQUEST['mask'];

	$sequence=sm_remove_useless($raw_seq);

	$db_conn = db_connect();
	$query = "update primers
		    set name='$name',
			description='$description',
			orientation='$orientation',
			concentration='$concentration',
			purity='$purity',
			sequence='$sequence',
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
    $query = "delete from primers where id = '{$_REQUEST['id']}'";
    $result = $db_conn->query($query);
  }
  elseif($_SESSION['selecteditemDel'])//multiple delete
  {
  	$selecteditemDel=$_SESSION['selecteditemDel'];
    unset($_SESSION['selecteditemDel']);
    $num_selecteditemDel=count($selecteditemDel);
    $module = get_id_from_name('modules','primers');
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
  	  	$query = "delete from primers where id = '{$selecteditemDel[$i]}'";
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
    $orientation_array = array( 'Forward'=>'0','Reverse'=>'1');
    foreach ($orientation_array as $key=>$value) {
      if ($value == $row['orientation']) {
        $orientation= $key;
      }
    }
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
    $xls[]= ereg_replace("[\r,\n,\t]"," ",$row['id'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['description'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$orientation)."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['concentration'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['purity'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['sequence'])."\t".
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
  "orientation"."\t".
  "concentration"."\t".
  "purity"."\t".
  "sequence"."\t".
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