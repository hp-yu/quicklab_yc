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
 	export_excel('sellers',$query);
 	exit;
 }
?>
  <html>
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Planner-Quicklab</title>
	<link href="css/general.css" rel="stylesheet" type="text/css" />
<script>
function task_submit() {
	window.returnValue='ok';
	window.close   ();
}
function showcalendar(obj) {
	var dv=window.showModalDialog("include/calendar.htm","44","center:1;help:no;status:no;dialogHeight:246px;dialogWidth:216px;scroll:no")
  if (dv) {if (dv=="null") obj.value='';else obj.value=dv;}
}
</script>
<script type="text/javascript" src="include/js/moveoptions.js"></script>
	<BASE target='_self'>
  </head>
  <body leftmargin="5" topmargin="5">
<?php

  StandardForm();


  do_html_footer();
?>
<?php
function StandardForm()
{
	processRequest();
}

function AddTaskForm() {
	$project=$_REQUEST['project'];
?>
<form method="POST" target="" >
<table>
<tr><td colspan="2"><b>Add task:</b></td></tr>
</tr>
<tr>
<td>Name: </td>
<td><input type="text" name="name" /></td>
</tr>
<tr>
<td>Description:</td>
<td><textarea name='description' cols='40' rows='4'></textarea></td>
</tr>
<tr>
<td>Start date:</td>
<td><INPUT style="CURSOR: pointer" onclick=showcalendar(this,this) readOnly type="text" name="startdate" value="<?php echo date("Y-m-d")?>"/></td>
</tr>
<tr>
<td>End date:</td>
<td><INPUT style="CURSOR: pointer" onclick=showcalendar(this,this) readOnly type="text" name="enddate" /></td>
</tr>
<tr>
<td>Parent:</td>
<td>
<select name="parent" >
<?php
	$db_conn=db_connect();
	$query= "SELECT id FROM planner_tasks WHERE `root`=1 AND `project`=$project";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	$root = $match['id'];
	$task=display_tree($root,$project);
	foreach ($task as $key=>$value) {
		echo "<option value='$key'>$value</option>";
	}
?>
</select>
</td>
</tr>
<tr>
<td>People:</td>
<td>
	<?php
	$db_conn=db_connect();
	$left_content=array();
	$right_content=array();
	$query= "select a.id, a.name from people a, planner_project_people b WHERE a.id=b.people_id AND b.project_id=$project ORDER BY CONVERT(a.name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$left_content[$match['id']]=$match['name'];
	}
	echo move_options_table(array('name'=>'people[]','width'=>120,'size'=>8,'content'=>$left_content),array('name'=>'people2task[]','width'=>120,'size'=>8,'content'=>$right_content));
	?>
</td>
</tr>
<tr>
<td colspan='2'><input type='submit' value='Submit' onclick="task_submit()" onmouseover="select_all_options('people2task[]')"/>
</td>
</tr>
</table>
<input type="hidden" name="action" value="addtask"/>
</form>
<?php
}

function AddTask() {
	$name=$_REQUEST['name'];
	$description=$_REQUEST['description'];
	$startdate=$_REQUEST['startdate'];
	$enddate=$_REQUEST['enddate'];
	$parent=$_REQUEST['parent'];
	$date_create=date("Y-m-d H:i:s");
	$db_conn = db_connect();
	$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	$created_by=$match['people_id'];

	try {
		if (!empty($name)&&count($_REQUEST['people2task'])>0&&!empty($startdate)&&!empty($enddate)&&(strtotime($enddate)>=strtotime($startdate))) {
			//START TRANSACTION
			$db_conn ->autocommit(false);
			$query="SELECT * FROM planner_tasks WHERE `id`=$parent FOR UPDATE";
			$rs=$db_conn->query($query);
			$parent=$rs->fetch_assoc();
			//insert task
			$query="UPDATE planner_tasks SET `right`=`right`+2 WHERE (`right`>{$parent['right']}-1) AND `project`={$parent['project']};";
			$rs=$db_conn->query($query);
			if (!$rs) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
			$query="UPDATE planner_tasks SET `left`=`left`+2 WHERE (`left`>{$parent['right']}-1) AND `project`={$parent['project']};";
			$rs=$db_conn->query($query);
			if (!$rs) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
			$query="INSERT INTO planner_tasks
	SET `left`={$parent['right']},
	`right`={$parent['right']}+1,
	`name`='$name',
	`project`='{$parent['project']}',
	`description`='$description',
	`startdate`='$startdate',
	`enddate`='$enddate',
	`date_create`='$date_create',
	`created_by`='$created_by';";
			$rs=$db_conn->query($query);
			$task_id=$db_conn->insert_id;
			if (!$rs) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
			//insert task related people
			for($i=0;$i<count($_REQUEST['people2task']); $i++) {
				$query="INSERT INTO planner_task_people
      (`task_id`,`people_id`)
      VALUES
      ('$task_id','".$_REQUEST['people2task'][$i]."')";
				$rs = $db_conn->query($query);
				if (!$rs) {
					throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
				}
			}
			$db_conn->commit();
		}
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function DeleteTask() {
	try {
		$task=$_REQUEST['task'];
		$project=$_REQUEST['project'];
		$db_conn = db_connect();
		//START TRANSACTION
		$db_conn->autocommit(FALSE);
		$query = "SELECT * FROM planner_tasks WHERE id=$task FOR UPDATE";
		$rs=$db_conn->query($query);
		$task=$rs->fetch_assoc();
		$query="UPDATE planner_tasks SET `right`=`right`-2 WHERE (`right`>{$task['right']}) AND `project`=$project;";
		$db_conn->query($query);
		$query="UPDATE planner_tasks SET `left`=`left`-2 WHERE (`left`>{$task['left']}) AND `project`=$project;";
		$db_conn->query($query);
		$query = "DELETE FROM planner_tasks WHERE `id`='{$task['id']}'";
		$db_conn->query($query);
		$query = "DELETE FROM planner_task_people WHERE 'task_id'='{$task['id']}'";
		$db_conn->query($query);
		$db_conn->COMMIT();
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function EditTask() {
	$id=$_REQUEST['task'];
	$name=$_REQUEST['name'];
	$description=$_REQUEST['description'];
	$startdate=$_REQUEST['startdate'];
	$enddate=$_REQUEST['enddate'];
	$date_update=date("Y-m-d H:i:s");
	$db_conn = db_connect();
	$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	$updated_by=$match['people_id'];

	try {
		if (!empty($name)&&count($_REQUEST['people2task'])>0&&!empty($startdate)&&!empty($enddate)&&(strtotime($enddate)>=strtotime($startdate))) {
			//start transaction
			$db_conn ->autocommit(false);
			//update task
			$query="UPDATE planner_tasks
	SET `name`='$name',
	`description`='$description',
	`startdate`='$startdate',
	`enddate`='$enddate',
	`date_update`='$date_update',
	`updated_by`='$updated_by'
	WHERE id=$id;";
			$rs=$db_conn->query($query);
			if (!$rs) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
			//update task related people
			$query= "DELETE FROM planner_task_people WHERE `task_id`='$id'";
			$rs=$db_conn->query($query);
			if (!$rs) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
			for($i=0;$i<count($_REQUEST['people2task']); $i++) {
				$query="INSERT INTO planner_task_people
      (`task_id`,`people_id`)
      VALUES
      ('$id','".$_REQUEST['people2task'][$i]."')";
				$rs = $db_conn->query($query);
				if (!$rs) {
					throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
				}
			}
			$db_conn->commit();
		}
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}

function EditTaskForm() {
	$task=$_REQUEST['task'];
	$project=$_REQUEST['project'];
	$db_conn=db_connect();
	$query= "SELECT id FROM planner_tasks WHERE `root`=1 AND `project`=$project";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	$root = $match['id'];
	$query= "SELECT * FROM planner_tasks WHERE `id`=$task";
	$rs=$db_conn->query($query);
	$task=$rs->fetch_assoc();
?>
<form method="POST" target="" >
<table>
<tr><td colspan="2"><b>Edit task:</b></td></tr>
</tr>
<tr>
<td>Name</td>
<td><input type="text" name="name" value="<?php echo $task['name']?>"/></td>
</tr>
<tr>
<td>Description:</td>
<td><textarea name='description' cols='40' rows='4'><?php echo $task['description'];?></textarea></td>
</tr>
<tr>
<td>Start date:</td>
<td><INPUT style="CURSOR: pointer" onclick=showcalendar(this,this) readOnly type="text" name="startdate" value="<?php echo $task['startdate']?>"/></td>
</tr>
<tr>
<td>End date:</td>
<td><INPUT style="CURSOR: pointer" onclick=showcalendar(this,this) readOnly type="text" name="enddate" value="<?php echo $task['enddate']?>"/></td>
</tr>
<tr>
<td>People:</td>
<td>
	<?php
	$db_conn=db_connect();
	$left_content=array();
	$right_content=array();
	$query= "select a.id, a.name from people a, planner_project_people b WHERE a.id=b.people_id AND b.project_id=$project ORDER BY CONVERT(a.name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$left_content[$match['id']]=$match['name'];
	}
	$query= "select a.id, a.name from people a, planner_task_people b WHERE a.id=b.people_id AND b.task_id={$task['id']} ORDER BY CONVERT(a.name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$right_content[$match['id']]=$match['name'];
	}
	echo move_options_table(array('name'=>'people[]','width'=>120,'size'=>8,'content'=>$left_content),array('name'=>'people2task[]','width'=>120,'size'=>8,'content'=>$right_content));
	?>
</td>
</tr>
<tr>
<td colspan='2'><input type='submit' value='Submit' onclick="task_submit()" onmouseover="select_all_options('people2task[]')"/>
</td>
</tr>
</table>
<input type="hidden" name="action" value="edittask"/>
<input type="hidden" name="task" value="<?php echo $task['id']?>"/>
</form>
<?php
}

function DeleteTaskForm() {
	$task=$_REQUEST['task'];
	$project=$_REQUEST['project'];
	$db_conn=db_connect();
	$query= "SELECT * FROM planner_tasks WHERE `id` = $task";
	$rs=$db_conn->query($query);
	$task=$rs->fetch_assoc();
?>
<form method="POST" target="" >
<table>
<tr><td colspan="2"><b>Delete task:</b></td></tr>
</tr>
<tr>
<td colspan="2">
<?php
$query = "SELECT * FROM planner_tasks WHERE `left`<={$task['left']} AND `right`>={$task['right']} AND `project` = $project ORDER BY `left`";
$rs=$db_conn->query($query);
$n=0;
while ($match=$rs->fetch_assoc()) {
	if ($n>0) {
		echo ">";
	}
	echo $match['name'];
	$n++;
}
?>
</td>
</tr>
<tr>
<td colspan='2'><input type='submit' value='Submit'  onclick="task_submit()"/>
</td>
</tr>
</table>
<input type="hidden" name="action" value="deletetask"/>
</form>
<?php
}

function display_tree($root,$project)  {
// �õ���ڵ������ֵ
$db_conn=db_connect();
$result = $db_conn->query("SELECT * FROM planner_tasks WHERE id=$root AND project=$project;");
$row = $result->fetch_assoc();

// ׼��һ��յ���ֵ���?
$right = array();
$task=array();

// ��ø������������ڵ�?
$result = $db_conn->query("SELECT * FROM planner_tasks WHERE `left` BETWEEN {$row['left']} AND {$row['right']} AND project=$project ORDER BY `left` ASC;");

// ��ʾÿһ��
while ($row = $result->fetch_assoc())
{
// only check stack if there is one
if (count($right)>0)
{
// ��������Ƿ�Ӧ�ý��ڵ��Ƴ��ջ
while ($right[count($right)-1]<$row['right'])
{
array_pop($right);
}
}

// �����ʾ�ڵ�����?
$task[$row['id']]=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',count($right)).$row['name'];

// �����ڵ���뵽��ջ��?
$right[] = $row['right'];
}
return $task;
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

function processRequest() {
		$action = $_POST['action'];
	if ($action == "edittask")
		{
			EditTask();
		}
	if ($action == "deletetask")
		{
			DeleteTask();
		}
	if ($action == "edit")
		{
			Edit();
		}
	if ($action == "addtask")
		{
			AddTask();
		}
	if ($action == "editrelation")
		{
			EditRelation();
		}
	if ($action == "delete")
		{
			Delete();
		}
	$type = $_REQUEST['type'];
	if ($type == "edittaskform")
		{
			EditTaskForm();
		}
	if ($type == "deletetaskform")
		{
			DeleteTaskForm();
		}
	if ($type == "addtaskform")
		{
			AddTaskForm();
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