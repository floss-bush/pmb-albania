<?php

function convert_txt_webprisme($notice, $s, $islast, $isfirst, $param_path) {
	$r_="+++";	
	$nt=_parser_text_no_function_($notice,"NOTICE");
	if (!$nt["OP"][0]["value"]) $nt["OP"][0]["value"]=$s["OP"][0]["value"];
	$r_.=$nt["DO"][0]["value"].";;".$nt["DS"][0]["value"].";;".$nt["OP"][0]["value"].";;";
	//$r_.=$nt["REF"][0]["value"].";;".$nt["DO"][0]["value"].";;".$nt["DS"][0]["value"].";;".$nt["OP"][0]["value"].";;";
	$r_.=$nt["NOM"][0]["value"].";;".$nt["SITE"][0]["value"].";;".$nt["MEL"][0]["value"].";;".$nt["DE"][0]["value"].";;";
	$r_.=$nt["COMMENT"][0]["value"].";;".$nt["DOC"][0]["value"].";;".$nt["LI"][0]["value"];
	
	$r['VALID'] = true;
	$r['ERROR'] = "";
	$r['DATA'] = $r_;
	return $r;
}

?>
