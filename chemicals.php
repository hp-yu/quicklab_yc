<?php
include_once('include/includes.php');
?>
<script src="./jmol/Jmol.js" type="text/javascript"></script>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��?
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('chemicals');?>
<?php
  do_html_header('Chemicals-Quicklab');
  do_header();
  //do_leftnav();
?>
<?php
  js_selectall();
  js_selectedRequest_module();
?>
<script>
function   requestOrder (module_id, item_id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("orders_operate.php?action=request_form&module_id="+module_id+"&item_id="+item_id,obj   ,"dialogWidth=500px;dialogHeight=500px");
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
	$fields="CONCAT(name,description,synonym,cas)";
	$sort=array('id'=>'id','name'=>'name');
  ?>
		<tr>
      <td align='center' valign='middle'><h2>Chemicals&nbsp;&nbsp;<?php
      if (userPermission("3")){
      	echo "<a href='chemicals_operate.php?type=add'><img src='./assets/image/general/add.gif' alt='Add new' border='0'/></a>";
      	//echo "&nbsp;<a href='chemicals_operate.php?type=import'><img src='./assets/image/general/import.gif' alt='Import from file' border='0'/></a></h2>";
      }
      else {
      	echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" border="0"/>';
      	//echo '&nbsp;<img src="./assets/image/general/import-grey.gif" alt="Import from file" border="0"/></h2>';
 	    }?>
	  </td>
    </tr>
    <?php search_keywords('chemicals',$fields);?>
    <tr>
      <td colspan='2' height='21' valign='top'>And project:<?php
      $query= "select * from projects ORDER BY name";
      echo query_select_all('project', $query,'id','name',$_REQUEST['project']);?>
      </td>
    </tr>
    <tr>
      <td>And created by:<?php
      $query= "select id,name from people ORDER BY CONVERT(name USING GBK)";
      echo query_select_all('created_by', $query,'id','name',$_REQUEST['created_by']);
      ?>AND masked:<?php
      $mask=array(array("0","no"),
      array("1","yes"));
		echo option_select_all('mask',$mask,2,$_REQUEST['mask']);?></td>
    </tr>
  <?php
	resultsDisplayControl($sort,10);
	?>
  </table>
</form>
<?php
$db_conn = db_connect();
$query=query_module('chemicals');
pagerForm('chemicals',$query);
//searchResultsForm('chemicals',$results);
	if ($results  && $results->num_rows) {
		echo "<form action='' method='post' name='results' target='_self' >";
		echo "<table width='100%' class='results'>";
		echo "<tr><td class='results_header'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header'>";
		echo "Quick ID</td><td class='results_header'>";
		echo "Name</td><td class='results_header'>";
		echo "Create</td><td class='results_header'>";
		echo "Operate</td><td class='results_header'>";
		echo "Storage</td><td class='results_header'>";
		echo "Order</td></tr>";
		while ($matches = $results->fetch_array()) {
			$module=get_record_from_name('modules','chemicals');
			echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this)  name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
			echo get_quick_id($module[id],$matches[id])."</td><td width='200' class='results'>";
			echo "<a href='chemicals_operate.php?type=detail&id={$matches[id]}'>".wordwrap($matches[name],190,"<br>")."</a></td><td class='results'>";
			$people=get_record_from_id('people',$matches['created_by']);
			echo $people['name']." ".$matches['date_create']."</td><td class='results'>";
			
			if (userPermission('2',$matches['created_by'])) {
//				echo "<a href='label.php?module_id=".$module['id']."&item_id=".$matches['id']."' target='_blank'><img src='./assets/image/general/label-s.gif' alt='Print label' border='0'/></a>&nbsp;&nbsp;";
				echo "<a href='chemicals_operate.php?type=edit&id=".$matches['id']."'><img src='./assets/image/general/edit-s.gif' alt='Edit' border='0'/></a>&nbsp;&nbsp;";
				echo "<a href='chemicals_operate.php?type=structure&id=".$matches['id']."'><img src='./assets/image/general/structure.gif' alt='structure' border='0'/></a>&nbsp;&nbsp;";
//				echo "<a href='chemicals_operate.php?type=relation&id=".$matches['id']."'><img src='./assets/image/general/attach-s.gif' alt='Related items' border='0'/></a>&nbsp;&nbsp;";
				echo "<a href='chemicals_operate.php?type=delete&id=".$matches['id']."'><img src='./assets/image/general/del-s.gif' alt='Delete'  border='0'/></a></td><td class='results'>";
			}
			else {
//				echo '<img src="./assets/image/general/label-s.gif" alt="Print label" border="0"/>&nbsp;&nbsp;';
				echo '<img src="./assets/image/general/edit-s-grey.gif" alt="Edit" border="0"/>&nbsp;&nbsp;';
				echo '<img src="./assets/image/general/structure.gif" alt="structure" border="0"/>&nbsp;&nbsp;';
//				echo '<img src="./assets/image/general/attach-s-grey.gif" alt="Related items" border="0"/>&nbsp;&nbsp;';
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
			}
			else {
				echo $storage_count;
			}
			echo "&nbsp;&nbsp;";
			if (userPermission('3')) {
				echo "<a onclick=\"addStorage({$module['id']},{$matches['id']})\" style=\"cursor:pointer\"/><img
	    src=\"./assets/image/general/add-s.gif\" alt=\"Store\" border=\"0\"/></td><td class=\"results\">";
			}
			else {
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
			}
			else {
				echo $order_count;
			}
			echo "&nbsp;&nbsp;";
			if (userPermission('3')) {
				echo "<a onclick=\"requestOrder({$module['id']},{$matches['id']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/add-s.gif\" alt=\"Request\" border=\"0\"/></a></td></tr>";
			}
			else {
				echo '<img src="./assets/image/general/add-s-grey.gif" alt="Request" border="0"/></td></tr>';
			}
		}
		echo '</table>';
		selectSubmit("chemicals");
		echo '</form>';
	}

?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
