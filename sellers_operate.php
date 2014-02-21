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
 	unset($_SESSION['query']);
 	export_excel('sellers',$query);
 	exit;
 }
?>
<?php
  do_html_header('Sellers-Quicklab');
  do_header();
  do_leftnav();
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
	<tr><td colspan='2'><div align='center'><h2>Sellers</h2></div></td></tr>
<?php
	processRequest();
}

function AddForm()
{
	if(!userPermission('3'))
    {
  	  alert();
    }
	?>
  <form name='add' method='post' action=''>
	<tr><td colspan='2'><h3>Add new sellers:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
      </tr>
      <tr>
        <td>Address:</td>
        <td><input type='text' name='address' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['address']))?>"/></td>
      </tr>
      <tr>
        <td>Telephone:</td>
        <td><input type='text' name='tel' value="<?php echo stripslashes(htmlspecialchars($_POST['tel']))?>"/></td>
      </tr>
      <tr>
        <td>Fax:</td>
        <td><input type='text' name='fax' value="<?php echo stripslashes(htmlspecialchars($_POST['fax']))?>"/></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input type='text' name='email' size="30" value="<?php echo stripslashes(htmlspecialchars($_POST['email']))?>"/></td>
      </tr>
      <tr>
        <td>Web site:(http://)</td>
        <td><input type='text' name='url' size="30" value="<?php echo stripslashes(htmlspecialchars($_POST['url']))?>"/></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols="40" rows="4"><?php echo stripslashes($_POST['note']) ?></textarea></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />&nbsp;&nbsp;<a href='<?php 
        echo $_SESSION['url_1'];?>'><img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
    	</td>
      </tr>
      <input type="hidden" name="action" value="add">
      </form></table>
      <?php
}

function EditForm()
{
  $seller = get_record_from_id('sellers',$_REQUEST['id']);
  if(!userPermission('3'))
  {
  	alert();
  }
  ?>
    <form name='edit' method='post' action=''>
  	  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' size="40" value="<?php
  echo stripslashes(htmlspecialchars($seller['name']));?>">*</td>
      </tr>
      <tr>
        <td>Address:</td>
        <td><input type='text' name='address' size="40" value="<?php
     echo $seller['address'];?>"></td>
      </tr>
      <tr>
        <td>Telephone:</td>
        <td><input type='text' name='tel' value='<?php
     echo $seller['tel'];?>'></td>
      </tr>
      <tr>
        <td>Fax:</td>
        <td><input type='text' name='fax' value='<?php
     echo $seller['fax'];?>'></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input type='text' name='email' size="30" value='<?php
     echo $seller['email'];?>'></td>
      </tr>
      <tr>
        <td>Web site:(http://)</td>
        <td><input type='text' name='url' size="30" value='<?php
     echo $seller['url'];?>'></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols='40' rows='4'><?php
     echo $seller['note'];?></textarea></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <input type="hidden" name="action" value="edit"/>
    </form></table> 
  <?php
}

function Detail()
{
	if(!userPermission('4'))
    {
  	  alert();
    }
  $seller = get_record_from_id('sellers',$_REQUEST['id']);
?>
      <tr><td colspan='2'><h3>Detail:
      <a href="sellers_operate.php?type=edit&id=<?php echo $seller['id']?>"/>
      <img src="./assets/image/general/edit.gif" border="0"/></a>
      </h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $seller['name'];?></td>
      </tr>
      <tr>
        <td>Address:</td>
        <td><?php echo $seller['address'];?></td>
      </tr>
      <tr>
        <td>Telephone:</td>
        <td><?php echo $seller['tel'];?></td>
      </tr>
      <tr>
        <td>Fax:</td>
        <td><?php echo $seller['fax'];?></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><?php echo $seller['email'];?></td>
      </tr>
      <tr>
        <td>Web site:</td>
        <td><?php echo "<a href='{$seller['url']}' target='_blank'>".$seller['url'];?></a></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><?php echo wordwrap($seller['note'],70,"<br/>");?></td>
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
      href='download.php?filename=sellers_import.csv'><span style="font-size:10pt;">DOWNLOAD</span></a> the template.</td>
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
      $query="DROP TABLE IF EXISTS temp_sellers";
      $result=$db_conn->query($query);
      
      $query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_sellers SELECT * FROM sellers WHERE 0";
      $result=$db_conn->query($query);

      $query="ALTER TABLE temp_sellers MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
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

       	$query="INSERT INTO temp_sellers 
       	 	(name,address,tel,fax,email,url,note)
       		VALUES 
      		('$name','$address','$tel','$fax','$email','$url','$note')"; 
       	$result=$db_conn->query($query);
       	if(!$result) {
      	  throw new Exception("There was a database error when executing<pre>$query</pre>") ;
        }
	  }
      
	  $rand=mt_rand();
	  $filename = "temp/sellers_import_$rand.txt";
      move_uploaded_file($_FILES['file']['tmp_name'],'./'.$filename);
	  
      $query="SELECT * FROM temp_sellers";
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

		$query="INSERT INTO sellers
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
    if (!filled_out(array($_REQUEST['name']))) 
    {
  	  throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
    }
    $name = $_REQUEST['name'];
    $address = $_REQUEST['address'];
    $tel = $_REQUEST['tel'];
    $fax = $_REQUEST['fax'];
    $email = $_REQUEST['email'];
    $url=$_REQUEST['url'];
    $note=$_REQUEST['note'];
    if($email!=''&&!valid_email($email))
  	{
  		throw new Exception("'$email' is not a valid email address,</br>"
  		.'- please go back and try again.');
  	}
	$db_conn = db_connect();
	$query = "insert into sellers 
      (name,address,tel,fax,email,url,note)
       VALUES 
      ('$name','$address','$tel','$fax','$email','$url','$note')"; 
  	$result = $db_conn->query($query);
  	$id=$db_conn->insert_id;
	if (!$result) 
    {
      throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
     }
	header('Location: sellers.php?id='.$id);
  }
  catch (Exception $e)
  {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}
function Edit()
{
  try {
    if (!filled_out(array($_REQUEST['name']))) 
    {
  	  throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
    }
    $id=$_REQUEST['id'];
    $name = $_REQUEST['name'];
    $address = $_REQUEST['address'];
    $tel = $_REQUEST['tel'];
    $fax = $_REQUEST['fax'];
    $email=$_REQUEST['email'];
    $url=$_REQUEST['url'];
    $note=$_REQUEST['note'];
    if($email!=''&&!valid_email($email))
  	{
  		throw new Exception("'$email' is not a valid email address,</br>"
  		.'- please go back and try again.');
  	}
	$db_conn = db_connect();
	$query = "update sellers set
	    name='$name',
		  address='$address',
			tel='$tel',
			fax='$fax',
			email='$email',
			url='$url',
			note='$note'
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
?>