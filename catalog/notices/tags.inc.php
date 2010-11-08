<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// © 2006 mental works / www.mental-works.com contact@mental-works.com
// 	complètement repris et corrigé par PMB Services 
// +-------------------------------------------------+
// $Id: tags.inc.php,v 1.13 2009-05-16 11:11:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des tags laisses par les lecteurs sur les notices
echo "<h1>".$msg[titre_tag]."</h1>";

if (!$nb_per_page) $nb_per_page=10;

//droits d'acces utilisateur/notice (modification)
$acces_jm='';
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_jm = $dom_1->getJoin($PMBuserid,8,'num_notice');
}

//action = VALIDER le tag
switch ($quoifaire) {
	case 'valider':
		for ($i=0 ; $i < sizeof($valid_id_tags) ; $i++) {
			$acces_m=1;
			if ($acces_jm) {
				$q = "select count(1) from tags $acces_jm where id_tag=".$valid_id_tags[$i];
				$r = mysql_query($q, $dbh);
				if(mysql_result($r,0,0)==0) {
					$acces_m=0;
				}
			}
			if ($acces_m!=0) {
				$requete="select libelle, num_notice, index_l from tags left join notices on notice_id=num_notice where id_tag=".$valid_id_tags[$i];
				$r=mysql_query($requete, $dbh) or die(mysql_error()." <br />".$requete);
				if (mysql_numrows($r)) {
					$loc = mysql_fetch_object($r);
					$tab_index_l=array();
					$tab_index_l = explode($pmb_keyword_sep,trim($loc->index_l));
					$tab_index_l[]=stripslashes($loc->libelle);
					for ($j=0 ; $j < sizeof($tab_index_l) ; $j++) {
						if (!$tab_index_l[$j]) {
							unset($tab_index_l[$j]);
						}
					}
					$index_l=addslashes(implode($pmb_keyword_sep,$tab_index_l));
					$index_matieres=" ".strip_empty_words($index_l)." ";
					
					$requete="update notices set index_l='".$index_l."',index_matieres='$index_matieres' where notice_id='".$loc->num_notice."'";
					mysql_query($requete, $dbh) or die(mysql_error()." <br />".$requete);
					
					notice::majNoticesGlobalIndex($loc->num_notice);
					notice::majNoticesMotsGlobalIndex($loc->num_notice);
					
					$requete="delete from tags where id_tag='".$valid_id_tags[$i]."'";
					mysql_query($requete, $dbh) or die(mysql_error()." <br />".$requete);
				}
			}
		}	
		break;
	case 'supprimer' :
		for ($i=0 ; $i < sizeof($valid_id_tags) ; $i++) {
			$acces_m=1;
			if ($acces_jm) {
				$q = "select count(1) from tags $acces_jm where id_tag=".$valid_id_tags[$i];
				$r = mysql_query($q, $dbh);
				if(mysql_result($r,0,0)==0) {
					$acces_m=0;
				}
			}
			if ($acces_m!=0) {
				$rqt = "delete from tags where id_tag='".$valid_id_tags[$i]."' ";
				mysql_query ($rqt, $dbh) ;
			}
		}	
		break;
	default:
		break;
}

echo "<form class='form-catalog' method='post' id='validation_tags' name='validation_tags' >
		<h3>".$msg[tag_titre_form]."</h3>
		<div class='form-contenu'>";

//variables
if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;
$url_base = "./catalog.php?categ=tags";

//requete d'affichage des notices et des tags
$requete="select 1 from tags ";
$r = mysql_query($requete, $dbh) or die (mysql_error()." <br /><br />".$requete);
$nbr_lignes = mysql_num_rows($r);

$requete="select id_tag,libelle,num_notice,user_code,dateajout,index_l,DATE_FORMAT(dateajout,'".$msg[format_date]."') as ladate, 
			empr_login, empr_nom, empr_prenom,
			niveau_biblio, niveau_biblio, notice_id
			from tags left join empr on empr_login=user_code 
		  			left join notices on notice_id=num_notice
		  order by index_serie, tnvol, index_sew ,dateAjout desc 
		  limit $debut,$nb_per_page  ";

$r = mysql_query($requete, $dbh) or die (mysql_error()." <br /><br />".$requete);

if (mysql_numrows($r)) {

	//affichage des notices
	print $begin_result_liste;
	$res_final = "";
	$notice_id=0;
	while (($loc = mysql_fetch_object($r))) {
		if ($notice_id!=$loc->notice_id) {
			if ($notice_id!=0) $res_final .=  "</ul><br />" ;
			$notice_id=$loc->notice_id;
			$deb = 1 ;
			if($loc->niveau_biblio != 's' && $loc->niveau_biblio != 'a') {
				// notice de monographie
				$display = new mono_display($loc->notice_id, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1);
				$res_final .= pmb_bidi($display->result);
			} else {
				// on a affaire à un périodique
				$serial = new serial_display($loc->notice_id, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum_serial, 0, 0, 1, 1, true, 1 );
				$res_final .= pmb_bidi($serial->result);
			}
			$res_final .=  "<ul>" ;
		} else $deb = 0 ;
		
		$res_final .=  "<li><b>$loc->libelle</b><div class='left'>" ;
		$res_final .=  $loc->ladate."  $loc->empr_prenom $loc->empr_nom </div>
			    <div class='right'>
			    	<input type='checkbox' name='valid_id_tags[]' id='valid_id_tags[]' value='$loc->id_tag' />
			    	</div>";
		if ($loc->index_l) $res_final .=  "<div class='row'>$msg[tag_deja_tags] <b>$loc->index_l</b></div>";
		$res_final .=  "</li>";
	}
	$res_final .=  "</ul><br />" ;
	print $res_final ;
}

print aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
echo "</div>";
echo "
		<div class='row'>
			<div class='left'>
				<input type='hidden' name='quoifaire' value='' />
				<input type='button' class='bouton' name='valider' value='".$msg[tag_bt_valider]."' onclick='this.form.quoifaire.value=\"valider\"; this.form.submit()' />&nbsp;
				<input type='button' class='bouton' name='supprimer' value='".$msg[tag_bt_supprimer]."' onclick='this.form.quoifaire.value=\"supprimer\"; this.form.submit()' />&nbsp;
				</div>
			<div class='right'>
				<input type='button' class='bouton' name='selectionner' value='".$msg[tag_bt_selectionner]."' onClick=\"setCheckboxes('validation_tags', 'valid_id_tags', true); return false;\" />&nbsp;
				</div>
			</div>
<div class='row'></div>
			</form>";
jscript_checkbox() ;


