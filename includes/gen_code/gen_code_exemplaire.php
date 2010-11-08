<?php

function init_gen_code_exemplaire($notice_id,$bull_id)
{
	global $dbh;
	$requete="select max(expl_cb)as cb from exemplaires WHERE expl_cb like 'GEN%'";
	$query = mysql_query($requete, $dbh);
	if(mysql_num_rows($query)) {	
    	if(($cb = mysql_fetch_object($query)))
			$code_exemplaire= $cb->cb;
		else $code_exemplaire = "GEN000000"; 	
	} else $code_exemplaire = "GEN000000"; 
	return $code_exemplaire;  	   						
}

function gen_code_exemplaire($notice_id,$bull_id,$code_exemplaire)
{
	$code_exemplaire++;
	return $code_exemplaire;
}