<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: tu_notice.class.php,v 1.5 2010-02-23 10:19:20 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/titre_uniforme.class.php");

class tu_notice {
	
	// ---------------------------------------------------------------
	//		propriétés de la classe
	// ---------------------------------------------------------------	
	var $id;		// MySQL id notice
	var $ntu_data;	//données des titres uniformes lié a la notice 
	var $ntu_form;
	
	// ---------------------------------------------------------------
	//		tu_notice($id) : constructeur
	// ---------------------------------------------------------------
	function tu_notice($id=0,$recursif=0) {
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

		$this->ntu_data=array();
		if($this->id) {				
			$requete = "SELECT * FROM notices_titres_uniformes WHERE ntu_num_notice=$this->id order by ntu_ordre";
		
			$result = mysql_query($requete, $dbh);
			$nb_result=0;
			if(mysql_num_rows($result)) {
				while(($res_tu = mysql_fetch_object($result))) {
					$this->ntu_data[$nb_result]->num_tu=	$res_tu->ntu_num_tu;
					$this->ntu_data[$nb_result]->titre=	$res_tu->ntu_titre;
					$this->ntu_data[$nb_result]->date=	$res_tu->ntu_date;
					$this->ntu_data[$nb_result]->sous_vedette=	$res_tu->ntu_sous_vedette;
					$this->ntu_data[$nb_result]->langue=	$res_tu->ntu_langue;
					$this->ntu_data[$nb_result]->version=	$res_tu->ntu_version;
					$this->ntu_data[$nb_result]->mention=	$res_tu->ntu_mention;  
					$this->ntu_data[$nb_result]->tu= new titre_uniforme($this->ntu_data[$nb_result]->num_tu);	
					/*  Champs récupérés du titre uniforme:
					 	name 			
						tonalite
						comment
						distrib (array)
						ref (array)
						subdiv (array)
					*/
					$nb_result++;
				}				
			} else {
				// pas trouvé avec cette clé
						
				
			}
		}
	}

	function get_print_type($type=0) {
		global $msg;
		switch($type) {
			
			default:
				if(!$this->ntu_data) return'';
				$display="<b>".$msg["catal_onglet_titre_uniforme"]."</b>&nbsp;:";
				foreach ($this->ntu_data as $tu) {
					$link="<a href='./autorites.php?categ=titres_uniformes&sub=titre_uniforme_form&id=".$tu->num_tu."' class='lien_gestion'>
					".$tu->tu->name."
					</a>";
					$biblio_fields=array(); 
					if($tu->titre)$biblio_fields[]=$tu->titre;	
					if($tu->date)$biblio_fields[]=$tu->date;	
					if($tu->sous_vedette)$biblio_fields[]=$tu->sous_vedette;			
					if($tu->langue)$biblio_fields[]=$tu->langue;			
					if($tu->version)$biblio_fields[]=$tu->version;			
					if($tu->mention)$biblio_fields[]=$tu->mention;			
					$biblio_print=implode("; ",$biblio_fields);		
					if($biblio_print)	$biblio_print=": ".$biblio_print;
					$display.=" ".$link.$biblio_print."<br />";
				}
			break;	
		}
		return $display;
		
	}
	
	function get_form($form_name) {
		global $msg;

		$i=0;
		do {	
			$values[$i]["id"]= $this->ntu_data[$i]->num_tu;
			$values[$i]["label"]= $this->ntu_data[$i]->tu->name;
			$j=0;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_titre_section"];
			$values[$i]["objets"][$j]["name"]="ntu_titre";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$this->ntu_data[$i]->titre;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_date"];
			$values[$i]["objets"][$j]["name"]="ntu_date";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$this->ntu_data[$i]->date;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_sous_vedette"];
			$values[$i]["objets"][$j]["name"]="ntu_sous_vedette";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$this->ntu_data[$i]->sous_vedette;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_langue"];
			$values[$i]["objets"][$j]["name"]="ntu_langue";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$this->ntu_data[$i]->langue;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_version"];
			$values[$i]["objets"][$j]["name"]="ntu_version";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$this->ntu_data[$i]->version;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_mention"];
			$values[$i]["objets"][$j]["name"]="ntu_mention";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$this->ntu_data[$i]->mention;
		} while	(++$i<count($this->ntu_data));
		$this->ntu_form=$this->gen_input_selection($msg["catal_onglet_titre_uniforme"],$form_name,"titre_uniforme",$values,"titre_uniforme","saisie-80emr");
		return $this->ntu_form;
	}
		
	function get_form_import($form_name,$ntu_data) {
		global $msg;

		$i=0;
		do {	
			$values[$i]["id"]= $ntu_data[$i]->num_tu;
			$values[$i]["label"]= $ntu_data[$i]->tu->name;
			$j=0;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_titre_section"];
			$values[$i]["objets"][$j]["name"]="ntu_titre";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$ntu_data[$i]->titre;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_date"];
			$values[$i]["objets"][$j]["name"]="ntu_date";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$ntu_data[$i]->date;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_sous_vedette"];
			$values[$i]["objets"][$j]["name"]="ntu_sous_vedette";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$ntu_data[$i]->sous_vedette;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_langue"];
			$values[$i]["objets"][$j]["name"]="ntu_langue";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$ntu_data[$i]->langue;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_version"];
			$values[$i]["objets"][$j]["name"]="ntu_version";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$ntu_data[$i]->version;
			$j++;
			$values[$i]["objets"][$j]["label"]=$msg["catal_titre_uniforme_mention"];
			$values[$i]["objets"][$j]["name"]="ntu_mention";
			$values[$i]["objets"][$j]["class"]="saisie-80em";
			$values[$i]["objets"][$j]["value"]=$ntu_data[$i]->mention;
		} while	(++$i<count($ntu_data));
		$ntu_form=tu_notice::gen_input_selection($msg["catal_onglet_titre_uniforme"],$form_name,"titre_uniforme",$values,"titre_uniforme","saisie-80emr");
		return $ntu_form;
	}	
	
	function gen_input_selection($label,$form_name,$item,$values,$what_sel,$class='saisie-80em' ) {  
	
		global $msg;
		$select_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";
		$link="'./select.php?what=$what_sel&caller=$form_name&param1=f_".$item."_code!!num!!&param2=f_".$item."!!num!!&deb_rech='+escape(this.form.f_".$item."!!num!!.value), '$what_sel', 400, 400, -2, -2, '$select_prop'";
		$size_item=strlen($item)+2;
				
		$script_js="
		<script>
		var memo_id='';
		function fonction_selecteur_".$item."() {
			var nom='f_".$item."';
			if(memo_id) name=memo_id.substring(4);  
			else name=this.getAttribute('id').substring(4);
			memo_id='';	    
			name_id = name.substr(0,nom.length)+'_code'+name.substr(nom.length);
			openPopUp('./select.php?what=$what_sel&caller=$form_name&param1='+name_id+'&param2='+name, '$what_sel', 400, 400, -2, -2, '$select_prop');	        
	    }
	    function fonction_raz_".$item."() {
	        name=this.getAttribute('id').substring(4);
			name_id = name.substr(0,$size_item)+'_code'+name.substr($size_item);
	        document.getElementById(name).value='';
			document.getElementById(name_id).value='';
	    }
	    function add_".$item."() {
	        template = document.getElementById('add".$item."');
	        suffixe=document.getElementById('max_".$item."').value;
	  
	        ".$item."=document.createElement('div');
	        ".$item.".className='parent';
	        ".$item.".setAttribute('id','tu'+suffixe);
	      	".$item.".style.display='block';
	      		      	
	      	img_".$item."= document.createElement('img');
			img_".$item.".setAttribute('src','./images/plus.gif');  
			img_".$item.".setAttribute('class','img_plus');
			img_".$item.".setAttribute('name','imEx');
			img_".$item.".setAttribute('id','tu'+suffixe+'Img');
			img_".$item.".setAttribute('onclick',\"expandBase(this.id.substring(0,this.id.length - 3), true); return false;\");
			img_".$item.".setAttribute('border','0');	
	        
	        nom_id = 'f_".$item."'+suffixe;
	        f_".$item." = document.createElement('input');
	        f_".$item.".setAttribute('name',nom_id);
	        f_".$item.".setAttribute('id',nom_id);
	        f_".$item.".setAttribute('type','text');
	        f_".$item.".className='$class';
	        f_".$item.".setAttribute('value','');
			f_".$item.".setAttribute('completion','".$item."');
	        
			id = 'f_".$item."_code'+suffixe;
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
	
	        ".$item.".appendChild(img_".$item.");
	        space=document.createTextNode(' ');
	        ".$item.".appendChild(space);
	        ".$item.".appendChild(f_".$item.");
			".$item.".appendChild(f_".$item."_code);
	        space=document.createTextNode(' ');
	        ".$item.".appendChild(space);
	        ".$item.".appendChild(del_f_".$item.");
	        ".$item.".appendChild(space.cloneNode(false));
	        if('$what_sel')".$item.".appendChild(sel_f_".$item.");	        
	        template.appendChild(".$item.");
	        
	        child_".$item."= document.createElement('div');
	        child_".$item.".className='child';
	        child_".$item.".setAttribute('id','tu'+suffixe+'Child');
	        child_".$item.".setAttribute('etirable','yes');
	        child_".$item.".setAttribute('invert','');
	        child_".$item.".setAttribute('hide','');
	      	child_".$item.".style.display='none';
	      	template.appendChild(child_".$item.");
			//!!add_option!!	
			document.getElementById('max_".$item."').value=(suffixe*1)+(1*1) ;
	        ajax_pack_element(f_".$item.");	        
	    }
		</script>";
		$script_js_option="
			div_label_!!num!!=document.createElement('div');
			div_label_!!num!!.className='row';
			label_!!num!!=document.createElement('label');
			texte_!!num!!=document.createTextNode('!!label!!');
  			label_!!num!!.appendChild(texte_!!num!!);
			div_label_!!num!!.appendChild(label_!!num!!);
					
	        div_!!num!!=document.createElement('div');
	        div_!!num!!.className='row';
	        op_!!num!! = document.createElement('input');
	        op_!!num!!.setAttribute('name','!!name!!'+suffixe);
	        op_!!num!!.setAttribute('id','!!name!!'+suffixe);
	        op_!!num!!.setAttribute('type','text');
	        op_!!num!!.className='!!class!!';
	        op_!!num!!.setAttribute('value','');
	        div_!!num!!.appendChild(op_!!num!!);
	        
	    	child_".$item.".appendChild(div_label_!!num!!);
	    	child_".$item.".appendChild(div_!!num!!);
	    ";
		
		//template de zone de texte pour chaque valeur				
		$aff="		
		<div style='display: block;' id='tu!!num!!Parent' class='parent'>
			<img src='./images/plus.gif' class='img_plus' name='imEx' id='tu!!num!!Img' title='Zone des notes' onclick=\"expandBase('tu!!num!!', true); return false;\" border='0'>
			<input type='text' class='$class' id='f_".$item."!!num!!' name='f_".$item."!!num!!' value='!!label_element!!' autfield='f_".$item."_code!!num!!' completion=\"".$item."\" />
			<input type='hidden' id='f_".$item."_code!!num!!' name='f_".$item."_code!!num!!' value='!!id_element!!'>
			<input type='button' class='bouton' value='".$msg["raz"]."' onclick=\"this.form.f_".$item."!!num!!.value='';this.form.f_".$item."_code!!num!!.value=''; \" />
			!!bouton_parcourir!!
			!!bouton_ajouter!!
		</div>		
		<div hide='' style='display: none;' invert='' id='tu!!num!!Child' class='child' title=''>	
		\n";
			
		$aff_option="
		<div class='row'>
			<label for='!!name!!!!num!!' class='etiquette'>!!label!!</label></div>
		<div class='row'>
			<input type='text' class='!!class!!' id='!!name!!!!num!!' name='!!name!!!!num!!' value=\"!!value!!\" />
		</div>";	

		if($what_sel)$bouton_parcourir="<input type='button' id='sel_f_".$item."!!num!!' class='bouton' value='".$msg["parcourir"]."' onclick=\"memo_id=this.getAttribute('id');fonction_selecteur_".$item."();\" />";
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
			// option
			foreach($value["objets"] as $objet) {
				
				$option = str_replace('!!label!!', $objet["label"], $aff_option);		
				$option = str_replace('!!name!!', $objet["name"], $option);		
				$option = str_replace('!!class!!', $objet["class"], $option);		
				$option = str_replace('!!num!!', $num, $option);		
				$option = str_replace('!!value!!', $objet["value"], $option);	
				$template.=$option;	
			}
			$template.="</div>";				
			if(!$num) {				
				$j=0;
				foreach($value["objets"] as $objet) {
					// Ajout des javascript qui permet la répétabilité des champs option 			
					$option_js = str_replace('!!label!!', addslashes($objet["label"]), $script_js_option);		
					$option_js = str_replace('!!name!!', $objet["name"], $option_js);		
					$option_js = str_replace('!!class!!', $objet["class"], $option_js);		
					$option_js = str_replace('!!num!!', $j, $option_js);		
					$option_js = str_replace('!!value!!', $objet["value"], $option_js);
					$script_option_js.=$option_js; 
					$j++;	
				}				
				$template=str_replace('!!add_option!!',$script_option_js, $template);		
			}	
			$num++;
		}	
		$template.="<input type='hidden' name='max_".$item."' id='max_".$item."' value='$num'>";					
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
			$button_voir .= "onclick='unload_off();document.location=\"./catalog.php?categ=search&mode=0&etat=tu_search&tu_id=$this->id\"'>";
			
			$button_delete = "<input type='button' class='bouton' value='$msg[63]' ";
			$button_delete .= "onClick=\"confirm_delete();\">";
			
		} else {
			$action = './autorites.php?categ=titres_uniformes&sub=update&id=';
			$libelle = $msg["aut_titre_uniforme_ajouter"];
			$button_remplace = '';
			$button_delete ='';
		}
				
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
		
		if(!$this->id) return false;
		$requete = "DELETE FROM notices_titres_uniformes WHERE ntu_num_notice='$this->id' ";
		mysql_query($requete, $dbh);
		$this->id=0;
		$this->ntu_data=array();
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
		

//		titre_uniforme::update_index($by);
		
		return FALSE;
	}
	
	// ---------------------------------------------------------------
	//		update($value) : mise à jour 
	// ---------------------------------------------------------------
	function update($values) {
	
		global $dbh;
		global $msg;
		global $include_path;

		if(!$this->id) return false;
		$requete = "DELETE FROM notices_titres_uniformes WHERE ntu_num_notice=".$this->id;
		mysql_query($requete, $dbh);
		// nettoyage des chaînes en entrée		
		$ordre=0;
		foreach($values as $value) {			
			if($value['num_tu']) {
				$requete = "INSERT INTO notices_titres_uniformes SET 
				ntu_num_notice='$this->id', 
				ntu_num_tu='".$value['num_tu']."', 
				ntu_titre='".clean_string($value['ntu_titre'])."', 
				ntu_date='".clean_string($value['ntu_date'])."', 
				ntu_sous_vedette='".clean_string($value['ntu_sous_vedette'])."', 
				ntu_langue='".clean_string($value['ntu_langue'])."', 
				ntu_version='".clean_string($value['ntu_version'])."', 
				ntu_mention='".clean_string($value['ntu_mention'])."',
				ntu_ordre=$ordre 				
				";
				mysql_query($requete, $dbh);
			}
			$ordre++;
		}
		return TRUE;
	}
		
	// ---------------------------------------------------------------
	//		import() : import d'un titre_uniforme
	// ---------------------------------------------------------------
	// fonction d'import de notice titre_uniforme 
	function import($data) {
	// To do
	
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
			notice::majNoticesMotsGlobalIndex($notice_id);
		}
	}
	
} // class auteur


