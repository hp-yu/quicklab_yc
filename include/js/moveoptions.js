function move_options(e1, e2) {
	e1=document.getElementById(e1);
	e2=document.getElementById(e2);
	//count the number of selected options, if 0, alert and return.
	var n=0;
	for(var i=0;i<e1.options.length;i++){
		if(e1.options[i].selected){
			n++;
		}
	}
	if(n==0){
		alert("Please select the items to move first.");
		return;
	}
	//move options;
	for(var i=0;i<e2.options.length;i++){
		e2.options[i].selected=false;
	}
	for(var i=0;i<e1.options.length;i++){
		if(e1.options[i].selected){
			for (var n=0;n<e2.options.length;n++) {
				if(e2.options[n].text==e1.options[i].text)
				{return}
			}
			var o = e1.options[i];
			e2.options.add(new Option(o.text, o.value));
			e2.options[e2.options.length-1].selected=true;
		}
	}
}
function move_all_options(e1,e2){
	e1=document.getElementById(e1);
	e2=document.getElementById(e2);
	//count the number of options to move, if 0, alert and return.
	var n=e1.options.length;
	if(n==0){
		alert("There are no items to move.");
		return;
	}
	//move all options
	for(var i=0;i<e2.options.length;i++){
		e2.remove(i);
		i--;
	}
	for(var i=0;i<e1.options.length;i++){
		var o = e1.options[i];
		e2.options.add(new Option(o.text, o.value));
	}
}
function move_options_reverse(e1,e2) {
	e1=document.getElementById(e1);
	e2=document.getElementById(e2);
	//count the number of selected options, if 0, alert and return.
	var n=0;
	for(var i=0;i<e2.options.length;i++){
		if(e2.options[i].selected){
			n++;
		}
	}
	if(n==0){
		alert("Please select the items to move first.");
		return;
	}
	//move options
	for(var i=0;i<e1.options.length;i++){
		e1.options[i].selected=false;
	}
	for(var i=0;i<e2.options.length;i++){
		if(e2.options[i].selected){
			var o = e2.options[i];
			e1.options.add(new Option(o.text, o.value));
			e1.options[e1.options.length-1].selected=true;
			e2.remove(i);
			i--;
		}
	}
}
function move_all_options_reverse(e1,e2) {
	e1=document.getElementById(e1);
	e2=document.getElementById(e2);
	//count the number of options to move, if 0, alert and return.
	var n=e2.options.length;
	if(n==0){
		alert("There are no items to move.");
		return;
	}
	//move all options
	for(var i=0;i<e1.options.length;i++){
		e1.options[i].selected=false;
	}
	for(var i=0;i<e2.options.length;i++){
		var o = e2.options[i];
		e1.options.add(new Option(o.text, o.value));
		e2.remove(i);
		i--;
	}
}
function select_all_options(e) {
	e=document.getElementById(e);
	for(var i=0;i<e.options.length;i++){
		e.options[i].selected=true;
	}
}
function remove_options(e) {
	e=document.getElementById(e);
	//count the number of selected options, if 0, alert and return.
	var n=0;
	for(var i=0;i<e.options.length;i++){
		if(e.options[i].selected){
			n++;
		}
	}
	if(n==0){
		alert("Please select the items to move first.");
		return;
	}
	//remove options;
	for(var i=0;i<e.options.length;i++){
		if(e.options[i].selected){
			var o = e.options[i];
			e.remove(i);
			i--;
		}
	}
}
function remove_all_options(e){
	e=document.getElementById(e);
	//count the number of options to move, if 0, alert and return.
	var n=e.options.length;
	if(n==0){
		alert("There are no items to move.");
		return;
	}
	//remove all options
	for(var i=0;i<e.options.length;i++){
		var o = e.options[i];
		e.remove(i);
		i--;
	}
}