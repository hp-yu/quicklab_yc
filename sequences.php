<?php
include_once('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��??
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('sequences');?>
<?php
  do_html_header('Sequences-Quicklab');
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
	$fields="CONCAT(name,description,sequence_identifier,sequence)";
	$sort=array('id'=>'id','name'=>'name');
	search_header('Sequences');
	search_keywords('sequences',$fields);
	?>
  <tr>
    <td colspan='2' height='21' valign='top'>And sequence type:<?php
      $seq_type=array('DNA'=>'1','RNA'=>'2','Amino acid'=>'3');
      echo array_select_all('seq_type',$seq_type,$_REQUEST['seq_type']);?>
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
  if($_REQUEST['seq_type']==null){$_REQUEST['seq_type']='%';}//special

  $id=$_REQUEST['id'];
  $fields=$_REQUEST['fields'];
  $sort=$_REQUEST['sort'];
  $order=$_REQUEST['order'];
  $project=$_REQUEST['project'];
  $created_by=$_REQUEST['created_by'];
  $mask=$_REQUEST['mask'];
  $seq_type=$_REQUEST['seq_type'];

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
  	FROM sequences
  	WHERE ($keywords_string)
  	AND id LIKE '$id'
 	 	AND project LIKE '$project'
  	AND created_by LIKE '$created_by'
  	AND mask LIKE '$mask'
  	AND seq_type LIKE '$seq_type'
  	$mask_str ORDER BY $sort $order";
  $_SESSION['query']=$query;//used for EXCEL export

  pagerForm('sequences',$query);
  searchResultsForm('sequences',$results);
?>
</td>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
