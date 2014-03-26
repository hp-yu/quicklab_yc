<?php
include_once('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��?
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('sellers');?>
<?php
  do_html_header('Sellers-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  js_selectall();
?>
<form action="sellers.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class='search'>
	<?php
	$fields="CONCAT(name,address,tel)";
	search_header('Sellers');
	search_keywords('sellers',$fields);
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
 	$search_field='name';
	$sort=$_REQUEST['sort'];
	$order=$_REQUEST['order'];

	//seperate the keywords by space ("AND" "LIKE")
    $keywords = split(' ', $_REQUEST['keywords']);
    $num_keywords = count($keywords);
    for ($i=0; $i<$num_keywords; $i++)
    {
      if ($i)
      {
        $keywords_string .= "AND $search_field LIKE '%".$keywords[$i]."%' ";
      }
      else
      {
        $keywords_string .= "$search_field LIKE '%".$keywords[$i]."%' ";
      }
    }

    $query =  "SELECT *
	           FROM sellers
          	   WHERE ($keywords_string)
          	   AND id LIKE '$id'
	           ORDER BY $sort $order";

  $_SESSION['query']=$query;//used for EXCEL export

  pagerForm('sellers',$query);

  if ($results  && $results->num_rows>0)
  {
	echo "<form action='' method='get' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'>ID</td><td class='results_header'>";
	echo "Name</td><td class='results_header'>";
	echo "Telephone</td><td class='results_header'>";
	echo "Web site</td><td class='results_header'>";
	echo "Operate</td></tr>";

    while ($matches = $results->fetch_array())
    {
	  echo "<tr><td class='results'>{$matches['id']}</td><td class='results'>";
	  echo "<a href='sellers_operate.php?type=detail&id={$matches[id]}'>{$matches['name']}</a></td><td class='results'>";
      echo "{$matches['tel']}</td><td class='results'>";
      echo "<a href='{$matches['url']}' target='_blank'>{$matches['url']}</a></td><td class='results'>";
      if (userPermission('3')) {
	    echo '<a href="sellers_operate.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit" border="0"/></a></td></tr>';
      }
	  else{
	  	echo '<img src="./assets/image/general/edit-s-grey.gif" alt="Edit" border="0"/></td></tr>';
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
