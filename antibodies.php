<?php
include_once('include/includes.php');
?>
<?php 
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��?
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('antibodies');?>
<?php
  do_html_header('Antibodies-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php 
  js_selectall();
  js_selectedRequest_module();
?>
<form action="" method='get' name='search' target='_self'>
  <table width='100%' class='search'>
	<?php
	$fields="CONCAT(name,description)";
	$sort=array('id'=>'id','name'=>'name');
	standardSearch('Antibodies',$fields);
	resultsDisplayControl($sort,10);
	?>
  </table>
</form>
<?php 
  $db_conn = db_connect();
  $query=query_module('antibodies');
  pagerForm('antibodies',$query);
  searchResultsForm('antibodies',$results);
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
