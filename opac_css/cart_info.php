<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cart_info.php,v 1.44 2011-01-18 16:09:18 trenon Exp $

//Actions et affichage du résultat pour un panier de l'opac

$base_path=".";
require_once($base_path."/includes/init.inc.php");
require_once($base_path."/includes/error_report.inc.php") ;
require_once($base_path."/includes/global_vars.inc.php");
require_once($base_path.'/includes/opac_config.inc.php');
	
// récupération paramètres MySQL et connection á la base
require_once($base_path.'/includes/opac_db_param.inc.php');
require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

require_once($base_path."/includes/misc.inc.php");

//Sessions !! Attention, ce doit être impérativement le premier include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");

require_once($base_path.'/includes/start.inc.php');
require_once($base_path."/includes/check_session_time.inc.php");

// récupération localisation
require_once($base_path.'/includes/localisation.inc.php');

// version actuelle de l'opac
require_once($base_path.'/includes/opac_version.inc.php');

require_once($base_path."/classes/search.class.php");

?>
<html>
<body class="cart_info_body">
<span id='cart_info_iframe_content'>
<?php

function add_query($requete) {
	global $cart_;
	global $opac_max_cart_items;
	global $msg;
	global $charset;
	
	$resultat=mysql_query($requete);
	$nbtotal=@mysql_num_rows($resultat);
	$n=0; $na=0;
	while ($r=mysql_fetch_object($resultat)) {
		if (count($cart_)<$opac_max_cart_items) {
			$as=array_search($r->notice_id,$cart_);
			if (($as===null)||($as===false)) {
				$cart_[]=$r->notice_id;
				$n++;	
			} else $na++;
		}
	}
	$message=sprintf($msg["cart_add_notices"],$n,$nbtotal);
	if ($na) $message.=", ".sprintf($msg["cart_already_in"],$na);
	if (count($cart_)==$opac_max_cart_items) $message.=", ".$msg["cart_full"];
	return $message;
}


print "<link rel=\"stylesheet\" href=\"./styles/".$css."/".$css.".css\" />
<span class='img_basket'><img src='images/basket_small_20x20.gif' border='0' valign='center'/></span>&nbsp;";
$cart_=$_SESSION["cart"];
if (!count($cart_)) $cart_=array();
if (($id)&&(!$lvl)) {
	if (count($cart_)<$opac_max_cart_items) {
		$as=array_search($id,$cart_);
		$notice_header=htmlentities(substr(strip_tags(stripslashes(html_entity_decode($header,ENT_QUOTES))),0,45),ENT_QUOTES,$charset);
		if ($notice_header!=$header) $notice_header.="...";
		if (($as!==null)&&($as!==false)) {
			$message=sprintf($msg["cart_notice_exists"],$notice_header);
		} else {
			$cart_[]=$id;
			$message=sprintf($msg["cart_notice_add"],$notice_header);
		}
	} else {
		$message=$msg["cart_full"];
	}
} else if ($lvl) {
	switch ($lvl) {
		case "more_results":
			switch ($mode) {
				case "tous" :
					$requete="select notice_id, ".stripslashes($pert)." from notices, notice_statut, notices_global_index ".stripslashes($clause)." and notice_id = num_notice group by notice_id ".stripslashes($tri);
					$message=add_query($requete);
					break;
				case "titre":
					$requete="select notice_id, ".stripslashes($pert)." from notices, notice_statut ".stripslashes($clause)." group by notice_id ".stripslashes($tri);
					$message=add_query($requete);
					break;
				case "keyword":
					$requete="select notice_id, ".stripslashes($pert)." from notices, notice_statut ".stripslashes($clause)." group by notice_id ".stripslashes($tri);
					$message=add_query($requete);
					break;
				case "abstract":
					$requete="select notice_id, ".stripslashes($pert)." from notices, notice_statut ".stripslashes($clause)." group by notice_id ".stripslashes($tri);
					$message=add_query($requete);
					break;
				case "extended":
					$es=new search();
					$table=$es->make_search();
					$requete="select notices.notice_id, tit1 from $table,notices, notice_statut where notices.notice_id=$table.notice_id and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
					$message=add_query($requete);
					break;
				case "external":
					if ($_SESSION["external_type"]=="multi") $es=new search("search_fields_unimarc"); else $es=new search("search_simple_fields_unimarc");
					$table=$es->make_search();
					$requete="select concat('es', notice_id) as notice_id from $table where 1;";
					$message=add_query($requete);
					break;
			}
			break;
		case "author_see":
			$rqt_auteurs = "select author_id as aut from authors where author_see='$id' and author_id!=0 union select author_see as aut from authors where author_id='$id' and author_see!=0 " ;
			$res_auteurs = mysql_query($rqt_auteurs, $dbh);
			$clause_auteurs = "responsability_author in ('$id' ";
			while($id_aut=mysql_fetch_object($res_auteurs)) 
				$clause_auteurs .= ", '".$id_aut->aut."' "; 
			$clause_auteurs .= " ) " ;
			$requete = "SELECT distinct notices.notice_id FROM notices, responsability, notice_statut ";
			$requete .= " where $clause_auteurs and notice_id=responsability_notice and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
			$message=add_query($requete);
			break;
		case "categ_see":
//TODO
//			$requete = "SELECT distinct notices.notice_id FROM notices, notices_categories, notice_statut WHERE notcateg_categorie='$id' and notcateg_notice=notice_id and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
			$requete = "SELECT distinct notices.notice_id FROM notices, notices_categories, notice_statut WHERE num_noeud='$id' and notcateg_notice=notice_id and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;

			$requete .= " ORDER BY $opac_categories_categ_sort_records";
			$message=add_query($requete);
			break;
		case "indexint_see":
			$requete = "SELECT notice_id FROM notices, notice_statut WHERE indexint='$id' and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
			$requete .= " ORDER BY $opac_categories_categ_sort_records";
			$message=add_query($requete);
			break;
		case "coll_see":
			$requete = "SELECT notices.notice_id FROM notices, notice_statut WHERE coll_id='$id' and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
			$message=add_query($requete);
			break;
		case "publisher_see":
			$requete  = "SELECT distinct notice_id FROM notices, notice_statut WHERE (ed1_id='$id' or ed2_id='$id') and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
			$message=add_query($requete);
			break;
		case "serie_see":
			$requete  = "SELECT distinct notice_id FROM notices, notice_statut WHERE tparent_id='$id' and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
			$message=add_query($requete);
			break;
		case "subcoll_see":
			$requete = "SELECT notice_id FROM notices, notice_statut WHERE subcoll_id='$id' and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
			$message=add_query($requete);
			break;
		case "etagere_see":
			$requete = "SELECT distinct notice_id FROM caddie_content, etagere_caddie, notices, notice_statut where etagere_id='$id' and caddie_content.caddie_id=etagere_caddie.caddie_id and notice_id=object_id and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
			$message=add_query($requete);
			break;
		case "dsi":
			$requete = "select distinct notice_id from bannette_contenu, notices, notice_statut where num_bannette='$id' and notice_id=num_notice and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
			$message=add_query($requete);
			break;
		case "analysis":
			$requete="SELECT analysis_notice as notice_id FROM analysis where analysis_bulletin='$id'";	
			$message=add_query($requete);
			break;	
		case "listlecture":
			$req = "select notices_associees from opac_liste_lecture where id_liste=$id";
			$res = mysql_query($req,$dbh);
			if(mysql_num_rows($res)){
				$notices = explode(",",mysql_result($res,0,0));
				$requete = "SELECT notices.notice_id FROM notices, notice_statut WHERE notice_id in ('".implode("','",$notices)."') and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
				$message=add_query($requete);
			}
			if($sub == "consult")
				print "<script>top.document.liste_lecture.action=\"index.php?lvl=show_list&sub=consultation&id_liste=$id\";top.document.liste_lecture.target=\"\"</script>";
			else
				print "<script>top.document.liste_lecture.action=\"index.php?lvl=show_list&sub=view&id_liste=$id\";top.document.liste_lecture.target=\"\"</script>";
			break;		
		case "section_see":
			//On regarde les droits
			//droits d'acces emprunteur/notice
			$acces_j='';
			if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
				require_once("$class_path/acces.class.php");
				$ac= new acces();
				$dom_2= $ac->setDomain(2);
				$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
			}
				
			if($acces_j) {
				$statut_j='';
				$statut_r='';
			} else {
				$statut_j=',notice_statut';
				$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
			}
			
			//On regarde dans quelle type de navigation on se trouve
			$requete="SELECT num_pclass FROM docsloc_section WHERE num_location='".$location."' AND num_section='".$id."' ";
			$res=mysql_query($requete);
			$type_aff_navigopac=0;
			if(mysql_num_rows($res)){
				$type_aff_navigopac=mysql_result($res,0,0);
			}
			//$message="navigopac : ".$type_aff_navigopac;break;
			if($type_aff_navigopac == 0 or ($type_aff_navigopac == -1 && !$plettreaut)or ($type_aff_navigopac != -1 && $type_aff_navigopac != 0 && !isset($dcote) && !isset($nc))){
				//Pas de navigation ou navigation par les auteurs mais sans choix éffectué
				$requete="create temporary table temp_n_id ENGINE=MyISAM ( SELECT notice_id FROM notices ".$acces_j." JOIN exemplaires ON expl_section='".$id."' and expl_location='".$location."' and expl_notice=notice_id ".$statut_j." WHERE 1 ".$statut_r." GROUP BY notice_id)";
				mysql_query($requete) or die(mysql_error()."<br /><br />$requete<br /><br />");
				//On récupère les notices de périodique avec au moins un exemplaire d'un bulletin dans la localisation et la section
				$requete="INSERT INTO temp_n_id (SELECT notice_id FROM exemplaires JOIN bulletins ON expl_section='".$id."' and expl_location='".$location."' and expl_bulletin=bulletin_id JOIN notices ON notice_id=bulletin_notice ".$acces_j." ".$statut_j." WHERE 1 ".$statut_r." GROUP BY notice_id)";
				mysql_query($requete) or die(mysql_error()."<br /><br />$requete<br /><br />");
				//echo "Requete : ".$requete."<br/>";
				@mysql_query("alter table temp_n_id add index(notice_id)");
				$requete = "SELECT notice_id FROM temp_n_id ";
			}elseif($type_aff_navigopac == -1 ){
				$requete="create temporary table temp_n_id ENGINE=MyISAM ( SELECT notice_id FROM notices ".$acces_j." JOIN exemplaires ON expl_section='".$id."' and expl_location='".$location."' and expl_notice=notice_id ".$statut_j." WHERE 1 ".$statut_r." GROUP BY notice_id)";
				mysql_query($requete) or die(mysql_error()."<br /><br />$requete<br /><br />");
				//On récupère les notices de périodique avec au moins un exemplaire d'un bulletin dans la localisation et la section
				$requete="INSERT INTO temp_n_id (SELECT notice_id FROM exemplaires JOIN bulletins ON expl_section='".$id."' and expl_location='".$location."' and expl_bulletin=bulletin_id JOIN notices ON notice_id=bulletin_notice ".$acces_j." ".$statut_j." WHERE 1 ".$statut_r." GROUP BY notice_id)";
				mysql_query($requete) or die(mysql_error()."<br /><br />$requete<br /><br />");
				
				if($plettreaut == "num"){
					$requete = "SELECT temp_n_id.notice_id FROM temp_n_id JOIN responsability ON responsability_notice=temp_n_id.notice_id JOIN authors ON author_id=responsability_author and trim(index_author) REGEXP '^[0-9]' GROUP BY temp_n_id.notice_id";
				}elseif($plettreaut == "vide"){
					$requete = "SELECT temp_n_id.notice_id FROM temp_n_id LEFT JOIN responsability ON responsability_notice=temp_n_id.notice_id WHERE responsability_author IS NULL GROUP BY temp_n_id.notice_id";
				}else{
					$requete = "SELECT temp_n_id.notice_id FROM temp_n_id JOIN responsability ON responsability_notice=temp_n_id.notice_id JOIN authors ON author_id=responsability_author and trim(index_author) REGEXP '^[".$plettreaut."]' GROUP BY temp_n_id.notice_id";
				}
				
			}else{
				//Navigation par plan de classement
				
				//Table temporaire de tous les id
				if ($ssub) {
					$t_dcote=explode(",",$dcote);
					$t_expl_cote_cond=array();
					for ($i=0; $i<count($t_dcote); $i++) {
						$t_expl_cote_cond[]="expl_cote regexp '(^".$t_dcote[$i]." )|(^".$t_dcote[$i]."[0-9])|(^".$t_dcote[$i]."$)|(^".$t_dcote[$i].".)'";
					}
					$expl_cote_cond="(".implode(" or ",$t_expl_cote_cond).")";
				}else{
					$expl_cote_cond= " expl_cote regexp '".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote))."' and expl_cote not regexp '(\\\\.[0-9]*".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")|([^0-9]*[0-9]+\\\\.?[0-9]*.+".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")' ";
				}	
				$requete="create temporary table temp_n_id ENGINE=MyISAM select notice_id FROM notices, exemplaires ".$acces_j." ".$statut_j."  WHERE expl_location=$location and expl_section='$id' and notice_id=expl_notice ".$statut_r." " ;
				if (strlen($dcote)) {
					$requete.= " and $expl_cote_cond ";
					$level_ref=strlen($dcote)+1;
				}
				$requete.=" group by notice_id ";
				@mysql_query($requete);

				$requete2 = "insert into temp_n_id (SELECT notice_id FROM notices $acces_j ,exemplaires, bulletins $statut_j ";
				$requete2.= "where  expl_location=$location and expl_section=$id and notice_id=bulletin_notice and expl_bulletin=bulletin_id ";
				if (strlen($dcote)) {
					$requete2.= " and $expl_cote_cond ";
				}
				
				$requete2.= "$statut_r ";
				$requete2.= "group by notice_id) ";
				@mysql_query($requete2);
				@mysql_query("alter table temp_n_id add index(notice_id)");
				
				//Calcul du classement
				$rq1_index="create temporary table union1 ENGINE=MyISAM (select distinct expl_cote from exemplaires, temp_n_id where expl_location=$location and expl_section=$id and expl_notice=temp_n_id.notice_id) ";
				$res1_index=mysql_query($rq1_index);
				$rq2_index="create temporary table union2 ENGINE=MyISAM (select distinct expl_cote from exemplaires, temp_n_id, bulletins where expl_location=$location and expl_section=$id and bulletin_notice=temp_n_id.notice_id and expl_bulletin=bulletin_id) ";
				$res2_index=mysql_query($rq2_index);			
				$req_index="select distinct expl_cote from union1 union select distinct expl_cote from union2";
				$res_index=mysql_query($req_index);
		
				if ($level_ref==0) $level_ref=1;
				
				while (($ct=mysql_fetch_object($res_index)) && $nc) {
					if (preg_match("/[0-9][0-9][0-9]/",$ct->expl_cote,$c)) {
						$found=false;
						$lcote=(strlen($c[0])>=3) ? 3 : strlen($c[0]);
						$level=$level_ref;
						while ((!$found)&&($level<=$lcote)) {
							$cote=substr($c[0],0,$level);
							$compl=str_repeat("0",$lcote-$level);
							$rq_index="select indexint_name,indexint_comment from indexint where indexint_name='".$cote.$compl."' and length(indexint_name)>=$lcote and num_pclass='".$type_aff_navigopac."' order by indexint_name limit 1";
							$res_index_1=mysql_query($rq_index);
							if (mysql_num_rows($res_index_1)) {
								$rq_del="select distinct notice_id from notices, exemplaires where expl_cote='".$ct->expl_cote."' and expl_notice=notice_id ";
								$rq_del.=" union select distinct notice_id from notices, exemplaires, bulletins where expl_cote='".$ct->expl_cote."' and expl_bulletin=bulletin_id and bulletin_notice=notice_id ";
								$res_del=mysql_query($rq_del) ;
								while (list($n_id)=mysql_fetch_row($res_del)) {
									mysql_query("delete from temp_n_id where notice_id=".$n_id);
								}
								$found=true;
							} else $level++;
						}
					}
				}
				$requete = "SELECT notice_id FROM temp_n_id " ;	
			}
			
			$message=add_query($requete);
			break;
	}
} else $message="";
if(!count($cart_)) echo $msg["cart_empty"]; else echo $message." <a href='#' onClick=\"parent.document.location='index.php?lvl=show_cart'; return false;\">".sprintf($msg["cart_contents"],count($cart_))."</a>.";
$_SESSION["cart"]=$cart_;
?>
</span>
</body>
</html>
