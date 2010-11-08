<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// server specific functions for conversion of SUTRS record
// authors: Marco Vaninetti, Massimo Mancini
// state: higly experimental ;-)
// +-------------------------------------------------+
// $Id: sutrs_func.php,v 1.9 2009-05-16 11:05:14 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "sutrs_func.php")) die("no access");

/*--------------------------------------------------------------------------------------------
Convert $campi in a string for last import via sutrs2unimarciso mechanism (params.xml)
using sut_* functions in sutrs_func.php.
The format of text record is
------------------------------------------------------------------------
N.	Info				Unimarc		Tag SUTRS		Function sut_*		
------------------------------------------------------------------------
1  	isbn				010 a		Numbers			sut_numbers

// un autore principale
2	Author-fname		700 a		Authors			sut_authors
3	Author-lname		700 b
4	Author-func			700 4

// max 2 coautori
5	Author-fname		701 a		Authors			sut_authors
6	Author-lname		701 b
7	Author-func			701 4

8	Author-fname		701 a		Authors			sut_authors
9	Author-lname		701 b
10	Author-func			701 4

// max 6 aut.secondari
11	Author-fname		702 a		Authors			sut_authors
12	Author-lname		702 b
13	Author-func			702 4

14	Author-fname		702 a		Authors			sut_authors
15	Author-lname		702 b
16	Author-func			702 4

17	Author-fname		702 a		Authors			sut_authors
18	Author-lname		702 b
19	Author-func			702 4

20	Author-fname		702 a		Authors			sut_authors
21	Author-lname		702 b
22	Author-func			702 4

23	Author-fname		702 a		Authors			sut_authors
24	Author-lname		702 b
25	Author-func			702 4

26	Author-fname		702 a		Authors			sut_authors
27	Author-lname		702 b
28	Author-func			702 4

29 	Title1				200 a		Title			sut_titles
30	Title2				200 b
31	Title3				200	c
32	Title4				200	d

33  Edition-mention     205 a		Edition			sut_edition

34	Editor-city			210 a						sut_editor				
35	Editor-name			210 c
36	Editor-year			210 d

37	Collection-name		225 a		Collection		sut_collection
38	Collection-nr		225 v
39	Collection-issn		225 x
40	Collection-subname	225 i   	?				sut_dummy

41	Serie-name			461 t						sut_dummy
42	Serie-number		461 v	

43 	Dewey-code			676	a		DecimalIndex	sut_decimalindex	
	
44	KeyWord/subject		610 a		FreeIndex		sut_freeindex
		
45	Lang				101 a		Lang			sut_lang
46	Orig.Lang			101 c

47	Collation-npages	215 a		Collation		sut_collation
48	Collation-ill.		215 c
49	Collation-size		215 d
50	Collation-accomp	215 e

51	Note-gen			300 a		Notes			sut_notes
52	Note-con			327 a
53	Note-res			330 a

54	Linked_notices
55	Places
56	Series


if no function function = sut_dummy()

typical ITALIAN SUTR record (| and :: are NOT in original record):

|Natura:: Monografia |Lingua:: ITALIANO |Paese:: ITALIA 
|Titolo:: I *persuasori occulti / Vance Packard ; traduzione di Carlo Fruttero. - Milano : Il saggiatore, 1968. - 336 p. ; 18 cm . 
|Numeri_standard:: BNI 688653 |Collana:: I gabbiani 
|Autore:: Packard, Vance |Autore:: Fruttero, Carlo 
|Soggetto:: F Pubblicità - Aspetti psicologici 
|Classificazione:: D 659.1019 Pubblicità. Principi psicologici
---------------------------------------------------------------------------------------------*/

function from_sutrs($ss,$campo){
	$base_path = "../..";
	global $class_path;
	global $lang;
	global $include_path;
	global $charset;
	global $campi,$sep;
	global $fun;
	
	$campi=array();

	// strip \r\n and spaces
	$pattern = "/\r/";
	$ss= preg_replace($pattern, "", $ss);
	$pattern = "/\n /";
	$ss= preg_replace($pattern, " ", $ss);
	$pattern = "/\n/";
	$ss= preg_replace($pattern, " ", $ss);
	$pattern = "/\s+/";
	$ss= preg_replace($pattern, " ", $ss);

	// put tag sutrs in sutrs record 
	foreach ($campo as $dummykey=>$v){
		$dato[$v]="";
		$ss=preg_replace("/$v:/","|$v::",$ss);
	}	

	// 	explode fields sutrs in $dato (array) 
	$aX = preg_split ("/\|/", $ss);
	foreach ($aX as $dummykey=>$vv) {
		$v=preg_split ("/::/", $vv);
		if ($dato[$v[0]]=='') $sep='';
			else $sep='|';
		$dato[$v[0]] .= $sep.$v[1];
	}		

	// copy $dato values to associative array $campi which keys are standard and in english 
	reset($campo);
	while (list($k,$v)=each($campo)){
		$campi[$k]=$dato[$v];
	}

//* DEBUG  
		
// 			$fp = fopen ("../../temp/raw".rand().".unimarc","wb");
// 			fwrite ($fp,$lista[3]);
// 			fclose ($fp);


	// text record construction in $notice
	$sep='|'; $notice="";

	//isbn
	$notice.=sut_numbers();  		// col. 1
	$notice.=del_more_garbage(sut_authors());  		// col. 2..28  (1 author = 3 col) 
	$notice.=del_more_garbage(sut_title())  ; 		// col.29..32
	$notice.=sut_edition();			// col.33
	$notice.=del_more_garbage(sut_editor()); 			// col.34..36
	$notice.=del_more_garbage(sut_collection());  	// col.37..40

	// serie
	$notice.=sut_dummy(2);  		// col.41..42

	$notice.=sut_decimalindex(); 	// col.43
	$notice.=sut_freeindex(); 		// col.44
	$notice.=sut_lang(); 			// col.45

	// orig.lang
	$notice.=sut_dummy(1); 			// col.46

	$notice.=sut_collation(); 		// col.47..50

	$notice.=sut_notes(); 			// col.51..53


//DEBUG: in z_progression_main expand size frame3 to 50%
	//print "<hr /><br />";
	//printr($campo,'','CAMPO');
	//printr($campi,'','CAMPI');
	//print "$ss<br /><hr />$notice";
	
	//Lecture des paramètres d'import

	$param_path = "sutrs2unimarciso";

	//Lecture des paramètres
	_parser_("$base_path/admin/convert/imports/".$param_path."/params.xml", array("IMPORTNAME" => "_import_name_", "NPERPASS" => "_n_per_pass_", "INPUT" => "_input_", "STEP" => "_step_", "OUTPUT" => "_output_"), "PARAMS");
	require_once ("$base_path/admin/convert/xmltransform.php");
	//En fonction du type de fichier d'entrée, inclusion du script de gestion des entrées
	require_once("$base_path/admin/convert/imports/input_text.inc.php");
	//En fonction du type de fichier de sortie, inclusion du script de gestion des sorties
	require_once("$base_path/admin/convert/imports/output_iso_2709.inc.php");

	$n_current = 0;
	$n_errors = 0;
	$n_per_pass = 1;
	
	$notice_ = convert_notice($notice);
	return $notice_;
} 
	


function sut_dummy($nc){
	global $sep;
	$ret="";
	for ($k=0;$k<$nc;$k++) $ret .= '|';	
	return $ret;
}

function sut_bibliolevel(){
	global $campi, $sep;
	return '';
}


function sut_typedoc(){
	global $campi, $sep;
	return $sep;
}

// AUTHORS: max 9 authors 
// if n.authors > 3 all in 702
function sut_authors(){
	global $campi, $sep;
	$ret=""; $aay=array('','','','','','');
	$xx=explode('/',$campi['Title']);
	$at=explode(';',$xx[1]);
	// per il momento gestisco solo un autore principale,nonostantel' "esplosione" :-)
	$x=$campi['Authors'];
	if (strpos($x,$sep)>0) $ax=explode($sep,$x);
	else $ax=array($x);
	$ax[0]=trim($ax[0]);
	// invece gli autori secondari li "esplodiamo" dal campo nomi
	$y=$campi['Names'];
	$pattern = "/ (\#\{)([^\}]+)(\}) /";
	$y= preg_replace($pattern, "|", $y);

    //se gli autori principali sono nel campo Names li togliamo
	$pattern ="/".$ax[0]."\|/";
	$y= preg_replace($pattern, "", $y);
	$ay=explode($sep,$y);

	//mettiamo i primi 6 autori secondari in $aay
	for ($k=0;$k<count($ay);$k++){
		$aay[$k]=$ay[$k];
	}	

    //search respons.functions and redefinition of resp.levels
	$i=0;
	$axy=array('','','','','','','','','');
	$afn=array('','','','','','','','','');
	$axy[0]=$ax[0];
	for ($k=0;$k<6 ; $k++)
	{
		$a=split(',',$aay[$k]);
		$fn="";
		
		if ($a[0]!='') 	$fn=sut_autfn($a,$at); //search for author's function
			
		$axy[$k+2]=$ay[$k];
		$afn[$k+2]=$fn;
		
	}	
	for ($k=0;$k<9;$k++){
		$a=split(',',$axy[$k]);
		$ret.=$a[0].$sep.$a[1].$sep.$afn[$k].$sep; 		
	}	
	return $ret;
}

function sut_autfn($a,$at){
	global $campi,$fun;
	$ret="";
	$x=$at[0].'.'.$at[1];
	$ax=explode('-',$x);
	$ax[0]=preg_replace('/[A-Z]\./','_',$ax[0]);
	$af=explode('.',$ax[0]);
	//printr($af,'','AF');
	reset($fun);
	while (list($cf,$ff)=each($fun)){
		$ff=str_replace('/','\/',$ff);
		for ($k=0;$k<count($af);$k++){
			if ($af[$k]){	
				if (strpos($ff,'|')>0)  // multifunc desc
					$aff=explode('|',$ff);
					else
						$aff=array($ff);
				foreach ($aff as $dummykey=>$fx){									
					$patt="/$fx/i" ;
					if (preg_match($patt,$af[$k])){ //cerca funzione
						if (strpos($af[$k],$a[0])){ //cerca cognome
							$ret=$cf; 				//funzione identificata !!!
							//$k=9999 ;
							break 3;
						} 
					}
				}
			}
		}
	}
	return $ret;
}	

function sut_title(){
	global $campi, $sep;
	$ret="";
	$x=explode('/',$campi['Title']);

	$x[0]=str_replace('*','',$x[0]);
	$x[0]=str_replace('<<','',$x[0]);
	$x[0]=str_replace('>>','',$x[0]);
	$x[0]=str_replace('<','',$x[0]);
	$x[0]=str_replace('>','',$x[0]);
	$x[0]=preg_replace('/([1-9])\:/','vv $1 ',$x[0]);

	$t=explode(':',$x[0],2);    // titolo e complemento del titolo

	preg_match('/(vv\s[1-9]\s)/',$t[0],$vol);
	if ($vol[0])	$t[0]=str_replace($vol[0],'',$t[0])." ".str_replace('vv','',$vol[0]);

	//printr($t,'','TITOLO');
	$ret=$t[0].$sep.$sep.$sep.str_replace(' :',' ',$t[1]).$sep;
	$ret=str_replace('*','',$ret);
	return $ret;
}

function sut_edition(){
	global $campi, $sep;
	$ret=trim($campi['Edition']).$sep;
	return $ret;
}

function sut_editor(){
	global $campi, $sep;
	$ax=array('','');
	$bx=array('','');
	$ax=explode(':',$campi['Editor']);
	$bx=explode(',',$ax[1]);
	$ret=$ax[0].$sep.$bx[0].$sep.$bx[1].$sep;
	
	return $ret;
}

function sut_collation(){
	global $campi, $sep;
	$axyz=array('','','','');
	$ret="";
	$t=$campi['Collation'];
	
	$ax=explode(':',$t);
	if (count($ax)>1) {
		$axyz[0]=$ax[0];
		$ay=explode(';',$ax[1]);
		$axyz[1]=$ay[0];
		if (count($ax)>1) $axyz[2]=$ay[1];
	}else{
		$az=explode(';',$t);
		$axyz[0]=$az[0];
		if (count($az)>1) $axyz[2]=$az[1];
	}
	
	$ret=$axyz[0].$sep.$axyz[1].$sep.$axyz[2].$sep.$axyz[3].$sep;
	//printr($xx,'','COLLAZIONE');
	return $ret;
}

function sut_collection(){
	global $campi, $sep;
	$pattern = "/(\#\{)([^\}]+)(\})/";
	$ret= preg_replace($pattern, "", $campi['Collection']);
	$ret=str_replace('*','',$ret);
	
	$ret=trim($ret);
	$ret.=$sep.$sep.$sep.$sep;
	
	return $ret;
}

function sut_notes(){
	global $campi, $sep;
	return $sep;
}

function sut_numbers(){
	global $campi, $sep;
	$x=explode(' ',trim($campi['Numbers']));
	$s=array() ; $n=array() ; $i=0 ;$j=0; $l=-1;
	for ($k=0;$k<count($x);$k++){
		if(is_numeric(substr($x[$k],0,1))){
			$n[$j]=$x[$k] ; $j++;
		}else{
			if ($x[$k]=='ISBN') $l=$i;
			$v[$i]=$x[$k] ; $i++;		
		}
	}
	if ($l>-1) $ret=$n[$l].$sep;
		else  $ret=$n[0].$sep;
	return $ret;
}

function sut_names(){
	global $campi, $sep;
	return $sep;
}

function sut_freeindex(){
	global $campi, $sep;
	$ret=str_replace('|',' ',$campi['FreeIndex']);
	$ret=trim(preg_replace('/\b.\s|\s\-\s/',' ',$ret));
	$ret .= $sep;
	return $ret;
}

function sut_decimalindex(){
	global $campi, $sep;
	$ret='';
	$d=trim($campi['DecimalIndex']);
	$dd=explode(' ',$d);
	$ret=$dd[0];
	$ret.=$sep;
	return $ret;
}

function sut_country(){
	global $campi, $sep;
	$ret=trim($campi['Country']).$sep;
	return $ret;
}

function sut_lang(){
	global $campi, $sep;
	global $codelang;
	$cl=array_search(strtolower(trim($campi['Lang'])),$codelang);
	$ret=$cl.$sep;
	return $ret;
}

function sut_Linked_notices(){
	global $campi, $sep;
	$ret=dummy(1);
	return $ret;
}

function sut_Places(){
	global $campi, $sep;
	$ret=dummy(1);
	return $ret;
}

function sut_Series(){
	global $campi, $sep;
	$ret=dummy(1);
	return $ret;
}	
?>
