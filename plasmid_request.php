<?php
include('include/includes.php');
?>
<?php
do_html_header('Plasmid request-Quicklab');
do_header();
//do_leftnav();
?>
<?php
js_selectall();
?>
<script>
function   showDetail (id) {
	window.open ("plasmid_request_operate.php?action=detail&id="+id, 'newwindow', 'height=500, width=500, toolbar=no, menubar=no, scrollbars=no, resizable=yes,location=no, status=no')
}

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
function   check (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("plasmid_request_operate.php?action=check_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   edit (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("plasmid_request_operate.php?action=edit_form&id="+id,obj   ,"dialogWidth=600px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   cancel (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("plasmid_request_operate.php?action=cancel_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   request () {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("plasmid_request_operate.php?action=request_form",obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
function   take (id) {
	var   obj   =   new   Object();
	var   a=window.showModalDialog("plasmid_request_operate.php?action=take_form&id="+id,obj   ,"dialogWidth=500px;dialogHeight=500px");
	if   (a=="ok")
    window.location.href=window.location   .href;
}
</script>

<form action="" method="get" name="search" target="_self">
<table width="100%" class='search'>
<tr>
<td><h2 align="center">Plasmid request manager&nbsp;&nbsp;</h2></td>
</tr>
<tr>
<td>
AND state:
<?php
$state=array('- Select all -'=>'%','requested'=>'1','approved'=>'2','taken'=>'3');
echo array_select('state',$state,$_REQUEST['state']);
?>
AND cancel:
<?php
$cancel=array('no'=>'0','yes'=>'1');
echo array_select('cancel',$cancel,$_REQUEST['cancel']);
?>
<input type='Submit' name='Submit' value='Go' />
<input type="reset" value="Clean"/>
</td>
</tr>
<tr>
<td>AND created by:
<?php
$query= "select id,name from people ORDER BY CONVERT(name USING GBK)";
echo query_select_all('created_by', $query,'id','name',$_REQUEST['created_by']);
?>
AND create date:
<?php
$create_date=array('- Select all -'=>'%','this year'=>'1','this month'=>'2');
echo array_select('date_create',$create_date,$_REQUEST['date_create']);
?>
</td>
</tr>
<tr>
<td>
<?php
if ( isset($_GET['PageSize']) )
$PageSize=(int)$_GET['PageSize'];
else $PageSize=10;
echo "Show <input type='text' name='PageSize' size='2' value=".$PageSize."> items per page, "
?>
sort by:
<?php
$sort=array('id'=>'id','trade name'=>'trade_name');
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
if($_REQUEST['plasmid_bank_id']==null){$_REQUEST['plasmid_bank_id']='%';}
if($_REQUEST['created_by']==null){$_REQUEST['created_by']='%';}
if($_REQUEST['date_create']==null){$_REQUEST['date_create']='%';}
if($_REQUEST['state']==null){$_REQUEST['state']='%';}
if($_REQUEST['cancel']==null){$_REQUEST['cancel']=0;}
if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}

$id=$_REQUEST['id'];
$plasmid_id=$_REQUEST['plasmid_id'];
$plasmid_bank_id=$_REQUEST['plasmid_bank_id'];
$created_by=$_REQUEST['created_by'];
$date_create=$_REQUEST['date_create'];
$state=$_REQUEST['state'];

switch ($date_create) {
	case '1':
		$date_create_str="YEAR(`date_create`)=YEAR(CURDATE()) ";
		break;
	case '2':
		$date_create_str="YEAR(`date_create`)=YEAR(CURDATE()) AND MONTH(`date_create`)=MONTH(CURDATE()) ";
		break;
	case '%':
		$date_create_str="`date_create` LIKE '%' ";
		break;
}

$cancel=$_REQUEST['cancel'];
$sort=$_REQUEST['sort'];
$order=$_REQUEST['order'];

$db_conn = db_connect();
$query = "SELECT *,
    	DATE_FORMAT(date_create,'%m/%d/%y')AS date_create,
    	DATE_FORMAT(date_approve ,'%m/%d/%y')AS date_approve ,
    	DATE_FORMAT(date_take ,'%m/%d/%y')AS date_take,
    	DATE_FORMAT(date_cancel,'%m/%d/%y')AS date_cancel
    	FROM plasmid_request WHERE
    	id LIKE '$id'
    	AND `plasmid_id` LIKE '$plasmid_id'
    	AND `plasmid_bank_id` LIKE '$plasmid_bank_id'
    	AND created_by LIKE '$created_by'
    	AND $date_create_str
    	AND state LIKE '$state'
    	AND cancel LIKE '$cancel'
      ORDER BY $sort $order";

$_SESSION['query']=$query;//used for EXCEL export
pagerForm('orders',$query,10);
$userauth=check_user_authority($_COOKIE['wy_user']);

if ($results  && $results->num_rows) {
	echo "<form action='' method='post' name='results' target='_self' >";
	echo "<table width='100%' class='results' width='5%'>";
	echo "<tr><td class='results_header'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header' width='5%'>";
	echo "ID</td><td class='results_header' width='40%'>";
	echo "Name</td><td class='results_header' width='40%'>";
	echo "State&details</td><td class='results_header' width='10%'>";
	echo "Operate</td></tr>";

	while ($matches = $results->fetch_array()) {
		echo "<tr><td class='results'><input type='checkbox' onclick=changechecked(this) name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
		echo $matches['id']."</td><td class='results'>";
		$people=get_record_from_id('people',$matches['created_by']);
		$plasmid=get_name_from_id('plasmids',$matches['plasmid_id']);
		echo "<a href='plasmids.php?id={$matches['plasmid_id']}' target='_blank'>".wordwrap($plasmid['name'],190,"<br>")."</a>, ".$matches['volume']." μL<br>".$people['name']."&nbsp;".$matches['date_create']."</td><td class='results'>";
		//state
		if ($matches['cancel']==1) {
			$people=get_record_from_id('people',$matches['cancelled_by']);
			echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Cancelled<br>".$people['name']." ".$matches['date_cancel']."</a></td><td class='results'>";
		} else {
			switch ($matches['state']) {
				case '1':
					$people=get_record_from_id('people',$matches['created_by']);
					echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Requested<br>".$people['name']." ".$matches['date_create']."</a></td><td class='results'>";
					break;
				case '2':
					$people=get_record_from_id('people',$matches['approved_by']);
					echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Approved<br>".$people['name']." ".$matches['date_approve']."</a></td><td class='results'>";
					break;
				case '3':
					$people=get_record_from_id('people',$matches['taken_by']);
					echo "<a href='#' onclick= 'showDetail({$matches["id"]})'>Taken<br>".$people['name']." ".$matches['date_take']."</a></td><td class='results'>";
					break;
			}
		}
		//operate
		if ($matches['cancel']==1) {
			echo "</td><tr>";
		} else {
			switch ($matches['state']) {
				case '1':
					if (userPermission("2")) {
						echo "<a onclick=\"check({$matches['id']})\" style=\"cursor:pointer\"/>";
					}
					echo "<span style='color:red;'>Check</span></br><a onclick=\"edit({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit'  border='0'/></a>&nbsp;&nbsp;<a onclick=\"cancel({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/cancel-s.gif' alt='Cancel'  border='0'/></td></tr>";
					break;
				case '2':
					echo "<a onclick=\"take({$matches['id']})\" style=\"cursor:pointer\"/><span style='color:red;'>Take</span></a></br><a onclick=\"edit({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit'  border='0'/></a>&nbsp;&nbsp;<a onclick=\"cancel({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/cancel-s.gif' alt='Cancel'  border='0'/></a></td></tr>";
					break;
				case '3':
					echo "<a onclick=\"edit({$matches['id']})\" style=\"cursor:pointer\"/><img src='./assets/image/general/edit-s.gif' alt='Edit'  border='0'/></a></td></tr>";
					break;
			}
		}
	}
	echo "</table></form>";
}
?>

<?php
//do_rightbar();
do_footer();
do_html_footer();
?>
