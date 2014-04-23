<?php
include('include/includes.php');
?>
<?php
$_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
do_html_header('Device maintenance-Quicklab');
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
<script>
function   requestOrder (module_id, item_id, dealer) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("orders_operate.php?action=request_form&module_id="+module_id+"&item_id="+item_id+"&dealer="+dealer, obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
</script>
<form action="maintenance.php" method="get" name="search" target="_self" id="form1">
  <table width="100%" class="search">
    <tr><td align="center">
      <h2>Device maintenance</h2></td>
    </tr>
    <tr>
      <td>Search device:
<?php
$db_conn = db_connect();
$query="SELECT * FROM devices ORDER BY CONVERT(name USING GBK)";
$result=$db_conn->query($query);
$select  = "<select name='device_id'>";
$select .= '<option value="%"';
if($_REQUEST['device_id'] == '') $select .= ' selected ';
$select .= '>- Select all -</option>';
while($option = $result->fetch_assoc()) {
	$select .= "<option value='{$option['id']}'";
	if ($option['id'] == $_REQUEST['device_id']) {
		$select .= ' selected';
	}
	$select .=  ">{$option['name']}";
	if ($option['cat_nbr']!="") {
		$select .="/{$option['cat_nbr']}";
	}
	if ($option['sn']!="") {
		$select .="/{$option['sn']}";
	}
	$select .="</option>";
}
$select .= "</select>\n";
echo $select;
?>
      <input type="submit" name="Submit" value="Go" />
    </td>
  </tr>
  <tr>
  	<td>AND operator:
<?php
$query="SELECT * FROM people WHERE state=1 ORDER BY CONVERT(name USING GBK)";
echo query_select_all('operator',$query,'id','name',$_REQUEST['operator']);
?>
		AND company:
<?php
$query="SELECT * FROM sellers ORDER BY CONVERT(name USING GBK)";
echo query_select_all('company',$query,'id','name',$_REQUEST['company']);
?>
  	</td>
  </tr>
<?php
$sort=array('id'=>'id');
resultsDisplayControl($sort,10);
?>
</table></form>

<?php
if($_REQUEST['id']==null){$_REQUEST['id']='%';}
if($_REQUEST['device_id']==null){$_REQUEST['device_id']='%';}
if($_REQUEST['operator']==null){$_REQUEST['operator']='%';}
if($_REQUEST['company']==null){$_REQUEST['company']='%';}
if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

$id=$_REQUEST['id'];
$device_id=$_REQUEST['device_id'];
$operator=$_REQUEST['operator'];
$company=$_REQUEST['company'];
$sort=$_REQUEST['sort'];
$order=$_REQUEST['order'];

$query =  "SELECT * FROM maintenance WHERE
          	 id LIKE '$id'
          	 AND device_id LIKE '$device_id'
          	 AND operator LIKE '$operator'
          	 AND company LIKE '$company'
	           ORDER BY $sort $order";

$_SESSION['query']=$query;//used for EXCEL export

pagerForm('maintenance',$query);

if ($results&&$results->num_rows>0) {
	echo "<form action='' method='get' name='results' target='_self' >";
	echo "<table width='100%' class='results'>";
	echo "<tr><td class='results_header'>ID</td><td class='results_header'>";
	echo "Device&Cat/SN</td><td class='results_header'>";
	echo "Operator&Date</td><td class='results_header'>";
	echo "Operate&Date</td><td class='results_header'>";
	echo "Order</td></tr>";
	while ($matches = $results->fetch_array())	{
		echo "<tr><td class='results'>".$matches['id']."</td><td class='results'>";
		$query="SELECT * FROM devices WHERE id='{$matches['device_id']}'";
		$rs=$db_conn->query($query);
		$device=$rs->fetch_assoc();
		echo '<a href="devices_operate.php?type=detail&id='.$matches['device_id'].'">';
		echo $device['name'];
		if ($device['cat_nbr']!=""||$device['sn']!="") {
			echo "<br>";
		}
		if ($device['cat_nbr']!="") {
			echo "{$device['cat_nbr']}";
		}
		if ($device['sn']!="") {
			echo "/{$device['sn']}";
		}
		echo "</a></td><td class='results'>";
		$query="SELECT * FROM people WHERE id='{$matches['operator']}'";
		$rs=$db_conn->query($query);
		$operator=$rs->fetch_assoc();
		echo '<a href="maintenance_operate.php?type=detail&id='.$matches['id'].'">';
		echo $operator['name']."<br>".date('Y-m-d',strtotime($matches['date_maintenance']))."</a></td><td class='results'>";
		echo '<a href="maintenance_operate.php?type=edit&id='.$matches['id'].'"><img src="./assets/image/general/edit-s.gif" alt="Edit" title="Edit" border="0"/></a></td><td class="results">';
		//query the orders of this item where the state is not cancelled.
		$module=get_record_from_name('modules','maintenance');
		$query = "select id from orders WHERE module_id='{$module['id']}'
                AND item_id = '{$matches['id']}'
                AND cancel=0";
		$order = $db_conn->query($query);
		$order_count=$order->num_rows;
		if($order_count>0)  {
			echo "<a href='orders.php?module_id=".$module['id']."&item_id=".$matches['id']."' target='_blank'><img src=\"./assets/image/general/info-s.gif\" title=\"Info\" border=\"0\"/></a></td></tr>";
		}
		else {
			if (userPermission('3')) {
				echo "<a onclick=\"requestOrder({$module['id']},{$matches['id']},{$matches['company']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/add-s.gif\" title=\"Request\" border=\"0\"/></a></td></tr>";
			}
			else {
				echo '<img src="./assets/image/general/add-s-grey.gif" title="Request" border="0"/></td></tr>';
			}
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