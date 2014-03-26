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
 	export_excel('abook',$query);
 	exit;
 }
 if ($_REQUEST['type']=='import_template') {
 	import_template();
 	exit;
 }
?>
<?php
  do_html_header_begin('Address book operate-Quicklab');
?>
<script src="include/jquery/lib/jquery.js" type="text/javascript"></script>
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
			name: "required"
		},
		messages: {
			name: {required: 'required'}
		}});
});
</script>
  <form name='add_form' id="add_form" method='post' action=''>
  <table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Address book</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input type='text' name='email' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['email']))?>"/></td>
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
        <td>Mobile:</td>
        <td><input type='text' name='mobile' value="<?php echo stripslashes(htmlspecialchars($_POST['mobile']))?>"/></td>
      </tr>
      <tr>
        <td>Company:</td>
        <td><input type='text' name='company' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['company']))?>"/></td>
      </tr>
      <tr>
        <td>Address:</td>
        <td><input type='text' name='address' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['address']))?>"/></td>
      </tr>
      <tr>
        <td>City:</td>
        <td><input type='text' name='city' value="<?php echo stripslashes(htmlspecialchars($_POST['city']))?>"/></td>
      </tr>
      <tr>
        <td>Post code:</td>
        <td><input type='text' name='postcode' value="<?php echo stripslashes(htmlspecialchars($_POST['postcode']))?>"/></td>
      </tr>
      <tr>
        <td>Country:</td>
        <td><input type='text' name='country' value="<?php echo stripslashes(htmlspecialchars($_POST['country']))?>"/></td>
      </tr>
      <tr>
        <td>Web site:(http://)</td>
        <td><input type='text' name='web' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['web']))?>"/></td>
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
      </table></form>
      <?php
}

function EditForm()
{
  $abook = get_record_from_id('abook',$_REQUEST['id']);
  if(!userPermission('3'))
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
	<tr><td colspan='2'><div align='center'><h2>Address book</h2></div></td></tr>
  	  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($abook['name']));?>">*</td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input type='text' name='email' size="40" value='<?php echo $abook['email'];?>'></td>
      </tr>
      <tr>
        <td>Telephone:</td>
        <td><input type='text' name='tel' value='<?php	echo $abook['tel'];?>'></td>
      </tr>
      <tr>
        <td>Fax:</td>
        <td><input type='text' name='fax' value='<?php	echo $abook['fax'];?>'></td>
      </tr>
      <tr>
        <td>Mobile:</td>
        <td><input type='text' name='mobile' value='<?php	echo $abook['mobile'];?>'></td>
      </tr>
      <tr>
        <td>Company:</td>
        <td><input type='text' name='company' size="40" value='<?php	echo $abook['company'];?>'></td>
      </tr>
      <tr>
        <td>Address:</td>
        <td><input type='text' name='address' size="40" value="<?php echo $abook['address'];?>"></td>
      </tr>
      <tr>
        <td>City:</td>
        <td><input type='text' name='city' value="<?php echo $abook['city'];?>"></td>
      </tr>
      <tr>
        <td>Post code:</td>
        <td><input type='text' name='postcode' value="<?php echo $abook['postcode'];?>"></td>
      </tr>
      <tr>
        <td>Country:</td>
        <td><input type='text' name='country' value="<?php echo $abook['country'];?>"></td>
      </tr>
      <tr>
        <td>Web site:(http://)</td>
        <td><input type='text' name='web' size="40" value='<?php echo $abook['web'];?>'></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols='40' rows='4'><?php echo $abook['note'];?></textarea></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <input type="hidden" name="action" value="edit"/>
    </table></form> 
  <?php
}

function Detail()
{
	if(!userPermission('4'))
    {
  	  alert();
    }
  $abook = get_record_from_id('abook',$_REQUEST['id']);
?>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Address book</h2></div></td></tr>
      <tr><td colspan='2'><h3>Detail:
      <a href="abook_operate.php?type=edit&id=<?php echo $abook['id']?>"/>
      <img src="./assets/image/general/edit.gif" border="0"/></a>
      </h3></td>
      </tr>
      <tr>
        <td width='20%'>Name:</td>
        <td width='80%'><?php echo $abook['name'];?></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><?php echo $abook['email'];?></td>
      </tr>
      <tr>
        <td>Telephone:</td>
        <td><?php echo $abook['tel'];?></td>
      </tr>
      <tr>
        <td>Fax:</td>
        <td><?php echo $abook['fax'];?></td>
      </tr>
      <tr>
        <td>Mobile:</td>
        <td><?php echo $abook['mobile'];?></td>
      </tr>
      <tr>
        <td>Company:</td>
        <td><?php echo $abook['company'];?></td>
      </tr>
      <tr>
        <td>Address:</td>
        <td><?php echo $abook['address'];?></td>
      </tr>
      <tr>
        <td>City:</td>
        <td><?php echo $abook['city'];?></td>
      </tr>
      <tr>
        <td>Post code:</td>
        <td><?php echo $abook['postcode'];?></td>
      </tr>
      <tr>
        <td>Country:</td>
        <td><?php echo $abook['country'];?></td>
      </tr>
      <tr>
        <td>Web site:</td>
        <td><?php echo "<a href='{$abook['web']}' target='_blank'>".$abook['web'];?></a></td>
      </tr>
      <tr>
        <td>Note:</td>
        <td><?php echo wordwrap($abook['note'],70,"<br/>");?></td>
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
	<tr><td colspan='2'><div align='center'><h2>Address book</h2></div></td></tr>
		<tr>
			<td colspan='2'><h3>Import from file (txt or csv format):</h3></td>
  	</tr>
    <tr>
    	<td width='20%'>File:</td>
      <td width='80%'><input type='file' name='file'/>*</td>
    </tr>
    <tr>
    	<td>Separator:</td>
    	<td><input type='radio' name='separator' value="comma" 
<?php 
if($_REQUEST['separator']=='comma'||!isset($_REQUEST['separator'])) echo "checked" 
?>
			/>comma(,)&nbsp;&nbsp;
<input type='radio' name='separator' value="tab" 
<?php
if($_REQUEST['separator']=='tab') echo "checked"
?>
			/>tab&nbsp;&nbsp;
      </td>
    </tr>
    <tr>
    	<td colspan='2'><input type='submit' name='Submit' value='Preview' />
      </td>
    </tr>
    </form>
    <form name="download" method="POST" target="_self">
    <tr>
    	<td colspan='2' ><span style="font-size:10pt;color:red;">ATTENTION:</span><br/>
        1. You can convert your data to plain text format (txt or csv) with fields separators from <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;EXCEL or other applications.<br/>
        2. Data cannot have fields separator or newline characters in it.<br/>
        3. Follow the format below as the first line in the file, or <a href='#' onclick="submit()"><span style="font-size:10pt;">DOWNLOAD</span></a> the template.</td>
        <input type="hidden" name="type" value="import_template"/>
    </tr>
    </form>
    <tr>
    	<td colspan='2'><table class="template" width='100%'>
    <tr><td class='results_header'>name</td><td class='results_header'>
    email</td><td class='results_header'>
    tel</td><td class='results_header'>
	  fax</td><td class='results_header'>
	  mobile</td><td class='results_header'>
	  company</td><td class='results_header'>
	  address</td><td class='results_header'>
	  city</td><td class='results_header'>
	  postcode</td><td class='results_header'>
	  country</td><td class='results_header'>
	  web</td><td class='results_header'>
	  note</td></tr></table></td>
    </tr>
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
	if(isset($_REQUEST['project'])&&$_REQUEST['project']=='') {
		throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
	}
	if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
		$db_conn=db_connect();
		$query="DROP TABLE IF EXISTS temp_abook";
		$result=$db_conn->query($query);

		$query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_abook SELECT * FROM abook WHERE 0";
		$result=$db_conn->query($query);

		$query="ALTER TABLE temp_abook MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
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
			$email=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
			$tel=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
			$fax=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
			$mobile=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
			$company=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
			$address=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
			$city=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
			$postcode=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
			$country=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
			$web=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
			$note=mb_convert_encoding(addslashes(trim($data[11])),"utf-8","gb2312");

			$query="INSERT INTO temp_abook
       	 	(`name`,`email`,`tel`,`fax`,`mobile`,`company`,`address`,`city`,`postcode`,`country`,`web`,`note`,`date_create`,`created_by`)
       VALUES 
      ('$name','$email','$tel','$fax','$mobile','$company','$address','$city','$postcode','$country','$web','$note','$date_create','$created_by')"; 
			$result=$db_conn->query($query);
			if(!$result) {
				throw new Exception("There was a database error when executing<pre>$query</pre>") ;
			}
		}

		$rand=mt_rand();
		$filename = "temp/abook_import_$rand.txt";
		move_uploaded_file($_FILES['file']['tmp_name'],'./'.$filename);

		$query="SELECT * FROM temp_abook";
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
   email</td><td class='results_header'>
   tel</td><td class='results_header'>
	 fax</td><td class='results_header'>
	 mobile</td><td class='results_header'>
	 company</td><td class='results_header'>
	 address</td><td class='results_header'>
	 city</td><td class='results_header'>
	 postcode</td><td class='results_header'>
	 country</td><td class='results_header'>
	 web</td><td class='results_header'>
	 note</td></tr>
<?php
while ($matches = $results->fetch_array()) {
	echo "<tr>";
	echo "<td class='results'>".$matches['name']."</td>";
	echo "<td class='results'>".$matches['email']."</td>";
	echo "<td class='results'>".$matches['tel']."</td>";
	echo "<td class='results'>".$matches['fax']."</td>";
	echo "<td class='results'>".$matches['mobile']."</td>";
	echo "<td class='results'>".$matches['company']."</td>";
	echo "<td class='results'>".$matches['address']."</td>";
	echo "<td class='results'>".$matches['city']."</td>";
	echo "<td class='results'>".$matches['postcode']."</td>";
	echo "<td class='results'>".$matches['country']."</td>";
	echo "<td class='results'>".$matches['web']."</td>";
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
		$date_create=date('Y-m-d');
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$created_by=$match['people_id'];
		
		for($n=1;$n<$row_num;$n++) {
			$data=split($separator,$file[$n]);
			$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
			$email=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
			$tel=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
			$fax=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
			$mobile=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
			$company=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
			$address=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
			$city=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
			$postcode=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
			$country=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
			$web=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
			$note=mb_convert_encoding(addslashes(trim($data[11])),"utf-8","gb2312");

			$query="INSERT INTO abook
       	 	(`name`,`email`,`tel`,`fax`,`mobile`,`company`,`address`,`city`,`postcode`,`country`,`web`,`note`,`date_create`,`created_by`)
       VALUES 
      ('$name','$email','$tel','$fax','$mobile','$company','$address','$city','$postcode','$country','$web','$note','$date_create','$created_by')"; 
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
		$email = $_REQUEST['email'];
		$tel = $_REQUEST['tel'];
		$fax = $_REQUEST['fax'];
		$mobile = $_REQUEST['mobile'];
		$company = $_REQUEST['company'];
		$address = $_REQUEST['address'];
		$city = $_REQUEST['city'];
		$postcode = $_REQUEST['postcode'];
		$country = $_REQUEST['country'];
		$web = $_REQUEST['web'];
		$note=$_REQUEST['note'];
		
		$db_conn = db_connect();
    $query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$created_by=$match['people_id'];
		$date_create=date("Y-m-d");
		
		if($email!=''&&!valid_email($email))
		{
			throw new Exception("'$email' is not a valid email address,</br>"
			.'- please go back and try again.');
		}
		$query = "insert into abook
      (`name`,`email`,`tel`,`fax`,`mobile`,`company`,`address`,`city`,`postcode`,`country`,`web`,`note`,`date_create`,`created_by`)
       VALUES 
      ('$name','$email','$tel','$fax','$mobile','$company','$address','$city','$postcode','$country','$web','$note','$date_create','$created_by')"; 
		$result = $db_conn->query($query);
		$id=$db_conn->insert_id;
		if (!$result)
		{
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		header('Location: abook.php?id='.$id);
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
		$email = $_REQUEST['email'];
		$tel = $_REQUEST['tel'];
		$fax = $_REQUEST['fax'];
		$mobile = $_REQUEST['mobile'];
		$company = $_REQUEST['company'];
		$address = $_REQUEST['address'];
		$city = $_REQUEST['city'];
		$postcode = $_REQUEST['postcode'];
		$country = $_REQUEST['country'];
		$web = $_REQUEST['web'];
		$note=$_REQUEST['note'];

		$db_conn = db_connect();
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$updated_by=$match['people_id'];
		$date_update=date("Y-m-d");

		if($email!=''&&!valid_email($email))
		{
			throw new Exception("'$email' is not a valid email address,</br>"
			.'- please go back and try again.');
		}
		$db_conn = db_connect();
		$query = "update abook set
	    name='$name',
	    email='$email',
	    tel='$tel',
			fax='$fax',
			mobile='$mobile',
			company='$company',
		  address='$address',
		  city='$city',
		  postcode='$postcode',
			country='$country',
			web='$web',
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
	switch ($type) {
		case "add":AddForm();break;
		case "detail":Detail();break;
		case "edit":EditForm();break;
		case "delete":DeleteForm();break;
		case "import":ImportForm();break;
		default:break;
	}
	$action = $_POST['action'];
	switch ($action) {
		case "add":Add();break;
		case "edit":Edit();break;
		case "import":Import();break;
		case "detail":Detail();break;
		case "delete":Delete();break;
		default:break;
	}
}

function import_template() {
	$fileName ='abook_import.csv';
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName");
	echo "name,";
	echo "email,";
	echo "tel,";
	echo "fax,";
	echo "mobile,";
	echo "company,";
	echo "address,";
	echo "city,";
	echo "postcode,";
	echo "country,";
	echo "web,";
	echo "note,";
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
    ereg_replace("[\r,\n,\t]"," ",$row['email'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['tel'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['fax'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['mobile'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['company'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['address'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['city'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['postcode'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['country'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['web'])."\t".
    ereg_replace("[\r,\n,\t]"," ",$row['note']);
  }
  $title="id"."\t".
  "name"."\t".
  "email"."\t".
  "tel"."\t".
  "fax"."\t".
  "mobile"."\t".
  "company"."\t".
  "address"."\t".
  "city"."\t".
  "postcode"."\t".
  "country"."\t".
  "web"."\t".
  "note";

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