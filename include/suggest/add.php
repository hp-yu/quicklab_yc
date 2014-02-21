<?php
echo '
<link href="suggest.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="suggest.js"></script>
<div id="content" onclick="hideSuggestions();">
<input size="20" name="keyword" onchange = "handleKeyUp(event)" value="">&nbsp;&nbsp;
<div id="scroll">
<div id="suggest">
</div>
</div>
</div>  ';
?>