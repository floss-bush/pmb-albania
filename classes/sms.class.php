<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sms.class.php,v 1.3 2010-06-16 12:13:47 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition des classes d'envoit de sms selon opérateur

function explode_sms_param() {
	global $empr_sms_config;
	$ret=array();
	$param_list=explode(';',$empr_sms_config);	  
	if(is_array($param_list))
	foreach($param_list as $param){
		$p=explode('=',$param);	
		if(is_array($p)) $ret[$p[0]]=$p[1];
	}
	return $ret;
}

class smstrend {
	// propriétés
	
	// ---------------------------------------------------------------
	//	constructeur
	// ---------------------------------------------------------------
	function smstrend ($id_classement=0) {		
		$param_list=explode_sms_param();
		$this->login=$param_list["login"];
		$this->password=$param_list["password"];
		$this->tpoa=$param_list["tpoa"];
	}
	
	function send_sms($telephone, $message) {
		global $charset;
		$telephone=preg_replace("/[^0-9]/","",$telephone); 
		if ($telephone[0]=="0") $telephone="+33".substr($telephone,1); 
		else if ($telephone[0]!="+") return false;
		$fields=array(
			"login"=>$this->login,
			"password"=>$this->password,
			"mobile"=>$telephone,
			"messageQty"=>"GOLD",
			"messageType"=>"PLUS",
			"tpoa"=>$this->tpoa, //$object_message,
			"message"=>$message
		);
		if (strtoupper($charset)!="UTF-8") {			
			foreach ($fields as $key=>$val)$fields[$key]=utf8_encode($val);
		}
		foreach ($fields as $key=>$val) $post[]=$key."=".rawurlencode($val);
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://www.smstrend.net/fra/sendMessageFromPost.oeg");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&",$post));
		$r=curl_exec($ch);
		curl_close($ch);
		
		if($r=="OK") return true;
		return false;
	}

} // fin de déclaration de la classe sms_pmb
  
