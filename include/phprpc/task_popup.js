//create phprpc client object rpc
var rpc_task_popup = new PHPRPC_Client('task_popup.php', ['task_detail']);
var dom = (document.getElementById) ? true : false;
var ns5 = (!document.all && dom || window.opera) ? true: false;
function describe_task(result,id) {
	var f=document.getElementById("ganttchart");
	var taskdiv = "<div id='task_popup_"+id+"' class = 'taskpopup' onmouseover = 'show_task("+id+")' onmouseout = 'hide_task("+id+")'><ul><li><b>"+result["name"]+"</b>&nbsp;&nbsp;<a onclick='edit_task_form("+result["project"]+","+id+")' style='cursor:pointer'/><img src='./assets/image/general/edit-s-t.gif' alt='Edit' title='Edit' border='0'/></a>&nbsp;&nbsp;<a onclick='delete_task_form("+result["project"]+","+id+")' style='cursor:pointer'/><img src='./assets/image/general/cancel-s-t.gif' alt='Delete' title='Delete' border='0'/></a>";
	if (result["description"]!="") {
		taskdiv +="</li><li>"+result["description"]+"";
	}
	taskdiv +="</li><li>"+result["startdate"]+" :: "+ result["enddate"]+" ("+result["duration"]+"d)</li><li>related people: </li><li>"+result["people"]+"</li><li>"+"created by: </li><li>"+result["created_by"]+" at "+result["date_create"];
	if(result["updated_by"]!=null) {
		taskdiv +="</li><li>updated by: </li><li>"+result["updated_by"]+" at "+result["date_update"];
	}
	taskdiv +="</li></ul></div>";
	f.innerHTML += taskdiv;
}
function get_task(id) {
	var f=document.getElementById("ganttchart");
	var task_popup = document.getElementById("task_popup_"+id);
	var parent = document.getElementById("task_bar_"+id);
	//create div if not exist
	if (task_popup == null) {
		rpc_task_popup.task_detail(id, function (result) {
			describe_task(result,id);
		});
	}
	//position and show div if exist
	if (task_popup != null) {
		var mouseX, mouseY;
		parent.onmousemove = function (evt) {
			mouseX = (ns5)? evt.pageX: window.event.clientX;
			mouseY = (ns5)? evt.pageY: window.event.clientY;
			task_popup.style.left=mouseX-2;
			task_popup.style.top=mouseY-2;
			task_popup.style.visibility="visible";
		}
	}
}
function hide_task(id) {
	var task_popup = document.getElementById("task_popup_"+id);
	if (task_popup != null) {
		//f.removeChild(task_popup);
		task_popup.style.visibility='hidden';
	}
}
function show_task(id) {
	var task_popup = document.getElementById("task_popup_"+id);
	if (task_popup != null) {
		//f.removeChild(task_popup);
		task_popup.style.visibility="visible";
	}
}