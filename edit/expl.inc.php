<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl.inc.php,v 1.35 2009-06-17 14:59:20 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ("$class_path/emprunteur.class.php");

//R�cup�ration des variables post�es, on en aura besoin pour les liens
$page=$_SERVER[SCRIPT_NAME];

// Pour localiser les �ditions : $deflt2docs_location, $pmb_lecteurs_localises, $empr_location_id ;

// Calcul du nombre de pages � afficher 
$sql = "SELECT count(1) ";
$sql.= "FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) ";
$sql.= "LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) ";
$sql.= "LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), ";
$sql.= "docs_type , pret, empr ";
$sql.= "WHERE ";

if ($pmb_lecteurs_localises) {
	if ($empr_location_id=="") 
		$empr_location_id = $deflt2docs_location ;
	if ($empr_location_id!=0) 
		$sql.= "empr_location='$empr_location_id' AND "; 
}

$sql.= "expl_typdoc = idtyp_doc and pret_idexpl = expl_id and empr.id_empr = pret.pret_idempr ";
$sql = $sql.$critere_requete;

$req_nombre_lignes_pret = mysql_query($sql, $dbh);

$nombre_lignes_pret = mysql_result($req_nombre_lignes_pret, 0, 0);

//Si aucune limite_page n'a �t� pass�e, valeur par d�faut : 10
if ($limite_page=="") {
	$limite_page = 10; 
}
$nbpages= $nombre_lignes_pret / $limite_page; 

// on arondi le nombre de page pour ne pas avoir de virgules, ici au chiffre sup�rieur 
$nbpages_arrondi = ceil($nbpages); 

// on enl�ve 1 au nombre de pages, car la 1ere page affich�e ne fait pas partie des pages suivantes
$nbpages_arrondi = $nbpages_arrondi - 1; 

// si la variable numero de page a une valeur ou est diff�rente de 0,
// on multiplie la limite par le numero de la page pass�e par l'url
// sinon, pas de variable numero_page
if(isset($numero_page) || $numero_page != 0 ) { 
	$limite_mysql = $limite_page * $numero_page; 
} else { 
	$limite_mysql = 0; // la limite est de 0
} 

switch($sub) {
	case "encours" :
		echo "<h1>".$msg[1110]."&nbsp;:&nbsp;".$msg[1111]."</h1>";
		break;
	case "retard" :
		echo "<h1>".$msg[1110]."&nbsp;:&nbsp;".$msg[1112]."</h1>";
		break;
	case "retard_par_date" :
		echo "<h1>".$msg[1110]."&nbsp;:&nbsp;".$msg[edit_expl_retard_par_date]."</h1>";
		break;
}

// Comptage retard/en cours
$sql_count = "SELECT IF(pret_retour>=CURDATE(),'ENCOURS','RETARDS') as retard, count(pret_idexpl) as combien ";
$sql_count.= "FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) ";
$sql_count.= "        LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) ";
$sql_count.= "        LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), ";
$sql_count.= "        pret, empr ";
$sql_count.= "WHERE ";
if ($pmb_lecteurs_localises) {
	if ($empr_location_id!=0) 
		$sql_count.= "empr_location='$empr_location_id' AND "; 
}
$sql_count.= "pret_idexpl = expl_id  and empr.id_empr = pret.pret_idempr ";
$sql_count.= " group by retard ";
$req_count = mysql_query($sql_count) or die("Erreur SQL !<br />".$sql_count."<br />".mysql_error()); 
while ($data_count = mysql_fetch_object($req_count)) { 
	$nbtotal_prets[$data_count->retard]=$data_count->combien;
}
// construction du message ## pr�ts en retard sur un total de ##
$msg[n_retards_sur_total_de] = str_replace ("!!nb_retards!!",$nbtotal_prets[RETARDS]*1,$msg[n_retards_sur_total_de]);
$msg[n_retards_sur_total_de] = str_replace ("!!nb_total!!",($nbtotal_prets[RETARDS]+$nbtotal_prets[ENCOURS])*1,$msg[n_retards_sur_total_de]);
echo $msg[n_retards_sur_total_de];

// formulaire de restriction
echo "
	<form class='form-$current_module' action='$page?categ=$categ&sub=$sub&limite_page=$limite_page&numero_page=$numero_page' method='post'>
 	$msg[circ_afficher]
 	<input type=text name=limite_page size=2 value=$limite_page class='petit'> $msg[1905] ";
if ($pmb_lecteurs_localises) echo docs_location::gen_combo_box_empr($empr_location_id);
echo "<input type='submit' class='bouton' value='".$msg['actualiser']."' />
	</form><br />";

//REINITIALISATION DE LA REQUETE SQL
$sql = "SELECT date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
$sql .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
$sql .= " IF(pret_retour>=CURDATE(),0,1) as retard, " ;
$sql .= " id_empr, empr_nom, empr_prenom, empr_mail, id_empr, empr_cb, expl_cote, expl_cb, expl_notice, expl_bulletin, notices_m.notice_id as idnot, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit ";
$sql .= "FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) ";
$sql .= "        LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) ";
$sql .= "        LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), ";
$sql .= "        docs_type , pret, empr ";
$sql .= "WHERE ";
if ($pmb_lecteurs_localises) {
	if ($empr_location_id!=0) 
		$sql.= "empr_location='$empr_location_id' AND "; 
}
$sql.= "expl_typdoc = idtyp_doc and pret_idexpl = expl_id  and empr.id_empr = pret.pret_idempr ";
$sql = $sql.$critere_requete;
$sql = $sql." LIMIT ".$limite_mysql.", ".$limite_page; 
// on lance la requ�te (mysql_query) et on impose un message d'erreur si la requ�te ne se passe pas bien (or die) 
$req = mysql_query($sql) or die("Erreur SQL !<br />".$sql."<br />".mysql_error()); 

// on va scanner tous les tuples un par un
echo "<table >";
echo "<tr>
 	<th>$msg[4014]</th>
	<th>$msg[4016]</th>
 	<th>$msg[233]</th>
 	<th>$msg[234]</th>
 	<th>$msg[empr_nom_prenom]</th>
 	<th>$msg[circ_date_emprunt]</th>
 	<th>$msg[circ_date_retour]</th>
 	<th colspan=2>$msg[369]</th>
	</tr>"; 
$odd_even=0;
while ($data = mysql_fetch_array($req)) {
	$empr_nom = $data['empr_nom'];
	$empr_prenom = $data['empr_prenom'];
	$empr_mail = $data['empr_mail'];
	$id_empr = $data['id_empr']; 
	$empr_cb = $data['empr_cb'];
	$aff_pret_date = $data['aff_pret_date'];
	$aff_pret_retour = $data['aff_pret_retour'];  
	$retard = $data['retard'];
	$cote_expl = $data['expl_cote'];  
	$id_expl =$data['expl_cb'];
	$titre = $data['tit'];
	$id_empr=$data['id_empr'];
	
	$header_aut = "";
	$responsabilites = get_notice_authors($data['idnot']) ;
	$as = array_search ("0", $responsabilites["responsabilites"]) ;
	if ($as!== FALSE && $as!== NULL) {
		$auteur_0 = $responsabilites["auteurs"][$as] ;
		$auteur = new auteur($auteur_0["id"]);
		$header_aut .= $auteur->isbd_entry;
	} else {
		$aut1_libelle=array();
		$as = array_keys ($responsabilites["responsabilites"], "1" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			$aut1_libelle[]= $auteur->isbd_entry;
		}
		
		$header_aut .= implode (", ",$aut1_libelle) ;
	}
	
	$header_aut ? $auteur=$header_aut : $auteur="";
	if($retard || ($sub=='encours')) {	
		// on affiche les r�sultats
		if ($retard) $tit_color="color='RED'";				
		else $tit_color="";				
	
		if ($odd_even==0) {
			$pair_impair = "odd";
			$odd_even=1;
		} elseif ($odd_even==1) {
			$pair_impair = "even";
			$odd_even=0;
		}
		
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"";			
		echo "<tr class='$pair_impair' $tr_javascript>";		
	
		if (SESSrights & CIRCULATION_AUTH) 
			echo "<td><a href=\"./circ.php?categ=visu_ex&form_cb_expl=".$id_expl."\">".$id_expl."</a></td>";
		else 
			echo "<td>".$id_expl."</td>";
	
		echo "<td>".$cote_expl."</td>";
		
		if (SESSrights & CATALOGAGE_AUTH) {
			if ($data['expl_notice']) 
				echo "<td><a href='./catalog.php?categ=isbd&id=".$data['expl_notice']."'><font $tit_color><b>".$titre."</b></font></a></td>"; // notice de monographie
			elseif ($data['expl_bulletin']) 
				echo "<td><a href='./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$data['expl_bulletin']."'><font $tit_color><b>".$titre."</b></font></a></td>"; // notice de bulletin
			else 
				echo "<td><font $tit_color><b>".$titre."</b></font></td>";
		} else 
			echo "<td><font $tit_color><b>".$titre."</b></font></td>";
		
		echo "<td><font $tit_color>".$auteur."</font></td>";    
		// **************** ajout ic�ne ajout panier
		if ($empr_show_caddie) {
			$img_ajout_empr_caddie="<img src='./images/basket_empr.gif' align='middle' alt='basket' title=\"${msg[400]}\" onClick=\"openPopUp('./cart.php?object_type=EMPR&item=".$id_empr."', 'cart', 600, 700, -2, -2,'$selector_prop_ajout_caddie_empr')\">&nbsp;";
		}
		
		echo "<td>$img_ajout_empr_caddie<a href=\"./circ.php?categ=pret&form_cb=".rawurlencode($empr_cb)."\">".$empr_nom.", ".$empr_prenom."</a></td>"; 
		echo "<td>".$aff_pret_date."</td>"; 
		echo "<td><font $tit_color><b>".$aff_pret_retour."</b></font></td>";
	
		/* test de date de retour d�pass�e */
		if ($retard) {			
			$imprime_click = "onclick=\"openPopUp('./pdf.php?pdfdoc=lettre_retard&cb_doc=$id_expl&id_empr=$id_empr', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) \"";
			$mail_click = "onclick=\"if (confirm('".$msg["mail_retard_confirm"]."')) {openPopUp('./mail.php?type_mail=mail_retard&cb_doc=$id_expl&id_empr=$id_empr', 'mail', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');} return(false) \"";
			echo "\n<td align='center'><a href=\"#\" ".$imprime_click."><img src=\"./images/new.gif\" title=\"".$msg["lettre_retard"]."\" alt=\"".$msg[lettre_retard]."\" border=\"0\"></a></td>\n";
			if (($empr_mail)&&($biblio_email)) 
				echo "<td><a href=\"#\" ".$mail_click."><img src=\"./images/mail.png\" title=\"".$msg[mail_retard]."\" alt=\"".$msg[mail_retard]."\" border=\"0\"></a></td>"; 
			else 
				echo "<td>&nbsp;</td>";
		} else {
			echo "<td>&nbsp;</td><td>&nbsp;</td>";
		}
	
		echo "</tr>\n";
	}
} 

echo "</table>";

//LIENS PAGE SUIVANTE et PAGE PRECEDENTE
$navpag="";
if( $nbpages_arrondi != 0 && empty($numero_page)) {
 	$navpag = '&lt; '.$msg[48].' <a href="'.$page.'?categ='.$categ.'&sub='.$sub.'&limite_page='.$limite_page;
 	$navpag .='&numero_page=1&empr_location_id='.$empr_location_id.'">'.$msg[49]. '></a>'; // on passe la variable numero page � 1
} elseif ($nbpages_arrondi !='0' && isset($numero_page) && $numero_page < $nbpages_arrondi) {
	$suivant = $numero_page + 1; // on ajoute 1 au numero de page en cours 
	$precedent = $numero_page - 1;
	$navpag .='<a href="'.$page.'?categ='.$categ.'&sub='.$sub.'&limite_page='.$limite_page.'&numero_page='.$precedent;
	$navpag .='&empr_location_id='.$empr_location_id.'">&lt; '.$msg[48].'</a>'; // retour page pr�c�dente
	$navpag .='<a href="'.$page.'?categ='.$categ.'&sub='.$sub.'&limite_page='.$limite_page.'&numero_page='.$suivant;
	$navpag .='&empr_location_id='.$empr_location_id.'">'.$msg[49].' &gt;</a>'; //le lien pour les pages suivantes
	} // dans cette condition, le lien qui sera affich� lorsque le nombre de page a �t� atteint
		elseif ( $nbpages_arrondi !='0' && isset($numero_page) && $numero_page >= $nbpages_arrondi ) { 
			$precedent = $numero_page - 1;
			$navpag .='<a href="'.$page.'?categ='.$categ.'&sub='.$sub.'&limite_page='.$limite_page.'&numero_page='.$precedent;
 			$navpag .='&empr_location_id='.$empr_location_id.'">&lt; '.$msg[48].'</a>'; // retour page pr�c�dente
		}

// formulaire d'action tout imprimer, dispo uniquement si pas de relances pultiples
if ($pmb_gestion_amende==0 || $pmb_gestion_financiere==0) {
	$bouton_imprime_tout ="" ;
	if ($pmb_lecteurs_localises && $empr_location_id!="") 
		$restrict_localisation = "&empr_location_id=$empr_location_id" ;
	switch($sub) {
		case "encours" :
			break;
		case "pargroupe" :
			$bouton_imprime_tout = "<input type='button' class='bouton' value=\"".$msg['lettres_relance_groupe']."\" onclick=\"openPopUp('./pdf.php?pdfdoc=lettre_retard_groupe', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) \" >";
			break;
		case "retard" :
		case "retard_par_date" :
			$bouton_imprime_tout = "<input type='button' class='bouton' value=\"".$msg['lettres_relance']."\" onclick=\"openPopUp('./pdf.php?pdfdoc=lettre_retard".$restrict_localisation."', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) \" >";
			break;
		case "owner" :
			break;
		default :
			break;
	}
	if ($bouton_imprime_tout) echo "
		<br />
		<form class='form-$current_module' action='' method='post'>
		$bouton_imprime_tout
		</form>";
}

echo "<br /><center>$navpag</center>" ;

mysql_free_result ($req); 
