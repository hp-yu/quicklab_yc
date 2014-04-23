<?php
include('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��??????
$_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php
do_html_header('Orders-Quicklab');
do_header();
//do_leftnav();
?>
<?php
js_selectall();
?>
<script>
function   showDetail (id) {
	window.open ("orders_operate.php?action=detail&id="+id, 'newwindow', 'height=500, width=500, toolbar=no, menubar=no, scrollbars=no, resizable=yes,location=no, status=no')
}
function submitResultsForm() {
	var selectcount = 0;//check at least select one
	for(i=0;i<document.results.elements.length;i++) {
		if (document.results.elements[i].checked) {
			selectcount = selectcount + 1;
		}
	}
	if(selectcount==0) {
		alert("You have not select any item.")
		document.results.actionRequest.value = ""
		return
	}
	var confirmVal
	if (document.results.actionRequest.value == "approve") {
		<?php
		if (!userPermission('2')) {
		?>
			alert("You do not have the authority to do this.")
			document.results.actionRequest.value = ""
		  return
		<?php
		}
		?>
		confirmVal = confirm("Are you sure to approve the selected order(s)?")
		if (!confirmVal) {
			document.results.actionRequest.value = ""
			return
		}
		else {
			document.results.actionType.value = "approve"
		}
	}
	if (document.results.actionRequest.value == "receive") {
		confirmVal = confirm("You have received the selected item(s)?")
		if (!confirmVal) {
			document.results.actionRequest.value = ""
			return
		}
		else {
			document.results.actionType.value = "receive"
		}
	}
	document.results.submit()
}
function submitResultsForm2(v,r) {
	if (r=='approve') {
	  <?php
	  if (!userPermission('2')) {
		  ?>
		  alert("You do not have the authority to do this.")
		  document.results.actionRequest.value = ""
		  return
		  <?php
	  }
	  ?>
	  var confirmVal
	  confirmVal = confirm("Are you sure to approve this order?")
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
		document.results.actionType.value = "approve"
	  }
	}
	if (r=='receive') {
	  var confirmVal
	  confirmVal = confirm("You have receive this item?")
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
		document.results.actionType.value = "receive"
	  }
	}
	document.results.submit()
}
</script>
<script >
function resetform(f)
{
	f.PageSize.value="10";
	f.keywords.value="";
	for (i=0; f.elements[i]; i++) {
		if(f.elements[i].type=="select-one") {
			f.elements[i].options[0].selected=true;
		}
	}
}
function   checkOrder (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("orders_operate.php?action=check_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   editOrder (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("orders_operate.php?action=edit_form&id="+id,obj   ,"dialogWidth=600px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   cancelOrder (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("orders_operate.php?action=cancel_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   requestOrder () {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("orders_operate.php?action=request_form",obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   receiveOrder (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("orders_operate.php?action=receive_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   addStorage (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("storages_operate.php?action=add_form&order_id="+id,obj,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location.href;
}
function   editStorage (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("storages_operate.php?action=edit_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
</script>
<form action="orders.php" method="get" name="search" target="_self">
  <table width="100%" class='search'>
    <tr>
      <td><h2 align="center">Orders manager&nbsp;&nbsp;
<?php
if (userPermission("2")){
	echo "<a onclick=\"requestOrder()\" style=\"cursor:pointer\"/><img src='./assets/image/general/add.gif' alt='Request' title='Request' border='0'/></a>&nbsp;&nbsp;";
}
else {
	echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" title="Add new" border="0"/>&nbsp;&nbsp;';
}
/*
if (userPermission("2")) {
	echo "<a href='orders_operate.php?type=quickorder'><img src='./assets/image/general/add-r.gif' alt='Quick order' title='Quick order' border='0'/></a></h2>";
}
else {
	echo "<img src='./assets/image/general/add-grey.gif' alt='Quick order' title='Quick order' border='0'/></h2>";
}*/
?>
			</h2></td>
    </tr>
	<?php
	$fields="CONCAT(trade_name,invoice,cat_nbr,lot_nbr)";
	search_keywords('orders',$fields,'trade_name');
	?>
	  <tr>
      <td>AND state:<?php
    $state=array('- Select all -'=>'%','requested'=>'1','approved'=>'2','received'=>'4');
		//$state=array('- Select all -'=>'%','request'=>'1','approve'=>'2','order'=>'3','finish'=>'4');
		echo array_select('state',$state,$_REQUEST['state']);?>

	  AND cancel:<?php
		$cancel=array('no'=>'0','yes'=>'1');
		echo array_select('cancel',$cancel,$_REQUEST['cancel']);?>
		AND mask:<?php
		$mask=array(array("0","no"),array("1","yes"));
		echo option_select_all('mask',$mask,2,$_REQUEST['mask']);?>
	    </td>
    </tr>
    <tr>
      <td>AND seller:
        <?php
        $query= "select id,name from sellers ORDER BY CONVERT(name USING GBK)";
		echo query_select_all('seller', $query,'id','name',$_REQUEST['seller']);?>
	  </td>
    </tr>
    <tr>
      <td>AND module:<?php
        $query= "select id,name from modules ORDER BY name";
		echo query_select_all('module_id', $query,'id','name',$_REQUEST['module_id']);?>
				AND created by:
        <?php
        $query= "select id,name from people ORDER BY CONVERT(name USING GBK)";
		echo query_select_all('created_by', $query,'id','name',$_REQUEST['created_by']);?>
				AND create date:
        <?php
        $create_date=array('- Select all -'=>'%','this year'=>'1','this month'=>'2');
        echo array_select('create_date',$create_date,$_REQUEST['create_date']);
        ?>
			</td>
		</tr>
		<tr>
      <td><?php
      if ( isset($_GET['PageSize']) )
      $PageSize=(int)$_GET['PageSize'];
      else $PageSize=10;
      echo "Show <input type='text' name='PageSize' size='2' value=".$PageSize."> items per page, "?>
	    sort by:<?php
	    $sort=array('id'=>'id','trade name'=>'trade_name');
	    echo array_select('sort',$sort,$_REQUEST['sort']);
	    $order=array(DESC=>DESC,ASC=>ASC);
	    echo array_select('order',$order,$_REQUEST['order']);?></td>
    </tr>
  </table>
 </form>
<?php
	if($_REQUEST['id']==null){$_REQUEST['id']='%';}
	if($_REQUEST['fields']==null){$_REQUEST['fields']='CONCAT(trade_name,invoice,cat_nbr,lot_nbr)';}
	if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
	if($_REQUEST['module_id']==null){$_REQUEST['module_id']='%';}
	if($_REQUEST['item_id']==null){$_REQUEST['item_id']='%';}
	//if($_REQUEST['manufacturer']==null){$_REQUEST['manufacturer']='%';}
	//if($_REQUEST['dealer']==null){$_REQUEST['dealer']='%';}
	if($_REQUEST['seller']==null){$_REQUEST['seller']='%';}
	if($_REQUEST['created_by']==null){$_REQUEST['created_by']='%';}
	//if($_REQUEST['ordered_by']==null){$_REQUEST['ordered_by']='%';}
	//if($_REQUEST['received_by']==null){$_REQUEST['received_by']='%';}
	if($_REQUEST['create_date']==null){$_REQUEST['create_date']='%';}
	if($_REQUEST['state']==null){$_REQUEST['state']='%';}
	//if($_REQUEST['approve']==null){$_REQUEST['approve']='%';}
	if($_REQUEST['cancel']==null){$_REQUEST['cancel']=0;}
	if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
	if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}
	if($_REQUEST['mask']==null){$_REQUEST['mask']='%';}

	$id=$_REQUEST['id'];
	//$fields=$_REQUEST['fields'];
	$module_id=$_REQUEST['module_id'];
	$item_id=$_REQUEST['item_id'];
	//$manufacturer=$_REQUEST['manufacturer'];
	//$dealer=$_REQUEST['dealer'];
	$seller=$_REQUEST['seller'];
	$created_by=$_REQUEST['created_by'];
	//$ordered_by=$_REQUEST['ordered_by'];
	//$received_by=$_REQUEST['received_by'];
	$create_date=$_REQUEST['create_date'];
	$state=$_REQUEST['state'];

	switch ($create_date) {
	case '1':
		$create_date_str="YEAR(create_date)=YEAR(CURDATE()) ";
		break;
	case '2':
		$create_date_str="YEAR(create_date)=YEAR(CURDATE()) AND MONTH(create_date)=MONTH(CURDATE()) ";
		break;
	case '%':
		$create_date_str="create_date LIKE '%' ";
		break;
	}
	$lower_limit=$_REQUEST['lower_limit'];
	$upper_limit=$_REQUEST['upper_limit'];
	if (isset($lower_limit)&&$lower_limit!="") {
		if (isset($upper_limit)&&$upper_limit!="") {
			$price_limit_str="price>=$lower_limit AND price<=$upper_limit ";
		}
		else {
			$price_limit_str="price>=$lower_limit ";
		}
	}
	else {
		$price_limit_str="price LIKE '%' ";
	}

	$cancel=$_REQUEST['cancel'];
	$sort=$_REQUEST['sort'];
	$order=$_REQUEST['order'];
	$mask=$_REQUEST['mask'];

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

	$db_conn = db_connect();
	$query = "SELECT *,
    	DATE_FORMAT(create_date,'%m/%d/%y')AS create_date,
    	DATE_FORMAT(approve_date ,'%m/%d/%y')AS approve_date ,
    	DATE_FORMAT(order_date ,'%m/%d/%y')AS order_date ,
    	DATE_FORMAT(receive_date ,'%m/%d/%y')AS receive_date,
    	DATE_FORMAT(cancel_date,'%m/%d/%y')AS cancel_date
    	FROM orders WHERE ($keywords_string)
    	AND id LIKE '$id'
    	AND module_id LIKE '$module_id'
    	AND item_id LIKE '$item_id'
    	AND (manufacturer LIKE '$seller' OR dealer LIKE '$seller')
    	AND created_by LIKE '$created_by'
    	AND $create_date_str
    	AND state LIKE '$state'
    	AND $price_limit_str
    	AND cancel LIKE '$cancel'
    	AND mask LIKE '$mask'
      $mask_str ORDER BY $sort $order";

	$_SESSION['query']=$query;//used for EXCEL export
	pagerForm('orders',$query,10);
	$userauth=check_user_authority($_COOKIE['wy_user']);

	if ($results  && $results->num_rows)
	{
		echo "<form action='' method='post' name='results' target='_self' >";
		echo "<table width='100%' class='results'>";
		echo "<tr><td class='results_header'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header'>";
		echo "Name</td><td class='results_header'>";
		echo "Seller</td><td class='results_header'>";
		//echo "Pack</td><td class='results_header'>";
		//echo "Qty</td><td class='results_header'>";
		echo "Price</td><td class='results_header'>";
		echo "State&details</td><td class='results_header'>";
		echo "Operate</td><td class='results_header'>";
		//echo "Pay</td><td class='results_header'>";
		//echo "Receive</td><td class='results_header'>";
		echo "Store</td></tr>";

		while ($matches = $results->fetch_array()) {
			echo "<tr><td class='results'><input type='checkbox' onclick=changechecked(this) name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
			$people=get_record_from_id('people',$matches['created_by']);
			if ($matches['module_id']&&$matches['item_id']) {
				$modules=get_record_from_id('modules',$matches['module_id']);
			  $item=get_name_from_id($modules['table'],$matches['item_id']);
			  $name=$modules['name'].":".$item['name'];
			  echo "<a href='{$modules['table']}.php?id={$matches['item_id']}' target='_blank'>".wordwrap($name,190,"<br>")."</a><br>".$people['name']."&nbsp;".$matches['create_date']."</td><td class='results'>";
			}
			else {
				echo wordwrap($matches['trade_name'],190,"<br>")."<br>".$people['name']."&nbsp;".$matches['create_date']."</td><td class='results'>";
			}
			if ($matches['manufacturer']) {
				$manufacturer=get_name_from_id('sellers',$matches['manufacturer']);
				echo "<a href='sellers.php?id=".$matches['manufacturer']."'  target = '_blank'>".$manufacturer['name']."</a><br>";
			}
			if ($matches['dealer']) {
			  $dealer=get_name_from_id('sellers',$matches['dealer']);
			  echo "<a href='sellers.php?id=".$matches['dealer']."' target = '_blank' >".$dealer['name']."</a></td><td class='results'>";
			}
			else {
				echo "</td><td class='results'>";
			}
			echo $matches['pack']."/".$matches['qty']."<br>".$matches['price']."</td><td class='results'>";
			if ($matches['cancel']==1) {
				$people=get_record_from_id('people',$matches['cancelled_by']);
				echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Cancelled<br>".$people['name']." ".$matches['cancel_date']."</a></td><td class='results'>";
			}
			else {
				switch ($matches['state']) {
					case '1':
						$people=get_record_from_id('people',$matches['created_by']);
						echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Requested<br>".$people['name']." ".$matches['create_date']."</a></td><td class='results'>";
						break;
					case '2':
						$people=get_record_from_id('people',$matches['approved_by']);
						echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Approved<br>".$people['name']." ".$matches['approve_date']."</a></td><td class='results'>";
						break;
					case '3':
						/*
						$people=get_record_from_id('people',$matches['ordered_by']);
						echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Ordered<br>".$people['name']." ".$matches['order_date']."</a></td><td class='results'>";
						break;
						*/
						$people=get_record_from_id('people',$matches['approved_by']);
						echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Approved<br>".$people['name']." ".$matches['approve_date']."</a></td><td class='results'>";
						break;
					case '4':
						if($matches['invoice']!=''&&$matches['account_id']!=''&&$matches['account_id']!=0) {
							//label the quickorder
							if ($matches['approved_by']) {
								echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Finished</a></td><td class='results'>";
							}
							else {
								echo "<a href='#' onclick= 'showDetail({$matches["id"]})' style='color:red'>Finished</a></td><td class='results'>";
							}
						}
						else {
							$people=get_record_from_id('people',$matches['received_by']);
							if ($matches['approved_by']) {
								echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Received<br>".$people['name']." ".$matches['receive_date']."</a></td><td class='results'>";
							}
							else {
								echo "<a href='#' onclick= 'showDetail({$matches["id"]})' style='color:red'>Received<br>".$people['name']." ".$matches['receive_date']."</a></td><td class='results'>";
							}
						}
						break;
				}
			}
			if ($matches['cancel']==1) {
				echo "</td><td class='results'>";
			}
			else {
				switch ($matches['state']) {
					case '1':
						$query="SELECT * FROM `orders_approve` WHERE `key`='is_approve'";
						$result_approve=$db_conn->query($query);
						$match_approve=$result_approve->fetch_array() ;
						$is_approve=$match_approve['value'];
						$query="SELECT * FROM `orders_approve` WHERE `key`='lower_limit'";
						$result_approve=$db_conn->query($query);
						$match_approve=$result_approve->fetch_array() ;
						$lower_limit=$match_approve['value'];
						if($is_approve==1&&$matches['price']>=$lower_limit) {
							if (userPermission("2")) {
								echo "<a onclick=\"checkOrder({$matches['id']})\" style=\"cursor:pointer\"/>";
							}
							echo "<span style='color:red;'>Check</span></br><a onclick=\"editOrder({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit' title='Edit'  border='0'/></a>&nbsp;&nbsp;<a onclick=\"cancelOrder({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/cancel-s.gif' alt='Cancel' title='Cancel'  border='0'/></td><td class='results'>";
						}
						else {
							echo "<a onclick=\"receiveOrder({$matches['id']})\" style=\"cursor:pointer\"/><span style='color:red;'>Receive</span></a></br><a onclick=\"editOrder({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit' title='Edit' border='0'/></a>&nbsp;&nbsp;<a onclick=\"cancelOrder({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/cancel-s.gif' alt='Cancel' title='Cancel' border='0'/></a></td><td class='results'>";
						break;
							/*
							echo "<a href='orders_operate.php?type=order&id={$matches[id]}'><span style='color:red;'>Order</span></br><a href='orders_operate.php?type=edit&id={$matches[id]}'><img src='./assets/image/general/edit-s.gif' alt='Edit' title='Edit' border='0'/></a>&nbsp;&nbsp;<a href='orders_operate.php?type=cancel&id={$matches[id]}'><img src='./assets/image/general/cancel-s.gif' alt='Cancel' title='Cancel' border='0'/></td><td class='results'>";
							*/
						}
						break;
					case '2':
					  echo "<a onclick=\"receiveOrder({$matches['id']})\" style=\"cursor:pointer\"/><span style='color:red;'>Receive</span></a></br><a onclick=\"editOrder({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit' title='Edit' border='0'/></a>&nbsp;&nbsp;<a onclick=\"cancelOrder({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/cancel-s.gif' alt='Cancel'  title='Cancel' border='0'/></a></td><td class='results'>";
						break;
						/*
						echo "<a href='orders_operate.php?type=order&id={$matches[id]}'><span style='color:red;'>Order</span></br><a href='orders_operate.php?type=edit&id={$matches[id]}'><img src='./assets/image/general/edit-s.gif' alt='Edit' title='Edit' border='0'/></a>&nbsp;&nbsp;<a href='orders_operate.php?type=cancel&id={$matches[id]}'><img src='./assets/image/general/cancel-s.gif' alt='Cancel' title='Cancel' border='0'/></td><td class='results'>";
						break;
						*/
					case '3':
						echo "<a onclick=\"receiveOrder({$matches['id']})\" style=\"cursor:pointer\"/><span style='color:red;'>Receive</span></a></br><a onclick=\"editOrder({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit' title='Edit' border='0'/></a>&nbsp;&nbsp;<a onclick=\"cancelOrder({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/cancel-s.gif' alt='Cancel' title='Cancel' border='0'/></a></td><td class='results'>";
						break;
					case '4':
						echo "<a onclick=\"editOrder({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit' title='Edit' border='0'/></a></td><td class='results'>";
						break;
				}
			}
			/*
			if($matches['invoice']!='') {
				echo "<img src='./assets/image/general/ok-s.gif' alt='' title='' border='0'/></td><td class='results'>";
			}
			else {
				echo "</td><td class='results'>";
			}
			if($matches['account_id']!=''&&$matches['account_id']!=0) {
				echo "<img src='./assets/image/general/ok-s.gif' alt='' title='' border='0'/></td><td class='results'>";
			}
			else {
				echo "</td><td class='results'>";
			}*/
			//storage
			if($matches['state']>1&&$matches['cancel']==0) {
				$query="SELECT * FROM storages WHERE
	  			order_id=".$matches['id'];
				$result=$db_conn->query($query);
				$storage_count=$result->num_rows;
				if($storage_count>0) {
					echo '<a href="storages.php?order_id='.$matches['id'].'" target="_blank">'.$storage_count.'</a>';
				} else {
					echo $storage_count;
				}
				echo "&nbsp;&nbsp;";
				if (userPermission('3')) {
					echo "<a onclick=\"addStorage({$matches['id']})\" style=\"cursor:pointer\"/><img
	    src=\"./assets/image/general/add-s.gif\" alt=\"Store\" title=\"Store\" border=\"0\"/></a>";
				} else {
					echo '<img src="./assets/image/general/add-s-grey.gif" alt="Store" title="Store" border="0"/>';
				}
			}
			echo "</td></tr>";
		}
		echo '</table>';
		$query = "SELECT SUM(price)
    	FROM orders WHERE ($keywords_string)
    	AND id LIKE '$id'
    	AND module_id LIKE '$module_id'
    	AND item_id LIKE '$item_id'
    	AND (manufacturer LIKE '$seller' OR dealer LIKE '$seller')
    	AND created_by LIKE '$created_by'
    	AND $create_date_str
    	AND state LIKE '$state'
    	AND $price_limit_str
    	AND cancel LIKE '$cancel'
    	AND mask LIKE '$mask'
      $mask_str ORDER BY $sort $order";

		$result=$db_conn->query($query);
		$match=$result->fetch_assoc();
		echo '<table width="100%%"><tr>
		<td align="left">Selected:
      <select name="actionRequest" onchange="javascipt:submitResultsForm()">
      <option value="" selected> -Choose- </option>';
			echo '<option value="approve">Approve</option>';
			echo '<option value="receive">Receive</option>';
			echo '</select>
      <input type="hidden" name="actionType" value=""></td>';
		echo "<td align='right'>Total price: <span style='color:red'>".number_format($match['SUM(price)'])."</span></td></tr></table>";
		echo '</form>';
	}
?>
<?php
//only the administrator and the keeper himself can change the state.
function approve() {
	$db_conn=db_connect();
	$query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	$approved_by=$match['people_id'];
	$approve_date=date('Y-m-d');

	$selecteditem=$_REQUEST['selectedItem'];
	$num_selecteditem=count($selecteditem);
	for($i=0;$i<$num_selecteditem;$i++) {
		$query="SELECT * FROM orders WHERE id='{$selecteditem[$i]}'";
		$result = $db_conn->query($query);
		$order=$result->fetch_assoc();
		if ($order['state']=='1') {
			$query = "UPDATE orders
		  SET approved_by='$approved_by',
			approve_date='$approve_date',
			state='2'
		  WHERE id='{$selecteditem[$i]}'" ;
		  $result = $db_conn->query($query);
		  //Send reminder mail to the people who requested this order after the order is approved.
		  $query="SELECT * FROM `orders_mails` WHERE `key`='is_approve_mail'";
			$result=$db_conn->query($query);
			$match_orders_mails=$result->fetch_assoc();
			if ($match_orders_mails['value']==1) {
				$created_by=$order['created_by'];
				$people=get_record_from_id('people',$created_by);
				$to=trim($people['email']);
				$subject = 'Your order request approved';
				$people=get_record_from_id('people',$approved_by);
				$message = "
  <table>
    <tr>
      <td>".$people['name']." approved your order ".$order['trade_name']." on ".$approve_date."</td>
    </tr>
    <tr>
      <td><a href='http://".IP_ADDRESS."/quicklab/orders.php?id={$order['id']}' target='_blank'>Go to quicklab</a> (If it can not link to the quicklab, copy the address below.)</td>
    </tr>
    <tr>
      <td>http://".IP_ADDRESS."/quicklab/orders.php?id={$order['id']}</td>
    </tr>
  </table>
";
				$mail= new Mailer();
				$mail->basicSetting();
				$mail->Subject =$subject;
				$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
				$mail->MsgHTML($css.$message);
				$mail->AddAddress($to);
				@$mail->Send();
			}
		}
	}
	header('Location: '.$_SESSION['url_1']);
}
function receive() {
	$db_conn=db_connect();
	$query = "SELECT * FROM users WHERE username = '{$_COOKIE['wy_user']}'";
	$result = $db_conn->query($query);
	$match=$result->fetch_assoc();
	$received_by=$match['people_id'];
	$receive_date=date('Y-m-d');

	$selecteditem=$_REQUEST['selectedItem'];
	$num_selecteditem=count($selecteditem);
	for($i=0;$i<$num_selecteditem;$i++) {
		$query="SELECT * FROM orders WHERE id='{$selecteditem[$i]}'";
		$result = $db_conn->query($query);
		$order=$result->fetch_assoc();
		if ($order['state']=='2'||$order['state']=='3') {
			$query = "UPDATE orders
		  SET received_by='$received_by',
			receive_date='$receive_date',
			state='4'
		  WHERE id='{$selecteditem[$i]}'" ;
			$result = $db_conn->query($query);

			if ($order['module_id']=='10') {
				$date_arrival=date('Y-m-d');
				$query="UPDATE animals SET
			  state=1,
			  date_arrival='$date_arrival'
			  WHERE id='{$order['item_id']}'";
				$result = $db_conn->query($query);
			}
			//Send mail to the people who requested this order after the product is received by other people.
			$created_by=$order['created_by'];
			$query="SELECT * FROM `orders_mails` WHERE `key`='is_receive_mail'";
			$result=$db_conn->query($query);
			$match_orders_mails=$result->fetch_assoc();
			if ($match_orders_mails['value']==1&&$received_by!=$created_by) {
				$people=get_record_from_id('people',$created_by);
				$to=trim($people['email']);
				$subject = 'Your order received';
				$people=get_record_from_id('people',$received_by);
				$message = "
  <table>
    <tr>
      <td>".$people['name']." received your order ".$order['trade_name']." on ".$receive_date."</td>
    </tr>
    <tr>
      <td><a href='http://".IP_ADDRESS."/quicklab/orders.php?id={$order['id']}' target='_blank'>Go to quicklab</a> (If it can not link to the quicklab, copy the address below.)</td>
    </tr>
    <tr>
      <td>http://".IP_ADDRESS."/quicklab/orders.php?id={$order['id']}</td>
    </tr>
  </table>
";
				$mail= new Mailer();
				$mail->basicSetting();
				$mail->Subject =$subject;
				$css="<style>body{font-family:Verdana, Arial, sans-serif;font-size:8pt;}td{font-family:Verdana, Arial, sans-serif;font-size:8pt;}</style>";
				$mail->MsgHTML($css.$message);
				$mail->AddAddress($to);
				@$mail->Send();
			}
		}
	}
	header('Location: '.$_SESSION['url_1']);
}
?>
<?php
if (isset($_REQUEST['actionType'])&&$_REQUEST['actionType']!='') {
	if($_REQUEST['actionType']=='approve') {
		approve();
	}
	if($_REQUEST['actionType']=='receive') {
		receive();
	}
}
?>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
