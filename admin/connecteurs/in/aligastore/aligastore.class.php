<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: aligastore.class.php,v 1.10 2011-04-15 15:16:00 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");
require_once($base_path."/admin/connecteurs/in/aligastore/aligastore_protocol.class.php");
require_once("$include_path/isbn.inc.php");
require_once("$class_path/caddie.class.php");

if (version_compare(PHP_VERSION,'5','>=') && extension_loaded('xsl')) {
	if (substr(phpversion(), 0, 1) == "5") @ini_set("zend.ze1_compatibility_mode", "0");
	require_once($include_path.'/xslt-php4-to-php5.inc.php');
}

if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}


//Voici un array_unique qui marche aussi avec des objects et des arrays.
function array_unique_more($array, $keep_key_assoc = false)
{
    $duplicate_keys = array();
    $tmp         = array();       

    foreach ($array as $key=>$val)
    {
        // convert objects to arrays, in_array() does not support objects
        if (is_object($val))
            $val = (array)$val;

        if (!in_array($val, $tmp))
            $tmp[] = $val;
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key)
        unset($array[$key]);
       
    return $keep_key_assoc ? $array : array_values($array);
}

class aligastore extends connector {
	//Variables internes pour la progression de la récupération des notices
	var $callback_progress;		//Nom de la fonction de callback progression passée par l'appellant
	var $current_set;			//Set en cours de synchronisation
	var $total_sets;			//Nombre total de sets sélectionnés
	var $metadata_prefix;		//Préfixe du format de données courant
	var $source_id;				//Numéro de la source en cours de synchro
	var $search_id;
	var $xslt_transform;		//Feuille xslt transmise
	var $sets_names;			//Nom des sets pour faire plus joli !!
	var $del_old;				//Supression ou non des notices dejà existantes
	var $url;
	var $username;
	var $password;
	var $blank_image;
	var $image_thumb_url;
	var $image_front;
	var $image_back;
	var $image_folder;
	var $image_folder_url;
	var $fetchimages;
	
	//Résultat de la synchro
	var $error;					//Y-a-t-il eu une erreur	
	var $error_message;			//Si oui, message correspondant
	
    function aligastore($connector_path="") {
    	parent::connector($connector_path);
    }
    
    function get_id() {
    	return "aligastore";
    }
    
    //Est-ce un entrepot ?
	function is_repository() {
		return 3;
	}
    
    function unserialize_source_params($source_id) {
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			$params["PARAMETERS"]=$vars;
		}
		return $params;
    }
    
    function source_get_property_form($source_id) {
    	global $charset;
    	
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		//URL
		if (!isset($url))
			$url = "http://www.aligastore.com/partenaires/xmldetaillivre.php";
		$form="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["aliga_base_url"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='url' id='url' class='saisie-60em' value='".htmlentities($url,ENT_QUOTES,$charset)."'/>
			</div>
		</div>";

		//Username
		if (!isset($username))
			$username="";
		$form.="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["aliga_username"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='username' id='url' class='saisie-30em' value='".htmlentities($username,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		";
		
		//Password
		if (!isset($password))
			$password="";
		$form.="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["aliga_password"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='password' id='url' class='saisie-30em' value='".htmlentities($password,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		";

		//Image Thumb URL
		if (!isset($image_thumb_url))
			$image_thumb_url="http://www.aligastore.com/query.dll/img?gcdFab=!!isbn!!&type=0";
		$form.="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["aliga_image_thumburl"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='image_thumb_url' id='image_thumb_url' class='saisie-60em' value='".htmlentities($image_thumb_url,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		";
		
		//Image Front
		if (!isset($image_front))
			$image_front="http://www.aligastore.com/query.dll/img?gcdFab=!!isbn!!&type=1";
		$form.="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["aliga_image_front"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='image_front' id='image_front' class='saisie-60em' value='".htmlentities($image_front,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		";

		//Image Back
		if (!isset($image_back))
			$image_back="http://www.aligastore.com/query.dll/img?gcdFab=!!isbn!!&type=4";
		$form.="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["aliga_image_back"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='image_back' id='image_back' class='saisie-60em' value='".htmlentities($image_back,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		";

		//Image Fetch?
		if (!isset($fetch_images))
			$fetch_images=0;
		$form.="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["aliga_fetch_images"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='checkbox' name='fetch_images' ".($fetch_images ? 'checked' : '')." id='fetch_images'/>
			</div>
		</div>
		";

		//Image Folder
		if (!isset($image_folder))
			$image_folder="";
		$form.="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["aliga_image_folder"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='image_folder' id='image_folder' class='saisie-30em' value='".htmlentities($image_folder,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		";
		
		//Image Folder Public URL
		if (!isset($image_folder_public))
			$image_folder_public="";
		$form.="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["aliga_image_folder_public"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='image_folder_public' id='image_folder_public' class='saisie-30em' value='".htmlentities($image_folder_public,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		";

		$form.="
	<div class='row'></div>
";
		return $form;
    }
    
    function make_serialized_source_properties($source_id) {
    	global $url,$username, $password, $fetch_images, $image_folder, $image_thumb_url, $image_front, $image_back, $image_folder_public;
    	$t["url"]=stripslashes($url);
    	$t["username"]=stripslashes($username);
    	$t["password"]=stripslashes($password);
    	$t["fetch_images"]=stripslashes($fetch_images);
    	$t["image_folder"]=stripslashes($image_folder);
    	$t["image_folder_public"]=stripslashes($image_folder_public);
    	$t["image_thumb_url"]=stripslashes($image_thumb_url);
    	$t["image_front"]=stripslashes($image_front);
    	$t["image_back"]=stripslashes($image_back);

		$this->sources[$source_id]["PARAMETERS"]=serialize($t);
	}
	
	//Récupération  des proriétés globales par défaut du connecteur (timeout, retry, repository, parameters)
	function fetch_default_global_values() {
		$this->timeout=5;
		$this->repository=1;
		$this->retry=3;
		$this->ttl=1800;
		$this->parameters="";
	}
	
	//Formulaire des propriétés générales
	function get_property_form() {
		$this->fetch_global_properties();
		return "";
	}
	
	function make_serialized_properties() {
		$this->parameters="";
	}
	
	function rec_record($record, $source_id, $search_id) {
		global $charset,$base_path;
		if (!trim($record))
			return;
		//On a un enregistrement unimarc, on l'enregistre
		$rec_uni_dom=new xml_dom_aligastore($record,$charset, false);
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
			//Mise à jour
			if ($ref) {
				//Si conservation des anciennes notices, on regarde si elle existe
				if (!$this->del_old) {
					$requete="select count(*) from entrepot_source_".$source_id." where ref='".addslashes($ref)."'";
					$rref=mysql_query($requete);
					if ($rref) $ref_exists=mysql_result($rref,0,0);
				}
				//Si pas de conservation des anciennes notices, on supprime
				if ($this->del_old) {
					$requete="delete from entrepot_source_".$source_id." where ref='".addslashes($ref)."'";
					mysql_query($requete);
				}
				$ref_exists = false;
				//Si pas de conservation ou refï¿½rence inexistante
				if (($this->del_old)||((!$this->del_old)&&(!$ref_exists))) {
					//Insertion de l'entï¿½te
					$n_header["rs"]=$rec_uni_dom->get_value("unimarc/notice/rs");
					$n_header["ru"]=$rec_uni_dom->get_value("unimarc/notice/ru");
					$n_header["el"]=$rec_uni_dom->get_value("unimarc/notice/el");
					$n_header["bl"]=$rec_uni_dom->get_value("unimarc/notice/bl");
					$n_header["hl"]=$rec_uni_dom->get_value("unimarc/notice/hl");
					$n_header["dt"]=$rec_uni_dom->get_value("unimarc/notice/dt");
					
					//Rï¿½cupï¿½ration d'un ID
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
		
	function cancel_maj($source_id) {
		return false;
	}
	
	function break_maj($source_id) {
		return false;
	}
	
	function form_pour_maj_entrepot($source_id) {
		$form = "";
		
		$form .= "<script>";
		$form .= "
		function unckeckall() {
			for (i=0, count=document.sync_form.elements.length; i<count; i++) {
				if (document.sync_form.elements[i].value.substr(0, 21) == 'synchro_noticecaddie_') {
					document.sync_form.elements[i].checked = false;
				}
				if (document.sync_form.elements[i].value.substr(0, 19) == 'synchro_explcaddie_') {
					document.sync_form.elements[i].checked = false;
				}
			}
		}

		function clear_the_radio_button() {
			document.getElementById('quoi_synchro_synchro_base').checked = false;
		}";		
		$form .= "</script>";
		
		$form .= $this->msg["aliga_syncbase"];
		$form .= '<blockquote>';
		$form .= '<input type="radio" onclick="unckeckall();" checked name="quoi_synchro[]" value="synchro_base" id="quoi_synchro_synchro_base"><label for="quoi_synchro_synchro_base">'.$this->msg["aliga_syncbase"].'</label>';
		$form .= '</blockquote>';

		$form .= $this->msg["aliga_sync_noticecaddie"];
		$form .= '<blockquote>';
		$caddies = caddie::get_cart_list("NOTI");
		foreach ($caddies as $caddie) {
			$form .= '<input type="checkbox" onclick="clear_the_radio_button()" name="quoi_synchro[]" value="synchro_noticecaddie_'.$caddie["idcaddie"].'" id="quoi_synchro_synchro_noticecaddie_'.$caddie["idcaddie"].'"><label for="quoi_synchro_synchro_noticecaddie_'.$caddie["idcaddie"].'">'.$caddie["name"].'</label><i> ('.($caddie["nb_item_base"]).' '.$this->msg["aliga_caddie_element"].')</i><br />';			
		}
		$form .= '</blockquote>';
		
		
		$form .= $this->msg["aliga_sync_explcaddie"];
		$form .= '<blockquote>';
		$caddies = caddie::get_cart_list("EXPL");
		foreach ($caddies as $caddie) {
			$form .= '<input type="checkbox" onclick="clear_the_radio_button()" name="quoi_synchro[]" value="synchro_explcaddie_'.$caddie["idcaddie"].'" id="quoi_synchro_synchro_explcaddie_'.$caddie["idcaddie"].'"><label for="quoi_synchro_synchro_explcaddie_'.$caddie["idcaddie"].'">'.$caddie["name"].' <i>('.($caddie["nb_item_base"]).' '.$this->msg["aliga_caddie_element"].')</i></label><br />';
		}				
		$form .= '</blockquote>';
		$form .= "<br /><br />";
		return $form;
	}

	//Nécessaire pour passer les valeurs obtenues dans form_pour_maj_entrepot au javascript asynchrone
	function get_maj_environnement($source_id) {
		global $quoi_synchro;
		$envt=array();
		$envt["quoi_synchro"]=$quoi_synchro;
		return $envt;
	}
	
	function get_image_information($isbn, $download_images) {
		//Récupération et traitement des images et des zones associées.
		$bypass_testvalidity = true;
		$images_status = array(
			"thumb" => "",
			"front" => "",
			"back" => ""
		);
		if ($download_images) {
			$images_status = $this->fetch_and_record_images($isbn);
		}
		if (!$images_status["thumb"] && $this->image_thumb_url) {
			if ($bypass_testvalidity || $this->test_image_validity($isbn, $this->image_thumb_url)) {
				$url = str_replace("!!isbn!!", $isbn, $this->image_thumb_url);
				$images_status["thumb"] = $url;
			}
		}
		if (!$images_status["front"] && $this->image_front) {
			if ($bypass_testvalidity || $this->test_image_validity($isbn, $this->image_front)) {
				$url = str_replace("!!isbn!!", $isbn, $this->image_front);
				$images_status["front"] = $url;
			}
		}
		if (!$images_status["back"] && $this->image_back) {
			if ($bypass_testvalidity || $this->test_image_validity($isbn, $this->image_back)) {
				$url = str_replace("!!isbn!!", $isbn, $this->image_back);
				$images_status["back"] = $url;
			}
		}
		
		$image_information = "";
		if ($images_status["thumb"]) {
			$image_information .= '<f c="896">';
			$image_information .=   '<s c="a">';
			$image_information .=     htmlspecialchars($images_status["thumb"]);
			$image_information .=   '</s>';
			$image_information .= '</f>';
		}
		if ($images_status["front"]) {
			$image_information .= '<f c="897">';
			$image_information .=   '<s c="a">';
			$image_information .=     htmlspecialchars($images_status["front"]);
			$image_information .=   '</s>';
			$image_information .=   '<s c="b">';
			$image_information .=     htmlspecialchars($this->msg["aliga_cover_front"]);
			$image_information .=   '</s>';
			$image_information .= '</f>';
		}
		if ($images_status["back"]) {
			$image_information .= '<f c="897">';
			$image_information .=   '<s c="a">';
			$image_information .=     htmlspecialchars($images_status["back"]);
			$image_information .=   '</s>';
			$image_information .=   '<s c="b">';
			$image_information .=     htmlspecialchars($this->msg["aliga_cover_back"]);
			$image_information .=   '</s>';
			$image_information .= '</f>';
		}
		
		return $image_information;
	}
	
	function fetch_and_record_notice($isbn, $xsl) {
		if (!$isbn)
			return;
	
		$parameters = array(
			"LOG" => $this->username,
			"PASS" => $this->password,
			"GcdFab" => $isbn
		);
		$base_url = $this->url;
		
		$arequest = new aligastore_request($base_url, $parameters);
		$arequest->aligastore_response();
		if ($arequest->data) {
			$arequest->data = $this->apply_xsl_to_xml($arequest->data, $xsl);
			
			$images_are_present = preg_match("<!--!!!__IMAGEINFO_YES__!!!-->", $arequest->data);
			if ($images_are_present)
				$image_info = $this->get_image_information($isbn, $this->fetchimages);
			else $image_info = "";

			$arequest->data = str_replace("<!--!!!__IMAGEINFO_YES__!!!-->", "", $arequest->data);
			$arequest->data = str_replace("<!--!!!__IMAGEINFO_NO__!!!-->", "", $arequest->data);
				
			$arequest->data = str_replace("<!--!!!__thumbnail_information__!!!-->", $image_info, $arequest->data);
			$this->rec_record($arequest->data, $this->source_id, $this->search_id);
		}
	}
	
	function fetch_and_record_images($isbn) {
		if (!is_dir($this->image_folder))
			return;

		$result = array(
			"thumb" => "",
			"front" => "",
			"back" => ""
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$folder = $this->image_folder;
		$folder_url = $this->image_folder_url;
		
		configurer_proxy_curl($ch);		
		
		if (!file_exists($folder."/".$isbn."_thumb.jpg")) {
			$url = str_replace("!!isbn!!", $isbn, $this->image_thumb_url);
			curl_setopt($ch, CURLOPT_URL, $url);
			$buffer = curl_exec($ch);
			if (!curl_error($ch)) {
				file_put_contents($folder."/".$isbn."_thumb.jpg", $buffer);
				$result['thumb'] = $folder_url."/".$isbn."_thumb.jpg";
			}
		}
		else
			$result['thumb'] = $folder_url."/".$isbn."_thumb.jpg";
		
		if (!file_exists($folder."/".$isbn."_front.jpg")) {
			$url = str_replace("!!isbn!!", $isbn, $this->image_front);
			curl_setopt($ch, CURLOPT_URL, $url);
			$buffer = curl_exec($ch);
			if (!curl_error($ch)) {
				file_put_contents($folder."/".$isbn."_front.jpg", $buffer);
				$result['front'] = $folder_url."/".$isbn."_front.jpg";
			}
		
		}
		else
			$result['front'] = $folder_url."/".$isbn."_front.jpg";

		if (!file_exists($folder."/".$isbn."_back.jpg")) {
			$url = str_replace("!!isbn!!", $isbn, $this->image_back);
			curl_setopt($ch, CURLOPT_URL, $url);
			$buffer = curl_exec($ch);
			if (!curl_error($ch)) {
				file_put_contents($folder."/".$isbn."_back.jpg", $buffer);
				$result['back'] = $folder_url."/".$isbn."_back.jpg";
			}
		}
		else
			$result['back'] = $folder_url."/".$isbn."_back.jpg";
	
		curl_close($ch);
		return $result;
	}
	
	function get_blank_image($url_thumb) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$url = str_replace("!!isbn!!", "", $url_thumb);
		curl_setopt($ch, CURLOPT_URL, $url);
		$buffer = curl_exec($ch);
		curl_close($ch);
		return $buffer;
	}
	
	function test_image_validity($isbn, $url) {
		if (!$this->blank_image) {
			$this->blank_image = $this->get_blank_image($url);
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$url = str_replace("!!isbn!!", $isbn, $url);
		curl_setopt($ch, CURLOPT_URL, $url);
		$image = curl_exec($ch);
		curl_close($ch);
		
		return !($image == $this->blank_image);
	}
	
	function maj_entrepot($source_id,$callback_progress="",$recover=false,$recover_env="") {
		global $dbh;
		@set_time_limit(0);
		//Allons chercher plein d'informations utiles et amusantes
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
		if (!isset($url))
			$url = "";
		if (!isset($username))
			$username = "";
		if (!isset($password))
			$password = "";
		if (!isset($fetch_images))
			$fetch_images = 0;
		if (!isset($image_folder))
			$image_folder = 0;
		if (!isset($image_folder_public))
			$image_folder_public = 0;
		if (!isset($image_thumb_url))
			$image_thumb_url = 0;
		if (!isset($image_front))
			$image_front = 0;
		if (!isset($image_back))
			$image_back = 0;

		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
		$this->source_id = $source_id;
		$this->search_id = 0;
		$this->image_thumb_url = $image_thumb_url;
		$this->image_front = $image_front;
		$this->image_back = $image_back;
		$this->image_folder = $image_folder;
		$this->image_folder_url = $image_folder_public;
		$this->fetchimages = $fetch_images;
		
		if ($recover) {
			$envt=unserialize($recover_env);
			$start_notice_id = $envt["current_notice_id"];
			$count_lu=$envt["already_read_count"];
		}
		else {
			$start_notice_id = 0;
			$count_lu = 0;
		}

		//Obtenons une liste d'ISBN à synchroniser
			//Toute la base
		$isbns = array();
		global $quoi_synchro;
		if (!$quoi_synchro)
			$quoi_synchro = array("synchro_base");
			
		foreach($quoi_synchro as $quoi_synchru) {
			if ($quoi_synchru == "synchro_base") {
				$sql_local = "SELECT code, notice_id FROM notices WHERE code != '' AND notice_id > ".addslashes($start_notice_id)." ORDER BY notice_id";
				$res = mysql_query($sql_local);
				while($row = mysql_fetch_row($res)) {
					if (isISBN($row[0])) {
						$isbns[] = array("isbn" => $row[0], "notice_id" => $row[1]);
					}
				}			
			}
				//Panier de notice
			else if (substr($quoi_synchru, 0, 21) == "synchro_noticecaddie_") { 
				$caddie_id = substr($quoi_synchru, 21);
				$caddie_content_sql = "SELECT notices.code, notices.notice_id FROM caddie_content LEFT JOIN notices ON (caddie_content.object_id = notices.notice_id) WHERE caddie_content.caddie_id = ".addslashes($caddie_id).' AND notices.code != \'\'';
				$res = mysql_query($caddie_content_sql, $dbh);
				while ($row=mysql_fetch_row($res)) {
					if (isISBN($row[0])) {
						$isbns[] = array("isbn" => $row[0], "notice_id" => $row[1]);
					}
				}
			}
				//Panier d'exemplaires
			else if (substr($quoi_synchru, 0, 19) == "synchro_explcaddie_") { 
				$caddie_id = substr($quoi_synchru, 19);
				$caddie_content_sql = "SELECT notices.code, notices.notice_id FROM caddie_content, exemplaires, notices  WHERE caddie_content.object_id = exemplaires.expl_id AND exemplaires.expl_notice = notices.notice_id AND caddie_content.caddie_id = ".addslashes($caddie_id).' AND notices.code != \'\'';
				$res = mysql_query($caddie_content_sql, $dbh);
				while ($row=mysql_fetch_row($res)) {
					if (isISBN($row[0])) {
						$isbns[] = array("isbn" => $row[0], "notice_id" => $row[1]);
					}
				}
			}			
		}

	
		//Et on dédoublonne
		$isbns = array_unique_more($isbns);

		//Allons chercher la feuille de conversion aliga->pmbunimarc
		global $base_path;
		$xsl_transform = file_get_contents($base_path."/admin/connecteurs/in/aligastore/xslt/aligatopmbunimarx.xsl");
		
		//Et c'est parti
		$count_total = count($isbns)+$count_lu;
		$latest_percent = floor(100 * $count_lu / $count_total);
		foreach ($isbns as $isbn) {
			//Si on veut des images, il nous faut un isbn 13
			$isbn["isbn"] = formatISBN($isbn["isbn"], 13);
			$isbn["isbn"] = preg_replace('/-|\.| /', '', $isbn["isbn"]);
			
			//Fetch!
			$this->fetch_and_record_notice($isbn["isbn"], $xsl_transform);
			
			if (floor(100 * $count_lu / $count_total) > $latest_percent) {
				//Mise à jour de source_sync pour reprise en cas d'erreur
				$envt["current_notice_id"]=$isbn["notice_id"];
				$envt["already_read_count"]=$count_lu;
				$requete="update source_sync set env='".addslashes(serialize($envt))."' where source_id=".$source_id;
				mysql_query($requete);
				
				//Inform
				$callback_progress($count_lu / $count_total, $count_lu, $count_total);
				$latest_percent = floor(100 * $count_lu / $count_total);
				flush();
				ob_flush();		
			}
			$count_lu++;
		}
		
		return $count_lu;
	}
	
	function apply_xsl_to_xml($xml, $xsl) {
		global $charset;
		$xh = xslt_create();
		xslt_set_encoding($xh, $charset);
		$arguments = array(
	   	  '/_xml' => $xml,
	   	  '/_xsl' => $xsl
		);
		$result = xslt_process($xh, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments);
		xslt_free($xh);
		return $result;		
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
		if (!isset($url))
			$url = "";
		if (!isset($username))
			$username = "";
		if (!isset($password))
			$password = "";
		if (!isset($fetch_images))
			$fetch_images = 0;
		if (!isset($image_folder))
			$image_folder = 0;
		if (!isset($image_folder_public))
			$image_folder_public = 0;
		if (!isset($image_thumb_url))
			$image_thumb_url = 0;
		if (!isset($image_front))
			$image_front = 0;
		if (!isset($image_back))
			$image_back = 0;

		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
		$this->source_id = $source_id;
		$this->search_id = 0;
		$this->image_thumb_url = $image_thumb_url;
		$this->image_front = $image_front;
		$this->image_back = $image_back;
		$this->image_folder = $image_folder;
		$this->image_folder_url = $image_folder_public;
		$this->fetchimages = $fetch_images;

		$isbns = array();
		foreach($query as $amterm) {
			if ($amterm->ufield == '010$a') {
				$isbns[] = $amterm->values[0];
			}
		}
		
		global $base_path;
		$xsl_transform = file_get_contents($base_path."/admin/connecteurs/in/aligastore/xslt/aligatopmbunimarx.xsl");
		
		foreach($isbns as $isbn) {
			//Si on veut des images, il nous faut un isbn 13
			$isbn = formatISBN($isbn, 13);
			$isbn = preg_replace('/-|\.| /', '', $isbn);
			
			$this->fetch_and_record_notice($isbn, $xsl_transform);
		}

	}
			
	function enrichment_is_allow(){
		return true;
	}
	
	function getEnrichmentHeader(){
		$header= array();
		$header[]= "<!-- Script d'enrichissement pour Alligastore-->";
		return $header;
	}
	
	function getTypeOfEnrichment($source_id){
		$type['type'] = array(
			"resume",
			"sommaire",
			"bio"
		);		
		$type['source_id'] = $source_id;
		return $type;		
	}
	
	function getEnrichment($notice_id,$source_id,$type=""){
		$enrichment= array();
		$infos = $this->getNoticeInfos($notice_id,$source_id);
		//on renvoi ce qui est demandé... si on demande rien, on renvoi tout..
		switch ($type){
			case "resume" :
				if($infos['resume']) $enrichment['resume']['content'] = $infos['resume'];
				else $enrichment['resume']['content'] = $this->msg['aliga_enrichment_no_resume'];
				break;
			case "sommaire" :
				if($infos['sommaire']) $enrichment['sommaire']['content'] = $infos['sommaire'];
				else $enrichment['sommaire']['content'] = $this->msg['aliga_enrichment_no_sommaire'];
				break;
			case "bio" :
				if($infos['bio']) $enrichment['bio']['content'] = $infos['bio'];
				else $enrichment['bio']['content'] = $this->msg['aliga_enrichment_no_bio'];
				break;
			default :
				if($infos['resume']) $enrichment['resume']['content'] = $infos['resume'];
				else $enrichment['resume']['content'] = $this->msg['aliga_enrichment_no_resume'];
				if($infos['sommaire']) $enrichment['sommaire']['content'] = $infos['sommaire'];
				else $enrichment['sommaire']['content'] = $this->msg['aliga_enrichment_no_sommaire'];
				if($infos['bio']) $enrichment['bio']['content'] = $infos['bio'];
				else $enrichment['bio']['content'] = $this->msg['aliga_enrichment_no_bio'];
				break;
		}		
		
		$enrichment['source_label']=$this->msg['aliga_enrichment_source'];	
		return $enrichment;
	}
	
	function getNoticeInfos($notice_id,$source_id){
		global $base_path,$charset;
		$this->initSource($source_id);
		$return = array();
		$rqt = "select code from notices where notice_id = '".$notice_id."'";
		$res = mysql_query($rqt);
		if(mysql_num_rows($res)){
			$code = mysql_result($res,0,0);
			$code = preg_replace('/-|\.| /', '', $code);
			if($code != ""){
				$parameters = array(
					"LOG" => $this->username,
					"PASS" => $this->password,
					"GcdFab" => $code
				);
				$arequest = new aligastore_request($this->url, $parameters);
				$arequest->aligastore_response();
			//	$xsl_transform = file_get_contents($base_path."/admin/connecteurs/in/aligastore/xslt/aligatopmbunimarx.xsl");
				$xsl_transform = file_get_contents($base_path."/admin/connecteurs/in/aligastore/xslt/aligaEnrichment.xsl");
				if ($arequest->data){
					file_put_contents("/home/arenou/public_html/alligastore.xml",$arequest->data);
					$arequest->data = $this->apply_xsl_to_xml($arequest->data, $xsl_transform);
					file_put_contents("/home/arenou/public_html/alligastore-convert.xml",$arequest->data);
					$dom=new xml_dom_aligastore($arequest->data,$charset, false);
					$return['resume'] = $dom->get_value("enrichment/resume");
					$return['bio'] = $dom->get_value("enrichment/biographie");
					$return['sommaire'] = $dom->get_value("enrichment/sommaire");
				} 
			}
		}
		return $return;
	}
	
	function initSource($source_id){
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
		if (!isset($url))
			$url = "";
		if (!isset($username))
			$username = "";
		if (!isset($password))
			$password = "";
		if (!isset($fetch_images))
			$fetch_images = 0;
		if (!isset($image_folder))
			$image_folder = 0;
		if (!isset($image_folder_public))
			$image_folder_public = 0;
		if (!isset($image_thumb_url))
			$image_thumb_url = 0;
		if (!isset($image_front))
			$image_front = 0;
		if (!isset($image_back))
			$image_back = 0;

		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
		$this->source_id = $source_id;
		$this->search_id = 0;
		$this->image_thumb_url = $image_thumb_url;
		$this->image_front = $image_front;
		$this->image_back = $image_back;
		$this->image_folder = $image_folder;
		$this->image_folder_url = $image_folder_public;
		$this->fetchimages = $fetch_images;
	}
}
?>