<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sru.class.php,v 1.8 2010-06-12 14:23:52 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");
require_once($class_path."/search.class.php");
require_once($base_path."/admin/connecteurs/in/sru/sru_protocol.class.php");

//
// Scandir for PHP4
//
if(!function_exists('scandir'))
{
    function scandir($dir, $sortorder = 0)
    {
        if(is_dir($dir))
        {
            $dirlist = opendir($dir);
           
            while( ($file = readdir($dirlist)) !== false)
            {
                if(!is_dir($file))
                {
                    $files[] = $file;
                }
            }
           
            ($sortorder == 0) ? asort($files) : arsort($files);
           
            return $files;
        }
        else
        {
        return FALSE;
        break;
        }
    }
}

class sru extends connector {
	//Variables internes pour la progression de la r�cup�ration des notices
	var $callback_progress;		//Nom de la fonction de callback progression pass�e par l'appellant
	var $current_set;			//Set en cours de synchronisation
	var $total_sets;			//Nombre total de sets s�lectionn�s
	var $metadata_prefix;		//Pr�fixe du format de donn�es courant
	var $source_id;				//Num�ro de la source en cours de synchro
	var $n_recu;				//Nombre de notices re�ues
	var $xslt_transform;		//Feuille xslt transmise
	var $sets_names;			//Nom des sets pour faire plus joli !!
	var $del_old;				//Supression ou non des notices dej� existantes
	var $schema_config;
	
	//R�sultat de la synchro
	var $error;					//Y-a-t-il eu une erreur	
	var $error_message;			//Si oui, message correspondant
	
    function sru($connector_path="") {
    	parent::connector($connector_path);
    }
    
    function get_id() {
    	return "sru";
    }
    
    //Est-ce un entrepot ?
	function is_repository() {
		return 2;
	}
    
    function unserialize_source_params($source_id) {
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			$params["PARAMETERS"]=$vars;
		}
		return $params;
    }
    
    
    /*R�cup�re la grille d'automappage pour le remplissage automatique des champs de recherche
     * Exemple d'un �l�ment du rendu: 
     *     [dc____title] => Array
     *   (
     *       [0] => 200$a
     *       [1] => 7XX
     *   )
     * Donc dc.title correspond aux champs 'titre' et 'auteur'.
     *  */
    function get_automap_config($filename) {
    	$result = array("cql_____" => array("XXX"));
		$file = file_get_contents($filename);
		$dom = new xml_dom_sru($file);
		$node = $dom->get_nodes('auto_map_indexes/map');
		foreach ($node as $map) {
			$map_elements = explode("\n", $dom->get_datas($map));
			$mmap = array();
			foreach($map_elements as $map_element) {
				$trimmed = trim($map_element);
				if ($trimmed){
					$mmap[] = $trimmed;
				}
			}
			$attribs = $dom->get_attributes($map);
			$result[$attribs["set"]."____".$attribs["name"]] = $mmap;
		}
		
			$long_formats = explode("\n", $dom->get_datas($schema));
			foreach ($long_formats as $long_format) {
				$trimmed = trim($long_format); 
				if ($trimmed)
					$result[$id]['long_formats'][] = $trimmed;
			}
		
		return $result;
    }
    
    /*G�n�re un multi select avec la liste des champs PMB dedans. 
     * Pour le mapping des champs serveur / PMB
     */
    
    function make_field_combo_box($fiels, $field_name, $selected=array()) {
    	global $msg, $charset;
    	$r="<select MULTIPLE size=\"5\" name='".$field_name."[]' >\n";
    	$r.="<option value='' style='color:#000000'>".htmlentities($msg["multi_select_champ"],ENT_QUOTES,$charset)."</font></option>\n";
    	
    	//Champs fixes
    	$open_optgroup=0;
		$open_optgroup_deja_affiche=0;
		$open_optgroup_en_attente_affiche=0;

    	foreach($fiels as $id => $ff) {
   			$r.="<option value='".$id."' style='color:#000000' ".(in_array($id, $selected) ? 'SELECTED' : '').">".htmlentities($ff,ENT_QUOTES,$charset)."</option>\n";
    	}
    	$r.="</select>";    	
    	return $r;
    }
    
    /*Renvoi le formulaire de propri�t� de la source
     */
    function source_get_property_form($source_id) {
    	global $charset, $base_path;
//    	
//R�cup�ration des param�tres de la source
//
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		if (!isset($allowed_indexes))
			$allowed_indexes = array();
		if (!isset($max_record_per_search))
			$max_record_per_search = 100;	
			
		if (!isset($style_sheets))
			$style_sheets = array();		
		
//		highlight_string(print_r($style_sheets, true));
	
//	
//URL de la source
//
		$form="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["sru_url"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='url' id='url' class='saisie-60em' value='".htmlentities($url,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		<div class='row'>
		";
		
		if (!$url) 
			$form.="<h3 style='text-align:center'>".$this->msg["rec_addr"]."</h3>";
		else {
//	
//Demande au serveur des propri�t�s de la source
//
			$parameters = array('version' => '1.1');
			$this->get_schemas_config();
			$request = new sru_request($url, "explain", $parameters, $this->schema_config);
			if (!$request->error) {
				$source_properties = $request->analyse_response('ISO-8859-1');
			}
			else {
				print $request->error_message;				
			}
			
			if ($source_properties) {

//	
//Titre du serveur
//
				if ($source_properties["databaseinfo"]["title"]["value"])
					$form.="
						<div class='row'>
							<div class='colonne3'>
								<label>".$this->msg["sru_title"]."</label>
							</div>
							<div class='colonne_suite'>
								".htmlentities($source_properties["databaseinfo"]["title"]["value"], ENT_QUOTES, $charset)."
							</div>
						</div>
						";
//	
//Description du serveur
//
				
				if ($source_properties["databaseinfo"]["description"]["value"])
					$form.="
						<div class='row'>
							<div class='colonne3'>
								<label>".$this->msg["sru_description"]."</label>
							</div>
							<div class='colonne_suite'>
								".htmlentities($source_properties["databaseinfo"]["description"]["value"], ENT_QUOTES, $charset)."
							</div>
						</div>
						";
//	
//Auteur du serveur
//

				if ($source_properties["databaseinfo"]["author"])
					$form.="
						<div class='row'>
							<div class='colonne3'>
								<label>".$this->msg["sru_author"]."</label>
							</div>
							<div class='colonne_suite'>
								".htmlentities($source_properties["databaseinfo"]["author"], ENT_QUOTES, $charset)."
							</div>
						</div>
						";	
			
//	
//Contact du serveur
//
				if ($source_properties["databaseinfo"]["contact"])
					$form.="
						<div class='row'>
							<div class='colonne3'>
								<label>".$this->msg["sru_contact"]."</label>
							</div>
							<div class='colonne_suite'>
								".htmlentities($source_properties["databaseinfo"]["contact"], ENT_QUOTES, $charset)."
							</div>
						</div>
						";

//	
//Nombre maximum de r�sultats
//

				$nb_max_input = '<input class="saisie-5em" type="text" name="max_record_count" value="'.$max_record_per_search.'">';
				$form.="
					<br />
					<div class='row'>
						<div class='colonne3'>
							<label>".$this->msg["sru_maxrecordpersearch"]."</label>
						</div>
						<div class='colonne_suite'>
							".$nb_max_input."
						</div>
					</div>
					";
						
//	
//Mapping des champs Serveur / PMB
//
						
				$search_fields_keyword = array();
				$search_field_list = "";
				$count = 1;

				if ($source_properties["indexinfo"]) {
					$auto_map_config = $this->get_automap_config($base_path."/admin/connecteurs/in/sru/automap_fields.xml");
//						highlight_string(print_r($source_properties["indexinfo"], true));
					if (!isset($field_maps))
						$field_maps = array();
						
					$auto_map = array();
					$search_field_list .= "<table>";

					$sc=new search(false,"search_simple_fields_unimarc");
					$sc2=new search(false,"search_fields_unimarc");
					$total_fields = array();
					foreach($sc2->fixedfields as $fixed_field) {
						if (!isset($fixed_field["UNIMARCFIELD"]))
							continue;
						$total_fields[$fixed_field["UNIMARCFIELD"]] = $fixed_field["TITLE"];
					}
					foreach($sc->fixedfields as $fixed_field) {
						if (!isset($fixed_field["UNIMARCFIELD"]))
							continue;
						$total_fields[$fixed_field["UNIMARCFIELD"]] = $fixed_field["TITLE"];
					}  
					$unimarc_to_indexes = array_flip(array_keys(array_merge(array("000_" => ""), $total_fields)));
//					print_r($unimarc_to_indexes);
					$all_field = array(0 => array("title" => $this->msg["sru_global_search"], "lang" => "en"), "map" => "_", "set" => "cql");
					$source_properties["indexinfo"] = array_merge(array($all_field), $source_properties["indexinfo"]);

					foreach ($source_properties["indexinfo"] as $index_info) {
						$field_dname = $index_info["set"].'____'.$index_info["map"]; 

						$input_options = "";
						if (in_array($field_dname, $allowed_indexes))
							$input_options .= " CHECKED";
						$search_field_list .= "<tr><td>";
						$search_field_list .= '<input type="checkbox" id="indexenabled_'.$field_dname.'" value="'.$field_dname.'" name="indexenabled[]" '.$input_options.'>';
						$search_field_list .= '<label for="indexenabled_'.$field_dname.'">'.htmlentities($index_info[0]["title"], ENT_QUOTES, $charset)." (".$index_info["set"].")</label>";
						$search_field_list .= "</td><td> ".$this->msg['sru_is_mapped_to'].' <br />';
						$field_value = isset($field_maps[$field_dname]) ? $field_maps[$field_dname] : array();
						$search_field_list .=  $this->make_field_combo_box($total_fields, "field_map_".$field_dname, $field_value)."<br />\n";
						$search_field_list .= "</td></tr>";
						$search_fields_keyword[] = $index_info["map"];
						
						if ($auto_map_config[$field_dname]) {
							$auto_map[$field_dname] = $auto_map_config[$field_dname];
						}
						
						$count++;
					}
					$search_field_list .= "</table>";
					$search_field_list .= "
					<script type='text/javascript'>
						function get_index_for_automap(input, value) {
							for (i=0; i<input.length; i++) {
								if (input.options[i].value == value)
									return i;									
							}
							return 0;
						}
						function do_auto_map() {
							";
					foreach ($auto_map as $qui_map => $quoi_map) {
						$search_field_list .= "document.source_form['field_map_".$qui_map."[]'].selectedIndex = -1\n";
						$search_field_list .= "document.getElementById('indexenabled_".$qui_map."').checked = true\n";
						foreach($quoi_map as $quoi_map_element) {
							$search_field_list .= "document.source_form['field_map_".$qui_map."[]'].options[".$unimarc_to_indexes[$quoi_map_element]."].selected = true\n";
						}
					}

					$search_field_list .= "
						}";

					$search_field_list .= "</script>";
					$search_field_list .= "<input value=\"".$this->msg["sru_automap"]."\" type='button' onclick=\"do_auto_map();\">";
				}
				$form.="
					<br />
					<div class='row'>
						<div class='colonne3'>
							<label>".$this->msg["sru_searchfields"]."</label>
						</div>
						<div class='colonne_suite'>
							".$search_field_list."
						</div>
					</div>
					";
					
//	
//Choix d'une recherche global en cas d'�chec de construction CQL.
//
					
				$sru_cqlfail_means_global_input = '<input type="checkbox" name="sru_cqlfail_means_global" '.($sru_cqlfail_means_global ? 'CHECKED' : '').'>';
				$form.="
					<br /><br />
					<div class='row'>
						<div class='colonne3'>
							<label>".$this->msg["sru_cqlfail_means_global"]."</label>
						</div>
						<div class='colonne_suite'>
							".$sru_cqlfail_means_global_input."
						</div>
					</div>
					<br /><br />
					";	

//	
//Choix du schema et mapping des feuilles XSLT
//

//				$schema_infos = array ("default" => $this->msg["default_schema"]);
				$schema_infos = array ();
				if ($source_properties["schemainfo"])
					$schema_infos = array_merge($schema_infos, $source_properties["schemainfo"]);

				$schema_input = "<select name=\"chosen_schema\" onchange=\"enable_or_disable_stylesheet_automap_button()\">";
				foreach ($schema_infos as $name => $description) {
					$schema_input .= "<option value=\"".$name."\" ".($name == $chosen_schema ? "selected" : "").">".htmlentities($description, ENT_QUOTES, $charset)."</option>";
				}
				$schema_input .= "</select>";

				$form.="
					<div class='row'>
						<div class='colonne3'>
							<label>".$this->msg["sru_chosen_schema"]."</label>
						</div>
						<div class='colonne_suite'>
							".$schema_input."
						</div>
					</div>
					";	
					
				/*STYLE SHEETS*/
				
				$astylesheet_input = "";
				$xslt_dir_content = scandir("admin/connecteurs/in/sru/xslt");
//				print_r($xslt_dir_content);
				$count = 1;
				$stylesheet_lines = array();
				//Parce qu'en plus il peut y avoir un gland qui a upload� deux fichiers diff�rents avec le m�me nom
				for($i=0, $localcount=count($style_sheets); $i<$localcount; $i++) {
					
					if ($style_sheets[$i]["type"] == "custom_file") {
						$astylesheet_input .= "c_select_values[$count] = '__KEEP_INDEX".$i."';\n";
						$astylesheet_input .= "c_select_captions[$count] = 'Fichier upload� ".$style_sheets[$i]["name"]."';\n";
						$stylesheet_lines[$i] = $count;
						$count++;
					}
					else {
						$stylesheet_lines[$i] = -1;
					}
					
				}
				
//				highlight_string(print_r($automap_style_sheet_js, true));
				$names_to_indexes = array();
				foreach($xslt_dir_content as $style_sheet) {
					if ($style_sheet == '.' || $style_sheet == '..') 
						continue;
					$astylesheet_input .= "c_select_values[$count] = '".$style_sheet."';\n";
					$astylesheet_input .= "c_select_captions[$count] = '".$style_sheet."';\n";
					$names_to_indexes[$style_sheet] = $count;
					$count++; 
				}
				
				for($i=0, $localcount=count($style_sheets); $i<$localcount; $i++) {
					if ($stylesheet_lines[$i] == -1) {
						$stylesheet_lines[$i] = $names_to_indexes[$style_sheets[$i]["name"]]; 
					}
				}
				
				$built_in_style_sheets_set = $this->get_schemas_config();
				$automap_style_sheets_count = 0;
				$automap_style_sheet_js = "var automap_config = [];\n";
//				highlight_string(print_r($built_in_style_sheets_set, true));
				foreach ($built_in_style_sheets_set as $schema_name => $schema_content) {
					if ($schema_content["stylesheets"]) {
						$automap_style_sheet_js .= "automap_config['$schema_name'] = [];\n";
						$local_count=0;
						foreach($schema_content["stylesheets"] as $style_sheet_filename) {
							$automap_style_sheet_js .= "automap_config['$schema_name'][$local_count] = '".$names_to_indexes[$style_sheet_filename]."'\n";
							$local_count++;
						}						
					}
				}
				$automap_style_sheet_js .= "\n\n";
				
				$full_stylesheet_input = "
		<input type=\"hidden\" name=\"xslt_count\" value=\"0\">
		<div id=\"xslt_line_host\">
		</div>
		<input type=\"button\" onclick=\"document.source_form.xslt_count.value = document.source_form.xslt_count.value * 1 + 1; addLine(document.source_form.xslt_count.value, 0)\" value=\"Ajouter\"/>
		&nbsp;<input type=\"button\" name=\"xslt_automap_button\" id=\"xslt_automap_button\" onclick=\"do_automap_stylesheets()\" value=\"Remplir automatiquement\" DISABLED>
		<script type=\"text/javascript\">
			var c_select_values = [];
			var c_select_captions = [];
			c_select_values[0] = '__CUSTOM__';
			c_select_captions[0] = 'Fichier Perso';
			$astylesheet_input

			$automap_style_sheet_js

			function enable_or_disable_stylesheet_automap_button() {
				document.source_form.xslt_automap_button.disabled = automap_config[document.source_form.chosen_schema.value] == undefined;
			}
			enable_or_disable_stylesheet_automap_button();

			function do_automap_stylesheets() {
				schema_id = document.source_form.chosen_schema.value;
				//Let's clear the area
				while(document.source_form.xslt_count.value > 0)
					deleteLine(1, document.source_form.xslt_count.value);
				
				var i=0; var acount=0;
				for(i=0, acount=automap_config[schema_id].length; i<acount; i++) {
//					alert(automap_config[schema_id][i]);
					document.source_form.xslt_count.value = i+1;
					addLine(i+1);
					document.getElementById('xslt_select_'+(i+1)).selectedIndex = automap_config[schema_id][i];
				}
			}

			function addLine(number) {
 				var hostdiv = document.getElementById('xslt_line_host');
  	  	  	  	var xslt_line = document.createElement('div');
    	  	  	var divIdName = 'xslt_line_'+number;
    	  	  	xslt_line.setAttribute('id',divIdName);
    	  	  	
				var xslt_select = document.createElement('select');
				xslt_select.name = 'xslt_select_'+number;
				xslt_select.id = 'xslt_select_'+number;
				var i=0;
				for(i=0, count=c_select_values.length; i<count; i++)
					xslt_select.options[i] = new Option(c_select_captions[i], c_select_values[i]);

				var xslt_file = document.createElement('input');
				xslt_file.type = 'file';
				xslt_file.name = 'xslt_file_'+number;
				xslt_file.id = 'xslt_file_'+number;
				xslt_file.setAttribute('onchange', 'document.source_form.xslt_select_'+number+'.selectedIndex=0;');

				var xslt_removebutton = document.createElement('input');
				xslt_removebutton.type = 'button';
				xslt_removebutton.name = 'xslt_remove_'+number;
				xslt_removebutton.id = 'xslt_remove_'+number;
				xslt_removebutton.value = 'Supprimer';
				xslt_removebutton.setAttribute('onclick', 'deleteLine('+number+', document.source_form.xslt_count.value);');

				var xslt_goupbutton = document.createElement('input');
				xslt_goupbutton.type = 'button';
				xslt_goupbutton.name = 'xslt_goup_'+number;
				xslt_goupbutton.id = 'xslt_goup_'+number;
				xslt_goupbutton.value = 'Monter';
				if (document.source_form.xslt_count.value == 1)
					xslt_goupbutton.disabled = true;
				xslt_goupbutton.setAttribute('onclick', 'MoveElementDown('+(number-1)+', document.source_form.xslt_count.value)');

				if (document.source_form.xslt_count.value > 1) {
					document.getElementById('xslt_godown_'+(number-1)).disabled = false;
				}
				
				var xslt_godownbutton = document.createElement('input');
				xslt_godownbutton.type = 'button';
				xslt_godownbutton.name = 'xslt_godown_'+number;
				xslt_godownbutton.id = 'xslt_godown_'+number;
				xslt_godownbutton.value = 'Descendre';
				xslt_godownbutton.disabled = true;
				xslt_godownbutton.setAttribute('onclick', 'MoveElementDown('+(number)+', document.source_form.xslt_count.value)');
								
				xslt_line.appendChild(xslt_select);
				xslt_line.innerHTML += '&nbsp;';
				xslt_line.appendChild(xslt_file);
				xslt_line.innerHTML += '&nbsp;';
				xslt_line.appendChild(xslt_goupbutton);
				xslt_line.innerHTML += '&nbsp;';
				xslt_line.appendChild(xslt_godownbutton);
				xslt_line.innerHTML += '&nbsp;';
				xslt_line.appendChild(xslt_removebutton);
							
    	  	  	hostdiv.appendChild(xslt_line);
	}
			function ChangeElementId(number, newnumber) {
					var line_to_move = document.getElementById('xslt_line_'+number);				
					line_to_move.id = 'xslt_line_'+(newnumber);
				
					var select_to_move = document.getElementById('xslt_select_'+number);				
					select_to_move.name = 'xslt_select_'+(newnumber);
					select_to_move.id = 'xslt_select_'+(newnumber);
				
					var file_to_move = document.getElementById('xslt_file_'+number);				
					file_to_move.name = 'xslt_file_'+(newnumber);
					file_to_move.id = 'xslt_file_'+(newnumber);
					file_to_move.setAttribute('onchange', 'document.source_form.xslt_select_'+newnumber+'.selectedIndex=0;');			
				
					var button_to_move = document.getElementById('xslt_remove_'+number);				
					button_to_move.name = 'xslt_remove_'+(newnumber);
					button_to_move.id = 'xslt_remove_'+(newnumber);
					button_to_move.setAttribute('onclick', 'deleteLine('+newnumber+', document.source_form.xslt_count.value);');			
				
					var button_to_move = document.getElementById('xslt_goup_'+number);				
					button_to_move.name = 'xslt_goup_'+(newnumber);
					button_to_move.id = 'xslt_goup_'+(newnumber);
					button_to_move.setAttribute('onclick', 'MoveElementDown('+(newnumber-1)+', document.source_form.xslt_count.value)');
				
					var button_to_move = document.getElementById('xslt_godown_'+number);				
					button_to_move.name = 'xslt_godown_'+(newnumber);
					button_to_move.id = 'xslt_godown_'+(newnumber);
					button_to_move.setAttribute('onclick', 'MoveElementDown('+(newnumber)+', document.source_form.xslt_count.value)');
			}
				
			function MoveElementDown(number, count) {
					var line_to_move = document.getElementById('xslt_line_'+number);				
					var line_after = document.getElementById('xslt_line_'+(number+1));
					var hostdiv = document.getElementById('xslt_line_host');
					hostdiv.insertBefore(line_after, line_to_move);
					
					line_to_move.id = 'xslt_line_'+(number+1);
					line_after.id = 'xslt_line_'+number;
				
					var select_to_move = document.getElementById('xslt_select_'+number);				
					var select_after = document.getElementById('xslt_select_'+(number+1));
					select_to_move.name = 'xslt_select_'+(number+1);
					select_after.name = 'xslt_select_'+number;
					select_to_move.id = 'xslt_select_'+(number+1);
					select_after.id = 'xslt_select_'+number;
				
					var file_to_move = document.getElementById('xslt_file_'+number);				
					var file_after = document.getElementById('xslt_file_'+(number+1));
					file_to_move.name = 'xslt_file_'+(number+1);
					file_after.name = 'xslt_file_'+number;
					file_to_move.name = 'xslt_file_'+(number+1);
					file_after.name = 'xslt_file_'+number;
					file_to_move.id = 'xslt_file_'+(number+1);
					file_after.id = 'xslt_file_'+number;
					file_to_move.setAttribute('onchange', 'document.source_form.xslt_select_'+(number+1)+'.selectedIndex=0;');
					file_after.setAttribute('onchange', 'document.source_form.xslt_select_'+(number)+'.selectedIndex=0;');
				
					var button_to_move = document.getElementById('xslt_remove_'+number);				
					var button_after = document.getElementById('xslt_remove_'+(number+1));
					button_to_move.name = 'xslt_remove_'+(number+1);
					button_after.name = 'xslt_remove_'+number;
					button_to_move.name = 'xslt_remove_'+(number+1);
					button_after.name = 'xslt_remove_'+number;
					button_to_move.id = 'xslt_remove_'+(number+1);
					button_after.id = 'xslt_remove_'+number;
					button_to_move.setAttribute('onclick', 'deleteLine('+(number+1)+', document.source_form.xslt_count.value);'); 
					button_after.setAttribute('onclick', 'deleteLine('+(number)+', document.source_form.xslt_count.value);');
				
					var button_to_move = document.getElementById('xslt_goup_'+number);				
					var button_after = document.getElementById('xslt_goup_'+(number+1));
					button_to_move.name = 'xslt_goup_'+(number+1);
					button_after.name = 'xslt_goup_'+number;
					button_to_move.name = 'xslt_goup_'+(number+1);
					button_after.name = 'xslt_goup_'+number;
					button_to_move.id = 'xslt_goup_'+(number+1);
					button_after.id = 'xslt_goup_'+number;
					button_to_move.disabled = false;
					if (number == 1)
						button_after.disabled = true;				
					button_to_move.setAttribute('onclick', 'MoveElementDown('+(number)+', document.source_form.xslt_count.value)'); 
					button_after.setAttribute('onclick', 'MoveElementDown('+(number-1)+', document.source_form.xslt_count.value)');
				
					var button_to_move = document.getElementById('xslt_godown_'+number);				
					var button_after = document.getElementById('xslt_godown_'+(number+1));
					button_to_move.name = 'xslt_godown_'+(number+1);
					button_after.name = 'xslt_godown_'+number;
					button_to_move.name = 'xslt_godown_'+(number+1);
					button_after.name = 'xslt_godown_'+number;
					button_to_move.id = 'xslt_godown_'+(number+1);
					button_after.id = 'xslt_godown_'+number;
					button_after.disabled = false;
					if (number+1 == count)
						button_to_move.disabled = true;				
					button_to_move.setAttribute('onclick', 'MoveElementDown('+(number+1)+', document.source_form.xslt_count.value)'); 
					button_after.setAttribute('onclick', 'MoveElementDown('+(number)+', document.source_form.xslt_count.value)');
			}
				
			function deleteLine(number, count) {
				var line_to_remove = document.getElementById('xslt_line_'+number);
 				var hostdiv = document.getElementById('xslt_line_host');
				hostdiv.removeChild(line_to_remove);

				for (i=number+1, acount=count; i<=acount; i++) {
					ChangeElementId(i, i-1);
				} 
				document.source_form.xslt_count.value = document.source_form.xslt_count.value * 1 -1;
			}\n\n";
				for($i=0, $localcount=count($stylesheet_lines); $i<$localcount; $i++) {
					$full_stylesheet_input .= "document.source_form.xslt_count.value = ".($i+1)."\n";
					$full_stylesheet_input .= "addLine(".($i+1).");\n";
					$full_stylesheet_input .= "document.source_form.xslt_select_".($i+1).".selectedIndex = ".($stylesheet_lines[$i] ? $stylesheet_lines[$i] : 0).";\n";
				}	

				$full_stylesheet_input .= "
						</script>
				";
//				highlight_string(print_r($stylesheet_lines, true));
				$full_stylesheet_input .= '';
				 
				$form.="
					<div class='row'>
						<div class='colonne3'>
							<label>".$this->msg["sru_xsl_to_pmbunimarc"]."</label>
						</div>
						<div class='colonne_suite'>
							".$full_stylesheet_input."
						</div>
					</div>
					";	
			}
		}
		$form.="
	</div>
	<div class='row'></div>
";
		return $form;
    }
    
    function make_serialized_source_properties($source_id) {
    	global $url,$sets,$formats,$del_deleted,$del_xsl_transform, $chosen_schema, $indexenabled, $max_record_count, $sru_cqlfail_means_global;

    	$oldparams=$this->get_source_params($source_id);
		if ($oldparams["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$oldvars=unserialize($oldparams["PARAMETERS"]);
		}
		$old_style_sheets = $oldvars["style_sheets"];

    	$t["url"]=stripslashes($url);
    	$t["chosen_schema"]=$chosen_schema;
    	$t["allowed_indexes"] = $indexenabled;
    	$t["max_record_per_search"] = $max_record_count;
		$t["sru_cqlfail_means_global"] = isset($sru_cqlfail_means_global) ? 1 : 0; 

    	
    	$field_maps = array();
    	
    	if (!$indexenabled) $indexenabled = array();

    	foreach($indexenabled as $aninded) {
    		$aname = "field_map_".$aninded;
    		global $$aname;
//    		highlight_string(print_r($$aname, true));
    		if (isset($$aname)) {
    			$field_maps[$aninded] = $$aname;
    		}
    	}
    	$t["field_maps"] = $field_maps;

		$style_sheets = array();
    	global $xslt_count;    	
    	if ($xslt_count) {
    		for ($i=1, $count=$xslt_count; $i<=$count; $i++) {
    			$aname = "xslt_select_".$i;
    			global $$aname;
    			
    			if ($$aname == '__CUSTOM__') {
    				$axslt_info = array();
    				$axslt_info["type"] = "custom_file"; 
    				if (($_FILES["xslt_file_".$i])&&(!$_FILES["xslt_file_".$i]["error"])) {
    					$axslt_info["name"] = $_FILES["xslt_file_".$i]["name"];
    					$axslt_info["content"] = file_get_contents($_FILES["xslt_file_".$i]["tmp_name"]);
    				}
    				else {
    					$axslt_info["name"] = "";
    					$axslt_info["content"] = "";
    				}
    			} else if (substr($$aname, 0, 12) == '__KEEP_INDEX') {
    				$index_to_keep = substr($$aname, 12);
					$axslt_info = $old_style_sheets[$index_to_keep];
    			}
    			else {
    				$axslt_info = array();
    				$axslt_info["type"] = "built_in";
    				$axslt_info["name"] = $$aname;
    				$axslt_info["content"] = "built_in";
    			}
    			$style_sheets[] = $axslt_info;
    		}
    	}
    	$t["style_sheets"] = $style_sheets;
    	
		$this->sources[$source_id]["PARAMETERS"]=serialize($t);
	}
	
	//R�cup�ration  des prori�t�s globales par d�faut du connecteur (timeout, retry, repository, parameters)
	function fetch_default_global_values() {
		$this->timeout=5;
		$this->repository=2;
		$this->retry=3;
		$this->ttl=1800;
		$this->parameters="";
	}
	
	//Formulaire des propri�t�s g�n�rales
	function get_property_form() {
		$this->fetch_global_properties();
		return "";
	}
	
	function make_serialized_properties() {
		$this->parameters="";
	}
	
	/* Converti une recherche multi-crit�re PMB en CQL SRU
	 * En se servant des options entr�es dans le formulaire de la source
	 * 
	 */
	
	function mterms_to_cql($query, $sru_cqlfail_means_global) {
		global $allowed_indexes;
		global $field_maps;
		$cql = "";
		$first = true;
		$query_count = count($query);
		$gotone = false;

		foreach ($query as $element) {
			if (!$first)
				$cql .= " ".$element->inter." ";

			if ($query_count > 1)
				$cql .= "(";

			$found = array();
			foreach($field_maps as $key => $content) {
				if (in_array($element->ufield, $content))
					$found[] = $key;
			}
			
			foreach ($found as $index_found) {
				$index_found = str_replace("____", ".", $index_found);
				if ($index_found != "cql._")
					$cql .= $index_found.' any "'.$element->values[0].'" ';
				else 
					$cql .= ' "'.$element->values[0].'" ';
				$cql .= " or ";
				$gotone = true;
			}
			$cql = substr($cql, 0, -4); //On eleve le dernier "or"
			
			if ($query_count > 1)
				$cql .= ")";
		}
		if (!$gotone) return "";
		return $cql;
	}
	
	//Fonction de recherche
	function search($source_id,$query,$search_id) {
		
		$params=$this->get_source_params($source_id);
		$this->fetch_global_properties();
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}

		$this->get_schemas_config();

		$cql = $this->mterms_to_cql($query, $sru_cqlfail_means_global);
		if (!$cql) {
			if ($sru_cqlfail_means_global) {
				$cql = "";
				foreach ($query as $element) {
					$cql .= implode(" ", $element->values)." "; 
				}
			}
			else {
				print $this->msg["sru_no_query"].'<br />';
				return;				
			}
		}

//		print $cql;
		
		$received_count = 0;
		$next_record_position = 1;
		
		$failsafe_count = 0;
		
		//On en demande tant qu'on en a pas eu assez.
		while($received_count < $max_record_per_search) {
			$failsafe_count++;
			if ($failsafe_count > 100) break; //On ne sait jamais...
			
			$parameters = array(
				'version' => '1.1', 
	//			'recordPacking' => 'xml', 
				'maximumRecords' => $max_record_per_search,
				'query' => $cql,
				'startRecord' => $next_record_position 
			);
			
			if ($chosen_schema != 'default')
				$parameters['recordSchema'] = "$chosen_schema"; 
						
			$request = new sru_request($url, "searchRetrieve", $parameters, $this->schema_config, SR_MODE_STYLESHEETS);
			$style_sheets_to_apply = array();
			foreach($style_sheets as $style_sheet) {
				if ($style_sheet["type"] == "built_in")
					$style_sheets_to_apply[] = file_get_contents("admin/connecteurs/in/sru/xslt/".$style_sheet["name"]);
				else if ($style_sheet["type"] == "custom_file")
					$style_sheets_to_apply[] = $style_sheet["content"];
			}
			$request->style_sheets_to_apply = $style_sheets_to_apply;
			if (!$request->error) {
				$result = $request->analyse_response('ISO-8859-1');
				$received_count = 10000;
				if ($result) {
					$received_count += count($result["records"]);

					if ($result['records']) {
						foreach ($result["records"] as $record) {
							$this->rec_record($record["record_unimarc"], $source_id, $search_id);
						}

						if (isset($result["next_record_position"]))
							$next_record_position = $result["next_record_position"];
						else 
							break;
//						highlight_string(print_r($result["records"], true));
					}
					else 
						break;
				}
				else {
					$this->error = true;
					$this->error_message = $request->error_message;
					return;
				}				
			}
			else {
				$this->error = true;
				$this->error_message = $request->error_message;
				return;				
			}
			
		flush();
		}
		
	}
	
	function get_schemas_config() {

		//Si on l'a d�j� fait, on s'en souvient
		if ($this->schema_config) {
			return $this->schema_config;
		}
		$result = array();
		$file = file_get_contents('admin/connecteurs/in/sru/schema_xslts.xml');
		$dom = new xml_dom_sru($file);
		
		$node = $dom->get_nodes('schemas_xslts/schemas/schema');
		foreach ($node as $schema) {
			$attribs = $dom->get_attributes($schema);
			$id = $attribs['id'];
			$result[$id]['id'] = $id;
			$long_formats = explode("\n", $dom->get_datas($schema));
			foreach ($long_formats as $long_format) {
				$trimmed = trim($long_format); 
				if ($trimmed)
					$result[$id]['long_formats'][] = $trimmed;
			}
		}
		
		$node = $dom->get_nodes('schemas_xslts/schemas_to_pmbunimarc/schema');
		foreach ($node as $schema) {
			$attribs = $dom->get_attributes($schema);
			$id = $attribs['id'];
			$result[$id]['id'] = $id;
			$long_formats = explode("\n", $dom->get_datas($schema));
			foreach ($long_formats as $long_format) {
				$trimmed = trim($long_format); 
				if ($trimmed)
					$result[$id]['stylesheets'][] = $trimmed;
			}
		}
		$this->schema_config = $result; 
		return $result;
	}
	
	function record_schema_to_list_of_style_sheets($schema) {
		$to_unimarc_style_sheets = array();
		if ($schema) {
			$schema_config = $this->get_schemas_config();
			if (isset($schema_config[$this->record_schema]["stylesheets"]))
				$to_unimarc_style_sheets = $schema_config[$this->record_schema]["stylesheets"];			
		}
		return $to_unimarc_style_sheets;
	}
	
	function rec_record($record, $source_id, $search_id) {
		global $charset,$base_path;
		//On a un enregistrement unimarc, on l'enregistre
		$rec_uni_dom=new xml_dom_sru($record,$charset, false);
		if (!$rec_uni_dom->error) {
			//Initialisation
			$ref="";
			$ufield="";
			$usubfield="";
			$field_order=0;
			$subfield_order=0;
			$value="";
			$date_import=date("Y-m-d H:i:s",time());
			
			$fs=$rec_uni_dom->get_nodes("unimarc/notice/f");
			//Recherche du 001
			if ($fs)
				for ($i=0; $i<count($fs); $i++) {
					if ($fs[$i]["ATTRIBS"]["c"]=="001") {
						$ref=$rec_uni_dom->get_datas($fs[$i]);
						break;
					}
				}
			if (!$ref) $ref = md5($record);
			//Mise � jour 
			if ($ref) {
				//Si conservation des anciennes notices, on regarde si elle existe
				if (!$this->del_old) {
					$requete="select count(*) from entrepot_source_".$source_id." where ref='".addslashes($ref)."'";
					$rref=mysql_query($requete);
					if ($rref) $ref_exists=mysql_result($rref,0,0);
				}
				//Si pas de conservation des anciennes notices, on supprime
				if ($this->del_old) {
//					$requete="delete from entrepot_source_".$source_id." where ref='".addslashes($ref)."'";
//					mysql_query($requete);
				}
				$ref_exists = false;
				//Si pas de conservation ou ref�rence inexistante
				if (($this->del_old)||((!$this->del_old)&&(!$ref_exists))) {
					//Insertion de l'ent�te
					$n_header["rs"]=$rec_uni_dom->get_value("unimarc/notice/rs");
					$n_header["ru"]=$rec_uni_dom->get_value("unimarc/notice/ru");
					$n_header["el"]=$rec_uni_dom->get_value("unimarc/notice/el");
					$n_header["bl"]=$rec_uni_dom->get_value("unimarc/notice/bl");
					$n_header["hl"]=$rec_uni_dom->get_value("unimarc/notice/hl");
					$n_header["dt"]=$rec_uni_dom->get_value("unimarc/notice/dt");
					
					//R�cup�ration d'un ID
					$requete="insert into external_count (recid, source_id) values('".addslashes($this->get_id()." ".$source_id." ".$ref)."', ".$source_id.")";
					$rid=mysql_query($requete);
					if ($rid) $recid=mysql_insert_id();
					
					foreach($n_header as $hc=>$code) {
						$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid, search_id) values(
						'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
						'".$hc."','',-1,0,'".addslashes($code)."','',$recid, '$search_id')";
						mysql_query($requete);
					}
					if ($fs)
					for ($i=0; $i<count($fs); $i++) {
						$ufield=$fs[$i]["ATTRIBS"]["c"];
						$field_order=$i;
						$ss=$rec_uni_dom->get_nodes("s",$fs[$i]);
						if (is_array($ss)) {
							for ($j=0; $j<count($ss); $j++) {
								$usubfield=$ss[$j]["ATTRIBS"]["c"];
								$value=$rec_uni_dom->get_datas($ss[$j]);
								$subfield_order=$j;
								$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid, search_id) values(
								'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
								'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
								' ".addslashes(strip_empty_words($value))." ',$recid, '$search_id')";
								mysql_query($requete);
							}
						} else {
							$value=$rec_uni_dom->get_datas($fs[$i]);
							$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid, search_id) values(
							'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
							'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
							' ".addslashes(strip_empty_words($value))." ',$recid, '$search_id')";
							mysql_query($requete);
						}
					}
				}
				$this->n_recu++;
			}
		}
	}
		
}
?>