<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: parse_format.class.php,v 1.1 2010-08-11 10:08:23 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");


require_once ($include_path . "/misc.inc.php");

class parse_format {
	var $error; //Erreur
	var $error_message; //Message d'erreur
	var $environnement=array();

	function parse_format($filename='interpreter.inc.php') {
		global $include_path;
		global $func_format;
		global $var_format;
		
		require_once ( $include_path."/interpreter/$filename");	
		$this->func_format=$func_format;
		$this->var_format=$var_format;
		$this->var_return='';
	}

	
	function exec_function($function_name, $param_name, $param_number) {
		if(! $this->func_format[$function_name]) return " $function_name not found ";
		$ret = $this->func_format[$function_name]( $param_name, $this);
		return $ret;
	}
	
	function exec($cmd, & $i) { 
		$state = 0;
		$ret = "";
		$function_name='';
		for ($i; $i < strlen($cmd); $i++) {
			switch ($state) {
				case '0' : //state Normal
					switch ($cmd[$i]) {
						case '$' :
							$state = "get_param_name";
							$param_name = '';
							break;
						case '#' :
							$state = "get_function_name";
							break;
						default :
							break;
					}
					break;
				case 'get_param_name' : //get param name
					
					switch ($cmd[$i]) {
						case ';' :
							if($this->var_set){
								$this->var_set_name=$param_name;
								$this->var_set=0;
							}	
							return ($this->var_format[$param_name]);
							break;
						case '=' :
							$this->var_return=$param_name;
							return ($param_name);
							break;	
						default :
							$param_name .= $cmd[$i];
						break;
					}
					break;
				case 'get_function_name' : //get param name
	
					switch ($cmd[$i]) {
						case '(' :
							$param_number = 0;
							$param_name[$param_number] = '';
							$state = "get_function_param";
							if(($function_name == "SET") || ($function_name == "set")) $this->var_set=1;
							break;
						default :
							$function_name .= $cmd[$i];
							break;
					}
					break;
				case 'get_function_param' : //get param name
	
					switch ($cmd[$i]) {
						case '$' :
							$param_name[$param_number] .= $this->exec($cmd, $i);
							break;
						case '#' :
							$param_name[$param_number] .= $this->exec($cmd, $i);
							break;
						case ')' :
							if ($cmd[$i +1] == ';') { // fin d'une fonction par );
								$i++;
								if(($function_name == "SET") || ($function_name == "set")) {
									$this->var_format[$this->var_set_name]=$param_name[1];
									$this->var_set=0;
									return '';
								}else 
									return ($this->exec_function($function_name, $param_name, $param_number));		

							} else {
								$param_name[$param_number] .= ')';
							}
							break;
						case ',' :
							$param_number++;
							$param_name[$param_number] = '';
							break;
						default :
							if( ($cmd[$i]=='\\') && ( ($i+1) < strlen($cmd)) )$i++;
							$param_name[$param_number] .= $cmd[$i];
							break;
					}
					break;
				default :
				break;
			}
		}
		return $ret;
	}
		
	function exec_cmd() {
	
		$cmd=$this->cmd;
		
		$ret = "";
		for ($i = 0; $i < strlen($cmd); $i++) {
			switch ($cmd[$i]) {
				case '$' :
				case '#' :
					$return = $this->exec($cmd, $i);
					
					if(!$this->var_return){	
						//C'est le retour pour afficher
						$ret .=$return;
							
					}else{
						//C'est une affectation d'une variable
						$this->var_format[$this->var_return]=$this->exec($cmd, $i);
						$this->var_return='';
					}	
					if ($this->erreur == 1) {
						return -1;
					}
					break;
				default :
					if( ($cmd[$i]=='\\') && ( ($i+1) < strlen($cmd)) )$i++;
					$ret .= $cmd[$i];
					break;
			}
		}
		return $ret;
	}

	
	function exec_cmd_conso() {

	$cmd=$this->cmd;
	
		//$ret = "";
		for ($i = 0; $i < strlen($cmd); $i++) {
			switch ($cmd[$i]) {
				case '$' :
				case '#' :
					$return = $this->exec($cmd, $i);
					
					if(!$this->var_return && !is_array($return) ){	
						//C'est le retour pour afficher
						$ret .=$return;
							
					} elseif(is_array($return)){
						$ret = $return;
					} else{
						//C'est une affectation d'une variable
						$this->var_format[$this->var_return]=$this->exec($cmd, $i);
						$this->var_return='';
					}	
					if ($this->erreur == 1) {
						return -1;
					}
					break;
				default :
					if( ($cmd[$i]=='\\') && ( ($i+1) < strlen($cmd)) )$i++;
					$ret .= $cmd[$i];
					break;
			}
		}
		return $ret;
	}
	
	
}	
	
?>