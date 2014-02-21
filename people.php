<?php
include('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��?
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
  do_html_header('People-Quicklab');
  do_header();
  do_leftnav();
?>
<?php
  js_selectall();
?>
<script>
function submitResultsForm() {
	var selectcount = 0;//check at least select one
	for(i=0;i<document.results.elements.length;i++){
		if (document.results.elements[i].checked){
			selectcount = selectcount + 1;
		}
	}
	if(selectcount==0)
	{
		alert("You have not select any item.")
		document.results.actionRequest.value = ""
		return
	}
	var confirmVal
	if (document.results.actionRequest.value == "leaveLab") {
		confirmVal = confirm("Are you sure the selected people have left.")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		}
		else{
			document.results.actionType.value = "leaveLab"
		}
	}
	if (document.results.actionRequest.value == "inLab") {
		confirmVal = confirm("Are you sure the selected people have left.")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		}
		else{
			document.results.actionType.value = "inLab"
		}
	}
	document.results.submit()
}
</script>
<form action="people.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class='search'>
    <tr>
      <td align="center" valign="middle">
        <h2>People&nbsp;&nbsp;
		<?php
	        if (userPermission("3")) {
	          echo '<a href="people_operate.php?type=add"><img src="./assets/image/general/add.gif" alt="Add new" border="0"/></a></h2>';
 	        }
 	        else {
 	          echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" border="0"/></h2>';
 	        }
			?>
      </td>
    </tr>
<?php
$fields="CONCAT(name)";
search_keywords('people',$fields,"",1);
?>
        <TR>
        <td>
        AND state:<?php
		$state=array(array("0","in lab"),array("1","leave lab"));
		echo option_select_all('state',$state,2,$_REQUEST['state']);?>
        </td>
        </TR>
		<?php
        $sort=array('id'=>'id','name'=>'CONVERT(name using GBK)','enter date'=>'date_enter');
        resultsDisplayControl($sort,10);?>
  </table>
</form>

<?php

$db_conn = db_connect();

if($_REQUEST['id']==null){$_REQUEST['id']='%';}
if($_REQUEST['search_field']==null){$_REQUEST['search_field']='name';}
if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
if($_REQUEST['state']==null){$_REQUEST['state']='%';}
if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

$id=$_REQUEST['id'];
$state=$_REQUEST['state'];
$sort=$_REQUEST['sort'];
$order=$_REQUEST['order'];

//seperate the keywords by space ("AND" "LIKE")
$keywords = split(' ', $_REQUEST['keywords']);
$num_keywords = count($keywords);
for ($i=0; $i<$num_keywords; $i++) {
	if ($i) {
		$keywords_string .= "AND $fields LIKE '%".$keywords[$i]."%' ";
	}
	else {
		$keywords_string .= "$fields LIKE '%".$keywords[$i]."%' ";
	}
}

$query =  "SELECT * FROM people WHERE
($keywords_string) AND id LIKE '$id'
AND state LIKE '$state' ORDER BY $sort $order";

$_SESSION['query']=$query;//used for EXCEL export

pagerForm('people',$query);

if ($results  && $results->num_rows>0) {
	echo "<form action='' method='post' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header'>";
	echo "Name</td><td class='results_header'>";
	echo "Email</td><td class='results_header'>";
	echo "Enter date</td><td class='results_header'>";
	echo "Status</td><td class='results_header'>";
	echo "Operate</td></tr>";

	while ($matches = $results->fetch_array()) {
		echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this)  name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
		echo "<a href='people_operate.php?type=detail&id={$matches[id]}'>
             {$matches[name]}</td><td class='results'>";
		echo "{$matches['email']}</td><td class='results'>";
		echo "{$matches['date_enter']}</td><td class='results'>";
		echo "{$matches['status']}</td><td class='results'>";
		if (userPermission(2,$matches['id'])) {
			echo '<a href="people_operate.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit" border="0"/></a></td></tr>';
		}
		else {
			echo '<img src="./assets/image/general/edit-s-grey.gif" alt="Edit" border="0"/></td></tr>';
		}
	}
	echo '</table>';
	?>
  <table>
  	<tr><td>Selected:
    	<select name="actionRequest" onchange="javascipt:submitResultsForm()">
      	<option value="" selected> -Choose- </option>
      	<option value="leaveLab">Leave lab</option>
      	<option value="inLab">In lab</option>
    	</select>
      <input type="hidden" name="actionType" value=""></td>
    <tr>
  </table>
	<?php
	echo '</form>';
}
?>
<?php
function leaveLab () {
	$db_conn=db_connect();
	$selecteditem=$_REQUEST['selectedItem'];
	$num_selecteditem=count($selecteditem);
	for($i=0;$i<$num_selecteditem;$i++) {
		$query = "UPDATE `people`
		SET `state`=1
		WHERE `id`={$selecteditem[$i]}";
		$result = $db_conn->query($query);
		if (!$result) {
			echo $query;
		}
	}
	header('Location: '.$_SESSION['url_1']);
}

function inLab () {
	$db_conn=db_connect();
	$selecteditem=$_REQUEST['selectedItem'];
	$num_selecteditem=count($selecteditem);
	for($i=0;$i<$num_selecteditem;$i++) {
		$query = "UPDATE `people`
		SET `state`=0
		WHERE `id`={$selecteditem[$i]}";
		$result = $db_conn->query($query);
		if (!$result) {
			echo $query;
		}
	}
	header('Location: '.$_SESSION['url_1']);
}

if (isset($_REQUEST['actionType'])&&$_REQUEST['actionType']!='') {
	if($_REQUEST['actionType']=='leaveLab') {
		leaveLab();
	}
	if($_REQUEST['actionType']=='inLab') {
		inLab();
	}
}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>

