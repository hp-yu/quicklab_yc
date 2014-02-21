<?php
include ("php/phprpc_server.php");
include("../includes.php");
function task_detail($id) {
	$db_conn= db_connect();
	$query = "SELECT * FROM planner_tasks WHERE id = $id";
	$rs = $db_conn -> query ($query);
	$row = $rs ->fetch_assoc();
	$task = array();
	$task['id']=$row['id'];
	$task['name']=$row['name'];
	$task['project']=$row['project'];
	$task['description']=$row['description'];
	$task['startdate']=$row['startdate'];
	$task['enddate']=$row['enddate'];
	$duration = (strtotime($row['enddate']) - strtotime($row['startdate']))/60/60/24+1;
	$task['duration']=$duration;
	$query= "select a.name from people a, planner_task_people b WHERE a.id=b.people_id AND b.task_id=$id ORDER BY CONVERT(a.name USING GBK)";
	$rs=$db_conn->query($query);
	while ($match=$rs->fetch_assoc()) {
		$task['people'] .= $match['name'].",";
	}
	$query= "select name from people WHERE id={$row['created_by']}";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	$task['created_by']=$match['name'];
	$query= "select name from people WHERE id={$row['updated_by']}";
	$rs=$db_conn->query($query);
	$match=$rs->fetch_assoc();
	$task['updated_by']=$match['name'];
	$task['date_update']=date("Y-m-d",strtotime($row['date_update']));
	$task['date_create']=date("Y-m-d",strtotime($row['date_create']));
	return $task;
}
$server = new PHPRPC_Server();
$server->add("task_detail");
$server->start();
?>