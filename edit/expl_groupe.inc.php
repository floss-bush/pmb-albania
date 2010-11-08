<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl_groupe.inc.php,v 1.33 2009-06-17 14:59:20 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Récupération des variables postées, on en aura besoin pour les liens
$page=$_SERVER[SCRIPT_NAME];

//Requete et calcul du nombre de pages à afficher selon la taille de la base 'pret'
// ********************************************************************************

$sql = "";
$sql = "SELECT id_groupe, libelle_groupe, resp_groupe, ";
$sql = $sql." id_empr, empr_cb, empr_nom, empr_prenom, ";
$sql = $sql."pret_idexpl, pret_date, pret_retour, ";
$sql = $sql."expl_id, notice_id, expl_cb, tit1 ";
$sql = $sql."FROM empr,pret,empr_groupe, groupe, exemplaires LEFT OUTER JOIN notices "; 
$sql = $sql."ON exemplaires.expl_notice = notices.notice_id "; 
$sql = $sql."WHERE pret.pret_idempr = empr.id_empr AND pret.pret_idexpl = exemplaires.expl_id AND empr_groupe.empr_id = empr.id_empr AND groupe.id_groupe = empr_groupe.groupe_id ";
$sql = $sql.$critere_requete;

$req_nombre_lignes_pret = mysql_query($sql);

$nombre_lignes_pret = mysql_numrows($req_nombre_lignes_pret);

//Si aucune limite_page n'a été passée, valeur par défaut : 10
if ($limite_page=="") {$limite_page = 10; }
$nbpages= $nombre_lignes_pret / $limite_page; 

// on arondi le nombre de page pour ne pas avoir de virgules, ici au chiffre supérieur 
$nbpages_arrondi = ceil($nbpages); 

// on enlève 1 au nombre de pages, car la 1ere page affichée ne fait pas partie des pages suivantes
$nbpages_arrondi = $nbpages_arrondi - 1; 

// si par un quelconque hasard, on se retrouve après le dernier enregistrement, rechargement de la liste au premier ouvrage
if ($numero_page > $nbpages_arrondi) {
	echo "<script language=\"javascript\">document.location.replace(\"".$page."?categ=".$categ."&sub=".$sub."&limite_page=".$limite_page."\");</script>";
	}

switch($sub) {
	case "ppargroupe":
		echo "<h1>".$msg[1110]."&nbsp;:&nbsp;".$msg[1114]."</h1>";
		break;
	case "rpargroupe":
  		echo "<h1>".$msg[1110]."&nbsp;:&nbsp;".$msg["menu_retards_groupe"]."</h1>";
		break;
	default:
		break;
}

jscript_checkboxb();

//REINITIALISATION DE LA REQUETE SQL
$sql = "SELECT id_groupe, libelle_groupe, resp_groupe, ";
$sql .= "id_empr, empr_cb, empr_nom, empr_prenom, empr_mail, ";
$sql .= "pret_idexpl, pret_date, pret_retour, ";
$sql .= "expl_cote, expl_id, expl_cb, ";
$sql .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
$sql .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
$sql .= " IF(pret_retour>=curdate(),0,1) as retard, " ; 
$sql .= " expl_notice, expl_bulletin, notices_m.notice_id as idnot, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit ";
$sql .= "FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) ";
$sql.= "        LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) ";
$sql.= "        LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), " ;
$sql.= "        empr,pret,empr_groupe, groupe "; 
$sql .= "WHERE pret.pret_idempr = empr.id_empr AND pret.pret_idexpl = exemplaires.expl_id AND empr_groupe.empr_id = empr.id_empr AND groupe.id_groupe = empr_groupe.groupe_id ";
$sql .= $critere_requete; 

//Renvoi un tableau contenant la liste des groupes, ainsi que l'index du premier élément de ce groupe dans la requete.
mysql_query("SET @rank :=0;");
$sqlgroup = "
SELECT id_groupe, libelle_groupe, MIN(rank) as min_pos FROM (
	SELECT rank, id_groupe, libelle_groupe FROM (
		SELECT @rank := @rank +1 AS rank, libelle_groupe, id_groupe	FROM 
		(
			$sql
		) as temp
		ORDER BY libelle_groupe
		) AS foo
	) AS final GROUP BY libelle_groupe;";

$groups = array();
$req = mysql_query($sqlgroup);
while ($row = mysql_fetch_array($req)) {
	$groups[$row["id_groupe"]]=array("libelle" => $row["libelle_groupe"], "first_pos" => $row["min_pos"]-1);
}

// si la variable numero de page a une valeur ou est différente de 0,
// on multiplie la limite par le numero de la page passée par l'url
// sinon, pas de variable numero_page
if (!isset($gogroup_id)) 
	$gogroup_id = -1;

if ($gogroup_id != -1) {
	$limite_mysql = $groups[$gogroup_id]['first_pos'];
	$numero_page = ceil(($groups[$gogroup_id]['first_pos'] + $limite_page) / $limite_page) - 1; 
}
else if(isset($numero_page) || $numero_page != 0 ) { 
	$limite_mysql = $limite_page * $numero_page; 
} 
else { 
	$limite_mysql = 0; // la limite est de 0
}

$sql .= " LIMIT ".$limite_mysql.", ".$limite_page;

// on lance la requête (mysql_query) et on impose un message d'erreur si la requête ne se passe pas bien (or die) 
$req = mysql_query($sql) or die("Erreur SQL !<br />".$sql."<br />".mysql_error()); 

echo "<form class='form-$current_module' action=$page?categ=$categ&sub=$sub&limite_page=$limite_page&numero_page=$numero_page method=post>";

echo $msg['edit_go_directly_to']."<select name=\"gogroup_id\"><option value=\"-1\"></option>";

	foreach ($groups as $id => $infos){
		echo '<option value="'.$id.'" '.($id == $gogroup_id ? 'selected' : '').'>'.$infos["libelle"].'</option>';
	}
	
echo "</select><br />";
echo "$msg[circ_afficher] <input type='text' name='limite_page' size=2 value=$limite_page class='petit'> $msg[1905]";
echo "<br /><input type='submit' class='bouton' value='".$msg["actualiser"]."' title='".$msg["actualiser"]."' />
	</form>";


// on va scanner tous les tuples un par un 
echo "<form name='cases_a_cocher' onSubmit='return(false);' method='post'";
switch($sub) {
	case "ppargroupe" :
		echo " action='./pdf.php?pdfdoc=liste_pret_groupe' ";
		break;
	case "rpargroupe" :
		echo " action='./pdf.php?pdfdoc=lettre_retard_groupe' ";
		break;
	default :
		break;
	}		
echo "><table class='fiche-lecteur' width=100%>";
$cochtous="onclick=\"unSetCheckboxes('cases_a_cocher','coch_groupe')\"";
echo "<tr><th>&nbsp;<input type='button' name='cochgroupes' class='bouton_small' value='+' title='' ".$cochtous."/>&nbsp;</th><th>".$msg[4014]."</th><th>".$msg[4016]."</th><th>".$msg[233].
	"</th><th>".$msg[234]."</th><th>".$msg[empr_nom_prenom].
	"</th><th>".$msg[circ_date_emprunt]."</th><th>".$msg[circ_date_retour].
	"</th>";

switch($sub) {
	case "ppargroupe" :
		echo "<th>".$msg[1110]."</th>";
		$message=$msg['imprimer_liste_prets_groupe'];
		break;
	case "rpargroupe" :
		echo "<th>".$msg[369]."</th>";
		$message=$msg['imprimer_lettres_groupe_relance'];
		break;
	default :
		break;
	}

echo "</tr>"; 
$odd_even=0;
$ancien_groupe=0;

while ($data = mysql_fetch_array($req)) { 
	$id_groupe = $data['id_groupe'];
	$groupe_libelle = $data['libelle_groupe'];
	$responsable = $data['resp_groupe'];
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
	$header_aut = "" ;

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

	// on affiche les résultats 
	if ($id_groupe!=$ancien_groupe) {
		// compter les totaux pour ce groupe et les retards
		$sqlcount = "SELECT count(pret_idexpl) as combien , IF(pret_retour>=curdate(),0,1) as retard ";
		$sqlcount .= "FROM exemplaires, empr, pret, empr_groupe, groupe "; 
		$sqlcount .= "WHERE pret.pret_idempr = empr.id_empr AND pret.pret_idexpl = exemplaires.expl_id AND empr_groupe.empr_id = empr.id_empr AND groupe.id_groupe = empr_groupe.groupe_id and id_groupe=$id_groupe group by retard order by retard ";
		$reqcount = mysql_query($sqlcount) or die(mysql_error()."<br />".$sqlcount);
		$nbok=0;
		$nbretard=0;
		while ($datacount = mysql_fetch_object($reqcount)) { 
			if ($datacount->retard==0) $nbok=$datacount->combien;
			if ($datacount->retard==1) $nbretard=$datacount->combien;
		}
		$retard_sur_total = str_replace ("!!nb_retards!!",$nbretard*1,$msg[n_retards_sur_total_de]);
		$retard_sur_total = str_replace ("!!nb_total!!",($nbretard+$nbok)*1,$retard_sur_total);
		
		echo "\r\n<tr class='group_title'><td colspan=5><input type='checkbox' class='checkbox' name='coch_groupe[]' value='".$id_groupe."'>";
		echo "<b>".$groupe_libelle."</b></td>
				<td colspan=3>".htmlentities($retard_sur_total, ENT_QUOTES, $charset)."</td>";
		
		switch ($sub) {
			case "ppargroupe":
				$imprime_click = "onclick=\"openPopUp('./pdf.php?pdfdoc=liste_pret_groupe&id_groupe=$id_groupe', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) \"";
 				echo "\n<td align=center><a href=\"#\" ".$imprime_click."><img src=\"./images/new.gif\" title=\"".$message."\" alt=\"".$message."\" border=\"0\"></a>\n";
		
				//mail responsable
				$sql1="";
				$sql1 .= "SELECT empr_mail FROM empr WHERE empr.id_empr=".$responsable."";
				$req1= mysql_query($sql1);
				$result=mysql_fetch_array($req1);
				$mail_responsable=$result['empr_mail'];
		
		
				if ($mail_responsable) {
					$mail_click = "onclick=\"if (confirm('".$msg["mail_retard_confirm"]."')) {openPopUp('./mail.php?type_mail=mail_prets&id_groupe=$id_groupe', 'mail', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');} return(false) \"";
					echo "<a href=\"#\" ".$mail_click."><img src=\"./images/mail.png\" title=\"".$msg[mail_retard]."\" alt=\"".$msg[mail_retard]."\" border=\"0\"></a>"; 
				}				
			break;			
			case "rpargroupe":
				$imprime_click = "onclick=\"openPopUp('./pdf.php?pdfdoc=lettre_retard_groupe&id_groupe=$id_groupe', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) \"";
 				echo "\n<td align=center><a href=\"#\" ".$imprime_click."><img src=\"./images/new.gif\" title=\"".$message."\" alt=\"".$message."\" border=\"0\"></a>\n";
		
				//mail responsable
				$sql1="";
				$sql1 .= "SELECT empr_mail FROM empr WHERE empr.id_empr=".$responsable."";
				$req1= mysql_query($sql1);
				$result=mysql_fetch_array($req1);
				$mail_responsable=$result['empr_mail'];
		
				if ($mail_responsable) {
					$mail_click = "onclick=\"if (confirm('".$msg["mail_retard_confirm"]."')) {openPopUp('./mail.php?type_mail=mail_retard_groupe&id_groupe=$id_groupe', 'mail', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');} return(false) \"";
					echo "<a href=\"#\" ".$mail_click."><img src=\"./images/mail.png\" title=\"".$msg[mail_retard]."\" alt=\"".$msg[mail_retard]."\" border=\"0\"></a>"; 
				}
			break;
			default:
			
			break;
		}
		
		echo "</td>";
		echo "</tr>";
		
	}

	if($retard || ($sub=="ppargroupe") ){
		if ($retard) $tit_color="color='RED'";				
		else $tit_color="";
			
		if ($odd_even==0) {			
			$pair_impair = "odd";
			$odd_even=1;
		} else if ($odd_even==1) {				
			$pair_impair = "even";
			$odd_even=0;
		}
		
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"";
		echo "\r\n<tr class='$pair_impair' $tr_javascript>";
		echo "<td>&nbsp;</td>";	  
		
		if (SESSrights & CIRCULATION_AUTH) echo "<td><a href=\"./circ.php?categ=visu_ex&form_cb_expl=".$id_expl."\">".$id_expl."</a></td>";
			else echo "<td>".$id_expl."</td>";
	
		echo "<td>".$cote_expl."</td>";	
		
		if (SESSrights & CATALOGAGE_AUTH) {
			if ($data['expl_notice']) echo "<td><a href='./catalog.php?categ=isbd&id=".$data['expl_notice']."'><font $tit_color><b>".$titre."</b></font></a></td>"; // notice de monographie
			elseif ($data['expl_bulletin']) echo "<td><a href='./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$data['expl_bulletin']."'><font $tit_color><b>".$titre."</b></font></a></td>"; // notice de bulletin
			else echo "<td><font $tit_color><b>".$titre."</b></font></td>";
		} else echo "<td><font $tit_color><b>".$titre."</b></font></td>";    
			echo "<td><font $tit_color>".$auteur."</font></td>";    
			echo "<td><a href=\"./circ.php?categ=pret&form_cb=".rawurlencode($empr_cb)."\">".$empr_nom.", ".$empr_prenom."</a></td>"; 
			echo "<td>".$aff_pret_date."</td>"; 
			echo "<td><font $tit_color><b>".$aff_pret_retour."</font></b></td>";
		/* test de date de retour dépassée */
	
		switch ($sub) {
			case "ppargroupe":
				if ($retard) {
					$imprime_click = "onclick=\"openPopUp('./pdf.php?pdfdoc=liste_pret&cb_doc=$id_expl&id_empr=$id_empr', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) \"";
		 			echo "\n<td align=center><a href=\"#\" ".$imprime_click."><img src=\"./images/new.gif\" title=\"".$msg['prets_en_cours']."\" alt=\"".$msg['prets_en_cours']."\" border=\"0\"></a>\n";
					$mail_click = "onclick=\"if (confirm('".$msg["mail_retard_confirm"]."')) {openPopUp('./mail.php?type_mail=mail_prets&cb_doc=$id_expl&id_empr=$id_empr', 'mail', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes'); } return(false) \"";
					if (($empr_mail)&&($biblio_email)) echo "<a href=\"#\" ".$mail_click."><img src=\"./images/mail.png\" title=\"".$msg[mail_retard]."\" alt=\"".$msg[mail_retard]."\" border=\"0\"></a>";
				} else {
					echo "</td><td>&nbsp;</td>";
				} 
			break;
			case "rpargroupe":
				
				if ($retard) {
					$imprime_click = "onclick=\"openPopUp('./pdf.php?pdfdoc=lettre_retard&cb_doc=$id_expl&id_empr=$id_empr', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) \"";
		 			echo "\n<td align=center><a href=\"#\" ".$imprime_click."><img src=\"./images/new.gif\" title=\"".$msg['lettre_retard']."\" alt=\"".$msg['lettre_retard']."\" border=\"0\"></a>\n";
					$mail_click = "onclick=\"if (confirm('".$msg["mail_retard_confirm"]."')) {openPopUp('./mail.php?type_mail=mail_retard&cb_doc=$id_expl&id_empr=$id_empr', 'mail', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes'); } return(false) \"";
					if (($empr_mail)&&($biblio_email)) echo "<a href=\"#\" ".$mail_click."><img src=\"./images/mail.png\" title=\"".$msg[mail_retard]."\" alt=\"".$msg[mail_retard]."\" border=\"0\"></a>"; 
				} else {
					echo "</td><td>&nbsp;</td>";
				}
			break;
			default:
			break;
		}
		echo "</tr>\n";
	}
	$ancien_groupe=$id_groupe;
}

echo "</table></form>";

$bouton_imprime_tout ="" ;
switch($sub) {
	case "ppargroupe" :
		$bouton_imprime_tout = "<input type='button' class='bouton_small' value='".$msg['imprimer_liste_prets_groupes']."' title='".$msg['imprimer_liste_prets_groupes']."' onclick=\"if (verifCheckboxes('cases_a_cocher','coch_groupe')) { openPopUp('', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); document.forms['cases_a_cocher'].target='lettre'; document.forms['cases_a_cocher'].submit(); return(false) } \" >";
		break;
	case "rpargroupe" :
		$bouton_imprime_tout = "<input type='button' class='bouton_small' value='".$msg['lettres_relance_groupe']."' title='".$msg['lettres_relance_groupe']."' onclick=\"if (verifCheckboxes('cases_a_cocher','coch_groupe')) { openPopUp('', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); document.forms['cases_a_cocher'].target='lettre'; document.forms['cases_a_cocher'].submit(); return(false) }\" >";
		break;
	default :
		break;
}
	
//LIENS PAGE SUIVANTE et PAGE PRECEDENTE
// si le nombre de page n'est pas 0 et si la variable numero_page n'est pas définie
// dans cette condition, la variable numero_page est incrémenté et est inférieure à $nombre 
if( $nbpages_arrondi != 0 && empty($numero_page)) {
 	$navpage  ='< '.$msg[48].' | <a href="'.$page.'?categ='.$categ.'&sub='.$sub.'&limite_page='.$limite_page;
 	$navpage .= '&numero_page=1">'.$msg[49].' ></a>'; // on passe la variable numero page à 1
	} elseif ($nbpages_arrondi !='0' && isset($numero_page) && $numero_page < $nbpages_arrondi) {
		$suivant = $numero_page + 1; // on ajoute 1 au numero de page en cours 
		$precedent = $numero_page - 1;
		$navpage .= '<a href="'.$page.'?categ='.$categ.'&sub='.$sub.'&limite_page='.$limite_page.'&numero_page='.$precedent;
 		$navpage .= '">< '.$msg[48].'</a>'; // retour page précédente
		$navpage .= '<a href="'.$page.'?categ='.$categ.'&sub='.$sub.'&limite_page='.$limite_page.'&numero_page='.$suivant;
 		$navpage .= '">'.$msg[49].' ></a>'; //le lien pour les pages suivantes
		} // dans cette condition, le lien qui sera affiché lorsque le nombre de page a été atteint
		  elseif ( $nbpages_arrondi !='0' && isset($numero_page) && $numero_page >= $nbpages_arrondi ) { 
			$precedent = $numero_page - 1;
			$navpage .= '<a href="'.$page.'?categ='.$categ.'&sub='.$sub.'&limite_page='.$limite_page.'&numero_page='.$precedent;
 			$navpage .= '">< '.$msg[48].'</a>'; // retour page précédente
			}

if ($bouton_imprime_tout) echo "
	<br />
	<form class='form-$current_module' action='' method='post'>
	$bouton_imprime_tout
	</form>";

echo "<br /><center>$navpage</center>" ;

mysql_free_result ($req); 

?>