<?php
include('include/includes.php');
?>
<?php
do_html_header('Plasmid bank-Quicklab');
do_header();
//do_leftnav();
?>
<?php
if(!userPermission(2)) {
	echo '<table class="alert"><tr><td><h3>You do not have the authority to do this!</h3></td></tr></table>';
	do_footer();
	do_html_footer();
	exit;
}
?>
<?php
js_selectall();
js_selectedRequest_module();
?>
<script>
function submitResultsForm(module_id) {
	var selectcount = 0;//check at least select one
	for(i=0;i<document.results.elements.length;i++){
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
	if (document.results.actionRequest.value == "store") {
		confirmVal = confirm("Are you sure to store the selected item(s) at one location?")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		} else {
			document.results.actionRequest.value = ""
			n=0
			var selected_items = "";
			for(i=0;i<document.results.elements.length;i++){
				if (document.results.elements[i].checked&&document.results.elements[i].name == "selectedItem[]") {
					if (n!=0) selected_items += ",";
					selected_items += document.results.elements[i].value;
					n++;
				}
			}
			var   obj   =   new   Object();
			var   a=window.showModalDialog("storages_operate.php?action=add_form&module_id="+module_id+"&selected_items="+selected_items+"&mask=1",obj   ,"dialogWidth=500px;dialogHeight=500px");
			if   (a=="ok")
			window.location.href=window.location   .href;
			return
		}
	}
}
function   showDetail (id) {
	window.open ("plasmid_bank_operate.php?action=detail&id="+id, 'newwindow', 'height=500, width=500, toolbar=no, menubar=no, scrollbars=no, resizable=yes,location=no, status=no')
}
function resetform(f) {
	f.PageSize.value="10";
	f.keywords.value="";
	for (i=0; f.elements[i]; i++) {
		if(f.elements[i].type=="select-one") {
			f.elements[i].options[0].selected=true;
		}
	}
}
function   addStorage (module_id, item_id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("storages_operate.php?action=add_form&module_id="+module_id+"&item_id="+item_id+"&mask=1",obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
	window.location.href=window.location   .href;
}
function   edit (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("plasmid_bank_operate.php?action=edit_form&id="+id,obj   ,"dialogWidth=600px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   del (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("plasmid_bank_operate.php?action=delete_form&id="+id,obj   ,"dialogWidth=600px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
</script>
<form action="" method="get" name="search" target="_self">
<table width="100%" class='search'>
<tr>
<td><h2 align="center">Plasmid bank&nbsp;&nbsp;</h2></td>
</tr>
<tr>
<td>
Search:&nbsp;&nbsp;
<input type="text" size="40" name="keywords" value="<?php echo $_REQUEST['keywords']?>"/>
<input type='Submit' name='Submit' value='Go' />
<input type="reset" value="Clean"/>
</td>
</tr>
<tr>
<td>
<?php
echo "AND maker:&nbsp;&nbsp;";
$query="SELECT id, name FROM people ORDER BY CONVERT(name USING GBK)";
echo query_select_all("maker",$query,"id","name",$_REQUEST['maker']);
?>
</td>
</tr>
<tr>
<td>
<?php
if ( isset($_GET['PageSize']) )
$PageSize=(int)$_GET['PageSize'];
else $PageSize=10;
echo "Show <input type='text' name='PageSize' size='2' value=".$PageSize."> items per page, ";
echo "sort by:";
$sort=array('id'=>'id');
echo array_select('sort',$sort,$_REQUEST['sort']);
$order=array(DESC=>DESC,ASC=>ASC);
echo array_select('order',$order,$_REQUEST['order']);
?>
</td>
</tr>
</table>
</form>

<?php
if($_REQUEST['id']==null){$_REQUEST['id']='%';}
if($_REQUEST['plasmid_id']==null){$_REQUEST['plasmid_id']='%';}
if($_REQUEST['keywords']==null){$_REQUEST['keywords']='%';}
if($_REQUEST['maker']==null){$_REQUEST['maker']='%';}
if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

$id=$_REQUEST['id'];
$keywords=$_REQUEST['keywords'];
$plasmid_id=$_REQUEST['plasmid_id'];
$maker=$_REQUEST['maker'];
$sort=$_REQUEST['sort'];
$order=$_REQUEST['order'];

$db_conn = db_connect();
$query = "SELECT *
    	FROM plasmid_bank WHERE
    	id LIKE '$id'
    	AND id LIKE '$keywords'
    	AND `plasmid_id` LIKE '$plasmid_id'
    	AND `maker` LIKE '$maker'
      ORDER BY $sort $order";

$_SESSION['query']=$query;//used for EXCEL export
pagerForm('plasmid_bank',$query,10);
$userauth=check_user_authority($_COOKIE['wy_user']);
$module=get_record_from_name('modules',"plasmid bank");
if ($results  && $results->num_rows) {
	echo "<form action='' method='post' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header' width='5%'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header' width='5%'>";
	echo "ID</td><td class='results_header' width='40%'>";
	echo "Plasmid name</td><td class='results_header' width='10%'>";
	echo "Maker</td><td class='results_header' width='10%'>";
	echo "Request</td><td class='results_header' width='10%'>";
	echo "Volume<br>used/total</td><td class='results_header' width='10%'>";
	echo "Storage</td><td class='results_header' width='10%'>";
	echo "Operate</td></tr>";

	while ($matches = $results->fetch_array()) {
		echo "<tr><td class='results'><input type='checkbox' onclick=changechecked(this) name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
		echo $matches['id']."</td><td class='results'>";
		$people=get_name_from_id('people',$matches['maker']);
		$plasmid=get_name_from_id('plasmids',$matches['plasmid_id']);
		echo "<a href='plasmids.php?id={$matches['plasmid_id']}' target='_blank'>".$plasmid['name']."</a></td><td class='results'>";
		echo $people['name']."</td><td class='results'>";
		//request
		$query = "select id from plasmid_request WHERE `plasmid_bank_id` = '{$matches['id']}'";
		$rs = $db_conn->query($query);
		$num_request=$rs->num_rows;
		$query="SELECT SUM(`volume`) AS `total_volume` FROM `plasmid_request` WHERE `plasmid_bank_id`='{$matches['id']}'";
		$rs = $db_conn->query($query);
		$match_request=$rs->fetch_assoc();
		if ($num_request>0) {
			$total_request_volume=$match_request['total_volume'];
		} else {
			$total_request_volume=0;
		}
		if ($num_request>0) {
			echo "<a href='plasmid_request.php?plasmid_bank_id={$matches['id']}' target='_blank'>".$num_request."</a></td><td class='results'>";
		} else {
			echo $num_request."</td><td class='results'>";
		}
		//volume
		echo $total_request_volume."/".$matches['volume']." μL</td><td class='results'>";
		//storages
		$query = "select id from storages WHERE module_id='{$module['id']}' AND item_id = '{$matches['id']}' AND state=1";
		$storage = $db_conn->query($query);
		$storage_count=$storage->num_rows;
		if($storage_count>0) {
			echo '<a href="storages.php?module_id='.$module['id'].'&item_id='.$matches['id'].'" target="_blank">'.$storage_count.'</a>';
		} else {
			echo $storage_count;
		}
		echo "&nbsp;&nbsp;";
		if (userPermission('3')) {
			echo "<a onclick=\"addStorage({$module['id']},{$matches['id']})\" style=\"cursor:pointer\"/><img
	    src=\"./assets/image/general/add-s.gif\" alt=\"Store\" title=\"Store\" border=\"0\"/></a></td><td class=\"results\">";
		} else {
			echo '<img src="./assets/image/general/add-s-grey.gif" alt="Store" title="Store" border="0"/></td><td class="results">';
		}
		//operate
		echo "<a onclick=\"edit({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit' title='Edit' border='0'/></a>&nbsp;&nbsp;<a onclick=\"del({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/del-s.gif' alt='Delete' title='Delete' border='0'/></a></td></tr>";
	}
	?>
</table>
<table>
<tr><td>Selected:
<select name="actionRequest" onchange="javascipt:submitResultsForm(<?php echo $module['id'] ?>)">
<option value="" selected> -Choose- </option>
<option value="store">Store</option>
</select>
</td>
<tr>
</table>
</form>
	<?php
}
	?>

<?php
//do_rightbar();
do_footer();
do_html_footer();
?>
