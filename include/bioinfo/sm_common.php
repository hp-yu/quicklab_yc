<?php
function sm_tidyup($sequence) {
	if ($sequence!='') {
		$n=1;
		$seq_split_1=str_split($sequence,60);
		foreach ($seq_split_1 as $key_1=>$value_1) {
			if($n>1) {$seq_output.="\r\n";}
			$seq_split_2=str_split($value_1,10);
			$seq_output.= str_repeat("&nbsp;", 9-strlen($n));
			$seq_output.=$n;
			$n=$n+60;
			foreach ($seq_split_2 as $key_2=>$value_2) {
				$seq_output.="&nbsp;".$value_2;
			}
		}
	}
  return $seq_output;
}

function sm_remove_useless($seq) {
	$seq = sm_get_sequence_from_fasta($seq);
	$seq = preg_replace("/\d|\W/","",$seq);
	return $seq;
}
function sm_get_sequence_from_fasta ($sequence) {
	if (preg_match("/\>[^\f\n\r]+[\f\n\r]/",$sequence))	{
		$sequence = preg_replace("/\>[^\f\n\r]+[\f\n\r]/","",$sequence);
	}
	return $sequence;
}
function sm_reverse($seq){
	$seq = strtoupper ($seq);
	$reverse="";
	for($i=strlen($seq)-1;$i>=0;$i--){
		$reverse.=substr($seq,$i,1);
	}
	return $reverse;
}
function sm_complement($seq){
	// change the sequence to upper case
	$seq = strtoupper ($seq);
	// the system used to get the complementary sequence is simple but fas
	$seq=str_replace("A", "t", $seq);
	$seq=str_replace("T", "a", $seq);
	$seq=str_replace("G", "c", $seq);
	$seq=str_replace("C", "g", $seq);
	$seq=str_replace("Y", "r", $seq);
	$seq=str_replace("R", "y", $seq);
	$seq=str_replace("W", "w", $seq);
	$seq=str_replace("S", "s", $seq);
	$seq=str_replace("K", "m", $seq);
	$seq=str_replace("M", "k", $seq);
	$seq=str_replace("D", "h", $seq);
	$seq=str_replace("V", "b", $seq);
	$seq=str_replace("H", "d", $seq);
	$seq=str_replace("B", "v", $seq);
	// change the sequence to upper case again for output
	$seq = strtoupper ($seq);
	return $seq;
}
function sm_orf_finder($seq,$genetic_code,$start_pos,$strand,$min_length){
	$genetic_code_string=sm_get_genetic_code_string($genetic_code);
	$genetic_code_array=split(',',$genetic_code_string);
	$genetic_code_match_exp = sm_get_genetic_code_match_exp ($genetic_code_array);
	$genetic_code_match_result = sm_get_genetic_code_match_result($genetic_code_array);
	for ($j = 0; $j < sizeof($genetic_code_match_exp); $j++){
		if ($genetic_code_match_result[$j] == "M")	{
			$genetic_code_match_exp_start = $genetic_code_match_exp[$j];
		}
		if ($genetic_code_match_result[$j] == "*")	{
			$genetic_code_match_exp_stop = $genetic_code_match_exp[$j];
		}
	}

	$rf=$start_pos+1;
	$orf=array();
	$k=0;$i=0;
	$protein_length = 0;
	if ($strand==2) {$seq=sm_complement(sm_reverse($seq));}
	while ($i <= strlen($seq) - 3)	{
		for ($i = $start_pos; $i <= strlen($seq) - 3; $i = $i + 3)	{
			$codon = substr($seq,$i,3);
			if (($start_condons != "any") && ($found_start==false) && (preg_match($genetic_code_match_exp_start,$codon)==0))	{
				break;
			}
			$found_start = true;
			if (preg_match($genetic_code_match_exp_stop,$codon)>0) {
				$found_stop = true;
			}
			$protein_length++;
			if (($found_stop) && ($protein_length < $min_length))	{break;}
			if ((($found_stop) && ($protein_length >= $min_length)) || (($i >= strlen($seq) - 5) && ($protein_length >= $min_length)))	{
				if ($strand==1) {
					$orf[$k]['start']=$start_pos+1;
					$orf[$k]['end']=$i + 3;
				} else {
					$orf[$k]['end']=strlen($seq)-($start_pos+1)+1;
					$orf[$k]['start']=strlen($seq)-($i + 3)+1;
				}
				$orf[$k]['ori']=$strand;
				$k++;
				break;
			}
		}
		$start_pos = $i + 3;
		$i=$start_pos;
		$found_start=false;
		$found_stop=false;
		$protein_length = 0;
	}
	return $orf;
}
function sm_get_genetic_code_match_exp ($genetic_code_array) {
	$genetic_code_match_exp = array();
	for ($j = 0; $j < sizeof($genetic_code_array); $j++)	{
		$split=split("=",$genetic_code_array[$j]);
		$genetic_code_match_exp[$j] = $split[0]."i";
	}
	return $genetic_code_match_exp;
}
function sm_get_genetic_code_match_result ($genetic_code_array) {
	$genetic_code_match_result = array();
	for ($j = 0; $j < sizeof($genetic_code_array); $j++)	{
		$split=split("=",$genetic_code_array[$j]);
		$genetic_code_match_result[$j] = $split[1];
	}
	return $genetic_code_match_result;
}
function sm_get_genetic_code_string ($type) {

//  The Standard Code (transl_table=1)
//    AAs  = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = ---M---------------M---------------M----------------------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if (($type == "standard") || ($type == "1")) {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]gg/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]|[tu]ga|[tu][agr]a/=*";
	}

//  The Vertebrate Mitochondrial Code (transl_table=2)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CCWWLLLLPPPPHHQQRRRRIIMMTTTTNNKKSS**VVVVAAAADDEEGGGG
//  Starts = --------------------------------MMMM---------------M------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG


	if ($type == "2") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][tcuy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu][agr]/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]g[agr]/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]|ag[agr]/=*";
	}


//  The Yeast Mitochondrial Code (transl_table=3)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CCWWTTTTPPPPHHQQRRRRIIMMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = ----------------------------------MM----------------------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG


	if ($type == "3") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][tcuy]/=I," .
	    "/aa[agr]/=K," .
	    "/[tu][tu][agr]/=L," .
	    "/a[tu][agr]/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]|c[tu][acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]g[agr]/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]/=*";
	}

//  The Mold, Protozoan, and Coelenterate Mitochondrial Code and the Mycoplasma/Spiroplasma Code (transl_table=4)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CCWWLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = --MM---------------M------------MMMM---------------M------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "4") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]g[agr]/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]/=*";
	}

//  The Invertebrate Mitochondrial Code (transl_table=5)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CCWWLLLLPPPPHHQQRRRRIIMMTTTTNNKKSSSSVVVVAAAADDEEGGGG
//  Starts = ---M----------------------------MMMM---------------M------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "5") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][tcuy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu][agr]/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[acgturyswkmbdhvn]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]g[agr]/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]/=*";
	}

//  The Ciliate, Dasycladacean and Hexamita Nuclear Code (transl_table=6)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYYQQCC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = -----------------------------------M----------------------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "6") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]|[tu]a[agr]|[tcuy]a[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]gg/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]ga/=*";
	}


//  The Echinoderm and Flatworm Mitochondrial Code (transl_table=9)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CCWWLLLLPPPPHHQQRRRRIIIMTTTTNNNKSSSSVVVVAAAADDEEGGGG
//  Starts = -----------------------------------M---------------M------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "9") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aag/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu]g/=M," .
	    "/aa[atcuwmhy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[acgturyswkmbdhvn]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]g[agr]/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]/=*";
	}


//  The Euplotid Nuclear Code (transl_table=10)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CCCWLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = -----------------------------------M----------------------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "10") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[atcuwmhy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]gg/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]/=*";
	}

//  The Bacterial and Plant Plastid Code (transl_table=11)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = ---M---------------M------------MMMM---------------M------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "11") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]gg/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]|[tu]ga|[tu][agr]a/=*";
	}


//  The Alternative Yeast Nuclear Code (transl_table=12)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CC*WLLLSPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = -------------------M---------------M----------------------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "12") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][atcuwmhy]|[tu][tu][agr]|[ctuy][tu]a/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]|c[tu]g/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]gg/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]|[tu]ga|[tu][agr]a/=*";
	}


//  The Ascidian Mitochondrial Code (transl_table=13)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CCWWLLLLPPPPHHQQRRRRIIMMTTTTNNKKSSGGVVVVAAAADDEEGGGG
//  Starts = ---M------------------------------MM---------------M------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "13") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]|ag[agr]|[agr]g[agr]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][tcuy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu][agr]/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]g[agr]/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]/=*";
	}

//  The Alternative Flatworm Mitochondrial Code (transl_table=14)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYYY*CCWWLLLLPPPPHHQQRRRRIIIMTTTTNNNKSSSSVVVVAAAADDEEGGGG
//  Starts = -----------------------------------M----------------------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "14") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aag/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu]g/=M," .
	    "/aa[atcuwmhy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[acgturyswkmbdhvn]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]g[agr]/=W," .
	    "/[tu]a[atcuwmhy]/=Y," .
	    "/[tu]ag/=*";
	}

//  Blepharisma Nuclear Code (transl_table=15)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY*QCC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = -----------------------------------M----------------------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "15") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]|[tu]ag|[tcuy]ag/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]gg/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu][agr]a/=*";
	}

//  Chlorophycean Mitochondrial Code (transl_table=16)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY*LCC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = -----------------------------------M----------------------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "16") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]|[tu]ag|[tu][atuw]g/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]gg/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu][agr]a/=*";
	}

//  Trematode Mitochondrial Code (transl_table=21)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSSSSYY**CCWWLLLLPPPPHHQQRRRRIIMMTTTTNNNKSSSSVVVVAAAADDEEGGGG
//  Starts = -----------------------------------M---------------M------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "21") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][tcuy]/=I," .
	    "/aag/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]/=L," .
	    "/a[tu][agr]/=M," .
	    "/aa[atcuwmhy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[acgturyswkmbdhvn]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]g[agr]/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]/=*";
	}


//  Scenedesmus obliquus mitochondrial Code (transl_table=22)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FFLLSS*SYY*LCC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = -----------------------------------M----------------------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "22") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[tu][tu][agr]|[ctuy][tu][agr]|[tu]ag|[tu][atuw]g/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[cgtyskb]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]gg/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu][agcrsmv]a/=*";
	}


//  Thraustochytrium Mitochondrial Code (transl_table=23)
//Standard = FFLLSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//    AAs  = FF*LSSSSYY**CC*WLLLLPPPPHHQQRRRRIIIMTTTTNNKKSSRRVVVVAAAADDEEGGGG
//  Starts = --------------------------------M--M---------------M------------
//  Base1  = TTTTTTTTTTTTTTTTCCCCCCCCCCCCCCCCAAAAAAAAAAAAAAAAGGGGGGGGGGGGGGGG
//  Base2  = TTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGGTTTTCCCCAAAAGGGG
//  Base3  = TCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAGTCAG

	if ($type == "23") {
	    return "/gc[acgturyswkmbdhvn]/=A," .
	    "/[tu]g[ctuy]/=C," .
	    "/ga[tcuy]/=D," .
	    "/ga[agr]/=E," .
	    "/[tu][tu][tcuy]/=F," .
	    "/gg[acgturyswkmbdhvn]/=G," .
	    "/ca[tcuy]/=H," .
	    "/a[tu][atcuwmhy]/=I," .
	    "/aa[agr]/=K," .
	    "/c[tu][acgturyswkmbdhvn]|[ctuy][tu]g/=L," .
	    "/a[tu]g/=M," .
	    "/aa[tucy]/=N," .
	    "/cc[acgturyswkmbdhvn]/=P," .
	    "/ca[agr]/=Q," .
	    "/cg[acgturyswkmbdhvn]|ag[agr]|[cam]g[agr]/=R," .
	    "/[tu]c[acgturyswkmbdhvn]|ag[ct]/=S," .
	    "/ac[acgturyswkmbdhvn]/=T," .
	    "/g[tu][acgturyswkmbdhvn]/=V," .
	    "/[tu]gg/=W," .
	    "/[tu]a[ctuy]/=Y," .
	    "/[tu]a[agr]|[tu]ga|[tu][agtrwkd]a/=*";
	}

	return true;
}

function sm_extract_sequences($text){
	if (substr_count($text,">")==0){
		$sequence[0]["seq"] = preg_replace ("/\W|\d/", "", strtoupper ($text));
	}else{
		$arraysequences=preg_split("/>/", $text,-1,PREG_SPLIT_NO_EMPTY);
		$counter=0;
		foreach($arraysequences as $key =>$val){
			$seq=substr($val,strpos($val,"\n"));
			$seq = preg_replace ("/\W|\d/", "", strtoupper($seq));
			if (strlen($seq)>0){
				$sequence[$counter]["seq"] = $seq;
				$sequence[$counter]["name"]=substr($val,0,strpos($val,"\n"));
				$counter++;
			}
		}
	}
	return $sequence;
}

function sm_reduce_enzymes_array($enzymes_array,$minimun,$retype,$defined_sq,$wre){
	// if $wre => all endonucleases but the selected one must be removed
	if ($wre){
		foreach($enzymes_array as $key => $val){
			if (strpos(" ,".$enzymes_array[$key][0].",",$wre)>0){
				$new_array[$wre]=$enzymes_array[$key];
				return $new_array;
			}
		}
	}
	// remove endonucleases which do not match requeriments
	foreach ($enzymes_array as $enzyme => $val){
		// if retype==1 -> only Blund ends (continue for rest)
		if ($retype==1 and $enzymes_array[$enzyme][5]!=0){continue;}
		// if retype==2 -> only Overhang end (continue for rest)
		if ($retype==2 and $enzymes_array[$enzyme][5]==0){continue;}
		// Only endonucleases with which recognized in template a minimum of bases (continue for rest)
		if ($minimun>$enzymes_array[$enzyme][6]){continue;}
		// if defined sequence selected, no N (".") or "|" in pattern
		if ($defined_sq==1){
			if (strpos($enzymes_array[$enzyme][2],".")>0 or strpos($enzymes_array[$enzyme][2],"|")>0){continue;}
		}
		$enzymes_array2[$enzyme]=$enzymes_array[$enzyme];
	}
	return $enzymes_array2;
}

function sm_restriction_digest($enzymes_array,$sequence){
	foreach ($enzymes_array as $enzyme => $val){
		// this is to put together results for IIb endonucleases, which are computed as "enzyme_name" and "enzyme_name@"
		$enzyme2=str_replace("@","",$enzyme);
		// split sequence based on pattern from restriction enzyme
		$fragments = preg_split("/".$enzymes_array[$enzyme][0]."/", $sequence,-1,PREG_SPLIT_DELIM_CAPTURE);
		reset ($fragments);
		$maxfragments=sizeof($fragments);
		// when sequence is cleaved ($maxfragments>1) start further calculations
		if ($maxfragments>1){
			$recognitionposition=strlen($fragments[0]);
			$counter_cleavages=0;
			$list_of_cleavages="";
			// for each frament generated, calculate cleavage position,
			// add it to a list, and add 1 to counter
			for ($i=2;$i<$maxfragments; $i+=2){
				$cleavageposition=$recognitionposition+$enzymes_array[$enzyme][2];
				$digestion[$enzyme2]["cuts"][$cleavageposition]="";
				// As overlapping may occur for many endonucleases,
				// a subsequence starting in position 2 of fragment is calculate
				$subsequence=substr($fragments[$i-1],1).$fragments[$i].substr($fragments[$i+1],0,40);
				$subsequence=substr($subsequence,0,2*$enzymes_array[$enzyme][1]-2);
				//Previous process is repeated
				// split subsequence based on pattern from restriction enzyme
				$fragments_subsequence = preg_split($enzymes_array[$enzyme][0],$subsequence);
				// when subsequence is cleaved start further calculations
				if (sizeof($fragments_subsequence)>1){
					// for each fragment of subsequence, calculate overlapping cleavage position,
					//    add it to a list, and add 1 to counter
					$overlapped_cleavage=$recognitionposition+1+strlen($fragments_subsequence[0])+$enzymes_array[$enzyme][2];
					$digestion[$enzyme2]["cuts"][$overlapped_cleavage]="";
				}
				// this is a counter for position
				$recognitionposition+=strlen($fragments[$i-1])+strlen($fragments[$i]);
			}
		}
	}
	return $digestion;
}

function sm_feature_finder($features,$sequence){
	$n=1;
	$result=array();
	foreach ($features as $value){
		$len_fea=strlen($value['seq']);
		$pos=strpos($sequence,$value['seq']);
		if ($pos>0) {
			$result[$n]['id']=$n;
			$result[$n]['name']=$value['name'];
			$result[$n]['color']="rgb(0,0,0)";
			$result[$n]['start']=$pos;
			$result[$n]['end']=$pos+$len_fea;
			$result[$n]['ori']="1";
			$n++;
		}
	}
	$len_seq=strlen($sequence);
	//echo "<textarea cols='80',rows='6'/>".$sequence."</textarea>";
	for ($i = $len_seq-1; $i>=0; $i--) {
		$rev_sequence .= substr($sequence,$i,1);
	};
	$sequence = sm_complement ($rev_sequence);
	//echo "<textarea cols='80',rows='6'/>".$sequence."</textarea>";
	foreach ($features as $value){
		$len_fea=strlen($value['seq']);
		$pos=strpos($sequence,$value['seq']);
		if ($pos>0) {
			$result[$n]['id']=$n;
			$result[$n]['name']=$value['name'];
			$result[$n]['color']="rgb(0,0,0)";
			$result[$n]['end']=$len_seq-$pos;
			$result[$n]['start']=$len_seq-($pos+$len_fea);
			$result[$n]['ori']="2";
			$n++;
		}
	}
	return $result;
}

?>