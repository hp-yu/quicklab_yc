<html>
<head><title>Control</title>

<style>
TD 
{
	font-size: 8pt; 
  font-family: verdana,helvetica; 
}
A  {
	text-decoration: none;
  color: blue
} 

A:hover {
  text-decoration: none;
  color: blue;
}
</style>

<script>
function openWin(file, width, height) {
    window.open(file, '_blank', 'width='+width+',height='+height+',menubar=yes,scrollbars=yes')
}
</script>
</head>

<body bgcolor=#D4D0C8 marginheight=0 marginwidth=0 topmargin=0 leftmargin=0>
<MAP NAME="ImageMap42788">
<!--<AREA SHAPE="rect" ALT="Import Bookmarks File" COORDS="0,0,16,15"    HREF="start_import.asp" target=_top>
<AREA SHAPE="rect" ALT="Export Bookmarks File" COORDS="22,0,38,15"   HREF="javascript:openWin('export_bookmarks.asp', 600, 400);">-->
<AREA SHAPE="rect" ALT="Add New Location"        COORDS="36,0,52,15"   HREF="javascript:parent.parent.submitShowForm('NEWFOLDERFORM')"  target=basefrm>
<AREA SHAPE="rect" ALT="Edit Location"         COORDS="60,1,75,15"   HREF="javascript:parent.parent.submitShowForm('EDITFOLDERFORM')" target=basefrm>
<AREA SHAPE="rect" ALT="Copy Location"           COORDS="84,2,99,15" HREF="javascript:parent.parent.submitShowForm('COPYFOLDERFORM')" target=basefrm>
<AREA SHAPE="rect" ALT="Move Location"           COORDS="107,1,122,15"  HREF="javascript:parent.parent.submitShowForm('MOVEFOLDERFORM')" target=basefrm>
<AREA SHAPE="rect" ALT="Delete Location"         COORDS="133,0,148,15" HREF="javascript:parent.parent.submitShowForm('DELFOLDER') " target=basefrm>
<AREA SHAPE="rect" ALT="Add New Box"          COORDS="173,0,188,15" HREF="javascript:parent.parent.submitShowForm('NEWLINKFORM')" target=basefrm>
<AREA SHAPE="rect" ALT="Edit Box"             COORDS="199,0,213,15" HREF="javascript:parent.parent.submitShowForm('EDITLINKFORM')" target=basefrm>
<AREA SHAPE="rect" ALT="Move Box To Another Location" COORDS="223,1,237,15" HREF="javascript:parent.parent.submitShowForm('MOVELINKFORM')" target=basefrm>
<AREA SHAPE="rect" ALT="Move Box To Another Location" COORDS="234,3,238,6"  HREF="javascript:parent.parent.submitShowForm('MOVELINKFORM')" target=basefrm>
<AREA SHAPE="rect" ALT="Delete Box"           COORDS="249,0,264,15" HREF="javascript:parent.parent.submitShowForm('DELLINK')" target=basefrm>
<!--<AREA SHAPE="rect" ALT="Help Topics"           COORDS="288,0,301,15" HREF="javascript:openWin('help.html', 600, 400);">-->
</MAP>
<table width=100% cellpadding=2 cellspacing=0><tr style="border-style: outset; border-width:1"><td><img border=0 src=./tree/controlbuttons.gif USEMAP="#ImageMap42788"></td>
<td valign=middle align=right width=100%>
<a href="javascript:parent.parent.submitShowForm('SEARCH')" target="basefrm">Seach by user&nbsp;&nbsp;</a>
</td> </tr>
</table>
</body>
 
</html>
