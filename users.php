<?php
include('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��??
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
  do_html_header('Users-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  if(!userPermission(2)) {
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
<form action="users.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class="search">
    <tr><td align="center">
      <h2>Users&nbsp;&nbsp;<a href='users_operate.php?type=add'><img src='./assets/image/general/add.gif' alt='Add new' title="Add new" border='0'/></a></h2></td>
    </tr>
    <tr>
      <td >Search username for:
        <input type="text" name="keywords" size="40" value="<?php echo stripslashes(htmlspecialchars($_REQUEST['keywords']))?>"/>
        <input type="submit" name="Submit" value="Go" />
      </td></tr>
      <tr>
        <td>And valid: <?php
      	$valid=array(array('1','yes'),
      				array('0','no'));
      	echo option_select_all('valid',$valid,2,$_REQUEST['valid'])?>
      And people: <?php
		$query= "select id,name from people order by convert(name using gbk)";
		echo query_select_all('people_id', $query,'id','name',$_REQUEST['people_id']);
	  ?>
	  And authority: <?php
		$authority= array( array( '2', 'Administrator'),
					  array( '3', 'Staff'),
		              array( '4', 'User'));
		echo option_select_all('authority', $authority,3,$_REQUEST['authority']);
	  ?></td></tr><?php
        $sort=array('id'=>'id','username'=>'username');
        resultsDisplayControl($sort,10);?>
</table></form>

<?php
  if($_REQUEST['id']==null){$_REQUEST['id']='%';}
 	if($_REQUEST['search_field']==null){$_REQUEST['search_field']='username';}
 	if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
 	if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
 	if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}
 	if($_REQUEST['people_id']==null){$_REQUEST['people_id']='%';}
 	if($_REQUEST['authority']==null){$_REQUEST['authority']='%';}
 	if($_REQUEST['valid']==null){$_REQUEST['valid']='%';}

 	$id=$_REQUEST['id'];
    $search_field=$_REQUEST['search_field'];
	$sort=$_REQUEST['sort'];
	$order=$_REQUEST['order'];
	$people_id=$_REQUEST['people_id'];
	$authority=$_REQUEST['authority'];
	$valid=$_REQUEST['valid'];

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
    $db_conn = db_connect();
    $query =  "SELECT *
	           FROM users
          	   WHERE ($keywords_string)
          	   AND id LIKE '$id'
          	   AND people_id LIKE '$people_id'
          	   AND authority LIKE '$authority'
          	   AND valid LIKE '$valid'
          	   AND authority >1
	           ORDER BY $sort $order";

  $_SESSION['query']=$query;//used for EXCEL export

  pagerForm('users',$query);

 if ($results&&$results->num_rows>0)
 {
    echo "<form action='' method='get' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'>Username</td><td class='results_header'>";
	echo "Authority</td><td class='results_header'>";
	echo "People</td><td class='results_header'>";
	echo "Operate</td></tr>";
    while ($matches = $results->fetch_array())
    {
      echo "<tr><td class='results'>{$matches['username']}</td><td class='results'>";
      $authority= array( array( '1', 'Super administrator'),
      				  array( '2', 'Administrator'),
					  array( '3', 'Staff'),
		              array( '4', 'User'));
	  for ($i=0; $i < 4; $i++)
      {
        if ($authority[$i][0] == $matches['authority'])
       	{
          echo $authority[$i][1];
        }
      }
      echo "</td><td class='results'>";
	  $people=get_name_from_id('people',$matches['people_id']);
	  echo "{$people['name']}</td><td class='results'>";
      echo '<a href="users_operate.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit" title="Edit" border="0"/></a>&nbsp;&nbsp;';
      echo '<a href="users_operate.php?type=delete&id='.$matches['id'].'"><img src="./assets/image/general/del-s.gif" alt="Delete" title="Delete" border="0"/></a></td></tr>';
    }
    echo '</table></form>';
  }
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>