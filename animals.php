<?php
include_once('include/includes.php');
?>
<?php
//��SESSION��¼һ��ҳ��ĵ�ַ�����ڴӶ���ҳ�淵��??
  $_SESSION['url_1']=$_SERVER['REQUEST_URI'];
?>
<?php selectedRequest('animals');?>
<?php
  do_html_header('Animals-Quicklab');
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
	$fields="CONCAT(name,description)";
	$sort=array('id'=>'id','name'=>'name');
  ?>
	<tr>
      <td align='center' valign='middle'><h2>Animals&nbsp;&nbsp;<?php
      if (userPermission("3")){
      	echo "<a href='animals_operate.php?type=add'><img src='./assets/image/general/add.gif' alt='Add new' border='0'/></a></h2>";
      	//echo "&nbsp;<a href='animals_operate.php?type=import'><img src='./assets/image/general/import.gif' alt='Import from file' border='0'/></a></h2>";
      }
      else {
      	echo '<img src="./assets/image/general/add-grey.gif" alt="Add new" border="0"/></h2>';
      	//echo '&nbsp;<img src="./assets/image/general/import-grey.gif" alt="Import from file" border="0"/></h2>';
 	    }?>
	  </td>
    </tr>
    <tr>
      <td colspan='2' height='21' valign='top'>Serach strain:<?php
      $query="SELECT * FROM ani_strains ORDER BY name";
      $db_conn = db_connect();
  		$result = $db_conn->query($query);
  		echo "<select name='strain'>";
  		echo '<option value="%"';
  		if($_REQUEST['strain'] == '') echo ' selected ';
      echo '>- Select all -</option>';
      for ($i=0; $i < $result->num_rows; $i++) {
        $option = $result->fetch_assoc();
        echo "<option value='{$option['id']}'";
        if ($option['id'] == $_REQUEST['strain']) {
          echo ' selected';
        }
        $query="SELECT * FROM species WHERE id={$option['species']}";
        $result_species = $db_conn->query($query);
        $species = $result_species->fetch_assoc();
        echo ">".$option['name']." (".$species['name'].")</option>";
      }
      echo "</select>";
      //echo query_select_all('strain',$query,'id','name',$_REQUEST['strain']);?>
      AND state:<?php
      $state=array('- Select all -'=>'%','available'=>'1','on experiment'=>'2','dead'=>'3');
      echo array_select('state',$state,$_REQUEST['state']);
      ?>
      <input type='Submit' name='Submit' value='Go' />
      <input type="reset" value="Clean"/>
      </td>
		</tr>
		<?php search_keywords('animals',$fields); ?>
    <tr>
      <td colspan='2' height='21' valign='top'>And project:<?php
      $query= "select * from projects ORDER BY name";
      echo query_select_all('project', $query,'id','name',$_REQUEST['project']);?>
      </td>
    </tr>
    <tr>
      <td>And created by:<?php
      $query= "select * from people ORDER BY name";
      echo query_select_all('created_by', $query,'id','name',$_REQUEST['created_by']);
      ?>AND masked:<?php
      $mask=array(array("0","no"),
      array("1","yes"));
		echo option_select_all('mask',$mask,2,$_REQUEST['mask']);?></td>
    </tr>
  <?php

	//standardSearch('Animals',$fields);
	resultsDisplayControl($sort,10);
	?>
  </table>
</form>
<?php
  $db_conn = db_connect();

  if($_REQUEST['id']==null){$_REQUEST['id']='%';}
  if($_REQUEST['strain']==null){$_REQUEST['strain']='%';}
  if($_REQUEST['state']==null){$_REQUEST['state']='%';}
  if($_REQUEST['fields']==null){$_REQUEST['fields']='name';}
  if($_REQUEST['keywords']==null){$_REQUEST['keywords']='';}
  if($_REQUEST['sort']==null){$_REQUEST['sort']='id';}
  if($_REQUEST['order']==null){$_REQUEST['order']='DESC';}
  if($_REQUEST['project']==null){$_REQUEST['project']='%';}
  if($_REQUEST['created_by']==null){$_REQUEST['created_by']='%';}
  if($_REQUEST['mask']==null){$_REQUEST['mask']='%';}

  $id=$_REQUEST['id'];
  $strain=$_REQUEST['strain'];
  $state=$_REQUEST['state'];
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
  		FROM animals
  		WHERE ($keywords_string)
  		AND id LIKE '$id'
  		AND strain LIKE '$strain'
  		AND state LIKE '$state'
  		AND project LIKE '$project'
  		AND created_by LIKE '$created_by'
  		AND mask LIKE '$mask'
  		$mask_str ORDER BY $sort $order";

  $_SESSION['query']=$query;//used for EXCEL export

  pagerForm('animals',$query);
  
  $module=get_record_from_name('modules','animals');

  if ($results  && $results->num_rows) {
  	echo "<form action='' method='post' name='results' target='_self' >";
  	echo "<table width='100%' class='results'>";
  	echo "<tr><td class='results_header'><input type='checkbox' name='clickall' onclick=selectall(this.checked)></td><td class='results_header'>";
  	echo "Quick ID</td><td class='results_header'>";
  	echo "Name&Details</td><td class='results_header'>";
  	echo "State</td><td class='results_header'>";
  	echo "Operate</td><td class='results_header'>";
  	echo "Storage</td><td class='results_header'>";
  	echo "Order</td></tr>";
  	while ($matches = $results->fetch_array()) {
  		echo "<tr><td class='results'><input type='checkbox'  onclick=changechecked(this)  name='selectedItem[]' value={$matches[id]}></td><td class='results'>";
  		echo get_quick_id($module[id],$matches[id])."</td><td width='200' class='results'>";
  		echo "<a href='animals_operate.php?type=detail&id={$matches[id]}'>".wordwrap($matches[name],190,"<br>")."<br>";
  		if ($matches['health']) {
  			$health=array('General'=>'1','Clean'=>'2','SPF'=>'3','Sterile'=>'4');
  			foreach ($health as $key=>$value) {
  				if ($matches['health'] == $value) {
  					echo $key.",";
  				}
  			}
  		}
  		if ($matches['gender']) {
  			$gender=array('male'=>'1','female'=>'2');
  			foreach ($gender as $key=>$value) {
  				if ($matches['gender'] == $value) {
  					echo $key.",";
  				}
  			}
  		}
  		if ($matches['qty']!='0') {
  			echo $matches['qty'].",";
  		}
  		if ($matches['weight']!='') {
  			echo $matches['weight'].",";
  		}
  		if ($matches['age']!='') {
  			echo $matches['age'];
  		}
  		echo "</a></td><td class='results'>";
  		//$people=get_record_from_id('people',$matches['created_by']);
  		//echo $people['name']." ".$matches['date_create']."</td><td class='results'>";
  		if ($matches['state']) {
  			$state=array('arrival'=>'1','on experiment'=>'2','dead'=>'3');
  			if ($matches['state']=='1') {
  				echo "arrival<br>".$matches['date_arrival'];
  			}
  			if ($matches['state']=='2') {
  				echo "on experiment<br>".$matches['date_experiment'];
  			}
  			if ($matches['state']=='3') {
  				echo "<img src='./assets/image/general/cross.gif' alt='dead' border='0'/>&nbsp;".$matches['date_death'];
  			}
  		}
  		echo "</td><td class='results'>";
  		if (userPermission('2',$matches['created_by'])) {
  			echo "<a href='animals_operate.php?type=edit&id=".$matches['id']."'><img src='./assets/image/general/edit-s.gif' alt='Edit' border='0'/></a>&nbsp;&nbsp;";
//  			echo "<a href='animals_operate.php?type=relation&id=".$matches['id']."'><img src='./assets/image/general/attach-s.gif' alt='Related items' border='0'/></a>&nbsp;&nbsp;";
  			echo "<a href='animals_operate.php?type=delete&id=".$matches['id']."'><img src='./assets/image/general/del-s.gif' alt='Delete'  border='0'/></a></td><td class='results'>";
  		}
  		else
  		{
  			echo '<img src="./assets/image/general/edit-s-grey.gif" alt="Edit" border="0"/>&nbsp;&nbsp;';
//  			echo '<img src="./assets/image/general/attach-s-grey.gif" alt="Related items" border="0"/>&nbsp;&nbsp;';
  			echo '<img src="./assets/image/general/del-s-grey.gif" alt="Delete"  border="0"/></td><td class="results">';
  		}
  		//query the storages of this item where the state is in stock.
  		$db_conn=db_connect();
  		$query = "select id from storages WHERE module_id='{$module['id']}'
                AND item_id = '{$matches['id']}'
                AND state=1";
  		$storage = $db_conn->query($query);
  		$storage_count=$storage->num_rows;
  		if($storage_count>0) {
  			echo '<a href="storages.php?module_id='.$module['id'].'&item_id='.$matches['id'].'">'.$storage_count.'</a>';
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
  		$query = "select * ,
  			DATE_FORMAT(create_date,'%m/%d/%y')AS create_date,
    	DATE_FORMAT(approve_date ,'%m/%d/%y')AS approve_date ,
    	DATE_FORMAT(order_date ,'%m/%d/%y')AS order_date ,
    	DATE_FORMAT(receive_date ,'%m/%d/%y')AS receive_date
    	from orders WHERE module_id='{$module['id']}'
                AND item_id = '{$matches['id']}'
                AND state>0";
  		$order_result = $db_conn->query($query);
  		$order_count=$order_result->num_rows;
  		$order=$order_result->fetch_assoc();
  		if($order_result->num_rows>0) {
  			switch ($order['state']) {
  				case '1':
  					$people=get_record_from_id('people',$order['created_by']);
  					echo "<a href='orders.php?id=".$order['id']."' target='_blank'>Requested<br>".$people['name']." ".$order['create_date']."</a></td></tr>";
  					break;
  				case '2':
  					$people=get_record_from_id('people',$order['created_by']);
  					echo "<a href='orders.php?id=".$order['id']."' target='_blank'>Requested<br>".$people['name']." ".$order['create_date']."</a></td></tr>";
  					break;
  				case '3':
  					/*
  					$people=get_record_from_id('people',$order['ordered_by']);
  					echo "<a href='orders_operate.php?type=detail&id=".$order['id']."' target='_blank'>Ordered<br>".$people['name']." ".$order['order_date']."</a></td><td class='results'>";
  					break;
  					*/
  					$people=get_record_from_id('people',$order['created_by']);
  					echo "<a href='orders.php?id=".$order['id']."' target='_blank'>Requested<br>".$people['name']." ".$order['create_date']."</a></td></tr>";
  					break;
  				case '4':
  					$people=get_record_from_id('people',$order['received_by']);
  					echo "<a href='orders.php?id=".$order['id']."' target='_blank'>Received<br>".$people['name']." ".$order['receive_date']."</a></td></tr>";
  					break;
  			}
  		}
  		else {
  			if (userPermission('3')) {
  				echo "<a onclick=\"requestOrder({$module['id']},{$matches['id']})\" style=\"cursor:pointer\"/><img src=\"./assets/image/general/add-s.gif\" alt=\"Request\" border=\"0\"/></a></td></tr>";
  			}
  			else {
  				echo '<img src="./assets/image/general/add-s-grey.gif" alt="Request" border="0"/></td></tr>';
  			}
  		}
  	}
  	echo '</table>';
  	selectSubmit("animals");
  	echo '</form>';
  }
?>
</td>
<?php
do_rightbar();
do_footer();
do_html_footer();
?>
