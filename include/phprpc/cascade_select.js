//create phprpc client object rpc
var rpc_cascade_select = new PHPRPC_Client('cascade_select.php', ['get_child','initiate']);

//clear select options
function clear_select(so) {
	for (var i = so.options.length - 1; i > -1; i--) {
		// some browser do not support options attribute remove method£¬use DOM removeChild method
		if (so.options.remove) {
			so.options.remove(i);
		} else {
			so.removeChild(so.options[i]);
		}
	}
}
/*set select options
so:select obj
d: data array
vf: option value field
tf: option text field
*/
function set_select(so, d, vf, tf, de) {
	var so = document.getElementById(so);
	if (d!=null) {
		var opt = document.createElement("option");
		opt.text = "- choose -";
		opt.value = "";
		if (so.options.add) {
			so.options.add(opt);
		} else {
			so.appendChild(opt);
		}
		for (var i = 0, n = d.length; i < n; i++) {
			var opt = document.createElement('option');
			opt.text = d[i][tf];
			opt.value = d[i][vf];
			if (so.options.add) {
				so.options.add(opt);
			} else {
				so.appendChild(opt);
			}
		}
		so.value = de;
	} else {
		so.style.display = "none";
	}
}

function change_select (pid , s) {
	var f=document.getElementById("cascade_select");
	//get this select id number, convert to the number format
	var this_id_num = Number(s.id.replace(/[^0-9]/g,''));
	//stat select num
	var num_select = Number (document.getElementById("num_select").value);
	var br = Number (document.getElementById("br").value);
	//remove redundant select
	if (num_select>this_id_num+1) {
		for (var r=0;r<(num_select-this_id_num-1);r++) {
			f.removeChild(document.getElementById("S"+(this_id_num+r+2)));
			document.getElementById("num_select").value--;
			if (br==1) {
				f.removeChild(document.getElementById("B"+(this_id_num+r+2)));
			}
		}
	}
	//if select option value is "", remove next select
	if (pid=="") {
		f.removeChild(document.getElementById("S"+(this_id_num+1)));
		document.getElementById("num_select").value--;
		if (br==1) {
			f.removeChild(document.getElementById("B"+(this_id_num+1)));
		}
	} else {
		//add next select if need
		if (num_select==this_id_num) {
			//remember select value, to prevent the loss of value after innerHTML(firefox)
			var v = [];
			for (var i=0;i<this_id_num;i++) {
				v[i] = document.getElementById("S"+(i+1)).value;
			}
			var add_id = "S"+(this_id_num + 1);
			if (br == 1) {
				var add_br_id = "B"+(this_id_num + 1);
				f.innerHTML += "<br id="+add_br_id+"><select id='"+add_id+"' name='"+add_id+"' onchange='change_select(this.value,this)'></select>";
			} else {
				f.innerHTML += "<select id='"+add_id+"' name='"+add_id+"' onchange='change_select(this.value,this)'></select>";
			}
			document.getElementById("num_select").value++;
			for (var i=0;i<this_id_num;i++) {
				document.getElementById("S"+(i+1)).value = v[i];
			}
		}
		//remove next select options, then add options
		var next_id="S"+(this_id_num+1);
		var so = document.getElementById(next_id);
		//if hide, display
		so.style.display = "";
		clear_select(so);
		//Callback function set_select
		rpc_cascade_select.get_child(pid, function (result) {
			set_select(next_id, result, 'id', 'name',"");
		});
	}
}

//execute after initiation of rpc client (useService)
rpc_cascade_select.onready = function () {
	var f=document.getElementById("cascade_select");
	var br = Number (document.getElementById("br").value);
	var location = document.getElementById("location");
	if ( location == null){
		id = "";
	} else {
		id = location.value;
	}
	rpc_cascade_select.initiate(id,br,function (result) {
		f.innerHTML += result;
	});
}