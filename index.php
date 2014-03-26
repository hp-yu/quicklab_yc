<?php
include('include/includes.php');
//$_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
do_html_header('Home-Quicklab');
do_header();
//do_leftnav();
?>
<form action='index.php' method='get' name='search' target='_self'>
<table class='search' width="100%">
<?php
homeSearch();
?>
<tr><td></td></tr>
</table>
</form>
<?php
  $db_conn = db_connect();
  if (!$_REQUEST['project']) {
  	$_REQUEST['project']="%";
  }
  if (!$_REQUEST['created_by']) {
  	$_REQUEST['created_by']="%";
  }
	$project=$_REQUEST['project'];
	$created_by=$_REQUEST['created_by'];

	//seperate the keywords by space ("AND" "LIKE")
	$keywords = split(' ', $_REQUEST['keywords']);
	$num_keywords = count($keywords);
	for ($i=0; $i<$num_keywords; $i++) {
		if ($i) {
			$keywords_string .= "AND name LIKE '%".$keywords[$i]."%' ";
		}
		else {
			$keywords_string .= "name LIKE '%".$keywords[$i]."%' ";
		}
	}
	$query="SELECT * FROM modules WHERE material=1 ORDER BY name";
	$results=$db_conn->query($query);

	echo "<table width='100%' class='results'><tr><td class='results_header'>";
  echo "Module name</td><td class='results_header'>";
	echo "number of items</td></tr>";
	while($module=$results->fetch_array()) {
		$query_module =  "SELECT COUNT(id) AS num_records
      FROM ".$module['table']."
      WHERE ($keywords_string)
      AND project LIKE '$project'
      AND created_by LIKE '$created_by'";
		$results_module=$db_conn->query($query_module);
		$matches_module=$results_module->fetch_array();
		echo "<tr><td class='results'><a href='".$module['name'].".php?fields=name&keywords=".
		$_REQUEST['keywords']."&project=".$_REQUEST['project']."&created_by=".$_REQUEST['created_by']."'>".
		$module['name']."</a></td><td class='results'>";
		echo $matches_module['num_records']."</td></tr>";
		$total_records+=$matches_module['num_records'];
	}
  echo "<tr><td class='results_header'>Total number</td><td class='results_header'>";
	echo $total_records."</td></tr>";
	echo "</table>";
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>