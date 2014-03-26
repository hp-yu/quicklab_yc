<?php
include_once('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��??
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('reagents');?>
<?php
  do_html_header('Reagents-Quicklab');
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
	search_header('Reagents');
	search_keywords('reagents',$fields);
	?>
  <tr>
    <td colspan='2' height='21' valign='top'>And reagent category:<?php
      $query= "select * from reagent_cat ORDER BY name";
      echo query_select_all('reagent_cat_id', $query,'id','name',$_REQUEST['reagent_cat_id']);?>
    </td>
  </tr>
	<?php
	search_project();
	search_create_mask();
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
  if($_REQUEST['project']==null){$_REQUEST['project']='%';}
  if($_REQUEST['created_by']==null){$_REQUEST['created_by']='%';}
  if($_REQUEST['mask']==null){$_REQUEST['mask']='%';}
  if($_REQUEST['reagent_cat_id']==null){$_REQUEST['reagent_cat_id']='%';}//special

  $id=$_REQUEST['id'];
  $fields=$_REQUEST['fields'];
  $sort=$_REQUEST['sort'];
  $order=$_REQUEST['order'];
  $project=$_REQUEST['project'];
  $created_by=$_REQUEST['created_by'];
  $mask=$_REQUEST['mask'];
  $reagent_cat_id=$_REQUEST['reagent_cat_id'];

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

  $userauth=check_user_authority($_COOKIE['wy_user']);
  $userpid=get_pid_from_username($_COOKIE['wy_user']);

  if($userauth>2) {
  	$mask_str=" AND (mask=0 OR created_by='{$userpid}')";
  }
  else {
  	$mask_str="";
  }

  $query =  "SELECT *,
		DATE_FORMAT(date_create,'%m/%d/%y')AS date_create
  	FROM reagents
  	WHERE ($keywords_string)
  	AND id LIKE '$id'
 	 	AND project LIKE '$project'
  	AND created_by LIKE '$created_by'
  	AND mask LIKE '$mask'
  	AND reagent_cat_id LIKE '$reagent_cat_id'
  	$mask_str ORDER BY $sort $order";
  $_SESSION['query']=$query;//used for EXCEL export

  pagerForm('reagents',$query);
  searchResultsForm('reagents',$results);
?>
</td>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
