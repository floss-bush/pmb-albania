<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mobile.class.php,v 1.3 2010-09-01 09:03:41 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path, $include_path;
require_once($class_path."/connecteurs_out.class.php");
require_once($class_path."/connecteurs_out_sets.class.php");
require_once($class_path."/external_services_converters.class.php");

require_once("$class_path/etagere.class.php");
require_once($class_path."/XMLtabs.class.php");

class mobile extends connecteur_out {
	
	function get_config_form() {
		//Rien
		return '';
	}
	
	function update_config_from_form() {
		return;
	}
	
	function instantiate_source_class($source_id) {
		return new mobile_source($this, $source_id, $this->msg);
	}
	
	function process($source_id, $pmb_user_id) {
		global $opac_url_base,$opac_biblio_name,$charset;
		$plop = $opac_biblio_name;		
		$source = new mobile_source($this, $source_id, $this->msg);
		$param = $source->config;
		$param['opacUrl'] = $opac_url_base;
		$param['connectorDriver'] = "pmb";
		$param['biblioName'] = ($charset!= "UTF-8" ? utf8_encode($opac_biblio_name) : $opac_biblio_name);
		
		echo json_encode($param);
		return;
	}
}

class mobile_source extends connecteur_out_source {
	var $onglets = array();
	
	function mobile_source($connector, $id, $msg) {
		global $include_path;
		
		parent::connecteur_out_source($connector, $id, $msg);
		//Onglets dispo dans l'appli
		$xml = new XMLtabs($include_path."/mobile/tabs.xml");
		$xml->analyser();
		$this->onglets = $xml->table;
		}
	
	function get_config_form() {
		global $charset, $dbh, $pmb_url_base;
		
		$result = parent::get_config_form();
		
		//on attributs/initialise certaines valeurs par défaut
		if(!$this->config['activeTabs'])
			$this->config['activeTabs'] = array();
		if(!$this->config['shelf_nbResultsByPage'])
			$this->config['shelf_nbResultsByPage'] = 20;
		if(!$this->config['search_nbResultsByPage'])
			$this->config['search_nbResultsByPage'] = 20;
		if(!$this->config['bulletinsList_nbResultsByPage'])
			$this->config['bulletinsList_nbResultsByPage'] = 10;	
		if(!$this->config['analysisList_nbResultsByPage'])
			$this->config['analysisList_nbResultsByPage'] = 10;					
		//Adresse d'utilisation
		$result .= "<div class=row><label class='etiquette' for='api_exported_functions'>".$this->msg['mobile_service_endpoint']."</label><br />";
		if ($this->id) {
			$result .= "<a target='_blank' href='".$pmb_url_base."ws/connector_out.php?source_id=".$this->id."'>".$pmb_url_base."ws/connector_out.php?source_id=".$this->id."</a>";
		}
		else {
			$result .= $this->msg["mobile_service_endpoint_unrecorded"];
		}
		$result .= "</div>";

		//Connecteur dédié
		$connecteurs = new connecteurs_out();
		foreach($connecteurs->connectors as $conn) {
			if( $conn->name == 'JSON-RPC') $sources = $conn->sources;
		}			
		$result .= "
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<label class='etiquette' for='proxyUrl'>".$this->msg['mobile_admin_proxyUrl']."</label><br />";
		if(sizeof($sources) == 0) {
			$result .= $this->msg['mobile_admin_error_proxy'];
		} else {
			$result.="
			<select name='proxyUrl'>";
			foreach ($sources as $source){
				$result.= "
				<option value='".$pmb_url_base."ws/connector_out.php?source_id=".$source->id."'".($this->config['proxyUrl']== $pmb_url_base."ws/connector_out.php?source_id=".$source->id ? " selected":"").">".$source->name."</option>";
			}
			$result.="
			</select>";
		}		
		
		$result .="
		</div>
		<div class='row'>&nbsp;</div>";
			
		//Onglets dispo dans l'appli
		$result.="
		<div class='row'>
			<label class='etiquette' >".$this->msg['mobile_admin_tabs_title']."</label><br />
			<div class='notice-child'>
			<table class='quadrille'>
				<tr>
					<th style='text-align:right;'>".$this->msg['mobile_admin_form_tabs_label']."</th>
					<th style='text-align:center;' >".$this->msg['mobile_admin_form_tabs_valid']."</th>
					<th style='text-align:center;'>".$this->msg['mobile_admin_firstTab']."</th>
					<th style='text-align:left;'>".$this->msg['mobile_admin_form_tabs_desc']."</th>
					
				</tr>";
		$i=0;
		
		foreach ($this->onglets as $onglet =>$value){
			$result.="
				<tr >
					<td style='text-align:right;'>".$value['label']."</td>
					<td style='text-align:center;'>
						".$this->msg['mobile_admin_form_tabs_yes']."&nbsp;
						<input type='radio' id='tab_".$onglet."_ok' name='$onglet' value='1'".
							//on coche l'option déjà enregistré
							($this->config['activeTabs'][$onglet] == 1 ? " checked " : " ").
							//on active les options en fonction 
							"onchange='checkParam(\"$onglet\",false);' />&nbsp;
						".$this->msg['mobile_admin_form_tabs_no']."&nbsp;
						<input type='radio' id='tab_".$onglet."_ko' name='$onglet' value='0' ".
							//on coche l'option déjà enregistré
							($this->config['activeTabs'][$onglet] == 0 ? " checked " : " ").
							//on désactive les options en fonction
							"onchange='checkParam(\"$onglet\",false);' />
					</td>
					<td style='text-align:center;'>
						<input type='radio' id='firstTab_$onglet' name='firstTab' value='$onglet' ".($this->config['firstTab'] === $onglet ? " checked" : ($i==0 ? " checked" : ""))."'/>
					</td>
					<td style='text-align:left;'>".$value['desc']."</td>
				</tr>";
			$i++;
		}
		$result.="
			</table>
			</div>
		</div>
		<div class='row'>&nbsp;</div>";

		//param pour l'onglet 'Accueil'
		$form_infoPage="
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<label class='etiquette' for='firstInfoPage'>".$this->msg['mobile_admin_firstInfoPage']."</label><br />";
		$requete = "select id_infopage as id, title_infopage as title from infopages where valid_infopage = 1 order by title DESC";
		$res = mysql_query($requete);
		if(mysql_num_rows($res)){
			$form_infoPage.= "
			<select id ='firstInfoPage' name='firstInfoPage'>";
			while ($infopage = mysql_fetch_object($res)){
				$form_infoPage.="
				<option value='".$infopage->id."'".($this->config["firstInfoPage"]== $infopage->id ? " selected":"").">".$infopage->title."</option>";
			}
			$form_infoPage.="
			</select>";
		}else{
			$form_infoPage.=$this->msg['mobile_admin_error_infopage'];
		}
		$form_infoPage.= "
		</div>
		<div class='row'>&nbsp;</div>
		";
		$result.= gen_plus("form_infoPage",$this->msg['mobile_admin_form_infoPagesTitle'],$form_infoPage).	
		"<div class='row'>&nbsp;</div>";
		
		
		//param pour l'onglet 'Etagère coup de coeur'
		$form_shelf.="
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<label class='etiquette' for='heartShelf'>".$this->msg['mobile_admin_heatShelf']."</label><br />";
		$etageres = etagere::get_etagere_list();
		$etagere_valid = false;
		$select = "
			<select id='heartShelf' name='heartShelf'>";
		foreach($etageres as $etagere){
			if ($etagere['validite'] == 1 && $etagere['visible_accueil']==1){	
				$select .="
				<option value='".$etagere['idetagere']."'".($this->config['heartShelf']== $etagere['idetagere'] ? " selected":"").">".$etagere['name']."</option>";
				$etagere_valid = true;
			}
		}
		$select.="
			</select>";
		if($etagere_valid == true){
			$form_shelf .= $select;
		}else{
			$form_shelf.=$this->msg['mobile_admin_error_etageres'];		
		}
		$form_shelf .= "
		</div>
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<label class='etiquette' for='shelf_nbResultsByPage'>".$this->msg['mobile_admin_nbResultsByPage']."</label><br />
			<input type='text' id='shelf_nbResultsByPage' name='shelf_nbResultsByPage' value='".$this->config['shelf_nbResultsByPage']."' />
		</div>
		<div class='row'>&nbsp;</div>";

		
		$result .= gen_plus("form_shelf",$this->msg['mobile_admin_form_shelfTitle'],$form_shelf).	
		"<div class='row'>&nbsp;</div>";
		
		
		
		//param pour l'onglet 'Recherche Simple'
		 $form_search = "
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<label class='etiquette' for='search_nbResultsByPage'>".$this->msg['mobile_admin_nbResultsByPage']."</label><br />
			<input type='text' id='search_nbResultsByPage' name='search_nbResultsByPage' value='".$this->config['search_nbResultsByPage']."' />
		</div>
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<label class='etiquette' for='allowTypDocFilter'>".$this->msg["mobile_admin_form_search_allowSearchByTypeDoc"]."</label><br />
			".$this->msg['mobile_admin_form_search_allowSearchByTypeDoc_yes']."&nbsp;<input type='radio' id='allowTypDocFilter_yes' name='allowTypDocFilter' value='1' ".($this->config["allowTypDocFilter"] == 1 ? " checked " : " ")." />&nbsp;
			".$this->msg['mobile_admin_form_search_allowSearchByTypeDoc_no']."&nbsp;<input type='radio' id='allowTypDocFilter_no' name='allowTypDocFilter' value='0' ".($this->config["allowTypDocFilter"] == 0 ? " checked " : " ")."/>
		</div>
		<div class='row'>&nbsp;</div>";
		
		$result .= gen_plus("form_search",$this->msg['mobile_admin_form_searchTitle'],$form_search).	
		"<div class='row'>&nbsp;</div>";	
		
	
		//param pour Mon Compte
		$form_myAccount = "
		<div class='row'>&nbsp;</div>
		<div class='row'>&nbsp;</div>";
		$result .= gen_plus("form_myAccount",$this->msg['mobile_admin_form_myAccountTitle'],$form_myAccount).	
		"<div class='row'>&nbsp;</div>";					
		
		
		
		//param pour Pret Autonome
		$form_selfCheck = "
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<label class='etiquette' for='allowCheckIn'>".$this->msg["mobile_admin_form_selfCheck_allowCheckIn"]."</label><br />
			".$this->msg['mobile_admin_form_selfCheck_allowCheckIn_yes']."&nbsp;<input type='radio' id='allowCheckIn_yes' name='allowCheckIn' value='1' ".($this->config["allowCheckIn"] == 1 ? " checked " : " ")." />&nbsp;
			".$this->msg['mobile_admin_form_selfCheck_allowCheckIn_no']."&nbsp;<input type='radio' id='allowCheckIn_no' name='allowCheckIn' value='0' ".($this->config["allowCheckIn"] == 0 ? " checked " : " ")."/>
		</div>
		<div class='row'>&nbsp;</div>";
		
		$result .= gen_plus("form_selfCheck",$this->msg['mobile_admin_form_selfCheckTitle'],$form_selfCheck).	
		"<div class='row'>&nbsp;</div>";
		
		
		//param d'affichage général
		$param_gen = "
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<label class='etiquette' for='bulletinsList_nbResultsByPage'>".$this->msg['mobile_admin_form_bulletinsList_nbResultsByPage']."</label><br />
			<input type='text' id='bulletinsList_nbResultsByPage' name='bulletinsList_nbResultsByPage' value='".$this->config['bulletinsList_nbResultsByPage']."' />
		</div>
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<label class='etiquette' for='analysisList_nbResultsByPage'>".$this->msg['mobile_admin_form_analysisList_nbResultsByPage']."</label><br />
			<input type='text' id='analysisList_nbResultsByPage' name='analysisList_nbResultsByPage' value='".$this->config['analysisList_nbResultsByPage']."' />
		</div>
		<div class='row'>&nbsp;</div>";
		
		$result .= gen_plus("paramGen",$this->msg['mobile_admin_form_paramsGen'],$param_gen).	
		"<div class='row'>&nbsp;</div>";		
		
				
		$result.="
		<script type='text/javascript' src='javascript/tablist.js'></script>
		<script type='text/javascript'>
			// on génère une fonction d'initialisation
			function init(){";
			foreach($this->onglets as $onglet =>$value){
				$result.="
				checkParam('$onglet');
				expandBase('paramGen',true);
			";
			}		
			$result.="
			}

			onload = init;

			// on s'assure que l'application dispose bien d'une première page à la soumission du formulaire...
			document.forms['form_connectorout'].onsubmit = function(){
				for(var i=0 ; i<document.forms['form_connectorout'].firstTab.length ; i++){
					if(document.forms['form_connectorout'].firstTab[i].checked == true)
					return true;
				}";
			foreach($this->onglets as $onglet =>$value){
				$result.="
				if(document.getElementById('tab_'+'$onglet'+'_ok').checked == true){
					document.getElementById('firstTab_$onglet').checked = true;
					return true;
				}";
				}
			$result.="	
				alert('".$this->msg['mobile_admin_form_needOneTab']."');
				return false;			
			}

			function checkParam(onglet){
				if(document.getElementById('tab_'+onglet+'_ok').checked == true){
					switchParamValue('firstTab_'+onglet,'disabled','active');
					if(document.getElementById('form_'+onglet+'Child').style.display == 'none')
						expandBase('form_'+onglet,true);
					switchParamsValues(onglet,'active');
				}else{
					switchParamValue('firstTab_'+onglet,'disabled','inactive');
					document.getElementById('firstTab_'+onglet).checked = false;
					if(document.getElementById('form_'+onglet+'Child').style.display == 'block')
						expandBase('form_'+onglet,true);
					switchParamsValues(onglet,'inactive');
				}
			}

			function switchParamsValues(onglet,state){
				switch (onglet){
					case 'infoPage' :
						switchParamValue('firstInfoPage','disabled',state);
						break;
					case 'selfCheck' :
						switchParamValue('allowCheckIn_yes','disabled',state);
						if(document.getElementById('allowCheckIn_yes').checked ==true)
							switchParamValue('allowCheckIn_yes','checked',state);
						switchParamValue('allowCheckIn_no','disabled',state);
						if(document.getElementById('allowCheckIn_no').checked ==true)
							switchParamValue('allowCheckIn_no','checked',state);
						break;
					case 'myAccount' :
						switchParamValue('tab_selfCheck_ok','disabled',state);
						if(state == 'inactive')
							switchParamValue('tab_selfCheck_ko','checked','active');
						checkParam('selfCheck');
						break;
					case 'search' :
						switchParamValue('search_nbResultsByPage','disabled',state);
						switchParamValue('allowTypDocFilter_yes','disabled',state);
						switchParamValue('allowTypDocFilter_no','disabled',state);
						if(document.getElementById('allowTypDocFilter_no').checked ==true)
							switchParamValue('allowTypDocFilter_no','checked',state);
						if(document.getElementById('allowTypDocFilter_yes').checked ==true)
							switchParamValue('allowTypDocFilter_yes','checked',state);
						break;
					case 'shelf':
						switchParamValue('heartShelf','disabled',state);
						switchParamValue('shelf_nbResultsByPage','disabled',state);
						break;
				}				
			}

			function switchParamValue(id,param,state){
				var newState = false;
				if(state == 'active'){
					switch(param){
						case 'disabled' :
							newState = false;
							break;
						case 'checked' :
							newState = true;			
							break;
					}
				}else if(state == 'inactive'){
					switch(param){
						case 'disabled' :
							newState = true;	
							break;
						case 'checked' :
							newState = false;
							break;
					}					
				}
				document.getElementById(id)[param] = newState;
			}
		</script>";

		return $result;
	}
	
	function update_config_from_form() {
		global $dbh;
		global $proxyUrl,$appTitle,$firstTab,$firstInfoPage,$heartShelf,$search_nbResultsByPage,$shelf_nbResultsByPage;
		global $allowTypDocFilter,$allowCheckIn;
		global $bulletinsList_nbResultsByPage,$analysisList_nbResultsByPage;
		global $onglets;
		
		parent::update_config_from_form();
		
		//les trucs faciles
		$this->config['proxyUrl'] = $proxyUrl;
		$this->config['firstInfoPage'] = (isset($firstInfoPage) ? $firstInfoPage :'');
		$this->config['heartShelf'] = (isset($heartShelf) ? $heartShelf :'');
		$this->config['search_nbResultsByPage'] = $search_nbResultsByPage;
		$this->config['shelf_nbResultsByPage'] = $shelf_nbResultsByPage;
		if($allowTypDocFilter == 1){
			$this->config['allowTypDocFilter'] = true;
		}else $this->config['allowTypDocFilter'] = false;
		if($allowCheckIn == 1){
			$this->config['allowCheckIn'] = true;
		}else $this->config['allowCheckIn'] = false;
		//le tableau des onglets activés ou non
		$this->config['activeTabs']=array();
		foreach($this->onglets as $ongletName => $value){
			global $$ongletName;
			if(isset($$ongletName)){
				$this->config['activeTabs'][$ongletName]=$$ongletName;
			}else{
				$this->config['activeTabs'][$ongletName]=0;
			}
		}	
		if(isset($firstTab))
			$this->config['firstTab'] = $firstTab;
		else{
			foreach($this->onglets as $ongletName => $value){
				if($this->config['activeTabs'][$ongletName] != 1)
				$this->config['firstTab'] = $ongletName;
				break;
			}
		}
		$this->config['bulletinsList_nbResultsByPage'] = $bulletinsList_nbResultsByPage;
		$this->config['analysisList_nbResultsByPage'] = $analysisList_nbResultsByPage;
		return;
	}
}

?>