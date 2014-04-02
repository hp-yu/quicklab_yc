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
 	export_excel('ani_strains',$query);
 	exit;
 }
?>
<?php
  do_html_header_begin('Animal strains operate-Quicklab');
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
	if(!userPermission('3'))
    {
  	  alert();
    }
	?>
<script type="text/javascript">
$(document).ready(function() {
	$("#add_form").validate({
		rules: {
			name: "required",
			species: "required"
		},
		messages: {
			name: {required: 'required'},
			species: {required: 'required'}
		}});
});
</script>
  <form name='add_form' id="add_form" method='post' action=''>
  <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Animal strains</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new animal strain:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Strain name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols="60" rows="4"><?php echo stripslashes($_POST['description']) ?></textarea></td>
      </tr>
      <tr>
        <td>Species:</td><td><?php
		$query= "SELECT * FROM species ORDER BY name";
		echo query_select_choose('species', $query,'id','name',$_POST['species']);?>*
        </td>
      </tr>
      <tr>
        <td>Genes&Alleles:</td>
        <td><input type='text' name='genes_alleles' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['genes_alleles']))?>"/></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />&nbsp;&nbsp;<a href='<?php 
        echo $_SESSION['url_1'];?>'><img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
    	  </td>
      </tr>
      <?php HiddenInputs('created_by','date_create','add');?>
      </table></form>
      <?php
}
function HiddenInputs($people,$date,$action)
{
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
function EditForm()
{
  $ani_strains = get_record_from_id('ani_strains',$_REQUEST['id']);
  if(!userPermission('3')) {
  	alert();
  }
  ?>
 <script type="text/javascript">
$(document).ready(function() {
	$("#edit_form").validate({
		rules: {
			name: "required",
			species: "required"
		},
		messages: {
			name: {required: 'required'},
			species: {required: 'required'}
		}});
});
</script>
    <form name='edit_form' id="edit_form" method='post' action=''>
    <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Animal strains</h2></div></td></tr>
  	  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($ani_strains['name']));?>">*</td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols='60' rows='4'><?php
     echo $ani_strains['description'];?></textarea></td>
      </tr>
      <tr>
        <td>Species:</td><td><?php
		$query= "select * from species";
		echo query_select_choose('species', $query,'id','name',$ani_strains['species']);?>*
        </td>
      </tr>
      <tr>
        <td>Genes&Alleles:</td>
        <td><input type='text' name='genes_alleles' size="40" value='<?php
     echo $ani_strains['genes_alleles'];?>'></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <?php HiddenInputs('updated_by','date_update','edit');?>
    </form></table> 
  <?php
}

function Detail()
{
	if(!userPermission('4'))
    {
  	  alert();
    }
  $ani_strains = get_record_from_id('ani_strains',$_REQUEST['id']);
?>
<form name='detail_form' id="detail_form" method='post' action=''>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Animal strains</h2></div></td></tr>
      <tr><td colspan='2'><h3>Detail:
      <a href="ani_strains_operate.php?type=edit&id=<?php echo $ani_strains['id']?>"/>
      <img src="./assets/image/general/edit.gif" border="0"/></a></h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $ani_strains['name'];?></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><?php echo wordwrap($ani_strains['description'],70,"<br/>");?></td>
      </tr>
      <tr>
        <td>Species:</td><td><?php
				$species= get_record_from_id('species',$ani_strains['species']);
				echo $species['name'];?></td>
		  </tr>
      <tr>
        <td>Genes&Alleles:</td>
        <td><?php echo $ani_strains['genes_alleles'];?></td>
      </tr>
      <tr>
        <td colspan='2'><a href='<?php echo $_SESSION['url_1'];?>'><img 
	 src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
        </td>
      </tr>
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
  <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Animal strains</h2></div></td></tr>
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
      href='download.php?filename=ani_strains_import.csv'><span style="font-size:10pt;">DOWNLOAD</span></a> the template.</td>
      </tr>
      <tr>
        <td colspan='2'><table class="template" width='100%'>
      <tr><td class='results_header'>name</td><td class='results_header'>
	  description</td><td class='results_header'>
	  species*</td><td class='results_header'>
	  genes_alleles</td></tr></table></td>
      </tr>
      <tr><td colspan="2">*For the species, use species id (find it in the species module).</td></tr>
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
      $query="DROP TABLE IF EXISTS temp_ani_strains";
      $result=$db_conn->query($query);
      
      $query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_ani_strains SELECT * FROM ani_strains WHERE 0";
      $result=$db_conn->query($query);

      $query="ALTER TABLE temp_ani_strains MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
      $result=$db_conn->query($query);
      
      $file=file($_FILES['file']['tmp_name']);
      $row_num=count($file);
	  
      $separator=$_REQUEST['separator'];
	  if ($separator=="tab") $separator="\t";
	  elseif ($separator=="comma") $separator=",";
	  $date_create=date('Y-m-d');
  	$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
  	$result = $db_conn->query($query);
  	$match=$result->fetch_assoc();
  	$created_by=$match['people_id'];
	  for($n=1;$n<$row_num;$n++) {
        $data=split($separator,$file[$n]);   	 
       	$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
       	$description=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
  			$species=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
  			$query="SELECT * FROM species WHERE id='$species'";
  			$result=$db_conn->query($query);
  			if($result->num_rows==0) $species=0;
       	$genes_alleles=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");

       	$query="INSERT INTO temp_ani_strains 
       	 	(name,description,species,genes_alleles,date_create,created_by)
       		VALUES 
      		('$name','$description','$species','$genes_alleles','$date_create','$created_by')"; 
       	$result=$db_conn->query($query);
       	if(!$result) {
      	  throw new Exception("There was a database error when executing<pre>$query</pre>") ;
        }
	  }
      
	  $rand=mt_rand();
	  $filename = "temp/ani_strains_import_$rand.txt";
      move_uploaded_file($_FILES['file']['tmp_name'],'./'.$filename);
	  
      $query="SELECT * FROM temp_ani_strains";
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
	    description</td><td class='results_header'>
	    species*</td><td class='results_header'>
	    genes_alleles</td></tr>
	    <?php
	    while ($matches = $results->fetch_array()) {
	      echo "<tr>";
          echo "<td class='results'>".$matches['name']."</td>"; 
          echo "<td class='results'>".$matches['description']."</td>";   
          $query="SELECT * FROM species WHERE id='{$matches['species']}'";
	    	  $result=$db_conn->query($query);
	    	  $species=$result->fetch_assoc();
	    	  echo "<td class='results'>".$species['name']."</td>"; 
          echo "<td class='results'>".$project['tel']."</td>";
          echo "<td class='results'>".$matches['genes_alleles']."</td>";  
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
	  $date_create=date('Y-m-d');
  	$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
  	$result = $db_conn->query($query);
  	$match=$result->fetch_assoc();
  	$created_by=$match['people_id'];
	  for($n=1;$n<$row_num;$n++) {
        $data=split($separator,$file[$n]);   	 
       	$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
       	$description=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
  			$species=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
  			$query="SELECT * FROM species WHERE id='$species'";
  			$result=$db_conn->query($query);
  			if($result->num_rows==0) $species=0;
       	$genes_alleles=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");

       	$query="INSERT INTO ani_strains 
       	 	(name,description,species,genes_alleles,date_create,created_by)
       		VALUES 
      		('$name','$description','$species','$genes_alleles','$date_create','$created_by')"; 
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
    if (!filled_out(array($_REQUEST['name'],$_REQUEST['species']))) 
    {
  	  throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
    }
    $name = $_REQUEST['name'];
    $description = $_REQUEST['description'];
    $species = $_REQUEST['species'];
    $genes_alleles = $_REQUEST['genes_alleles'];
	  $date_create = $_REQUEST['date_create'];
	  $created_by=$_REQUEST['created_by'];

	$db_conn = db_connect();
	$query = "insert into ani_strains 
      (name,description,species,genes_alleles,date_create,created_by)
       VALUES 
      ('$name','$description','$species','$genes_alleles','$date_create','$created_by')"; 
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	if (!$result) {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
     }
	header('Location: ani_strains.php?id='.$id);
  }
  catch (Exception $e)
  {
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
    $description = $_REQUEST['description'];
    $species = $_REQUEST['species'];
    $genes_alleles = $_REQUEST['genes_alleles'];
		$date_update = $_REQUEST['date_update'];
  	$updated_by = $_REQUEST['updated_by'];

	$db_conn = db_connect();
	$query = "UPDATE ani_strains SET
	    name='$name',
		  description='$description',
			species='$species',
			genes_alleles='$genes_alleles',
			date_update='$date_update',
			updated_by='$updated_by'
			where id='$id'";
				  
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
	if ($type == "import") 
		{
			ImportForm();
		}
	if ($type == "relation") 
		{
			EditRelationForm();
		}
	$action = $_POST['action'];
	if ($action == "add") 
		{
			Add();
		}
	if ($action == "detail") 
		{
			Detail();
		}
	if ($action == "edit") 
		{
			Edit();
		}
	if ($action == "import") 
		{
			Import();
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
  	$query="SELECT name from species WHERE id='{$row['species']}'";
		$result = $db_conn->query($query);
		$species=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['created_by']}'";
		$result = $db_conn->query($query);
		$created_by=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['updated_by']}'";
		$result = $db_conn->query($query);
		$updated_by=$result->fetch_assoc();
		
    $xls[]= $row['id']."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['description'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$species['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['genes_alleles'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$created_by['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_create'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$updated_by['name'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['date_update']);
  }
  $title="id"."\t".
  "name"."\t".
  "description"."\t".
  "species"."\t".
  "genes_alleles"."\t".
  "created_by"."\t".
  "date_create"."\t".
  "updated_by"."\t".
  "date_update";

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