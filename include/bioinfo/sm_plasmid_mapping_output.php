<?php
if (!empty($_GET)) {
	//name, size, diameter
	$plasmid_map_name = $_GET['name'];
	$plasmid_map_size = $_GET['size'];
	$plasmid_map_diameter = $_GET['diameter'];
	//restriction sites
	$n=1;//the start restriction site id
	//clean all restriction sites first
	$plasmid_map_res = array();
	foreach ($_GET as $key=>$value) {
		if (strstr($key,"res_name_")&&$value!="") {
			$res_id=substr($key,9);//get the feature id
			//if not deleted
			if ($_GET["res_del_".$res_id]!=1) {
				$plasmid_map_res[$n]['id'] = $n;
				$plasmid_map_res[$n]['name'] = $value;
				$plasmid_map_res[$n]['site'] = $_GET["res_site_".$res_id];
				$n++;
			}
		}
	}
	//features
	$n=1; //the start feature id
	//clean all features first
	$plasmid_map_fea = array();
	foreach ($_GET as $key=>$value) {
		if (strstr($key,"fea_name_")&&$value!="") {
			$fea_id=substr($key,9);//get the feature id
			//if not deleted
			if ($_GET["fea_del_".$fea_id]!=1) {
				$plasmid_map_fea[$n]['id'] = $n;
				$plasmid_map_fea[$n]['name'] = $value;
				$plasmid_map_fea[$n]['start'] = $_GET["fea_start_".$fea_id];
				$plasmid_map_fea[$n]['end'] = $_GET["fea_end_".$fea_id];
				$plasmid_map_fea[$n]['ori'] = $_GET["fea_ori_".$fea_id];
				$plasmid_map_fea[$n]['color'] = $_GET["fea_color_".$fea_id];
				$n++;
			}
		}
	}
}

include("sm_plasmid_mapping_class.php");
$plasmid = new PlasmidMapDraw();
$plasmid->mName = $plasmid_map_name;
$plasmid->mSize = $plasmid_map_size;
$plasmid->mDiameter = $plasmid_map_diameter;
$plasmid->mRes = $plasmid_map_res;
$plasmid->mFea = $plasmid_map_fea;
header("Content-type: image/png");
$plasmid->OutputPng();
?>