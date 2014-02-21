<?php
include_once('include/includes.php');
do_html_header('Restriction enzyme databse-Quicklab');
do_header();
do_leftnav();
?>
<?php
js_selectall();
?>
<form method="get" name="search" target="_self" id="form1">
<table width="100%" class='search'>
<tr>
<td align='center' valign='middle'><h2>Restriction enzyme databse</h2>
</td></tr>
<?php
$fields="CONCAT(name,prototype)";
search_keywords('rebase',$fields);
$sort=array('name'=>'name');
$order=array(ASC=>ASC,DESC=>DESC);
resultsDisplayControl($sort,20,$order);
?>
</table>
</form>
<?php
$db_conn = db_connect();
if($_REQUEST['id']==null){$_REQUEST['id']='%';}
if($_REQUEST['keywords']==null){$_REQUEST['keywords']='%';}
if($_REQUEST['sort']==null){$_REQUEST['sort']='name';}
if($_REQUEST['order']==null){$_REQUEST['order']='ASC';}

$id=$_REQUEST['id'];
$search_field='CONCAT(name,prototype)';
$keywords = $_REQUEST['keywords'];
$sort=$_REQUEST['sort'];
$order=$_REQUEST['order'];

$query =  "SELECT * FROM `rebase` WHERE ($search_field LIKE '$keywords') AND `id` LIKE '$id' ORDER BY $sort $order";

$_SESSION['query']=$query;//used for EXCEL export

pagerForm('rebase',$query,20,0);

if ($results  && $results->num_rows>0) {
	echo "<form action='' method='get' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'>ID</td><td class='results_header'>";
	echo "Name</td><td class='results_header'>";
	echo "Prototype</td><td class='results_header'>";
	echo "Recognition sequence</td></tr>";

	while ($matches = $results->fetch_array()) {
		echo "<tr><td class='results'>{$matches['id']}</td><td class='results'>";
		echo "<a href='http://rebase.neb.com/rebase/enz/{$matches['name']}.html' target='_blank' title='REBASE details'>{$matches['name']}</a></td><td class='results'>";
		echo "{$matches['prototype']}</td><td class='results'>";
		echo "{$matches['rec_seq']}</td></tr>";
	}
	echo '</table></form>';
}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
