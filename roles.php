<?php
include('include/includes.php');
?>
<?php
$_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
do_html_header('Roles-Quicklab');
do_header();
//do_leftnav();
?>
<?php
if(!userPermission(1)) {
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
<script>
function   edit_role (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("roles_operate.php?action=edit_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok") {
		window.location.href=window.location.href;
	}
}
function   add_role () {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("roles_operate.php?action=add_form",obj,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok") {
		window.location.href=window.location.href;
	}
}
function   edit_permission (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("roles_operate.php?action=edit_permission_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
}
function   delete_role (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("roles_operate.php?action=delete_form&id="+id,obj,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok") {
		window.location.href=window.location.href;
	}
}
function   role_detail (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("roles_operate.php?action=detail&id="+id,obj,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok") {
		window.location.href=window.location.href;
	}
}
</script>
<form action="roles.php" method="get" name="search" target="_self" id="form1">
<table width="100%" class="search">
<tr><td align="center">
<h2>Roles&nbsp;&nbsp;
<a onclick="add_role()" style="cursor:pointer"/>
<img src='./assets/image/general/add.gif' alt='Add new' title='Add new' border='0'/></a></h2></td>
</tr>
<tr>
<td >Search:
<input type="text" name="keywords" size="40" value="<?php echo stripslashes(htmlspecialchars($_REQUEST['keywords']))?>"/>
<input type="submit" name="Submit" value="Go" />
</td>
</tr>
<?php
$sort=array('id'=>'id','name'=>'name');
resultsDisplayControl($sort,10);
?>
</table></form>

<?php
if($_REQUEST['id']==null){$_REQUEST['id']='%';}
if($_REQUEST['search_field']==null){$_REQUEST['search_field']='name';}
if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

$id=$_REQUEST['id'];
$search_field=$_REQUEST['search_field'];
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
$db_conn = db_connect();
$query =  "SELECT *
	           FROM rbac_roles
          	   WHERE ($keywords_string)
          	   AND id LIKE '$id'
	           ORDER BY $sort $order";

$_SESSION['query']=$query;//used for EXCEL export

pagerForm('roles',$query);

if ($results&&$results->num_rows>0)
{
	echo "<form action='' method='get' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'>Name</td><td class='results_header'>";
	echo "Operate</td></tr>";
	while ($matches = $results->fetch_array())
	{
		echo "<tr><td class='results'><a onclick=\"role_detail({$matches['id']})\" style=\"cursor:pointer\"/>{$matches['name']}</a></td><td class='results'>";
		//check sys role
		if ($matches['sys']==0) {
			echo "<a onclick=\"edit_permission({$matches['id']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/key-s.gif\" title=\"Permission\"  border=\"0\"/></a>&nbsp;&nbsp;";
			echo "<a onclick=\"edit_role({$matches['id']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/edit-s.gif\" title=\"Edit\"  border=\"0\"/></a>&nbsp;&nbsp;";
			echo "<a onclick=\"delete_role({$matches['id']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/del-s.gif\" title=\"Delete\"  border=\"0\"/></a>";
		}  else {
			echo "<img src=\"./assets/image/general/key-s-grey.gif\" title=\"Permission\"  border=\"0\"/>&nbsp;&nbsp;";
			echo "<img src=\"./assets/image/general/edit-s-grey.gif\" title=\"Edit\"  border=\"0\"/>&nbsp;&nbsp;";
			echo '<img src="./assets/image/general/del-s-grey.gif" title="Delete"  border="0"/>';
		}
		echo "</td></tr>";
	}
	echo '</table></form>';
}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>