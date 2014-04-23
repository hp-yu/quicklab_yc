<?php
include('include/includes.php');
include('include/bioinfo/sm.php');
?>
<?php
 if ($_REQUEST['type']=='export_excel') {
 	if(!userPermission(3)) {
 	  header('location:'.$_SESSION['url_1']);
 	}
 	$query=$_SESSION['query'];
 	export_excel('plasmids',$query);
 	exit;
 }
  if ($_REQUEST['type']=='import_template') {
 	import_template();
 	exit;
 }
?>
<?php
  do_html_header_begin('Plasmids operate-Quicklab');
?>
<script src="include/jquery/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript" src="include/bioinfo/sm_common.js"></script>
<script>
function cal_seq_len(){
	e1=document.getElementById('raw_seq');
	e2=document.getElementById('seq_len');
	len=removeuseless(e1.value).length;
	e2.innerHTML=len+' bp';
}
</script>
<script>
$(document).ready(function() {
	cal_seq_len();
});
</script>
<script type="text/javascript">
function moveOptionToTextarea(e1, e2){
	for(var i=0;i<e1.options.length;i++){
		if(e1.options[i].selected) {
			var e = e1.options[i]
			if(e2.value=='') {
				e2.value=e.text;
			}
			else {
				e2.value+=",\r\n"+e.text;
			}
		}
	}
}
function showinsert() {
	var e1 = document.getElementById("insert");
	var e2 = document.getElementById("isinsert");
	if(e2.checked == true) {
		e1.style.display = "";
	}
	else {
		e1.style.display = "none";
	}
}
</script>
<?php
  do_html_header_end();
  do_header();
  //do_leftnav();
  process_request();
  do_rightbar();
  do_footer();
  do_html_footer();
?>

<?php
function add_form()
{
	if(!userPermission('3'))
	{
		alert();
	}
  ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#add_form").validate({
		rules: {
			name: "required"
		},
		messages: {
			name: {required: 'required'}
		}});
});
</script>
    <form name='add_form' id="add_form" method='post' action='' enctype="multipart/form-data">
    <table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Plasmids</h2></div></td></tr>
	<tr><td colspan='2'><h3>Add new plasmid:</h3></td>
      </tr>
      <tr>
        <td width='20%' id="aaa">Name:</td>
        <td width='80%'><input type='text' name='name' id="name" size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['name']))?>"/>*</td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
        $query= "select * from projects where state=1";
		echo query_select_choose('project', $query,'id','name',$_POST['project']);?>
        </td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols="50" rows="3"><?php echo stripslashes($_POST['description']) ?></textarea></td>
      </tr>
      <tr>
        <td>Vector backbone:</td>
        <td><input type='text' name='backbone' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['backbone']))?>"/></td>
      </tr>
      <tr>
        <td>Type of vector:</td>
        <td><input type='text' name='vector_type' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['vector_type']))?>"/></td>
      </tr>
      <tr><td>Sequence:</br><span id="seq_len" style="background-color:yellow"></span></td>
      <td><textarea id="raw_seq"  name='raw_seq' class="sequence" cols="80" rows="6" onchange="tidyup('raw_seq');cal_seq_len()"><?php echo stripslashes($_POST['raw_seq'])?></textarea></td>
      </tr>      
      <tr>
        <td>Map:</td>
        <td><input type='file' name='map' value="<?php echo stripslashes(htmlspecialchars($_POST['file']))?>"/></td>
      </tr>
      <tr>
        <td>With insert/gene:</td>
        <td><input type="checkbox" name="isinsert" value="1" ></td>
      </tr>
      <tr><td colspan="2">
      <table id="insert" class="standard" width="100%">
      <tr>
        <td width="20%">Insert name:</td>
        <td width="80%"><input type='text' name='insert_name' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['insert_name']))?>"/></td>
      </tr>
      <tr>
        <td>Species:</td>
        <td><input type='text' name='species' value="<?php echo stripslashes(htmlspecialchars($_POST['species']))?>"/></td>
      </tr>
      <tr>
        <td>Sequence identifier:<br/>(GenBank number,<br>GI or VERSION)</td>
        <td><input type='text' name='sequence_identifier' value="<?php echo stripslashes(htmlspecialchars($_POST['sequence_identifier']))?>"/> (e.g. AAU34114.1, 52222571)</td>
      </tr>
      <tr>
        <td>Insert size:</td>
        <td><input type='text' name='insert_size' value="<?php echo stripslashes(htmlspecialchars($_POST['insert_size']))?>"/></td>
      </tr>
      <tr>
        <td>Modification:</td>
        <td><input type='text' name='gene_modification' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['gene_modification']))?>"/></td>
      </tr>
      <tr>
        <td>Cloning sites(5'/3'):<br/> (e.g. EcoRI/HindIII)</td>
        <td><input type='text' name='cloning_sites' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['cloning_sites']))?>"/></td>
      </tr>
      <tr>
        <td >Fusion proteins<br/> or tags:</td>
        <td><input type='text' name='tag' size="40" value="<?php echo stripslashes(htmlspecialchars($_POST['tag']))?>"/></td>
      </tr>
      </table>
      </td></tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols="50" rows="3"><?php echo stripslashes($_POST['note']) ?></textarea></td>
      </tr>
      <tr>
        <td>Mask:</td>
        <td><?php
        $mask=array(array("0","no"),
        array("1","yes"));
		echo option_select('mask',$mask,2,$_POST['mask']);?>
		</td>
  </tr>
	<?php
	//coustom fields
	$db_conn=db_connect();
	$query="SELECT * FROM custom_fields WHERE module_name='plasmids'";
	$rs=$db_conn->query($query);
	if ($rs->num_rows>0) {
		?>
		<tr>
			<td colspan="2"><i>Custom field(s):</i></td>
		</tr>
		<?php
	}
	while ($match=$rs->fetch_assoc() ) {
		echo "<tr><td>";
		echo $match['field_name'].":</td><td>";
		if ($match['field_typr']=="TEXT") {
			echo "<textarea name='".$match['field_id']."' cols='50' rows='3'>".stripslashes($_POST[$match['field_id']])."</textarea>";
		}
		else {
			echo "<input type='text' name='".$match['field_id']."' size='40' value='".stripslashes(htmlspecialchars($_POST[$match['field_id']]))."'/>";
		}
		echo "</td></tr>";
	}
	?>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />&nbsp;&nbsp;<a href='<?php
        echo $_SESSION['url_1'];?>'><img src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a>
    	</td>
      </tr>
      <?php hidden_inputs('created_by','date_create','add');?>
      </table></form>
      <?php
}

function edit_form()
{
	$plasmid = get_record_from_id('plasmids',$_REQUEST['id']);
	if(!userPermission('2',$plasmid['created_by']))
	{
		alert();
	}
  ?>
 <script type="text/javascript">
$(document).ready(function() {
	$("#edit_form").validate({
		rules: {
			name: "required"
		},
		messages: {
			name: {required: 'required'}
		}});
});
</script>
    <form name='edit_form' id="edit_form" method='post' action='' enctype="multipart/form-data">
    <table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Plasmids</h2></div></td></tr>
  	  <tr><td colspan='2'><h3>Edit:</h3></td>
      </tr>
      <tr>
        <td width='10%'>Name:</td>
        <td width='90%'><input type='text' name='name' id="name" size="40" value="<?php
  echo stripslashes(htmlspecialchars($plasmid['name']));?>">*</td>
      </tr>
      <tr>
        <td>Project:</td><td><?php
        $query= "select * from projects";
		echo query_select_choose('project', $query,'id','name',$plasmid['project']);?>
        </td></tr>
      <tr>
        <td>Description:</td>
        <td><textarea name='description' cols='50' rows='3'><?php
     echo $plasmid['description'];?></textarea></td>
      </tr>
	  <tr>
        <td>Vector backbone:</td>
        <td><input type='text' name='vector_name' size="40" value="<?php
     echo $plasmid['vector_name'];?>"></td>
      </tr>
      <tr>
        <td>Vector type:</td>
        <td><input type='text' name='vector_type' size="40" value="<?php
     echo $plasmid['vector_type'];?>"></td>
      </tr>
      <tr>
        <td>Sequence:</br><span id="seq_len" style="background-color:yellow"></span></td>
        <td colspan="2"><textarea id="raw_seq" name='raw_seq' class="sequence" cols="80" rows="6" onchange="tidyup('raw_seq');cal_seq_len()"><?php
        $db_conn = db_connect();
        $query="SELECT sequence FROM plasmid_sequences WHERE plasmid_id='{$_REQUEST['id']}'";
        $result=$db_conn->query($query);
        $plasmid_sequences=$result->fetch_assoc();
        if ($result->num_rows>0) {echo sm_tidyup($plasmid_sequences['sequence']);}
        ?></textarea></td>
      </tr>
      <tr>
        <td <?php if($plasmid['map']!='') echo 'colspan="2"';?>>Map:(JPEG format)
        <?php if ($plasmid['map']!='')
        {echo '<input type="checkbox" value="1" name="delete_image">Delete this map?';
        echo '</td></tr><tr><td colspan="2">';
        echo '&nbsp;&nbsp;<img src="resize_image.php?image=';
        echo urlencode($plasmid['map']);
        echo '&max_width=500&max_height=500" border="1"/>';
        echo '</td></tr><tr><td >Repalce!';}
    	?>
        </td>
        <td><input type='file' name='map'/></td>
      </tr>
      <tr>
        <td>With insert/gene:</td>
        <td><input type='checkbox' name='isinsert' value="1" " <?php
        if($plasmid['isinsert']=='1') {
        	echo "checked";
        }?>></td>
      </tr>
      <tr><td colspan="2">
      <table id="insert" width="100%" class="standard">
      <tr>
        <td width="20%">Insert name:</td>
        <td width="80%"><input type='text' name='insert_name' size="40" value='<?php
     	echo $plasmid['insert_name'];?>'></td>
      </tr>
      <tr>
        <td>Species:</td>
        <td><input type='text' name='species' size="40" value='<?php
     	echo $plasmid['species'];?>'></td>
      </tr>
      <tr>
        <td>Sequence identifier: <br/>(GenBank number,<br>GI or VERSION)</td>
        <td><input type='text' name='sequence_identifier' value='<?php echo $plasmid['sequence_identifier'];?>'></td>
      </tr>
      <tr>
        <td>Insert size:</td>
        <td><input type='text' name='insert_size' value='<?php echo $plasmid['insert_size'];?>'></td>
      </tr>
      <tr>
        <td>Modification:</td>
        <td><input type='text' name='modification' size="40" value='<?php
     	echo $plasmid['modification'];?>'></td>
      </tr>
      <tr>
        <td>Cloning sites:</td>
        <td><input type='text' name='cloning_sites' size="40" value='<?php
     	echo $plasmid['cloning_sites'];?>'></td>
      </tr>
      <tr>
        <td>Fusion proteins <br/>or tags:</td>
        <td><input type='text' name='tags' size="40" value='<?php
     	echo $plasmid['tags'];?>'></td>
      </tr>
      </table>
      </td></tr>
      <tr>
        <td>Note:</td>
        <td><textarea name='note' cols='50' rows='3'><?php
     	echo $plasmid['note'];?></textarea></td>
      </tr>
      <tr>
				<td>Mask:</td>
				<td><?php
				$mask=array(array(1,'yes'),array(0,'no'));
    		echo option_select('mask',$mask,2,$plasmid['mask']);?>
    		</td>
 			</tr>
 	<?php
		$db_conn=db_connect();
		$query="SELECT * FROM custom_fields WHERE module_name='plasmids'";
		$rs=$db_conn->query($query);
		if ($rs->num_rows>0) {
			?>
 			<tr>
 				<td><i>Custom field(s):</i></td>
 			</tr>
			<?php
		}
		while($match=$rs->fetch_assoc()) {
			echo "<tr><td>";
			echo $match['field_name'].":</td><td>";
			if ($match['field_typr']=="TEXT") {
				echo "<textarea name='".$match['field_id']."' cols='50' rows='3'>".$plasmid[$match['field_id']]."</textarea>";
			}
			else {
				echo "<input type='text' name='".$match['field_id']."' size='40' value='".stripslashes(htmlspecialchars($plasmid[$match['field_id']]))."'/>";
			}
			echo "</td></tr>";
		}
	?>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' /></td>
      </tr>
      <?php hidden_inputs('updated_by','date_update','edit');?>
    </table></form>
  <?php
}

function edit_relation_form()
{
	$plasmid = get_record_from_id('plasmids',$_REQUEST['id']);
	if(!userPermission('2',$plasmid['created_by']))
	{
		alert();
	}
?>
  <form name="relation" method="post" action="" target="_self">
  <table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Plasmids</h2></div></td></tr>
    <tr><td colspan="2"><h3>Relate plasmid: <?php echo $plasmid['name']; ?> to these items below:</h3></td></tr>
    <tr><td width="200">
  	<select name="clipboardtarget[]" style="width:190px;font-size:7pt;" size="5" multiple
  	ondblclick="removeOption(document.getElementById('clipboardtarget[]'))">
  	<?php
  	$db_conn = db_connect();
  	$module=get_record_from_name('modules','plasmids');
  	$query="SELECT * FROM items_relation
	  WHERE item_from='".$module['id']."_".$_REQUEST['id']."'";
  	$results=$db_conn->query($query);
  	while ($matches=$results->fetch_assoc())
  	{
  		$key_array=split("_",$matches['item_to']);
  		$module=get_name_from_id(modules,$key_array[0]);
  		$item=get_name_from_id($module['name'],$key_array[1]);
  		echo "<option value=".$matches['item_to'].">".$module['name'].": ".$item['name']."</option>";
  	}
	?>
  	</select></td>
  	<td align="left" valign="top">Doubleclick in the clipboard to transfer, <br>and doubleclick here to delete.</td></tr>
  	<tr><td ><input type='submit' onmouseover="allselected(document.getElementById('clipboardtarget[]'))" name='Submit' value='Submit' />
  	</td></tr>
  	<?php hidden_inputs('','','relation');?>
 </table> </form>
<?php
}

function detail()	{
	if(!userPermission('4')) {
		alert();
	}
	$plasmid = get_record_from_id('plasmids',$_REQUEST['id']);
	?>
<form name='detail_form' id="detail_form" method='post' action=''>
	<table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Plasmids</h2></div></td></tr>
  <tr><td colspan='2'><h3>Details:&nbsp;
    <a href="plasmids_operate.php?type=edit&id=<?php echo $plasmid['id']?>"/>
    <img src='./assets/image/general/edit.gif' alt='edit' title='edit' border='0'/></a></h3>
    </td>
  </tr>
  <tr>
    <td width='20%'>Name:</td>
    <td width='80%'><?php echo $plasmid['name'];?></td>
  </tr>
  <tr>
    <td>Project:</td><td><?php
    $project= get_record_from_id('projects',$plasmid['project']);
	  echo $project['name'];?></td>
	</tr>
    <tr>
      <td>Description:</td>
      <td><?php echo wordwrap($plasmid['description'],70,"<br/>");?></td>
    </tr>
    <tr>
      <td>Vector backbone:</td>
      <td><?php echo $plasmid['vector_name'];?></td>
    </tr>
    <tr>
      <td>Vector type:</td>
      <td><?php echo $plasmid['vector_type'];?></td>
    </tr>
    <?php
    if ($plasmid['isinsert']=='1') {
	?>
    <tr>
      <td>Insert name:</td>
      <td><?php echo $plasmid['insert_name'];?></td>
    </tr>
    <tr>
      <td>Species:</td>
      <td><?php echo $plasmid['species'];?></td>
    </tr>
    <tr>
      <td>Sequence identifier:</td>
      <td><?php echo $plasmid['sequence_identifier'];?></td>
    </tr>
    <tr>
      <td>Insert size:</td>
      <td><?php echo $plasmid['insert_size'];?></td>
    </tr>
    <tr>
      <td>Modification:</td>
      <td><?php echo $plasmid['modification'];?></td>
    </tr>
    <tr>
      <td>Cloning sites:</td>
      <td><?php echo $plasmid['cloning_sites'];?></td>
    </tr>
    <tr>
      <td>Fusion proteins<br/> or tags:</td>
      <td><?php echo $plasmid['tags'];?></td>
    </tr>
    <?php
    }
    ?>
    <tr>
      <td>Note:</td>
      <td><?php echo wordwrap($plasmid['note'],70,"<br/>");?></td>
    </tr>
    <tr>
      <td>Created by:</td><td><?php
      $people = get_name_from_id('people',$plasmid['created_by']);
	  echo $people['name'].'  '.$plasmid['date_create'];?></td></tr>
    </tr>
    <tr>
      <td>Updated by:</td><td><?php
      $people = get_name_from_id('people',$plasmid['updated_by']);
      echo $people['name'].'  '.$plasmid['date_update'];?>
      </td>
    </tr>
<?php
	$db_conn=db_connect();
	$query="SELECT * FROM custom_fields WHERE module_name='plasmids'";
	$rs=$db_conn->query($query);
	if ($rs->num_rows>0) {
		?>
		<tr>
			<td colspan="2"><i>Custom field(s):</i></td>
		</tr>
		<?php
	}
	while ($match=$rs->fetch_assoc()) {
		echo "<tr><td>";
		echo $match['field_name']."</td><td>";
		if ($match['field_type']=="TEXT") {
			echo wordwrap($plasmid[$match['field_id']],70,"<br/>")."</td></tr>";
		}
		else {
			echo $plasmid[$match['field_id']]."</td></tr>";
		}
	}
	$module=get_record_from_name('modules','plasmids');
	$query="SELECT * FROM items_relation
	  WHERE item_from='".$module['id']."_".$_REQUEST['id']."'";
	$results=$db_conn->query($query);
	$num_relateditems=$results->num_rows;
	if($num_relateditems!=0)
	{
		echo "<tr><td valign='top' rowspan=".$num_relateditems.">Related items:</td>";
		while ($matches=$results->fetch_assoc())
		{
			$key_array=split("_",$matches['item_to']);
			$module=get_name_from_id(modules,$key_array[0]);
			$item=get_name_from_id($module['name'],$key_array[1]);
			echo "<td>".$module['name'].": <a href='".$module['name']."_operate.php?type=detail&id=".$key_array[1]."' target='_blank'/>".
			$item['name']."</a></td></tr>";
		}
	}
	if ($plasmid['map']!='') {
		echo "<tr><td colspan='2'>Map uploaded:</td></tr>";
		echo "<tr><td colspan='2'>";
		echo "&nbsp;&nbsp;<img src='resize_image.php?image=";
		echo urlencode($plasmid['map']);
		echo "&max_width=500&max_height=500' border='1'/>";
		echo "</td></tr>";
	}
	//if the plasmid map drawn by quicklab existed
	$query="SELECT * FROM `plasmid_map` WHERE `plasmid_id`='{$_REQUEST['id']}'";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	if ($rs->num_rows>0) {
		$plasmid_map_name = $match['name'];
		$plasmid_map_size = $match['size'];
		$plasmid_map_diameter = $match['diameter'];
		//restriction sites
		$query="SELECT * FROM `plasmid_map_res` WHERE `plasmid_id`='{$_REQUEST['id']}' ORDER BY `site`";
		$rs=$db_conn->query($query);
		$match=array();
		$n=1;
		while($match=$rs->fetch_assoc()) {
			$plasmid_map_res[$n]['id'] = $n;
			$plasmid_map_res[$n]['name'] = $match['name'];
			$plasmid_map_res[$n]['site'] = $match['site'];
			$n++;
		}
		//original features
		$query="SELECT * FROM `plasmid_map_fea` WHERE `plasmid_id`='{$_REQUEST['id']}' AND `insert`=0 ORDER BY `start`";
		$rs=$db_conn->query($query);
		$plasmid_map_fea=array();
		$n=1;
		while($match=$rs->fetch_assoc()) {
			$plasmid_map_fea[$n]['id'] = $n;
			$plasmid_map_fea[$n]['name'] = $match['name'];
			$plasmid_map_fea[$n]['color'] = $match['color'];
			$plasmid_map_fea[$n]['start'] = $match['start'];
			$plasmid_map_fea[$n]['end'] = $match['end'];
			$plasmid_map_fea[$n]['ori'] = $match['ori'];
			$n++;
		}
		//insert
		$query="SELECT * FROM `plasmid_map_fea` WHERE `plasmid_id`='{$_REQUEST['id']}' AND `insert`=1 ORDER BY `start`";
		$rs=$db_conn->query($query);
		$plasmid_map_insert=array();
		$n=1;
		while($match=$rs->fetch_assoc()) {
			$plasmid_map_insert[$n]['id'] = $n;
			$plasmid_map_insert[$n]['name'] = $match['name'];
			$plasmid_map_insert[$n]['color'] = $match['color'];
			$plasmid_map_insert[$n]['start'] = $match['start'];
			$plasmid_map_insert[$n]['end'] = $match['end'];
			$plasmid_map_insert[$n]['ori'] = $match['ori'];
			$n++;
		}
		//output the image, transfer data using GET
		$plasmid_map_get="name=".$plasmid_map_name."&size=".$plasmid_map_size."&diameter=".$plasmid_map_diameter;
		if (count($plasmid_map_res)>0) {
			$n=1;
			foreach ($plasmid_map_res as $key=>$value) {
				//$res_id=$value['id'];
				$plasmid_map_get.="&res_name_".$n."=".$value['name']."&res_site_".$n."=".$value['site'];
				$n++;
			}
		}
		//combine the original features and inserts together as features
		if (count($plasmid_map_fea)>0||count($plasmid_map_insert)>0) {
			$n=1;
			foreach ($plasmid_map_fea as $key=>$value) {
				$plasmid_map_get.="&fea_name_".$n."=".$value['name']."&fea_color_".$n."=".$value['color']."&fea_start_".$n."=".$value['start']."&fea_end_".$n."=".$value['end']."&fea_ori_".$n."=".$value['ori'];
				$n++;
			}
			foreach ($plasmid_map_insert as $key=>$value) {
				$plasmid_map_get.="&fea_name_".$n."=".$value['name']."&fea_color_".$n."=".$value['color']."&fea_start_".$n."=".$value['start']."&fea_end_".$n."=".$value['end']."&fea_ori_".$n."=".$value['ori'];
				$n++;
			}
		}
		echo "<tr><td colspan='2'>Map drawn:</td></tr>";
		echo "<tr><td colspan='2'>";
		echo "&nbsp;&nbsp;";
		echo "<a href='plasmid_mapping.php?plasmid_id=".$_REQUEST['id']."' title='edit map' target='_blank'/>";
		echo "<img src='include/bioinfo/sm_plasmid_mapping_output.php?".$plasmid_map_get."' border='1'/>";
		echo "</a></td></tr>";
	}
	?>
	  <tr>
      <td colspan="2">Sequence:</td>
    </tr>
    <tr>
      <td colspan="2" class="sequence"><?php
      $query = "select * from plasmid_sequences where plasmid_id = '{$_REQUEST['id']}'";
      $result = $db_conn->query($query);
      $plasmid_sequences=$result->fetch_assoc();
      echo nl2br(sm_tidyup($plasmid_sequences['sequence']));?></td>
    </tr>
    <tr>
      <td colspan='2'><a href='<?php echo $_SESSION['url_1'];?>'>
      <img src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a>
      </td>
    </tr>
  </table>
<?php
}
function delete_form()
{
	$plasmid = get_record_from_id('plasmids',$_REQUEST['id']);
	if(!userPermission('2',$plasmid['created_by']))
	{
		alert();
	}
	if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
	{
		$module = get_id_from_name('modules','plasmids');
		$db_conn=db_connect();
		$query = "SELECT *
    	FROM items_relation WHERE item_from='".$module['id']."_".$_REQUEST['id'].
    	"' OR item_to='".$module['id']."_".$_REQUEST['id']."'";
    	$relateditem = $db_conn->query($query);
    	$relateditem_count=$relateditem->num_rows;

    	$query = "SELECT id
    	FROM storages WHERE module_id='{$module['id']}'
        AND item_id ='{$_REQUEST['id']}'";
    	$storage = $db_conn->query($query);
    	$storage_count=$storage->num_rows;

    	$query = "SELECT id
    	FROM orders WHERE module_id='{$module['id']}'
        AND item_id ='{$_REQUEST['id']}'";
    	$order = $db_conn->query($query);
    	$order_count=$order->num_rows;

    	if($relateditem_count==0&&$storage_count==0&&$order_count==0)
    	{
    		echo "<form name='delete' method='post' action=''>";
    		echo "<table width='100%' class='operate' >
<tr><td colspan='2'><div align='center'><h2>Plasmids</h2></div></td></tr>";
    		echo "<tr><td colspan='2'><h3>Are you sure to delete the plasmid: ";
    		echo $plasmid['name'];
    		echo "?</h3></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' name='Submit' value='Submit' />";
    		hidden_inputs('','',"delete");
    		echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a></td></tr>";
    		echo "</table></form>";
    	}
    	else
    	{
    		echo "<table width='100%' class='operate' >
<tr><td colspan='2'><div align='center'><h2>Plasmids</h2></div></td></tr>";
    		echo "<tr><td><h3>This plasmid related to ";
    		if($relateditem_count!=0)
    		{
    			echo "<br>".$relateditem_count." other items, ";
    		}
    		if($storage_count!=0)
    		{
    			echo "<br>".$storage_count." storages, ";
    		}
    		if($order_count!=0)
    		{
    			echo "<br>".$order_count." orders, ";
    		}
    		echo "<br>do not suggest to delete!</h3></td>
      </tr>
      <tr><td>
      <a href='". $_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a></td></tr>";
    		echo "</form>";
    	}
    	
	}
	elseif($_SESSION['selecteditemDel'])//multiple delete
	{
		$num_selecteditemDel=count($_SESSION['selecteditemDel']);
		echo "<form name='edit' method='post' action=''>";
		echo "<table width='100%' class='operate' >
<tr><td colspan='2'><div align='center'><h2>Plasmids</h2></div></td></tr>";
		echo "<table width='100%' class='operate' >
<tr><td colspan='2'><div align='center'><h2>Plasmids</h2></div></td></tr>";
		echo "<tr><td colspan='2'><h3>Are you sure to delete the $num_selecteditemDel plasmid(s)?<br>
    plasmid related to other items, storages, orders <br>can not be deleted.</h3></td></tr> ";
		echo "<tr><td colspan='2'><input type='submit' name='Submit' value='Submit' />";
		hidden_inputs('','',"delete");
		echo "&nbsp;<a href='".$_SESSION['url_1']."'><img
      src='./assets/image/general/back.gif' alt='Back' title='Back' border='0'/></a></td></tr>";
		echo "</table></form>";
	}
}
function import_form() {
	if(!userPermission('3')) {
		alert();
	}
  ?>
<script>
function submit() {
	document.download.submit();
}
</script>
<form name='preview' method='post' action='' enctype="multipart/form-data">
<table width="100%" class="operate" >
<tr><td colspan='2'><div align='center'><h2>Plasmids</h2></div></td></tr>
<tr><td colspan='2'><h3>Import from file:</h3></td></tr>
<tr>
<td width='20%'>File:</td>
<td width='80%'><input type='file' name='file'/>*</td>
</tr>
<tr><td colspan='2'><input type='submit' name='Submit' value='Preview' /></td></tr>
</form>
<form name="download" id="download" method="POST" target="_self">
<tr><td colspan='2' >
<a href='#' onclick="submit()"><span style="font-size:10pt;">DOWNLOAD</span></a> the template (CSV file).</td></tr>
<input type="hidden" name="type" value="import_template"/>
</form>
<tr><td colspan="2"><span style="color:red"/>NOTE:</span></td></tr>
<tr><td colspan="2">*For the column 'isinsert', use 0 represents no and 1 represents yes, default 0.</td></tr>
<tr><td colspan="2">*For the column 'project', use project id (find it in the projects module), default 'others'.</td></tr>
 <tr><td colspan="2">*For the column 'mask', use 0 represents no and 1 represents yes, default 0.</td></tr>
</table>
  <?php
  try{
  	if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
  		$type = basename($_FILES['file']['type']);
  		switch ($type) {
  			case 'octet-stream':
  			case 'plain':     break;
  			default:        throw new Exception('Invalid file format: '.
  			$_FILES['file']['type'].$type) ;
  		}
  	}
  	if((isset($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name']))) {
  		$db_conn=db_connect();

  		$query="DROP TABLE IF EXISTS temp_plasmids";
  		$result=$db_conn->query($query);

  		$query="DROP TABLE IF EXISTS temp_plasmid_sequences";
  		$result=$db_conn->query($query);

  		$query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_plasmids SELECT * FROM plasmids WHERE 0";
  		$result=$db_conn->query($query);

  		$query="ALTER TABLE temp_plasmids MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
  		$result=$db_conn->query($query);

  		$query="CREATE TEMPORARY TABLE IF NOT EXISTS temp_plasmid_sequences SELECT * FROM plasmid_sequences WHERE 0";
  		$result=$db_conn->query($query);

  		$query="ALTER TABLE temp_plasmid_sequences MODIFY id INT(9) UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY";
  		$result=$db_conn->query($query);

  		//$file=file($_FILES['file']['tmp_name']);
  		$fp=fopen($_FILES['file']['tmp_name'],'rb');
  		//$row_num=count($file);

  		//$separator=$_REQUEST['separator'];
  		//if ($separator=="tab") $separator="\t";
  		//elseif ($separator=="comma") $separator=",";
  		$date_create=date('Y-m-d');
  		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
  		$result = $db_conn->query($query);
  		$match=$result->fetch_assoc();
  		$created_by=$match['people_id'];
  		$n=0;
  		while ($data=fgetcsv($fp)) {
  			if ($n>0) {
  				$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
  				$description=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
  				$vector_name=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
  				$vector_type=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
  				$sequence=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
  				$isinsert=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
  				if($isinsert != 1) $isinsert=0;
  				$insert_name=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
  				$species=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
  				$sequence_identifier=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
  				$insert_size=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
  				$modification=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
  				$cloning_sites=mb_convert_encoding(addslashes(trim($data[11])),"utf-8","gb2312");
  				$tags=mb_convert_encoding(addslashes(trim($data[12])),"utf-8","gb2312");
  				$project=mb_convert_encoding(addslashes(trim($data[13])),"utf-8","gb2312");
  				$query="SELECT * FROM projects WHERE id='$project'";
  				$result=$db_conn->query($query);
  				$match=$result->fetch_assoc();
  				if($result->num_rows==0) $project=1;
  				$note=mb_convert_encoding(addslashes(trim($data[14])),"utf-8","gb2312");
  				$mask=mb_convert_encoding(addslashes(trim($data[15])),"utf-8","gb2312");
  				if($mask != 1) $mask=0;

  				$query_custom_fields="SELECT * FROM custom_fields WHERE module_name='plasmids' ORDER BY id";
  				if($isinsert=='1') {
  					$query = "insert into temp_plasmids
      		(name,description,vector_name,vector_type,isinsert,insert_name,species,sequence_identifier,insert_size,modification,cloning_sites,tags,date_create,created_by,project,note,mask";
  					$rs=$db_conn->query($query_custom_fields);
  					while ($match=$rs->fetch_assoc()) {
  						$query.=",".$match['field_id'];
  					}
  					$query.=")
       		VALUES
      		('$name','$description','$vector_name','$vector_type','$isinsert','$insert_name','$species','$sequence_identifier','$insert_size','$modification','$cloning_sites','$tags','$date_create','$created_by','$project','$note','$mask'";
  					$c=16;
  					$rs=$db_conn->query($query_custom_fields);
  					while ($match=$rs->fetch_assoc()) {
  						$query.=",'".mb_convert_encoding(addslashes(trim($data[$c])),"utf-8","gb2312")."'";;
  						$c++;
  					}
  					$query.=")";
  				} else {
  					$query = "insert into temp_plasmids
      		(name,description,vector_name,vector_type,isinsert,date_create,created_by,project,note,mask";
  					$rs=$db_conn->query($query_custom_fields);
  					while ($match=$rs->fetch_assoc()) {
  						$query.=",".$match['field_id'];
  					}
  					$query.=")
       		VALUES
      		('$name','$description','$vector_name','$vector_type','$isinsert','$date_create','$created_by','$project','$note','$mask'";
  					$c=16;
  					$rs=$db_conn->query($query_custom_fields);
  					while ($match=$rs->fetch_assoc()) {
  						$query.=",'".mb_convert_encoding(addslashes(trim($data[$c])),"utf-8","gb2312")."'";;
  						$c++;
  					}
  					$query.=")";
  				}
  				$result=$db_conn->query($query);
  				$id=$db_conn->insert_id;
  				if(!$result) {
  					throw new Exception("There was a database error when executing<pre>$query</pre>") ;
  				}
  				if($sequence!='') {
  					$sequence=sm_remove_useless($sequence);
  					$query="INSERT INTO temp_plasmid_sequences (plasmid_id,sequence) VALUES ('$id','$sequence')";
  					$result = $db_conn->query($query);
  				}
  			}
  			$n++;
  		}
  		$rand=mt_rand();
  		$filename = "temp/plasmids_import_$rand.csv";
  		move_uploaded_file($_FILES['file']['tmp_name'],'./'.$filename);

  		$query="SELECT * FROM temp_plasmids";
  		$results=$db_conn->query($query);
  		$row_num=$results->num_rows;
  		if ($results  && $results->num_rows) {
      	?>
<form name='import' method='post' action=''>
<input type="hidden" name="action" value="import">
<input type="hidden" name="filename" value="<?php echo $filename ?>">
<table width="100%" class="alert"><tr><td>Totally <?php echo $row_num ?> records, check the data carefully before import!
<input type='submit' name='submit' value='import'></td></tr></table>
<table width='100%' class='results'>
<tr><td class='results_header'>name</td><td class='results_header'>
description</td><td class='results_header'>
vector name</td><td class='results_header'>
vector type</td><td class='results_header'>
sequence</td><td class='results_header'>
is inserted*</td><td class='results_header'>
insert name</td><td class='results_header'>
species</td><td class='results_header'>
sequence identifier</td><td class='results_header'>
insert size</td><td class='results_header'>
modification</td><td class='results_header'>
cloning sites</td><td class='results_header'>
tags</td><td class='results_header'>
project*</td><td class='results_header'>
note</td><td class='results_header'>
mask*</td>
	    <?php
	    $query="SELECT * FROM custom_fields WHERE module_name='plasmids' ORDER BY id";
	    $rs=$db_conn->query($query);
	    while ($match=$rs->fetch_assoc()) {
	    	echo "<td class='results_header'><i>";
	    	echo $match['field_name'];
	    	echo "</i></td>";
	    }?>
	    </tr>
	    <?php
	    while ($matches = $results->fetch_array()) {
	    	echo "<tr>";
	    	echo "<td class='results'>".$matches['name']."</td>";
	    	echo "<td class='results'>".$matches['description']."</td>";
	    	echo "<td class='results'>".$matches['vector_name']."</td>";
	    	echo "<td class='results'>".$matches['vector_type']."</td>";
	    	$query = "select * from temp_plasmid_sequences where plasmid_id = '{$matches['id']}'";
	    	$result = $db_conn->query($query);
	    	$plasmid_sequences=$result->fetch_assoc();
	    	if(strlen($plasmid_sequences['sequence'])<=30) {
	    		echo "<td class='results'>".chunk_split($plasmid_sequences['sequence'],10,"<br/>")."</td>";
	    	}
	    	else {
	    		echo "<td class='results'>".chunk_split(substr($plasmid_sequences['sequence'],0,30),10,"<br/>");
	    		echo "...[".strlen($plasmid_sequences['sequence'])." bp]</td>";
	    	}
	    	echo "<td class='results'>".$matches['isinsert']."</td>";
	    	echo "<td class='results'>".$matches['insert_name']."</td>";
	    	echo "<td class='results'>".$matches['species']."</td>";
	    	echo "<td class='results'>".$matches['sequence_identifier']."</td>";
	    	echo "<td class='results'>".$matches['insert_size']."</td>";
	    	echo "<td class='results'>".$matches['modification']."</td>";
	    	echo "<td class='results'>".$matches['cloning_sites']."</td>";
	    	echo "<td class='results'>".$matches['tags']."</td>";
	    	$query="SELECT * FROM projects WHERE id='{$matches['project']}'";
	    	$result=$db_conn->query($query);
	    	$project=$result->fetch_assoc();
	    	echo "<td class='results'>".$project['name']."</td>";
	    	echo "<td class='results'>".$matches['note']."</td>";
	    	echo "<td class='results'>".$matches['mask']."</td>";
	    	$query="SELECT * FROM custom_fields WHERE module_name='plasmids' ORDER BY id";
	    	$rs=$db_conn->query($query);
	    	while ($match=$rs->fetch_assoc()) {
	    		echo "<td class='results'>";
	    		echo $matches[$match['field_id']];
	    		echo "</td>";
	    	}
	    	echo "</tr>";
	    }
	    echo "</table></form>";
  		}
  	}
  }
  catch (Exception $e) {
  	echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
  }
}
function add()
{
	try {
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['created_by'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$name = $_REQUEST['name'];
		$project = $_REQUEST['project'];
		$description = $_REQUEST['description'];
		$vector_name = $_REQUEST['vector_name'];
		$vector_type = $_REQUEST['vector_type'];
		$raw_seq = $_REQUEST['raw_seq'];
		$isinsert = $_REQUEST['isinsert'];
		$insert_name = $_REQUEST['insert_name'];
		$species = $_REQUEST['species'];
		$sequence_identifier = $_REQUEST['sequence_identifier'];
		$insert_size = $_REQUEST['insert_size'];
		$modification = $_REQUEST['modification'];
		$cloning_sites = $_REQUEST['cloning_sites'];
		$tags = $_REQUEST['tags'];
		$date_create = $_REQUEST['date_create'];
		$created_by=$_REQUEST['created_by'];
		$note=$_REQUEST['note'];
		$mask=$_REQUEST['mask'];
		if ( (isset($_FILES['map']['name']) && is_uploaded_file($_FILES['map']['tmp_name']))) {
			$type = basename($_FILES['map']['type']);
			switch ($type) {
				case 'jpeg':
				case 'pjpeg':     break;
				default:        throw new Exception('Invalid picture format: '.
				$_FILES['map']['type'].$type) ;
			}
		}
		$db_conn = db_connect();
		//start transaction
		$db_conn->autocommit(false);
		$query_custom_fields="SELECT * FROM custom_fields WHERE module_name='plasmids' ORDER BY id";

		if($isinsert=='1') {
			$query = "insert into plasmids
      (name,description,vector_name,vector_type,isinsert,insert_name,species,sequence_identifier,insert_size,modification,cloning_sites,tags,date_create,created_by,project,note,mask";
			$rs=$db_conn->query($query_custom_fields);
			while ($match=$rs->fetch_assoc() ) {
				$query.=",".$match['field_id'];
			}
			$query.=")
       VALUES
      ('$name','$description','$vector_name','$vector_type','$isinsert','$insert_name','$species','$sequence_identifier','$insert_size','$modification','$cloning_sites','$tags','$date_create','$created_by','$project','$note','$mask'";
      $rs=$db_conn->query($query_custom_fields);
			while ($match=$rs->fetch_assoc() ) {
				$query.=",'".$_REQUEST[$match['field_id']]."'";
			}
			$query.=")";
		}
		else {
			$query = "insert into plasmids
      (name,description,vector_name,vector_type,isinsert,date_create,created_by,project,note,mask";
			$rs=$db_conn->query($query_custom_fields);
			while ($match=$rs->fetch_assoc() ) {
				$query.=",".$match['field_id'];
			}
      $query.=")
       VALUES
      ('$name','$description','$vector_name','$vector_type','$isinsert','$date_create','$created_by','$project','$note','$mask'";
			$rs=$db_conn->query($query_custom_fields);
			while ($match=$rs->fetch_assoc() ) {
				$query.=",'".$_REQUEST[$match['field_id']]."'";
			}
			$query.=")";
		}
		$result = $db_conn->query($query);
		$id=$db_conn->insert_id;
		if (!$result)
		{
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		if ( (isset($_FILES['map']['name']) && is_uploaded_file($_FILES['map']['tmp_name'])))
		{
			$filename = "../quicklab_data/plasmids/map_$id.jpg";
			move_uploaded_file($_FILES['map']['tmp_name'],$filename);
			$query = "update plasmids
              set map = '$filename'
              where id = $id";
			$result = $db_conn->query($query);
		}
		if(filled_out(array($_REQUEST['raw_seq'])))
		{
			$sequence=sm_remove_useless($raw_seq);
			$query="INSERT INTO plasmid_sequences
    	(plasmid_id,sequence)
    	VALUES
    	('$id','$sequence')";
			$result = $db_conn->query($query);
		}
		//finish transaction
		$db_conn->commit();
		header('Location: plasmids.php?id='.$id);
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function import() {
	try {
		$filename = $_POST['filename'];
		$fp=fopen("./".$filename,"rb");
		unlink("./".$filename);
		//$row_num=count($file);

		//$separator=$_REQUEST['separator'];
		//if ($separator=="tab") $separator="\t";
		//elseif ($separator=="comma") $separator=",";
		$date_create=date('Y-m-d');
		$db_conn=db_connect();
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		$created_by=$match['people_id'];
		$n=0;
		while($data=fgetcsv($fp)) {
			if ($n>0) {
				$name=mb_convert_encoding(addslashes(trim($data[0])),"utf-8","gb2312");
				$description=mb_convert_encoding(addslashes(trim($data[1])),"utf-8","gb2312");
				$vector_name=mb_convert_encoding(addslashes(trim($data[2])),"utf-8","gb2312");
				$vector_type=mb_convert_encoding(addslashes(trim($data[3])),"utf-8","gb2312");
				$sequence=mb_convert_encoding(addslashes(trim($data[4])),"utf-8","gb2312");
				$isinsert=mb_convert_encoding(addslashes(trim($data[5])),"utf-8","gb2312");
				if($isinsert != 1) $isinsert=0;
				$insert_name=mb_convert_encoding(addslashes(trim($data[6])),"utf-8","gb2312");
				$species=mb_convert_encoding(addslashes(trim($data[7])),"utf-8","gb2312");
				$sequence_identifier=mb_convert_encoding(addslashes(trim($data[8])),"utf-8","gb2312");
				$insert_size=mb_convert_encoding(addslashes(trim($data[9])),"utf-8","gb2312");
				$modification=mb_convert_encoding(addslashes(trim($data[10])),"utf-8","gb2312");
				$cloning_sites=mb_convert_encoding(addslashes(trim($data[11])),"utf-8","gb2312");
				$tags=mb_convert_encoding(addslashes(trim($data[12])),"utf-8","gb2312");
				$project=mb_convert_encoding(addslashes(trim($data[13])),"utf-8","gb2312");
				$query="SELECT * FROM projects WHERE id='$project'";
				$result=$db_conn->query($query);
				$match=$result->fetch_assoc();
				if($result->num_rows==0) $project=1;
				$note=mb_convert_encoding(addslashes(trim($data[14])),"utf-8","gb2312");
				$mask=mb_convert_encoding(addslashes(trim($data[15])),"utf-8","gb2312");
				if($mask != 1) $mask=0;

				$query_custom_fields="SELECT * FROM custom_fields WHERE module_name='plasmids' ORDER BY id";
				$rs=$db_conn->query($query_custom_fields);
				if($isinsert=='1') {
					$query = "insert into plasmids
      		(name,description,vector_name,vector_type,isinsert,insert_name,species,sequence_identifier,insert_size,modification,cloning_sites,tags,date_create,created_by,project,note,mask";
					$rs=$db_conn->query($query_custom_fields);
					while ($match=$rs->fetch_assoc()) {
						$query.=",".$match['field_id'];
					}
					$query.=")
       		VALUES
      		('$name','$description','$vector_name','$vector_type','$isinsert','$insert_name','$species','$sequence_identifier','$insert_size','$modification','$cloning_sites','$tags','$date_create','$created_by','$project','$note','$mask'";
					$c=16;
					$rs=$db_conn->query($query_custom_fields);
					while ($match=$rs->fetch_assoc()) {
						$query.=",'".mb_convert_encoding(addslashes(trim($data[$c])),"utf-8","gb2312")."'";;
						$c++;
					}
					$query.=")";
				} else {
					$query = "insert into plasmids
      		(name,description,vector_name,vector_type,isinsert,date_create,created_by,project,note,mask";
					$rs=$db_conn->query($query_custom_fields);
					while ($match=$rs->fetch_assoc()) {
						$query.=",".$match['field_id'];
					}
					$query.=")
       		VALUES
      		('$name','$description','$vector_name','$vector_type','$isinsert','$date_create','$created_by','$project','$note','$mask'";
					$c=16;
					$rs=$db_conn->query($query_custom_fields);
					while ($match=$rs->fetch_assoc()) {
						$query.=",'".mb_convert_encoding(addslashes(trim($data[$c])),"utf-8","gb2312")."'";;
						$c++;
					}
					$query.=")";
				}
				$result=$db_conn->query($query);
				$id=$db_conn->insert_id;
				if(!$result) {
					throw new Exception("There was a database error when executing<pre>$query</pre>") ;
				}
				if($sequence!='') {
					$sequence=sm_remove_useless($sequence);
					$query="INSERT INTO plasmid_sequences (plasmid_id,sequence) VALUES ('$id','$sequence')";
					$result = $db_conn->query($query);
				}
			}
			$n++;
		}
		header('Location: '.$_SESSION['url_1']);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function edit_relation()
{
	try{
		$id=$_REQUEST['id'];
		$module=get_record_from_name('modules','plasmids');
		$db_conn=db_connect();
		$query="DELETE FROM items_relation WHERE item_from='".$module['id']."_".$id."'";
		$result = $db_conn->query($query);
		if (!$result)
		{
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		for($i=0;$i<count($_REQUEST['clipboardtarget']); $i++)
		{
			$query="INSERT INTO items_relation
      (item_from,item_to)
      VALUES
      ('".$module['id']."_".$id."','".$_REQUEST['clipboardtarget'][$i]."')";
			$result = $db_conn->query($query);
			if (!$result)
			{
				throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
			}
		}
		header("Location:".$_SESSION['url_1']);
	}
	catch (Exception $e)
	{
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function edit()
{
	try {
		if (!filled_out(array($_REQUEST['name'],$_REQUEST['updated_by'])))
		{
			throw new Exception('You have not filled the form out correctlly,</br>- please try again.');
		}
		$id=$_REQUEST['id'];
		$name = $_REQUEST['name'];
		$project = $_REQUEST['project'];
		$description = $_REQUEST['description'];
		$vector_name = $_REQUEST['vector_name'];
		$vector_type = $_REQUEST['vector_type'];
		$raw_seq = $_REQUEST['raw_seq'];
		$isinsert = $_REQUEST['isinsert'];
		$insert_name = $_REQUEST['insert_name'];
		$species = $_REQUEST['species'];
		$sequence_identifier = $_REQUEST['sequence_identifier'];
		$insert_size = $_REQUEST['insert_size'];
		$modification = $_REQUEST['modification'];
		$cloning_sites = $_REQUEST['cloning_sites'];
		$tags = $_REQUEST['tags'];
		$date_update = $_REQUEST['date_update'];
		$updated_by = $_REQUEST['updated_by'];
		$note = $_REQUEST['note'];
		$mask = $_REQUEST['mask'];
		if ( (isset($_FILES['map']['name']) && is_uploaded_file($_FILES['map']['tmp_name']))) {
			$type = basename($_FILES['map']['type']);
			switch ($type) {
				case 'jpeg':
				case 'pjpeg':     break;
				default:        throw new Exception('Invalid picture format: '.
				$_FILES['map']['type'].$type) ;
			}
		}
		$db_conn = db_connect();
		//start transaction
		$db_conn->autocommit(false);
		//plasmid
		$query_custom_fields="SELECT * FROM custom_fields WHERE module_name='plasmids'";
		if($isinsert=='1') {
			$query = "update plasmids SET
		    name='$name',
			description='$description',
			vector_name='$vector_name',
			vector_type='$vector_type',
			isinsert='$isinsert',
			insert_name='$insert_name',
			species='$species',
			sequence_identifier='$sequence_identifier',
			insert_size='$insert_size',
			modification='$modification',
			cloning_sites='$cloning_sites',
			tags='$tags',
			date_update='$date_update',
			updated_by='$updated_by',
			project='$project',
			note='$note',";
			$rs=$db_conn->query($query_custom_fields);
			while ($match=$rs->fetch_assoc()) {
				$query.=$match['field_id']."='".$_REQUEST[$match['field_id']]."', ";
			}
			$query.=" mask='$mask'
			where id='$id'";
		} else {
			$query = "update plasmids SET
		    name='$name',
			description='$description',
			vector_name='$vector_name',
			vector_type='$vector_type',
			isinsert='$isinsert',
			insert_name='',
			species='',
			sequence_identifier='',
			insert_size='',
			modification='',
			cloning_sites='',
			tags='',
			date_update='$date_update',
			updated_by='$updated_by',
			project='$project',
			note='$note',";
			$rs=$db_conn->query($query_custom_fields);
			while ($match=$rs->fetch_assoc()) {
				$query.=$match['field_id']."='".$_REQUEST[$match['field_id']]."', ";
			}
			$query.=" mask='$mask'
			where id='$id'";
		}
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		//update plasmid bank name
		$query="UPDATE plasmid_bank SET name='$name' WHERE plasmid_id='$id'";
		$result = $db_conn->query($query);
		if (!$result) {
			throw new Exception("There was a database error when executing <pre>$query</pre>,</br>please try again.");
		}
		//plasmid map uploaded
		if ( (isset($_FILES['map']['name']) && is_uploaded_file($_FILES['map']['tmp_name']))) {
			$filename = "../quicklab_data/plasmids/map_$id.jpg";
			move_uploaded_file($_FILES['map']['tmp_name'],$filename);
			$query = "update plasmids
              set map = '$filename'
              where id = '$id'";
			$result = $db_conn->query($query);
		}
		elseif($_REQUEST['delete_image']=='1') {
			$query="SELECT map from plasmids where id='$id'";
			$result = $db_conn->query($query);
			$match=$result->fetch_assoc();
			unlink("./".$match['map']);
			$query = "update plasmids
               set map = ''
               where id = '$id'";
			$result = $db_conn->query($query);
		}
		//plasmid sequence
		$sequence=sm_remove_useless($raw_seq);
		$query="DELETE FROM `plasmid_sequences` WHERE `plasmid_id`='$id'";
		$db_conn->query($query);
		if(strlen($sequence)>0) {
			$query="INSERT INTO `plasmid_sequences` (`plasmid_id`,`sequence`) VALUES ('$id','$sequence')";
			$db_conn->query($query);
		}
		//finish transaction
		$db_conn->commit();
		header("Location:".$_SESSION['url_1']);
	}
	catch (Exception $e) {
		echo '<table class="alert"><tr><td><h3>'.$e->getMessage().'</h3></td></tr></table>';
	}
}
function delete()
{
	$db_conn=db_connect();
	//start transaction
	$db_conn->autocommit(false);
	if(isset($_REQUEST['id'])&&$_REQUEST['id']!='')//single delete
	{
		$query="SELECT map from plasmids where id='{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		if($match['map']!='') {
			unlink("./".$match['map']);
		}
		$query="DELETE from plasmid_sequences where plasmid_id='{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
		$query="DELETE from plasmid_request where plasmid_id='{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
		$query="DELETE from plasmid_bank where plasmid_id='{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
		$query="DELETE from plasmid_map where plasmid_id='{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
		$query="DELETE from plasmid_map_res where plasmid_id='{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
		$query="DELETE from plasmid_map_fea where plasmid_id='{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
		$query = "delete from plasmids where id = '{$_REQUEST['id']}'";
		$result = $db_conn->query($query);
	}
	elseif($_SESSION['selecteditemDel'])//multiple delete
	{
		$selecteditemDel=$_SESSION['selecteditemDel'];
		unset($_SESSION['selecteditemDel']);
		$num_selecteditemDel=count($selecteditemDel);
		$module = get_id_from_name('modules','plasmids');
		for($i=0;$i<$num_selecteditemDel;$i++)
		{
			$query = "SELECT *
    	FROM items_relation WHERE item_from='".$module['id']."_".$selecteditemDel[$i].
    	"' OR item_to='".$module['id']."_".$selecteditemDel[$i]."'";
    	$relateditem = $db_conn->query($query);
    	$relateditem_count=$relateditem->num_rows;//check related items
    	$query = "SELECT id
    	FROM storages WHERE module_id='{$module['id']}'
        AND item_id ='{$selecteditemDel[$i]}'";
    	$storage = $db_conn->query($query);
    	$storage_count=$storage->num_rows;//check related storages
    	$query = "SELECT id
    	FROM orders WHERE module_id='{$module['id']}'
        AND item_id ='{$selecteditemDel[$i]}'";
    	$order = $db_conn->query($query);
    	$order_count=$order->num_rows;//check related orders
    	if($relateditem_count==0&&$storage_count==0&&$order_count==0)
    	{
    		$query="SELECT map from plasmids where id='{$selecteditemDel[$i]}'";
    		$result = $db_conn->query($query);
    		$match=$result->fetch_assoc();
    		if($match['map']!='') {
    			unlink("./".$match['map']);
    		}
    		$query="DELETE from plasmid_sequences where plasmid_id='{$selecteditemDel[$i]}'";
    		$result = $db_conn->query($query);
    		$query="DELETE from plasmid_request where plasmid_id='{$selecteditemDel[$i]}'";
			$result = $db_conn->query($query);
			$query="DELETE from plasmid_bank where plasmid_id='{$selecteditemDel[$i]}'";
			$result = $db_conn->query($query);
			$query="DELETE from plasmid_map where plasmid_id='{$selecteditemDel[$i]}'";
			$result = $db_conn->query($query);
			$query="DELETE from plasmid_map_res where plasmid_id='{$selecteditemDel[$i]}'";
			$result = $db_conn->query($query);
			$query="DELETE from plasmid_map_fea where plasmid_id='{$selecteditemDel[$i]}'";
			$result = $db_conn->query($query);
    		$query = "delete from plasmids where id = '{$selecteditemDel[$i]}'";
    		$result = $db_conn->query($query);
    	}
		}
	}
	//finish transaction
	$db_conn->commit();
	header('Location: '.$_SESSION['url_1']);
}

function import_template() {
	$fileName ='plasmids_import.csv';
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName");
	echo "name,";
	echo "description,";
	echo "vector_name,";
	echo "vector_type,";
	echo "sequence,";
	echo "isinsert,";
	echo "insert_name,";
	echo "species,";
	echo "sequence_identifier,";
	echo "insert_size,";
	echo "modification,";
	echo "cloning_sites,";
	echo "tags,";
	echo "project*,";
	echo "note,";
	echo "mask*,";
	$db_conn=db_connect();
	$query="SELECT * FROM custom_fields WHERE module_name='plasmids' ORDER BY id";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		echo $match['field_name'].",";
	}
}

function hidden_inputs($people,$date,$action)
{
	if($people!=null)
	{
		echo "<input type='hidden' name='$people' value='";
		$db_conn = db_connect();
		$query = "select * from users where username = '{$_COOKIE['wy_user']}'";
		$result = $db_conn->query($query);
		$match=$result->fetch_assoc();
		echo $match['people_id']."'>";
	}
	if($date!=null)
	{
		echo "<input type='hidden' name=$date value='";
		echo date('Y-m-d')."'>";
	}
	echo "<input type='hidden' name='action' value='$action' >";
	if($_REQUEST['destination'])
	{
		echo "<input type='hidden' name='destination' value= '";
		echo $_REQUEST['destination']."'>";
	}
	else
	{
		echo "<input type='hidden' name='destination' value= '";
		echo $_SERVER['HTTP_REFERER']."'>";
	}
}
function process_request()
{
	$type = $_REQUEST['type'];
	switch ($type) {
		case "add":add_form();break;
		case "detail":detail();break;
		case "edit":edit_form();break;
		case "delete":delete_form();break;
		case "relation":edit_relation_form();break;
		case "import":import_form();break;
		default:break;
	}
	$action = $_POST['action'];
	switch ($action) {
		case "add":add();break;
		case "edit":edit();break;
		case "import":import();break;
		case "detail":detail();break;
		case "relation":edit_relation();break;
		case "delete":delete();break;
		default:break;
	}
}
function export_excel($module_name,$query) {
	$db_conn=db_connect();
	$results = $db_conn->query($query);
	$num_rows=$results->num_rows;
	$rc = 0;
	while($row = $results->fetch_array()) {
		$query = "select * from plasmid_sequences where plasmid_id = '{$row['id']}'";
		$result = $db_conn->query($query);
		$plasmid_sequences=$result->fetch_assoc();
		$query="SELECT name from projects WHERE id='{$row['project']}'";
		$result = $db_conn->query($query);
		$project=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['created_by']}'";
		$result = $db_conn->query($query);
		$created_by=$result->fetch_assoc();
		$query="SELECT name from people WHERE id='{$row['updated_by']}'";
		$result = $db_conn->query($query);
		$updated_by=$result->fetch_assoc();
		$mask_array = array( 'no'=>'0','yes'=>'1');
		foreach ($mask_array as $key=>$value) {
			if ($value == $row['mask']) {
				$mask= $key;
			}
		}
		$data= ereg_replace("[\r,\n,\t]"," ",$row['id'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['name'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['description'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['vector_name'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['vector_type'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$plasmid_sequences['sequence'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['isinsert'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['insert_name'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['species'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['sequence_identifier'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['insert_size'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['modification'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['cloning_sites'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['tags'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$created_by['name'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['date_create'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$updated_by['name'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['date_update'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$project['name'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$row['note'])."\t".
		ereg_replace("[\",\r,\n,\t]"," ",$mask);
		$query="SELECT * FROM custom_fields WHERE module_name='plasmids' ORDER BY id";
		$rs=$db_conn->query($query);
		while ($match=$rs->fetch_assoc()) {
			$data.="\t".ereg_replace("[\",\r,\n,\t]"," ",$row[$match['field_id']]);
		}
		$xls[]=$data;
	}
	$title="id"."\t".
	"name"."\t".
	"description"."\t".
	"vector_name"."\t".
	"vector_type"."\t".
	"sequence"."\t".
	"isinsert"."\t".
	"insert_name"."\t".
	"species"."\t".
	"sequence_identifier"."\t".
	"insert_size"."\t".
	"modification"."\t".
	"cloning_sites"."\t".
	"tags"."\t".
	"created_by"."\t".
	"date_create"."\t".
	"updated_by"."\t".
	"date_update"."\t".
	"project"."\t".
	"note"."\t".
	"mask";
	$query="SELECT * FROM custom_fields WHERE module_name='plasmids' ORDER BY id";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$title.="\t".$match['field_name'];
	}

	$xls = implode("\r\n", $xls);

	$people_id=get_pid_from_username($_COOKIE['wy_user'] );
	$exportor = get_name_from_id('people',$people_id);

	$fileName ='Export-'.$module_name.'-'.date('Ymd').'.xls';
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$fileName");

	//echo "Export from database: ".mb_convert_encoding($module_name,"gb2312","utf-8")."\n";
	//echo "Expoet date: ".date('m/d/Y')."\n";
	//echo "Export by: ".mb_convert_encoding($exportor['name'],"gb2312","utf-8")."\n";
	//echo "Totally ".$num_rows." records."."\n\n";
	echo mb_convert_encoding($title,"gb2312","utf-8")."\n";
	echo mb_convert_encoding($xls,"gb2312","utf-8");
}
?>