<?php
include('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��?
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
  do_html_header('Reagent categories-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  if(!userPermission(3)){
   echo '<table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
   do_rightbar();
   do_footer();
   do_html_footer();
   exit;
  }
?>
<?php
  js_selectall();
?>
<form action="reagent_cat.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class="search">
    <tr><td align="center">
      <h2>Reagent categories&nbsp;&nbsp;<a href='reagent_cat_operate.php?type=add'><img src='./assets/image/general/add.gif' alt='Add new' border='0'/></a></h2></td>
    </tr>
<?php
$fields="CONCAT(`name`)";
search_keywords('reagent_cat',$fields);
$sort=array('id'=>'id','name'=>'name');
resultsDisplayControl($sort,10);
?>
</table></form>

<?php
  if($_REQUEST['id']==null){$_REQUEST['id']='%';}
 	if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
 	if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
 	if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

 	$id=$_REQUEST['id'];
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
    $db_conn = db_connect();
    $query =  "SELECT *
	           FROM reagent_cat
          	   WHERE ($keywords_string)
          	   AND id LIKE '$id'
	           ORDER BY $sort $order";

  $_SESSION['query']=$query;//used for EXCEL export

  pagerForm('reagent_cat',$query);

 if ($results&&$results->num_rows>0)
 {
    echo "<form action='' method='get' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'>ID</td><td class='results_header'>";
	echo "Name</td><td class='results_header'>";
	echo "Operate</td></tr>";
    while ($matches = $results->fetch_array())
    {
    echo "<tr><td class='results'>".$matches['id']."</td><td class='results'>";
    echo $matches['name']."</td><td class='results'>";
      echo '<a href="reagent_cat_operate.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit"  border="0"/></a></td></tr>';
    }
    echo '</table></form>';
  }
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>