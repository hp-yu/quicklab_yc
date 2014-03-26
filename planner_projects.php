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
  do_html_header('Planner-Quicklab');
?>
<script type="text/javascript" src="include/js/moveoptions.js"></script>
<script type="text/javascript" src="include/js/showcalendar.js"></script>
<?php
  StandardForm();
  do_rightbar();

  do_html_footer();
?>
<?php
function StandardForm()
{
	processRequest();
}

function AddForm()
{
	do_header();
  //do_leftnav();
	if(!userPermission('3'))
    {
  	  alert();
    }
	?>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Planner</h2></div></td></tr>
  <form name='add' method='post' action=''>
	<tr><td colspan='2'><h3>Add new:</h3></td>
  </tr>
  <tr>
  <td width='20%'>Name:</td>
  <td width='80%'><input type='text' name='name' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
  </tr>
  <td>Start date:</td>
	<td><INPUT style="CURSOR: pointer" onclick=showcalendar(this) readOnly type="text" name="startdate" value="<?php echo date("Y-m-d")?>"/></td>
	</tr>
	<tr>
	<td>End date:</td>
	<td><INPUT style="CURSOR: pointer" onclick=showcalendar(this) readOnly type="text" name="enddate" /></td>
	</tr>
<tr>
<td>People:</td>
<td>
	<?php
	$db_conn=db_connect();
	$left_content=array();
	$right_content=array();
	$query="SELECT id,name FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$left_content[$match['id']]=$match['name'];
	}
	echo move_options_table(array('name'=>'people[]','width'=>120,'size'=>8,'content'=>$left_content),array('name'=>'people2project[]','width'=>120,'size'=>8,'content'=>$right_content));
	?>
</td>
</tr>
  <tr>
  <td colspan='2'><input type='submit' value='Submit' onmouseover="select_all_options('people2project[]')">&nbsp;&nbsp;<a href='<?php
  echo $_SESSION['url_1'];?>'><img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
  </td>
  </tr>
  <input type="hidden" name="action" value="add">
  </form>
</table>
<?php
	do_footer();
}

function EditForm() {
	do_header();
  //do_leftnav();
  if(!userPermission('3')) {
  	alert();
  }
  $id=$_REQUEST['id'];
  $db_conn=db_connect();
  $query="SELECT * FROM planner_projects WHERE id=$id";
  $rs=$db_conn->query($query);
  $project=$rs->fetch_assoc();
  $query="SELECT * FROM planner_tasks WHERE project=$id AND root=1";
  $rs=$db_conn->query($query);
  $task=$rs->fetch_assoc();
	?>
<script type="text/javascript" src="include/js/moveoptions.js"></script>
<table width="100%" class="operate" >
	<tr><td colspan='2'><div align='center'><h2>Planner</h2></div></td></tr>
  <form name='add' method='post' action=''>
	<tr><td colspan='2'><h3>Edit:</h3></td>
  </tr>
  <tr>
  <td width='20%'>Name:</td>
  <td width='80%'><input type='text' name='name' size="40" value="<?php echo $project['name']?>"/>*</td>
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
	$query="SELECT id,name FROM people WHERE state=0 ORDER BY CONVERT(name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$left_content[$match['id']]=$match['name'];
	}
	$query= "select a.id, a.name from people a, planner_project_people b WHERE a.id=b.people_id AND b.project_id={$project['id']} ORDER BY CONVERT(a.name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$right_content[$match['id']]=$match['name'];
	}
	echo move_options_table(array('name'=>'people[]','width'=>120,'size'=>8,'content'=>$left_content),array('name'=>'people2project[]','width'=>120,'size'=>8,'content'=>$right_content));
	?>
</td>
</tr>
<tr>
<td colspan='2'><input type='submit' value='Submit' onmouseover="select_all_options('people2project[]')">&nbsp;&nbsp;<a href='<?php
  echo $_SESSION['url_1'];?>'><img src='./assets/image/general/back.gif' alt='Back' border='0'/></a>
</td>
</tr>
<input type="hidden" name="action" value="edit">
</form>
</table>
<?php
	do_footer();
}

function Detail() {
	if(!userPermission('4')) {
  	  alert();
    }
?>
<script>
function   add_task_form(project) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("planner_tasks.php?type=addtaskform&project="+project,obj   ,"dialogWidth=400px;dialogHeight=400px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   edit_task_form(project,task) {
 	var task
	var   obj   =   new   Object();
	var   a=window.showModalDialog("planner_tasks.php?type=edittaskform&project="+project+"&task="+task,obj   ,"dialogWidth=400px;dialogHeight=400px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   delete_task_form(project,task) {
 	var task
	var   obj   =   new   Object();
	var   a=window.showModalDialog("planner_tasks.php?type=deletetaskform&project="+project+"&task="+task,obj   ,"dialogWidth=400px;dialogHeight=300px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
</script>

<?php
$id=$_REQUEST['id'];
$project=explode(",",$id);
$db_conn=db_connect();

?>
<form target="_self" method="POST" id="ganttchart">
<table>
<tr>
<td><b>Gantt Chart: </b></td>
</tr>

<tr>
<td>
<?php
//select people
$n=0;
foreach ($project as $key=>$value) {
	if ($n>0) {
		$project_string .= "OR ";
	}
	$project_string .= "b.project_id=$value ";
	$n++;
}
$query= "select a.id, a.name from people a, planner_project_people b WHERE a.id=b.people_id AND ($project_string) GROUP BY a.id ORDER BY CONVERT(a.name USING GBK)";
echo "People: ";
$result = $db_conn->query($query);
echo "<select name='people' onchange='submit(this)'>";
echo '<option value="%"';
if($_POST['people'] == '') echo ' selected ';
echo '>- Select all -</option>';
for ($i=0; $i < $result->num_rows; $i++) {
	$option = $result->fetch_assoc();
	echo "<option value='{$option[id]}'";
	if ($option[id] == $_POST['people']) {
		echo ' selected';
	}
	echo  ">{$option[name]}</option>";
}
echo "</select>";
?>
</td>
</tr>

<tr>
<td>
<?php
if (!isset($_REQUEST['people'])) {
	$people = "%";
}
else {
	$people = $_REQUEST['people'];
}
gantt_chart($project,$people);
?>

</td>
</tr>
</table>

<?php
//��Ŀ��ƺ������Ա
foreach ($project as $key=>$value) {
	$query="SELECT * FROM planner_projects WHERE id=$value";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
?>
<table>
<tr>
<td width="10%">Project name:  </td>
<td><?php echo $match['name'] ?></td>
</tr>
<tr>
<td width="10%" valign="top">People:  </td>
<td>
<?php
$query="SELECT b.name FROM planner_project_people a, people b WHERE a.project_id=$value AND a.people_id=b.id ORDER BY CONVERT(b.name USING GBK)";
$rs=$db_conn->query($query);
$n=0;
while ($match=$rs->fetch_assoc()) {
	if ($n>0) {
		echo ", ";
	}
	echo $match['name'];
	$n++;
}
?>
</td>
</tr>
</table>
</form>
<?php
}
}

function gantt_chart($project,$people) {
?>
<style>
table.gatte {
border: 1px solid #006633;
background-color: #cccccc;
}

td.gantt_header_1 {
background-color: #666666;
color: #FFFFFF;
text-align: center;
font-weight: bold;
padding-left:2px;
padding-right:2px;
height: 16px;
}

td.gantt_header_2 {
background-color: #666666;
color: #FFFFFF;
text-align: center;
font-weight: bold;
height: 16px;
}
td.gantt_header_3 {
background-color: #666666;
color: #FFFFFF;
text-align: center;
font-weight: bold;
height: 16px;
width: 20px;
}
td.gantt_header_4 {
background-color: #999999;
color: #FFFFFF;
text-align: center;
font-weight: bold;
height: 16px;
width: 20px;
}
td.gantt_task_name {
background-color: #FFFFFF;
text-align: left;
height: 12px;
padding-left:4px;
padding-right:4px;
}
td.gantt_task_other {
background-color: #FFFFFF;
text-align: center;
height: 12px;
}
td.gantt_task_empty {
background-color: #FFFFFF;
text-align: left;
height: 12px;
}
td.gantt_task_chart {
background-color: #8db6cd;
text-align: left;
height: 12px;
}
</style>
<table cellpadding="0" cellspacing="1" class="gatte" width="100%">
<tr>
<td class="gantt_header_1">Name</td>
<td class="gantt_header_1">Work</td>
<td class="gantt_header_1">Duration</td>
<?php
//ͳ������������������
$db_conn = db_connect();
$n=0;
foreach ($project as $key=>$value) {
	if ($n>0) {
		$project_string .= "OR ";
	}
	$project_string .= "project=$value ";
	$n++;
}
$query="SELECT MIN(`startdate`) AS startdate, MAX(`enddate`) AS enddate FROM planner_tasks WHERE ($project_string) AND ((`right`-`left`)=1)";
$rs=$db_conn->query($query);
$match=$rs->fetch_assoc();
//��startdate��������һ
$startdate=$match['startdate'];
$w=date('w',strtotime($startdate));
if ($w==0) {
	$w=7;
}
$span_s=$w-1;
$startdate = date('Y-m-d',strtotime($startdate."   -$span_s   day"));
//��startdate����������
$enddate=$match['enddate'];
$w=date('w',strtotime($enddate));
if ($w==0) {
	$w=7;
}
$span_e=7-$w;
$enddate = date('Y-m-d',strtotime($enddate."   +$span_e   day"));
//���������?
$datediff = (strtotime($enddate) - strtotime($startdate))/60/60/24;
//����������
$date=$startdate;
for($i=0;$i<=$datediff;$i++) {
	$w=date('w',strtotime($date));
	if ($w==0) {
		$w=7;
	}
	if ($w==1||$i==0) {
		$colspan=8-$w;
		$WEEK = date('W',strtotime($date));
		$YEAR = date('Y',strtotime($date));
		$MONTH = date('M',strtotime($date));
		echo "<td class=\"gantt_header_1\" colspan=$colspan>Week ".$WEEK." ".$MONTH." ".$YEAR."</td>";
	}
	$date = date('Y-m-d',strtotime($date."   +1   day"));
}
?>
</tr>
<tr>
<td class="gantt_header_2"></td>
<td class="gantt_header_2"></td>
<td class="gantt_header_2"></td>
<script type="text/javascript" src="include/phprpc/js/compressed/phprpc_client.js"></script>
<script type="text/javascript" src="include/phprpc/task_popup.js"></script>
<script>
rpc_task_popup.useService('include/phprpc/task_popup.php');
</script>
<?php
//����������
$date=$startdate;
for($i=0;$i<=$datediff;$i++) {
	//ͻ����ĩ
	if (date('w',strtotime($date))==6||date('w',strtotime($date))==0) {
		echo "<td class=\"gantt_header_4\"";
	}
	else {
		echo "<td class=\"gantt_header_3\"";
	}
	//ͻ�����?
	if ($date==date("Y-m-d")) {
		echo " width=20 style='color:red'>".date('d',strtotime($date))."</td>";
	}
	else {
		echo " width=20>".date('d',strtotime($date))."</td>";
	}
	$date = date('Y-m-d',strtotime($date."   +1   day"));
}
?>
</tr>
<?php
//��������
foreach ($project as $key_p=>$value_p) {
	//find out the root of this project
	$query="SELECT * FROM planner_tasks WHERE project=$value_p AND root=1";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	$task=display_tree($match['id'],$value_p);
	$colspan = $datediff+1;
	foreach ($task as $key=>$value) {
		echo "<tr>";
		//�������?
		$query="SELECT root FROM planner_tasks WHERE id=$key";
		$rs=$db_conn->query($query);
		$match=$rs->fetch_assoc();
		if ($match['root']==1) {
			echo "<td class='gantt_task_name'><b>$value</b>&nbsp;<a onclick=\"add_task_form($value_p)\" style=\"cursor:pointer\"/><img src='./assets/image/general/add-s.gif' alt='Add' border='0'/></a></td>";
		}
		else {
			echo "<td class='gantt_task_name'>$value</td>";
		}
		$query= "SELECT * FROM planner_tasks WHERE id=$key";
		$rs=$db_conn->query($query);
		$task=$rs->fetch_assoc();

		//������ӽڵ�?
		$workdays=0;
		if ($task['right']-$task['left']>1) {
			//�����ۼƹ�����
			$query= "SELECT a.* FROM planner_tasks a, planner_task_people b WHERE (a.left BETWEEN {$task['left']} AND {$task['right']}) AND ((a.right-a.left)=1) AND (a.project=$value_p) AND (a.id=b.task_id) AND (b.people_id LIKE '$people') GROUP BY a.id";
			$rs=$db_conn->query($query);
			while ($match=$rs->fetch_assoc()) {
				$workdays += (strtotime($match['enddate']) - strtotime($match['startdate']))/60/60/24+1;
			}
			echo "<td class='gantt_task_other'><b>$workdays d</b></td>";
			//�����������?
			$query= "SELECT DATEDIFF(MAX(a.enddate),MIN(a.startdate)) AS duration FROM planner_tasks a, planner_task_people b WHERE (a.left BETWEEN {$task['left']} AND {$task['right']}) AND ((a.right-a.left)=1) AND (a.project=$value_p) AND (a.id=b.task_id) AND (b.people_id LIKE '$people')";
			$rs=$db_conn->query($query);
			$match=$rs->fetch_assoc();
			if ($workdays==0) {
				$duration=0;
			}
			else {
				$duration=$match['duration']+1;
			}
			echo "<td class='gantt_task_other'><b>$duration d</b></td>";
			//����ʱ����
			echo "<td class='gantt_task_empty' colspan=$colspan></td>";
		}
		//���û���ӽ��
		else {
			$query = "SELECT COUNT(*) AS num_rows FROM planner_task_people WHERE task_id=$key AND people_id LIKE '$people'";
			$rs=$db_conn->query($query);
			$match=$rs->fetch_assoc();
			//if people related to this task
			if ($match['num_rows']>0) {
				//�����ۼƹ�����
				$workdays = (strtotime($task['enddate']) - strtotime($task['startdate']))/60/60/24+1;
				echo "<td class='gantt_task_other'>$workdays d</td>";
				//�����������?
				echo "<td class='gantt_task_other'>$workdays d</td>";
				//����ʱ����
				$gantt_chart = (strtotime($task['enddate']) - strtotime($task['startdate']))/60/60/24+1;
				$gantt_empty_begin = (strtotime($task['startdate']) - strtotime($startdate))/60/60/24;
				$gantt_empty_end = $colspan-$gantt_empty_begin-$gantt_chart;
				if ($gantt_empty_begin>0) {
					echo "<td class='gantt_task_empty' colspan=$gantt_empty_begin>";
				}
				echo "<td  id = 'task_bar_$key' class='gantt_task_chart' colspan=$gantt_chart  onmouseover = 'get_task($key)' onmouseout = 'hide_task($key)'>";
				if ($gantt_empty_end>0) {
					echo "<td class='gantt_task_empty' colspan=$gantt_empty_end>";
				}
				//echo "<div  style='width:$widthtask px; height:5 px;BACKGROUND-COLOR:#8db6cd; BORDER:1px, solid;margin-left:$widthempty px'/></div>";
				echo "</td>";
			}
			else {
				//�����ۼƹ�����
				echo "<td class='gantt_task_other'></td>";
				//�����������?
				echo "<td class='gantt_task_other'></td>";
				//����ʱ����
				echo "<td class='gantt_task_empty' colspan=$colspan></td>";
			}
		}
		echo "</tr>";
	}
}
echo "</table>";
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
function Add() {
	try {
		if (!filled_out(array($_REQUEST['name']))&&count($_REQUEST['people2project'])>0) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$name = $_REQUEST['name'];
		$startdate = $_REQUEST['startdate'];
		$enddate = $_REQUEST['enddate'];
		$db_conn = db_connect();
		//add project
		$query = "insert into planner_projects
      (`name`)
       VALUES
      ('$name')";
		$result = $db_conn->query($query);
		$project_id=$db_conn->insert_id;
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		//add root task
		$date_create=date("Y-m-d H:i:s");
		$db_conn = db_connect();
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$created_by=$match['people_id'];
		$query = "insert into planner_tasks
      (`name`,`left`,`right`,`root`,`project`,`startdate`,`enddate`,`created_by`,`date_create`)
       VALUES
      ('$name',1,2,1,'$project_id','$startdate','$enddate','$created_by','$date_create')";
		$result = $db_conn->query($query);
		$task_id=$db_conn->insert_id;
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		for($i=0;$i<count($_REQUEST['people2project']); $i++) {
			//add project rel
			$query="INSERT INTO planner_project_people
      (`project_id`,`people_id`)
      VALUES
      ('$project_id','".$_REQUEST['people2project'][$i]."')";
			$result = $db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
			//add task related people
			$query="INSERT INTO planner_task_people
      (`task_id`,`people_id`)
      VALUES
      ('$task_id','".$_REQUEST['people2project'][$i]."')";
			$result = $db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		header('Location: planner.php?id='.$id);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function Edit() {
	try {
		if (!filled_out(array($_REQUEST['name']))&&count($_REQUEST['people2project'])>0) {
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
		$startdate = $_REQUEST['startdate'];
		$enddate = $_REQUEST['enddate'];
		$db_conn = db_connect();
		//edit project
		$query = "UPDATE planner_projects
		SET `name` = '$name'
		WHERE id = '$id'";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		//edit root task
		$date_update=date("Y-m-d H:i:s");
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$updated_by=$match['people_id'];
		$query = "UPDATE planner_tasks
		SET `name` = '$name',
		`startdate` = '$startdate',
		`enddate` = '$enddate',
		`updated_by` = '$updated_by',
		`date_update` = '$date_update'
		WHERE `root` = '1' AND `project` = '$id'";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		//edit project related people
		//edit root task related people
		$query = "DELETE FROM planner_project_people WHERE `project_id` = $id";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		$query = "SELECT * FROM planner_tasks WHERE `root` = 1 AND `project` = $id";
		$result = $db_conn->query($query);
		$match = $result->fetch_assoc();
		$task_id = $match['id'];
		$query = "DELETE FROM planner_task_people WHERE `task_id` = $task_id";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		for($i=0;$i<count($_REQUEST['people2project']); $i++) {
			$query="INSERT INTO planner_project_people
      (`project_id`,`people_id`)
      VALUES
      ('$id','".$_REQUEST['people2project'][$i]."')";
			$result = $db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
			$query="INSERT INTO planner_task_people
      (`task_id`,`people_id`)
      VALUES
      ('$task_id','".$_REQUEST['people2project'][$i]."')";
			$result = $db_conn->query($query);
			if (!$result) {
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		header('Location: planner.php?id='.$id);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function processRequest() {
	$type = $_REQUEST['type'];
	switch ($type) {
		case "add":AddForm();break;
		case "detail":Detail();break;
		case "edit":EditForm();break;
		default:break;
	}
	$action = $_POST['action'];
	switch ($action) {
		case "add":Add();break;
		case "edit":Edit();break;
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