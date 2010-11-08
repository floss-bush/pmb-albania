<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: all.inc.php,v 1.32 2010-08-19 07:35:07 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Récupération des variables postées, on en aura besoin pour les liens
$page=$_SERVER[SCRIPT_NAME];

echo"<form action='empr.php' method='post' name='FormEmpr'>";
echo"<input name='lvl' value='all' type='hidden'>";	
echo"<input name='prolonge_id' value='0' type='hidden'>";

// Si click bouton de prolongation, et prolongation autorisée 
if($prolonge_id>0 && $opac_pret_prolongation==1){
	//Il faut prolonger un livre
	
	$prolongation = TRUE;
	
	//on recupere les informations du pret 
	$query = "select cpt_prolongation, retour_initial, pret_date, pret_retour from pret where pret_idexpl=".$prolonge_id." limit 1";
	$result = mysql_query($query, $dbh);
	$data = mysql_fetch_array($result);
	$cpt_prolongation = $data['cpt_prolongation']; 
	$retour_initial =  $data['retour_initial'];
	$pret_date =  $data['pret_date'];
	$date_retour = $data['pret_retour'];

	$duree_prolongation = $opac_pret_duree_prolongation;
	
	// Limitation simple du pret
	if ($pmb_pret_restriction_prolongation==1) {

		$pret_nombre_prolongation = $pmb_pret_nombre_prolongation;
	
	} elseif($pmb_pret_restriction_prolongation==2) {
		
		// Limitation du pret par les quotas
		//Initialisation des quotas pour nombre de prolongations
		$qt = new quota("PROLONG_NMBR_QUOTA");
		//Tableau de passage des paramètres
		$struct["READER"] = $id_empr;
		$struct["EXPL"] = $prolonge_id;
		$pret_nombre_prolongation = $qt -> get_quota_value($struct);
	
		//Initialisation des quotas la durée de prolongations
		$qt = new quota("PROLONG_TIME_QUOTA");
		$struct["READER"] = $id_empr;
		$struct["EXPL"] = $prolonge_id;	
		$duree_prolongation = $qt -> get_quota_value($struct);	
	
	}
	
	$today = sql_value("SELECT CURRENT_DATE()");
	$date_prolongation = sql_value("SELECT DATE_ADD('$retour_initial', INTERVAL $duree_prolongation DAY)");
	$diff = sql_value("SELECT DATEDIFF('$retour_initial','$today')");
	
	if($diff<-$duree_prolongation || $diff>$duree_prolongation) {
		$prolongation = FALSE;
		echo "test prolongation faux<br />";		
	}
		
	if($prolongation==TRUE)	{		
		$cpt_prolongation++;
		// Memorisation de la nouvelle date de prolongation	
		$query = "update pret set cpt_prolongation='".$cpt_prolongation."', pret_retour='".$date_prolongation."' where pret_idexpl=".$prolonge_id;
		$result = mysql_query($query, $dbh);
	}	

}	
	
// REQUETE SQL

$sql = "SELECT notices_m.notice_id as num_notice_mono, bulletin_id, IF(pret_retour>sysdate(),0,1) as retard, expl_id," ;
$sql.= "date_format(pret_retour, '".$msg["format_date_sql"]."') as aff_pret_retour, pret_retour, "; 
$sql.= "date_format(pret_date, '".$msg["format_date_sql"]."') as aff_pret_date, " ;
$sql.= "trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if(mention_date, concat(' (',mention_date,')') ,if (date_date, concat(' (',date_format(date_date, '".$msg["format_date_sql"]."'),')') ,'')))) as tit, if(notices_m.notice_id, notices_m.notice_id, notices_s.notice_id) as not_id ";
$sql.= "FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) ";
$sql.= "        LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) ";
$sql.= "        LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), ";
$sql.= "        docs_type , pret, empr ";
$sql.= "WHERE expl_typdoc = idtyp_doc and pret_idexpl = expl_id  and empr.id_empr = pret.pret_idempr ";
$sql = $sql.$critere_requete;
$req = mysql_query($sql) or die("Erreur SQL !<br />".$sql."<br />".mysql_error()); 
$nb_elements = mysql_num_rows($req) ;

if ($lvl=="late") $class_aff_expl="class='liste-expl-empr-late'" ;
$class_aff_expl="class='liste-expl-empr-all'" ;

if ($nb_elements) {
	echo"<table $class_aff_expl width='100%'>";
	echo "<tr>" ;
	if ($lvl!="late") echo "<th><center>".$msg["empr_late"]."</center></th>" ;
	echo "<th>".$msg["title"]."</th>
		<th>".$msg["author"]."</th>
		<th><center>".$msg["date_loan"]."</center></th>
		<th><center>".$msg["date_back"]."</center></th>";
		
	if($opac_pret_prolongation==1 && $allow_prol) 
		echo "<th><center>".$msg["opac_titre_champ_prolongation"]."</center></th>";	
	echo "</tr>" ;
	$odd_even=1;
	while ($data = mysql_fetch_array($req)) { 
		$id_expl =$data['expl_cb'];
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
		
		if ($data['num_notice_mono']) $tr_javascript=" class='$pair_impair' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./index.php?lvl=notice_display&id=".$data['num_notice_mono']."&seule=1';\" style='cursor: pointer' ";
			else $tr_javascript=" class='$pair_impair' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./index.php?lvl=bulletin_display&id=".$data['bulletin_id']."';\" style='cursor: pointer' ";
		$deb_ligne = "<tr $tr_javascript>";
		echo $deb_ligne ;
		/* test de date de retour dépassée */
		if ($lvl!="late") 
			if ($data['retard']) echo "<td class='expl-empr-retard'><center><b>&times;</b></center></td>";
				else echo "<td>&nbsp;</td>";
		echo "<td>".$titre."</td>";    
		echo "<td>".$auteur."</td>";    
		echo "<td><center>".$data['aff_pret_date']."</center></td>"; 
			
		if ($data['retard']) echo "<td class='expl-empr-retard'><center>".$data['aff_pret_retour']."</center></td>";
			else echo "<td><center>".$data['aff_pret_retour']."</center></td>";
		// Paramètre de l'opac $opac_pret_prolongation autorisant la gestion des prolongations
		if ($opac_pret_prolongation==1 && $allow_prol) {
			$prolongation=TRUE;
			$expl_id = $data['expl_id'] ;
			$query = "select cpt_prolongation,retour_initial, pret_date,pret_retour, expl_location from pret, exemplaires where expl_id=pret_idexpl and pret_idexpl='".$data['expl_id']."'";
			$result = mysql_query($query, $dbh);
			$data_expl = mysql_fetch_array($result);
			$cpt_prolongation = $data_expl['cpt_prolongation']; 
			$retour_initial =  $data_expl['retour_initial'];
			$pret_date =  $data_expl['pret_date'];
			$date_retour= $data_expl['pret_retour'];
			$cpt_prolongation++;
			
			$duree_prolongation=$opac_pret_duree_prolongation;	
			$today=sql_value("SELECT CURRENT_DATE()");
			if ($pmb_pret_restriction_prolongation==0) {
				// Aucune limitation des prolongations
				$prolongation=true;
				$duree_prolongation=$opac_pret_duree_prolongation;	
			} else if ($pmb_pret_restriction_prolongation>0) {
				$pret_nombre_prolongation=$pmb_pret_nombre_prolongation;
				if(($pmb_pret_restriction_prolongation==1) && ($cpt_prolongation>$pret_nombre_prolongation)) {
					// Limitation simple de la prolongation
					$prolongation=FALSE;
				} else if($pmb_pret_restriction_prolongation==2) {
					// Limitation du pret par les quotas
					//Initialisation des quotas pour nombre de prolongations
					$qt = new quota("PROLONG_NMBR_QUOTA");
					//Tableau de passage des paramètres
					$struct["READER"] = $id_empr;
					$struct["EXPL"] = $expl_id;						
					$pret_nombre_prolongation=$qt -> get_quota_value($struct);		

					if($cpt_prolongation>$pret_nombre_prolongation) $prolongation=FALSE;

					//Initialisation des quotas la durée de prolongations
					$qt = new quota("PROLONG_TIME_QUOTA");
					$struct["READER"] = $id_empr;
					$struct["EXPL"] = $expl_id;	
					$duree_prolongation=$qt -> get_quota_value($struct);	
				} // fin if gestion par quotas
			} // fin else if pmb_pret_restriction_prolongation>0

			$date_prolongation=sql_value("SELECT DATE_ADD('$retour_initial', INTERVAL $duree_prolongation DAY)");
			$diff=sql_value("SELECT DATEDIFF('$retour_initial','$today')");
			if($diff<-$duree_prolongation || $diff>$duree_prolongation) {
				$prolongation=FALSE;
			}
			
			$req_date_calendrier = "select date_ouverture from ouvertures where ouvert=1 and num_location='".$data_expl['expl_location']."' order by date_ouverture asc";
			$res_date_calendrier = mysql_query($req_date_calendrier);
			while(($date_calendrier = mysql_fetch_object($res_date_calendrier))){
				$ecart = sql_value("SELECT DATEDIFF('$date_calendrier->date_ouverture','$date_prolongation')");
				if($ecart >= 0 ){
					$date_prolongation = $date_calendrier->date_ouverture;
					break; 
				}
			}
				
			// Verif s'il y a des résa et plus d'exemplaire dispo
			if ($prolongation==TRUE) {		
				if($data['num_notice_mono'])	$data['bulletin_id']=0; 
				else	$data['num_notice_mono']=0;
				// chercher le premier (par ordre de rang, donc de date de début de résa, non validé
				$rqt = 	"SELECT count(1) FROM resa 
						WHERE resa_idnotice='".$data['num_notice_mono']."' AND resa_idbulletin='".$data['bulletin_id']."' 
						AND resa_cb='' AND resa_date_fin='0000-00-00' ";	
				$res= mysql_query($rqt);
				$nbresa = mysql_result($res, 0, 0);
				if($nbresa){						
					$rqt="SELECT count(1) FROM exemplaires, docs_statut WHERE expl_statut=idstatut and pret_flag=1 AND expl_notice=".$data['num_notice_mono']." AND expl_bulletin=".$data['bulletin_id']." "; 
					$res= mysql_query($rqt);
					$nbexpl = mysql_result($res, 0, 0);
					$rqt="SELECT count(1) FROM pret,exemplaires WHERE pret_idexpl=expl_id AND expl_notice=".$data['num_notice_mono']." AND expl_bulletin=".$data['bulletin_id']." "; 
					$res= mysql_query($rqt);
					$nbexpl_en_pret = mysql_result($res, 0, 0);
					if(($nbexpl-$nbexpl_en_pret) < $nbresa){
						$prolongation=FALSE;
					}
				}					
			}				
						
			// Proposer le bouton prolongation
			if ($prolongation==TRUE) {	
				// Mettre au format affichable
				$rqt_date = "select date_format('$date_prolongation', '".$msg["format_date_sql"]."') as aff_date_prolongation ";
				$resultatdate = mysql_query($rqt_date);
				$res = mysql_fetch_object($resultatdate) ;
				$aff_date_prolongation= $res->aff_date_prolongation;
				// Bouton de prolongation
				if (sql_value("SELECT DATEDIFF('$date_retour','$date_prolongation')") != 0) {
					$js="onmousedown=\"if (event) e=event; else e=window.event; if (e.target) elt=e.target; else elt=e.srcElement; e.cancelBubble = true; if (e.stopPropagation) e.stopPropagation(); return false;\" "; //if (elt.nodeName=='A') document.location='./empr.php?prolongation=$aff_date_prolongation&prolonge_id=$expl_id&lvl=$lvl'; return false;\" ";
					echo "<td><center><a href='./empr.php?prolongation=$aff_date_prolongation&prolonge_id=$expl_id&tab=loan&lvl=$lvl' $js >$aff_date_prolongation</a></center></td>";
				} else {
					$js="onmousedown=\"if (event) e=event; else e=window.event; if (e.target) elt=e.target; else elt=e.srcElement; e.cancelBubble = true; if (e.stopPropagation) e.stopPropagation();return false;\"";
					echo "<td style='cursor: default'  $js ><center>&nbsp;</center></td>";
				}
			} else {
				$js= "onmousedown=\"if (event) e=event; else e=window.event; if (e.target) elt=e.target; else elt=e.srcElement; e.cancelBubble = true; if (e.stopPropagation) e.stopPropagation();return false;\"";
				echo "<td style='cursor: default' $js ><center>&nbsp;</center></td>";
			}
	
		} // fin if prolongeable	
		echo "</tr>\n";

	} // fin du while
	
	echo "</table>";
	
	
	echo"</form>"; 
} else { // fin du if nb_elements
	switch($lvl) {
		case 'all':	
			print $msg["empr_no_loan"] ;
			break;
		case 'late':
			print $msg["empr_no_late"] ;
			break;
	}		
}

mysql_free_result ($req); 

function sql_value($rqt) {
	$result=mysql_query($rqt);
	$row = mysql_fetch_row($result);
	return $row[0];
}
