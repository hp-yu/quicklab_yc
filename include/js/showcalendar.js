function showcalendar(obj) {
	var dv=window.showModalDialog("include/calendar.htm","44","center:1;help:no;status:no;dialogHeight:246px;dialogWidth:216px;scroll:no")
  if (dv) {if (dv=="null") obj.value='';else obj.value=dv;}
}