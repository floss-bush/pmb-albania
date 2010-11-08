<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: edition_func.inc.php,v 1.31 2010-09-14 14:57:10 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/thesaurus.class.php");
require_once($class_path."/parametres_perso.class.php");
require_once("$class_path/notice_tpl_gen.class.php");

// Affichage tabulaire du contenu d'un caddie
function afftab_cart_objects ($idcaddie=0, $flag="" , $no_flag = "",$notice_tpl=0) {
global $msg;
global $dbh;
global $worksheet ;
global $myCart ;
global $dest ;
global $entete_bloc;
global $max_aut ;
global $max_perso;
global $res_compte3 ;

global $etat_table ; // permet de savoir si les tag table sont ouverts ou fermï¿½s

if (($flag=="") && ($no_flag=="")) {
	$no_flag = 1;
	$flag = 1;
	}

$caddie_type = $myCart->type ;
        	
// Afin de trier les éditions :
switch ($caddie_type) {
	case 'NOTI' :
		$fromc = " left join notices on object_id=notice_id " ;
		$orderc = ", niveau_hierar desc " ;
		break;
	case 'EXPL' :
		$fromc = " left join exemplaires on object_id=expl_id " ;
		$orderc = ", expl_notice desc, expl_bulletin " ;
		break;
	case 'BULL' :
		$fromc = " left join bulletins on object_id=bulletin_id " ;
		$orderc = ", date_date " ;
		break;
	}	

$requete = "SELECT caddie_content.* FROM caddie_content $fromc where caddie_id='".$idcaddie."' ";
if ($flag && $no_flag ) $complement_clause = "";
if (!$flag && $no_flag ) $complement_clause = " and flag is null ";
if ($flag && !$no_flag ) $complement_clause = " and flag is not null ";
if (!$flag && !$no_flag ) return ;
$requete .= $complement_clause." order by blob_type, content $orderc, object_id";

$liste=array();
$result = mysql_query($requete, $dbh) or die($requete."<br />".mysql_error($dbh));
if($dest=="EXPORT_NOTI"){
	$noti_tpl=new notice_tpl_gen($notice_tpl);		
}
if(mysql_num_rows($result)) {
	while ($temp = mysql_fetch_object($result)) 		
		if($dest=="EXPORT_NOTI"){
			if ($caddie_type=="EXPL"){
				$rqt_test = "select expl_bulletin+expl_notice as id from exemplaires where expl_id='".$temp->object_id."' ";				
				$res_notice = mysql_query($rqt_test, $dbh);
				$obj_notice = mysql_fetch_object($res_notice) ;
				if(!$flag_notice_id[$obj_notice->id]){
					$flag_notice_id[$obj_notice->id]=1;
					$contents.=$noti_tpl->build_notice($obj_notice->id)."<hr />";
				}		
			}
			elseif ($caddie_type=="NOTI") $contents.=$noti_tpl->build_notice($temp->object_id)."<hr />";	
			if ($caddie_type=="BULL"){
				$rqt_test = $rqt_tout = "select num_notice as id from bulletins where bulletin_id = '".$temp->object_id."' ";			
				$res_notice = mysql_query($rqt_test, $dbh);
				$obj_notice = mysql_fetch_object($res_notice);
				if(!$flag_notice_id[$obj_notice->id] && $obj_notice->id){
					$flag_notice_id[$obj_notice->id]=1;
					$contents.=$noti_tpl->build_notice($obj_notice->id)."<hr />";
				}		
			}
		}else 
			$liste[] = array('object_id' => $temp->object_id, 'content' => $temp->content, 'blob_type' => $temp->blob_type, 'flag' => $temp->flag ) ;
	} else return;


switch($dest) {
	case "TABLEAU":
		break;
	case "EXPORT_NOTI":
		return $contents;
		break;	
	case "TABLEAUHTML":
	default:
		echo pmb_bidi("<h1>".$msg['panier_num']." $idcaddie / ".$myCart->name."</h1>");
		echo pmb_bidi($myCart->comment."<br />");
		
		break;
	}

// en fonction du type de caddie on affiche ce qu'il faut
if ($caddie_type=="NOTI") {
	// calcul du nombre max de colonnes pour les auteurs
	$rqt_compte1 = "create temporary table tmp_compte1 ENGINE=MyISAM as select count(*) as comptage from caddie_content join notices on object_id=notice_id left join responsability on responsability_notice=notice_id where caddie_id=$idcaddie group by notice_id" ;
	$res_compte1 = mysql_query($rqt_compte1, $dbh) ; 
	$rqt_compte2 = "select max(comptage) as max_aut from tmp_compte1 " ;
	$res_compte2 = mysql_query($rqt_compte2, $dbh) ; 
	$compte2 = mysql_fetch_object($res_compte2) ;
	$max_aut = $compte2->max_aut ;
	
	// calcul du nombre max de colonnes pour les champs perso
	$rqt_compte3 = "select idchamp, titre from notices_custom order by ordre " ;
	$res_compte3 = mysql_query($rqt_compte3, $dbh) ; 
	$max_perso = mysql_num_rows($res_compte3) ;
		
	// boucle de parcours des notices trouvées
	// inclusion du javascript de gestion des listes dépliables
	// début de liste
	$entete_bloc_prec="";
	while(list($cle, $object) = each($liste)) {
		if ($object[content]=="") {
			//On regarde le type de notice
			$requete="select niveau_biblio, niveau_hierar FROM notices WHERE notice_id='".$object[object_id]."' ";
			$mon_res=mysql_query($requete,$dbh);
			$sel=", '', '', '', '', '', '', ''";
			$tabl="";
			if(mysql_result($mon_res,0,0) == "a" && mysql_result($mon_res,0,1) == "2"){
				$sel=" ,n2.tit1 as 'Périodique', n2.code as ISSN, b.bulletin_numero, b.mention_date, b.date_date, b.bulletin_titre, b.bulletin_cb ";
				$tabl=" JOIN analysis ON n1.notice_id=analysis_notice JOIN bulletins b ON analysis_bulletin=b.bulletin_id JOIN notices n2 ON n2.notice_id=bulletin_notice ";
			}elseif(mysql_result($mon_res,0,0) == "b" && mysql_result($mon_res,0,1) == "2"){
				$sel=" ,n2.tit1, n2.code as ISSN, b.bulletin_numero, b.mention_date, b.date_date, b.bulletin_titre, b.bulletin_cb ";
				$tabl=" JOIN bulletins b ON n1.notice_id=b.num_notice JOIN notices n2 ON n2.notice_id=bulletin_notice ";
			}
			$rqt_tout = "SELECT n1.notice_id, n1.typdoc, n1.tit1, n1.tit2, n1.tit3, n1.tit4, serie_name, n1.tnvol, p1.ed_name, p1.ed_ville, collection_name, sub_coll_name, n1.year, n1.nocoll, n1.mention_edition, p2.ed_name as '2nd editeur', p2.ed_ville as 'ville 2nd editeur', n1.code as ISBN, n1.npages, n1.ill, n1.size, n1.accomp, n1.n_gen, n1.n_contenu, n1.n_resume, n1.lien, n1.eformat, n1.index_l, indexint_name, n1.niveau_biblio, n1.niveau_hierar, n1.prix, n1.statut, n1.commentaire_gestion, n1.thumbnail_url, n1.create_date, n1.update_date ".$sel." FROM notices n1";
			$rqt_tout.= " left join series on serie_id=n1.tparent_id ";
			$rqt_tout.= " left join publishers p1 on p1.ed_id=n1.ed1_id ";
			$rqt_tout.= " left join publishers p2 on p2.ed_id=n1.ed2_id ";
			$rqt_tout.= " left join collections on n1.coll_id=collection_id ";
			$rqt_tout.= " left join sub_collections on n1.subcoll_id=sub_coll_id ";
			$rqt_tout.= " left join indexint on n1.indexint=indexint_id ";
			$rqt_tout.=$tabl;
			$rqt_tout.= " WHERE n1.notice_id='".$object[object_id]."' ";
			//echo "requete :".$rqt_tout."\n";
			$entete_bloc="MONO";
			if ($entete_bloc!=$entete_bloc_prec) {
				extrait_info_notice($rqt_tout, 1, $object[flag]);
				$entete_bloc_prec=$entete_bloc ;
				} else extrait_info_notice($rqt_tout, 0, $object[flag]);
				
		} else {
			$entete_bloc="BLOB";
			if ($entete_bloc!=$entete_bloc_prec) {
				extrait_blob($object[blob_type]." ".$object[content],1, $object[flag]);
				$entete_bloc_prec=$entete_bloc ;
				} else extrait_blob($object[blob_type]." ".$object[content],0, $object[flag]);;
			}
		} // fin de liste
} // fin si NOTI
// si EXPL
if ($caddie_type=="EXPL") {
	// boucle de parcours des exemplaires trouvés
	while(list($cle, $expl) = each($liste)) {
		if (!$expl[content]) {
			$rqt_test = "select expl_bulletin from exemplaires where expl_id='".$expl[object_id]."' ";
			$result_test = mysql_query($rqt_test, $dbh);
			$obj_test = mysql_fetch_object($result_test) ;
			if ($obj_test->expl_bulletin==0) {
				// expl de mono
				$rqt_tout  = "SELECT e.*, t.*, s.*, st.*, l.*, stat.*, n.*";
				$rqt_tout .= " FROM exemplaires e";
				$rqt_tout .= ", docs_type t";
				$rqt_tout .= ", docs_section s";	
				$rqt_tout .= ", docs_statut st";	
				$rqt_tout .= ", docs_location l";	
				$rqt_tout .= ", docs_codestat stat";
				$rqt_tout .= ", notices n";
				$rqt_tout .= " WHERE e.expl_id='".$expl[object_id]."'";
				$rqt_tout .= " AND e.expl_typdoc=t.idtyp_doc";
				$rqt_tout .= " AND e.expl_section=s.idsection";
				$rqt_tout .= " AND e.expl_statut=st.idstatut";
				$rqt_tout .= " AND e.expl_location=l.idlocation";
				$rqt_tout .= " AND e.expl_codestat=stat.idcode";
				$rqt_tout .= " AND e.expl_notice=n.notice_id";
				$entete_bloc="EXPLMONO" ;
				} else {
					// expl de bulletin
					$rqt_tout  = "SELECT e.*, t.*, s.*, st.*, l.*, stat.*, n.*, b.*";
					$rqt_tout .= " FROM exemplaires e";
					$rqt_tout .= ", docs_type t";
					$rqt_tout .= ", docs_section s";	
					$rqt_tout .= ", docs_statut st";	
					$rqt_tout .= ", docs_location l";	
					$rqt_tout .= ", docs_codestat stat";
					$rqt_tout .= ", notices n";
					$rqt_tout .= ", bulletins b";
					$rqt_tout .= " WHERE e.expl_id='".$expl[object_id]."'";
					$rqt_tout .= " AND e.expl_typdoc=t.idtyp_doc";
					$rqt_tout .= " AND e.expl_section=s.idsection";
					$rqt_tout .= " AND e.expl_statut=st.idstatut";
					$rqt_tout .= " AND e.expl_location=l.idlocation";
					$rqt_tout .= " AND e.expl_codestat=stat.idcode";
					$rqt_tout .= " AND e.expl_bulletin=b.bulletin_id";
					$rqt_tout .= " AND n.notice_id=b.bulletin_notice";
					$entete_bloc="EXPLBULL";
					}
			if ($entete_bloc!=$entete_bloc_prec) {
				extrait_info($rqt_tout, 1, $expl[flag]);
				$entete_bloc_prec=$entete_bloc ;
				} else extrait_info($rqt_tout, 0, $expl[flag]);
			} else  {
				$entete_bloc="BLOB";
				if ($entete_bloc!=$entete_bloc_prec) {
					extrait_blob($expl[blob_type]." ".$expl[content],1, $expl[flag]);
					$entete_bloc_prec=$entete_bloc ;
					} else extrait_blob($expl[blob_type]." ".$expl[content],0, $expl[flag]);
				}
		} // fin de liste
	} // fin si EXPL
if ($caddie_type=="BULL") {
	// boucle de parcours des bulletins trouvés
	// inclusion du javascript de gestion des listes dépliables
	// début de liste
	while(list($cle, $expl) = each($liste)) {
		if (!$expl[content]) {
			$rqt_tout = "select * from bulletins where bulletin_id = '".$expl[object_id]."' ";
			$entete_bloc="BULL";
			if ($entete_bloc!=$entete_bloc_prec) {
				extrait_info($rqt_tout, 1, $expl[flag]);
				$entete_bloc_prec=$entete_bloc ;
				} else extrait_info($rqt_tout, 0, $expl[flag]);
			} 
		else {
			$entete_bloc="BLOB";
			if ($entete_bloc!=$entete_bloc_prec) {
				extrait_blob($expl[blob_type]." ".$expl[content],1, $expl[flag]);
				$entete_bloc_prec=$entete_bloc ;
				} else extrait_blob($expl[blob_type]." ".$expl[content],0, $expl[flag]);
			}
		} // fin de liste
	} // fin si BULL
return;
}



function extrait_info ($sql="", $entete=1, $flag="") {
	global $dbh ;
	global $dest ;
	global $worksheet ;
	global $entete_bloc;
	global $msg;
	
	global $debligne_excel;
	global $etat_table ; // permet de savoir si les tag table sont ouverts ou fermés
	
	global $max_aut ; // le nombre max de colonnes d'auteurs
	
	if (!$debligne_excel) $debligne_excel = 0 ;
	
	$res = @mysql_query($sql, $dbh);
	$nbr_lignes = @mysql_num_rows($res);
	$nbr_champs = @mysql_num_fields($res);
             		
	if ($nbr_lignes) {
		switch($dest) {
			case "TABLEAU":
				if ($entete) {
					$worksheet->write_string((1+$debligne_excel),0,$msg["caddie_mess_edition_".$entete_bloc]);
					$debligne_excel++ ;
					}
				for($i=0; $i < $nbr_champs; $i++) {
					// entête de colonnes
					$fieldname = mysql_field_name($res, $i);
					if ($entete) {
						$worksheet->write_string((1+$debligne_excel),0,$msg['caddie_action_marque']);
						$worksheet->write_string((1+$debligne_excel),($i+1),${fieldname});
						}
					}
				if ($entete) $debligne_excel++ ;
             		        		
				for($i=0; $i < $nbr_lignes; $i++) {
					$debligne_excel++;
					$row = mysql_fetch_row($res);
					if ($flag) $worksheet->write_string(($i+$debligne_excel),0,"X");
					$j=0;
					foreach($row as $dummykey=>$col) {
						if(!$col) $col=" ";
						$worksheet->write_string(($i+$debligne_excel),($j+1),$col);
						$j++;
						}
					}
				break;
			case "TABLEAUHTML":
				if ($entete) {
					if ($etat_table) echo "\n</table>";
					echo "<h3>".$msg["caddie_mess_edition_".$entete_bloc]."</h3>";
					echo "\n<table><th align='left'>".$msg['caddie_action_marque']."</th>";
					$etat_table = 1 ;
					}
				for($i=0; $i < $nbr_champs; $i++) {
					$fieldname = mysql_field_name($res, $i);
					if ($entete) print("<th align='left'>${fieldname}</th>");
					}
				for($i=0; $i < $nbr_lignes; $i++) {
					$row = mysql_fetch_row($res);
					echo "<tr>";
					if ($flag) print "<td>X</td>"; else print "<td>&nbsp;</td>";
					foreach($row as $dummykey=>$col) {
						if (is_numeric($col)){
 							$col = "'".$col ;
							}
						if(!$col) $col="&nbsp;";
						print pmb_bidi("<td>$col</td>");
						}
					echo "</tr>";
					}
				break;
			default:
				if ($entete) {
					if ($etat_table) echo "\n</table>";
					echo "<h3>".$msg["caddie_mess_edition_".$entete_bloc]."</h3>";
					echo "\n<table><th align='left'>".$msg['caddie_action_marque']."</th>";
					$etat_table = 1 ;
					}
				for($i=0; $i < $nbr_champs; $i++) {
					$fieldname = mysql_field_name($res, $i);
					if ($entete) print("<th align='left'>${fieldname}</th>");
					}
				$odd_even=0;
				for($i=0; $i < $nbr_lignes; $i++) {
					$row = mysql_fetch_row($res);
					if ($odd_even==0) {
						echo "	<tr class='odd'>";
						$odd_even=1;
						} else if ($odd_even==1) {
							echo "	<tr class='even'>";
							$odd_even=0;
							}
					if ($flag) print "<td>X</td>"; else print "<td>&nbsp;</td>";
					foreach($row as $dummykey=>$col) {
						if(!$col) $col="&nbsp;";
						print pmb_bidi("<td>$col</td>");
						}
					echo "</tr>";
					}
				break;
			} // fin switch
		} // fin if nbr_lignes
	} // fin fonction extrait_info

	
function extrait_info_notice ($sql="", $entete=1, $flag="") {
	global $dbh ;
	global $dest ;
	global $worksheet ;
	global $entete_bloc;
	global $msg;
	
	global $debligne_excel;
	global $etat_table ; // permet de savoir si les tag table sont ouverts ou fermés
	
	global $max_aut ; // le nombre max de colonnes d'auteurs
	
	global $thesaurus_mode_pmb;
	global $thesaurus_defaut;
	global $lang;
	global $pmb_keyword_sep;
	
	global $max_perso;
	global $res_compte3 ;

	if (!$debligne_excel) $debligne_excel = 0 ;
	
	$res = @mysql_query($sql, $dbh);
	$nbr_lignes = @mysql_num_rows($res);
	$nbr_champs = @mysql_num_fields($res);
             		
	if ($nbr_lignes) {
		switch($dest) {
			case "TABLEAU":
				if ($entete) {
					$worksheet->write_string((1+$debligne_excel),0,$msg["caddie_mess_edition_".$entete_bloc]);
					$debligne_excel++ ;
					$worksheet->write_string((1+$debligne_excel),0,$msg['caddie_action_marque']);
					for($i=0; $i < $nbr_champs; $i++) {
						// entête de colonnes
						$fieldname = mysql_field_name($res, $i);
						$worksheet->write_string((1+$debligne_excel),($i+1),$fieldname);
						}
					for($i=0; $i < $max_aut; $i++) {
						$worksheet->write_string((1+$debligne_excel),($i*6+1+$nbr_champs),"aut_entree_$i");
						$worksheet->write_string((1+$debligne_excel),($i*6+2+$nbr_champs),"aut_rejete_$i");
						$worksheet->write_string((1+$debligne_excel),($i*6+3+$nbr_champs),"aut_dates_$i");
						$worksheet->write_string((1+$debligne_excel),($i*6+4+$nbr_champs),"aut_fonction_$i");
						$worksheet->write_string((1+$debligne_excel),($i*6+5+$nbr_champs),"aut_type_$i");
						$worksheet->write_string((1+$debligne_excel),($i*6+6+$nbr_champs),"aut_resp_type_$i");
						}
					$worksheet->write_string((1+$debligne_excel),($max_aut*6+$nbr_champs),"DESCR");
					for($i=0; $i < $max_perso; $i++) {
						$perso = mysql_fetch_object($res_compte3) ;
						$worksheet->write_string((1+$debligne_excel),($max_aut*6+$nbr_champs+1+$i),$perso->titre);
						}
					$debligne_excel++;
					}
				for($i=0; $i < $nbr_lignes; $i++) {
					$debligne_excel++;
					$row = mysql_fetch_row($res);
					$id_notice = $row[0] ;
					if ($flag) $worksheet->write_string($debligne_excel,0,"X");
					$j=0;
					foreach($row as $dummykey=>$col) {
						if(!$col) $col=" ";
						$worksheet->write_string($debligne_excel,($j+1),$col);
						$j++;
						}
					$rqt_aut = "SELECT author_name, author_rejete, author_date, responsability_fonction, author_type, responsability_type ";
					$rqt_aut .= "FROM responsability JOIN authors ON responsability_author=author_id ";
					$rqt_aut .= "WHERE responsability_notice=$id_notice " ;
					$rqt_aut .= "ORDER BY responsability_type ASC, responsability_ordre ASC";
					$res_aut = @mysql_query($rqt_aut);
					for($iaut=0; $iaut < $max_aut; $iaut++) {
						$aut = @mysql_fetch_row($res_aut);
						$worksheet->write_string($debligne_excel,($iaut*6+1+$nbr_champs),$aut[0]);
						$worksheet->write_string($debligne_excel,($iaut*6+2+$nbr_champs),$aut[1]);
						$worksheet->write_string($debligne_excel,($iaut*6+3+$nbr_champs),$aut[2]);
						$worksheet->write_string($debligne_excel,($iaut*6+4+$nbr_champs),$aut[3]);
						$worksheet->write_string($debligne_excel,($iaut*6+5+$nbr_champs),$aut[4]);
						$worksheet->write_string($debligne_excel,($iaut*6+6+$nbr_champs),$aut[5]);
					}

					$q = "drop table if exists catlg ";
					$r = mysql_query($q, $dbh);
					$q = "CREATE TEMPORARY TABLE catlg ENGINE=MyISAM as ";
					$q.= "SELECT categories.num_noeud, categories.libelle_categorie ";
					$q.= "FROM noeuds, categories, notices_categories ";
					$q.= "WHERE notices_categories.notcateg_notice = '".$id_notice."' ";
					$q.= "AND categories.langue = '".$lang."' ";
					$q.= "AND categories.num_noeud = notices_categories.num_noeud " ;
					$q.= "AND categories.num_noeud = noeuds.id_noeud ";
					$q.= "ORDER BY ordre_categorie";
					$r = mysql_query($q, $dbh) ;

					$q = "DROP TABLE IF EXISTS catdef ";
					$r = mysql_query($q, $dbh);

					$q = "CREATE TEMPORARY TABLE catdef ( ";
					$q.= "num_noeud int(9) unsigned not null default '0', ";
					$q.= "num_thesaurus int(3) unsigned not null default '0', ";
					$q.= "libelle_categorie text not null ) ENGINE=MyISAM ";			
					$r = mysql_query($q, $dbh);
			
					$thes_list = thesaurus::getThesaurusList();
					$q = '';
					foreach($thes_list as $id_thesaurus=>$libelle_thesaurus) {
						$thes = new thesaurus($id_thesaurus);
						$q = "INSERT INTO catdef ";
						$q.= "SELECT categories.num_noeud, noeuds.num_thesaurus, categories.libelle_categorie ";  
						$q.= "FROM noeuds, categories, notices_categories ";
						$q.= "WHERE noeuds.num_thesaurus=$id_thesaurus and notices_categories.notcateg_notice = '".$id_notice."' ";
						$q.= "AND categories.langue = '".$thes->langue_defaut."' ";
						$q.= "AND categories.num_noeud = notices_categories.num_noeud " ;
						$q.= "AND categories.num_noeud = noeuds.id_noeud ";
						$q.= "ORDER BY ordre_categorie";
						$r = mysql_query($q, $dbh);
					}

					$q = "select catdef.num_thesaurus as num_thesaurus, ";
					$q.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as libelle_categorie ";
					$q.= "from catdef left join catlg on catdef.num_noeud = catlg.num_noeud ";
					if (!$thesaurus_mode_pmb) 
					$q.= "where catdef.num_thesaurus = '".$thesaurus_defaut."' ";	

					$res_desc = mysql_query($q, $dbh);

					while ($desc = mysql_fetch_object($res_desc)) {
						$lib_desc.=($lib_desc?$pmb_keyword_sep:"");
						if ($thesaurus_mode_pmb) $lib_desc .= '['.thesaurus::getLibelle($desc->num_thesaurus).'] ';
						$lib_desc .= $desc->libelle_categorie ;
					}
					$worksheet->write_string($debligne_excel,($max_aut*6+$nbr_champs),"$lib_desc");
					
					$p_perso=new parametres_perso("notices");
					//Champs personalisés
					if (!$p_perso->no_special_fields) {
						$perso_=$p_perso->show_fields($id_notice);
						for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
							$p=$perso_["FIELDS"][$i];
							$worksheet->write_string($debligne_excel,($max_aut*6+$nbr_champs+1+$i),html_entity_decode($p["AFF"],ENT_QUOTES|ENT_COMPAT,"iso-8859-15"));
						}
					}
					
					}
				break;
			case "TABLEAUHTML":
				if ($entete) {
					if ($etat_table) echo "\n</table>";
					echo "<h3>".$msg["caddie_mess_edition_".$entete_bloc]."</h3>";
					echo "\n<table><th align='left'>".$msg['caddie_action_marque']."</th>";
					for($i=0; $i < $nbr_champs; $i++) {
						$fieldname = mysql_field_name($res, $i);
						print("<th align='left'>${fieldname}</th>");
						}
					for($i=0; $i < $max_aut; $i++) {
						print pmb_bidi("<th align='left'>aut_entree_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_rejete_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_dates_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_fonction_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_type_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_resp_type_$i</th>") ;
						}
					print "<th align='left'>DESCR</th>" ;
					for($i=0; $i < $max_perso; $i++) {
						$perso = mysql_fetch_object($res_compte3) ;
						print "<th align='left'>".$perso->titre."</th>" ;
						}
					$etat_table = 1 ;
					}
				for($i=0; $i < $nbr_lignes; $i++) {
					$row = mysql_fetch_row($res);
					$id_notice = $row[0] ;
					echo "<tr>";
					if ($flag) print "<td>X</td>"; else print "<td>&nbsp;</td>";
					foreach($row as $dummykey=>$col) {
						if (is_numeric($col)){
 							$col = "'".$col ;
							}
						if(!$col) $col="&nbsp;";
						print pmb_bidi("<td>$col</td>");
						}
					$rqt_aut = "SELECT author_name, author_rejete, author_date, responsability_fonction, author_type, responsability_type ";
					$rqt_aut .= "FROM responsability JOIN authors ON responsability_author=author_id ";
					$rqt_aut .= "WHERE responsability_notice=$id_notice " ;
					$rqt_aut .= "ORDER BY responsability_type ASC, responsability_ordre ASC";
					$res_aut = @mysql_query($rqt_aut, $dbh);
					for($i=0; $i < $max_aut; $i++) {
						$aut = @mysql_fetch_row($res_aut);
						print pmb_bidi("<td>$aut[0]</td>") ;
						print pmb_bidi("<td>$aut[1]</td>") ;
						print pmb_bidi("<td>$aut[2]</td>") ;
						print pmb_bidi("<td>$aut[3]</td>") ;
						print pmb_bidi("<td>$aut[4]</td>") ;
						print pmb_bidi("<td>$aut[5]</td>") ;
					}

					$q = "drop table if exists catlg ";
					$r = mysql_query($q, $dbh);

					$q = "create temporary table catlg ENGINE=MyISAM as ";
					$q.= "select categories.num_noeud, categories.libelle_categorie ";
					$q.= "from noeuds, categories, notices_categories ";
					$q.= "where notices_categories.notcateg_notice = '".$id_notice."' ";
					$q.= "and categories.langue = '".$lang."' ";
					$q.= "and categories.num_noeud = notices_categories.num_noeud " ;
					$q.= "and categories.num_noeud = noeuds.id_noeud ";
					$q.= "ORDER BY ordre_categorie";
					$r = mysql_query($q, $dbh) ;

					$q = "drop table if exists catdef ";
					$r = mysql_query($q, $dbh);

					$q = "create temporary table catdef ( ";
					$q.= "num_noeud int(9) unsigned not null default '0', ";
					$q.= "num_thesaurus int(3) unsigned not null default '0', ";
					$q.= "libelle_categorie text not null ";
					$q.= ") ENGINE=MyISAM ";			
					$r = mysql_query($q, $dbh);
			
					$thes_list = thesaurus::getThesaurusList();
					$q = '';
					foreach($thes_list as $id_thesaurus=>$libelle_thesaurus) {
						$thes = new thesaurus($id_thesaurus);
						$q = "insert into catdef ";
						$q.= "select categories.num_noeud, noeuds.num_thesaurus, categories.libelle_categorie ";  
						$q.= "from noeuds, categories, notices_categories ";
						$q.= "where noeuds.num_thesaurus=$id_thesaurus and notices_categories.notcateg_notice = '".$id_notice."' ";
						$q.= "and categories.langue = '".$thes->langue_defaut."' ";
						$q.= "and categories.num_noeud = notices_categories.num_noeud " ;
						$q.= "and categories.num_noeud = noeuds.id_noeud ";
						$q.= "ORDER BY ordre_categorie";
						$r = mysql_query($q, $dbh);
					}

					$q = "select catdef.num_thesaurus as num_thesaurus, ";
					$q.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as libelle_categorie ";
					$q.= "from catdef left join catlg on catdef.num_noeud = catlg.num_noeud ";
					if (!$thesaurus_mode_pmb) 
						$q.= "where catdef.num_thesaurus = '".$thesaurus_defaut."' ";
					$res_desc = mysql_query($q, $dbh);
					
					while ($desc = mysql_fetch_object($res_desc)) {
						$lib_desc.=($lib_desc?$pmb_keyword_sep:"");
						if ($thesaurus_mode_pmb) $lib_desc .= '['.thesaurus::getLibelle($desc->num_thesaurus).'] ';
						$lib_desc .= $desc->libelle_categorie ;
					}
					print pmb_bidi("<td>$lib_desc</td>" );
					$p_perso=new parametres_perso("notices");
					//Champs personalisés
					if (!$p_perso->no_special_fields) {
						$perso_=$p_perso->show_fields($id_notice);
						for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
							$p=$perso_["FIELDS"][$i];
							print "<td>".$p["AFF"]."</td>" ;
							}
						}
					echo "</tr>";
					}
				break;
			default:
				if ($entete) {
					if ($etat_table) echo "\n</table>";
					echo "<h3>".$msg["caddie_mess_edition_".$entete_bloc]."</h3>";
					echo "\n<table><th align='left'>".$msg['caddie_action_marque']."</th>";
					for($i=0; $i < $nbr_champs; $i++) {
						$fieldname = mysql_field_name($res, $i);
						print("<th align='left'>${fieldname}</th>");
						}
					for($i=0; $i < $max_aut; $i++) {
						print pmb_bidi("<th align='left'>aut_entree_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_rejete_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_dates_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_fonction_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_type_$i</th>") ;
						print pmb_bidi("<th align='left'>aut_resp_type_$i</th>") ;
						}
					print "<th align='left'>DESCR</th>" ;
					for($i=0; $i < $max_perso; $i++) {
						$perso = mysql_fetch_object($res_compte3) ;
						print "<th align='left'>".$perso->titre."</th>" ;
						}
					$etat_table = 1 ;
					}

				$odd_even=0;
				for($i=0; $i < $nbr_lignes; $i++) {
					$row = mysql_fetch_row($res);
					$id_notice = $row[0] ;
					if ($odd_even==0) {
						echo "	<tr class='odd'>";
						$odd_even=1;
						} else if ($odd_even==1) {
							echo "	<tr class='even'>";
							$odd_even=0;
							}
					if ($flag) print "<td>X</td>"; else print "<td>&nbsp;</td>";
					foreach($row as $dummykey=>$col) {
						if(!$col) $col="&nbsp;";
						print pmb_bidi("<td>$col</td>");
						}
					$rqt_aut = "SELECT author_name, author_rejete, author_date, responsability_fonction, author_type, responsability_type ";
					$rqt_aut .= "FROM responsability JOIN authors ON responsability_author=author_id ";
					$rqt_aut .= "WHERE responsability_notice=$id_notice " ;
					$rqt_aut .= "ORDER BY responsability_type ASC, responsability_ordre ASC";
					$res_aut = @mysql_query($rqt_aut, $dbh);
					for($i=0; $i < $max_aut; $i++) {
						$aut = @mysql_fetch_row($res_aut);
						print pmb_bidi("<td>$aut[0]</td>") ;
						print pmb_bidi("<td>$aut[1]</td>") ;
						print pmb_bidi("<td>$aut[2]</td>") ;
						print pmb_bidi("<td>$aut[3]</td>") ;
						print pmb_bidi("<td>$aut[4]</td>") ;
						print pmb_bidi("<td>$aut[5]</td>") ;
						}

					$q = "drop table if exists catlg ";
					$r = mysql_query($q, $dbh);

					$q = "create temporary table catlg ENGINE=MyISAM as ";
					$q.= "select categories.num_noeud, categories.libelle_categorie ";
					$q.= "from noeuds, categories, notices_categories ";
					$q.= "where notices_categories.notcateg_notice = '".$id_notice."' ";
					$q.= "and categories.langue = '".$lang."' ";
					$q.= "and categories.num_noeud = notices_categories.num_noeud " ;
					$q.= "and categories.num_noeud = noeuds.id_noeud ";
					$q.= "ORDER BY ordre_categorie";
					$r = mysql_query($q, $dbh) ;
					$q = "drop table if exists catdef ";
					$r = mysql_query($q, $dbh);

					$q = "create temporary table catdef ( ";
					$q.= "num_noeud int(9) unsigned not null default '0', ";
					$q.= "num_thesaurus int(3) unsigned not null default '0', ";
					$q.= "libelle_categorie text not null ";
					$q.= ") ENGINE=MyISAM ";			
					$r = mysql_query($q, $dbh);

					$thes_list = thesaurus::getThesaurusList();
					$q = '';
					foreach($thes_list as $id_thesaurus=>$libelle_thesaurus) {
						$thes = new thesaurus($id_thesaurus);
						$q = "insert into catdef ";
						$q.= "select categories.num_noeud, noeuds.num_thesaurus, categories.libelle_categorie ";  
						$q.= "from noeuds, categories, notices_categories ";
						$q.= "where noeuds.num_thesaurus=$id_thesaurus and notices_categories.notcateg_notice = '".$id_notice."' ";
						$q.= "and categories.langue = '".$thes->langue_defaut."' ";
						$q.= "and categories.num_noeud = notices_categories.num_noeud " ;
						$q.= "and categories.num_noeud = noeuds.id_noeud ";
						$q.= "ORDER BY ordre_categorie";
						$r = mysql_query($q, $dbh);
					}

					$q = "select catdef.num_thesaurus as num_thesaurus, ";
					$q.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as libelle_categorie ";
					$q.= "from catdef left join catlg on catdef.num_noeud = catlg.num_noeud ";
					if (!$thesaurus_mode_pmb) 
						$q.= "where catdef.num_thesaurus = '".$thesaurus_defaut."' ";	
					$res_desc = mysql_query($q, $dbh);
					
					while ($desc = mysql_fetch_object($res_desc)) {
						$lib_desc.=($lib_desc?$pmb_keyword_sep:"");
						if ($thesaurus_mode_pmb) $lib_desc .= '['.thesaurus::getLibelle($desc->num_thesaurus).'] ';
						$lib_desc .= $desc->libelle_categorie ;
					}
					print pmb_bidi("<td>$lib_desc</td>") ;
					$p_perso=new parametres_perso("notices");
					//Champs personalisés
					if (!$p_perso->no_special_fields) {
						$perso_=$p_perso->show_fields($id_notice);
						for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
							$p=$perso_["FIELDS"][$i];
							print "<td>".$p["AFF"]."</td>" ;
							}
						}
					echo "</tr>";
					}
				break;
			} // fin switch
		} // fin if nbr_lignes
	} // fin fonction extrait_info_notice
	
function extrait_blob ($blob="", $entete=1, $flag="") {
	global $dbh ;
	global $dest ;
	global $worksheet ;
	global $entete_bloc;
	global $msg;
	
	global $debligne_excel;
	global $etat_table ; // permet de savoir si les tag table sont ouverts ou fermés
	
	if (!$debligne_excel) $debligne_excel = 0 ;
	
	switch($dest) {
		case "TABLEAU":
			if ($entete) {
				$worksheet->write_string((1+$debligne_excel),0,$msg["caddie_mess_edition_".$entete_bloc]);
				$debligne_excel++ ;
				}
			if ($flag) $worksheet->write_string((1+$debligne_excel),0,"X");
			$worksheet->write_string((1+$debligne_excel),1,$blob);
			$debligne_excel++ ;
			break;
		case "TABLEAUHTML":
		default:
			if ($etat_table) echo "\n</table>";
			if ($entete) echo "<h3>".$msg["caddie_mess_edition_".$entete_bloc]."</h3>";
			if ($flag) print "<strong>X</strong>&nbsp;"; else "<strong>&nbsp;</strong>&nbsp;";
			print pmb_bidi("$blob<br />");
			break;
		} // fin switch
	} // fin fonction extrait_info