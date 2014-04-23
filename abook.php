<?php
include_once('include/includes.php');
?>
<?php
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('abook');?>
<?php
  do_html_header('Address book-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  js_selectall();
?>
<form action="abook.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class='search'>

<?php
	$fields="CONCAT(name,email,tel,fax,mobile,company,address,note)";
?>
		<tr>
      <td align='center' valign='middle'><h2>Address book&nbsp;&nbsp;
<?php
if (userPermission("3")){
	echo "<a href='abook_operate.php?type=add'><img src='./assets/image/general/add.gif' alt='Add new' title='Add new' border='0'/></a>";
	//echo "&nbsp;<a href='abook_operate.php?type=import'><img src='./assets/image/general/import.gif' alt='Import from file' title='Import from file' border='0'/></a></h2>";
}
else {
	echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" title="Add new" border="0"/>';
	//echo '&nbsp;<img src="./assets/image/general/import-grey.gif" alt="Import from file" title="Import from file" border="0"/></h2>';
}
?>
	  	</td>
    </tr>
<?php
search_keywords('abook',$fields);
$sort=array('id'=>'id','name'=>'name');
resultsDisplayControl($sort,10);
?>
  </table>
</form>
<?php

$db_conn = db_connect();

if($_REQUEST['id']==null){$_REQUEST['id']='%';}
if($_REQUEST['fields']==null){$_REQUEST['fields']='name';}
if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

$id=$_REQUEST['id'];
$fields=$_REQUEST['fields'];
$sort=$_REQUEST['sort'];
$order=$_REQUEST['order'];

//seperate the keywords by space ("AND" "LIKE")
$keywords = split(' ', $_REQUEST['keywords']);
$num_keywords = count($keywords);
for ($i=0; $i<$num_keywords; $i++)
{
	if ($i)
	{
		$keywords_string .= "AND $fields LIKE '%".$keywords[$i]."%' ";
	}
	else
	{
		$keywords_string .= "$fields LIKE '%".$keywords[$i]."%' ";
	}
}

$query =  "SELECT * FROM abook WHERE ($keywords_string) AND id LIKE '$id' ORDER BY $sort $order";

$_SESSION['query']=$query;//used for EXCEL export

pagerForm('abook',$query);

if ($results  && $results->num_rows>0)
{
	echo "<form action='' method='get' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'>ID</td><td class='results_header'>";
	echo "Name</td><td class='results_header'>";
	echo "Email</td><td class='results_header'>";
	echo "Telephone</td><td class='results_header'>";
	echo "Operate</td></tr>";

	while ($matches = $results->fetch_array())
	{
		echo "<tr><td class='results'>{$matches['id']}</td><td class='results'>";
		echo "<a href='abook_operate.php?type=detail&id={$matches[id]}'>{$matches['name']}</a></td><td class='results'>";
		echo "{$matches['email']}</td><td class='results'>";
		echo "{$matches['tel']}</td><td class='results'>";
		if (userPermission('3')) {
			echo '<a href="abook_operate.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit" title="Edit" border="0"/></a></td></tr>';
		}
		else{
			echo '<img src="./assets/image/general/edit-s-grey.gif" alt="Edit" title="Edit" border="0"/></td></tr>';
		}
	}
	echo '</table></form>';
}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
