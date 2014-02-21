<?php
//���js�˵�����(�ݹ�)
//$db_conn��Ҫ���ں������棬��ֹl�ӹ�ർ��mysqḻ��
function make_js_menu_array($pid,$db_conn)
{
	$query = "select * from location where pid = '$pid' order by name";
	$rs= $db_conn->query($query);
	$rs_num = $rs->num_rows;
	if($rs_num)
	{
		$menu='';
		for ($i=0;$i< $rs_num;$i++)
		{
			$row=$rs->fetch_array();
			$new_pid=$row['id'];
			//$pcode=$row['pcode'];
			$name=$row['name'];
			//echo $pid.'
			$sub_menu=make_js_menu_array($new_pid,$db_conn);
			$douhao=($i==($rs_num-1))?"":",";
			if($sub_menu)
			{
				$menu=$menu."'".$name."','".$new_pid."',\n[".$sub_menu."]".$douhao."\n";
			}
			else
			{
				$menu=$menu."'".$name."','".$new_pid."',null".$douhao."\n";
			}
		}
	}
	else
	{
		return false;
	}
	return $menu;
}
//��module_id��item_id��ü�¼��������storage��order��

function getPaths($id)
{
	//dim rsHits, queryString, auxStr, parentId
	$auxStr="";
	$db_conn = db_connect();
	while ($id != 0 &&$id != 1)
	{
		$queryString = "SELECT pid, name FROM location WHERE ((id=".$id."))";
		$rs= $db_conn->query($queryString);
		$rsHits=$rs->fetch_assoc();
		if ($auxStr == "" )
		{
			$auxStr = $rsHits['name'];
		}
		else
		{
			$auxStr = $rsHits['name'] . " > " . $auxStr;
		}
		$id = $rsHits['pid'];
	}
	return $auxStr;
}
function getLids($id)
{
	//Lids=location ids
	$auxStr="";
	while ($id != 0 &&$id != 1)
	{
		$db_conn = db_connect();
		$queryString = "SELECT id,pid FROM location WHERE ((id=".$id."))";
		$rs= $db_conn->query($queryString);
		$rsHits=$rs->fetch_assoc();
		if ($auxStr == "" )
		{
			$auxStr = $rsHits['id'];
		}
		else
		{
			$auxStr = $rsHits['id'] . "," . $auxStr;
		}
		$id = $rsHits['pid'];
	}
	return $auxStr;
}
?>