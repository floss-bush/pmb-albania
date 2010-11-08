<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_bdp19_tulle.inc.php,v 1.2 2009-07-02 13:40:12 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// DEBUT paramétrage propre à la base de données d'importation :
$section_bdp19=array("BD adulte","Roman adulte","Roman policier adulte","Roman science fiction adulte",
"Documentaire adulte","Livre+CD adulte","Livre+K7 adulte","Large vision","Fond régional","Revues",
"Album jeunes","BD jeunes","Contes jeunes","Roman jeune","Roman enfant","Documentaire jeune","Livre+CD jeune",
"Livre+K7 jeune","Diapositives","CD","Disque","K7","DVD","K7 Vidéo");

$corresp_bdp19=array(
	array("BD"),
	array("R"),
	array("RX"),
	array("RS"),
	array("79","91","1","2","3","4","5","6","7","8","9","B","0"),
	array("CDL6"),
	array("KL6"),
	array("G"),
	array("L"),
	array("PER"),
	array("EA"),
	array("JBD"),
	array("C"),
	array("E"),
	array("J"),
	array("J1","J2","J3","J4","J5","J6","J7","J8","J9","J79","J91","JB","J0"),
	array("CDL7"),
	array("KL7"),
	array("D"),
	array("CD"),
	array("M"),
	array("K"),
	array("DV"),
	array("V","F","VJ","FJ")
);

$sec_search_bdp19=array(
	"CDL6",
	"CDL7",
	"KL6",
	"PER",
	"JBD",
	"KL7",
	"J79",
	"J91",
	"BD",
	"RX",
	"RS",
	"79",
	"91",
	"EA",
	"J1",
	"J2",
	"J3",
	"J4",
	"J5",
	"J6",
	"J7",
	"J8",
	"J9",
	"JB",
	"J0",
	"CD",
	"DV",
	"VJ",
	"FJ",
	"1",
	"2",
	"3",
	"4",
	"5",
	"6",
	"7",
	"8",
	"9",
	"0",
	"B",
	"R",
	"G",
	"L",
	"C",
	"E",
	"J",
	"D",
	"M",
	"K",
	"V",
	"F");
// 995 $r = type documents
// aucun : sont insérés en base de données avec les bons codages 995$r :
/*
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("Imprimés",15,15,"az");
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("Disque vinyle",15,15,"jz"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("K7 audio",15,15,"jd"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("K7 lue",15,15,"ld"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("Livre K7",15,15,"md"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("CD audio",15,15,"je"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("CD-lu",15,15,"ie"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("Livre CD",15,15,"me"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("DVD audio",15,15,"jf"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("K7 vidéo",15,15,"gd"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("DVD-vidéo",15,15,"gf"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("Affiches estampes",15,15,"ka");
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("Cartes postales",15,15,"kb"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("Diapositives - Icônes",15,15,"kc");
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("CD-Rom",15,15,"le"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("DVD-rom",15,15,"lf"); 
insert into docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_codage_import) values ("Exposition",15,15,"mz"); 
*/
// FIN paramétrage 

function recup_noticeunimarc_suite($notice) {
	global $aut_700,$aut_701,$aut_702,$aut_710,$aut_711,$aut_712;
	
	if($aut_700[0]["f"]){
		$aut_700[0]["f"]="";
	}
	if($aut_710[0]["f"]){
		$aut_710[0]["f"]="";
	}
	for($i=0;$i<count($aut_701);$i++){
		if($aut_700[$i]["f"]){
			$aut_700[$i]["f"]="";
		}
	}
	for($i=0;$i<count($aut_702);$i++){
		if($aut_702[$i]["f"]){
			$aut_702[$i]["f"]="";
		}
	}
	for($i=0;$i<count($aut_711);$i++){
		if($aut_711[$i]["f"]){
			$aut_711[$i]["f"]="";
		}
	}
	for($i=0;$i<count($aut_712);$i++){
		if($aut_712[$i]["f"]){
			$aut_712[$i]["f"]="";
		}
	}
	} // fin recup_noticeunimarc_suite = fin récupération des variables propres à la BDP02
	
function import_new_notice_suite() {
	global $dbh ;
	global $notice_id ;
	
	global $index_sujets ;
	global $pmb_keyword_sep ;
	
	global $info_600_a, $info_600_j, $info_600_x, $info_600_y, $info_600_z ;
	global $info_601_a, $info_601_j, $info_601_x, $info_601_y, $info_601_z ;
	global $info_602_a, $info_602_j, $info_602_x, $info_602_y, $info_602_z ;
	global $info_605_a, $info_605_j, $info_605_x, $info_605_y, $info_605_z ;
	global $info_606_a, $info_606_j, $info_606_x, $info_606_y, $info_606_z ;
	global $info_607_a, $info_607_j, $info_607_x, $info_607_y, $info_607_z ;

	if (is_array($index_sujets)) $mots_cles = implode (" $pmb_keyword_sep ",$index_sujets);
		else $mots_cles = $index_sujets;
	
	for ($a=0; $a<sizeof($info_600_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_600_a[$a][0] ;
		for ($j=0; $j<sizeof($info_600_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_600_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_600_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_600_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_601_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_601_a[$a][0] ;
		for ($j=0; $j<sizeof($info_601_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_601_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_601_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_601_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_602_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_602_a[$a][0] ;
		for ($j=0; $j<sizeof($info_602_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_602_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_602_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_602_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_605_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_605_a[$a][0] ;
		for ($j=0; $j<sizeof($info_605_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_605_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_605_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_605_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_606_a[$a][0] ;
		for ($j=0; $j<sizeof($info_606_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_606_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_606_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_606_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_607_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_607_a[$a][0] ;
		for ($j=0; $j<sizeof($info_607_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_607_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_607_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_607_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_z[$a][$j] ;
		}
	$mots_cles ? $index_matieres = strip_empty_words($mots_cles) : $index_matieres = '';
	$rqt_maj = "update notices set index_l='".addslashes($mots_cles)."', index_matieres=' ".addslashes($index_matieres)." ' where notice_id='$notice_id' " ;
	$res_ajout = mysql_query($rqt_maj, $dbh);

	} // fin import_new_notice_suite
			
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
	global $msg, $dbh ;
	global $nb_expl_ignores ;
	global $prix, $notice_id, $info_995, $typdoc_995, $tdoc_codage, $book_lender_id, 
		$section_bdp19, $sec_search_bdp19,$corresp_bdp19,$sdoc_codage, $book_statut_id, $locdoc_codage, $codstatdoc_995, $statisdoc_codage,
		$cote_mandatory, $book_location_id ;
		
	// lu en 010$d de la notice
	$price = $prix[0];
	
	// la zone 995 est répétable
	for ($nb_expl = 0; $nb_expl < sizeof ($info_995); $nb_expl++) {
		/* RAZ expl */
		$expl = array();
		
		/* préparation du tableau à passer à la méthode */
		$expl['cb'] 	    = $info_995[$nb_expl]['f'];
		$expl['notice']     = $notice_id ;
		
		// $expl['typdoc']     = $info_995[$nb_expl]['r']; chargé en base lors de l'installation, voir plus haut
		$data_doc=array();
		$data_doc['tdoc_libelle'] = "Libellé créé ".$info_995[$nb_expl]['r'];
		$data_doc['duree_pret'] = 15 ; /* valeur par défaut */
		$data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['r'] ;
		if ($tdoc_codage) $data_doc['tdoc_owner'] = $book_lender_id ;
			else $data_doc['tdoc_owner'] = 0 ;
		$expl['typdoc'] = docs_type::import($data_doc);
		
		$expl['cote'] = $info_995[$nb_expl]['k'];
		
		// traitement des sections en fonction de la cote
		// cote[1] ?? G L ou U
		// -Non
		//		trouver le début de la cote dans le tableau
		//		créer la section si besoin
		// -Oui
		//		tronquer de cette première lettre
		//		trouver le nouveau début dans le tableau
		//		remettre la première lettre
		//		créer la section si besoin
		$car = (string)$info_995[$nb_expl]['k'][0];
		switch ($car) {
			case 'U':
				$prefix="" ;
				$suffixe_libelle="" ;
				$info_995[$nb_expl]['k']=substr((string)$info_995[$nb_expl]['k'],1);
				break;
			default:
				$prefix="" ;
				$suffixe_libelle="" ;
				break;
			}
		reset($sec_search_bdp19) ;
		$flag = 0 ;
		while (list($cle_tab,$val_tab)=each($sec_search_bdp19)) {
			$p=strpos((string)$info_995[$nb_expl]['k'],(string)$val_tab) ;
			if (($p!==false) && ($p==0)) {
				$flag=1;
				break;
				}
			}
		if ($flag==1) {
			//Recherche de la section
			for ($i=0; $i<count($corresp_bdp19); $i++) {
				$as=array_search($val_tab,$corresp_bdp19[$i]);
				if (($as!==null)&&($as!==false)) {
					$codage_section_lu=$i+1;
					$libelle_section_lu=$section_bdp19[$i];
				}
			}
		} else {
				$codage_section_lu = "INCONNU" ;
				$libelle_section_lu = "Section inconnue" ;
				}
		
		
		// $expl['section']    = $info_995['8']; à chercher dans docs_section
		$data_doc=array();
		$data_doc['section_libelle'] = $libelle_section_lu;
		$data_doc['sdoc_codage_import'] = $codage_section_lu ;
		if ($sdoc_codage) $data_doc['sdoc_owner'] = $book_lender_id ;
			else $data_doc['sdoc_owner'] = 0 ;
		$expl['section'] = docs_section::import($data_doc);
		
		// le statut est choisi lors de l'import
		
		$expl['statut'] = $book_statut_id;
		
		$expl['location'] = $book_location_id;
		
		// $expl['codestat']   = $info_995[$nb_expl]['q']; 'q' utilisé, éventuellement à fixer par combo_box
		$data_doc=array();
		//$data_doc['codestat_libelle'] = $info_995[$nb_expl]['q']." -Pub visé importé (".$book_lender_id.")";
		$data_doc['codestat_libelle'] = $codstatdoc_995[$info_995[$nb_expl]['q']];
		$data_doc['statisdoc_codage_import'] = $info_995[$nb_expl]['q'] ;
		if ($statisdoc_codage) $data_doc['statisdoc_owner'] = $book_lender_id ;
			else $data_doc['statisdoc_owner'] = 0 ;
		$expl['codestat'] = docs_codestat::import($data_doc);
		
		// $expl['creation']   = $info_995[$nb_expl]['']; à préciser
		// $expl['modif']      = $info_995[$nb_expl]['']; à préciser
                      	
		$expl['note']       = $info_995[$nb_expl]['u'];
		$expl['prix']       = $price;
		$expl['expl_owner'] = $book_lender_id ;
		$expl['cote_mandatory'] = $cote_mandatory ;
		
		$expl['date_depot'] = substr($info_995[$nb_expl]['m'],0,4)."-".substr($info_995[$nb_expl]['m'],4,2)."-".substr($info_995[$nb_expl]['m'],6,2) ;      
		$expl['date_retour'] = substr($info_995[$nb_expl]['n'],0,4)."-".substr($info_995[$nb_expl]['n'],4,2)."-".substr($info_995[$nb_expl]['n'],6,2) ;
		
		$expl_id = exemplaire::import($expl);
		if ($expl_id == 0) {
			$nb_expl_ignores++;
			}
                      	
		//debug : affichage zone 995 
		/*
		echo "995\$a =".$info_995[$nb_expl]['a']."<br />";
		echo "995\$b =".$info_995[$nb_expl]['b']."<br />";
		echo "995\$c =".$info_995[$nb_expl]['c']."<br />";
		echo "995\$d =".$info_995[$nb_expl]['d']."<br />";
		echo "995\$f =".$info_995[$nb_expl]['f']."<br />";
		echo "995\$k =".$info_995[$nb_expl]['k']."<br />";
		echo " section $codage_section_lu <br />";
		echo "995\$m =".$info_995[$nb_expl]['m']."<br />";
		echo "995\$n =".$info_995[$nb_expl]['n']."<br />";
		echo "995\$o =".$info_995[$nb_expl]['o']."<br />";
		echo "995\$q =".$info_995[$nb_expl]['q']."<br />";
		echo "995\$r =".$info_995[$nb_expl]['r']."<br />";
		echo "995\$u =".$info_995[$nb_expl]['u']."<br /><br />";
		*/
		} // fin for
	} // fin traite_exemplaires	TRAITEMENT DES EXEMPLAIRES JUSQU'ICI

// fonction spécifique d'export de la zone 995
function export_traite_exemplaires ($ex=array()) {
	global $msg, $dbh, $opac_biblio_name ;
	
	$subfields["a"] = $opac_biblio_name;
	//$subfields["a"] = $ex -> lender_libelle;
	//$subfields["c"] = $ex -> lender_libelle;
	$subfields["f"] = $ex -> expl_cb;
	$subfields["k"] = $ex -> expl_cote;

	if ($ex->statusdoc_codage_import) $subfields["o"] = $ex -> statusdoc_codage_import;
	if ($ex -> statisdoc_codage_import) $subfields["q"] = $ex -> statisdoc_codage_import;
		else $subfields["q"] = "u";
	if ($ex -> tdoc_codage_import) $subfields["r"] = $ex -> tdoc_codage_import;
		else $subfields["r"] = "uu";
	$subfields["u"] = $ex -> expl_note;
	
	global $export996 ;
	$export996['f'] = $ex -> expl_cb ;
	$export996['k'] = $ex -> expl_cote ;
	$export996['u'] = $ex -> expl_note ;

	$export996['m'] = substr($ex -> expl_date_depot, 0, 4).substr($ex -> expl_date_depot, 5, 2).substr($ex -> expl_date_depot, 8, 2) ;
	$export996['n'] = substr($ex -> expl_date_retour, 0, 4).substr($ex -> expl_date_retour, 5, 2).substr($ex -> expl_date_retour, 8, 2) ;

	$export996['a'] = $ex -> lender_libelle;
	$export996['b'] = $ex -> expl_owner;

	$export996['v'] = $ex -> location_libelle;
	$export996['w'] = $ex -> ldoc_codage_import;

	$export996['x'] = $ex -> section_libelle;
	$export996['y'] = $ex -> sdoc_codage_import;

	$export996['e'] = $ex -> tdoc_libelle;
	$export996['r'] = $ex -> tdoc_codage_import;

	$export996['1'] = $ex -> statut_libelle;
	$export996['2'] = $ex -> statusdoc_codage_import;
	$export996['3'] = $ex -> pret_flag;
	
	global $export_traitement_exemplaires ;
	$export996['0'] = $export_traitement_exemplaires ;
	
	return 	$subfields ;

	}	