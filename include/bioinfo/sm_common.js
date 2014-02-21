function removeuseless(str) {
	str = str.split(/\d|\W/).join("");
	return str;
}
function get_sequence_from_fasta (sequence) {
	if (sequence.search(/\>[^\f\n\r]+[\f\n\r]/) != -1)	{
		sequence = sequence.replace(/\>[^\f\n\r]+[\f\n\r]/,"");
	}
	return sequence;
}
function sm_reverse(e) {
	e1=document.getElementById(e);
	var str=' '+e1.value.toUpperCase();
	var pos = str.indexOf('>');
	if (pos>0){return;}
	str=removeuseless(str);
	if (!str) {e1.value=''};
	var revstr=' ';
	var k=0;
	for (i = str.length-1; i>=0; i--) {
		revstr+=str.charAt(i);
		k+=1;
	};
	e1.value=revstr;
	tidyup(e);
}
function sm_complement(e) {
	e1=document.getElementById(e);
	var str=' '+e1.value.toUpperCase();
	var pos = str.indexOf('>');
	if (pos>0){return};
	str=removeuseless(str);
	str = str.split("A").join("t");
	str = str.split("T").join("a");
	str = str.split("G").join("c");
	str = str.split("C").join("g");
	str=str.toUpperCase();
	e1.value=str;
	tidyup(e);
}
function tidyup(e) {
	e=document.getElementById(e);
	var str=e.value.toLowerCase();
	str=get_sequence_from_fasta(str);
	str=removeuseless(str);
	if (!str) {e.value=''};
	var revstr='';
	for (i =0; i<str.length; i++) {
		if (i%60==0) {
			if (i>0) {revstr+='\n';}
			y=(i+1).toString();
			for(n=0;n<9-y.length;n++) {revstr+=' ';}
			revstr+=i+1;
		}
		if (i%10==0) {revstr+=' ';}
		revstr+=str.charAt(i);
	}
	e.value=revstr;
}