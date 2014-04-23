<?php
include_once('include/includes.php');
?>
<?php
$_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('planner');?>
<?php
do_html_header('Planner-Quicklab');
do_header();
//do_leftnav();
?>
<?php
js_selectall();
?>
<script>
<!--all actionRequest -->
function submitResultsForm() {
	var selectcount = 0;//check at least select one
	var f = document.results;
	var url = "planner_projects.php?type=detail&id=";
	for(i=0;i<f.elements.length;i++){
		if (f.elements[i].checked){
			selectcount = selectcount + 1;
		}
	}
	if(selectcount==0) {
		alert("You have not select any item.")
		f.actionRequest.value = ""
		return
	}

	var confirmVal
	if (f.actionRequest.value == "detail") {
		var n=0;
		for(i=0;i<f.elements.length;i++) {
			if (f.elements[i].name=="selectedItem[]"&&f.elements[i].checked) {
				if (n>0) {
					url += ","+f.elements[i].value;
				}
				else {
					url += f.elements[i].value
				}
				n++;
			}
		}
		f.action=url;
		f.target="_blank";
	}
	document.results.actionRequest.value = ""
	f.submit();
}
</script>
<form action="planner.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class='search'>
	<?php
	$fields="CONCAT(name,address,tel)";
  ?>
	<tr>
      <td align='center' valign='middle'><h2>Planner&nbsp;&nbsp;<?php
      if (userPermission("3")){
      	echo "<a href='planner_projects.php?type=add'><img src='./assets/image/general/add.gif' alt='Add new' title='Back' border='0'/></a>";
      	echo "&nbsp;<a href='planner_projects.php?type=import'><img src='./assets/image/general/import.gif' alt='Import from file' title='Import from file' border='0'/></a></h2>";
      }
      else {
      	echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" title="Add new" border="0"/>';
      	echo '&nbsp;<img src="./assets/image/general/import-grey.gif" alt="Import from file" title="Import from file" border="0"/></h2>';
      }
?>
	  </td>
    </tr>
<?php
$fields="CONCAT(name)";
search_keywords('planner_projects',$fields);
$sort=array('id'=>'id','name'=>'name');
resultsDisplayControl($sort,10);
  ?>
  </table>
</form>
<?php

$db_conn = db_connect();

if($_REQUEST['id']==null){$_REQUEST['id']='%';}
if($_REQUEST['search_field']==null){$_REQUEST['search_field']='name';}
if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

$id=$_REQUEST['id'];
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

$query =  "SELECT *
	           FROM planner_projects
          	   WHERE ($keywords_string)
          	   AND id LIKE '$id'
	           ORDER BY $sort $order";

$_SESSION['query']=$query;//used for EXCEL export

pagerForm('planner_projects',$query);

if ($results  && $results->num_rows>0) {
	echo "<form action='' method='POST' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header' width='20'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td>";
	echo "<td class='results_header' width='20'>ID</td><td class='results_header'>";
	echo "Name</td><td class='results_header'>";
	echo "Operate</td></tr>";

	while ($matches = $results->fetch_array()) {
		echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this)  name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
		echo $matches['id']."</td><td class='results'>";
		echo "<a href='planner_projects.php?type=detail&id={$matches[id]}' target='_blank'>{$matches['name']}</a></td><td class='results'>";
		if (userPermission('3')) {
			echo '<a href="planner_projects.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit" title="Edit" border="0"/></a></td></tr>';
		}
		else{
			echo '<img src="./assets/image/general/edit-s-grey.gif" alt="Edit" title="Edit" border="0"/></td></tr>';
		}
	}
}
?>
</table>
<table>
<tr><td>Selected:
<select name="actionRequest" onchange="javascipt:submitResultsForm()">
<option value="" selected> -Choose- </option>
<option value="detail">View details</option>
</select>
<input type="hidden" name="actionType" value=""></td>
<tr>
</table>
</form>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
