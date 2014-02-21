<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Knowledge base</title>
<style>
TD {
	font-size: 8pt;
	font-family: verdana;
	text-decoration: none;
	white-space:nowrap;
	/*word-break:break-all; �Զ����� */
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
</head>

<body bgcolor=#D4D0C8 marginheight=0 marginwidth=0 topmargin=0 leftmargin=0>
<MAP NAME="ImageMap42788">
<!--<AREA SHAPE="rect" ALT="Import Bookmarks File" COORDS="0,0,16,15"    HREF="start_import.asp" target=_top>
<AREA SHAPE="rect" ALT="Export Bookmarks File" COORDS="22,0,38,15"   HREF="javascript:openWin('export_bookmarks.asp', 600, 400);">-->
<AREA SHAPE="rect" ALT="Add New Forder"        COORDS="36,0,52,15"   HREF="javascript:parent.parent.submitShowForm('NEWFOLDERFORM')"  target=basefrm>
<AREA SHAPE="rect" ALT="Edit Forder"         COORDS="60,1,75,15"   HREF="javascript:parent.parent.submitShowForm('EDITFOLDERFORM')" target=basefrm>
<AREA SHAPE="rect" ALT="Move Forder"           COORDS="84,2,100,15" HREF="javascript:parent.parent.submitShowForm('MOVEFOLDERFORM')" target=basefrm>
<AREA SHAPE="rect" ALT="Delete Forder"           COORDS="110,1,125,15"  HREF="javascript:parent.parent.submitShowForm('DELFOLDER')" target=basefrm>
<!--<AREA SHAPE="rect" ALT="Help Topics"           COORDS="288,0,301,15" HREF="javascript:openWin('help.html', 600, 400);">-->
</MAP>
<table width=100% cellpadding=2 cellspacing=0><tr style="border-style: outset; border-width:1"><td><img border=0 src="./tree/controlbuttons_2.gif" USEMAP="#ImageMap42788"></td>
<form name=form method="get" action="kbase_content.php" target="basefrm">
<td valign="middle" align=right width=100%>
<input align="absmiddle" type="text" name="keywords" value="<?php $_REQUEST['keywordds']?>" size="60"/>&nbsp;
<input type="submit" value="Search"/>&nbsp;
<input type="hidden" name="action" value="SEARCHFORM"/>
</td>
</form>
</tr>
</table>
</body>

</html>
