<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: titre_uniforme.class.php,v 1.13 2010-06-16 12:13:48 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/notice.class.php");
require_once("$class_path/aut_link.class.php");

class titre_uniforme {
	
	// ---------------------------------------------------------------
	//		propriétés de la classe
	// ---------------------------------------------------------------	
	var $id;		// MySQL id in table 'titres_uniformes'
	var $name;		// titre_uniforme name
	var $tonalite;	// web de l'auteur
	var $comment ; // Commentaire, peut contenir du HTML
	
	// ---------------------------------------------------------------
	//		auteur($id) : constructeur
	// ---------------------------------------------------------------
	function titre_uniforme($id=0,$recursif=0) {
		if($id) {
			// on cherche à atteindre une notice existante
			$this->recursif=$recursif;
			$this->id = $id;
			$this->getData();
		} else {
			// la notice n'existe pas
			$this->id = 0;
			$this->getData();
		}
	}
	
	// ---------------------------------------------------------------
	//		getData() : récupération infos auteur
	// ---------------------------------------------------------------
	function getData() {
		global $dbh,$msg;

		$this->name = '';			
		$this->tonalite = '';
		$this->comment ='';
		$this->distrib=array();
		$this->ref=array();
		$this->subdiv=array();
		$this->libelle="";
		if($this->id) {
			$requete = "SELECT * FROM titres_uniformes WHERE tu_id=$this->id LIMIT 1 ";
			$result = @mysql_query($requete, $dbh);
			if(mysql_num_rows($result)) {
				$temp = mysql_fetch_object($result);				
				$this->id	= $temp->tu_id;
				$this->name	= $temp->tu_name;
				$this->tonalite	= $temp->tu_tonalite;
				$this->comment	= $temp->tu_comment	;				
				$libelle[]=$this->name;
				if($this->tonalite)$libelle[]=$this->tonalite;
				$requete = "SELECT * FROM tu_distrib WHERE distrib_num_tu='$this->id' order by distrib_ordre";
				$result = mysql_query($requete, $dbh);
				if(mysql_num_rows($result)) {
					while(($param=mysql_fetch_object($result))) {
						$this->distrib[]["label"]=$param->distrib_name;
						$libelle[]=$param->distrib_name;
					}	
				}					
				$requete = "SELECT *  FROM tu_ref WHERE ref_num_tu='$this->id' order by ref_ordre";
				$result = mysql_query($requete, $dbh);
				if(mysql_num_rows($result)) {
					while(($param=mysql_fetch_object($result))) {
						$this->ref[]["label"]=$param->ref_name;
						$libelle[]=$param->ref_name;
					}	
				}			
				$requete = "SELECT *  FROM tu_subdiv WHERE subdiv_num_tu='$this->id' order by subdiv_ordre";
				$result = mysql_query($requete, $dbh);
				if(mysql_num_rows($result)) {
					while(($param=mysql_fetch_object($result))) {
						$this->subdiv[]["label"]=$param->subdiv_name;
						$libelle[]=$param->subdiv_name;
					}	
				}	
				$this->libelle=implode("; ",$libelle);				
			} else {
				// pas trouvé avec cette clé
				$this->id = 0;				
				
			}
		}
	}
	function gen_input_selection($label,$form_name,$item,$values,$what_sel,$class='saisie-80em' ) {  
	
		global $msg;
		$select_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";
		$link="'./select.php?what=$what_sel&caller=$form_name&p1=f_".$item."_code!!num!!&p2=f_".$item."!!num!!&deb_rech='+escape(this.form.f_".$item."!!num!!.value), '$what_sel', 400, 400, -2, -2, '$select_prop'";
		$size_item=strlen($item)+2;
		$script_js="
		<script>
		function fonction_selecteur_".$item."() {
			var nom='f_".$item."';
	        name=this.getAttribute('id').substring(4);  
			name_id = name.substr(0,nom.length)+'_code'+name.substr(nom.length);
			openPopUp('./select.php?what=$what_sel&caller=$form_name&p1='+name_id+'&p2='+name, '$what_sel', 400, 400, -2, -2, '$select_prop');
	        
	    }
	    function fonction_raz_".$item."() {
	        name=this.getAttribute('id').substring(4);
			name_id = name.substr(0,$size_item)+'_code'+name.substr($size_item);
	        document.getElementById(name).value='';
			document.getElementById(name_id).value='';
	    }
	    function add_".$item."() {
	        template = document.getElementById('add".$item."');
	        ".$item."=document.createElement('div');
	        ".$item.".className='row';
	
	        suffixe = eval('document.".$form_name.".max_".$item.".value')
	        nom_id = 'f_".$item."'+suffixe
	        f_".$item." = document.createElement('input');
	        f_".$item.".setAttribute('name',nom_id);
	        f_".$item.".setAttribute('id',nom_id);
	        f_".$item.".setAttribute('type','text');
	        f_".$item.".className='$class';
	        f_".$item.".setAttribute('value','');
			f_".$item.".setAttribute('completion','".$item."');
	        
			id = 'f_".$item."_code'+suffixe
			f_".$item."_code = document.createElement('input');
			f_".$item."_code.setAttribute('name',id);
	        f_".$item."_code.setAttribute('id',id);
	        f_".$item."_code.setAttribute('type','hidden');
			f_".$item."_code.setAttribute('value','');
	 
	        del_f_".$item." = document.createElement('input');
	        del_f_".$item.".setAttribute('id','del_f_".$item."'+suffixe);
	        del_f_".$item.".onclick=fonction_raz_".$item.";
	        del_f_".$item.".setAttribute('type','button');
	        del_f_".$item.".className='bouton';
	        del_f_".$item.".setAttribute('readonly','');
	        del_f_".$item.".setAttribute('value','".$msg["raz"]."');
	
	        sel_f_".$item." = document.createElement('input');
	        sel_f_".$item.".setAttribute('id','sel_f_".$item."'+suffixe);
	        sel_f_".$item.".setAttribute('type','button');
	        sel_f_".$item.".className='bouton';
	        sel_f_".$item.".setAttribute('readonly','');
	        sel_f_".$item.".setAttribute('value','".$msg["parcourir"]."');
	        sel_f_".$item.".onclick=fonction_selecteur_".$item.";
	
	        ".$item.".appendChild(f_".$item.");
			".$item.".appendChild(f_".$item."_code);
	        space=document.createTextNode(' ');
	        ".$item.".appendChild(space);
	        ".$item.".appendChild(del_f_".$item.");
	        ".$item.".appendChild(space.cloneNode(false));
	        if('$what_sel')".$item.".appendChild(sel_f_".$item.");
	        
	        template.appendChild(".$item.");
	
	        document.".$form_name.".max_".$item.".value=suffixe*1+1*1 ;
	        ajax_pack_element(f_".$item.");
	    }
		</script>";
		
		//template de zone de texte pour chaque valeur				
		$aff="
		<div class='row'>
		<input type='text' class='$class' id='f_".$item."!!num!!' name='f_".$item."!!num!!' value=\"!!label_element!!\" autfield='f_".$item."_code!!num!!' completion=\"".$item."\" />
		<input type='hidden' id='f_".$item."_code!!num!!' name='f_".$item."_code!!num!!' value='!!id_element!!'>
		<input type='button' class='bouton' value='".$msg["raz"]."' onclick=\"this.form.f_".$item."!!num!!.value='';this.form.f_".$item."_code!!num!!.value=''; \" />
		!!bouton_parcourir!!
		!!bouton_ajouter!!
		</div>\n";
		if($what_sel)$bouton_parcourir="<input type='button' class='bouton' value='".$msg["parcourir"]."' onclick=\"openPopUp(".$link.")\" />";
		else $bouton_parcourir="";
		$aff= str_replace('!!bouton_parcourir!!', $bouton_parcourir, $aff);	

		$template=$script_js."<div id=add".$item."' class='row'>";
		$template.="<div class='row'><label for='f_".$item."' class='etiquette'>".$label."</label></div>";
		$num=0;
		if(!$values[0])$values[0]="";
		foreach($values as $value) {
			
			$label_element=$value["label"];
			$id_element=$value["id"];
			
			$temp= str_replace('!!id_element!!', $id_element, $aff);	
			$temp= str_replace('!!label_element!!', $label_element, $temp);	
			$temp= str_replace('!!num!!', $num, $temp);	
			
			if(!$num) $temp= str_replace('!!bouton_ajouter!!', " <input class='bouton' value='".$msg["req_bt_add_line"]."' onclick='add_".$item."();' type='button'>", $temp);	
			else $temp= str_replace('!!bouton_ajouter!!', "", $temp);	
			$template.=$temp;			
			$num++;
		}	
		$template.="<input type='hidden' name='max_".$item."' value='$num'>";			
		
		$template.="</div><div id='add".$item."'/>
		</div>";
		return $template;		
	}	
	// ---------------------------------------------------------------
	//		show_form : affichage du formulaire de saisie
	// ---------------------------------------------------------------
	function show_form() {
	
		global $msg;
		global $titre_uniforme_form;
		global $charset;
		global $user_input, $nbr_lignes, $page ;
		
		if($this->id) {
			$action = "./autorites.php?categ=titres_uniformes&sub=update&id=$this->id";
			$libelle = $msg["aut_titre_uniforme_modifier"];
			$button_remplace = "<input type='button' class='bouton' value='$msg[158]' ";
			$button_remplace .= "onclick='unload_off();document.location=\"./autorites.php?categ=titres_uniformes&sub=replace&id=$this->id\"'>";
			
			$button_voir = "<input type='button' class='bouton' value='$msg[voir_notices_assoc]' ";
			$button_voir .= "onclick='unload_off();document.location=\"./catalog.php?categ=search&mode=9&etat=aut_search&aut_type=titre_uniforme&aut_id=$this->id\"'>";
			
			$button_delete = "<input type='button' class='bouton' value='$msg[63]' ";
			$button_delete .= "onClick=\"confirm_delete();\">";
			
		} else {
			$action = './autorites.php?categ=titres_uniformes&sub=update&id=';
			$libelle = $msg["aut_titre_uniforme_ajouter"];
			$button_remplace = '';
			$button_delete ='';
		}
		$aut_link= new aut_link(AUT_TABLE_TITRES_UNIFORMES,$this->id);
		$titre_uniforme_form = str_replace('<!-- aut_link -->', $aut_link->get_form('saisie_titre_uniforme') , $titre_uniforme_form);
		
		$titre_uniforme_form = str_replace('!!id!!',				$this->id,		$titre_uniforme_form);
		$titre_uniforme_form = str_replace('!!action!!',			$action,		$titre_uniforme_form);
		$titre_uniforme_form = str_replace('!!libelle!!',			$libelle,		$titre_uniforme_form);
		
		$titre_uniforme_form = str_replace('!!nom!!',				htmlentities($this->name,ENT_QUOTES, $charset), $titre_uniforme_form);
				
		$distribution_form=$this->gen_input_selection($msg["aut_titre_uniforme_form_distribution"],"saisie_titre_uniforme","distrib",$this->distrib,"","saisie-80em");
		$titre_uniforme_form = str_replace("<!--	Distribution instrumentale et vocale (pour la musique)	-->",$distribution_form, $titre_uniforme_form);

		$ref_num_form=$this->gen_input_selection($msg["aut_titre_uniforme_form_ref_numerique"],"saisie_titre_uniforme","ref",$this->ref,"","saisie-80em");
		$titre_uniforme_form = str_replace("<!--	Référence numérique (pour la musique)	-->",$ref_num_form, $titre_uniforme_form);
		
		$titre_uniforme_form = str_replace('!!tonalite!!',			htmlentities($this->tonalite,ENT_QUOTES, $charset),	$titre_uniforme_form);				
		$titre_uniforme_form = str_replace('!!comment!!',			htmlentities($this->comment,ENT_QUOTES, $charset),	$titre_uniforme_form);

		$sub_form=$this->gen_input_selection($msg["aut_titre_uniforme_form_subdivision_forme"],"saisie_titre_uniforme","subdiv",$this->subdiv,"","saisie-80em");
		$titre_uniforme_form = str_replace('<!-- Subdivision de forme -->',	$sub_form, $titre_uniforme_form);
		
		$titre_uniforme_form = str_replace('!!remplace!!',			$button_remplace,	$titre_uniforme_form);
		$titre_uniforme_form = str_replace('!!voir_notices!!',		$button_voir,		$titre_uniforme_form);
		$titre_uniforme_form = str_replace('!!delete!!',			$button_delete,		$titre_uniforme_form);
			
		$titre_uniforme_form = str_replace('!!user_input_url!!',	rawurlencode(stripslashes($user_input)),							$titre_uniforme_form);
		$titre_uniforme_form = str_replace('!!user_input!!',		htmlentities($user_input,ENT_QUOTES, $charset),						$titre_uniforme_form);
		$titre_uniforme_form = str_replace('!!nbr_lignes!!',		$nbr_lignes,														$titre_uniforme_form);
		$titre_uniforme_form = str_replace('!!page!!',				$page,																$titre_uniforme_form);
		print $titre_uniforme_form;
	}
	
	// ---------------------------------------------------------------
	//		replace_form : affichage du formulaire de remplacement
	// ---------------------------------------------------------------
	function replace_form() {
		global $titre_uniforme_replace;
		global $msg;
		global $include_path;
	
		if(!$this->id || !$this->name) {
			require_once("$include_path/user_error.inc.php");
			error_message($msg[161], $msg[162], 1, './autorites.php?categ=titres_uniformes&sub=&id=');
			return false;
		}	
		$titre_uniforme_replace=str_replace('!!old_titre_uniforme_libelle!!', $this->display, $titre_uniforme_replace);
		$titre_uniforme_replace=str_replace('!!id!!', $this->id, $titre_uniforme_replace);
		print $titre_uniforme_replace;
		return true;
	}
	
	
	// ---------------------------------------------------------------
	//		delete() : suppression 
	// ---------------------------------------------------------------
	function delete() {
		global $dbh;
		global $msg;
		
		if(!$this->id)	// impossible d'accéder à cette notice auteur
			return $msg[403]; 
	
		// effacement dans les notices
		// récupération du nombre de notices affectées
		$requete = "SELECT count(1) FROM notices_titres_uniformes WHERE ntu_num_tu='$this->id' ";
	
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
		if($nbr_lignes) {
			// Cet auteur est utilisé dans des notices, impossible de le supprimer
			return '<strong>'.$this->display."</strong><br />${msg['titre_uniforme_delete']}";
		}
	
		// effacement dans la table des titres_uniformes
		$requete = "DELETE FROM titres_uniformes WHERE tu_id='$this->id' ";
		mysql_query($requete, $dbh);
		// delete les champs répétables
		$requete = "DELETE FROM tu_distrib WHERE distrib_num_tu='$this->id' ";
		mysql_query($requete, $dbh);
		$requete = "DELETE FROM tu_ref WHERE ref_num_tu='$this->id' ";
		mysql_query($requete, $dbh);
		$requete = "DELETE FROM tu_subdiv WHERE subdiv_num_tu='$this->id' ";
		mysql_query($requete, $dbh);
		// liens entre autorités
		$aut_link= new aut_link(AUT_TABLE_TITRES_UNIFORMES,$this->id);
		$aut_link->delete();
		return false;
	}
	
	
	// ---------------------------------------------------------------
	//		replace($by) : remplacement 
	// ---------------------------------------------------------------
	function replace($by) {
	
		global $msg;
		global $dbh;
	
		if (($this->id == $by) || (!$this->id))  {
			return $msg[223];
		}
		
		// remplacement dans les responsabilités
		$requete = "UPDATE notices_titres_uniformes SET ntu_num_tu='$by' WHERE ntu_num_tu='$this->id' ";
		@mysql_query($requete, $dbh);
				
		// effacement dans la table des titres_uniformes
		$requete = "DELETE FROM titres_uniformes WHERE tu_id='$this->id' ";
		mysql_query($requete, $dbh);
		// delete les champs répétables
		$requete = "DELETE FROM tu_distrib WHERE distrib_num_tu='$this->id' ";
		mysql_query($requete, $dbh);
		$requete = "DELETE FROM tu_ref WHERE ref_num_tu='$this->id' ";
		mysql_query($requete, $dbh);
		$requete = "DELETE FROM tu_subdiv WHERE subdiv_num_tu='$this->id' ";
		mysql_query($requete, $dbh);
		
		titre_uniforme::update_index($by);
		
		return FALSE;
	}
	
	// ---------------------------------------------------------------
	//		update($value) : mise à jour 
	// ---------------------------------------------------------------
	function update($value) {
	
		global $dbh;
		global $msg;
		global $include_path;
		
		if(!$value['name'])	return false;
	
		// nettoyage des chaînes en entrée		
		$value['name'] = clean_string($value['name']);
		$value['tonalite'] = clean_string($value['tonalite']);
		$value['comment'] = clean_string($value['comment']);
	
		// s'assurer que ce titre uniforme n'existe pas déjà
	/*	$dummy = "SELECT * FROM titres_uniformes WHERE tu_name='".$value['name']."' and tu_id!='".$this->id."' ";
		$check = mysql_query($dummy, $dbh);
		if (mysql_num_rows($check)) {
			require_once("$include_path/user_error.inc.php");
			warning($msg["aut_titre_uniforme_creation"], $msg["aut_titre_uniforme_doublon_erreur"]);
			return FALSE;
		}*/
		
		$titre=titre_uniforme::import_tu_exist($value,1,$this->id);
		if($titre){
			require_once("$include_path/user_error.inc.php");
			warning($msg["aut_titre_uniforme_creation"], $msg["aut_titre_uniforme_doublon_erreur"]);
			return FALSE;
		}
		
/*
 * 
 	CREATE TABLE titres_uniformes (
		tu_id INT( 9 ) unsigned NOT NULL AUTO_INCREMENT,
		tu_name VARCHAR( 255 ) DEFAULT '' NOT NULL ,		
		tu_tonalite VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		tu_comment TEXT NOT NULL ,
		index_tu TEXT NOT NULL ,
		PRIMARY KEY ( tu_id )
	) 

 	CREATE TABLE tu_distrib (
		distrib_num_tu INT( 9 ) unsigned NOT NULL default 0,
		distrib_name VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		distrib_ordre smallint(5) unsigned NOT NULL default 0		
	) 
 	CREATE TABLE tu_ref (
		ref_num_tu INT( 9 ) unsigned NOT NULL default 0,
		ref_name VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		ref_ordre smallint(5) unsigned NOT NULL default 0	
	)
 	CREATE TABLE tu_subdiv (
		subdiv_num_tu INT( 9 ) unsigned NOT NULL default 0,
		subdiv_name VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		subdiv_ordre smallint(5) unsigned NOT NULL default 0	
	)

	CREATE TABLE notices_titres_uniformes (
		ntu_num_notice INT( 9 ) unsigned NOT NULL default 0,
		ntu_num_tu INT( 9 ) unsigned NOT NULL default 0,		
		ntu_titre VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		ntu_date VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		ntu_sous_vedette VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		ntu_langue VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		ntu_version VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		ntu_mention VARCHAR( 255 ) DEFAULT '' NOT NULL ,
		ntu_ordre smallint(5) unsigned NOT NULL default 0,
		PRIMARY KEY  (ntu_num_notice, ntu_num_tu)
	)
  
 * 
 */
		$requete  = "SET ";
		$requete .= "tu_name='".$value["name"]."', ";
		$requete .= "tu_tonalite='".$value["tonalite"]."', ";		
		$requete .= "tu_comment='".$value["comment"]."', ";
		
		$index.= $value["name"]." ".$value["tonalite"]." ";
		for($i=0;$i<count($value['distrib']);$i++) {	
			$index.= $value['distrib'][$i]." ";		
		}
		// Référence numérique (pour la musique)
		for($i=0;$i<count($value['ref']);$i++) {	
			$index.= $value['ref'][$i]." ";		
		}		
		// Référence numérique (pour la musique)
		for($i=0;$i<count($value['subdiv']);$i++) {		
			$index.= $value['subdiv'][$i]." ";				
		}			
		$requete .= "index_tu=' ".strip_empty_chars($index)." '";
		
		if($this->id) {
			// update
			$requete = 'UPDATE titres_uniformes '.$requete;
			$requete .= ' WHERE tu_id='.$this->id.' ;';
			if(mysql_query($requete, $dbh)) {
				titre_uniforme::update_index($this->id);				
			} else {
				require_once("$include_path/user_error.inc.php"); 
				warning($msg["aut_titre_uniforme_creation"], $msg["aut_titre_uniforme_modif_erreur"]);
				return FALSE;
			}
		} else {
			// creation
			$requete = 'INSERT INTO titres_uniformes '.$requete.' ';
			
			if(mysql_query($requete, $dbh)) {
				$this->id=mysql_insert_id();				
			} else {
				require_once("$include_path/user_error.inc.php"); 
				warning($msg["aut_titre_uniforme_creation"], $msg["aut_titre_uniforme_creation_erreur"]);
				return FALSE;
			}
		}
		$aut_link= new aut_link(AUT_TABLE_TITRES_UNIFORMES,$this->id);
		$aut_link->save_form();
		
		// Gestion des champ répétables
		$requete = "DELETE FROM tu_distrib WHERE distrib_num_tu='$this->id' ";
		mysql_query($requete, $dbh);
		$requete = "DELETE FROM tu_ref WHERE ref_num_tu='$this->id' ";
		mysql_query($requete, $dbh);
		$requete = "DELETE FROM tu_subdiv WHERE subdiv_num_tu='$this->id' ";
		mysql_query($requete, $dbh);
		
		// Distribution instrumentale et vocale (pour la musique)		
		for($i=0;$i<count($value['distrib']);$i++) {	
			$requete = "INSERT INTO tu_distrib SET 
			distrib_num_tu='$this->id', 
			distrib_name='".$value['distrib'][$i]."', 
			distrib_ordre='$i' ";
			mysql_query($requete, $dbh);		
		}
		// Référence numérique (pour la musique)
		for($i=0;$i<count($value['ref']);$i++) {	
			$requete = "INSERT INTO tu_ref SET 
			ref_num_tu='$this->id', 
			ref_name='".$value['ref'][$i]."', 
			ref_ordre='$i' ";
			mysql_query($requete, $dbh);		
		}		
		// Référence numérique (pour la musique)
		for($i=0;$i<count($value['subdiv']);$i++) {		
			$requete = "INSERT INTO tu_subdiv SET 
			subdiv_num_tu='$this->id', 
			subdiv_name='".$value['subdiv'][$i]."', 
			subdiv_ordre='$i' ";
			mysql_query($requete, $dbh);						
		}		
		return TRUE;
	}
		
	// ---------------------------------------------------------------
	//		import() : import d'un titre_uniforme
	// ---------------------------------------------------------------
	// fonction d'import de notice titre_uniforme 
	function import($value,$from_form=0) {
		global $dbh;
		// Si vide on sort
		if(trim($value['name']) == '') return FALSE;
		if(!$from_form) {
			$value['name'] = addslashes($value['name']);
			$value['tonalite'] = addslashes($value['tonalite']);
			$value['comment'] = addslashes($value['comment']);
			for($i=0;$i<count($value['distrib']);$i++) {	
				$value['distrib'][$i]= addslashes($value['distrib'][$i]);		
			}
			for($i=0;$i<count($value['ref']);$i++) {	
				$value['ref'][$i]= addslashes($value['ref'][$i]);		
			}
			for($i=0;$i<count($value['subdiv']);$i++) {	
				$value['subdiv'][$i]= addslashes($value['subdiv'][$i]);		
			}
		}		
			
		// s'assurer que ce titre uniforme n'existe pas déjà
		/*$dummy = "SELECT * FROM titres_uniformes WHERE tu_name='".$value['name']."' ";
		$check = mysql_query($dummy, $dbh);
		if (mysql_num_rows($check)) {
			$tu=mysql_fetch_object($check);
			$tu_id=$tu->tu_id;
			return $tu->tu_id;
		}*/
		
		$titre=titre_uniforme::import_tu_exist($value,$from_form);
		if($titre){
			return $titre;
		}
			
		$requete  = "INSERT INTO titres_uniformes SET ";
		$requete .= "tu_name='".$value["name"]."', ";
		$requete .= "tu_tonalite='".$value["tonalite"]."', ";		
		$requete .= "tu_comment='".$value["comment"]."', ";

		// Calcul des index
		$index.= $value["name"]." ".$value["tonalite"]." ";
		for($i=0;$i<count($value['distrib']);$i++) {	
			$index.= $value['distrib'][$i]." ";		
		}
		for($i=0;$i<count($value['ref']);$i++) {	
			$index.= $value['ref'][$i]." ";		
		}		
		$requete .= "index_tu=' ".strip_empty_chars($index)." '";
		
		// insertion du titre uniforme		
		if(mysql_query($requete, $dbh)) {
			$tu_id=mysql_insert_id();				
		} else {
			return FALSE;
		}		
		
		// Distribution instrumentale et vocale (pour la musique)		
		for($i=0;$i<count($value['distrib']);$i++) {	
			$requete = "INSERT INTO tu_distrib SET 
			distrib_num_tu='$tu_id', 
			distrib_name='".$value['distrib'][$i]."', 
			distrib_ordre='$i' ";
			mysql_query($requete, $dbh);		
		}
		// Référence numérique (pour la musique)
		for($i=0;$i<count($value['ref']);$i++) {	
			$requete = "INSERT INTO tu_ref SET 
			ref_num_tu='$tu_id', 
			ref_name='".$value['ref'][$i]."', 
			ref_ordre='$i' ";
			mysql_query($requete, $dbh);		
		}		
		// Référence numérique (pour la musique)
		for($i=0;$i<count($value['subdiv']);$i++) {		
			$requete = "INSERT INTO tu_subdiv SET 
			subdiv_num_tu='$tu_id', 
			subdiv_name='".$value['subdiv'][$i]."', 
			subdiv_ordre='$i' ";
			mysql_query($requete, $dbh);						
		}	
		return 	$tu_id;		
	}
	
	// ---------------------------------------------------------------
	//		import_tu_exist() : Recherche si le titre uniforme existe déjà
	// ---------------------------------------------------------------
	function import_tu_exist($value,$from_form=0,$tu_id=0) {
		global $dbh;
		// Si vide on sort
		if(trim($value['name']) == '') return FALSE;
		if(!$from_form) {
			$value['name'] = addslashes($value['name']);
			$value['tonalite'] = addslashes($value['tonalite']);
			
			for($i=0;$i<count($value['distrib']);$i++) {	
				$value['distrib'][$i]= addslashes($value['distrib'][$i]);		
			}
			for($i=0;$i<count($value['ref']);$i++) {	
				$value['ref'][$i]= addslashes($value['ref'][$i]);		
			}
		}	
		$dummy = "SELECT * FROM titres_uniformes WHERE tu_name='".$value['name']."' and tu_tonalite='".$value['tonalite']."' ";
		if($tu_id) $dummy = $dummy."and tu_id!='".$tu_id."'"; // Pour la création ou la mise à jour par l'interace 
		$check = mysql_query($dummy, $dbh);
		if (mysql_num_rows($check)) {
			
			while($row = mysql_fetch_object($check)){
				$tu_id=$row->tu_id;
				$différent=false;
				//Test si les titres de même nom ont aussi la (ou les) même distribution
				if(count($value['distrib']) == 0){ //Si le titre que je veux ajouter n'a pas de distribution je regarde si celui qui exsite en a une
					$requete = "select distrib_num_tu from tu_distrib where  
					distrib_num_tu='$tu_id' ";
					$test = mysql_query($requete, $dbh);
					if (mysql_num_rows($test)) {
						$différent = true; //Si il à une distribution le titre que je veux ajouter est différent
					}
					
				}else{
					//On test s'il y a autant de distribution
					$requete = "select distrib_num_tu from tu_distrib where distrib_num_tu='$tu_id' ";
					$nb=mysql_num_rows(mysql_query($requete, $dbh));
					if($nb != count($value['distrib'])){ //Si il y en a pas autant c'est un titre différent
						$différent = true;
					}else{ //Sinon on regarde si ce sont les mêmes
						$nb_occurence=array_count_values($value['distrib']);//avoir le nombre d'occurence de chaque terme
						for($i=0;$i<count($value['distrib']);$i++) {
							$requete = "select count(distrib_num_tu) from tu_distrib where  
							distrib_num_tu='$tu_id' and 
							distrib_name='".$value['distrib'][$i]."' group by distrib_num_tu "; 
							$test = mysql_query($requete, $dbh);
							$nb=@mysql_result($test,0,0);
							if (!$nb) {
								$différent = true; //Si une des distributions n'existe pas c'est un titre uniforme différent
							}elseif($nb != $nb_occurence[$value['distrib'][$i]]){
								$différent = true; //Si le nombre de cette distribution est différent c'est un titre uniforme différent
							}
						}	
					}
				}
				//Test si les titres de même nom ont aussi la (ou les) même réference
				if(count($value['ref']) == 0){ //Si le titre que je veux ajouter n'a pas de réference je regarde si celui qui exsite en a une
					$requete = "select ref_num_tu from tu_ref where  
					ref_num_tu='$tu_id' ";
					$test = mysql_query($requete, $dbh);
					if (mysql_num_rows($test)) {
						$différent = true; //Si il à une réference le titre que je veux ajouter est différent
					}
					
				}else{
					//On test s'il y a autant de réference
					$requete = "select ref_num_tu from tu_ref where ref_num_tu='$tu_id' ";
					$nb=mysql_num_rows(mysql_query($requete, $dbh));
					if($nb != count($value['ref'])){ //Si il y en a pas autant c'est un titre différent
						$différent = true;
					}else{ //Sinon on regarde si ce sont les mêmes
						$nb_occurence=array_count_values($value['ref']);//avoir le nombre d'occurence de chaque terme
						for($i=0;$i<count($value['ref']);$i++) {
							$requete = "select count(ref_num_tu) from tu_ref where  
							ref_num_tu='$tu_id' and 
							ref_name='".$value['ref'][$i]."' group by ref_num_tu "; 
							$test = mysql_query($requete, $dbh);
							$nb=@mysql_result($test,0,0);
							if (!$nb) {
								$différent = true; //Si une des réference n'existe pas c'est un titre uniforme différent
							}elseif($nb != $nb_occurence[$value['ref'][$i]]){
								$différent = true; //Si le nombre de cette réference est différent c'est un titre uniforme différent
							}
						}	
					}
				}
				if($différent == false){ //Si le titre n'est pas différent on retourne l'id du titre identique
					return $tu_id;
				}	
			}
			return $tu->tu_id;
		}	
		return 0;
	}	
	// ---------------------------------------------------------------
	//		search_form() : affichage du form de recherche
	// ---------------------------------------------------------------
	function search_form() {
		global $user_query, $user_input;
		global $msg, $charset;
		
		$user_query = str_replace ('!!user_query_title!!', $msg[357]." : ".$msg["aut_menu_titre_uniforme"] , $user_query);
		$user_query = str_replace ('!!action!!', './autorites.php?categ=titres_uniformes&sub=reach&id=', $user_query);
		$user_query = str_replace ('!!add_auth_msg!!', $msg["aut_titre_uniforme_ajouter"] , $user_query);
		$user_query = str_replace ('!!add_auth_act!!', './autorites.php?categ=titres_uniformes&sub=titre_uniforme_form', $user_query);
		$user_query = str_replace ('<!-- lien_derniers -->', "<a href='./autorites.php?categ=titres_uniformes&sub=titre_uniforme_last'>".$msg["aut_titre_uniforme_derniers_crees"]."</a>", $user_query);
		$user_query = str_replace("!!user_input!!",htmlentities(stripslashes($user_input),ENT_QUOTES, $charset),$user_query);
			
		print pmb_bidi($user_query) ;
	}
	
	//---------------------------------------------------------------
	// update_index($id) : maj des n-uplets la table notice_global_index en rapport avec cet author	
	//---------------------------------------------------------------
	function update_index($id) {
		global $dbh;
		// On cherche tous les n-uplet de la table notice correspondant à ce titre_uniforme.
		$found = mysql_query("select ntu_num_notice from notices_titres_uniformes where ntu_num_tu = ".$id,$dbh);
		// Pour chaque n-uplet trouvés on met a jour la table notice_global_index avec l'auteur modifié :
		while(($mesNotices = mysql_fetch_object($found))) {
			$notice_id = $mesNotices->ntu_num_notice;
			notice::majNoticesGlobalIndex($notice_id);
			notice::majNoticesMotsGlobalIndex($notice_id); //TODO preciser le datatype avant d'appeller cette fonction
		}
	}
	
} // class auteur


