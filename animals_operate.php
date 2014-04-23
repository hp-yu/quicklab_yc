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
 	export_excel('animals',$query);
 	exit;
 }
?>
<?php
  do_html_header_begin('Animals operate-Quicklab');
?>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<?php
  do_html_header_end();
  do_header();
  //do_leftnav();
  processRequest();
  do_rightbar();
  do_footer();
  do_html_footer();
?>
<?php
function AddForm()
{
	if(!userPermission('3')) {
  	  alert();
    }
	?>
<script type="text/javascript">
$.validator.setDefaults({	submitHandler: function() {
	document.add_form.action.value = "add";
	document.add_form.submit();
	//window.returnValue='ok';
	//window.close   ();
}});
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
</script>
<form name='add_form' id="add_form" method='post' target="">
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Animals</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new animal:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Strain:</td>
        <td width='80%'><?php
        $query="SELECT * FROM ani_strains ORDER BY name";
        $db_conn = db_connect();
  			$result = $db_conn->query($query);
  			?>
  			<select name='strain' id="strain" onchange="moveOptionToText(document.getElementById('strain'), document.getElementById('name'))">";
  			<?php
  			echo '<option value=""';
  			if($_POST['strain'] == ''||!$_POST['strain']) echo ' selected ';
        echo '>- Choose -</option>';
        for ($i=0; $i < $result->num_rows; $i++) {
          $option = $result->fetch_assoc();
          echo "<option value='{$option['id']}'";
          if ($option['id'] == $_POST['strain']) {
            echo ' selected';
          }
          $query="SELECT * FROM species WHERE id={$option['species']}";
          $result_species = $db_conn->query($query);
          $species = $result_species->fetch_assoc();
          echo ">".$option['name']." (".$species['name'].")</option>";
        }
        echo "</select>";
        ?>&nbsp;
        <a href="ani_strains_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add strains' title='Add strains' border='0'/></a>
        </td>
      </tr>
      <tr>
        <td>Name:</td>
        <td><input type='text' name='name' id="name" size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
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
        <td>Level:</td>
        <td><?php
		$health=array('General'=>'1','Clean'=>'2','SPF'=>'3','Sterile'=>'4');
		echo array_select_choose('health',$health,$_POST['health']);?>
        </td>
      </tr>
      <tr>
        <td>Gender:</td>
        <td><?php
		$gender=array('male'=>'1','female'=>'2');
		echo array_select_choose('gender',$gender,$_POST['gender']);?>
        </td>
      </tr>
      <tr>
        <td>Quantity:</td>
        <td><input type='text' name='qty' value="<?php echo stripslashes(htmlspecialchars($_POST['qty']))?>"/></td>
      </tr>
      <tr>
        <td>Weight:</td>
        <td><input type='text' name='weight' value="<?php echo stripslashes(htmlspecialchars($_POST['weight']))?>"/></td>
      </tr>
      <tr>
        <td>Age:</td>
        <td><input type='text' name='age' value="<?php echo stripslashes(htmlspecialchars($_POST['age']))?>"/></td>
      </tr>
      <tr>
        <td>Date of birth:</td>
        <td><input type='text' name='date_birth' value="<?php echo stripslashes(htmlspecialchars($_POST['date_birth']))?>"/>(YYYY-MM-DD)</td>
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
        echo $_SESSION['url_1'];?>'><img src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a>
    	</td>
      </tr>
      <?php HiddenInputs('created_by','date_create','add');?>
      </table>
      </form>
      <?php
}

function EditForm()
{
  $animal = get_record_from_id('animals',$_REQUEST['id']);
  if(!userPermission('2',$animal['created_by'])) {
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
function SetDate(e) {
	var arrivalDate=document.getElementById('date_arrival');
	var experimentDate=document.getElementById('date_experiment');
	var deathDate=document.getElementById('date_death');
	for(var i=0;i<e.options.length;i++){
		if(e.options[i].selected) {
			if (e.options[i].value=='1'&&arrivalDate.value=='') {
				arrivalDate.value="<?php echo date('Y-m-d');?>";
			}
			if (e.options[i].value=='2'&&experimentDate.value=='') {
				experimentDate.value="<?php echo date('Y-m-d');?>";
			}
			if (e.options[i].value=='3'&&deathDate.value=='') {
				deathDate.value="<?php echo date('Y-m-d');?>";
			}
		}
	}
}
</script>
    <form name='edit_form' id="edit_form" method='post' action=''>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Animals</h2></div></td></tr>
	  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Strain:</td>
        <td width='80%'><?php
        $query="SELECT * FROM ani_strains ORDER BY name";
        $db_conn = db_connect();
  			$result = $db_conn->query($query);
  			?>
  			<select name='strain' id="strain" onchange="moveOptionToText(document.getElementById('strain'), document.getElementById('name'))">";
  			<?php
  			echo '<option value=""';
  			if($animal['strain'] == ''||!$animal['strain']) echo ' selected ';
        echo '>- Choose -</option>';
        for ($i=0; $i < $result->num_rows; $i++) {
          $option = $result->fetch_assoc();
          echo "<option value='{$option['id']}'";
          if ($option['id'] == $animal['strain']) {
            echo ' selected';
          }
          $query="SELECT * FROM species WHERE id={$option['species']}";
          $result_species = $db_conn->query($query);
          $species = $result_species->fetch_assoc();
          echo ">".$option['name']." (".$species['name'].")</option>";
        }
        echo "</select>";
        ?>&nbsp;
        <a href="ani_strains_operate.php?type=add" target="_blank"><img src='./assets/image/general/add-s.gif' alt='Add strains'  title='Add strains'border='0'/></a>
        </td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name'  id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($animal['name']));?>">*</td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
		$query= "select * from projects";
		echo query_select_choose('project', $query,'id','name',$animal['project']);?>
        </td></tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols='50' rows='3'><?php
     echo $animal['description'];?></textarea></td>
      </tr>
      <tr>
        <td>Level:</td>
        <td><?php
		$health=array('General'=>'1','Clean'=>'2','SPF'=>'3','Sterile'=>'4');
		echo array_select_choose('health',$health,$animal['health']);?>
        </td>
      </tr>
      <tr>
        <td>Gender:</td>
        <td><?php
		$gender=array('male'=>'1','female'=>'2');
		echo array_select_choose('gender',$gender,$animal['gender']);?>
        </td>
      </tr>
      <tr>
        <td>Quantity:</td>
        <td><input type='text' name='qty' value="<?php echo stripslashes(htmlspecialchars($animal['qty']))?>"/></td>
      </tr>
      <tr>
        <td>Weight:</td>
        <td><input type='text' name='weight' value="<?php echo stripslashes(htmlspecialchars($animal['weight']))?>"/></td>
      </tr>
      <tr>
        <td>Age:</td>
        <td><input type='text' name='age' value="<?php echo stripslashes(htmlspecialchars($animal['age']))?>"/></td>
      </tr>
      <tr>
        <td>State:</td>
        <td><?php
				$state=array('arrival'=>'1','on experiment'=>'2','dead'=>'3');
				?>
				<select name='state' onchange="SetDate(document.getElementById('state'))">
				<?php
  			echo '<option value=""';
  			if($animal['state'] == '') echo ' selected ';
  			echo '>- Choose -</option>';
  			foreach ($state as $key=>$value) {
    			echo "<option value=$value";
    			if ($value == $animal['state']) {
      			echo ' selected';
    			}
    			echo  ">$key</option>";
  			}
  			echo "</select>";
				//echo array_select_choose('state',$state,$animal['state']);?>
        </td>
      </tr>
      <tr>
        <td>Date of birth:</td>
        <td><input type='text' name='date_birth' value="<?php
        if ($animal['date_birth']!='0000-00-00') {
        	echo stripslashes(htmlspecialchars($animal['date_birth']));
        }
        ?>"/>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Date of arrival:</td>
        <td><input type='text' name='date_arrival' value="<?php
        if ($animal['date_arrival']!='0000-00-00') {
        	echo stripslashes(htmlspecialchars($animal['date_arrival']));
        }
        ?>"/>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Date of experiment:</td>
        <td><input type='text' name='date_experiment' value="<?php
        if ($animal['date_experiment']!='0000-00-00') {
        	echo stripslashes(htmlspecialchars($animal['date_experiment']));
        }
        ?>"/>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Date of death:</td>
        <td><input type='text' name='date_death' value="<?php
        if ($animal['date_death']!='0000-00-00') {
        	echo stripslashes(htmlspecialchars($animal['date_death']));
        }
        ?>"/>(YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols='50' rows='3'><?php
     echo $animal['note'];?></textarea></td>
      </tr>
      <tr>
        <td>Mask:</td>
        <td><?php
        $mask=array(array(1,'yes'),array(0,'no'));
        echo option_select('mask',$mask,2,$animal['mask']);
        ?>
        </td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <?php HiddenInputs('updated_by','date_update','edit');?>
    </form></table>
  <?php
}

function EditRelationForm()
{
  $animal = get_record_from_id('animals',$_REQUEST['id']);
  if(!userPermission('2',$animal['created_by']))
  {
  	alert();
  }
?>
  <form name="relation" method="post" action="" target="_self">
 	<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Animals</h2></div></td></tr>
    <tr><td colspan="2"><h3>Relate animal: <?php echo $animal['name']; ?> to these items below:</h3></td></tr>
    <tr><td width="200">
  	<select name="clipboardtarget[]" style="width:190px;font-size:7pt;" size="5" multiple
  	ondblclick="removeOption(document.getElementById('clipboardtarget[]'))">
  	<?php
      $db_conn = db_connect();
      $module=get_record_from_name('modules','animals');
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
  	<?php HiddenInputs('','','editrelation');?>
  </form></table>
<?php
}

function Detail()
{
	if(!userPermission('4'))
    {
  	  alert();
    }
    $animal = get_record_from_id('animals',$_REQUEST['id']);
?>
<form name='detail_form' id="detail_form" method='post' action=''>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Animals</h2></div></td></tr>
      <tr><td colspan='2'><h3>Detail:
      <a href="animals_operate.php?type=edit&id=<?php echo $animal['id']?>"/><img src="./assets/image/general/edit.gif" border="0"/></a></h3></td>
      </tr>
      <tr>
        <td>Strain:</td><td><?php
		$strain= get_record_from_id('ani_strains',$animal['strain']);
		echo "<a href='ani_strains_operate.php?type=detail&id={$animal['strain']}' target='_blank'/>".$strain['name']."</a>";?></td></tr>
      <tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $animal['name'];?></td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
		$project= get_record_from_id('projects',$animal['project']);
		echo $project['name'];?></td></tr>
      <tr>
        <td>Description:</td>
        <td><?php echo wordwrap($animal['description'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>level:</td>
        <td><?php
     		$health=array('General'=>'1','Clean'=>'2','SPF'=>'3','Sterile'=>'4');
	 			foreach($health as $key=>$value) {
	 				if ($value==$animal['health']) {
	 					echo $key;
	 				}
	 			}?>
        </td>
      </tr>
      <tr>
        <td>Gender:</td>
        <td><?php
     		$gender=array('male'=>'1','female'=>'2');
	 			foreach($gender as $key=>$value) {
	 				if ($value==$animal['gender']) {
	 					echo $key;
	 				}
	 			}?>
        </td>
      </tr>
      <tr>
        <td>Quantity:</td>
        <td><?php echo $animal['qty'];?></td>
      </tr>
      <tr>
        <td>Weight:</td>
        <td><?php echo $animal['weight'];?></td>
      </tr>
      <tr>
        <td>Age:</td>
        <td><?php echo $animal['age'];?></td>
      </tr>
      <tr>
        <td>State:</td>
        <td><?php
     		$state=array('arrival'=>'1','on experiment'=>'2','dead'=>'3');
	 			foreach($state as $key=>$value) {
	 				if ($value==$animal['state']) {
	 					echo $key;
	 				}
	 			}?>
        </td>
      </tr>
      <tr>
        <td>Date of birth:</td>
        <td><?php echo datezero($animal['date_birth']);?></td>
      </tr>
      <tr>
        <td>Date of arrival:</td>
        <td><?php echo datezero($animal['date_arrival']);?></td>
      </tr>
      <tr>
        <td>Date of experiment:</td>
        <td><?php echo datezero($animal['date_experiment']);?></td>
      </tr>
      <tr>
        <td>Date of death:</td>
        <td><?php echo datezero($animal['date_death']);?></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><?php echo wordwrap($animal['note'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>Created by:</td><td><?php
		$people = get_name_from_id('people',$animal['created_by']);
		echo $people['name'].'  '.datezero($animal['date_create']);?></td></tr>
     </tr>
      <tr>
        <td>Updated by:</td><td><?php
		$people = get_name_from_id('people',$animal['updated_by']);
		echo $people['name'].'  '.datezero($animal['date_update']);?></td></tr>
	 <?php
       $db_conn = db_connect();
       $module=get_record_from_name('modules','animals');
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
	 src='./assets/image/general/back.gif' alt='Back'  title='Back' border='0'/></a>
        </td>
      </tr>
    </table>
    </form>
<?php
}
function DeleteForm()
{
  $animal = get_record_from_id('animals',$_REQUEST['id']);
  if(!userPermission('2',$animal['created_by']))
  {
  	alert();
  }
  if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
  {
	$module = get_id_from_name('modules','animals');
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
	<tr><td colspan='2'><div align='center'><h2>Animals</h2></div></td></tr>";
    echo "<tr><td colspan='2'><h3>Are you sure to delete the animal: ";
    echo $animal['name'];
	echo "?</h3></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />";
    HiddenInputs('','',"delete");
    echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back'  title='Back' border='0'/></a></td></tr>";
	}
	else
	{
		echo "<form name='edit' method='post' action=''>";
		echo "<table width='100%' class='operate' >
		<tr><td colspan='2'><div align='center'><h2>Animals</h2></div></td></tr>";
		echo "<tr><td colspan='2'><h3>This animal related to ";
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
      src='./assets/image/general/back.gif' alt='Back'  title='Back' border='0'/></a></td></tr>";
	}
	echo "</table></form>";
  }
  elseif($_SESSION['selecteditemDel'])//multiple delete
  {
		$num_selecteditemDel=count($_SESSION['selecteditemDel']);
	echo "<form name='edit' method='post' action=''>";
	echo "<table width='100%' class='operate' >
	<tr><td colspan='2'><div align='center'><h2>Animals</h2></div></td></tr>";
    echo "<tr><td colspan='2'><h3>Are you sure to delete the $num_selecteditemDel animal(s)?<br>
    animal related to other items, storages, orders <br>can not be deleted.</h3></td></tr> ";
	echo "<tr><td colspan='2'><input type='submit' name='Submit' value='Submit' />";
    HiddenInputs('','',"delete");
    echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back'  title='Back' border='0'/></a></td></tr>";
	echo "</table></form>";
  }
}

function Add()
{
  try {
  	if (!filled_out(array($_REQUEST['name'],$_REQUEST['created_by']))) {
  		throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
  	}
  	$strain = $_REQUEST['strain'];
  	$name = $_REQUEST['name'];
  	$project = $_REQUEST['project'];
  	$description = $_REQUEST['description'];
  	$health = $_REQUEST['health'];
  	$gender = $_REQUEST['gender'];
  	$qty = $_REQUEST['qty'];
  	$weight = $_REQUEST['weight'];
  	$age = $_REQUEST['age'];
  	$date_birth = $_REQUEST['date_birth'];
  	$date_create = $_REQUEST['date_create'];
  	$created_by=$_REQUEST['created_by'];
  	$note=$_REQUEST['note'];
  	$mask=$_REQUEST['mask'];

	  $db_conn = db_connect();
	  $query = "insert into animals
      (strain,name,description,health,gender,qty,weight,age,date_birth,date_create,created_by,project,note,mask)
       VALUES
      ('$strain','$name','$description','$health','$gender','$qty','$weight','$age','$date_birth','$date_create','$created_by','$project','$note','$mask')";
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	if (!$result)
    {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
     }
	header('Location: animals.php?id='.$id);
  }
  catch (Exception $e)
  {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}
function Import()
{
  try {
	$filename = $_POST['filename'];
    $file=file("./".$filename);
    unlink("./".$filename);
    $row_num=count($file);

    $separator=$_REQUEST['separator'];
	if ($separator=="tab") $separator="\t";
	elseif ($separator=="comma") $separator=",";
	$date_create=date('Y-m-d');
	$db_conn=db_connect();
    $query = "select * from users where username = '{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
    $created_by=$match['people_id'];
    for($n=1;$n<$row_num;$n++) {
    	$data=split($separator,$file[$n]);
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

      $query="INSERT INTO animals
       	 	(name,description,orientation,concentration,purity,sequence,date_create,created_by,project,note,mask)
       		VALUES
      		('$name','$description','$orientation','$concentration','$purity','$sequence','$date_create','$created_by','$project','$note','$mask')";
      $result=$db_conn->query($query);
      if(!$result) {
      	throw new Exception("There was a database error when executing<pre>$query</pre>") ;
      }
	}
    header('Location: '.$_SESSION['url_1']);
  }
  catch (Exception $e)
  {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}
function EditRelation()
{
  try{
  	$id=$_REQUEST['id'];
    $module=get_record_from_name('modules','animals');
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
function Edit()
{
	try {
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['updated_by'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$id=$_REQUEST['id'];
		$strain = $_REQUEST['strain'];
  	$name = $_REQUEST['name'];
  	$project = $_REQUEST['project'];
  	$description = $_REQUEST['description'];
  	$health = $_REQUEST['health'];
  	$gender = $_REQUEST['gender'];
  	$qty = $_REQUEST['qty'];
  	$weight = $_REQUEST['weight'];
  	$age = $_REQUEST['age'];
  	$state = $_REQUEST['state'];
  	$date_birth = $_REQUEST['date_birth'];
  	$date_arrival = $_REQUEST['date_arrival'];
  	$date_experiment = $_REQUEST['date_experiment'];
  	$date_death = $_REQUEST['date_death'];
  	$date_update = $_REQUEST['date_update'];
  	$updated_by=$_REQUEST['updated_by'];
  	$note=$_REQUEST['note'];
  	$mask=$_REQUEST['mask'];

		$db_conn = db_connect();
		$query = "UPDATE animals SET
		  strain='$strain',
		  name='$name',
			description='$description',
			health='$health',
			gender='$gender',
			qty='$qty',
			weight='$weight',
			age='$age',
			state='$state',
			date_birth='$date_birth',
			date_arrival='$date_arrival',
			date_experiment='$date_experiment',
			date_death='$date_death',
			date_update='$date_update',
			updated_by='$updated_by',
			project='$project',
			note='$note',
			mask='$mask'
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
function Delete()
{
  $db_conn=db_connect();
  if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
  {
    $query = "delete from animals where id = '{$_REQUEST['id']}'";
    $result = $db_conn->query($query);
  }
  elseif($_SESSION['selecteditemDel'])//multiple delete
  {
  	$selecteditemDel=$_SESSION['selecteditemDel'];
    unset($_SESSION['selecteditemDel']);
    $num_selecteditemDel=count($selecteditemDel);
    $module = get_id_from_name('modules','animals');
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
  	  	$query = "delete from animals where id = '{$selecteditemDel[$i]}'";
    	$result = $db_conn->query($query);
  	  }
    }
  }
  header('Location: '.$_SESSION['url_1']);
}
function HiddenInputs($people,$date,$action)
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
function processRequest()
{
	$type = $_REQUEST['type'];
	if ($type == "add")
		{
			AddForm();
		}
	if ($type == "detail")
		{
			Detail();
		}
	if ($type == "edit")
		{
			EditForm();
		}
	if ($type == "delete")
		{
			DeleteForm();
		}
	if ($type == "relation")
		{
			EditRelationForm();
		}
	if ($type == "import")
		{
			ImportForm();
		}
	$action = $_POST['action'];
	if ($action == "add")
		{
			Add();
		}
	if ($action == "import")
		{
			Import();
		}
	if ($action == "detail")
		{
			Detail();
		}
	if ($action == "edit")
		{
			Edit();
		}
	if ($action == "editrelation")
		{
			EditRelation();
		}
	if ($action == "delete")
		{
			Delete();
		}
}
function export_excel($module_name,$query)
{
  $db_conn=db_connect();
  $results = $db_conn->query($query);
  $num_rows=$results->num_rows;
  while($row = $results->fetch_array())
  {
		$query="SELECT name from ani_strains WHERE id='{$row['strain']}'";
		$result = $db_conn->query($query);
		$strain=$result->fetch_assoc();
		$health_array=array('General'=>'1','Clean'=>'2','SPF'=>'3','Sterile'=>'4');
	 	foreach($health_array as $key=>$value) {
	 		if ($value==$row['health']) {
	 			$health=$key;
	 		}
	 	}
    $gender_array = array( 'male'=>'1','female'=>'2');
    foreach ($gender_array as $key=>$value) {
      if ($value == $row['gender']) {
        $gender= $key;
      }
    }
    $state_array=array('arrival'=>'1','on experiment'=>'2','dead'=>'3');
	 	foreach($state_array as $key=>$value) {
	 		if ($value==$row['state']) {
	 			$state=$key;
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
    ereg_replace("[\r,\n,\t]"," ",$strain['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['description'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$health)."\t".
    ereg_replace("[\r,\n,\t]"," ",$gender)."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['qty'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['weight'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['age'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$state)."\t".
    ereg_replace("[\r,\n,\t]"," ",datezero($row['date_birth']))."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_arrival'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_experiment'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_death'])."\t".
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
  "strain"."\t".
  "description"."\t".
  "Level"."\t".
  "gender"."\t".
  "qty"."\t".
  "weight"."\t".
  "age"."\t".
  "state"."\t".
  "date_birth"."\t".
  "date_arrival"."\t".
  "date_experiment"."\t".
  "date_death"."\t".
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