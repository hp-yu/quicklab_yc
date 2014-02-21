<?php
include('include/includes.php');
include('include/bioinfo/sm.php');
do_html_header('Plasmid mapping-Quicklab');
do_header();
$db_conn=db_connect();
?>
<script type="text/javascript" src="include/jquery/lib/jquery.js"></script>
<script type="text/javascript" src="include/jquery/jquery.colorpicker.js"></script>
<script type="text/javascript" src="include/jquery/jquery.bgiframe.js"></script>
<script type="text/javascript" src="include/jquery/jquery.dimensions.js"></script>
<script type="text/javascript" src="include/jquery/jquery.validate.js"></script>
<script type="text/javascript" src="include/bioinfo/sm_common.js"></script>
<script>
function cal_seq_len(){
	e1=document.getElementById('sequence');
	e2=document.getElementById('seq_len');
	len=removeuseless(e1.value).length;
	e2.innerHTML=len+' bp';
}
</script>
<script>
$(document).ready(function() {
	$('#colorholder').ColorHolderInit();
	$('.colorselector').ColorSelectorInit();
	$("#plasmid_map_form").validate({
		rules: {
			name: "required",
			size: {required: true,digits:true,min:10},
			orf_prot_size: {required: true,digits:true,min:10}
		},
		messages: {
		}});
	cal_seq_len();
});
</script>
</head>
</html>
<body>
<?php
/*
strategy:
	res and fea:
		POST to array
		array to string to GET, output
		output GET to array
		array to input
*/
//initiate plasmid mapping parameters
if (!isset($plasmid_map_name)) $plasmid_map_name = "";
if (!isset($plasmid_map_size)) $plasmid_map_size = "";
if (!isset($plasmid_map_diameter)) $plasmid_map_diameter = "250";
if (!isset($plasmid_map_res)) $plasmid_map_res = array();
if (!isset($plasmid_map_fea)) $plasmid_map_fea = array();
if (!isset($plasmid_map_insert)) $plasmid_map_insert = array();
//if submitted
if (!empty($_POST)) {
	//name, size, diameter
	$plasmid_map_name = $_POST['name'];
	$plasmid_map_size = $_POST['size'];
	$plasmid_map_diameter = $_POST['diameter'];
	//if use backbone stored in database;
	if ($_POST['backbone']!="") {
		$plasmid_id=$_POST['backbone'];
		$query="SELECT * FROM `plasmid_map` WHERE `plasmid_id`='$plasmid_id'";
		$rs=$db_conn->query($query);
		$match=$rs->fetch_assoc();
		$plasmid_map_size = $match['size'];
		//query restriction sites
		$query="SELECT * FROM `plasmid_map_res` WHERE `plasmid_id`='$plasmid_id' ORDER BY `site`";
		$rs=$db_conn->query($query);
		$match=array();
		$n=1;
		while($match=$rs->fetch_assoc()) {
			$plasmid_map_res[$n]['id'] = $n;
			$plasmid_map_res[$n]['name'] = $match['name'];
			$plasmid_map_res[$n]['site'] = $match['site'];
			$n++;
		}
		//query original features
		$query="SELECT * FROM `plasmid_map_fea` WHERE `plasmid_id`='$plasmid_id' AND `insert`=0 ORDER BY `start`";
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
		//query inserts
		$query="SELECT * FROM `plasmid_map_fea` WHERE `plasmid_id`='$plasmid_id' AND `insert`=1 ORDER BY `start`";
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
	} elseif ($_POST['draw_from_seq']==1) {  //else if draw from seqences
		$sequence=$_POST['sequence'];
		$sequence=sm_extract_sequences($sequence);
		$plasmid_map_size=strlen($sequence[0]["seq"]);
		//if only commercial available endonucleases
		if ($_POST['rs_commercial']==1) {
			$commercial_sql="AND `commercial`=1";
		} else {
			$commercial_sql="";
		}
		//end type 0=>unknown,1=>blunt end,2=>overhang end
		switch ($_POST['end_type']) {
			case 1:$end_type_sql="`end_type` LIKE '1'";break;
			case 2:$end_type_sql="`end_type` LIKE '2'";break;
			default:$end_type_sql="`end_type` LIKE '%'";break;
		}
		//query enzymes, put them into an array, (default prototype endonucleases)
		$query="SELECT * FROM `rebase` WHERE $end_type_sql AND `prototype`='' $commercial_sql ORDER BY `name`";
		$rs=$db_conn->query($query);
		while($match=$rs->fetch_assoc()) {
			$enzymes_array[$match['name']][0]=$match['pattern_upper'];
			$enzymes_array[$match['name']][1]=$match['length'];
			$enzymes_array[$match['name']][2]=$match['cut_site_upper'];
			if ($match['palindromic']==0) {
				$enzymes_array[$match['name']."@"][0]=$match['pattern_lower'];
				$enzymes_array[$match['name']."@"][1]=$match['length'];
				$enzymes_array[$match['name']."@"][2]=$match['cut_site_lower'];
			}
		}
		//get the cleavage positions using function sm_restriction_digest()
		$digestion[0]=sm_restriction_digest($enzymes_array,$sequence[0]["seq"]);
		$n=1;
		$plasmid_map_res = array();
		foreach ($digestion[0] as $key=>$value) {
			//only unique restriction sites
			if(count($value['cuts'])==1) {
				$plasmid_map_res[$n]['id'] = $n;
				$plasmid_map_res[$n]['name'] = $key;
				foreach ($value['cuts'] as $key2=>$value2) {
					$plasmid_map_res[$n]['site'] = $key2;
				}
				$n++;
			}
		}
		//get feature positions, not finished, need blast
		$plasmid_map_fea = array();
		$n=1;
		/*
		$query="SELECT * FROM `features` ORDER BY `name`";
		$rs=$db_conn->query($query);
		$features=array();
		while($match=$rs->fetch_assoc()) {
			$features[$n]['seq']=$match['seq'];
			$features[$n]['name']=$match['name'];
			$n++;
		}
		$plasmid_map_fea=sm_feature_finder($features,$sequence[0]["seq"]);
		*/
		//get the setting of minimum size of protein sequence
		$orf_prot_size=$_POST['orf_prot_size'];
		//search orf using function sm_orf_finder(), then merge 6 arrays together into $orf
		$orf[0]=sm_orf_finder($sequence[0]["seq"],1,0,1,$orf_prot_size);
		$orf[1]=sm_orf_finder($sequence[0]["seq"],1,1,1,$orf_prot_size);
		$orf[2]=sm_orf_finder($sequence[0]["seq"],1,2,1,$orf_prot_size);
		$orf[3]=sm_orf_finder($sequence[0]["seq"],1,0,2,$orf_prot_size);
		$orf[4]=sm_orf_finder($sequence[0]["seq"],1,1,2,$orf_prot_size);
		$orf[5]=sm_orf_finder($sequence[0]["seq"],1,2,2,$orf_prot_size);
		$orf=array_merge($orf[0],$orf[1],$orf[2],$orf[3],$orf[4],$orf[5]);

		//$n=sizeof($plasmid_map_fea);
		$k=1;
		foreach ($orf as $value) {
			$plasmid_map_fea[$n]['id']=$n;
			$plasmid_map_fea[$n]['name']="orf_".$k;
			$plasmid_map_fea[$n]['color']="rgb(255,0,0)";
			$plasmid_map_fea[$n]['end']=$value['end'];
			$plasmid_map_fea[$n]['start']=$value['start'];
			$plasmid_map_fea[$n]['ori']=$value['ori'];
			$n++;
			$k++;
		}
	} else {  //draw from manual input
		//get restriction sites
		$n=1;//the start restriction site id
		//clean all restriction sites first
		$plasmid_map_res = array();
		//existed restriction sites
		foreach ($_POST as $key=>$value) {
			if (strstr($key,"res_name_")&&strstr($key,"res_name_add")===false&&$value!="") {
				$res_id=substr($key,9);//get the feature id
				//if not deleted
				if ($_POST["res_del_".$res_id]!=1) {
					$plasmid_map_res[$n]['id'] = $n;
					$plasmid_map_res[$n]['name'] = $value;
					$plasmid_map_res[$n]['site'] = $_POST["res_site_".$res_id];
					$n++;
				}
			}
		}
		//added restriction site
		if($_POST['res_name_add']!=""&&$_POST['res_site_add']!="") {
			$plasmid_map_res[$n]['id'] = $n;
			$plasmid_map_res[$n]['name'] = $_POST['res_name_add'];
			$plasmid_map_res[$n]['site'] = $_POST['res_site_add'];
		}
		//get features
		$n=1; //the start feature id
		//clean all features first
		$plasmid_map_fea = array();
		//existed features
		foreach ($_POST as $key=>$value) {
			if (strstr($key,"fea_name_")&&strstr($key,"fea_name_add")===false&&$value!="") {
				$fea_id=substr($key,9);//get the feature id
				//if not deleted
				if ($_POST["fea_del_".$fea_id]!=1) {
					$plasmid_map_fea[$n]['id'] = $n;
					$plasmid_map_fea[$n]['name'] = $value;
					$plasmid_map_fea[$n]['start'] = $_POST["fea_start_".$fea_id];
					$plasmid_map_fea[$n]['end'] = $_POST["fea_end_".$fea_id];
					$plasmid_map_fea[$n]['ori'] = $_POST["fea_ori_".$fea_id];
					$plasmid_map_fea[$n]['color'] = $_POST["fea_color_".$fea_id];
					$n++;
				}
			}
		}
		//added feature
		if($_POST['fea_name_add']!=""&&$_POST['fea_start_add']!=""&&$_POST['fea_end_add']!=""&&$_POST['fea_ori_add']!="") {
			$plasmid_map_fea[$n]['id'] = $n;
			$plasmid_map_fea[$n]['name'] = $_POST['fea_name_add'];
			$plasmid_map_fea[$n]['start'] = $_POST['fea_start_add'];
			$plasmid_map_fea[$n]['end'] = $_POST['fea_end_add'];
			$plasmid_map_fea[$n]['ori'] = $_POST['fea_ori_add'];
			$plasmid_map_fea[$n]['color'] = $_POST['fea_color_add'];
		}
		//get inserts
		$n=1; //the start insert id
		//clean all inserts first
		$plasmid_map_insert = array();
		foreach ($_POST as $key=>$value) {
			if (strstr($key,"insert_name_")&&strstr($key,"insert_name_add")===false&&$value!="") {
				$insert_id=substr($key,12);//get the insert id
				//if not deleted
				if ($_POST["insert_del_".$insert_id]!=1) {
					$plasmid_map_insert[$n]['id'] = $n;
					$plasmid_map_insert[$n]['name'] = $value;
					$plasmid_map_insert[$n]['start'] = $_POST["insert_start_".$insert_id];
					$plasmid_map_insert[$n]['end'] = $_POST["insert_end_".$insert_id];
					$plasmid_map_insert[$n]['ori'] = $_POST["insert_ori_".$insert_id];
					$plasmid_map_insert[$n]['color'] = $_POST["insert_color_".$insert_id];
					$n++;
				}
			}
		}
		//added inserts
		if($_POST['insert_name_add']!=""&&$_POST['insert_start_add']!=""&&$_POST['insert_end_add']!=""&&$_POST['insert_ori_add']!="") {
			$plasmid_map_insert[$n]['id'] = $n;
			$plasmid_map_insert[$n]['name'] = $_POST['insert_name_add'];
			$plasmid_map_insert[$n]['start'] = $_POST['insert_start_add'];
			$plasmid_map_insert[$n]['end'] = $_POST['insert_end_add'];
			$plasmid_map_insert[$n]['ori'] = $_POST['insert_ori_add'];
			$plasmid_map_insert[$n]['color'] = $_POST['insert_color_add'];
		}
		//update database if saved
		if ($_POST['save']=="Draw and save"&&!empty($_GET['plasmid_id'])) {
			$plasmid_id=$_GET['plasmid_id'];
			$date=date('Y-m-d H:i:s');
			$query = "select * from `users` where `username` = '{$_COOKIE['wy_user']}'";
			$rs = $db_conn->query($query);
			$user=$rs->fetch_assoc();
			//start transaction
			$db_conn->autocommit(false);
			//update plasmid sequence
			$sequence=sm_remove_useless($_POST['sequence']);
			$query="DELETE FROM `plasmid_sequences` WHERE `plasmid_id`='$plasmid_id'";
			$db_conn->query($query);
			if(strlen($sequence)>0) {
				$query="INSERT INTO `plasmid_sequences` (`plasmid_id`,`sequence`) VALUES ('$plasmid_id','$sequence')";
				$db_conn->query($query);
			}
			//update or insert table plasmid_map
			$query="SELECT * FROM `plasmid_map` WHERE `plasmid_id`='$plasmid_id'";
			$rs=$db_conn->query($query);
			if ($rs->num_rows>0) {
				$query="UPDATE `plasmid_map` SET
			`name`='$plasmid_map_name',
			`size`='$plasmid_map_size',
			`diameter`='$plasmid_map_diameter',
			`updated_by`='{$user['people_id']}',
			`date_update`='$date'
			WHERE `plasmid_id`='$plasmid_id'";
			} else {
				$query="INSERT INTO `plasmid_map` (`plasmid_id`,`name`,`size`,`diameter`,`created_by`,`date_create`) VALUES ('$plasmid_id','$plasmid_map_name','$plasmid_map_size','$plasmid_map_diameter','{$user['people_id']}','$date')";
			}
			$db_conn->query($query);
			//update table plasmid_map_res
			$query="DELETE FROM `plasmid_map_res` WHERE `plasmid_id`='$plasmid_id'";
			$db_conn->query($query);
			foreach ($plasmid_map_res as $value) {
				$query="INSERT INTO `plasmid_map_res` (`plasmid_id`,`name`,`site`) VALUES ('$plasmid_id','{$value['name']}','{$value['site']}')";
				$db_conn->query($query);
			}
			//update table plasmid_map_fea
			$query="DELETE FROM `plasmid_map_fea` WHERE `plasmid_id`='$plasmid_id'";
			$db_conn->query($query);
			foreach ($plasmid_map_fea as $value) {
				$query="INSERT INTO `plasmid_map_fea` (`plasmid_id`,`name`,`color`,`start`,`end`,`ori`,`insert`) VALUES ('$plasmid_id','{$value['name']}','{$value['color']}','{$value['start']}','{$value['end']}','{$value['ori']}','0')";
				$rs=$db_conn->query($query);
				if (!$rs) {
					echo $query;
				}
			}
			foreach ($plasmid_map_insert as $value) {
				$query="INSERT INTO `plasmid_map_fea` (`plasmid_id`,`name`,`color`,`start`,`end`,`ori`,`insert`) VALUES ('$plasmid_id','{$value['name']}','{$value['color']}','{$value['start']}','{$value['end']}','{$value['ori']}','1')";
				$db_conn->query($query);
			}
			//finish transaction
			$db_conn->commit();
		}
	}
	//if not submitted
} else {
	//if the plasmid id exist
	if (!empty($_GET['plasmid_id'])) {
		$plasmid_id=$_GET['plasmid_id'];
		$query="SELECT * FROM `plasmid_map` WHERE `plasmid_id`='$plasmid_id'";
		$rs=$db_conn->query($query);
		$match=$rs->fetch_assoc();
		//if the plasmid map data existed
		if ($rs->num_rows>0) {
			$plasmid_map_name = $match['name'];
			$plasmid_map_size = $match['size'];
			$plasmid_map_diameter = $match['diameter'];
			//restriction sites
			$query="SELECT * FROM `plasmid_map_res` WHERE `plasmid_id`='$plasmid_id' ORDER BY `site`";
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
			$query="SELECT * FROM `plasmid_map_fea` WHERE `plasmid_id`='$plasmid_id' AND `insert`=0 ORDER BY `start`";
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
			$query="SELECT * FROM `plasmid_map_fea` WHERE `plasmid_id`='$plasmid_id' AND `insert`=1 ORDER BY `start`";
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
		} else {
			$query="SELECT `name` FROM `plasmids` WHERE `id`='$plasmid_id'";
			$rs=$db_conn->query($query);
			$match=$rs->fetch_assoc();
			$plasmid_map_name = $match['name'];
			$plasmid_map_diameter = "250";
		}
	}
}
?>
<form id="plasmid_map_form" method="POST" target="_self">
<table align="center" cellpadding="0" cellspacing="0">
<tr>
<td>
<!--box of map-->
<div id="plasmid_mapping_map">
<b>&nbsp;map:</b></br>
<?php
//output the image, transfer data using GET method
//name, size, diameter
$plasmid_map_get="name=".$plasmid_map_name."&size=".$plasmid_map_size."&diameter=".$plasmid_map_diameter;
//restriction sites
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
//output
if ($plasmid_map_name!=""&&$plasmid_map_size!=""&&$plasmid_map_diameter!="") {
	echo "<img src='include/bioinfo/sm_plasmid_mapping_output.php?".$plasmid_map_get."'/>";
}
?>
</div>
<!--box of sequence-->
<div id="plasmid_mapping_seq">
<b>&nbsp;sequence:&nbsp;&nbsp;</b>
<span id="seq_len" style="font-family: Courier New;background-color:yellow" /></span>
</br>
&nbsp;&nbsp;<textarea id="sequence"  name='sequence' class="sequence" cols="80" rows="6" onchange="tidyup('sequence');cal_seq_len()">
<?php
if (!empty($_POST['sequence'])) {
	echo $_POST['sequence'];
} elseif (!empty($_POST['backbone'])) {
	$query="SELECT * FROM ".TB_PLASMID_SEQUENCES." WHERE `plasmid_id`='{$_POST['backbone']}'";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	if(!empty($match['sequence'])) {echo sm_tidyup($match['sequence']);}
} elseif (!empty($_GET['plasmid_id'])) {
	$query="SELECT * FROM ".TB_PLASMID_SEQUENCES." WHERE `plasmid_id`='{$_POST['plasmid_id']}'";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	if(!empty($match['sequence'])) {echo sm_tidyup($match['sequence']);}
}
?>
</textarea>
</div>
</td>
<td valign="top">
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<!--box of basic settings-->
<div id="plasmid_mapping_basic">
<table>
<tr>
<td colspan="4"><b>Basic settings:</b></td>
</tr>
<tr>
<td>
Name:&nbsp;<input type="text" name="name" id="name" value="<?php echo $plasmid_map_name?>" size="20">&nbsp;
Size:&nbsp;<input type="text" name="size" id="size" value="<?php echo $plasmid_map_size?>" size="5">&nbsp;bp&nbsp;
Diameter:&nbsp;
<?php
$diameter=array("small"=>"150","medium"=>"250","large"=>"350");
echo array_select("diameter",$diameter,$plasmid_map_diameter);
?>
</td>
</tr>
<tr>
<td colspan="4"><b>Optional:</b></td>
</tr>
<tr>
<td colspan="4">Load&nbsp;
<?php
$query="SELECT * FROM `plasmid_map` ORDER BY `name`";
echo query_select_choose("backbone",$query,"plasmid_id","name","");
?>
&nbsp;as backbone
</br>
<b>Or</b> draw from sequence&nbsp;
<input type="checkbox" name="draw_from_seq" value="1"/>
</td>
</tr>
<tr>
<td>
<b>Draw from sequence settings:</b>
</td>
</tr>
<tr>
<td>
<b>Restriction sites finder:</b>
</br>
Type of restriction enzyme
<select name="end_type">
<option value="%">All
<option value="1">Blunt ends
<option value="2">Overhang end
</select>
</br>
Only commercial available endonucleases
<input type="checkbox" name="rs_commercial" value="1" checked/>
</br>
Only prototype endonucleases
<input type="checkbox" name="rs_prototype" value="1" checked disabled/>
</br>
Only unique restriction sites
<input type=checkbox name="rs_unique" value="1" checked disabled>
</br>
<b>ORFs finder:</b>
</br>
Minimum size of protein sequence <input type=text size=5 id="orf_prot_size" name="orf_prot_size" value="150">
</td>
</tr>
<tr>
<td>
</td>
</tr>
</table>
</div>
<!--box of restriction sites settings-->
<div id="plasmid_mapping_res">
<table>
<tr>
<td colspan="3"><b>Restriction sites:</b></td>
</tr>
<tr>
<td></td>
<td>Del?</td>
<td>Name:</td>
<td>Site:</td>
</tr>
<tr>
<td>Add</td>
<td></td>
<td>
<?php
$query="SELECT * FROM `rebase` WHERE `prototype`='' ORDER BY `name`";
$rs=$db_conn->query($query);
while($match=$rs->fetch_assoc()){
	$res[$match['name']]=$match['name'];
}
echo array_select("res_name_add",$res,"");
?>
</td>
<td><input type="text" name="res_site_add" value="" size="4"/></td>
</tr>
<?php
foreach ($plasmid_map_res as $key=>$value) {
	echo "<tr>";
	echo "<td>{$value['id']}</td>";
	echo "<td><input type = 'checkbox' name = 'res_del_{$value['id']}' value =1/></td>";
	echo "<td>";
	echo array_select("res_name_{$value['id']}",$res,$value['name']);
	//echo "<input type = 'text' name = 'res_name_{$value['id']}' value ='{$value['name']}' size = '10' class='required'/>";
	echo "</td>";
	echo "<td><input type = 'text' name = 'res_site_{$value['id']}' value ='{$value['site']}' size = '4'  class='required'/></td>";
	echo "<td><a href='rebase.php?keywords=".$value['name']."' target = '_blank'><img src='./assets/image/general/info-s.gif' alt='Restriction enzyme details' border='0'/></td>";
	echo "</tr>";
}
?>
</table>
</div>
</td>
</tr>
<td>
<!--box of features settings-->
<div id="plasmid_mapping_fea">
<table>
<tr>
<td colspan="5"><b>Original features:</b></td>
</tr>
<tr>
<td></td>
<td>Del?</td>
<td>Name:</td>
<td>Color:</td>
<td>Start:</td>
<td>End:</td>
<td>Orientation:</td>
</tr>
<tr>
<td>Add</td>
<td></td>
<td><input type="text" name="fea_name_add" value="" size="20"/></td>
<td>
<div class="colorselector"  ></div>
<input name="fea_color_add" type="hidden" value="rgb(0,0,0)" />
</td>
<td><input type="text" name="fea_start_add" value="" size="4"/></td>
<td><input type="text" name="fea_end_add" value="" size="4"/></td>
<td>
<?php
$ori=array("none"=>"0","clockwise"=>"1","counterclockwise"=>"2");
echo array_select("fea_ori_add",$ori,0);
?>
</td>
</tr>
<?php
foreach ($plasmid_map_fea as $key=>$value) {
	echo "<tr>";
	echo "<td>{$value['id']}</td>";
	echo "<td><input type = 'checkbox' name = 'fea_del_{$value['id']}' value =1/></td>";
	echo "<td><input type = 'text' name = 'fea_name_{$value['id']}' value ='{$value['name']}' size='20' class='required'/></td>";
	echo "<td><div class='colorselector' /></div><input type = 'hidden' name = 'fea_color_{$value['id']}' value ='{$value['color']}'/></td>";
	echo "<td><input type = 'text' name = 'fea_start_{$value['id']}' value ='{$value['start']}' size='4' class='required'/></td>";
	echo "<td><input type = 'text' name = 'fea_end_{$value['id']}' value ='{$value['end']}' size='4' class='required'/></td>";
	echo "<td>";
	$ori=array("none"=>"0","clockwise"=>"1","counterclockwise"=>"2");
	echo array_select("fea_ori_{$value['id']}",$ori,$value['ori']);
	echo "</td></tr>";
}
?>
</table>
</div>
</td>
<tr>
</tr>
<td>
<!--box of inserts settings-->
<div id="plasmid_mapping_insert">
<table>
<tr>
<td colspan="5"><b>Insert:</b></td>
</tr>
<tr>
<td></td>
<td>Del?</td>
<td>Name:</td>
<td>Color:</td>
<td>Start:</td>
<td>End:</td>
<td>Orientation:</td>
</tr>
<tr>
<td>Add</td>
<td></td>
<td><input type="text" name="insert_name_add" value="" size="20"/></td>
<td>
<div class="colorselector"  ></div>
<input name="insert_color_add" type="hidden" value="rgb(0,0,0)" />
</td>
<td><input type="text" name="insert_start_add" value="" size="4"/></td>
<td><input type="text" name="insert_end_add" value="" size="4"/></td>
<td>
<?php
$ori=array("none"=>"0","clockwise"=>"1","counterclockwise"=>"2");
echo array_select("insert_ori_add",$ori,0);
?>
</td>
</tr>
<?php
foreach ($plasmid_map_insert as $key=>$value) {
	echo "<tr>";
	echo "<td>{$value['id']}</td>";
	echo "<td><input type = 'checkbox' name = 'insert_del_{$value['id']}' value =1/></td>";
	echo "<td><input type = 'text' name = 'insert_name_{$value['id']}' value ='{$value['name']}' size='20' class='required'/></td>";
	echo "<td><div class='colorselector' /></div><input type = 'hidden' name = 'insert_color_{$value['id']}' value ='{$value['color']}'/></td>";
	echo "<td><input type = 'text' name = 'insert_start_{$value['id']}' value ='{$value['start']}' size='4' class='required'/></td>";
	echo "<td><input type = 'text' name = 'insert_end_{$value['id']}' value ='{$value['end']}' size='4' class='required'/></td>";
	echo "<td>";
	$ori=array("none"=>"0","clockwise"=>"1","counterclockwise"=>"2");
	echo array_select("insert_ori_{$value['id']}",$ori,$value['ori']);
	echo "</td></tr>";
}
?>
</table>
</div>
</td>
<tr>
</tr>
<tr>
<td colspan="2" align="center">
<input type="submit" value="Draw"/>&nbsp;
<?php
if (!empty($_REQUEST['plasmid_id'])) {
	echo "<input type='submit' name='save' value='Draw and save'/>&nbsp;";
}
?>
<input type="reset" value="Reset"/>
</td>
</tr>
</tr>
</table>
</td>

</table>
<div id="colorholder" name="colorholder" class="colorholder"></div>
</form>
<?php
//do_footer();
do_html_footer();
?>