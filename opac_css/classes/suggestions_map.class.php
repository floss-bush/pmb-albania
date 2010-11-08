<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions_map.class.php,v 1.10 2009-05-16 10:52:43 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path.'/parser.inc.php');
require_once($class_path.'/suggestions.class.php');
require_once($class_path.'/suggestions_origine.class.php');


class suggestions_map {
	

	var $allowedflow=array();	//Tableau des flux autorises
	var $workflow=array();		//Tableau des flux definis
	
	var $firststate="";			//Nom de l'état initial
	var $laststate="";			//Nom de l'état final

	var $states=array();		//Tableau des etats possibles
	var $transitions=array();	//Tableau des transitions possibles pour un etat
	var $id_to_name=array();	//Tableau de correspondance id=>name pour les etats
	 
	 
	//Constructeur.	 
	function suggestions_map() {
		
		global $dbh;
		global $include_path;

		//Recherche des fichiers XML de description
		$map_file=$include_path.'/suggestions/suggestions_map.xml';
		$map_file_subst=$include_path.'/suggestions/suggestions_map_subst.xml';
		
		$xml=file_get_contents($map_file,"r") or die("Can't find XML file $map_file");
		if (file_exists($map_file_subst)) {
			$xml_subst=file_get_contents($map_file_subst,"r");		
		}
		
		//Parse le fichier dans un tableau	
		$param=_parser_text_no_function_($xml, "SUGGESTS");
		
		
		//Tableau des états ([nom etat]=>[valeurs etat])
		for ($i=0;$i<count($param['STATES'][0]['STATE']);$i++) {
			$this->states[$param['STATES'][0]['STATE'][$i]['NAME']]=$param['STATES'][0]['STATE'][$i];
			$this->id_to_name[$param['STATES'][0]['STATE'][$i]['ID']]=$param['STATES'][0]['STATE'][$i]['NAME'];
		}
/*		print 'states= <pre>';
		print_r ($this->states);
		print '</pre><br /><br />';
*/
/*		print 'id_to_name= <pre>';
		print_r ($this->id_to_name);
		print '</pre><br /><br />';
*/
		
		//Tableau des flux autorises
		$this->allowedflow=$param['ALLOWEDFLOW'][0];
/*		print 'allowedflow= <pre>';
		print_r ($this->allowedflow);
		print '</pre><br /><br />';
*/		
		
		//Tableau des flux definis
		if ($xml_subst) {
			$param_subst=_parser_text_no_function_($xml_subst, "SUGGESTS");
			$this->workflow=$param_subst['WORKFLOW'][0];
			
		} else {
			$this->workflow=$param['WORKFLOW'][0];
		}
/*		print 'workflow= <pre>';
		print_r ($this->workflow);
		print '</pre><br /><br />';
*/

		//Nom etat initial
		$this->firststate=$this->workflow['FIRSTSTATE'][0]['value'];
		
/*		print 'firststate= ';
		print ($this->firststate);
		print '<br /><br />';
*/		
		//Nom etat final
		$this->laststate=$this->workflow['LASTSTATE'][0]['value'];
/*		print 'laststate= ';
		print ($this->laststate);
		print '<br /><br />';
*/		

		//Tableau des transitions possibles pour un etat
		for($i=0;$i<count($this->workflow['FROMSTATE']);$i++) {
			$to_transitions=array();
			
			for($j=0;$j<count($this->workflow['FROMSTATE'][$i]['TOSTATE']); $j++) {
							
				$to_transitions[] = $this->workflow['FROMSTATE'][$i]['TOSTATE'][$j]['NAME'];
			}
			$this->transitions[$this->workflow['FROMSTATE'][$i]['NAME']]=$to_transitions;

		}
/*		print 'transitions= <br /><pre>';
		print_r ($this->transitions);
		print '</pre><br /><br />';
*/		
		//Test workflow
		if (!$this->workflowCheck()) die ("Workflow error ...") ;
	}	
	
	
	
	//Verification du workflow
	function workflowCheck() {
		
		//Etat initial
		$allowed_firststates=array();
		for ($i=0;$i<count($this->allowedflow['FIRSTSTATE']);$i++) {
			$allowed_firststates[]=$this->allowedflow['FIRSTSTATE'][$i]['value'];
		}
		if (in_array($this->workflow['FIRSTSTATE'][0]['value'], $allowed_firststates)===false) return FALSE;		
		
		//Etat final
		$allowed_laststates=array();
		for ($i=0;$i<count($this->allowedflow['LASTSTATE']);$i++) {
			$allowed_laststates[$i]=$this->allowedflow['LASTSTATE'][$i]['value'];
		}
		if (in_array($this->workflow['LASTSTATE'][0]['value'], $allowed_laststates)===false) return FALSE;		
		
		//Enchainements
		$allowed_transitions=$this->allowedflow['FROMSTATE'];
		$work_transitions=$this->workflow['FROMSTATE'];

		for($i=0;$i<count($allowed_transitions);$i++) {

			$allowed_from_states[$i]=$allowed_transitions[$i]['NAME'];	
			for ($j=0;$j<count($allowed_transitions[$i]['TOSTATE']);$j++) {
				$allowed_to_states[$allowed_from_states[$i]][]=$allowed_transitions[$i]['TOSTATE'][$j]['NAME'];
			}
			
		}

		for($i=0;$i<count($work_transitions);$i++) {

			$work_from_states[$i]=$work_transitions[$i]['NAME'];	
			for ($j=0;$j<count($work_transitions[$i]['TOSTATE']);$j++) {
				$work_to_states[$work_from_states[$i]][]=$work_transitions[$i]['TOSTATE'][$j]['NAME'];
			}
		}
	
		for($i=0;$i<count($work_from_states);$i++) {
			
			if(in_array($work_from_states[$i],$allowed_from_states)===false) return FALSE;
					
			for ($j=0;$j<count($work_to_states[$work_from_states[$i]]);$j++) {
				
				if (in_array($work_to_states[$work_from_states[$i]][$j], $allowed_to_states[$work_from_states[$i]])===false) {
					return FALSE;
				}
				
			}
			
		}

		return TRUE;
	}
	
	//Retourne l'attribut ID associe à un etat
	function getState_ID($state_name) {
		
		return $this->states[$state_name]['ID'];
	}
	
	
	//Retourne l'attribut ADD associe à un etat	
	function getState_ADD($state_name) {
		
		return $this->states[$state_name]['ADD'];
		
	}


	//Retourne l'attribut DISPLAY associe à un etat
	function getState_DISPLAY($state_name) {
		
		return $this->states[$state_name]['DISPLAY'];
	}


	//Retourne l'attribut MERGE associe à un etat
	function getState_MERGE($state_name) {
		
		return $this->states[$state_name]['MERGE'];
	}


	//Retourne l'attribut CATALOG associe à un etat
	function getState_CATALOG($state_name) {
		
		return $this->states[$state_name]['CATALOG'];
	}


	//Retourne l'attribut CATEG associe à un etat
	function getState_CATEG($state_name) {
		
		return $this->states[$state_name]['CATEG'];
	}


	//Retourne l'attribut COMMENT associe à un etat
	function getState_COMMENT($state_name) {
		
		return $this->states[$state_name]['COMMENT'];
	}


	//Retourne le tableau des actions associees a un etat
	function getState_ACTION($state_name) {
		
		return $this->states[$state_name]['ACTION'];
	}


	//Construction du sélecteur en fonction de la liste des états possibles et de l'état en cours
	function getStateSelector() {
		
		global $msg, $charset;
			
		$selector="<select class='saisie-25em' id='statut' name='statut' onchange=\"submit();\" >";
		$selector.="<option value='-1'>".htmlentities($msg['acquisition_sug_tous'], ENT_QUOTES, $charset)."</option>";
		
		foreach ($this->states as $name=>$content) {
			if ($this->getState_DISPLAY($name) != 'NO') {
				$selector.= "<option value='".$this->getState_ID($name)."'>";
				$selector.= htmlentities($msg[$this->getstate_COMMENT($name)], ENT_QUOTES, $charset);
				$selector.= "</option>";
			}
		}

		$selector.="</select>";
		
		return $selector;
	}



	//Construction de la liste des boutons en fonction de l'état en cours
	function getButtonList($state='-1') {
		
		global $msg, $charset;
		
		$button_list="";
		if ($state == '-1') { //Tous états possibles

			$button_list.= $this->getButtonList_MERGE();
			$button_list.='&nbsp;';
			foreach($this->states as $name=>$value) {

				if( $name != 'DELETED' && $name != 'TODO' ) {
					eval('$button_list.= $this->getButtonList_'.$name.'();');
					$button_list.='&nbsp;';
				}
			}
				
		} else {
		
			$state_name=$this->id_to_name[$state];
			$tostates=$this->transitions[$state_name];
			
			if ($this->getState_MERGE($state_name)!='NO') {
				$button_list.= $this->getButtonList_MERGE();
				$button_list.= '&nbsp;';
			}
			
			foreach($tostates as $id=>$name) {

				if( $name != 'DELETED' && $name != 'TODO' ) {
					eval('$button_list.= $this->getButtonList_'.$name.'();');
					$button_list.='&nbsp;';
				}
			}
						
		}
				
		return $button_list;
	}


	function getButtonList_MERGE() {
		
		global $msg;
		
		return "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_fus]' onClick=\"chk_MERGE(); \" />";
	}
	

	function getButtonList_VALIDATED() {
		
		global $msg;
		
		return "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_val]' onClick=\"chk_VALIDATED();\" />";
	}


	function getButtonList_REJECTED() {
		
		global $msg;
		
		return "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_rej]' onClick=\"chk_REJECTED(); \" />";
	}
	

	function getButtonList_CONFIRMED() {
	
		global $msg;
	
		return "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_con]' onClick=\"chk_CONFIRMED(); \" />";
	}
	

	function getButtonList_GIVENUP() {
		
		global $msg;
		
		return "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_aba]' onClick=\"chk_GIVENUP(); \" />";
	}
	

	function getButtonList_ORDERED() {
		
		global $msg;
		
		return "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_cde]' onClick=\"chk_ORDERED(); \" />";
	}
	

	function getButtonList_ESTIMATED() {
		
		global $msg;
		
		return "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_dev]' onClick=\"chk_ESTIMATED(); \" />";
	}


	function getButtonList_RECEIVED() {
		
		global $msg;
		
		return "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_rec]' onClick=\"chk_RECEIVED(); \" />";
	}
	

	function getButtonList_FILED() {
		
		global $msg;
		
		return "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_arc]' onClick=\"chk_FILED(); \" />";
	}

	
	function getButtonList_TODO($state='-1') {
		
		global $msg;
		
		$button = "<input type='button' class='bouton_small' value='$msg[acquisition_sug_bt_todo]' onClick=\"chk_TODO(); \" />";

		if ($state == '-1') { //Tous états possibles

			return $button;
			
		} else {

			$state_name=$this->id_to_name[$state];
			$tostates=$this->transitions[$state_name];
			
			if (in_array('TODO', $tostates)) {
						
				return $button;
			}
		}
		return "";
	}	


	function getCategModifier($state='-1', $num_categ='-1', $nb_per_page) {
		
		global $msg, $charset;
		
		$selector = "<label class='etiquette' >".htmlentities($msg['acquisition_sug_sel_categ'],ENT_QUOTES, $charset)."</label>&nbsp;"; 
		$selector.= "<select class='saisie-25em' id='to_categ' name='to_categ' onChange=\"chk_CATEG(); \" />";
		$selector.= "<option value= '0'>".htmlentities($msg['acquisition_sug_sel_no_categ'], ENT_QUOTES, $charset)."</option>";
		$tab_categ = suggestions_categ::getCategList();
		foreach ($tab_categ as $id_categ=>$lib_categ) {
			$selector.= "<option value='".$id_categ."' >".htmlentities($lib_categ, ENT_QUOTES, $charset)."</option>";
		}
		$selector.= "</select>";

		$script = "
		<script type='text/javascript' >
		//Affecte les elements coches a une categorie
		function chk_CATEG() {
			if(document.forms['sug_list_form'].elements['to_categ'].value == '0') return false;
			if(!verifChk(1)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_tocateg']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&action=to_categ&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}
		</script>";

		$selector.=$script;

		if ($state == '-1') { //Tous états possibles
			return $selector;
		} else {
			$state_name=$this->id_to_name[$state];
			if ($this->getState_CATEG($state_name) == 'YES') {
				return $selector;
			}
		}
		return "";
	}	


	function getButtonList_DELETED($state='-1') {
		
		global $msg;

		$button = "<input type='button' class='bouton_small' value='$msg[63]' onClick=\"chk_DELETED();\" />";

		if ($state == '-1') { //Tous états possibles

			return $button;
			
		} else {
		
			$state_name=$this->id_to_name[$state];
			$tostates=$this->transitions[$state_name];
			
			if (in_array('DELETED', $tostates)) {
						
				return $button;
			}
		}
		return "";
	}	


	//Retourne le bouton supprimer dans le formulaire de modification
	function getButton_DELETED($state,$id_bibli,$id_sug) {
		
		global $msg;

		$button = "<input type='button' class='bouton' value='$msg[63]' onClick=\"
				r = confirm('".$msg['acquisition_sug_sup']."');
				if(r){			
					document.location='./acquisition.php?categ=sug&action=delete&id_bibli=".$id_bibli."&id_sug=".$id_sug."';
				}
				return false; \" />";
		
		$mask = $this->getMask_FILED();
		if (($state & $mask) == $mask ) {	//Archive
			return $button;			
		}
		$state = ($state & ~$mask);
		$state_name=$this->id_to_name[$state];
		$tostates=$this->transitions[$state_name];
		
		if (in_array('DELETED', $tostates)) {
									
			return $button;
		}
		return "";
	}	


	//Retourne le bouton cataloguer dans le formulaire de modification
	function getButton_CATALOG($state,$id_bibli,$id_sug) {
		
		global $msg;

		$button = "<input type='button' class='bouton' value='$msg[acquisition_sug_cat]' onClick=\"
						document.forms['sug_modif_form'].setAttribute('action', './acquisition.php?categ=sug&action=catalog&id_bibli=".$id_bibli."&id_sug=".$id_sug."');
						document.forms['sug_modif_form'].submit(); \" />";

		$state_name=$this->id_to_name[$state];
			
		if ($this->getState_CATALOG($state_name)=='YES') {
						
			return $button;
		}
		return "";
	}	
	
	
	//Retourne un masque pour tenir compte des statuts archives 
	function getMask_FILED() {
		
		$mask=(int)($this->states['FILED']['ID']);
		return $mask;
	}


	//Retourne le commentaire Html associe a un etat
	function getHtmlComment($state) {
		
		global $msg,$charset;
		
		$mask = $this->getMask_FILED();
		$disp_state = ($state & ~$mask);
		$comment = htmlentities($msg[$this->getState_COMMENT($this->id_to_name[$disp_state])], ENT_QUOTES, $charset);
		if (($state & $mask) == $mask) $comment = '<s>'.$comment.'</s>';
		
		return $comment;
	}


	//Retourne le commentaire PDF associe a un etat
	function getPdfComment($state) {
		
		global $msg,$charset;
		
		$mask = $this->getMask_FILED();
		$disp_state = ($state & ~$mask);
		$comment = htmlentities($msg[$this->getState_COMMENT($this->id_to_name[$disp_state])], ENT_QUOTES, $charset);
		if (($state & $mask) == $mask) $comment.= "\n".$msg['acquisition_sug_arc'];
		
		return $comment;
	}


	//Construction de la liste des boutons en fonction de l'état en cours
	function getScriptList($state='-1', $num_categ='-1',$nb_per_page) {
		
		global $msg, $charset;
		
		$script_list="";
		if ($state == '-1') { //Tous états possibles

			$script_list.= $this->getScriptList_MERGE($num_categ, $nb_per_page);
			foreach($this->states as $name=>$value) {
				eval('$script_list.= $this->getScriptList_'.$name.'('.$num_categ.', '.$nb_per_page.');');
			}
				
		} else {
		
			$state_name=$this->id_to_name[$state];
			$tostates=$this->transitions[$state_name];
			
			if ($this->getState_MERGE[$state_name]!='NO') {
				$script_list.= $this->getScriptList_MERGE($num_categ, $nb_per_page);
			}
			
			foreach($tostates as $id=>$name) {
				eval('$script_list.= $this->getScriptList_'.$name.'('.$num_categ.', '.$nb_per_page.');');
			}
						
		}
				
		return $script_list;
	}


	function getScriptList_MERGE($num_categ='-1',$nb_per_page) {
		
		global $msg;
		
		$script = "
		//Fusionne les éléments cochés
		function chk_MERGE() {
			if(!verifChk(2)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_fus']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&action=fusChk&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}";
		
		return $script;
	}
	

	function getScriptList_VALIDATED($num_categ='-1',$nb_per_page) {
		
		global $msg;
		
		$script = "
		//Valide les éléments cochés
		function chk_VALIDATED() {
			if(!verifChk(1)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_val']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=VALIDATED&action=list&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}";

		return $script;
	}


	function getScriptList_REJECTED($num_categ='-1',$nb_per_page) {
		
		global $msg;

		$script = "
		//Rejete les éléments cochés
		function chk_REJECTED() {
			if(!verifChk(1)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_rej']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=REJECTED&action=list&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}";

		return $script;		
	}
	

	function getScriptList_CONFIRMED($num_categ='-1',$nb_per_page) {
		
		global $msg;
		
		$script = "
		//Confirme les éléments cochés
		function chk_CONFIRMED() {
			if(!verifChk(1)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_con']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=CONFIRMED&action=list&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}";

		return $script;		
	}
	

	function getScriptList_GIVENUP($num_categ='-1',$nb_per_page) {
		
		global $msg;
		
		$script = "
		//Abandonne les éléments cochés
		function chk_GIVENUP() {
			if(!verifChk(1)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_aba']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=GIVENUP&action=list&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}";
	
		return $script;		
	}
	

	function getScriptList_ORDERED($num_categ='-1',$nb_per_page) {
		
		global $msg;
		global $acquisition_sugg_to_cde;
		
		if ($acquisition_sugg_to_cde) {
					
			$script.="
				//Commande les éléments cochés
				function chk_ORDERED() {
					if(!verifChk(1)) return false;
					r = confirm(\"".$msg['acquisition_sug_msg_cde']."\");
					if (r) {
						document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=cde&action=from_sug');
						document.forms['sug_list_form'].submit();
						return true;	
					}
					return false;
				}";
	
		} else {			
				
			$script.="
				//Commande les éléments cochés
				function chk_ORDERED() {
					if(!verifChk(1)) return false;
					r = confirm(\"".$msg['acquisition_sug_msg_cde']."\");
					if (r) {
						document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=ORDERED&action=list&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
						document.forms['sug_list_form'].submit();
						return true;	
					}
					return false;
				}";
		}

		return $script;		
	}
	

	function getScriptList_ESTIMATED($num_categ='-1',$nb_per_page) {
		
		global $msg;
		global $acquisition_sugg_to_cde;
		
		if ($acquisition_sugg_to_cde) {
					
			$script.="
				//Devise les éléments cochés
				function chk_ESTIMATED() {
					if(!verifChk(1)) return false;
					r = confirm(\"".$msg['acquisition_sug_msg_dev']."\");
					if (r) {
						document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=devi&action=from_sug');
						document.forms['sug_list_form'].submit();
						return true;	
					}
					return false;
				}";
	
		} else {			
				
			$script.="
				//Devise les éléments cochés
				function chk_ESTIMATED() {
					if(!verifChk(1)) return false;
					r = confirm(\"".$msg['acquisition_sug_msg_dev']."\");
					if (r) {
						document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=ESTIMATED&action=list&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
						document.forms['sug_list_form'].submit();
						return true;	
					}
					return false;
				}";
		}

		return $script;		
	}
	

	function getScriptList_RECEIVED($num_categ='-1',$nb_per_page) {
		
		global $msg;
		
		$script = "
		//Reçoit les éléments cochés
		function chk_RECEIVED() {
			if(!verifChk(1)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_rec']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=RECEIVED&action=list&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}";

		return $script;		
	}
	

	function getScriptList_FILED($num_categ='-1',$nb_per_page) {
		
		global $msg;

		$script = "
		//Archive les éléments cochés
		function chk_FILED() {
			if(!verifChk(1)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_arc']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=FILED&action=list&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}";

		return $script;		
	}

	
	function getScriptList_TODO($num_categ='-1',$nb_per_page) {
		
		global $msg;

		$script = "
		//Archive les éléments cochés
		function chk_TODO() {
			if(!verifChk(1)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_todo']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=TODO&num_categ=".$num_categ."&nb_per_page=".$nb_per_page."');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}";

		return $script;		
	}	


	function getScriptList_DELETED() {
		
		global $msg;
		
		$script = "
		//Supprime les éléments cochés
		function chk_DELETED() {
			if(!verifChk(1)) return false;
			r = confirm(\"".$msg['acquisition_sug_msg_sup']."\");
			if (r) {
				document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&transition=DELETED');
				document.forms['sug_list_form'].submit();
				return true;	
			}
			return false;
		}";
		
		return $script;
	}	


	//Effectue une transition 
	function doTransition($toname,$chk){
		
		global $acquisition_email_sugg;
		
		foreach($chk as $key=>$id_sug){
			
			if ($id_sug) {
			
				$sug = new suggestions($id_sug);
				$state_name = $this->getStateNameFromId($sug->statut);
				
				if ($state_name) { //Statut existant
	
					if (in_array($toname, $this->transitions[$state_name])) {
						if ($this->getState_ADD($toname)== 'YES' ){
							$sug->statut = (int)($sug->statut) | (int)($this->getState_ID($toname));
						} else {
							$sug->statut = (int)($this->getState_ID($toname));
						}
						if ($acquisition_email_sugg) $this->sendmail($sug);
					}
					$sug->save();
	
				}
			}
		}
		
		foreach($chk as $key=>$id_sug){
			
			if ($id_sug) {
	
				$sug = new suggestions($id_sug);
				$state_name = $this->getStateNameFromId($sug->statut);
	
				if ($state_name) { //Statut existant
	
					$tab_action=$this->getState_ACTION($state_name);
					
					if (is_array($tab_action)){
						
						foreach($tab_action as $dummykey=>$action){
		
							switch ($action['NAME']) {
								
								case 'GOTOFIRSTSTATE' :
									$sug->statut = $this->getState_ID($this->firststate);
									$sug->save();
									break;
									
								case 'DELETE' :
									$sug->delete();
									suggestions_origine::delete($id_sug);	
									break;
							}
						}
					}
					
				} else { //statut inexistant
				
					if ($toname == 'DELETE'){ //Si transition = DELETE, on supprime la suggestion
						$sug->delete();
						suggestions_origine::delete($id_sug);	
					}
					
				}
			}
		}
	}
	
	
	//Change la categorie pour un tableau de suggestions
	function changeCateg($chk, $to_categ) {
		
		foreach($chk as $key=>$id_sug){
			
			$sug = new suggestions($id_sug);

			$state_name = $this->getStateNameFromId($sug->statut);
			
			if ($this->getState_CATEG($state_name)== 'YES'  && suggestions_categ::exists($to_categ) ){
				$sug->num_categ = $to_categ;
				$sug->save();
			}
		}
	}


	//Retourne l'id de l'etat de depart	
	function getFirstStateId() {

		return $this->getState_ID($this->firststate);
	}


	//Retourne le nom de l'etat a partir de l'id
	function getStateNameFromId($state_id) {

		$mask = $this->getMask_FILED();
		if (($state_id & $mask)==$mask ) $state_id=$mask;
		
		return $this->id_to_name[$state_id];
	}


	//Fonction d'envoi de mail  
	function sendMail($sug) {
		
		global $dbh, $msg, $charset;
		global $biblio_name,$biblio_email,$biblio_phone;
		global $acquisition_mel_rej_obj, $acquisition_mel_rej_cor;
		global $acquisition_mel_con_obj, $acquisition_mel_con_cor;
		global $acquisition_mel_aba_obj, $acquisition_mel_aba_cor;
		global $acquisition_mel_cde_obj, $acquisition_mel_cde_cor;
		global $acquisition_mel_rec_obj, $acquisition_mel_rec_cor;
		
		$mask = $this->getMask_FILED();
		
		if (($sug->statut & $mask)==0 ) $state=$sug->statut;
			else $state=$mask; 
		$state_name = $this->id_to_name[$state];
		
		switch($state_name) {
			
			case 'REJECTED' :	//Rejet
				$objet = $acquisition_mel_rej_obj;
				$corps = $acquisition_mel_rej_cor;
				break;
			case 'CONFIRMED' :	//Confirmation
				$objet = $acquisition_mel_con_obj;
				$corps = $acquisition_mel_con_cor;
				break;
			case 'GIVENUP' :	//Abandon
				$objet = $acquisition_mel_aba_obj;
				$corps = $acquisition_mel_aba_cor;
				break;
			case 'ORDERED' :	//Commande
				$objet = $acquisition_mel_cde_obj;
				$corps = $acquisition_mel_cde_cor;
				break;
			case 'RECEIVED' :	//Réception
				$objet = $acquisition_mel_rec_obj;
				$corps = $acquisition_mel_rec_cor;
				break;
			default :
				return;
				break;
		}
		
		$corps.="\n\n ".$msg['acquisition_sug_tit']." :\t ".$sug->titre."\n";
		if($sug->auteur) $corps.= $msg['acquisition_sug_aut']." :\t ".$sug->auteur."\n";
		if($sug->editeur) $corps.= $msg['acquisition_sug_edi']." :\t ".$sug->editeur."\n";
		if($sug->code) $corps.= $msg['acquisition_sug_cod']." :\t ".$sug->code."\n";
		if($sug->prix) $corps.= $msg['acquisition_sug_pri']." :\t ".$sug->prix."\n";
		if($sug->commentaires) $corps.= $msg['acquisition_sug_com']." :\t ".$sug->commentaires."\n";
		$corps.= "\n\n";
		
		$corps = str_replace('!!date!!', formatdate($sug->date_creation), $corps);
		
		$q = suggestions_origine::listOccurences($sug->id_suggestion);
		$list_orig = mysql_query($q, $dbh);
		while($row = mysql_fetch_object($list_orig)) {
	
			switch($row->type_origine){
				
				default:
				case '0' :
				 	$q = "SELECT nom, prenom, user_email FROM users where userid = '".$row->origine."' limit 1 ";
					$r = mysql_fetch_object(mysql_query($q, $dbh));
					$tonom = $r->prenom." ".$r->nom;
					$tomail = $r->user_email;			
					break;
				case '1' :
				 	$q = "SELECT empr_nom, empr_prenom, empr_mail FROM empr where id_empr = '".$row->origine."' limit 1 ";
					$r = mysql_fetch_object(mysql_query($q, $dbh));
					$tonom = $r->empr_prenom." ".$r->empr_nom;
					$tomail = $r->empr_mail;			
					break;
				case '2' :
					$tonom = $row->origine;
					$tomail = $row->origine;			
					break;
			}	
		
		
			if($tomail != '') {
				$res_envoi=mailpmb($tonom, $tomail, $objet, $corps, $biblio_name, $biblio_email,"Content-Type: text/plain; charset=\"$charset\"\n", "", "");
			}
		}
	
	}


}

?>