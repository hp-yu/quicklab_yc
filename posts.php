<?php
include_once('include/includes.php');
?>
<?php
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
  do_html_header('Posts-Quicklab');
  do_header();
  //do_leftnav();
?>

<?php
  js_selectall();
?>
<div id="contents">
<form action="" method='get' name='search' target='_self'>
<table width='100%' class='search'  style="margin-top:3pt">
	<?php
	$fields="CONCAT(name,content)";
	$sort=array('id'=>'id','name'=>'name');
  ?>
<tr><td align='center' valign='middle'><h2>Posts&nbsp;&nbsp;
	<?php
	if (userPermission("3")){
		echo "<a href='posts_operate.php?type=add_form'><img src='./assets/image/general/add.gif' alt='Add new' border='0'/></a>";
	} else {
		echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" border="0"/>';
	}
	?>
<h2>
</td></tr>
<tr><td colspan='2' height='21' valign='top'>Serach:
<input type="hidden" name="fields" value="<?php echo $fields ?>">
<input type='text' name='keywords' size="40" value="<?php echo stripslashes(htmlspecialchars($_REQUEST['keywords']))?>"/>
<input type='Submit' name='Submit' value='Go' />
<input type="reset" value="Clean"/>
</td></tr>
<tr><td>And created by:
	<?php
	$query= "select * from people ORDER BY name";
	echo query_select_all('created_by', $query,'id','name',$_REQUEST['created_by']);
  ?>
</td></tr>
  <?php
  resultsDisplayControl($sort,10);
	?>
</table>
</form>
<?php
if (isset($_REQUEST['actionType'])&&$_REQUEST['actionType']!='') {
	if($_REQUEST['actionType']=='delete') {
		delete();
	}
}
?>
<script>
function submitResultsForm() {
	var selectcount = 0;//check at least select one
	for(i=0;i<document.results.elements.length;i++) {
		if (document.results.elements[i].checked){
			selectcount = selectcount + 1;
		}
	}
	if(selectcount==0) {
		alert("You have not select any item.")
		document.results.actionRequest.value = ""
		return
	}
	var confirmVal
	if (document.results.actionRequest.value == "delete") {
		<?php
		if (!userPermission('2')) {
		?>
			alert("You do not have the authority to do this.")
			document.results.actionRequest.value = ""
		  return
		<?php
		}
		?>
		confirmVal = confirm("Are you sure to delete the selected post(s)?")
		if (!confirmVal) {
			document.results.actionRequest.value = ""
			return
		}
		else {
			document.results.actionType.value = "delete"
		}
	}

	document.results.submit()
}
function submitResultsForm2(v,r) {
	if (r=='delete') {
	  var confirmVal
	  confirmVal = confirm("Are you sure to delete this post?")
	  if (!confirmVal) {
		  document.results.actionRequest.value = ""
		  return
	  }
	  else {
	  	var f = document.results;
	  	for (i=0;i<f.elements.length;i++) {
	  		f.elements[i].checked = false;
	    	if (f.elements[i].name=="selectedItem[]"&&f.elements[i].value==v) {
		    	f.elements[i].checked = true;
	    	}
	  	}
			document.results.actionType.value = "delete"
	  }
	}
	document.results.submit()
}
</script>
<?php
  $db_conn = db_connect();

 	if($_REQUEST['id']==null){$_REQUEST['id']='%';}
	if($_REQUEST['fields']==null){$_REQUEST['fields']='name';}
	if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
	if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
	if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}
	if($_REQUEST['created_by']==null){$_REQUEST['created_by']='%';}

	$id=$_REQUEST['id'];
	$fields=$_REQUEST['fields'];
	$sort=$_REQUEST['sort'];
	$order=$_REQUEST['order'];
	$created_by=$_REQUEST['created_by'];

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

	$query =  "SELECT * FROM posts
  WHERE ($keywords_string)
  AND id LIKE '$id'
  AND created_by LIKE '$created_by'
  ORDER BY $sort $order";

  pagerForm('posts',$query);

	if ($results  && $results->num_rows) {
		echo "<form action='' method='post' name='results' target='_self' >";
		echo "<table width='100%' class='results'>";
		echo "<tr><td class='results_header' width='5%'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header' width='50%'>";
		echo "Name</td><td class='results_header' width='25%'>";
		echo "Create</td><td class='results_header' width='10%'>";
		echo "Views/Comments</td><td class='results_header' width='10%'>";
		echo "Operate</td></tr>";
		while ($matches = $results->fetch_array()) {
			echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this)  name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
			echo "<a href='posts_operate.php?type=detail&id={$matches[id]}'>".wordwrap($matches[name],190,"<br>")."</a></td><td class='results'>";
			$people=get_record_from_id('people',$matches['created_by']);
			echo $people['name']."&nbsp;&nbsp;".$matches['date_create']."</td><td class='results'>";
			$query="SELECT COUNT(*) AS comments_count FROM post_comments WHERE post_id='{$matches['id']}'";
			$rs=$db_conn->query($query);
			$match_comments=$rs->fetch_array();
			$comments_count=$match_comments['comments_count'];
			echo $matches['count']."/".$comments_count."</td><td class='results'>";
			if (userPermission(0,$matches['created_by'])) {
				echo "<a href='posts_operate.php?type=edit_form&id=".$matches['id']."'><img src='./assets/image/general/edit-s.gif' alt='Edit' border='0'/></a>&nbsp;&nbsp;";
			} else {
				echo '<img src="./assets/image/general/edit-s-grey.gif" alt="Edit" border="0"/>&nbsp;&nbsp;';
			}
			if (userPermission(2)) {
				?>
				<a href='#' onclick='javascipt:submitResultsForm2(<?php echo $matches[id]
?>,"delete")'><img src='./assets/image/general/del-s.gif' alt='Delete'  border='0'/></a></td></tr>
				<?php
			} else {
				echo '<img src="./assets/image/general/del-s-grey.gif" alt="Delete"  border="0"/></td></tr>';
			}
		}
		echo '</table>';
		?>
		<table>
      <tr><td>Selected:
      <select name="actionRequest" onchange="javascipt:submitResultsForm()">
      <option value="" selected> -Choose- </option>
	  	<option value="delete">Delete</option>
    	</select>
      <input type="hidden" name="actionType" value=""></td>
      <tr>
    </table>
		<?php
		echo '</form>';
	}

function delete() {
	$db_conn=db_connect();
	$selectedItem=$_REQUEST['selectedItem'];
	$num_selectedItem=count($selectedItem);
	$module = get_id_from_name('modules','primers');
	for($i=0;$i<$num_selectedItem;$i++) {
    $query = "delete from posts where id = '{$selectedItem[$i]}'";
    $result = $db_conn->query($query);
    $query = "delete from post_comments where post_id = '{$selectedItem[$i]}'";
    $result = $db_conn->query($query);
	}
  //header('Location: '.$_SESSION['url_1']);
}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
