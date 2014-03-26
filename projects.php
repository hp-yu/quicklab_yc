<?php
include_once('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��?
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('projects');?>
<?php
  do_html_header('Projects-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  if(!userPermission(2)){
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
<form action="projects.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class='search'>
    <tr>
      <td align="center" valign="middle">
        <h2>Projects&nbsp;&nbsp;
		<?php
	        if (userPermission("2"))
	        {
	          echo '<a href="projects_operate.php?type=add"><img src="./assets/image/general/add.gif" alt="Add new" border="0"/></a></h2>';
 	        }
 	        else
 	        {
 	          echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" border="0"/></h2>';
 	        }
			?>
      </td>
    </tr>
    <tr>
      <td >Search name for:
        <input type="text" name="keywords" size="40" value="<?php echo stripslashes(htmlspecialchars($_REQUEST['keywords']))?>"/>
        <input type="submit" name="Submit" value="Go" />
        <input type="button" onclick="resetform(document.search,10)" value="Clear"/></td></tr>
    <tr>
      <td>And state:<?php
		$state=array(array("1","on"),
					array("0","off"));
		echo option_select_all('state',$state,2,$_REQUEST['state']);?>

      </td>
    </tr>
		<?php
        $sort=array('id'=>'id','name'=>'name');
        resultsDisplayControl($sort,10);?>
  </table>
</form>
<?php

  $db_conn = db_connect();

 	if($_REQUEST['id']==null){$_REQUEST['id']='%';}
 	if($_REQUEST['search_field']==null){$_REQUEST['search_field']='name';}
 	if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
 	if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
 	if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}
 	if($_REQUEST['state']==null){$_REQUEST['state']='%';}

 	$id=$_REQUEST['id'];
 	$search_field='name';
	$sort=$_REQUEST['sort'];
	$order=$_REQUEST['order'];
	$state=$_REQUEST['state'];

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
	           FROM projects
          	   WHERE ($keywords_string)
          	   AND id LIKE '$id'
          	   AND state LIKE '$state'
	           ORDER BY $sort $order";

  $_SESSION['query']=$query;//used for EXCEL export

  pagerForm('projects',$query);

  if ($results  && $results->num_rows>0)
  {
	echo "<form action='' method='get' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'>ID</td><td class='results_header'>";
	echo "Name</td><td class='results_header'>";
	echo "Start date</td><td class='results_header'>";
	echo "Operate</td></tr>";

    while ($matches = $results->fetch_array())
    {
	  echo "<tr><td class='results'>{$matches['id']}</td><td class='results'>";
	  echo "<a href='projects_operate.php?type=detail&id={$matches[id]}'>{$matches['name']}</a></td><td class='results'>";
      echo "{$matches['date_start']}</td><td class='results'>";
	  echo '<a href="projects_operate.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit" border="0"/></a></td></tr>';
    }
    echo '</table></form>';
  }

?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
