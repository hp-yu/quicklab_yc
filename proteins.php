<?php
include_once('include/includes.php');
?>
<?php 
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('proteins');?>
<?php
  do_html_header('proteins-Quicklab');
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
	$fields="CONCAT(name,description,sequence_identifier)";
	$sort=array('id'=>'id','name'=>'name');
	standardSearch('Proteins',$fields);
	resultsDisplayControl($sort,10);
	?>
  </table>
</form>
<?php 
  $db_conn = db_connect();
  $query=query_module('proteins');
  pagerForm('proteins',$query);
  searchResultsForm('proteins',$results);
?>
</td>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
