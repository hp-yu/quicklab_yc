<?php
include ("php/phprpc_server.php");
include("../includes.php");
function get_child($pid) {
	$db_conn= db_connect();
	$query = "SELECT id,name FROM location WHERE pid = $pid";
	
	$rs = $db_conn -> query ($query);
	while ($row = $rs ->fetch_assoc()) {
		$child[]=$row;
	}
	return $child;
}

function initiate ($id,$br) {
	$path = array();
	$db_conn = db_connect();
	if ($id != "" && $id != 0) {
		//get the path array
		$n=0;
		while ($id != 1 ) {
			$query = "SELECT id,pid FROM location WHERE id=$id";
			
			$rs= $db_conn->query($query);
			$match=$rs->fetch_assoc();
			$path[$n]["id"] = $match["id"];
			$path[$n]["pid"] = $match["pid"];
			$id = $match["pid"];
			$n++;
		}
		//create html for js innerHTML
		$html .= "<input type = 'hidden' id = 'num_select' name = 'num_select' value = '".($n)."'/>";
		for ($m=$n-1;$m>=0;$m--) {
			if ($br == 1 && ($n-$m) !=1) {
				$html .= "<br id = 'B".($n-$m)."'>";
			}
			if ($br == 1) {
				$html .= "<select class='cascade_select_v' id ='S".($n-$m)."' name ='S".($n-$m)."' onchange='change_select(this.value,this)' >";
			} else {
				$html .= "<select class='cascade_select_h' id ='S".($n-$m)."' name ='S".($n-$m)."' onchange='change_select(this.value,this)' >";
			}
			$html .= "<option value = ''>- choose -</option>";
			$opt = get_child ($path[$m]['pid']);
			for ($i=0;$i<count($opt);$i++) {
				$html .= "<option value = '".$opt[$i]["id"]."' ";
				if ($opt[$i]["id"] == $path[$m]['id']) {
					$html .= "selected";
				}
				$html .= ">".$opt[$i]["name"]."</option>";
			}
			$html .= "</select>";
		}
	} else {
		$html .= "<input type = 'hidden' id = 'num_select' name = 'num_select' value = '1'/>";
		if ($br == 1) {
			$html .= "<select class='cascade_select_v' id ='S1' name ='S1' onchange='change_select(this.value,this)' >";
		} else {
			$html .= "<select class='cascade_select_h' id ='S1' name ='S1' onchange='change_select(this.value,this)' >";
		}
		$html .= "<option value = ''>- choose -</option>";
		$opt = get_child (1);
		for ($i=0;$i<count($opt);$i++) {
			$html .= "<option value = '".$opt[$i]["id"]."' >".$opt[$i]["name"]."</option>";
		}
		$html .= "</select>";
	}
	return $html;
}
$server = new PHPRPC_Server();
$server->add("get_child");
$server->add("initiate");
$server->start();
?>