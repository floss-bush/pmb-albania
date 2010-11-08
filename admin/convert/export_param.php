<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export_param.php,v 1.1 2009-05-04 15:09:03 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$include_path/templates/export_param.tpl.php");
require_once($class_path."/export_param.class.php");


switch($act){

	case 'update':
		if($sub=='paramopac'){
			$export_param_context = new export_param(EXP_GLOBAL_CONTEXT);
			$export_param_context->get_parametres(EXP_DEFAULT_OPAC);
			$export_param_context->update();
			$export_param_context->check_default_param();
		} elseif($sub=='paramgestion'){
			$export_param_context = new export_param(EXP_GLOBAL_CONTEXT);
			$export_param_context->get_parametres(EXP_DEFAULT_GESTION);
			$export_param_context->update();
			$export_param_context->check_default_param();
		}
		$act='';
		break;
	
	default:
		if($sub=='paramopac'){
			$export_param_opac = new export_param(EXP_DEFAULT_OPAC);
			$export_param_opac->check_default_param();
		}
		else {
			$export_param_gestion = new export_param(EXP_DEFAULT_GESTION);
			$export_param_gestion->check_default_param();
		}
		
		break;
}
		
$form_entete_param = str_replace('!!form_param!!',$form_param,$form_entete_param);
print $form_entete_param;



?>