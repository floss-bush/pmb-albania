<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: workflow.class.php,v 1.1 2009-10-01 13:29:24 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path.'/parser.inc.php');

class workflow {
	
	
	var $states_com = array();
	var $object = array();
	var $object_states = array();
	var $object_states_by_id = array();	
	var $object_types = array();
	var $object_types_by_id = array();
	var $object_name = '';
	var $object_workflow = array();
	var $object_startstate = array();
	var $object_transitions = array();
	
	/*
	 * Constructeur
	 */
	function workflow($obj_name,$workflow_name=''){
		
		global $include_path;
		
		$this->object_name = $obj_name;
		
		$file = $include_path."/demandes/workflow.xml";
		$file_subst = $include_path."/demandes/workflow_subst.xml";
		
		if (file_exists($file_subst)) {
			$xml=file_get_contents($file_subst,"r");		
		} else $xml=file_get_contents($file,"r") or die("Can't find XML file $file");
		
		//Parse le fichier dans un tableau	
		$param=_parser_text_no_function_($xml,"STRUCTURE");
		
		//Liste des états généraux (nom=>libellé)
		for($i=0;$i<count($param['STATES'][0]['STATE']);$i++){
			$this->states_com[$param['STATES'][0]['STATE'][$i]['NAME']] = $param['STATES'][0]['STATE'][$i]['COMMENT'];
		}

		//Chargement de l'objet
		for($i=0;$i<count($param['OBJECTS']);$i++){
			for($j=0;$j<count($param['OBJECTS'][$i]['OBJECT']);$j++){
				$nom = $param['OBJECTS'][$i]['OBJECT'][$j]['NAME'];
				if($nom == $this->object_name){
					$this->object = $param['OBJECTS'][$i]['OBJECT'][$j];
				}	
			}
		}
		
		//Chargement des attributs de l'objet
		
		//Etats
		for($i=0;$i<count($this->object['STATES'][0]['STATE']);$i++){
			$this->object_states_by_id[$this->object['STATES'][0]['STATE'][$i]['ID']] = $this->object['STATES'][0]['STATE'][$i]['NAME'];
			$this->object_states[$this->object['STATES'][0]['STATE'][$i]['NAME']]['ID'] = $this->object['STATES'][0]['STATE'][$i]['ID'];
			$this->object_states[$this->object['STATES'][0]['STATE'][$i]['NAME']]['COMMENT'] = $this->getStateCommentById($this->object['STATES'][0]['STATE'][$i]['ID']);
		}	
		//Types
		for($i=0;$i<count($this->object['TYPES'][0]['TYPE']);$i++){
			$this->object_types_by_id[$this->object['TYPES'][0]['TYPE'][$i]['ID']] = $this->object['TYPES'][0]['TYPE'][$i]['NAME'];
			$this->object_types[$this->object['TYPES'][0]['TYPE'][$i]['NAME']]['ID'] = $this->object['TYPES'][0]['TYPE'][$i]['ID'];
			$this->object_types[$this->object['TYPES'][0]['TYPE'][$i]['NAME']]['COMMENT']= $this->object['TYPES'][0]['TYPE'][$i]['COMMENT'];
		}	
		//Workflow
		for($i=0;$i<count($this->object['WORKFLOW']);$i++){
			if($this->object['WORKFLOW'][$i]['NAME'] == $workflow_name)
				$this->object_workflow = $this->object['WORKFLOW'][$i];
		}
		//Transitions possibles
		for($i=0;$i<count($this->object_workflow['SOURCE']);$i++) {
			$cibles=array();
			
			for($j=0;$j<count($this->object_workflow['SOURCE'][$i]['TARGET']); $j++) {
				$cibles[] = $this->object_workflow['SOURCE'][$i]['TARGET'][$j]['NAME'];
			}
			$this->object_transitions[$this->object_workflow['SOURCE'][$i]['NAME']]=$cibles;

		}
	}
	
	/*
	 * Retourne le nom d'un état en fonction de son id
	 */
	function getStatesById($state_id){
		return $this->object_states_by_id[$state_id];
	}
	
	/*
	 * Retourne le libellé associé à un état
	 */
	function getStateCommentById($state_id){
		global $msg;
		
		$message = explode(':',$this->states_com[$this->object_states_by_id[$state_id]]);
		return $msg[$message[1]];
	}
	
	/*
	 * Retourne le libellé associé à un type
	 */
	function getTypeCommentById($type_id){
		
		global $msg;
		
		$message = explode(":",$this->object_types[$this->object_types_by_id[$type_id]]['COMMENT']);
		
		return $msg[$message[1]];
	}
	
	/*
	 * Retourne la liste des états joignables depuis un autre état
	 */
	function getStateList($state_id=-1){
		
		$state_list = array();
		
		if($state_id == -1){
			$i=0;
			foreach($this->object_states as $key=>$value){
				$i++;
				$state_list[$i]['id'] = $value['ID'];
				$state_list[$i]['comment'] = $value['COMMENT'];
			}
		} else {
			$nom = $this->getStatesById($state_id);
			for($i=0;$i<count($this->object_transitions[$nom]);$i++){
				$state_list[$i]['id'] = $this->object_states[$this->object_transitions[$nom][$i]]['ID'];
				$state_list[$i]['comment'] = $this->getStateCommentById($this->object_states[$this->object_transitions[$nom][$i]]['ID']);
			}
		}
		
		return $state_list;
	}
	
	/*
	 * Retourne la liste des types d'un objet
	 */
	function getTypeList(){
		global $msg;
		
		$type_list = array();
		$i=0;
		
		foreach($this->object_types as $key=>$value){
			$i++;
			$type_list[$i]['id'] = $value['ID'];
			$message = explode(':',$value['COMMENT']);
			$type_list[$i]['comment'] = $msg[$message[1]];
		}
		
		return $type_list;
	}
	
	
	
	
}
?>