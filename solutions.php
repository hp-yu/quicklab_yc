<?php
include_once('include/includes.php');
?>
<?php
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('solutions');?>
<?php
  do_html_header('Solution center-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  js_selectall();
?>
<form action="solutions.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class='search'>
	<?php
	$fields="CONCAT(name,synonym,description)";
  ?>
	<tr>
    <td align='center' valign='middle'><h2>Solution center&nbsp;&nbsp;
    <?php
      if (userPermission("3")){
      	echo "<a href='solutions_operate.php?type=add'><img src='./assets/image/general/add.gif' alt='Add new' border='0'/></a></h2>";
      	//echo "&nbsp;<a href='solutions_operate.php?type=import'><img src='./assets/image/general/import.gif' alt='Import from file' border='0'/></a></h2>";
      }
      else {
      	echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" border="0"/></h2>';
      	//echo '&nbsp;<img src="./assets/image/general/import-grey.gif" alt="Import from file" border="0"/></h2>';
 	    }?>
	  </td>
  </tr>
  <?php search_keywords("solutions",$fields);?>
	<tr><td>
	And created by:
	<?php
    $query= "select * from people ORDER BY name";
    echo query_select_all('created_by', $query,'id','name',$_REQUEST['created_by']);
  ?>
	</td></tr>
	<?php
  $sort=array('id'=>'id','name'=>'name');
  resultsDisplayControl($sort,10);
  ?>
  </table>
</form>
<?php
  $db_conn = db_connect();
  if($_REQUEST['id']==null){$_REQUEST['id']='%';}
  if($_REQUEST['fields']==null){$_REQUEST['fields']='name';}
  if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
  if($_REQUEST['created_by']==null){$_REQUEST['created_by']='%';}
  if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
  if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

  $id=$_REQUEST['id'];
  $fields=$_REQUEST['fields'];
  $created_by=$_REQUEST['created_by'];
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

  $query =  "SELECT * ,
  DATE_FORMAT(date_create,'%m/%d/%y')AS date_create,
  DATE_FORMAT(date_update,'%m/%d/%y')AS date_update
  FROM solutions
  WHERE ($keywords_string)
  AND id LIKE '$id'
  AND created_by LIKE '$created_by'
	ORDER BY $sort $order";

  $_SESSION['query']=$query;//used for EXCEL export

  pagerForm('sellers',$query);

  if ($results  && $results->num_rows>0) {
  	echo "<form action='' method='get' name='results' target='_self' >";
  	echo "<table width='100%' class='results'>";
  	echo "<tr><td class='results_header'>ID</td><td class='results_header'>";
  	echo "Name</td><td class='results_header'>";
  	echo "Create</td><td class='results_header'>";
  	echo "Update</td><td class='results_header'>";
  	echo "Operate</td></tr>";

  	while ($matches = $results->fetch_array()) {
  		echo "<tr><td class='results'>{$matches['id']}</td><td class='results'>";
  		echo $matches['name'];
  		if ($matches['synonym']!='') {
  			echo ", ".$matches['synonym'];
  		}
  		echo "</td><td class='results'>";
  		$created_by=get_record_from_id('people',$matches['created_by']);
			echo $created_by['name']." ".$matches['date_create']."</td><td class='results'>";
			$updated_by=get_record_from_id('people',$matches['updated_by']);
			echo $updated_by['name']." ".$matches['date_update']."</td><td class='results'>";
  		echo '<a href="solutions_operate.php?type=calc&id='.$matches['id'].'"><img src="./assets/image/general/calc-s.gif" alt="Calculate" border="0"/></a>&nbsp;&nbsp;';
  		if (userPermission('3')) {
  			echo '<a href="solutions_operate.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit" border="0"/></a></td></tr>';
  		}
  		else {
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
