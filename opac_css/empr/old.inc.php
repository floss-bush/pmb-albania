<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if (!$allow_loan_hist) die();

//Récupération des variables postées, on en aura besoin pour les liens
$page=$_SERVER[SCRIPT_NAME];

// REQUETE SQL

if ($opac_empr_hist_nb_max) $limit=" LIMIT 0, $opac_empr_hist_nb_max ";
if ($opac_empr_hist_nb_jour_max) $restrict_date=" date_add(pret_archive.arc_fin, INTERVAL $opac_empr_hist_nb_jour_max day)>=sysdate() AND ";

$sql = "SELECT arc_expl_notice, arc_expl_bulletin, " ;
$sql.= "group_concat(distinct date_format(arc_debut, '".$msg["format_date_sql"]."') separator '<br />') as aff_pret_debut, "; 
$sql.= "trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if(mention_date, concat(' (',mention_date,')') ,if (date_date, concat(' (',date_format(date_date, '".$msg["format_date_sql"]."'),')') ,'')))) as tit, if(notices_m.notice_id, notices_m.notice_id, notices_s.notice_id) as not_id ";
$sql.= "FROM (((pret_archive LEFT JOIN notices AS notices_m ON arc_expl_notice = notices_m.notice_id ) ";
$sql.= "        LEFT JOIN bulletins ON arc_expl_bulletin = bulletins.bulletin_id) ";
$sql.= "        LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), ";
$sql.= "        empr ";
$sql.= "WHERE $restrict_date empr.id_empr = arc_id_empr and arc_id_empr='$id_empr' ";
$sql.= "group by arc_expl_notice, arc_expl_bulletin, tit, not_id ";
$sql.= "having tit is not null and tit <> '' ";
$sql.= "order by arc_debut desc $limit ";

$req = mysql_query($sql) or die("Erreur SQL !<br />".$sql."<br />".mysql_error()); 
$nb_elements = mysql_num_rows($req) ;

if ($nb_elements) {
	echo"<table $class_aff_expl width='100%'>";
	echo "<tr>" ;
	echo "<th>".$msg["title"]."</th>
		<th>".$msg["author"]."</th>
		<th><center>".$msg["date_loan"]."</center></th>";
	echo "</tr>" ;
	$odd_even=1;
	while ($data = mysql_fetch_array($req)) { 
		$titre = $data['tit'];
		
		// **********
		$responsab = array("responsabilites" => array(),"auteurs" => array());  // les auteurs
		$responsab = get_notice_authors($data['not_id']) ;
		
		//$this->responsabilites
		$as = array_search ("0", $responsab["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $responsab["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			$mention_resp = $auteur->isbd_entry;
		} else {
			$as = array_keys ($responsab["responsabilites"], "1" ) ;
			for ($i = 0 ; $i < count($as) ; $i++) {
				$indice = $as[$i] ;
				$auteur_1 = $responsab["auteurs"][$indice] ;
				$auteur = new auteur($auteur_1["id"]);
				$aut1_libelle[]= $auteur->isbd_entry;
			}
			$mention_resp = implode (", ",$aut1_libelle) ;
			$aut1_libelle = array();
		}
		
		$mention_resp ? $auteur = $mention_resp : $auteur="";
			
		// on affiche les résultats 
		if ($odd_even==0) {
			$pair_impair="odd";
			$odd_even=1;
			} else if ($odd_even==1) {
				$pair_impair="even";
				$odd_even=0;
				}
		if ($data['arc_expl_notice']) $tr_javascript=" class='$pair_impair' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./index.php?lvl=notice_display&id=".$data['not_id']."&seule=1';\" style='cursor: pointer' ";
			else $tr_javascript=" class='$pair_impair' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./index.php?lvl=bulletin_display&id=".$data['arc_expl_bulletin']."';\" style='cursor: pointer' ";
		$deb_ligne = "<tr $tr_javascript>";
		echo $deb_ligne ;
		echo "<td>".$titre."</td>";    
		echo "<td>".$auteur."</td>";    
		echo "<td><center>".$data['aff_pret_debut']."</center></td>"; 
			
		echo "</tr>\n";
		}
	
	echo "</table>";
	}