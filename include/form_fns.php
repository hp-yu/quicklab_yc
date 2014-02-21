<?php
function move_options_table($left,$right) {
	?>
<table>
<tr>
<td colspan="2">available options:</td>
<td>selected options:</td>
</tr>
<tr>
<td>
<select name="<?php echo $left['name'] ?>" id="<?php echo $left['name'] ?>" style="width:<?php echo $left['width'] ?>px;" size="<?php echo $left['size'] ?>" multiple ondblclick="move_options('<?php echo $left['name'] ?>', '<?php echo $right['name'] ?>')">
	<?php
	foreach ($left['content'] as $value=>$text) {
		echo "<option value='$value' >".$text."</option>";
	}
 	?>
</select>
</td>
<td align="center">
<a onclick="move_options('<?php echo $left['name'] ?>', '<?php echo $right['name'] ?>')" style="cursor:pointer" title="move"/>
>
</a>
</br>
</br>
<a onclick="move_all_options('<?php echo $left['name'] ?>', '<?php echo $right['name'] ?>')" style="cursor:pointer" title="move all"/>
>>
</a>
</br>
</br>
<a onclick="remove_options('<?php echo $right['name'] ?>')" style="cursor:pointer" title="remove"/>
<
</a>
</br>
</br>
<a onclick="remove_all_options('<?php echo $right['name'] ?>')" style="cursor:pointer" title="remove all"/>
<<
</a>
</br>
</td>
<td>
<select name="<?php echo $right['name'] ?>" id="<?php echo $right['name'] ?>" style="width:<?php echo $right['width'] ?>px;" size="<?php echo $right['size'] ?>" multiple
ondblclick="remove_options('<?php echo $right['name'] ?>')">
	<?php
	if (count($right['content'])>0) {
		foreach ($right['content'] as $value=>$text) {
			echo "<option value='$value' >".$text."</option>";
		}
	}
 	?>
</select>
</td>
</tr>
</table>
	<?php
}
?>