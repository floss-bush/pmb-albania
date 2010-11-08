<?php

function convert_txt_prisme($notice, $s, $islast, $isfirst, $param_path) {
	global $charset;
	
	$r_="+++";
	
	$notice = "<?xml version='1.0' encoding='$charset' ?>".$notice;
	
	$nt=_parser_text_no_function_($notice,"NOTICE");

	if ($nt["TY"][0]["value"]=="CHAPEAU") {
		$r['VALID']=false;
		$r['ERROR']="Notice ".$nt["REF"][0]["value"]." - Ignorée, c'est une notice chapeau !";
		$r['DATA']="";
		return $r;
	}
	
	if (!$nt["OP"][0]["value"]) 
		$nt["OP"][0]["value"]=$s["OP"][0]["value"];

	$r_.=$nt["REF"][0]["value"].";;".$nt["OP"][0]["value"].";;".$nt["DS"][0]["value"].";;".$nt["TY"][0]["value"].";;".$nt["URL"][0]["value"].";;";
	$r_.=$nt["GEN"][0]["value"].";;".$nt["AU"][0]["value"].";;".$nt["AUCO"][0]["value"].";;".$nt["AS"][0]["value"].";;";
	$r_.=$nt["DIST"][0]["value"].";;".$nt["TI"][0]["value"].";;".$nt["TN"][0]["value"].";;".$nt["COL"][0]["value"].";;";
	if ($nt["TY"][0]["value"]=="REVUE") {
		if (!$nt["TP"][0]["value"]) {
			$r['VALID']=false;
			$r['ERROR']="Notice ".$nt["REF"][0]["value"]." - ".$nt["TIT"][0]["value"]." : Article sans titre de périodique";
			$r['DATA']="";
			return $r;
		} else 
			$r_.=$nt["TP"][0]["value"].";;";
	} else 
		$r_.=";;";
	
	$r_.=$nt["SO"][0]["value"].";;".$nt["ED"][0]["value"].";;".$nt["ISBN"][0]["value"].";;".$nt["DP"][0]["value"].";;";
	$r_.=$nt["DATRI"][0]["value"].";;".$nt["ND"][0]["value"].";;";
	$r_.=$nt["NO"][0]["value"].";;".$nt["GO"][0]["value"].";;".$nt["HI"][0]["value"].";;".$nt["DENP"][0]["value"].";;";
	$r_.=$nt["DE"][0]["value"].";;".$nt["CD"][0]["value"].";;".$nt["RESU"][0]["value"].";;";
	
	$r['VALID'] = true;
	$r['ERROR'] = "";
	$r['DATA'] = $r_;
	return $r;
}

?>