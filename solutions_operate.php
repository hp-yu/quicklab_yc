<?php
include('include/includes.php');
?>
<?php
 if ($_REQUEST['type']=='export_excel') {
 	if(!userPermission(3)) {
 	  header('location:'.$_SESSION['url_1']);
 	}
 	$query=$_SESSION['query'];
 	unset($_SESSION['query']);
 	export_excel('solutions',$query);
 	exit;
 }
?>
<?php
  do_html_header('Solution center-Quicklab');
  do_header();
  //do_leftnav();
  StandardForm();
  do_footer();
  do_html_footer();
?>
<?php
function StandardForm() {
?>
	<table width="100%" class="operate" >
	<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
	<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
	<tr><td colspan='2'><div align='center'><h2>Solution center&nbsp;&nbsp;<a href='<?php echo $_SESSION['url_1'];?>'><img
	 src='./assets/image/general/back.gif' alt='Back' border='0'/></a></h2></div></td></tr>
<?php
	processRequest();
}

function AddForm() {
	if(!userPermission('3')) {
		alert();
	}
	?>
  <form name='add' method='post' action=''>
	<tr><td colspan='2'><h3>Add new solution:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
      </tr>
      <tr>
        <td width='20%'>Synonym:</td>
        <td width='80%'><input type='text' name='synonym' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['synonym']))?>"/></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols="40" rows="3"><?php echo stripslashes($_POST['description']) ?></textarea></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols="40" rows="3"><?php echo stripslashes($_POST['note']) ?></textarea></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />&nbsp;&nbsp;<a href='<?php
        echo $_SESSION['url_1'];?>'><img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
    	</td>
      </tr>
      <?php HiddenInputs('created_by','date_create','add');?>
      </form></table>
      <?php
}

function EditForm() {
  $solution = get_record_from_id('solutions',$_REQUEST['id']);
  if(!userPermission('3')) {
  	alert();
  }
  ?>
  <script>
  function deletecomponent(f) {
  	var e=document.getElementById(f);
  	confirmVal = confirm("Are you sure to delete this component?")
  	if (!confirmVal) {
  		return
  	}
  	else {
  		e.action.value = "deletecomponent";
  		e.submit()
  	}
  }
  function editcomponent(f) {
  	var e=document.getElementById(f);
  	e.action.value = "editcomponent";
  	e.submit()
  }
  </script>
    <form id='edit' method='post' target="_self">
  	  <tr><td colspan='2'><h3>Edit solution:<?php echo $solution['name'];?>&nbsp;&nbsp;
  	  <a href="solutions_operate.php?type=calc&id=<?php echo $_REQUEST['id'];?>"><img src="./assets/image/general/calc-s.gif" alt="Calculate" border="0"/></a></h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' size="40" value="<?php
  echo stripslashes(htmlspecialchars($solution['name']));?>">*</td>
      </tr>
      <tr>
        <td width='20%'>Synonym:</td>
        <td width='80%'><input type='text' name='synonym' size="40" value="<?php
  echo stripslashes(htmlspecialchars($solution['synonym']));?>"></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td width='80%'><input type='text' name='description' size="40" value="<?php
  echo stripslashes(htmlspecialchars($solution['description']));?>"></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <?php HiddenInputs('updated_by','date_update','edit');?>
    </form>
    </table>
    <table width="100%" class="operate" style="margin-top:3pt">
      <tr>
        <td colspan="2"><h3><?php echo $solution['name'];?> components:</h3></td>
      </tr>
      <tr>
        <td></td>
        <td>Component</td>
        <td>M.W.</td>
        <td>Stock</td>
        <td>Final concentration</td>
        <td>Operate</td>
      </tr>
      <?php
        $db_conn=db_connect();
        $query="SELECT * FROM solution_units ORDER BY id";
        $result=$db_conn->query($query);
        $liquid_unit=array();
        while ($match=$result->fetch_assoc()) {
					$liquid_unit[$match['name']]=$match['id'];
        }
				$query="SELECT * FROM solution_components ORDER BY id ASC";
				$result=$db_conn->query($query);
				$n=1;
				while($component=$result->fetch_array()) {
					?>
					<form id="<?php echo $component['id']?>" method="POST" target="_self">
					<tr>
					<td><?php echo $n?></td>
					<td><input size="20" name="component" value="<?php echo $component['component']?>">&nbsp;&nbsp;
          </td>
          <td><input size="10" name="mw" value="<?php if ( $component['mw']) echo $component['mw'];?>">&nbsp;&nbsp;
          </td>
          <td>
          <select name="state" id="state">
          <option value="0">powder</option>
          <option value="1">liquid</option>
          </select>&nbsp;&nbsp;
          <input size="10" name="stock_value" value="<?php echo $component['stock_value']?>">&nbsp;&nbsp;
          <?php
						echo array_select('stock_unit',$liquid_unit,$component['stock_unit']);
					?>
          </td>
					<td><input size="10" name="final_value" value="<?php echo $component['final_value']?>">&nbsp;&nbsp;
					<?php
					  echo array_select('final_unit',$liquid_unit,$component['final_unit']);
					?></td>
					<td><input type='button' onclick="javascipt:editcomponent(<?php echo $component['id']?>)" name='Edit' value='Edit' />&nbsp;&nbsp;
					<input type='button' onclick="javascipt:deletecomponent(<?php echo $component['id']?>)" name='Delete' value='Delete' />
					<?php HiddenInputs('updated_by','date_update','');?>
					<input type="hidden" name="component_id" value="<?php echo $component['id']?>"></td>
					</tr>
					</form>
					<?php
					$n+=1;
				}
      ?>
      <form id="add" name="id" method="POST" action="" onclick="hideSuggestions();">
        <tr>
          <td>New</td>
    			<td>
    			<input size="20" type="text" name="component" id="component" value="">
          </td>
          <td>
    			<input size="10" type="text" name="mw" id="mw" value="">
          </td>
          <td>
          <select name="state" id="state">
          <option value="0">powder</option>
          <option value="1">liquid</option>
          </select>&nbsp;&nbsp;
          <input size="10" name="stock_value" value="">&nbsp;&nbsp;
          <?php
					  echo array_select('stock_unit',$liquid_unit,$component['stock_unit']);
					?>
          </td>
					<td>
					<input size="10" name="final_value" value="">&nbsp;&nbsp;
					<?php
					  echo array_select('final_unit',$liquid_unit,$component['final_unit']);
					?></td>
          <td><input type="submit" name="submit" value="Add"></td>
        </tr>
      <?php HiddenInputs('updated_by','date_update','add_component');?>
      </form>
    </table>
  <?php
}

function CalcForm() {
  $solution = get_record_from_id('solutions',$_REQUEST['id']);
  if(!userPermission('3')) {
  	alert();
  }
  $db_conn=db_connect();
  $query="SELECT * FROM solution_units ORDER BY id";
  $result=$db_conn->query($query);
  $liquid_unit=array();
  while ($match=$result->fetch_assoc()) {
  	$liquid_unit[]=$match['name'];
  }
  $powder_unit=array("%(w/w)");
  ?>
<script>
function change_state(){
	var liquid_unit=new Array();
	var powder_unit=new Array();
	<?php
		foreach ($liquid_unit as $key=>$value) {
			echo "liquid_unit.push('".$value."');";
		}
		foreach ($powder_unit as $key=>$value) {
			echo "powder_unit.push('".$value."');";
		}
	?>
	alert($(this).value());
	alert(powder_unit.length);
}
</script>
  	  <tr><td colspan='2'><h3>Solution:<?php echo $solution['name'];?>&nbsp;&nbsp;
  	  <a href="solutions_operate.php?type=edit&id=<?php echo $_REQUEST['id'];?>"><img src="./assets/image/general/edit-s.gif" alt="Edit" border="0"/></a></h3>
  	  </td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $solution['name'];?></td>
      </tr>
      <tr>
        <td width='20%'>Synonym:</td>
        <td width='80%'><?php echo $solution['synonym'];?></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td width='80%'><?php echo wordwrap($solution['description'],70,"<br/>");?></td>
      </tr>
    <tr><td colspan="2">
<script>
$(document).ready(function() {
	$("#calc_form").validate({
		rules:{
			mw:"number",
			final_value:{required:true,number:true},
			stock_value:"number",
			multiple:"number",
			final_volume:"number"
		},
		messages:{
			mw:"",
			final_value:"",
			stock_value:"",
			multiple:"",
			final_volume:""
		}
	});
});
</script>
    <form id="calc_form" name="calc_form" target=""/>
    <table width="100%">
      <tr>
        <td colspan="2"><h3>Calculate:</h3></td>
      </tr>
      <tr>
        <td></td>
        <td>Component</td>
        <td>M.W.</td>
        <td>Final</td>
        <td>Stock</td>
      </tr>
      <?php

				$query="SELECT * FROM solution_components ORDER BY id ASC";
				$result=$db_conn->query($query);
				$n=1;
				while($component=$result->fetch_array()) {
					?>
					<tr>
					<td><?php echo $n?></td>
					<td><?php echo $component['component']?>&nbsp;&nbsp;
					</td>
					<td><input size="10" name="mw" id="mw" value="<?php $component['mw'] ?>"/></td>
					<td><input size="10" name="final_value" id="final_value" value="<?php echo $component['final_value']?>">&nbsp;&nbsp;
					<?php
					  echo array_select('final_unit',$liquid_unit,$component['final_unit']);
					?>
					</td>
					<td>
					<?php
						$state=array("powder"=>"0","liquid"=>"1");
						echo "<select name='state' onchange='change_state()'>";
						foreach ($state as $key=>$value) {
							echo "<option value='$value'";
							if ($value == $default) {
								echo ' selected';
							}
							echo  ">$key</option>";
						}
  					echo "</select>";
					?>
					<input size="10" name="stock_value" value="">&nbsp;&nbsp;
					<?php
						echo array_select('stock_unit',$liquid_unit,$component['stock_unit']);
					?>
					</td>
					<td>
					<input type="text" size="10" name="result" value="" readonly>
					<select name="final_volume_unit_liquid" onchange="calc()">
      			<option value="1">L</option>
      			<option value="0.001" selected>mL</option>
      			<option value="0.000001">μL</option>
      			<option value="0.000000001">nL</option>
    			</select>
					</td>
					</tr>
					<?php
					$n+=1;
				}
      ?>
      <tr>
      <td></td>
      <td>H2O</td>
      <td colspan="3" align="right"></td>
      <td>
      <input type="text" size="10" name="result" value="" readonly>
      <select name="final_volume_unit_liquid" onchange="calc()">
      	<option value="1">L</option>
      	<option value="0.001" selected>mL</option>
      	<option value="0.000001">μL</option>
      	<option value="0.000000001">nL</option>
    	</select>
      </td>
      </tr>
      <tr>
          <td colspan="4">
          Final volume:
          <input size="5" name="multiple" id="multiple" value="1">&nbsp;×&nbsp;
          <input size="10" name="final_volume" id="final_volume" value="">&nbsp;
          <select name="final_volume_unit_liquid" onchange="calc()">
      			<option value="1">L</option>
      			<option value="0.001" selected>mL</option>
      			<option value="0.000001">μL</option>
      			<option value="0.000000001">nL</option>
    			</select></td>
        </tr>
    </table>
     </form>
    </tr></td>
    </table>
  <?php
}

function ImportForm()
{
  if(!userPermission('3')) {
  	alert();
  }
  ?>
  <form name='preview' method='post' action='' enctype="multipart/form-data">
	<tr><td colspan='2'><h3>Import from file (txt or csv format):</h3></td>
      </tr>
      <tr>
        <td width='20%'>File:</td>
        <td width='80%'><input type='file' name='file'/>*</td>
      </tr>
      <tr>
        <td>Separator:</td>
        <td><input type='radio' name='separator' value="tab" <?php
         if($_REQUEST['separator']=='tab'||!isset($_REQUEST['separator'])) echo "checked" ?>/>tab&nbsp;&nbsp;
        <input type='radio' name='separator' value="comma" <?php
        if($_REQUEST['separator']=='comma') echo "checked" ?>/>comma(,)</td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Preview' />
      </td></tr>
      <tr>
        <td colspan='2' ><span style="font-size:10pt;color:red;">ATTENTION:</span><br/>
        1. You can convert your data to plain text format (txt or csv) with fields separators from <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;EXCEL or other applications.<br/>
        2. Data cannot have fields separator or newline characters in it.<br/>
        3. Follow the format below as the first line in the file, or <a
      href='download.php?filename=solutions_import.csv'><span style="font-size:10pt;">DOWNLOAD</span></a> the template.</td>
      </tr>
      <tr>
        <td colspan='2'><table class="template" width='100%'>
      <tr><td class='results_header'>name</td><td class='results_header'>
	  address</td><td class='results_header'>
	  tel</td><td class='results_header'>
	  fax</td><td class='results_header'>
	  email</td><td class='results_header'>
	  url</td><td class='results_header'>
	  note</td></tr></table></td>
      </tr>
  </form></table>
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
  	if(isset($_REQUEST['project'])&&$_REQUEST['project']=='') {
  	  throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
  	}
    if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
      $db_conn=db_connect();
      $query="DROP TABLE IF EXISTS temp_solutions";
      $result=$db_conn->query($query);

      $query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_solutions SELECT * FROM solutions WHERE 0";
      $result=$db_conn->query($query);

      $query="ALTER TABLE temp_solutions MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
      $result=$db_conn->query($query);

      $file=file($_FILES['file']['tmp_name']);
      $row_num=count($file);

	  $separator=$_REQUEST['separator'];
	  $project=$_REQUEST['project'];
	  $mask=$_REQUEST['mask'];

      $separator=$_REQUEST['separator'];
	  if ($separator=="tab") $separator="\t";
	  elseif ($separator=="comma") $separator=",";
	  for($n=1;$n<$row_num;$n++) {
        $data=split($separator,$file[$n]);
       	$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
       	$address=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
       	$tel=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
       	$fax=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
       	$email=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
       	$url=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
       	$note=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");

       	$query="INSERT INTO temp_solutions
       	 	(name,address,tel,fax,email,url,note)
       		VALUES
      		('$name','$address','$tel','$fax','$email','$url','$note')";
       	$result=$db_conn->query($query);
       	if(!$result) {
      	  throw new Exception("There was a database error when executing<pre>$query</pre>") ;
        }
	  }

	  $rand=mt_rand();
	  $filename = "temp/solutions_import_$rand.txt";
      move_uploaded_file($_FILES['file']['tmp_name'],'./'.$filename);

      $query="SELECT * FROM temp_solutions";
      $results=$db_conn->query($query);
      $row_num=$results->num_rows;
      if ($results  && $results->num_rows) {
      	?>
      	<form name='import' method='post' action=''>
        <input type="hidden" name="action" value="import">
        <input type="hidden" name="filename" value="<?php echo $filename ?>">
        <input type="hidden" name="separator" value="<?php echo $separator ?>">
        <table width="100%" class="alert"><tr><td>Totally <?php echo $row_num ?> records, check the data carefully before import!
        <input type='submit' name='submit' value='import'></td></tr></table>
        <table width='100%' class='results'>
        <tr><td class='results_header'>name</td><td class='results_header'>
	    address</td><td class='results_header'>
	    tel</td><td class='results_header'>
	    fax</td><td class='results_header'>
	    email</td><td class='results_header'>
	    url</td><td class='results_header'>
	    note</td></tr>
	    <?php
	    while ($matches = $results->fetch_array()) {
	      echo "<tr>";
          echo "<td class='results'>".$matches['name']."</td>";
          echo "<td class='results'>".$matches['address']."</td>";
          echo "<td class='results'>".$project['tel']."</td>";
          echo "<td class='results'>".$matches['fax']."</td>";
          echo "<td class='results'>".$matches['email']."</td>";
          echo "<td class='results'>".$matches['url']."</td>";
          echo "<td class='results'>".$matches['note']."</td>";
       	  echo "</tr>";
	    }
	    echo "</table></form>";
      }
    }
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
	$db_conn=db_connect();
	for($n=1;$n<$row_num;$n++) {
		$data=split($separator,$file[$n]);
		$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
		$address=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
		$tel=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
		$fax=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
		$email=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
		$url=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
		$note=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");

		$query="INSERT INTO solutions
       	 	(name,address,tel,fax,email,url,note)
       		VALUES
      		('$name','$address','$tel','$fax','$email','$url','$note')";
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
function Add()
{
  try {
    if (!filled_out(array($_REQUEST['name']))) {
  	  throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
    }
    $name = $_REQUEST['name'];
    $synonym = $_REQUEST['synonym'];
    $description = $_REQUEST['description'];
    $date_create = $_REQUEST['date_create'];
	  $created_by=$_REQUEST['created_by'];
    $note=$_REQUEST['note'];
	  $db_conn = db_connect();
	  $query = "insert into solutions
      (name,synonym,description,date_create,created_by,note)
       VALUES
      ('$name','$synonym','$description','$date_create','$created_by','$note')";
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	  if (!$result) {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
     }
	   header('Location: solutions.php?id='.$id);
  }
  catch (Exception $e) {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}
function AddComponent() {
  try {
    if (!filled_out(array($_REQUEST['component'],$_REQUEST['final_value']))) {
  	  throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
    }
    $solution_id = $_REQUEST['id'];
    $component = $_REQUEST['component'];
    $final_value = $_REQUEST['final_value'];
		$final_unit=$_REQUEST['final_unit'];
    $date_update = $_REQUEST['date_update'];
	  $updated_by=$_REQUEST['updated_by'];
	  $db_conn = db_connect();
	  $query = "INSERT INTO solution_components
      (solution_id,component,final_value,final_unit)
       VALUES
      ('$solution_id','$component','$final_value','$final_unit')";
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	  if (!$result) {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
     }
     $query = "UPDATE solutions SET
     date_update='$date_update',
     updated_by='$updated_by'
     WHERE id='$solution_id'";
  	$result = $db_conn->query($query);
	  if (!$result) {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
     }
	   header('Location:#');
  }
  catch (Exception $e) {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}
function Edit()
{
  try {
    if (!filled_out(array($_REQUEST['name']))) {
  	  throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
    }
    $id=$_REQUEST['id'];
    $name = $_REQUEST['name'];
    $synonym = $_REQUEST['synonym'];
    $description = $_REQUEST['description'];
    $date_update = $_REQUEST['date_update'];
	  $updated_by=$_REQUEST['updated_by'];
    $note = $_REQUEST['note'];
	  $db_conn = db_connect();
	  $query = "UPDATE solutions SET
	    name='$name',
		  synonym='$synonym',
			description='$description',
			date_update='$date_update',
			updated_by='$updated_by',
			note='$note'
			WHERE id='$id'";
  	$result = $db_conn->query($query);
	  if (!$result) {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
    }
    header("location:#");
	  //header("Location:".$_SESSION['url_1']);
  }
  catch (Exception $e) {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}
function processRequest() {
	$type = $_REQUEST['type'];
	switch ($type) {
		case 'add': AddForm(); break;
		case 'detail': Detail(); break;
		case 'edit': EditForm(); break;
		case 'delete': DeleteForm(); break;
		case 'import': ImportForm(); break;
		case 'relation': EditRelationForm(); break;
		case 'calc': CalcForm(); break;
	}
	$action = $_POST['action'];
	switch ($action) {
		case 'add': Add(); break;
		case 'add_component': AddComponent(); break;
		case 'edit': Edit(); break;
		case 'editcomponent': EditComponent(); break;
		case 'delete': Delete(); break;
		case 'deletecomponent': DeleteComponent(); break;
		case 'import': Import(); break;
		case 'relation': EditRelation(); break;
		case 'calc': Calc(); break;
	}
}
function export_excel($module_name,$query)
{
  $db_conn=db_connect();
  $results = $db_conn->query($query);
  $num_rows=$results->num_rows;
  while($row = $results->fetch_array())
  {
    $xls[]= $row['id']."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['address'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['tel'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['fax'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['email'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['url']);
  }
  $title="id"."\t".
  "name"."\t".
  "address"."\t".
  "tel"."\t".
  "fax"."\t".
  "email"."\t".
  "url";

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
function  DeleteComponent() {
	try {
		$solution_id = $_REQUEST['id'];
		$component_id=$_REQUEST['component_id'];
		$date_update = $_REQUEST['date_update'];
		$updated_by=$_REQUEST['updated_by'];

		$db_conn = db_connect();
		$query="DELETE FROM solution_components WHERE id='$component_id'";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		$query = "UPDATE solutions SET
     date_update='$date_update',
     updated_by='$updated_by'
     WHERE id='$solution_id'";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		header("Location:#");
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function  EditComponent() {
	try {
		if (!filled_out(array($_REQUEST['component'],$_REQUEST['final_value']))) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$solution_id = $_REQUEST['id'];
		$component_id=$_REQUEST['component_id'];
		$component=$_REQUEST['component'];
		$final_value=$_REQUEST['final_value'];
	  $final_unit=$_REQUEST['final_unit'];
		$date_update = $_REQUEST['date_update'];
		$updated_by=$_REQUEST['updated_by'];

		$db_conn = db_connect();
		$query="UPDATE solution_components SET
	component='$component',
	final_value='$final_value',
	final_unit='$final_unit'
	WHERE id='$component_id'
	";
		$result = $db_conn->query($query);
    if (!$result) {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
    }
		$query = "UPDATE solutions SET
     date_update='$date_update',
     updated_by='$updated_by'
     WHERE id='$solution_id'";
		$result = $db_conn->query($query);
    if (!$result) {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
    }
		header("Location:#");
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
?>