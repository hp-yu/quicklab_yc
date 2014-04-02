<?php
include_once('include/includes.php');
?>
<?php
$_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('devices');?>
<?php
do_html_header('Devices-Quicklab');
do_header();
//do_leftnav();
?>
<?php
js_selectall();
js_selectedRequest_module();
?>
<script>
function   requestOrder (module_id, item_id,manufacturer,dealer,cat_nbr) {
	var   obj   =   new   Object();
	var module_id;
	var item_id;
	var mamufacture;
	var dealer;
	var cat_nbr;
	var   a=window.showModalDialog("orders_operate.php?action=request_form&module_id="+module_id+"&item_id="+item_id+"&manufacturer="+manufacturer+"&dealer="+dealer+"&cat_nbr="+cat_nbr,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
	window.location.href=window.location   .href;
}
function   addStorage (module_id, item_id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("storages_operate.php?action=add_form&module_id="+module_id+"&item_id="+item_id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
</script>
<form action="" method='get' name='search' target='_self'>
  <table width='100%' class='search'>
	<?php
	$db_conn = db_connect();
	$fields="CONCAT(name,description";
	$query="SELECT * FROM custom_fields WHERE module_name='devices' AND search=1 ORDER BY id";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$fields.=",".$match['field_id'];
	}
	$fields.=")";
	$sort=array('id'=>'id','name'=>'name');
	search_header('Devices');
	search_keywords('devices',$fields);
?>
  <tr>
    <td colspan='2' height='21' valign='top'>
    And device category:
<?php
$query= "select * from device_cat ORDER BY name";
echo query_select_all('device_cat_id', $query,'id','name',$_REQUEST['device_cat_id']);
?>
		And project:
<?php
$query= "select * from projects ORDER BY name";
echo query_select_all('project', $query,'id','name',$_REQUEST['project']);
?>
    </td>
  </tr>
<?php
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
if($_REQUEST['device_cat_id']==null){$_REQUEST['device_cat_id']='%';}
if($_REQUEST['created_by']==null){$_REQUEST['created_by']='%';}
if($_REQUEST['mask']==null){$_REQUEST['mask']='%';}

$id=$_REQUEST['id'];
$device_cat_id=$_REQUEST['device_cat_id'];
$fields=$_REQUEST['fields'];
$sort=$_REQUEST['sort'];
$order=$_REQUEST['order'];
$project=$_REQUEST['project'];
$created_by=$_REQUEST['created_by'];
$mask=$_REQUEST['mask'];

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
  FROM devices
  WHERE ($keywords_string)
  AND id LIKE '$id'
  AND device_cat_id LIKE '$device_cat_id'
  AND project LIKE '$project'
  AND created_by LIKE '$created_by'
  AND mask LIKE '$mask'
  $mask_str ORDER BY $sort $order";
$_SESSION['query']=$query;//used for EXCEL export
pagerForm('devices',$query);
if ($results  && $results->num_rows) {
	echo "<form action='' method='post' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header'>";
	echo "Quick ID</td><td class='results_header'>";
	echo "Name&Cat/SN</td><td class='results_header'>";
	echo "Manufacturer&Dealer</td><td class='results_header'>";
	echo "Warranty</td><td class='results_header'>";
	echo "Operate</td><td class='results_header'>";
	echo "Storage</td><td class='results_header'>";
	echo "Order</td></tr>";
	while ($matches = $results->fetch_array()) {
		$module=get_record_from_name('modules','devices');
		echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this)  name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
		echo get_quick_id($module[id],$matches[id])."</td><td width='200' class='results'>";
		echo "<a href='devices_operate.php?type=detail&id={$matches[id]}'>".wordwrap($matches['name'],190);
		if ($matches['cat_nbr']!="") {
			echo "<br>".$matches['cat_nbr'];
		}
		if ($matches['sn']!="") {
			echo "/".$matches['sn'];
		}
		echo "</a></td><td class='results'>";
		$manufacturer=get_record_from_id('sellers',$matches['manufacturer']);
		$dealer=get_record_from_id('sellers',$matches['dealer']);
		echo "<a href='sellers_operate.php?type=detail&id=".$matches['manufacturer']."' target='_blank'>".$manufacturer['name']."</a><br><a href='sellers_operate.php?type=detail&id=".$matches['dealer']."' target='_blank'>".$dealer['name']."</a></td><td class='results'>";
		if ($matches['date_warranty_start']!="0000-00-00") {
			echo "start: ".$matches['date_warranty_start'];
		}
		if ($matches['date_warranty_end']!="0000-00-00") {
			echo "<br>end: ".$matches['date_warranty_end'];
		}
		echo "</td><td class='results'>";
		
		if (userPermission('2',$matches['created_by'])) {
			//echo "<a href='label.php?module_id=".$module['id']."&item_id=".$matches['id']."' target='_blank'><img src='./assets/image/general/label-s.gif' title='Print label' border='0'/></a>&nbsp;&nbsp;";
			echo "<a href='devices_operate.php?type=edit&id=".$matches['id']."'><img src='./assets/image/general/edit-s.gif' title='Edit' border='0'/></a>&nbsp;&nbsp;";
			//echo "<a href='devices_operate.php?type=relation&id=".$matches['id']."'><img src='./assets/image/general/attach-s.gif' title='Related items' border='0'/></a>&nbsp;&nbsp;";
			echo "<a href='devices_operate.php?type=delete&id=".$matches['id']."'><img src='./assets/image/general/del-s.gif' title='Delete'  border='0'/></a></td><td class='results'>";
		}
		else
		{
			//echo '<img src="./assets/image/general/label-s.gif" title="Print label" border="0"/>&nbsp;&nbsp;';
			echo '<img src="./assets/image/general/edit-s-grey.gif" title="Edit" border="0"/>&nbsp;&nbsp;';
			//echo '<img src="./assets/image/general/attach-s-grey.gif" title="Related items" border="0"/>&nbsp;&nbsp;';
			echo '<img src="./assets/image/general/del-s-grey.gif" title="Delete"  border="0"/></td><td class="results">';
		}
		//query the storages of this item where the state is in stock.
		$db_conn=db_connect();
		$query = "select id from storages WHERE module_id='{$module['id']}' AND item_id = '{$matches['id']}' AND state=1";
		$storage = $db_conn->query($query);
		$storage_count=$storage->num_rows;
		if($storage_count>0) {
			echo '<a href="storages.php?module_id='.$module['id'].'&item_id='.$matches['id'].'" target="_blank"><img src="./assets/image/general/info-s.gif" title="Info" border="0"/></a></td><td class="results">';
		}
		else {
			if (userPermission('3')) {
				echo "<a onclick=\"addStorage({$module['id']},{$matches['id']})\" style=\"cursor:pointer\"/><img
	    src=\"./assets/image/general/add-s.gif\" title=\"Store\" border=\"0\"/></td><td class=\"results\">";
			}
			else {
				echo '<img src="./assets/image/general/add-s-grey.gif" title="Store" border="0"/></td><td class="results">';
			}
		}

		//query the orders of this item where the state is not cancelled.
		$query = "select id from orders WHERE module_id='{$module['id']}'
                AND item_id = '{$matches['id']}'
                AND cancel=0";
		$order = $db_conn->query($query);
		$order_count=$order->num_rows;
		if($order_count>0)  {
			echo '<a href="orders.php?module_id='.$module['id'].'&item_id='.$matches['id'].'" target="_blank"><img src="./assets/image/general/info-s.gif" title="Info" border="0"/></a></td></tr>';
		}
		else {
			if (userPermission('3')) {
				echo "<a onclick=\"requestOrder({$module['id']},{$matches['id']},{$matches['manufacturer']},{$matches['dealer']},'{$matches['cat_nbr']}')\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/add-s.gif\" title=\"Request\" border=\"0\"/></a></td></tr>";
			}
			else {
				echo '<img src="./assets/image/general/add-s-grey.gif" title="Request" border="0"/></td></tr>';
			}
		}
	}
	echo '</table>';
	selectSubmit("devices");
	echo '</form>';
}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
