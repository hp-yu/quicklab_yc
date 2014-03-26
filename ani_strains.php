<?php
include_once('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��?
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php //selectedRequest('sellers');?>
<?php
  do_html_header('Animal strains-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  js_selectall();
?>
<form action="ani_strains.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class='search'>
	<tr>
    <td align='center' valign='middle'><h2>Animal strains&nbsp;&nbsp;<?php
      if (userPermission("3")){
      	echo "<a href='ani_strains_operate.php?type=add'><img src='./assets/image/general/add.gif' alt='Add new' border='0'/></a>";
      	//echo "&nbsp;<a href='ani_strains_operate.php?type=import'><img src='./assets/image/general/import.gif' alt='Import from file' border='0'/></a></h2>";
      }
      else {
      	echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" border="0"/>';
      	//echo '&nbsp;<img src="./assets/image/general/import-grey.gif" alt="Import from file" border="0"/></h2>';
 	    }?>
	  </td>
  </tr>
  <?php
  $fields="CONCAT(name,description,genes_alleles)";
	search_keywords('ani_strains',$fields);
	?>
  <tr>
    <td colspan='2' height='21' valign='top'>And species:<?php
      $query= "select * from species ORDER BY name";
      echo query_select_all('species', $query,'id','name',$_REQUEST['species']);?>
    </td>
  </tr>
	<?php
  $sort=array('id'=>'id','name'=>'name');
  resultsDisplayControl($sort,10);
  ?>
  </table>
</form>
<?php

  $db_conn = db_connect();

 	if($_REQUEST['id']==null){$_REQUEST['id']='%';}
 	if($_REQUEST['search_field']==null){$_REQUEST['search_field']='name';}
 	if($_REQUEST['species']==null){$_REQUEST['species']='%';}
 	if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
 	if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
 	if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

 	$id=$_REQUEST['id'];
 	//$search_field='name';
 	$species=$_REQUEST['species'];
	$sort=$_REQUEST['sort'];
	$order=$_REQUEST['order'];

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

    $query =  "SELECT * FROM ani_strains
          	   WHERE ($keywords_string)
          	   AND id LIKE '$id'
          	   AND species LIKE '$species'
	           ORDER BY $sort $order";

  $_SESSION['query']=$query;//used for EXCEL export

  pagerForm('ani_strains',$query);

  if ($results  && $results->num_rows>0)
  {
	echo "<form action='' method='get' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'>ID</td><td class='results_header'>";
	echo "Strain name</td><td class='results_header'>";
	echo "Species</td><td class='results_header'>";
	echo "Operate</td></tr>";

    while ($matches = $results->fetch_array()) {
	  echo "<tr><td class='results'>{$matches['id']}</td><td class='results'>";
	  echo "<a href='ani_strains_operate.php?type=detail&id={$matches['id']}'>{$matches['name']}</a></td><td class='results'>";
	  $query="SELECT * FROM species WHERE id='{$matches['species']}'";
	  $result_species=$db_conn->query($query);
	  $match_species=$result_species->fetch_assoc();
      echo "{$match_species['name']}</td><td class='results'>";
      if (userPermission('3')) {
	    echo '<a href="ani_strains_operate.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit" border="0"/></a></td></tr>';
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
