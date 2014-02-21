<?php
include_once('include/includes.php');
?>
<?php 
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('samples');?>
<?php
  do_html_header('Samples-Quicklab');
  do_header();
  do_leftnav();
?>
<?php 
  js_selectall();
  js_selectedRequest_module();
?>
<form action="" method='get' name='search' target='_self'>
  <table width='100%' class='search'>
	<?php
	$db_conn = db_connect();
	$fields="CONCAT(name,description";
	$query="SELECT * FROM custom_fields WHERE module_name='samples' AND search=1 ORDER BY id";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$fields.=",".$match['field_id'];
	}
	$fields.=")";
	$sort=array('id'=>'id','name'=>'name');
	standardSearch('Samples',$fields);
	resultsDisplayControl($sort,10);
	?>
  </table>
</form>
<?php 
  $query=query_module('samples');
  pagerForm('samples',$query);
  searchResultsForm('samples',$results);
?>
</td>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
