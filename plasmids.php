<?php
include_once('include/includes.php');
?>
<?php
$_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('plasmids');?>
<?php
do_html_header('Plasmids-Quicklab');
do_header();
//do_leftnav();
?>
<?php
js_selectall();
js_selectedRequest_module();
?>
<form action="" method='get' name='search' target='_self'>
<table width='100%' class='search'>
<?php
$db_conn = db_connect();
$fields="CONCAT(name,description,vector_name,insert_name,sequence_identifier";
$query="SELECT * FROM custom_fields WHERE module_name='plasmids' AND search=1 ORDER BY id";
$rs=$db_conn->query($query);
while ($match=$rs->fetch_assoc()) {
	$fields.=",".$match['field_id'];
}
$fields.=")";
$sort=array('id'=>'id','name'=>'name');
search_header('Plasmids');
search_keywords('Plasmids',$fields);
echo "<tr><td colspan='2' height='21' valign='top'>And project:";
$query= "select * from projects ORDER BY name";
echo query_select_all('project', $query,'id','name',$_REQUEST['project']);
echo "&nbsp;&nbsp;";
//echo "Bank:&nbsp;&nbsp;";
//$bank=array("Yes"=>"1","No"=>"0");
//echo array_select_all("bank",$bank,$_REQUEST['bank']);
echo "</td></tr>";
search_create_mask();
resultsDisplayControl($sort,10);
?>
</table>
</form>
<?php
if($_REQUEST['id']==null){$_REQUEST['id']='%';}
if($_REQUEST['fields']==null){$_REQUEST['fields']='name';}
if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}
if($_REQUEST['project']==null){$_REQUEST['project']='%';}
if($_REQUEST['created_by']==null){$_REQUEST['created_by']='%';}
if($_REQUEST['mask']==null){$_REQUEST['mask']='%';}
//if($_REQUEST['bank']==null){$_REQUEST['bank']="%";}

$id=$_REQUEST['id'];
$fields=$_REQUEST['fields'];
$sort=$_REQUEST['sort'];
$order=$_REQUEST['order'];
$project=$_REQUEST['project'];
$created_by=$_REQUEST['created_by'];
$mask=$_REQUEST['mask'];
//$bank=$_REQUEST['bank'];

//seperate the keywords by space ("AND" "LIKE")
$keywords = split(' ', $_REQUEST['keywords']);
$num_keywords = count($keywords);
for ($i=0; $i<$num_keywords; $i++) {
	if ($i) {
		$keywords_string .= "AND $fields LIKE '%".$keywords[$i]."%' ";
	} else {
		$keywords_string .= "$fields LIKE '%".$keywords[$i]."%' ";
	}
}

$userauth=check_user_authority($_COOKIE['wy_user']);
$userpid=get_pid_from_username($_COOKIE['wy_user']);

if($userauth>2) {
	$mask_str=" AND (mask=0 OR created_by='{$userpid}')";
} else {
	$mask_str="";
}

$query =  "SELECT *,
	DATE_FORMAT(date_create,'%m/%d/%y') AS date_create
  FROM plasmids
  WHERE $keywords_string
  AND id LIKE '$id'
  AND project LIKE '$project'
  AND created_by LIKE '$created_by'
  AND mask LIKE '$mask'
  $mask_str

  ORDER BY $sort $order";
$_SESSION['query']=$query;//used for EXCEL export
pagerForm('plasmids',$query);
?>
<script>
function   requestOrder (module_id, item_id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("orders_operate.php?action=request_form&module_id="+module_id+"&item_id="+item_id,obj   ,"dialogWidth=600px;dialogHeight=400px");
	if   (a=="ok")
	window.location.href=window.location   .href;
}
function   addStorage (module_id, item_id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("storages_operate.php?action=add_form&module_id="+module_id+"&item_id="+item_id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
	window.location.href=window.location   .href;
}
function   request (plasmid_id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("plasmid_request_operate.php?action=request_form&plasmid_id="+plasmid_id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
	window.location.href=window.location   .href;
}
function   bank (plasmid_id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("plasmid_bank_operate.php?action=add_form&plasmid_id="+plasmid_id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
	window.location.href=window.location   .href;
}
</script>
	<?php
	$module=get_record_from_name('modules',"plasmids");
	if ($results  && $results->num_rows) {
		echo "<form action='' method='post' name='results' target='_self' >";
		echo "<table width='100%' class='results'>";
		echo "<tr><td class='results_header' width='4%'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header' width='4%'>";
		echo "Quick ID</td><td class='results_header' width='30%'>";
		echo "Name</td><td class='results_header' width='15%'>";
		echo "Create</td><td class='results_header' width='15%'>";
		echo "Operate</td><td class='results_header' width='8%'>";
		echo "Storage</td><td class='results_header' width='8%'>";
		echo "Order</td></tr>";
//		echo "Bank</td><td class='results_header' width='8%'>";
//		echo "Request</td></tr>";
		while ($matches = $results->fetch_array()) {
			echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this)  name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
			echo  get_quick_id($module[id],$matches[id])."</td><td class='results'>";
			echo "<a href='plasmids_operate.php?type=detail&id={$matches[id]}'>".$matches['name']."</a></td><td class='results'>";
			$people=get_record_from_id('people',$matches['created_by']);
			echo $people['name']." ".$matches['date_create']."</td><td class='results'>";
			//echo "<a href='label.php?module_id=".$module['id']."&item_id=".$matches['id']."' target='_blank'><img src='./assets/image/general/label-s.gif' alt='Print label' border='0'/></a>&nbsp;&nbsp;";
			if (userPermission('2',$matches['created_by'])) {
				echo "<a href='plasmid_mapping.php?plasmid_id=".$matches['id']."' target='_blank'><img src='./assets/image/general/plasmid-mapping-s.gif' alt='Plasmid mapping' border='0'/></a>&nbsp;&nbsp;";
				echo "<a href='plasmids_operate.php?type=edit&id=".$matches['id']."'><img src='./assets/image/general/edit-s.gif' alt='Edit' border='0'/></a>&nbsp;&nbsp;";
				//echo "<a href='plasmids_operate.php?type=relation&id=".$matches['id']."'><img src='./assets/image/general/attach-s.gif' alt='Related items' border='0'/></a>&nbsp;&nbsp;";
				echo "<a href='plasmids_operate.php?type=delete&id=".$matches['id']."'><img src='./assets/image/general/del-s.gif' alt='Delete'  border='0'/></a></td><td class='results'>";
			} else {
				echo "<img src='./assets/image/general/plasmid-mapping-s-grey.gif' alt='Plasmid mapping' border='0'/>&nbsp;&nbsp;";
				echo '<img src="./assets/image/general/edit-s-grey.gif" alt="Edit" border="0"/>&nbsp;&nbsp;';
				//echo '<img src="./assets/image/general/attach-s-grey.gif" alt="Related items" border="0"/>&nbsp;&nbsp;';
				echo '<img src="./assets/image/general/del-s-grey.gif" alt="Delete"  border="0"/></td><td class="results">';
			}
			//query the storages of this item where the state is in stock.
			$query = "select id from storages WHERE module_id='{$module['id']}'
                AND item_id = '{$matches['id']}'
                AND state=1";
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
	    src=\"./assets/image/general/add-s.gif\" alt=\"Store\" border=\"0\"/></a></td><td class=\"results\">";
			} else {
				echo '<img src="./assets/image/general/add-s-grey.gif" alt="Store" border="0"/></td><td class="results">';
			}
			//query the orders of this item where the state is not cancelled.
			$query = "select id from orders WHERE module_id='{$module['id']}'
                AND item_id = '{$matches['id']}'
                AND cancel=0";
			$order = $db_conn->query($query);
			$order_count=$order->num_rows;
			if($order_count>0) {
				echo '<a href="orders.php?module_id='.$module['id'].'&item_id='.$matches['id'].'" target="_blank">'.$order_count.'</a>';
			} else {
				echo $order_count;
			}
			echo "&nbsp;&nbsp;";
			if (userPermission('3')) {
				echo "<a onclick=\"requestOrder({$module['id']},{$matches['id']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/add-s.gif\" alt=\"Request\" border=\"0\"/></a></td>";
			} else {
				echo "<img src=\"./assets/image/general/add-s-grey.gif\" alt=\"Request\" border=\"0\"/>";
			}
//			query the bank.
//			$query = "select * from plasmid_bank WHERE `plasmid_id` = '{$matches['id']}'";
//			$rs = $db_conn->query($query);
//			$num_bank=$rs->num_rows;
//			if($num_bank>0) {
//				if (userPermission('2')) {
//					echo '<a href="plasmid_bank.php?plasmid_id='.$matches['id'].'" target="_blank">'.$num_bank.'</a>&nbsp;&nbsp;';
//				} else {
//					echo $num_bank."&nbsp;&nbsp;";
//				}
//			}
//			if (userPermission('2')) {
//				echo "<a onclick=\"bank({$matches['id']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/add-s.gif\" alt=\"Add to bank\" border=\"0\"/></a>";
//			} else {
//				echo '<img src="./assets/image/general/add-s-grey.gif" alt="Add to bank" border="0"/>';
//			}
//			echo "</td><td class=\"results\">";
//			request
//			$query = "select id from plasmid_request WHERE `plasmid_id` = '{$matches['id']}'";
//			$rs = $db_conn->query($query);
//			$num_request=$rs->num_rows;
//			$query="SELECT SUM(`volume`) AS `total_volume` FROM `plasmid_request` WHERE `plasmid_id`='{$matches['id']}'";
//			$result_request = $db_conn->query($query);
//			$match_request=$result_request->fetch_assoc();
//			$total_volume=$match_request['total_volume'];
//			if($num_request>0) {
//				echo '<a href="plasmid_request.php?plasmid_id='.$matches['id'].'" target="_blank">'.$num_request.'</a>&nbsp;&nbsp;';
//			}
//			if ($num_bank>0) {
//				if (userPermission('3')) {
//					echo "<a onclick=\"request({$matches['id']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/add-s.gif\" alt=\"Request\" border=\"0\"/></a>";
//				} else {
//					echo '<img src="./assets/image/general/add-s-grey.gif" alt="Request" border="0"/>';
//				}
//			}
			echo "</td></tr>";
		}
		echo '</table>';
		selectSubmit("plasmids");
		echo '</form>';
	}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
