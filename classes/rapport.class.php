<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rapport.class.php,v 1.10 2010-03-04 15:35:21 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/templates/rapport.tpl.php");

class rapport {
	
	var $export_elements = array();
	var $rapport_elements = array();
	var $titre_gauche = "";
	var $cancel_action = "";
	var $form_action = "";
	var $rapport_xml = "";
	var $intro = "";
	
	function rapport(){				
	}
	
	function showRapport(){
		global $form_rapport, $charset, $base_path, $tab_traite;
		
		$tab_traite = array();
		$liste="";
		if($this->export_elements){			
			
			 $liste = "";
			 for($i=0;$i<count($this->export_elements);$i++){
				$id=$this->export_elements[$i]['id'];
				$titre = htmlentities($this->export_elements[$i]['titre'],ENT_QUOTES,$charset);
				$contenu= htmlentities($this->export_elements[$i]['contenu'],ENT_QUOTES,$charset);
				$date = htmlentities($this->export_elements[$i]['date'],ENT_QUOTES,$charset);
				$sujet = htmlentities($this->export_elements[$i]['sujet'],ENT_QUOTES,$charset);
				$id_parent = $this->export_elements[$i]['id_parent'];
				
				$drag = "<span id=\"elt_handle_$id\" style='padding-left:7px'  ><img src=\"".$base_path."/images/notice_drag.png\" /></span>";
				$liste .= "<div id='elt_$id' idelt='$id' draggable=\"yes\" dragtype=\"export\" dragtext=\"$titre\" 
					dragicon=\"".$base_path."/images/icone_drag_notice.png\" handler='elt_handle_$id'>".gen_plus("elt_".$id,"[".formatdate($date)."] ".$sujet." : ".$titre.$drag,$contenu)."</div>";
			}
			
		}
		
		if($this->rapport_elements){
			$liste_rap = "";
			for($i=0;$i<count($this->rapport_elements);$i++){			
				$id=$this->rapport_elements[$i]['id_item'];
				$style="";
				
				if(!$this->rapport_elements[$i]['num_element']){
					//Ajout manuel
					switch ($this->rapport_elements[$i]['type']) {
						case '1':
							//Titre
							$style = "style='background-color:#DECDEC' titre='yes'";
							$contenu = htmlentities($this->rapport_elements[$i]['contenu'],ENT_QUOTES,$charset);
						break;
						case '0':
							//Commmentaire
							$contenu = "* ".htmlentities($this->rapport_elements[$i]['contenu'],ENT_QUOTES,$charset);
						break;
					}
				} else $contenu= htmlentities($this->rapport_elements[$i]['contenu'],ENT_QUOTES,$charset);
					
				$titre = htmlentities(substr($this->rapport_elements[$i]['contenu'],0,15)."...",ENT_QUOTES,$charset);
				$ordre = $this->rapport_elements[$i]['ordre'];
				
				if($this->rapport_elements[$i]['sujet'])
					$contenu =  "<u>".htmlentities($this->rapport_elements[$i]['sujet'],ENT_QUOTES,$charset)."</u> : ".$contenu;
				
				$drag = "<span id=\"rap_handle_$id\" style='padding-left:7px'  ><img src=\"".$base_path."/images/notice_drag.png\" /></span>";
				$del = "<span id=\"rap_del_$id\" style='padding-left:7px;' onclick='delete_item($id);' ><img src=\"".$base_path."/images/cross.png\" style='cursor:pointer;width:10px;vertical-align:middle;'/></span>";
				$modif = "<span id=\"rap_modif_$id\" style='padding-left:7px;' onclick='modif_item($id);' ><img src=\"".$base_path."/images/b_edit.png\" style='cursor:pointer;width:10px;vertical-align:middle;'/></span>";				
				$liste_rap .= "
					<div class='row' $style id='rap_drag_$id' draggable=\"yes\" dragtype=\"rapport\" dragtext=\"$titre\" dragicon=\"".$base_path."/images/icone_drag_notice.png\"
						handler=\"rap_handle_$id\" recepttype=\"rapport\" recept=\"yes\" highlight=\"rap_highlight\" downlight=\"rap_downlight\" iditem='$id' order='$ordre'>".$contenu.$drag.$modif.$del."</div>			
				";
			}
		}
		
		$form_rapport = str_replace('!!cancel_action!!',$this->cancel_action,$form_rapport);
		$form_rapport = str_replace('!!form_action!!',$this->form_action,$form_rapport);
		$form_rapport = str_replace('!!titre_gauche!!',htmlentities($this->titre_gauche,ENT_QUOTES,$charset),$form_rapport);
		$form_rapport = str_replace('!!list_obj_rapport!!',$liste_rap,$form_rapport);
		$form_rapport = str_replace('!!list_obj!!',$liste,$form_rapport);
		
		//Définition des boutons du format d'export
		$file = $base_path."/demandes/export_format/catalog.xml";
		$file_subst = $base_path."/demandes/export_format/catalog_subst.xml";		
		if (file_exists($file_subst)) { 
			$xml=file_get_contents($file_subst,"r");		
		} else $xml=file_get_contents($file,"r") or die("Can't find XML file $file");		
		//Parse le fichier dans un tableau	
		$param=_parser_text_no_function_($xml,"CATALOG");
		
		$exp_btn ="";
		for($i=0;$i<count($param['ITEM']);$i++){
			$exp_btn .= "<input type='submit' class='bouton' id='".$param['ITEM'][$i]['NAME']."' name='".$param['ITEM'][$i]['NAME']."'
				value='".htmlentities($param['ITEM'][$i]['LIBELLE'],ENT_QUOTES,$charset)."' onClick='this.form.act.value=\"".$param['ITEM'][$i]['CLASSNAME']."\"' />&nbsp;";
		}
		$form_rapport = str_replace('!!liste_export!!',$exp_btn,$form_rapport);
				
		print $form_rapport;
	}
	
	/*
	 * Tableau des éléments exportables dans le rapport
	 */
	function getListeExport(){}
	
	/*
	 * Tableau des éléments composants le rapport
	 */
	function getListeRapport(){}
	
	/*
	 * Affectation des tableaux
	 */
	function setElements(){
		$this->getListeRapport();
		$this->getListeExport();
	}
	
	/*
	 * Création du rapport au format XML
	 */
	function create_rapport(){
		global $charset, $base_path;
		
		$this->rapport_xml = "<?xml version='1.0' encoding='".$charset."' ?>\n";
		$this->rapport_xml .= "<report>\n";
		$this->rapport_xml .= $this->intro;
		$this->rapport_xml .= "<notes>\n";
		for($i=0;$i<count($this->rapport_elements);$i++){
			if(!$this->rapport_elements[$i]['num_element']){
				//Ajout manuel
				switch ($this->rapport_elements[$i]['type']) {
					case '1':
						//Titre
						$this->rapport_xml .= "<note titre='yes' ><content>".htmlspecialchars($this->rapport_elements[$i]['contenu'],ENT_QUOTES,$charset)."</content></note>\n";
					break;
					case '0':
						//Commmentaire
						$this->rapport_xml .= "<note commentaire='yes'><content>".htmlspecialchars($this->rapport_elements[$i]['contenu'],ENT_QUOTES,$charset)."</content></note>\n" ;
					break;
				}
			} else {
				$this->rapport_xml .= "
					<note>
						<date>".htmlspecialchars($this->rapport_elements[$i]['date'],ENT_QUOTES,$charset)."</date>
						<sujet>".htmlspecialchars($this->rapport_elements[$i]['sujet'],ENT_QUOTES,$charset)."</sujet>
						<content>".htmlspecialchars($this->rapport_elements[$i]['contenu'],ENT_QUOTES,$charset)."</content>
					</note>\n
				";
			}
		}	
		$this->rapport_xml .= "</notes>\n";
		$this->rapport_xml .= "</report>";
	}
			
}

class rapport_demandes extends rapport {
	
	var $id_demande=0;
	
	function rapport_demandes($id=0){
		global $msg, $form_rapport;
		
		$this->id_demande = $id;
		$this->titre_gauche = $msg['demandes_liste_notes'];
		$this->cancel_action = "document.location='./demandes.php?categ=gestion&act=see_dmde&iddemande=".$this->id_demande."'";
		$this->form_action = "./demandes/get_rapport.php?iddemande=".$this->id_demande;

		$form_rapport = str_replace('!!idobject!!',$this->id_demande,$form_rapport);
		$this->setElements();
		//$this->generer_intro();
	}
	
	/*
	 * Tableau des notes liées à la demande
	 */	 
	function getListeExport(){
		global $dbh;	
		
		$req = "select id_note, CONCAT(SUBSTRING(contenu,1,20),'','...') as titre, contenu, date_note, num_note_parent, sujet_action as sujet from demandes_notes 
			join demandes_actions on num_action=id_action
			join demandes on (num_demande=id_demande and id_demande='".$this->id_demande."')
			where rapport=1
			and id_note not in (select num_note from rapport_demandes where num_demande='".$this->id_demande."' )
			order by num_action, date_note
			";
		$res = mysql_query($req,$dbh);		
		$indice=0;
		while(($note = mysql_fetch_object($res))){
			$this->export_elements[$indice]['id'] = $note->id_note;
			$this->export_elements[$indice]['titre'] = $note->titre;
			$this->export_elements[$indice]['contenu'] = nl2br($note->contenu);
			$this->export_elements[$indice]['date'] = $note->date_note;
			$this->export_elements[$indice]['id_parent'] = $note->num_note_parent;
			$this->export_elements[$indice]['sujet'] = $note->sujet;
			$indice++;
		}
		
		return $this->export_elements;
	}
	
	/*
	 * Tableau des éléments composants le rapport des demandes
	 */
	function getListeRapport(){
		
		global $dbh;
		
		$req = "select id_item, num_note, r.num_demande, r.contenu, ordre, type, date_note, sujet_action 
		from rapport_demandes r 
		left join demandes_notes on num_note=id_note 
		left join demandes_actions on num_action=id_action 
		where r.num_demande='".$this->id_demande."' order by ordre";
		$res = mysql_query($req,$dbh) or die(mysql_error()."<br/>".$req);
		$indice=0;
		while(($item = mysql_fetch_object($res))){
			$this->rapport_elements[$indice]['id_item'] = $item->id_item;
			$this->rapport_elements[$indice]['num_element'] = $item->num_note;
			$this->rapport_elements[$indice]['num_object'] = $item->num_demande;
			$this->rapport_elements[$indice]['contenu'] = nl2br($item->contenu);
			$this->rapport_elements[$indice]['ordre'] = $item->ordre;
			$this->rapport_elements[$indice]['type'] = $item->type;
			$this->rapport_elements[$indice]['date'] = formatdate($item->date_note);
			$this->rapport_elements[$indice]['sujet'] = $item->sujet_action;
			$indice++;
		}
		return $this->rapport_elements;
	}
	
	/*
	 * Générer l'intro du rapport
	 */
	function generer_intro(){
		global $dbh, $charset;
		
		$req = " select titre_demande, date_demande, deadline_demande, sujet_demande, group_concat(distinct if(concat(prenom,' ',nom)!='',concat(prenom,' ',nom),username) separator '/ ') as docu, 
			CONCAT(empr_prenom,' ',empr_nom) as demandeur, SUM(temps_passe) as temps, SUM(cout) as cout 
			from demandes
			join empr on num_demandeur=id_empr
			left join demandes_actions da on da.num_demande=id_demande
			left join demandes_users du on du.num_demande=id_demande
			left join users on num_user=userid
			where id_demande='".$this->id_demande."' group by id_demande ";
		$res = mysql_query($req,$dbh) or die(mysql_error()."<br/>".$req);
		while(($dmde = mysql_fetch_object($res))){
			$this->intro .= "
				<intro>
					<title>".htmlspecialchars($dmde->titre_demande,ENT_QUOTES,$charset)."</title>
					<date>".htmlspecialchars(formatdate($dmde->date_demande),ENT_QUOTES,$charset)."</date>
					<deadline>".htmlspecialchars(formatdate($dmde->deadline_demande),ENT_QUOTES,$charset)."</deadline>
					<documentaliste>".htmlspecialchars($dmde->docu,ENT_QUOTES,$charset)."</documentaliste>
					<demandeur>".htmlspecialchars($dmde->demandeur,ENT_QUOTES,$charset)."</demandeur>
					<time>".htmlspecialchars(($dmde->temps ? $dmde->temps : '0'),ENT_QUOTES,$charset)."</time>
					<cost>".htmlspecialchars(($dmde->cout ? $dmde->cout : '0'),ENT_QUOTES,$charset)."</cost>
					<abstract>".htmlspecialchars($dmde->sujet_demande,ENT_QUOTES,$charset)."</abstract>					
				</intro>			
			";
		}
		
		return $this->intro;
	}
}
?>