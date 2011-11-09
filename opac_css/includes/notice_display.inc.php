<?php
// +-------------------------------------------------+
// Â© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_display.inc.php,v 1.62.2.3 2011-10-07 09:59:08 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$libelle = $msg[270];

require_once($base_path.'/includes/templates/notice_display.tpl.php');
require_once($base_path.'/includes/explnum.inc.php');
require_once($base_path.'/classes/notice_affichage.class.php');
require_once($base_path.'/includes/bul_list_func.inc.php');
require_once($base_path.'/classes/upload_folder.class.php');

print $notice_display_header;
if ($ref) {
	$EAN = '';
	$isbn = '';
	$code = '';
	
	if(isEAN($ref)) {
		// la saisie est un EAN -> on tente de le formater en ISBN
		$EAN=$ref;
		$isbn = EANtoISBN($ref);
		// si échec, on prend l'EAN comme il vient
		if(!$isbn) 
			$code = str_replace("*","%",$ref);
		else {
			$code=$isbn;
			$code10=formatISBN($code,10);
		}
	} else {
		if(isISBN($ref)) {
			// si la saisie est un ISBN
			$isbn = formatISBN($ref);
			// si échec, ISBN erroné on le prend sous cette forme
			if(!$isbn) 
				$code = str_replace("*","%",$ref);
			else {
				$code10=$isbn ;
				$code=formatISBN($code10,13);
			}
		} else {
			// ce n'est rien de tout ça, on prend la saisie telle quelle
			$code = str_replace("*","%",$ref);
		}
	}
				
	if ($EAN && $isbn) {
		// cas des EAN purs : constitution de la requête
		$requete = "SELECT notice_id FROM notices  where code in ('$code','$EAN'".($code10?",'$code10'":"").") limit 1";
	} elseif ($isbn) {
		// recherche d'un isbn
		$requete = "SELECT notice_id FROM notices where code in ('$code'".($code10?",'$code10'":"").") limit 1";
	} elseif ($code) {
		$requete = "SELECT notice_id FROM notices where code like '$code' limit 1";
	} 
	$res = mysql_query($requete, $dbh);
	if(mysql_num_rows($res)) {
		$id=mysql_result($res,0,0);
	}
}
$id+=0;
//droits d'acces emprunteur/notice
$acces_v=TRUE;
if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_2= $ac->setDomain(2);
	$acces_v = $dom_2->getRights($_SESSION['id_empr_session'],$id,4);
} else {
	$requete = "SELECT notice_visible_opac, expl_visible_opac, notice_visible_opac_abon, expl_visible_opac_abon, explnum_visible_opac, explnum_visible_opac_abon FROM notices, notice_statut WHERE notice_id ='".$id."' and id_notice_statut=statut ";
	$myQuery = mysql_query($requete, $dbh);
	if(mysql_num_rows($myQuery)) {
		$statut_temp = mysql_fetch_object($myQuery);
		if(!$statut_temp->notice_visible_opac)	$acces_v=FALSE;
		if($statut_temp->notice_visible_opac_abon && !$_SESSION['id_empr_session'])	$acces_v=FALSE;
	} else 	$acces_v=FALSE;
}
if($acces_v) {
	global $pmb_logs_activate;
	if($pmb_logs_activate) recup_notice_infos($id);
	$requete = "SELECT notice_id, niveau_biblio,typdoc,opac_visible_bulletinage FROM notices WHERE notice_id='$id' LIMIT 1";	
	$res = @mysql_query($requete, $dbh);
	while (($obj=mysql_fetch_object($res))) {
	
		if ($mode_phototeque) {
			// Traitement exemplaire numerique	
			$requete = "SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, explnum_vignette, explnum_nomfichier, explnum_extfichier FROM explnum WHERE ";
			$requete .= "explnum_notice='$id' ";
			$requete .= " order by explnum_id LIMIT 1";
			$resultat = mysql_query($requete, $dbh) or die ($requete." ".mysql_error());
			$nb_ex = mysql_num_rows($resultat);
			if ($nb_ex) {
				// $explnum=mysql_result($resultat,0,0);
				$explnumobj=mysql_fetch_object($resultat); 
				if ($explnumobj->explnum_url) print "<center><img width='$opac_photo_mean_size_x' src=\"".$explnumobj->explnum_url."\"/></center><br />";
				else{
					//répertoire d'upload ou stockage en base, le traitement reste identique...
					print "<center><img src=\"vign_middle.php?explnum_id=".$explnumobj->explnum_id."\"/></center><br />";
				}
				if ($opac_photo_show_form) print "<center><a href='index.php?lvl=doc_command&id=$id&mode_phototeque=1'>".htmlentities($msg["command_phototeque_command_command"],ENT_QUOTES,$charset)."</a></center>";
			}
			$hide_explnum=1;
		}else{
			$req = "select explnum_id from explnum left join bulletins on num_notice = $id where explnum_notice = $id or explnum_bulletin = bulletin_id";
			$resultat = mysql_query($req, $dbh) or die ($req." ".mysql_error());
			$nb_ex = mysql_num_rows($resultat);
			if($opac_visionneuse_allow && $nb_ex){
				//print "&nbsp;&nbsp;&nbsp;".$link_to_visionneuse;
				print $sendToVisionneuseNoticeDisplay;
			}	
		}
	
		$id = $obj->notice_id ;
		$opac_notices_depliable = 0;
		switch($obj->niveau_biblio) {
			case "s":
				if(!$obj->opac_visible_bulletinage) {
					print pmb_bidi(aff_notice($id));
					break;
				}	

				//Recherche dans les numéros
				$start_num = ${bull_num_deb_."".$id};
				$end_num = ${bull_num_end_."".$id};
				if($f_bull_deb_id && $f_bull_end_id){
					$restrict_num = compare_date($f_bull_deb_id,$f_bull_end_id);
					$restrict_date = ""; 
				} else if($f_bull_deb_id && !$f_bull_end_id){
					$restrict_num = compare_date($f_bull_deb_id);
					$restrict_date = ""; 
				} else if(!$f_bull_deb_id && $f_bull_end_id){
					$restrict_num = compare_date("",$f_bull_end_id);
					$restrict_date = ""; 
				} else if((!$f_bull_deb_id) && (!$f_bull_end_id)){					
					if($start_num && !$end_num){
						$restrict_num = " and bulletin_numero like '%".$start_num."%' ";
						$restrict_date = ""; 
					} else if(!$start_num && $end_num){
						$restrict_num = "and bulletin_numero like '%".$end_num."%' ";
						$restrict_date = ""; 
					} else if($start_num && $end_num){
						$restrict_num = "and bulletin_numero like '%".$start_num."%' ";
						$restrict_date = ""; 
					}
				}
				
				// Recherche dans les dates et libellés de période
				if(!$restrict_num) 
					$restrict_date = compare_date($bull_date_start,$bull_date_end);
													
				// nombre de références par pages (12 par défaut)
				if (!isset($opac_bull_results_per_page)) $opac_bull_results_per_page=12; 
				if(!$page) $page=1;
				$debut =($page-1)*$opac_bull_results_per_page;
				$limiter = " LIMIT $debut,$opac_bull_results_per_page";
				//affichage
				print pmb_bidi(aff_notice($id));
								
				//Recherche par numéro
				$num_field_start = "
					<input type='hidden' name='f_bull_deb_id' id='f_bull_deb_id' />
					<input id='bull_num_deb_$id' name='bull_num_deb_$id' type='text' size='10' completion='bull_num' autfield='f_bull_deb_id' value='".$start_num."'>";
				$numfield_end = "
					<input type='hidden' name='f_bull_end_id' id='f_bull_end_id' />
					<input id='bull_num_end_$id' name='bull_num_end_$id' type='text' size='10' completion='bull_num' autfield='f_bull_end_id' value='".$end_num."'>";
				
				//Recherche par date
				$deb_value = str_replace("-","",$bull_date_start);
				$fin_value = str_replace("-","",$bull_date_end);
				$date_deb_value = ($deb_value ? formatdate($deb_value) : '...');
				$date_fin_value = ($fin_value ? formatdate($fin_value) : '...');
				$date_debut = "
					<input type='hidden' id='bull_date_start' name='bull_date_start' value='$bull_date_start'/>
					<input type='button' class='bouton' id='date_deb_btn' name='date_deb_btn'  value='".$date_deb_value."' onClick=\"window.open('./select.php?what=calendrier&caller=form_values&date_caller=&param1=bull_date_start&param2=date_deb_btn&auto_submit=NO&date_anterieure=YES', 'date_fin', 'width=250,height=300,toolbar=no,dependent=yes,resizable=yes')\"/>
					<input type='button' class='bouton' name='del' value='X' onclick='this.form.date_deb_btn.value=\"...\";this.form.bull_date_start.value=\"\";' />
				";
				$date_fin = "<input type='hidden' id='bull_date_end' name='bull_date_end' value='$bull_date_end' />
						<input type='button' class='bouton' id='date_fin_btn' name='date_fin_btn' value='".$date_fin_value."' onClick=\"window.open('./select.php?what=calendrier&caller=form_values&date_caller=&param1=bull_date_end&param2=date_fin_btn&auto_submit=NO&date_anterieure=YES', 'date_fin', 'width=250,height=300,toolbar=no,dependent=yes,resizable=yes')\"/>
						<input type='button' class='bouton' name='del' value='X' onclick='this.form.date_fin_btn.value=\"...\";this.form.bull_date_end.value=\"\";' />
				";
							
				$tableau = "
				<a name='tab_bulletin'></a>
				<h3>$msg[perio_list_bulletins]</h3>
				<div id='form_search_bull'>
					<div class='row'></div>\n
						<script src='./includes/javascript/ajax.js'></script>
						<form name=\"form_values\" action=\"./index.php?lvl=notice_display&id=$id\" method=\"post\" onsubmit=\"if (document.getElementById('onglet_isbd$id').className=='isbd_public_active') document.form_values.premier.value='ISBD'; else document.form_values.premier.value='PUBLIC';document.form_values.page.value=1;\">\n
							<input type=\"hidden\" name=\"premier\" value=\"\">\n
							<input type=\"hidden\" name=\"page\" value=\"$page\">\n
							<table>
								<tr>
									<td align='left' rowspan=2><strong>".$msg["search_bull"]."&nbsp;:&nbsp;</strong></td>
									<td align='right'><strong>".$msg["search_per_bull_num"]." : ".$msg["search_bull_start"]."</strong></td>
									<td >$num_field_start</td>						
									<td ><strong>".$msg["search_bull_end"]."</strong> $numfield_end</td>
								</tr>
								<tr>
									<td align='right'><strong>".$msg["search_per_bull_date"]." : ".$msg["search_bull_start"]."</strong></td>
									<td>$date_debut</td>
									<td><strong>".$msg["search_bull_end"]."</strong> $date_fin</td>
									<td>&nbsp;&nbsp;<input type='button' value='".$msg["142"]."' onclick='submit();'></td>
								</tr>
							</table>
						</form>
					<div class='row'></div><br />
				</div>\n";
				print $tableau;
				
				//quel affichage de notice il faut utiliser (Public, ISBD) (valeur postée)
				if ($premier) 
					print "<script> show_what('$premier','$id'); </script>";
				
				print "<script type='text/javascript'>ajax_parse_dom();</script>";	
				// A EXTERNALISER ENSUITE DANS un bulletin_list.inc.php
				/*$requete="SELECT bulletins.*,count(explnum_id) as nbexplnum FROM bulletins LEFT JOIN explnum ON explnum_bulletin = bulletin_id AND explnum_bulletin!=0 where bulletin_id in(
				SELECT bulletin_id FROM bulletins WHERE bulletin_notice='$id' $restrict_num $restrict_date and num_notice=0
				) or bulletin_id in(
				SELECT bulletin_id FROM bulletins,notice_statut, notices WHERE bulletin_notice='$id' $restrict_num $restrict_date 
				and notice_id=num_notice
				and statut=id_notice_statut 
				and((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")) 
				GROUP BY bulletins.bulletin_id ";*/
				$requete="SELECT bulletins . * , COUNT( explnum_id ) AS nbexplnum
				FROM bulletins 
				LEFT JOIN notices ON (num_notice=notice_id)
				LEFT JOIN notice_statut ON (statut = id_notice_statut AND ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))
				LEFT JOIN explnum ON bulletin_id = explnum_bulletin AND explnum_bulletin!=0
				WHERE bulletin_notice =  '$id' $restrict_num $restrict_date
				GROUP BY bulletins.bulletin_id";
				
				$rescount1=mysql_query($requete);
				$count1=mysql_num_rows($rescount1);
				
				//si on recherche par date ou par numéro, le résultat sera trié par ordre croissant
				if (($restrict_num)||($restrict_date)) $requete.=" ORDER BY date_date, bulletin_numero ";
				else $requete.=" ORDER BY date_date DESC, bulletin_numero DESC";
				$requete.=$limiter;
				$res = @mysql_query($requete, $dbh);
				$count=mysql_num_rows($res);
				if ($count) {
					if ($opac_fonction_affichage_liste_bull) eval("\$opac_fonction_affichage_liste_bull (\$res);");
					else affichage_liste_bulletins_normale($res); 
				} else print "<br /><strong>".$msg["bull_no_found"]."</strong>";
				print "<br /><br />";
				// constitution des liens
				if (!$count1) $count1=$count;
				$nbepages = ceil($count1/$opac_bull_results_per_page);
				$url_page = "javascript:if (document.getElementById(\"onglet_isbd$id\")) if (document.getElementById(\"onglet_isbd$id\").className==\"isbd_public_active\") document.form_values.premier.value=\"ISBD\"; else document.form_values.premier.value=\"PUBLIC\"; document.form_values.page.value=!!page!!; document.form_values.submit()";
				$action = "javascript:if (document.getElementById(\"onglet_isbd$id\")) if (document.getElementById(\"onglet_isbd$id\").className==\"isbd_public_active\") document.form_values.premier.value=\"ISBD\"; else document.form_values.premier.value=\"PUBLIC\"; document.form_values.page.value=document.form.page.value; document.form_values.submit()";
				if ($count) $form="<div class='row'></div><br />\n<center>".printnavbar($page, $nbepages, $url_page,$action)."</center>";
				break;
			case "a":
				print pmb_bidi(aff_notice($id));
				break;	
			case "m":
			default :
				//$l_typdoc=$obj->typdoc;
				print pmb_bidi("<br />".aff_notice($id)) ;
				break;
		}
	}
}

/**
 * Récupère les infos de la notice
 */
function recup_notice_infos($id_notice){
	
	global $infos_notice, $infos_expl;
	
	$rqt="select notice_id, typdoc, niveau_biblio, index_l, libelle_categorie, name_pclass, indexint_name 
		from notices n 
		left join notices_categories nc on nc.notcateg_notice=n.notice_id 
		left join categories c on nc.num_noeud=c.num_noeud 
		left join indexint i on n.indexint=i.indexint_id 
		left join pclassement pc on i.num_pclass=pc.id_pclass
		where notice_id='".$id_notice."'";
	$res_noti = mysql_query($rqt);
	while(($noti=mysql_fetch_array($res_noti))){		
		$infos_notice=$noti;
		$rqt_expl = " select section_libelle, location_libelle, statut_libelle, codestat_libelle, expl_date_depot, expl_date_retour, tdoc_libelle 
					from exemplaires e
					left join docs_codestat co on e.expl_codestat = co.idcode
					left join docs_location dl on e.expl_location=dl.idlocation
					left join docs_section ds on ds.idsection=e.expl_section
					left join docs_statut dst on e.expl_statut=dst.idstatut 
					left join docs_type dt on dt.idtyp_doc=e.expl_typdoc
					where expl_notice='".$id_notice."'";
		$res_expl=mysql_query($rqt_expl);
		while(($expl = mysql_fetch_array($res_expl))){
			$infos_expl[]=$expl;
		}
	}
}


function compare_date($date_debut="",$date_fin=""){
	
	global $dbh;
	
	if($date_debut && $date_fin){
		if($date_fin<$date_debut){
			$restrict = " and date_date between '".$date_fin."' and '".$date_debut."' ";
		} else if($date_fin == $date_debut) {
			$restrict = " and date_date='".$date_debut."' ";
		} else {
			$restrict = " and date_date between '".$date_debut."' and '".$date_fin."' ";
		}
	} else if($date_debut){
		$restrict = " and date_date >='".$date_debut."' ";
	} else if($date_fin){
		$restrict = " and date_date <='".$date_fin."' ";
	}
	
	return $restrict;
}

print $notice_display_footer;
print pmb_bidi($form);
mysql_free_result($res);