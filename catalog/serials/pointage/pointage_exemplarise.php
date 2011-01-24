<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pointage_exemplarise.php,v 1.32 2010-11-10 10:03:38 ngantier Exp $

// définition du minimum nécéssaire
$base_path="./../../..";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "\$msg[6]";
require_once ("$base_path/includes/init.inc.php");
require_once($class_path."/serials.class.php");
require_once($class_path."/serial_display.class.php");
require_once("$include_path/explnum.inc.php") ;
require_once ($class_path . "/parse_format.class.php");
require_once($class_path."/parametres_perso.class.php");

$templates = <<<ENDOFFILE
			<script type='text/javascript'>
				function desactive(obj) {
					var obj_1=obj+"_1";	
					var obj_2=obj+"_2";	
					var obj_3=obj+"_3";		
					parent.document.getElementById(obj_1).disabled = true;
					parent.document.getElementById(obj_2).disabled = true;
					parent.document.getElementById(obj_3).disabled = true;
				}		
				function enregistre(obj,bul_id) {
					var obj_bul=obj+"_bul";		
					desactive(obj)
					parent.document.getElementById(obj_bul).innerHTML="<a href='./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id="+bul_id+"'>"+"!!Voir_le_bulletin!!"+"</a>";
					parent.kill_frame_periodique();
				}		
				function Fermer(obj) {
					desactive(obj)
				 	parent.kill_frame_periodique();
				}				
			</script>
<div style='width: 98%;'>
	<div id="bouton_fermer_notice_preview" class="right"><a href='#' onClick='parent.kill_frame_periodique();return false;'>X</a></div>
	!!form!!
</div>						
ENDOFFILE;
$templates=str_replace("!!Voir_le_bulletin!!",$msg['pointage_voir_le_bulletin'],$templates);

if(!$expl_id) // pas d'id, c'est une création
	echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[4007], $serial_header);
else echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[4008], $serial_header);

function do_selector_bul_section($section_id, $location_id) {
	global $dbh;
 	global $charset;
	global $deflt_section;
	global $deflt_location;
	
	if (!$section_id) $section_id=$deflt_section ;
	if (!$location_id) $location_id=$deflt_location;

	$rqtloc = "SELECT idlocation FROM docs_location order by location_libelle";
	$resloc = mysql_query($rqtloc, $dbh);
	while ($loc=mysql_fetch_object($resloc)) {
		$requete = "SELECT idsection, section_libelle FROM docs_section, docsloc_section where idsection=num_section and num_location='$loc->idlocation' order by section_libelle";
		$result = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($result);
		if ($nbr_lignes) {			
			if ($loc->idlocation==$location_id) $selector .= "<div id=\"docloc_section".$loc->idlocation."\" style=\"display:block\">";
				else $selector .= "<div id=\"docloc_section".$loc->idlocation."\" style=\"display:none\">";
			$selector .= "<select name='f_ex_section".$loc->idlocation."' id='f_ex_section".$loc->idlocation."'>";
			while($line = mysql_fetch_row($result)) {
				$selector .= "<option value='$line[0]'";
				$line[0] == $section_id ? $selector .= ' SELECTED>' : $selector .= '>';
	 			$selector .= htmlentities($line[1],ENT_QUOTES, $charset).'</option>';
				}                                         
			$selector .= '</select></div>';
			}                 
		}
	return $selector;                         
}                                                 

function bul_do_form($obj,$titre='', $bul_id=0,$titre='') {
	// $obj = objet contenant les propriétés de l'exemplaire associé
	global $bul_expl_form1;
	global $msg; // pour texte du bouton supprimer
	global $dbh;
	global $pmb_type_audit,$select_categ_prop,$pmb_antivol ;
	global $id_bull,$bul_id,$serial_id,$numero,$pmb_rfid_activate,$pmb_rfid_serveur_url;
	
	$bul_expl_form1 = str_replace('!!titre!!', $titre, $bul_expl_form1);
	$action = "./pointage_exemplarise.php?act=update&id_bull=$id_bull&bul_id=$bul_id";
	
	// mise à jour des champs de gestion
	$bul_expl_form1 = str_replace('!!bul_id!!', $obj->expl_bulletin, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!id_form!!', md5(microtime()), $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!org_cb!!', $obj->expl_cb, $bul_expl_form1);	
	$bul_expl_form1 = str_replace('!!expl_id!!', $obj->expl_id, $bul_expl_form1);
	
	$bul_expl_form1 = str_replace('!!action!!', $action, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!id!!', $obj->expl_notice, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!cb!!', $obj->expl_cb, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!note!!', $obj->expl_note, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!comment!!', $obj->expl_comment, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!cote!!', htmlentities($obj->expl_cote,ENT_QUOTES, $charset), $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!prix!!', $obj->expl_prix, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!focus!!',$obj->focus, $bul_expl_form1);
	
	// select "type document"
	$bul_expl_form1 = str_replace('!!type_doc!!',
				do_selector('docs_type', 'expl_typdoc', $obj->expl_typdoc),
				$bul_expl_form1);		
	// select "section"
	$bul_expl_form1 = str_replace('!!section!!',
				do_selector_bul_section($obj->expl_section, $obj->expl_location),
				$bul_expl_form1);
	// select "statut"
	$bul_expl_form1 = str_replace('!!statut!!',
				do_selector('docs_statut', 'expl_statut', $obj->expl_statut),
				$bul_expl_form1);
	// select "localisation"
	$bul_expl_form1 = str_replace('!!localisation!!',
				gen_liste ("select distinct idlocation, location_libelle from docs_location, docsloc_section where num_location=idlocation order by 2", "idlocation", "location_libelle", 'expl_location', "calcule_section(this);", $obj->expl_location, "", "","","",0),
				$bul_expl_form1);
	// select "code statistique"
	$bul_expl_form1 = str_replace('!!codestat!!',
				do_selector('docs_codestat', 'expl_codestat', $obj->expl_codestat),
				$bul_expl_form1);
	// select "owner"
	$bul_expl_form1 = str_replace('!!owner!!',
				do_selector('lenders', 'expl_owner', $obj->expl_owner),
				$bul_expl_form1);
	$selector="";
	if($pmb_antivol>0) {
		// select "type_antivol"
		$selector = "
		<div class='colonne3'>
		<!-- code stat -->
		<label class='etiquette' for='type_antivol'>$msg[type_antivol]</label>
		<div class='row'>
		<select name='type_antivol' id='type_antivol'>";	
		$selector .= "<option value='0'";
		if($obj->type_antivol ==0)$selector .= ' SELECTED';
		$selector .= '>';
		$selector .= $msg["type_antivol_aucun"].'</option>';
		$selector .= "<option value='1'";
		if($obj->type_antivol ==1)$selector .= ' SELECTED';
		$selector .= '>';
		$selector .= $msg["type_antivol_magnetique"].'</option>';
		$selector .= "<option value='2'";
		if($obj->type_antivol ==2)$selector .= ' SELECTED';
		$selector .= '>';
		$selector .= $msg["type_antivol_autre"].'</option>';		
	                                        
		$selector .= '</select></div></div>';   
	}        
	$bul_expl_form1 = str_replace('!!type_antivol!!', $selector, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!bul_id!!', $bul_id, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!expl_id!!', $obj->expl_id, $bul_expl_form1);	
	$bul_expl_form1 = str_replace('!!bul_no!!', htmlentities($obj->bul_no,ENT_QUOTES, $charset)	, $bul_expl_form1);
	$date_date_formatee = formatdate($obj->date_date);
	$date_clic = "onClick=\"openPopUp('./../../../select.php?what=calendrier&caller=expl&date_caller=".str_replace('-', '', $obj->date_date)."&param1=date_date&param2=date_date_lib&auto_submit=NO&date_anterieure=YES', 'date_date', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"  ";
	$date_date = "<input type='hidden' name='date_date' value='".str_replace('-','', $obj->date_date)."' />
		<input class='saisie-10em' type='text' name='date_date_lib' value='".$date_date_formatee."' />
		<input class='bouton_small' type='button' name='date_date_lib_bouton' value='".$msg["bouton_calendrier"]."' ".$date_clic." />";
		
	$bul_expl_form1 = str_replace('!!date_date!!', $date_date, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!bul_date!!', htmlentities($obj->bul_date,ENT_QUOTES, $charset), $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!bul_titre!!', htmlentities($obj->bul_titre,ENT_QUOTES, $charset), $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!serial_id!!', $serial_id, $bul_expl_form1);
	$bul_expl_form1 = str_replace('!!numero!!', $obj->bul_titre, $bul_expl_form1);	
	$bul_expl_form1 = str_replace('!!destinataire!!', $obj->destinataire, $bul_expl_form1);

	$p_perso=new parametres_perso("expl");
	if (!$p_perso->no_special_fields) {
		$c=0;
		$perso="";
		$perso_=$p_perso->show_editable_fields($obj->expl_id);
		for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
			$p=$perso_["FIELDS"][$i];
			if ($c==0) $perso.="<div class='row'>\n";
			$perso.="<div class='colonne3'><label for='".$p["NAME"]."' class='etiquette'>".$p["TITRE"]."</label><div class='row'>".$p["AFF"]."</div></div>\n";
			$c++;
			if ($c==3) {
				$perso.="</div>\n";
				$c=0;
			}
		}	
		if ($c==1) $perso.="<div class='colonne2'>&nbsp;</div>\n</div>\n";
		$perso=$perso_["CHECK_SCRIPTS"]."\n".$perso;
	} else 
		$perso="\n<script>function check_form() { return true; }</script>\n";
	$bul_expl_form1 = str_replace("!!champs_perso!!",$perso,$bul_expl_form1);
	
	if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url) {
		$script_rfid_encode="if(script_rfid_encode()==false) return false;";	
		$bul_expl_form1 = str_replace('!!questionrfid!!', $script_rfid_encode, $bul_expl_form1);
	}
	else $bul_expl_form1 = str_replace('!!questionrfid!!', '', $bul_expl_form1);

	return $bul_expl_form1 ;
}

function sql_value($rqt) {
	if($result=mysql_query($rqt))
		if($row = mysql_fetch_row($result))	return $row[0];
	return '';
}

$requete = "SELECT * FROM abts_grille_abt WHERE id_bull='$id_bull'";
$abtsQuery = mysql_query($requete, $dbh);
if(mysql_num_rows($abtsQuery)) {
	$abts = mysql_fetch_object($abtsQuery);
	$modele_id = $abts->modele_id;
	$abt_id = $abts->num_abt;
	$value['date_date']=$abts->date_parution;
}
$requete = "SELECT * FROM abts_abts WHERE abt_id='$abt_id'";
$abtsQuery = mysql_query($requete, $dbh);
if(mysql_num_rows($abtsQuery)) {
	$abts = mysql_fetch_object($abtsQuery);

	$exemp_auto = $abts->exemp_auto;
	$type_antivol = $abts->type_antivol;
	$date_debut = $abts->date_debut;
	$date_fin = $abts->date_fin;
	
}
$requete = "SELECT num_notice,format_periode FROM abts_modeles WHERE modele_id='$modele_id'";
$abtsQuery = mysql_query($requete, $dbh);
if(mysql_num_rows($abtsQuery)) {
	$abts = mysql_fetch_object($abtsQuery);

	$format_periode = $abts->format_periode;
	$serial_id = $abts->num_notice;
}

//Préparation nouveau bulletin
$myBulletinage = new bulletinage(0, $serial_id);
$value['niveau_biblio']='b'; 
$value['bul_no']=$numero;

//Genération du libellé de période
$print_format=new parse_format();
$print_format->var_format['DATE'] = $value['date_date'];
$print_format->var_format['NUM'] = $nume;
$print_format->var_format['VOL'] = $vol;
$print_format->var_format['TOM'] = $tom;
$print_format->var_format['START_DATE'] = $date_debut;
$print_format->var_format['END_DATE'] = $date_fin;

$requete = "SELECT * FROM abts_abts_modeles WHERE modele_id='$modele_id' and abt_id='$abt_id' ";
$abtsabtsQuery = mysql_query($requete, $dbh);
if(mysql_num_rows($abtsabtsQuery)) {
	$abtsabts = mysql_fetch_object($abtsabtsQuery);
	$print_format->var_format['START_NUM'] = $abtsabts->num;
	$print_format->var_format['START_VOL'] = $abtsabts->vol;
	$print_format->var_format['START_TOM'] = $abtsabts->tome;	
	$num_statut=$abtsabts->num_statut_general;
}


$print_format->cmd = $format_periode;
$libelle_periode=$print_format->exec_cmd();
$value['bul_date']=$libelle_periode;

$flag_exemp_auto=0;
if ($exemp_auto==1){
	require_once($include_path."/$pmb_numero_exemplaire_auto_script");
	$flag_exemp_auto=1;
}

if(($act=='update') ) {
	$expl_cote = clean_string($expl_cote);
	$expl_note = clean_string($expl_note);
	$expl_comment = clean_string($expl_comment);
	$expl_prix = clean_string($expl_prix);	
	// on verifie l'existance du bulletin
	if(!$bul_id){
		if(!($bul_id=sql_value("SELECT bulletin_id FROM bulletins where  bulletin_numero='$numero' and date_date='$date_date' and bulletin_notice='$serial_id'"))){ 		
			//Création du bulletin si pas déjà présent
			$bul_id = $myBulletinage->update($value);
		}
	}
	// on récupère les valeurs
	$formlocid="f_ex_section".$expl_location ;
	$expl_section=$$formlocid ;

	// si le code-barre saisi est déjà utilisé, on affiche une erreur
	$requete = "SELECT COUNT(1) FROM exemplaires WHERE expl_cb='$f_ex_cb'";
	$myQuery = mysql_query($requete, $dbh);
	if(mysql_result($myQuery, 0, 0))  { 
		print "<script>alert('".$msg['pointage_message_code_utilise']."'); history.go(-1);</script>";
		exit();
	}
	// Dépiéger le dernier bulletin
	if($num_statut) {
		$requete="SELECT bulletin_id  FROM bulletins where date_date<'$date_date' and bulletin_notice='$serial_id' ORDER BY date_date DESC LIMIT 1";
		print $requete."<br />";
		$result_dernier = mysql_query($requete);
		if ($r_dernier = mysql_fetch_object($result_dernier)) {
			$dernier_bul_id	=$r_dernier->bulletin_id;
			$requete = "update exemplaires set expl_statut=$num_statut where expl_bulletin=$dernier_bul_id";
			mysql_query($requete, $dbh);
			print $requete."<br />";
		}
	}
	// on prépare la date de création ou modification
	$expl_date = today();
	
	$values = "expl_cb='$f_ex_cb'";
	$values .= ", expl_notice='0'";
	$values .= ", expl_bulletin='$bul_id'";
	$values .= ", expl_typdoc='$expl_typdoc'";
	$values .= ", expl_cote='$expl_cote'";
	$values .= ", expl_section='$expl_section'";
	$values .= ", expl_statut='$expl_statut'";
	$values .= ", expl_location='$expl_location'";
	$values .= ", expl_codestat='$expl_codestat'";
	$values .= ", expl_note='$expl_note'";
	$values .= ", expl_comment='$expl_comment'";
	$values .= ", expl_prix='$expl_prix'";
	$values .= ", expl_owner='$expl_owner'";
	$values .= ", type_antivol='$type_antivol'";
	$requete = "INSERT INTO exemplaires set $values , create_date=sysdate() ";

	$myQuery = mysql_query($requete, $dbh);
	$expl_id=mysql_insert_id();

	//parametres_perso de l'exemplaire
	$p_perso=new parametres_perso("expl");
	$nberrors=$p_perso->check_submited_fields();
	if(!$nberrors) $p_perso->rec_fields_perso($expl_id);
	
	//Mis à jour du bulletin	
	$requete = "UPDATE bulletins set bulletin_numero='$bul_no',date_date='$date_date', mention_date='$bul_date', bulletin_titre='$bul_titre' WHERE bulletin_id='$bul_id' ";
	$myQuery = mysql_query($requete, $dbh);
	$expl_id=mysql_insert_id();	
	
	// Déclaration du bulletin comme reçu
	$requete="update abts_grille_abt set state='2' where id_bull= '$id_bull' ";	
	mysql_query($requete);		
		
	$id_form = md5(microtime());
	$templates=str_replace("!!form!!","<script type='text/javascript'>enregistre('$id_bull','$bul_id');</script>",$templates);	
} 
else {
	include("$include_path/templates/serials.tpl.php");

	if($nonrecevable) {
		$value['bul_titre'] = $msg['abonnements_bulletin_non_recevable'] ;
		$requete="update abts_grille_abt set state='3' where id_bull= '$id_bull' ";	
		mysql_query($requete);		
		$templates=str_replace("!!form!!","<script type='text/javascript'>Fermer('$id_bull');</script>",$templates);
		print $templates;
		exit();
	} 
	$expl->date_date =$value['date_date'];
	$expl->bul_date = $libelle_periode; 
	$expl->bul_no = stripslashes($numero);
	
	//Récupération des infos du bulletin pour les proposer sur la frame
	$requete = "SELECT * FROM bulletins where bulletin_numero='$numero' and bulletin_notice='$serial_id' and date_date='".$value['date_date']."'";
	$bull_Query = mysql_query($requete, $dbh);
	if(mysql_num_rows($bull_Query)) {	
		$bull = mysql_fetch_object($bull_Query);
		$bul_id= $bull->bulletin_id;
		$expl->date_date = $bull->date_date;
		$expl->bul_date = $bull->mention_date;
		$expl->bul_titre = $bull->bulletin_titre;
	}	
	if($flag_exemp_auto==1)	{
		//Génération automatique de code barre, activé pour cet abonnement
  		$requete="DELETE from exemplaires_temp where sess not in (select SESSID from sessions)";
   		$res = mysql_query($requete,$dbh); 	
    	//Appel à la fonction de génération automatique de cb
    	$code_exemplaire =init_gen_code_exemplaire(0,$bul_id);
    	do {
    		$code_exemplaire = gen_code_exemplaire(0,$bul_id,$code_exemplaire);
    		$requete="select expl_cb from exemplaires WHERE expl_cb='$code_exemplaire'";
    		$res0 = mysql_query($requete,$dbh);
    		$requete="select cb from exemplaires_temp WHERE cb='$code_exemplaire'";
    		$res1 = mysql_query($requete,$dbh);
    	} while((mysql_num_rows($res0)||mysql_num_rows($res1)));
    		
   		//Memorise dans temps le cb et la session pour le cas de multi utilisateur session
   		$requete="INSERT INTO exemplaires_temp (cb ,sess) VALUES ('$code_exemplaire','".SESSid."')";
   		$res = mysql_query($requete,$dbh);
		$expl->expl_cb=$code_exemplaire;	
		//Focus sur le bouton 'Enregistre'
		$expl->focus="<script type='text/javascript' >document.forms[\"expl\"].bouton_enregistre.focus();</script>";
	} else {
		//Focus sur le l'input de saisie de code barre 
		$expl->focus="<script type='text/javascript' >document.forms[\"expl\"].f_ex_cb.focus();</script>";
	}
	$bull_form="";				
	$perio = new serial_display($myBulletinage->serial_id, 1);
	$perio_header =  $perio->header;
	
	print pmb_bidi("<div class='row'><h2>".$bulletinage->display.'</h2></div>');
	
	$requete = "SELECT * FROM abts_abts WHERE abt_id='$abt_id'";
	$abtsQuery = mysql_query($requete, $dbh);
	if(mysql_num_rows($abtsQuery)) {
		$abts = mysql_fetch_object($abtsQuery);
		$expl->expl_cote = $abts->cote;
		$expl->expl_location = $abts->location_id;
		$expl->expl_section = $abts->section_id;
		$expl->expl_codestat = $abts->codestat_id;
		$expl->expl_typdoc = $abts->typdoc_id;
		$expl->expl_statut = $abts->statut_id;
		$expl->expl_owner = $abts->lender_id;
		$expl->type_antivol = $abts->type_antivol;
		$expl->expl_note = $abts->expl_note;
		$expl->expl_comment = $abts->expl_comment;
		$expl->expl_prix = $abts->expl_prix;		
		$expl->destinataire = $abts->destinataire;
	}				
	// sélection de la cote dewey de la notice chapeau pour pré-renseignement de la cote en création expl
	$query_cote = "select indexint_name from indexint, notices, bulletins where bulletin_id='$bul_id' and bulletin_notice=notice_id and notices.indexint=indexint.indexint_id ";
	$myQuery_cote = mysql_query($query_cote , $dbh);
	if(mysql_num_rows($myQuery_cote)) {
		$pre_cote = mysql_fetch_object($myQuery_cote);
		$expl->expl_cote = $pre_cote->indexint_name ;
	}	
	$bull_form.= bul_do_form($expl);
	$templates=str_replace("!!form!!",$bull_form,$templates);
}
print $templates;
?>