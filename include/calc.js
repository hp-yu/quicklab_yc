function calc() {
	var e=document.getElementById('stock_type_form');
	for(var i = 0; i < e.length; i++) {
		if(e[i].checked) {
			var stock_type=e[i].value;
		}
	}
	if (stock_type=='1') {
		var e1=document.getElementById('mw_value');
		var e2=document.getElementById('mw_unit');
		var e3=document.getElementById('stock_purity_value');
		var e4=document.getElementById('stock_purity_unit');
		var e5=document.getElementById('final_conc_value');
		var e6=document.getElementById('final_conc_unit');
		var e7=document.getElementById('final_volume_value');
		var e8=document.getElementById('final_volume_unit');
		var e9=document.getElementById('stock_weight_value');
		var e10=document.getElementById('stock_weight_unit');
		var e=document.getElementById('powder_form');
		for(var i = 0; i < e.length; i++) {
			if(e[i].checked&&e[i].name=='calc_which') {
				var calc_which=e[i].value;
			}
		}
		for(var i=0;i<e6.options.length;i++){
			if(e6.options[i].selected) {
				var final_conc_unit = e6.options[i].text;
			}
		}
		for(var i = 0; i < e.length; i++) {
			if(e[i].type=='text') {
				e[i].readOnly =false;
				e[i].style.background="";
			}
		}
		if (e3.value>100) e3.value=100;
		if (calc_which=='5') {
			document.getElementById("stock_weight_value").readOnly =true;
			document.getElementById("stock_weight_value").style.background="yellow";
			if (e1.value!=''&&e3.value!=''&&e5.value!=''&&e7.value!=''&&(final_conc_unit=='M'||final_conc_unit=='mM'||final_conc_unit=='μM'||final_conc_unit=='nM')) {
				e9.value=(e1.value*e2.value*e5.value*e6.value*e7.value*e8.value)/(e10.value*e3.value*e4.value);
			}
			else if
			(e3.value!=''&&e5.value!=''&&e7.value!=''&&(final_conc_unit=='% (w/v)'||final_conc_unit=='g/mL'||final_conc_unit=='mg/mL (g/L)'||final_conc_unit=='μg/mL (mg/L)'||final_conc_unit=='ng/mL (μg/L)'||final_conc_unit=='ppm')) {
				e9.value=(e5.value*e6.value*e7.value*e8.value)/(e10.value*e3.value*e4.value);
			}
			else {
				e9.value='';
			}
		}
		if (calc_which=='4') {
			document.getElementById("final_volume_value").readOnly =true;
			document.getElementById("final_volume_value").style.background="yellow";
			if (e1.value!=''&&e3.value!=''&&e5.value!=''&&e9.value!=''&&(final_conc_unit=='M'||final_conc_unit=='mM'||final_conc_unit=='μM'||final_conc_unit=='nM')) {
				e7.value=(e9.value*e10.value*e3.value*e4.value)/(e1.value*e2.value*e5.value*e6.value*e8.value)
			}
			else if
			(e3.value!=''&&e5.value!=''&&e9.value!=''&&(final_conc_unit=='% (w/v)'||final_conc_unit=='g/mL'||final_conc_unit=='mg/mL (g/L)'||final_conc_unit=='μg/mL (mg/L)'||final_conc_unit=='ng/mL (μg/L)'||final_conc_unit=='ppm')) {
				e7.value=(e9.value*e10.value*e3.value*e4.value)/(e5.value*e6.value*e8.value)
			}
			else {
				e7.value='';
			}
		}
		if (calc_which=='3') {
			document.getElementById("final_conc_value").readOnly =true;
			document.getElementById("final_conc_value").style.background="yellow";
			if (e1.value!=''&&e3.value!=''&&e7.value!=''&&e9.value!=''&&(final_conc_unit=='M'||final_conc_unit=='mM'||final_conc_unit=='μM'||final_conc_unit=='nM')) {
				e5.value=(e9.value*e10.value*e3.value*e4.value)/(e1.value*e2.value*e6.value*e7.value*e8.value)
			}
			else if
			(e3.value!=''&&e7.value!=''&&e9.value!=''&&(final_conc_unit=='% (w/v)'||final_conc_unit=='g/mL'||final_conc_unit=='mg/mL (g/L)'||final_conc_unit=='μg/mL (mg/L)'||final_conc_unit=='ng/mL (μg/L)'||final_conc_unit=='ppm')) {
				e5.value=(e9.value*e10.value*e3.value*e4.value)/(e6.value*e7.value*e8.value)
			}
			else {
				e5.value='';
			}
		}
		if (calc_which=='2') {
			document.getElementById("stock_purity_value").readOnly =true;
			document.getElementById("stock_purity_value").style.background="yellow";
			if (e1.value!=''&&e5.value!=''&&e7.value!=''&&e9.value!=''&&(final_conc_unit=='M'||final_conc_unit=='mM'||final_conc_unit=='μM'||final_conc_unit=='nM')) {
				e3.value=(e1.value*e2.value*e5.value*e6.value*e7.value*e8.value)/(e4.value*e9.value*e10.value)
				if (e3.value>100)
				e3.value="false"
			}
			else if
			(e5.value!=''&&e7.value!=''&&e9.value!=''&&(final_conc_unit=='% (w/v)'||final_conc_unit=='g/mL'||final_conc_unit=='mg/mL (g/L)'||final_conc_unit=='μg/mL (mg/L)'||final_conc_unit=='ng/mL (μg/L)'||final_conc_unit=='ppm')) {
				e3.value=(e5.value*e6.value*e7.value*e8.value)/(e4.value*e9.value*e10.value)
				if (e3.value>100)
				e3.value="false";
			}
			else {
				e3.value='';
			}
		}
		if (calc_which=='1') {
			document.getElementById("mw_value").readOnly =true;
			document.getElementById("mw_value").style.background="yellow";
			if (e3.value!=''&&e5.value!=''&&e7.value!=''&&e9.value!=''&&(final_conc_unit=='M'||final_conc_unit=='mM'||final_conc_unit=='μM'||final_conc_unit=='nM')) {
				e1.value=(e9.value*e10.value*e3.value*e4.value)/(e2.value*e5.value*e6.value*e7.value*e8.value)
			}
			else {
				e1.value='';
			}
		}
	}
	if (stock_type=='2') {
		var e1=document.getElementById('mw_value_liquid');
		var e2=document.getElementById('mw_unit_liquid');
		var e3=document.getElementById('stock_conc_value_liquid');
		var e4=document.getElementById('stock_conc_unit_liquid');
		var e5=document.getElementById('final_conc_value_liquid');
		var e6=document.getElementById('final_conc_unit_liquid');
		var e7=document.getElementById('final_volume_value_liquid');
		var e8=document.getElementById('final_volume_unit_liquid');
		var e9=document.getElementById('stock_volume_value_liquid');
		var e10=document.getElementById('stock_volume_unit_liquid');
		var e=document.getElementById('liquid_form');
		for(var i = 0; i < e.length; i++) {
			if(e[i].checked&&e[i].name=='calc_which_liquid') {
				var calc_which_liquid=e[i].value;
			}
		}
		for(var i=0;i<e4.options.length;i++){
			if(e4.options[i].selected) {
				var stock_conc_unit_liquid = e4.options[i].text;
			}
		}
		for(var i=0;i<e6.options.length;i++){
			if(e6.options[i].selected) {
				var final_conc_unit_liquid = e6.options[i].text;
			}
		}
		for(var i = 0; i < e.length; i++) {
			if(e[i].type=='text') {
				e[i].readOnly =false;
				e[i].style.background="";
			}
		}
		if (calc_which_liquid=='5') {
			document.getElementById("stock_volume_value_liquid").readOnly =true;
			document.getElementById("stock_volume_value_liquid").style.background="yellow";
			if (e1.value!=''&&e3.value!=''&&e5.value!=''&&e7.value!=''&&(stock_conc_unit_liquid=='M'||stock_conc_unit_liquid=='mM'||stock_conc_unit_liquid=='μM'||stock_conc_unit_liquid=='nM')&&(final_conc_unit_liquid=='% (w/v)'||final_conc_unit_liquid=='g/mL'||final_conc_unit_liquid=='mg/mL (g/L)'||final_conc_unit_liquid=='μg/mL (mg/L)'||final_conc_unit_liquid=='ng/mL (μg/L)'||final_conc_unit_liquid=='ppm')) {
				e9.value=(e5.value*e6.value*e7.value*e8.value)/(e10.value*e1.value*e2.value*e3.value*e4.value);
			}
			else if (e1.value!=''&&e3.value!=''&&e5.value!=''&&e7.value!=''&&(stock_conc_unit_liquid=='% (w/v)'||stock_conc_unit_liquid=='g/mL'||stock_conc_unit_liquid=='mg/mL (g/L)'||stock_conc_unit_liquid=='μg/mL (mg/L)'||stock_conc_unit_liquid=='ng/mL (μg/L)'||stock_conc_unit_liquid=='ppm')&&(final_conc_unit_liquid=='M'||final_conc_unit_liquid=='mM'||final_conc_unit_liquid=='μM'||final_conc_unit_liquid=='nM')) {
				e9.value=(e1.value*e2.value*e5.value*e6.value*e7.value*e8.value)/(e10.value*e3.value*e4.value);
			}
			else if
			(e3.value!=''&&e5.value!=''&&e7.value!=''&&(((stock_conc_unit_liquid=='% (w/v)'||stock_conc_unit_liquid=='g/mL'||stock_conc_unit_liquid=='mg/mL (g/L)'||stock_conc_unit_liquid=='μg/mL (mg/L)'||stock_conc_unit_liquid=='ng/mL (μg/L)'||stock_conc_unit_liquid=='ppm')&&(final_conc_unit_liquid=='% (w/v)'||final_conc_unit_liquid=='g/mL'||final_conc_unit_liquid=='mg/mL (g/L)'||final_conc_unit_liquid=='μg/mL (mg/L)'||final_conc_unit_liquid=='ng/mL (μg/L)'||final_conc_unit_liquid=='ppm'))||((stock_conc_unit_liquid=='M'||stock_conc_unit_liquid=='mM'||stock_conc_unit_liquid=='μM'||stock_conc_unit_liquid=='nM')&&(final_conc_unit_liquid=='M'||final_conc_unit_liquid=='mM'||final_conc_unit_liquid=='μM'||final_conc_unit_liquid=='nM'))||((stock_conc_unit_liquid=='% (v/v)'||stock_conc_unit_liquid=='mL/L')&&(final_conc_unit_liquid=='% (v/v)'||final_conc_unit_liquid=='mL/L'))||(stock_conc_unit_liquid=='×'&&final_conc_unit_liquid=='×'))) {
				e9.value=(e5.value*e6.value*e7.value*e8.value)/(e10.value*e3.value*e4.value);
			}
			else if (e5.value!=''&&e7.value!=''&&(final_conc_unit_liquid=='% (v/v)'||final_conc_unit_liquid=='mL/L')) {
				e9.value=(e5.value*e6.value*e7.value*e8.value)/(e10.value);
			}
			else if (e3.value!=''&&e7.value!=''&&stock_conc_unit_liquid=='×'&&final_conc_unit_liquid!='×') {
				e9.value=(e7.value*e8.value)/(e10.value*e3.value*e4.value);
			}
			else {
				e9.value='';
			}
		}
		if (calc_which_liquid=='4') {
			document.getElementById("final_volume_value_liquid").readOnly =true;
			document.getElementById("final_volume_value_liquid").style.background="yellow";
			if (e1.value!=''&&e3.value!=''&&e5.value!=''&&e9.value!=''&&(stock_conc_unit_liquid=='M'||stock_conc_unit_liquid=='mM'||stock_conc_unit_liquid=='μM'||stock_conc_unit_liquid=='nM')&&(final_conc_unit_liquid=='% (w/v)'||final_conc_unit_liquid=='g/mL'||final_conc_unit_liquid=='mg/mL (g/L)'||final_conc_unit_liquid=='μg/mL (mg/L)'||final_conc_unit_liquid=='ng/mL (μg/L)'||final_conc_unit_liquid=='ppm')) {
				e7.value=(e9.value*e10.value*e1.value*e2.value*e3.value*e4.value)/(e5.value*e6.value*e8.value);
			}
			else if (e1.value!=''&&e3.value!=''&&e5.value!=''&&e9.value!=''&&(stock_conc_unit_liquid=='% (w/v)'||stock_conc_unit_liquid=='g/mL'||stock_conc_unit_liquid=='mg/mL (g/L)'||stock_conc_unit_liquid=='μg/mL (mg/L)'||stock_conc_unit_liquid=='ng/mL (μg/L)'||stock_conc_unit_liquid=='ppm')&&(final_conc_unit_liquid=='M'||final_conc_unit_liquid=='mM'||final_conc_unit_liquid=='μM'||final_conc_unit_liquid=='nM')) {
				e7.value=(e9.value*e10.value*e3.value*e4.value)/(e1.value*e2.value*e5.value*e6.value*e8.value);
			}
			else if
			(e3.value!=''&&e5.value!=''&&e9.value!=''&&(((stock_conc_unit_liquid=='% (w/v)'||stock_conc_unit_liquid=='g/mL'||stock_conc_unit_liquid=='mg/mL (g/L)'||stock_conc_unit_liquid=='μg/mL (mg/L)'||stock_conc_unit_liquid=='ng/mL (μg/L)'||stock_conc_unit_liquid=='ppm')&&(final_conc_unit_liquid=='% (w/v)'||final_conc_unit_liquid=='g/mL'||final_conc_unit_liquid=='mg/mL (g/L)'||final_conc_unit_liquid=='μg/mL (mg/L)'||final_conc_unit_liquid=='ng/mL (μg/L)'||final_conc_unit_liquid=='ppm'))||((stock_conc_unit_liquid=='M'||stock_conc_unit_liquid=='mM'||stock_conc_unit_liquid=='μM'||stock_conc_unit_liquid=='nM')&&(final_conc_unit_liquid=='M'||final_conc_unit_liquid=='mM'||final_conc_unit_liquid=='μM'||final_conc_unit_liquid=='nM'))||(stock_conc_unit_liquid=='×'&&final_conc_unit_liquid=='×'))) {
				e7.value=(e9.value*e10.value*e3.value*e4.value)/(e5.value*e6.value*e8.value);
			}
			else if (e5.value!=''&&e9.value!=''&&(final_conc_unit_liquid=='% (v/v)'||final_conc_unit_liquid=='mL/L')) {
				e7.value=(e9.value*e10.value)/(e5.value*e6.value*e8.value);
			}
			else if (e3.value!=''&&e9.value!=''&&stock_conc_unit_liquid=='×'&&final_conc_unit_liquid!='×') {
				e7.value=(e9.value*e10.value*e3.value*e4.value)/(e8.value);
			}
			else {
				e7.value='';
			}
		}
		if (calc_which_liquid=='3') {
			document.getElementById("final_conc_value_liquid").readOnly =true;
			document.getElementById("final_conc_value_liquid").style.background="yellow";
			if (e1.value!=''&&e3.value!=''&&e7.value!=''&&e9.value!=''&&(stock_conc_unit_liquid=='M'||stock_conc_unit_liquid=='mM'||stock_conc_unit_liquid=='μM'||stock_conc_unit_liquid=='nM')&&(final_conc_unit_liquid=='% (w/v)'||final_conc_unit_liquid=='g/mL'||final_conc_unit_liquid=='mg/mL (g/L)'||final_conc_unit_liquid=='μg/mL (mg/L)'||final_conc_unit_liquid=='ng/mL (μg/L)'||final_conc_unit_liquid=='ppm')) {
				e5.value=(e9.value*e10.value*e1.value*e2.value*e3.value*e4.value)/(e6.value*e7.value*e8.value);
			}
			else if (e1.value!=''&&e3.value!=''&&e7.value!=''&&e9.value!=''&&(stock_conc_unit_liquid=='% (w/v)'||stock_conc_unit_liquid=='g/mL'||stock_conc_unit_liquid=='mg/mL (g/L)'||stock_conc_unit_liquid=='μg/mL (mg/L)'||stock_conc_unit_liquid=='ng/mL (μg/L)'||stock_conc_unit_liquid=='ppm')&&(final_conc_unit_liquid=='M'||final_conc_unit_liquid=='mM'||final_conc_unit_liquid=='μM'||final_conc_unit_liquid=='nM')) {
				e5.value=(e9.value*e10.value*e3.value*e4.value)/(e1.value*e2.value*e6.value*e7.value*e8.value);
			}
			else if
			(e3.value!=''&&e7.value!=''&&e9.value!=''&&(((stock_conc_unit_liquid=='% (w/v)'||stock_conc_unit_liquid=='g/mL'||stock_conc_unit_liquid=='mg/mL (g/L)'||stock_conc_unit_liquid=='μg/mL (mg/L)'||stock_conc_unit_liquid=='ng/mL (μg/L)'||stock_conc_unit_liquid=='ppm')&&(final_conc_unit_liquid=='% (w/v)'||final_conc_unit_liquid=='g/mL'||final_conc_unit_liquid=='mg/mL (g/L)'||final_conc_unit_liquid=='μg/mL (mg/L)'||final_conc_unit_liquid=='ng/mL (μg/L)'||final_conc_unit_liquid=='ppm'))||((stock_conc_unit_liquid=='M'||stock_conc_unit_liquid=='mM'||stock_conc_unit_liquid=='μM'||stock_conc_unit_liquid=='nM')&&(final_conc_unit_liquid=='M'||final_conc_unit_liquid=='mM'||final_conc_unit_liquid=='μM'||final_conc_unit_liquid=='nM'))||(stock_conc_unit_liquid=='×'&&final_conc_unit_liquid=='×'))) {
				e5.value=(e9.value*e10.value*e3.value*e4.value)/(e6.value*e7.value*e8.value);
			}
			else if (e7.value!=''&&e9.value!=''&&(final_conc_unit_liquid=='% (v/v)'||final_conc_unit_liquid=='mL/L')) {
				e5.value=(e9.value*e10.value)/(e6.value*e7.value*e8.value);
			}
			else {
				e5.value='';
			}
		}
		if (calc_which_liquid=='2') {
			document.getElementById("stock_conc_value_liquid").readOnly =true;
			document.getElementById("stock_conc_value_liquid").style.background="yellow";
			if (e1.value!=''&&e5.value!=''&&e7.value!=''&&e9.value!=''&&(stock_conc_unit_liquid=='M'||stock_conc_unit_liquid=='mM'||stock_conc_unit_liquid=='μM'||stock_conc_unit_liquid=='nM')&&(final_conc_unit_liquid=='% (w/v)'||final_conc_unit_liquid=='g/mL'||final_conc_unit_liquid=='mg/mL (g/L)'||final_conc_unit_liquid=='μg/mL (mg/L)'||final_conc_unit_liquid=='ng/mL (μg/L)'||final_conc_unit_liquid=='ppm')) {
				e3.value=(e5.value*e6.value*e7.value*e8.value)/(e9.value*e10.value*e1.value*e2.value*e4.value);
			}
			else if (e1.value!=''&&e5.value!=''&&e7.value!=''&&e9.value!=''&&(stock_conc_unit_liquid=='% (w/v)'||stock_conc_unit_liquid=='g/mL'||stock_conc_unit_liquid=='mg/mL (g/L)'||stock_conc_unit_liquid=='μg/mL (mg/L)'||stock_conc_unit_liquid=='ng/mL (μg/L)'||stock_conc_unit_liquid=='ppm')&&(final_conc_unit_liquid=='M'||final_conc_unit_liquid=='mM'||final_conc_unit_liquid=='μM'||final_conc_unit_liquid=='nM')) {
				e3.value=(e1.value*e2.value*e5.value*e6.value*e7.value*e8.value)/(e9.value*e10.value*e4.value);
			}
			else if
			(e5.value!=''&&e7.value!=''&&e9.value!=''&&(((stock_conc_unit_liquid=='% (w/v)'||stock_conc_unit_liquid=='g/mL'||stock_conc_unit_liquid=='mg/mL (g/L)'||stock_conc_unit_liquid=='μg/mL (mg/L)'||stock_conc_unit_liquid=='ng/mL (μg/L)'||stock_conc_unit_liquid=='ppm')&&(final_conc_unit_liquid=='% (w/v)'||final_conc_unit_liquid=='g/mL'||final_conc_unit_liquid=='mg/mL (g/L)'||final_conc_unit_liquid=='μg/mL (mg/L)'||final_conc_unit_liquid=='ng/mL (μg/L)'||final_conc_unit_liquid=='ppm'))||((stock_conc_unit_liquid=='M'||stock_conc_unit_liquid=='mM'||stock_conc_unit_liquid=='μM'||stock_conc_unit_liquid=='nM')&&(final_conc_unit_liquid=='M'||final_conc_unit_liquid=='mM'||final_conc_unit_liquid=='μM'||final_conc_unit_liquid=='nM'))||(stock_conc_unit_liquid=='×'&&final_conc_unit_liquid=='×'))) {
				e3.value=(e5.value*e6.value*e7.value*e8.value)/(e9.value*e10.value*e4.value);
			}
			else if (e7.value!=''&&e9.value!=''&&stock_conc_unit_liquid=='×'&&final_conc_unit_liquid!='×') {
				e3.value=(e7.value*e8.value)/(e9.value*e10.value*e4.value);
			}
			else {
				e3.value='';
			}
		}
		if (calc_which_liquid=='1') {
			document.getElementById("mw_value_liquid").readOnly =true;
			document.getElementById("mw_value_liquid").style.background="yellow";
      if (e3.value!=''&&e5.value!=''&&e7.value!=''&&e9.value!=''&&(stock_conc_unit_liquid=='M'||stock_conc_unit_liquid=='mM'||stock_conc_unit_liquid=='μM'||stock_conc_unit_liquid=='nM')&&(final_conc_unit_liquid=='% (w/v)'||final_conc_unit_liquid=='g/mL'||final_conc_unit_liquid=='mg/mL (g/L)'||final_conc_unit_liquid=='μg/mL (mg/L)'||final_conc_unit_liquid=='ng/mL (μg/L)'||final_conc_unit_liquid=='ppm')) {
				e1.value=(e5.value*e6.value*e7.value*e8.value)/(e9.value*e10.value*e2.value*e3.value*e4.value);
			}
			else if (e3.value!=''&&e5.value!=''&&e7.value!=''&&e9.value!=''&&(stock_conc_unit_liquid=='% (w/v)'||stock_conc_unit_liquid=='g/mL'||stock_conc_unit_liquid=='mg/mL (g/L)'||stock_conc_unit_liquid=='μg/mL (mg/L)'||stock_conc_unit_liquid=='ng/mL (μg/L)'||stock_conc_unit_liquid=='ppm')&&(final_conc_unit_liquid=='M'||final_conc_unit_liquid=='mM'||final_conc_unit_liquid=='μM'||final_conc_unit_liquid=='nM')) {
				e1.value=(e9.value*e10.value*e3.value*e4.value)/(e2.value*e5.value*e6.value*e7.value*e8.value);
			}
			else {
				e1.value='';
			}
		}
	}
	if (stock_type=='3') {
		var e1=document.getElementById('mw_value_conversion');
		var e2=document.getElementById('mw_unit_conversion');
		var e3=document.getElementById('stock_conc_value_conversion');
		var e4=document.getElementById('stock_conc_unit_conversion');
		var e5=document.getElementById('final_conc_value_conversion');
		var e6=document.getElementById('final_conc_unit_conversion');
		var e=document.getElementById('conversion_form');
		for(var i = 0; i < e.length; i++) {
			if(e[i].checked&&e[i].name=='calc_which_conversion') {
				var calc_which_conversion=e[i].value;
			}
		}
		for(var i=0;i<e4.options.length;i++){
			if(e4.options[i].selected) {
				var stock_conc_unit_conversion = e4.options[i].text;
			}
		}
		for(var i=0;i<e6.options.length;i++){
			if(e6.options[i].selected) {
				var final_conc_unit_conversion = e6.options[i].text;
			}
		}
		for(var i = 0; i < e.length; i++) {
			if(e[i].type=='text') {
				e[i].readOnly =false;
				e[i].style.background="";
			}
		}

		if (calc_which_conversion=='3') {
			document.getElementById("final_conc_value_conversion").readOnly =true;
			document.getElementById("final_conc_value_conversion").style.background="yellow";
			if (e1.value!=''&&e3.value!=''&&(stock_conc_unit_conversion=='M'||stock_conc_unit_conversion=='mM'||stock_conc_unit_conversion=='μM'||stock_conc_unit_conversion=='nM')&&(final_conc_unit_conversion=='% (w/v)'||final_conc_unit_conversion=='g/mL'||final_conc_unit_conversion=='mg/mL (g/L)'||final_conc_unit_conversion=='μg/mL (mg/L)'||final_conc_unit_conversion=='ng/mL (μg/L)'||final_conc_unit_conversion=='ppm')) {
				e5.value=(e1.value*e2.value*e3.value*e4.value)/(e6.value);
			}
			else if (e1.value!=''&&e3.value!=''&&(stock_conc_unit_conversion=='% (w/v)'||stock_conc_unit_conversion=='g/mL'||stock_conc_unit_conversion=='mg/mL (g/L)'||stock_conc_unit_conversion=='μg/mL (mg/L)'||stock_conc_unit_conversion=='ng/mL (μg/L)'||stock_conc_unit_conversion=='ppm')&&(final_conc_unit_conversion=='M'||final_conc_unit_conversion=='mM'||final_conc_unit_conversion=='μM'||final_conc_unit_conversion=='nM')) {
				e5.value=(e3.value*e4.value)/(e1.value*e2.value*e6.value);
			}
			else if
			(e3.value!=''&&(((stock_conc_unit_conversion=='% (w/v)'||stock_conc_unit_conversion=='g/mL'||stock_conc_unit_conversion=='mg/mL (g/L)'||stock_conc_unit_conversion=='μg/mL (mg/L)'||stock_conc_unit_conversion=='ng/mL (μg/L)'||stock_conc_unit_conversion=='ppm')&&(final_conc_unit_conversion=='% (w/v)'||final_conc_unit_conversion=='g/mL'||final_conc_unit_conversion=='mg/mL (g/L)'||final_conc_unit_conversion=='μg/mL (mg/L)'||final_conc_unit_conversion=='ng/mL (μg/L)'||final_conc_unit_conversion=='ppm'))||((stock_conc_unit_conversion=='M'||stock_conc_unit_conversion=='mM'||stock_conc_unit_conversion=='μM'||stock_conc_unit_conversion=='nM')&&(final_conc_unit_conversion=='M'||final_conc_unit_conversion=='mM'||final_conc_unit_conversion=='μM'||final_conc_unit_conversion=='nM')))) {
				e5.value=(e3.value*e4.value)/(e6.value);
			}
			else {
				e5.value='';
			}
		}
		if (calc_which_conversion=='2') {
			document.getElementById("stock_conc_value_conversion").readOnly =true;
			document.getElementById("stock_conc_value_conversion").style.background="yellow";
			if (e1.value!=''&&e5.value!=''&&(stock_conc_unit_conversion=='M'||stock_conc_unit_conversion=='mM'||stock_conc_unit_conversion=='μM'||stock_conc_unit_conversion=='nM')&&(final_conc_unit_conversion=='% (w/v)'||final_conc_unit_conversion=='g/mL'||final_conc_unit_conversion=='mg/mL (g/L)'||final_conc_unit_conversion=='μg/mL (mg/L)'||final_conc_unit_conversion=='ng/mL (μg/L)'||final_conc_unit_conversion=='ppm')) {
				e3.value=(e5.value*e6.value)/(e1.value*e2.value*e4.value);
			}
			else if (e1.value!=''&&e5.value!=''&&(stock_conc_unit_conversion=='% (w/v)'||stock_conc_unit_conversion=='g/mL'||stock_conc_unit_conversion=='mg/mL (g/L)'||stock_conc_unit_conversion=='μg/mL (mg/L)'||stock_conc_unit_conversion=='ng/mL (μg/L)'||stock_conc_unit_conversion=='ppm')&&(final_conc_unit_conversion=='M'||final_conc_unit_conversion=='mM'||final_conc_unit_conversion=='μM'||final_conc_unit_conversion=='nM')) {
				e3.value=(e1.value*e2.value*e5.value*e6.value)/(e4.value);
			}
			else if
			(e5.value!=''&&(((stock_conc_unit_conversion=='% (w/v)'||stock_conc_unit_conversion=='g/mL'||stock_conc_unit_conversion=='mg/mL (g/L)'||stock_conc_unit_conversion=='μg/mL (mg/L)'||stock_conc_unit_conversion=='ng/mL (μg/L)'||stock_conc_unit_conversion=='ppm')&&(final_conc_unit_conversion=='% (w/v)'||final_conc_unit_conversion=='g/mL'||final_conc_unit_conversion=='mg/mL (g/L)'||final_conc_unit_conversion=='μg/mL (mg/L)'||final_conc_unit_conversion=='ng/mL (μg/L)'||final_conc_unit_conversion=='ppm'))||((stock_conc_unit_conversion=='M'||stock_conc_unit_conversion=='mM'||stock_conc_unit_conversion=='μM'||stock_conc_unit_conversion=='nM')&&(final_conc_unit_conversion=='M'||final_conc_unit_conversion=='mM'||final_conc_unit_conversion=='μM'||final_conc_unit_conversion=='nM')))) {
				e3.value=(e5.value*e6.value)/(e4.value);
			}
			else {
				e3.value='';
			}
		}
		if (calc_which_conversion=='1') {
			document.getElementById("mw_value_conversion").readOnly =true;
			document.getElementById("mw_value_conversion").style.background="yellow";
      if (e3.value!=''&&e5.value!=''&&(stock_conc_unit_conversion=='M'||stock_conc_unit_conversion=='mM'||stock_conc_unit_conversion=='μM'||stock_conc_unit_conversion=='nM')&&(final_conc_unit_conversion=='% (w/v)'||final_conc_unit_conversion=='g/mL'||final_conc_unit_conversion=='mg/mL (g/L)'||final_conc_unit_conversion=='μg/mL (mg/L)'||final_conc_unit_conversion=='ng/mL (μg/L)'||final_conc_unit_conversion=='ppm')) {
				e1.value=(e5.value*e6.value)/(e2.value*e3.value*e4.value);
			}
			else if (e3.value!=''&&e5.value!=''&&(stock_conc_unit_conversion=='% (w/v)'||stock_conc_unit_conversion=='g/mL'||stock_conc_unit_conversion=='mg/mL (g/L)'||stock_conc_unit_conversion=='μg/mL (mg/L)'||stock_conc_unit_conversion=='ng/mL (μg/L)'||stock_conc_unit_conversion=='ppm')&&(final_conc_unit_conversion=='M'||final_conc_unit_conversion=='mM'||final_conc_unit_conversion=='μM'||final_conc_unit_conversion=='nM')) {
				e1.value=(e3.value*e4.value)/(e2.value*e5.value*e6.value);
			}
			else {
				e1.value='';
			}
		}
	}
}