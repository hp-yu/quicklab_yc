function calc() {
	var e1=document.getElementById('mw_value');
	var e2=document.getElementById('mw_unit');
	var e3=document.getElementById('final_conc_value');
	var e4=document.getElementById('final_conc_unit');
	var e5=document.getElementById('final_volume_value');
	var e6=document.getElementById('final_volume_unit');
	var e7=document.getElementById('stock_amount_value');
	var e8=document.getElementById('stock_amount_unit');
	var e=document.getElementById('calc');
	for(var i = 0; i < e.length; i++) {
		if(e[i].checked&&e[i].name='calc_which') {
			var radioValue=e[i].value;
		}
	}
	for(var i=0;i<e4.options.length;i++){
		if(e4.options[i].selected) {
			var conc_unit = e4.options[i].text;
		}
	}
	if (radioValue=='5') {
		document.getElementById('stock_amount_value').readonly=TRUE;
		document.getElementById('stock_amount_value').style.background-color="yellow";
		if (e1.value!=''&&e3.value!=''&&e5.value!=''&&(conc_unit=='M'||conc_unit=='mM'||conc_unit=='μM'||conc_unit=='nM')) {
				e7.value=e1.value*e2.value*e3.value*e4.value*e5.value*e6.value/e8.value;
		}
		else if
		(e3.value!=''&&e5.value!=''&&(conc_unit=='%'||conc_unit=='g/mL'||conc_unit=='mg/mL (g/L)'||conc_unit=='μg/mL (mg/L)'||conc_unit=='ng/mL (μg/L)'||conc_unit=='ppm')) {
				e7.value=e3.value*e4.value*e5.value*e6.value/e8.value;
		}
		else {
			e7.value='';
		}
	}
		if (radioValue=='3') {
		if (e1.value!=''&&e3.value!=''&&e7.value!=''&&(conc_unit=='M'||conc_unit=='mM'||conc_unit=='μM'||conc_unit=='nM')) {
				e5.value=e7.value*e8.value/(e1.value*e2.value*e3.value*e4.value*e6.value);
		}
		else if
		(e3.value!=''&&e7.value!=''&&(conc_unit=='%'||conc_unit=='g/mL'||conc_unit=='mg/mL (g/L)'||conc_unit=='μg/mL (mg/L)'||conc_unit=='ng/mL (μg/L)'||conc_unit=='ppm')) {
				e5.value=e7.value*e8.value/(e3.value*e4.value*e6.value);
		}
		else {
			e5.value='';
		}
	}
	if (radioValue=='2') {
		if (e1.value!=''&&e5.value!=''&&e7.value!=''&&(conc_unit=='M'||conc_unit=='mM'||conc_unit=='μM'||conc_unit=='nM')) {
				e3.value=e7.value*e8.value/(e1.value*e2.value*e4.value*e5.value*e6.value);
		}
		else if
		(e5.value!=''&&e7.value!=''&&(conc_unit=='%'||conc_unit=='g/mL'||conc_unit=='mg/mL (g/L)'||conc_unit=='μg/mL (mg/L)'||conc_unit=='ng/mL (μg/L)'||conc_unit=='ppm')) {
				e3.value=e7.value*e8.value/(e4.value*e5.value*e6.value);
		}
		else {
			e3.value='';
		}
	}
	if (radioValue=='1') {
		if (e3.value!=''&&e5.value!=''&&e7.value!=''&&(conc_unit=='M'||conc_unit=='mM'||conc_unit=='μM'||conc_unit=='nM')) {
				e1.value=e7.value*e8.value/(e2.value*e3.value*e4.value*e5.value*e6.value);
		}
		else {
			e1.value='';
		}
	}
}