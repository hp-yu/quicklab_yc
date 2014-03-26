<?php
include('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��???
$_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Storages-Quicklab</title>
<link href="CSS/general.css" rel="stylesheet" type="text/css" />
</head>
<body leftmargin="5" topmargin="5">
<?php
do_header();
//do_leftnav();
?>
<?php
js_selectall();
?>

<script>
function   showDetail (id) {
	window.open ("storages_operate.php?action=detail&id="+id, 'newwindow', 'height=500, width=500, toolbar=no, menubar=no, scrollbars=no, resizable=yes,location=no, status=no')
}
function   addStorage () {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("storages_operate.php?action=add_form",obj,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location.href;
}
function   editStorage (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("storages_operate.php?action=edit_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function submitResultsForm() {
	var selectcount = 0;//check at least select one
	for(i=0;i<document.results.elements.length;i++){
		if (document.results.elements[i].checked){
			selectcount = selectcount + 1;
		}
	}
	if(selectcount==0)
	{
		alert("You have not select any item.")
		document.results.actionRequest.value = ""
		return
	}
	var confirmVal
	if (document.results.actionRequest.value == "OUTOFSTOCK") {
		confirmVal = confirm("Are you sure the selected storage(s) out of stock? and make sure you have the authority to do this.")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		}
		else{
			document.results.actionType.value = "OUTOFSTOCK";
			document.results.submit();
			return
		}
	}
	if (document.results.actionRequest.value == "INSTOCK") {
		confirmVal = confirm("Are you sure the selected storage(s) in stock? and make sure you have the authority to do this.")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		}
		else{
			document.results.actionType.value = "INSTOCK"
			document.results.submit();
			return;
		}
	}
	if (document.results.actionRequest.value == "CHANGEKEEPER") {
		confirmVal = confirm("Are you sure to change the keeper of the selected storage(s)? and make sure you have the authority to do this.")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		}
		else {
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
			var   a=window.showModalDialog("storages_operate.php?action=change_keeper_form&selected_items="+selected_items,obj   ,"dialogWidth=500px;dialogHeight=500px");
			if   (a=="ok")
			window.location.href=window.location   .href;
		}
	}
	if (document.results.actionRequest.value == "CHANGELOCATION") {
		confirmVal = confirm("Are you sure to change the location of the selected storage(s)? and make sure you have the authority to do this.")
		if (!confirmVal){
			document.results.actionRequest.value = ""
			return
		}
		else{
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
			var   a=window.showModalDialog("storages_operate.php?action=change_location_form&selected_items="+selected_items,obj   ,"dialogWidth=500px;dialogHeight=500px");
			if   (a=="ok")
			window.location.href=window.location   .href;
		}
	}

}
</script>
<form action='storages.php' method='get' name='search' target='_self' >
  <table width='100%' class='search'>
  	 <tr>
       <td><h2 align='center'>Storages&nbsp;&nbsp;<?php
      if (userPermission("3")){
      	echo "<a onclick=\"addStorage()\" style=\"cursor:pointer\"/><img src='./assets/image/general/add.gif' alt='Request' border='0'/></a></h2>";
		  }
		  else {
      	echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" border="0"/></h2>';
		  }?></td>
     </tr>
<?php
$fields="CONCAT(name)";
search_keywords('storages',$fields);
?>
     <tr>
     <td>AND keep by:&nbsp;<?php
     $query= "select id,name from people";
		echo query_select_all('keeper', $query,'id','name',$_REQUEST['keeper']);?>
      	AND state:&nbsp;<?php
      	$state=array('In stock'=>'1','Out of stock'=>'0');
      	echo array_select('state',$state,$_REQUEST['state']);?>
      	AND mask:<?php
		$mask=array(array("0","no"),array("1","yes"));
		echo option_select_all('mask',$mask,2,$_REQUEST['mask']);?>
     </td>
     </tr>
<?php
//Get the location id.
$num_select = $_REQUEST['num_select'];
for ($i=1;$i<=$num_select;$i++) {
	if ($_REQUEST['S'.$i]!="") {
		$location_id = $_REQUEST['S'.$i];
	}
}
?>
<tr>
<td>
<div id="cascade_select">
AND location:&nbsp;
<input type='hidden' id='br' value='0'/>
<input type='hidden' id='location' value='<?php echo $location_id;?>'/>
</div>
</td>
</tr>
<script type="text/javascript" src="include/phprpc/js/compressed/phprpc_client.js"></script>
<script type="text/javascript" src="include/phprpc/cascade_select.js"></script>
<script>
rpc_cascade_select.useService('include/phprpc/cascade_select.php');
</script>
     <tr>
      <td><?php
      if ( isset($_GET['PageSize']) )
      $PageSize=(int)$_GET['PageSize'];
      else $PageSize=5;
      echo "Show <input type='text' name='PageSize' size='2' value=".$PageSize.
	    "> items per page, "?>
	    sort by:<?php
	    $sort=array('id'=>'id','expiry date'=>'date_expiry',
	    'location'=>'location_id','keeper'=>'keeper','module'=>'module_id','item'=>'item_id');
	    echo array_select('sort',$sort,$_REQUEST['sort']);
	    $order=array(DESC=>DESC,ASC=>ASC);
	    echo array_select('order',$order,$_REQUEST['order']);
        ?></td>
    </tr>
  </table>
</form>

<?php
	if($_REQUEST['id']==null){$_REQUEST['id']='%';}
	if($_REQUEST['order_id']==null){$_REQUEST['order_id']='%';}
	if($_REQUEST['fields']==null){$_REQUEST['fields']='name';}
	if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
	if($_REQUEST['module_id']==null){$_REQUEST['module_id']='%';}
	if($_REQUEST['item_id']==null){$_REQUEST['item_id']='%';}
	if($_REQUEST['keeper']==null){$_REQUEST['keeper']='%';}
	if($_REQUEST['state']==null){$_REQUEST['state']=1;}
  if($_REQUEST['mask']==null){$_REQUEST['mask']='%';}
	if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
	if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

	$id=$_REQUEST['id'];
	$order_id=$_REQUEST['order_id'];
	$fields=$_REQUEST['fields'];
	$module_id=$_REQUEST['module_id'];
	$item_id=$_REQUEST['item_id'];
	$keeper=$_REQUEST['keeper'];
	$state=$_REQUEST['state'];
	$sort=$_REQUEST['sort'];
	$order=$_REQUEST['order'];
	$mask=$_REQUEST['mask'];

	$db_conn=db_connect();
	if (!isset($location_id) ||$location_id=="") {$location_id_sql="AND location_id LIKE '%'";}
	else {
		$location_id_sql = "AND (location_id ='$location_id' ";
		$location_id_sql .= LocationRecursion($location_id,$db_conn);
		$location_id_sql .= ")";
	}

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
		$mask_str=" AND (mask=0 OR keeper='{$userpid}')";
	}
	else {
		$mask_str="";
	}

	$query = "SELECT *,
    	DATE_FORMAT(date_storage,'%m/%d/%y')AS date_storage,
    	DATE_FORMAT(date_expiry,'%m/%d/%y')AS date_expiry
    	FROM storages WHERE ($keywords_string)
    	AND id LIKE '$id'
    	AND order_id LIKE '$order_id'
    	AND module_id LIKE '$module_id'
        AND item_id LIKE '$item_id'
        AND keeper LIKE '$keeper'
        $location_id_sql
        AND state LIKE '$state'
        AND mask LIKE '$mask'
      $mask_str ORDER BY $sort $order";
	//echo $query;
	$_SESSION['query']=$query;//used for EXCEL export

	pagerForm('storages',$query,5);

	$userauth=check_user_authority($_COOKIE['wy_user']);

	if ($results  && $results->num_rows)
	{
		//echo '<h3>Search results</h3>';
		echo "<form action='' method='post' name='results' target='_self' >";
		echo "<table width='100%' class='results'>";
		echo "<tr><td class='results_header'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header'>";
		echo "Name</td><td class='results_header'>";
		//echo "Item</td><td class='results_header'>";
		//echo "Keeper</td><td class='results_header'>";
		echo "Location&details</td><td class='results_header'>";
		//echo "Storage date</td><td class='results_header'>";
		//echo "Expiry date</td><td class='results_header'>";
		echo "Orders</td><td colspan='2' class='results_header'>";
		echo "Operate</td></tr>";

		while ($matches = $results->fetch_array())
		{
			echo "<tr><td class='results'><input type='checkbox' onclick=changechecked(this) name='selectedItem[]' value={$matches[id]}></td><td class='results'>";

			$people=get_record_from_id('people',$matches['keeper']);
			if ($matches['name']=='') {
				$modules=get_record_from_id('modules',$matches['module_id']);
			  $item=get_name_from_id($modules['table'],$matches['item_id']);
			  echo "<a href='{$modules['table']}.php?id={$matches['item_id']}' target = '_blank'>".$modules['name'].":".$item['name']."</a><br>".$people['name']."&nbsp;".$matches['date_storage']."</td><td class='results'>";
			}
			else {
				if ($matches['module_id']&&$matches['item_id']) {
					$modules=get_record_from_id('modules',$matches['module_id']);
			    $item=get_name_from_id($modules['table'],$matches['item_id']);
					echo "<a href='{$modules['table']}.php?id={$matches['item_id']}' target = '_blank'>".$matches['name']."</a><br>".$people['name']."&nbsp;".$matches['date_storage']."</td><td class='results'>";
				}
				else {
				  echo $matches['name']."<br>".$people['name']."&nbsp;".$matches['date_storage']."</td><td class='results'>";
				}
			}
			echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>".getPaths($matches['location_id'])."</a></td><td class='results'>";
			if($matches['order_id']==0) {
				echo "</td><td class='results'>";
			}
			else {
				echo "<a href='orders.php?id=".$matches['order_id']."' target = '_blank'><img src='./assets/image/general/info-s.gif' alt='Info' border='0'/></td><td class='results'>";
			}
			$userpid=get_pid_from_username($_COOKIE['wy_user']);
			if ($userpid==$matches['keeper']||$userauth<=2) {
				if($_REQUEST['state']==1) {
					echo "<a onclick=\"editStorage({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit' border='0'/></a></td></tr>";
				}
				else{
					echo "<img src='./assets/image/general/edit-s-grey.gif' alt='' border='0'/></td></tr>";
				}
			}
			else {
				echo "<img src='./assets/image/general/edit-s-grey.gif' alt='' border='0'/></td></tr>";
			}
		}
		echo '</table>
    <table>
      <tr><td>Selected:
      <select name="actionRequest" onchange="javascipt:submitResultsForm()">
      <option value="" selected> -Choose- </option>';
		if($_REQUEST['state']=='1'){
			echo '<option value="OUTOFSTOCK">Out of stock</option>';
			echo '<option value="CHANGEKEEPER">Change keeper</option>';
			echo '<option value="CHANGELOCATION">Change location</option>';
		}
		else{
			echo '<option value="INSTOCK">In stock</option>';
		}
		echo '</select>
      <input type="hidden" name="actionType" value=""></td>
      <tr>
    </table></form>';
	}
?>
<?php
//only the administrator and the keeper himself can change the state.
function OUTOFSTOCK()
{
	$userauth=check_user_authority($_COOKIE['wy_user']);
	$userpid=get_pid_from_username($_COOKIE['wy_user']);
	if($userauth>2)
	{
		$str=" AND keeper='$userpid'";
	}
	else
	{
		$str="";
	}
	$db_conn=db_connect();
	$selecteditem=$_REQUEST['selectedItem'];
	$num_selecteditem=count($selecteditem);
	for($i=0;$i<$num_selecteditem;$i++)
	{
		$query = "UPDATE storages
		SET state=0
		WHERE id=" . $selecteditem[$i] . "$str";
		$result = $db_conn->query($query);
	}
	header('Location: '.$_SESSION['url_1']);
}
function INSTOCK()
{
	$userauth=check_user_authority($_COOKIE['wy_user']);
	$userpid=get_pid_from_username($_COOKIE['wy_user']);
	if($userauth>2)
	{
		$str=" AND keeper='$userpid'";
	}
	else
	{
		$str="";
	}
	$db_conn=db_connect();
	$selecteditem=$_REQUEST['selectedItem'];
	$num_selecteditem=count($selecteditem);
	for($i=0;$i<$num_selecteditem;$i++)
	{
		$query = "UPDATE storages
		SET state=1
		WHERE id=" . $selecteditem[$i] . "$str";
		$result = $db_conn->query($query);
	}
	header('Location: '.$_SESSION['url_1']);
}
?>
<?php
if (isset($_REQUEST['actionType'])&&$_REQUEST['actionType']!='')
{
	if($_REQUEST['actionType']=='OUTOFSTOCK')
	{
		OUTOFSTOCK();
	}
	if($_REQUEST['actionType']=='INSTOCK')
	{
		INSTOCK();
	}
	if($_REQUEST['actionType']=='CHANGEKEEPER')
	{
		$_SESSION['selecteditemCK']=$_REQUEST['selectedItem'];
		header('location:storages_operate.php?type=changekeeper');
	}
	if($_REQUEST['actionType']=='CHANGELOCATION')
	{
		$_SESSION['selecteditemCL']=$_REQUEST['selectedItem'];
		header('location:storages_operate.php?type=changelocation');
	}
}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
<?php
function LocationRecursion($pid,$db_conn) {
	global $location_id_sql;
	//$db_conn=db_connect();
	$queryString = "SELECT * FROM location WHERE (pid=" . $pid ." AND isbox=0)";
	$rs = $db_conn->query($queryString);
	$rs_num = $rs->num_rows;
	if($rs_num) {
	  while ($rsHits=$rs->fetch_array()) {
	  	$location_id_sql .= "or location_id = {$rsHits['id']} ";
			LocationRecursion($rsHits['id'],$db_conn);
	  }
	}
}
?>
