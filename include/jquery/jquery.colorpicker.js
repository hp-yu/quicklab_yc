/**
 *
 * Color picker
 * Author: Yu Haiping 2008-12-25
 *
 */
(function($) {
	var currentselector = "1";
	//initiate the colorpickerholder
 	$.fn.ColorHolderInit = function(options) {
 		var holder = $(this);
 		//initiate the colorpickerholdertable, 40 ms-excel like colors
 		var colortable;
 		colortable = "<table class='colorselecttable' cellspacing='3px' cellpadding='0px'>";
 		var colors = new Array('#000000','#993300','#333300','#003300','#003366','#000080','#333399','#333333','#800000','#FF6600','#808000','#008000','#008080','#0000FF','#666699','#808080','#FF0000','#FF9900','#99CC00','#339966','#33CCCC','#3366FF','#800080','#969696','#FF00FF','#FFCC00','#FFFF00','#00FF00','#00FFFF','#00CCFF','#993366','#C0C0C0','#FF99CC','#FFCC99','#FFFF99','#CCFFCC','#CCFFFF','#99CCFF','#CC99FF','#FFFFFF');
 		var num_colors = colors.length;
 		for (var i=0; i<num_colors; i++) {
 			if (i%8 == 0) {
 				colortable += "<tr>";
 			}
 			colortable += "<td><div style='background-color:"+colors[i]+"' class='colorselectdiv'></div></td>";
 			if ((i+1)%8 == 0) {
 				colortable += "</tr>";
 			}
 		}
 		colortable += "</table>";
 		// requires jquery.bgiframe plugin, help IE6
 		$(this).bgiframe();
 		$(this).hide();
 		$(this).html(colortable);
 		$('.colorselectdiv').click(function () {
 			currentselector.css('backgroundColor',$(this).css('backgroundColor'));
 			currentselector.css('color',$(this).css('backgroundColor'));
 			if ($(this).css('backgroundColor').indexOf("#") > -1) {
 				var value = "rgb(" + HexToRGB($(this).css('backgroundColor')) + ")";
 			} else {
 				var value = $(this).css('backgroundColor');
 			}
 			//currentselector.val(value);
 			currentselector.next().val(value);
 		});
		//fadeout when click the body
 		$('body').click(function() {
 			//alert(currentselector);
 			holder.fadeOut();
 		});
 	};
	//initiate the colorselector element , it's an input text
 	$.fn.ColorSelectorInit = function(options) {
 		//default settings
 		var defaults = {
 			color: '#000000',
 			holder: '#colorholder'
 		};
 		var opts = $.extend(defaults, options);
 		//after submit or first load
 		$(this).each(function () {
 			$(this).css('background-color', ($(this).next().val() == "")? opts.color : $(this).next().val());
 			$(this).css('color', ($(this).next().val() == "")? opts.color : $(this).next().val());
 		})
 		//when click this selector
 		$(this).click(function() {
 			currentselector = $(this);
 			// requires jquery.dimension plugin,
 			var offset = $(this).offset();
 			$(opts.holder).css({
 				top: (offset.top + $(this).outerHeight ()) + 'px',
 				left: offset.left + 'px'
 			});
			//show holder
 			$(opts.holder).fadeIn();
 			return false;
 		});
 	};

 	function RGBToHex (rgb) {
 		var hex = [
 		rgb.r.toString(16),
 		rgb.g.toString(16),
 		rgb.b.toString(16)
 		];
 		$.each(hex, function (nr, val) {
 			if (val.length == 1) {
 				hex[nr] = '0' + val;
 			}
 		});
 		return hex.join('');
 	};

 	function HexToRGB (hex) {
 		var hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
 		return (hex >> 16) + "," + ((hex & 0x00FF00) >> 8) + "," + (hex & 0x0000FF);
 	};

})(jQuery);
