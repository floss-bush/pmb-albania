<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_opsys_morges.inc.php,v 1.3 2009-05-16 11:15:41 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/serials.class.php");
require_once($class_path."/categories.class.php");
require_once($class_path."/notice.class.php");
require_once($class_path."/author.class.php");
require_once($class_path."/thesaurus.class.php");
require_once("$class_path/XMLlist.class.php");
require_once("$class_path/editor.class.php");
require_once($class_path."/docs_codestat.class.php");

//	DATA_BASE == "morges"
$flag_titre_serie_recuperation=0;
$flag_depouillements_464=1;
$flag_depouillements_464_doc_sonore=1;
$flag_depouillements_464_doc_imprime=1;	
$flag_import_610_in_mot_cles=0;
$num_thesaurus=3;
$num_thesaurus_centre_interet=2;
$num_thesaurus_610=1;	
$sous_coll='1';


$thes = new thesaurus($num_thesaurus);

$thes_centre_interet = new thesaurus($num_thesaurus_centre_interet);

$thes_610 = new thesaurus($num_thesaurus_610);

function create_categ($th,$num_parent, $libelle, $index,$num_aut='') {
	
	//global $thes;
	$n = new noeuds();
	$n->num_thesaurus = $th->id_thesaurus;
	$n->num_parent = $num_parent;
	$n->autorite = $num_aut;
	
	$n->save();
	
	$c = new categories($n->id_noeud, 'fr_FR');
	$c->libelle_categorie = $libelle;
	$c->index_categorie = $index;
	$c->save();
	
	return $n->id_noeud;
}	

function del_notice($item) {
	global $dbh ;
	$requete_suppr = "delete from analysis where analysis_notice='".$item."' ";
	$result_suppr = @mysql_query($requete_suppr, $dbh);
	$requete_suppr = "delete from notices_categories WHERE notcateg_notice='".$item."' ";
	$result_suppr = @mysql_query($requete_suppr, $dbh);
	$requete_suppr = "delete from notices_langues WHERE num_notice='".$item."' ";
	$result_suppr = @mysql_query($requete_suppr, $dbh);
	$requete_suppr = "delete from responsability WHERE responsability_notice='".$item."' ";
	$result_suppr = @mysql_query($requete_suppr, $dbh);
	$requete_suppr = "delete from bannette_contenu WHERE num_notice='".$item."' ";
	$result_suppr = @mysql_query($requete_suppr, $dbh);
	$requete_suppr = "delete from audit WHERE object_id='".$item."' and type_obj=1 ";
	$result_suppr = @mysql_query($requete_suppr, $dbh);
	$requete_suppr = "delete from notices_custom_values WHERE notices_custom_origine='".$item."' ";
	$result_suppr = @mysql_query($requete_suppr, $dbh);
	$requete_suppr = "delete from notices where notice_id='".$item."' ";
	$result_suppr = @mysql_query($requete_suppr, $dbh);
}

$tpl_beforeupload_expl = "
                <form class='form-$current_module' ENCTYPE=\"multipart/form-data\" METHOD=\"post\" ACTION=\"iimport_expl.php\">
                <h3>".$msg['import_expl_form_titre']."</h3>
                <div class='form-contenu'>

	<INPUT TYPE='hidden' NAME='isbn_mandatory' id='io1' VALUE='0' />
	<INPUT TYPE='hidden' NAME='isbn_dedoublonnage' VALUE='0' />
	<INPUT TYPE='hidden' NAME='cote_mandatory' VALUE='0' />
	<INPUT TYPE='hidden' NAME='book_lender_id' value='1' />
	<INPUT TYPE='hidden' NAME='book_statut_id' value='1' />
	<INPUT TYPE='hidden' NAME='statutnot' value='1' />
	<INPUT TYPE='hidden' NAME='book_location_id' value='1' />
	<input type='hidden' name='isbn_only' value='1'/>

                    <div class='row'>
                        <label class='etiquette'>Supprimer, Mettre à jour ou Ajouter ?</label>
                        </div>
                    <div class='row'>
                        <INPUT TYPE='radio' NAME='que_faire' id='sdc0' VALUE='0' CLASS='radio' /><label for='sdc0'> Supprimer </label><br />
                        <INPUT TYPE='radio' NAME='que_faire' id='sdc1' VALUE='1' CLASS='radio' /><label for='sdc1'> Mettre à jour </label><br />
                        <INPUT TYPE='radio' NAME='que_faire' id='sdc2' VALUE='2' CLASS='radio' /><label for='sdc2'> Ajouter </label><br />
                        </div>

                    <div class='row'>
                        <label class='etiquette' for='txt_suite'>$msg[501]</label>
                        </div>
                    <div class='row'>
                        <INPUT NAME='userfile' class='saisie-80em' TYPE='file' size='60'>
                        <INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\">
                        <INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import_expl\">
                        <INPUT NAME=\"action\" TYPE=\"hidden\" value=\"afterupload\">
                        </div>
                    </div>
                <INPUT TYPE='SUBMIT' class='bouton' NAME='upload' VALUE='".$msg[502]."' />
                </FORM>"; 

$tpl_beforeupload_notices = "";
xml_save_test("$include_path/marc_tables/$lang/function_subst.xml");


function recup_noticeunimarc_suite($notice) {
	global $info_001,$info_464,$info_464_a,$info_464_e,$info_464_f,$info_461_3,$info_461_t,$info_200_a,$info_200_e,$info_210_d,$info_902_a,$info_686_a,$info_462_3,$info_462_t;
	
	global $aut_700,$aut_701,$aut_702,$aut_710,$aut_711,$aut_712;
	global $accomp_345_c;
	global $info_675_a;
	global $info_600_b,$info_601_b,$info_602_b,$info_605_b,$info_606_b,$info_607_b,$info_610_b;
	global $info_610_a,$info_610_b,$info_610_e,$info_610_j,$info_610_x,$info_610_y,$info_610_z,$info_610_3,$info_610;
	global $info_345_d,$info_071_a,$info_071_b;
	global $info_901;
	
	
	$record = new iso2709_record($notice, AUTO_UPDATE); 
	$info_001=$record->get_subfield("001");	
	$info_464=$record->get_subfield_array_array("464");
	$info_464_a=$record->get_subfield_array("464","a");
	$info_464_e=$record->get_subfield_array("464","e");
	$info_464_f=$record->get_subfield_array("464","f");
	
	$info_461_3=$record->get_subfield("461","3");	
	$info_461_t=$record->get_subfield("461","t");	
	$info_200_a=$record->get_subfield("200","a");
	$info_200_e=$record->get_subfield_array("200","e");
	$info_210_d=$record->get_subfield("210","d");
	$info_902_a=$record->get_subfield_array_array("902","a");	
	$info_686_a=$record->get_subfield("686","a");
	
	$info_462_3=$record->get_subfield("462","3");	
	$info_462_t=$record->get_subfield("462","t");
	
	$info_600_b=$record->get_subfield_array_array("600","b");
	$info_601_b=$record->get_subfield_array_array("601","b");
	$info_602_b=$record->get_subfield_array_array("602","b");
	$info_605_b=$record->get_subfield_array_array("605","b");
	$info_606_b=$record->get_subfield_array_array("606","b");
	$info_607_b=$record->get_subfield_array_array("607","b");
	$info_610_b=$record->get_subfield_array_array("610","b");
		
	$info_610_a=$record->get_subfield_array_array("610","a");
	$info_610_b=$record->get_subfield_array_array("610","b");
	$info_610_e=$record->get_subfield_array_array("610","e");
	$info_610_j=$record->get_subfield_array_array("610","j");
	$info_610_x=$record->get_subfield_array_array("610","x");
	$info_610_y=$record->get_subfield_array_array("610","y");
	$info_610_z=$record->get_subfield_array_array("610","z");
	$info_610_3=$record->get_subfield_array_array("610","3");
	
	
	$info_610=$record->get_subfield_array_array("610");
	//$info_610=$record->get_subfield_array_array("610","a","b","3","e");
	
	$aut_700=$record->get_subfield("700","a","b","3","p","4");
	$aut_701=$record->get_subfield("701","a","b","3","p","4");	
	$aut_702=$record->get_subfield("702","a","b","3","p","4");	
	$aut_710=$record->get_subfield("710","a","b","3","p","4");	
	$aut_711=$record->get_subfield("711","a","b","3","p","4");	
	$aut_712=$record->get_subfield("712","a","b","3","p","4");	
	
	$accomp_345_c=$record->get_subfield("345","c");	
	//CDU : classification décimale universelle
	$info_675_a=$record->get_subfield("675","a");
	// Prix pour disque et video
	$info_345_d=$record->get_subfield("345","d");
	// Code commercial
	$info_071_a=$record->get_subfield("071","a");
	// Producteur -> editeur
	$info_071_b=$record->get_subfield("071","b");
	// Centre d'intérêt
	//$info_901_a=$record->get_subfield_array_array("901","a");	
	$info_901=$record->get_subfield("901","a","3");	
	
} 

function xml_save_table($filename,$table) {
	global $table_responsability_function;
	$fp = fopen($filename, "w");
	fwrite($fp,"<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<!DOCTYPE XMLlist SYSTEM \"../../XMLlist.dtd\">\n<XMLlist>\n");
	foreach ($table_responsability_function as $key=>$val) {
		fwrite($fp,"<entry code=\"".$key."\">".$table_responsability_function[$key]."</entry>\n");
	}
	fwrite($fp,"</XMLlist>\n");
	fclose($fp);
	
}
function xml_save_test($filename) {
	global $table_responsability_function;
	$fp = fopen($filename, "a");	
	fclose($fp);
	
}

function update_authors_num_opsys($aut_ ,$responsability_type)
{	
	global $notice_id ;
	global $table_responsability_function;
	global $lang,$include_path;
	global $xml_changement;
	if(!is_array($table_responsability_function)) {
		//$table_responsability_function=unserialize_file("table_responsability_function.tmp");	
		$parser = new XMLlist("$include_path/marc_tables/$lang/function.xml");
		$parser->analyser();
		$table = $parser->table;
		$table_responsability_function = $parser->tablefav;
		if($table_responsability_function)foreach ($table_responsability_function as $key=>$val) {
			$table_responsability_function[$key]=$table[$key];
		}
	}	
	//print"<pre>";print_r($aut_);print"</pre>";
	for($i=0;$i<count($aut_);$i++ ) {
		if ($aut_[$i][3]!="") {
			
			$aut_i_4=str_replace('&',"",$aut_[$i]['4']);
			$aut_i_p=str_replace('&',"",$aut_[$i]['p']);
			$requete="select author_id from authors, responsability where author_name='".addslashes($aut_[$i][a])."' and author_rejete='".addslashes($aut_[$i][b])."' 
				and responsability_notice='$notice_id' and responsability_author = author_id";
			$result=mysql_query($requete);
			if($row = mysql_fetch_row($result)){
				$author_id=$row[0];
				$requete="update authors set author_comment='".addslashes($aut_[$i][3])."' where author_id='$author_id' ";
				mysql_query($requete);
				
				if($aut_i_4>=900) {
					$index='';
					if($table_responsability_function)foreach ($table_responsability_function as $key=>$val) {
						if($table_responsability_function[$key]==$aut_i_4) {
							 $index=$key;
							 break;
						}	 
					}
					if (!$index) {
						// creer 
						$index=count($table_responsability_function)+900;
						$table_responsability_function[$index]=$aut_i_4;
						$xml_changement=1;						
					} 						
					$requete="update responsability SET responsability_fonction='$index' where responsability_notice='$notice_id'
					and responsability_author = $author_id and responsability_fonction=".$aut_i_4;
					$result=mysql_query($requete);
				}					
									
				if ($aut_i_p!="") {
					$requete="delete from responsability where responsability_fonction='' and responsability_author = $author_id and responsability_type=$responsability_type and  responsability_notice='$notice_id'";
					$result=mysql_query($requete);	
					$index='';
					if($table_responsability_function)foreach ($table_responsability_function as $key=>$val) {
						if($table_responsability_function[$key]==$aut_i_p) {
							 $index=$key;
							 break;
						}	 
					}
					if (!$index) {
						// creer 
						$index=count($table_responsability_function)+900;
						$table_responsability_function[$index]=$aut_i_p;
						$xml_changement=1;						
					} 
					//$requete="update responsability SET responsability_fonction='$index' where responsability_notice='$notice_id' and responsability_author = $author_id";
					$requete="insert into responsability SET responsability_fonction='$index' , responsability_notice='$notice_id' , responsability_author = $author_id, responsability_type=$responsability_type";
					$result=mysql_query($requete);						
				} 
			

							
				
			}	
		}		
	}	
}	
function import_new_notice_suite() {
	global $dbh ;
	global $notice_id ;
	
	global $index_sujets ;
	global $pmb_keyword_sep ;
	global $id_notices_custom_opsys,$id_notices_custom_type_opsys;
	global $info_001,$info_464,$info_464_a,$info_464_e,$info_464_f,$info_461_3,$info_461_t,$info_200_a,$info_200_e,$info_210_d,$info_902_a,$info_686_a,$info_462_3,$info_462_t;
	global $num_thesaurus,$thes;
	global $info_675_a;
	global $info_600_a, $info_600_b, $info_600_j, $info_600_x, $info_600_y, $info_600_z ;
	global $info_601_a, $info_601_b, $info_601_j, $info_601_x, $info_601_y, $info_601_z ;
	global $info_602_a, $info_602_b, $info_602_j, $info_602_x, $info_602_y, $info_602_z ;
	global $info_605_a, $info_605_b, $info_605_j, $info_605_x, $info_605_y, $info_605_z ;
	global $info_606_a, $info_606_b, $info_606_j, $info_606_x, $info_606_y, $info_606_z ;
	global $info_607_a, $info_607_b, $info_607_j, $info_607_x, $info_607_y, $info_607_z ;
	global $info_610_a, $info_610_e, $info_610_b, $info_610_j, $info_610_x, $info_610_y, $info_610_z,$info_610_3,$info_610 ;
	
	global $bulletin_id;
	global $flag_titre_serie_recuperation;
	global $flag_depouillements_464,$flag_depouillements_464_doc_sonore,$flag_depouillements_464_doc_imprime;
	global $aut_700,$aut_701,$aut_702,$aut_710,$aut_711,$aut_712;
	global $accomp_345_c;
	global $table_responsability_function;
	global $lang,$include_path,$xml_changement;
	global $info_345_d;
	global $info_071_a,$info_071_b;
	global $thes_centre_interet,$num_thesaurus_centre_interet,$info_901;
	global $flag_import_610_in_mot_cles,$thes_610, $num_thesaurus_610;
	global $collection_225, $sous_coll;
	
	// prix dvd et video
	if($info_345_d[0]) {
		$requete="update notices set prix='".addslashes($info_345_d[0])."' where notice_id='$notice_id' ";
		mysql_query($requete);	
	}
	// EAN
	if($info_071_a[0]) {
		$requete="update notices set code='".addslashes($info_071_a[0])."' where notice_id='$notice_id' ";
		mysql_query($requete);	
	}	
	// Producteur -> Editeur
	if($info_071_b[0]) {
		
		$nom = $info_071_b[0];		
		$requete="select ed_id from publishers where ed_name='".addslashes($nom)."' ";
		$result=mysql_query($requete);
		if($row = mysql_fetch_row($result)){
			$ed_id=$row[0];	
		} else {
			$requete = "insert into publishers SET ed_name='$nom', ";	
			$requete .= "index_publisher=' ".strip_empty_words($nom)." '";
			mysql_query($requete);	
			$ed_id=mysql_insert_id();
		}		
		$requete="update notices set ed1_id='".$ed_id."' where notice_id='$notice_id' ";
		mysql_query($requete);	
	}	
	// 345_c Matériel d'accompagnement
	if($accomp_345_c[0]) {
		$requete="update notices set accomp='".addslashes($accomp_345_c[0])."' where notice_id='$notice_id' ";
		mysql_query($requete);	
	}
	update_authors_num_opsys($aut_700,0);
	update_authors_num_opsys($aut_701,1);
	update_authors_num_opsys($aut_702,2);
	update_authors_num_opsys($aut_710,2);
	update_authors_num_opsys($aut_711,2);
	update_authors_num_opsys($aut_712,2);
	if($xml_changement) {
		xml_save_table("$include_path/marc_tables/$lang/function_subst.xml",$table_responsability_function);
	}
	
	//UDC
	if($info_675_a[0]) {
		$indexint_id = indexint::import(clean_string($info_675_a[0]));
		$requete="update notices set indexint='$indexint_id' where notice_id='$notice_id' ";
		mysql_query($requete);	
	}
	
	if ($aut_700[0][a]!="") {
		if ($aut_700[0][3]!="") {
			$requete="select author_id from authors where author_name='".addslashes($aut_700[0][a])."' and author_rejete='".addslashes($aut_700[0][b])."' ";
			$result=mysql_query($requete);
			if($row = mysql_fetch_row($result)){
				$author_id=$row[0];
				$requete="update authors set author_comment='".addslashes($aut_700[0][3])."' where author_id='$author_id' ";
				mysql_query($requete);	
			}	
		}		
	}	
	
	if ($sous_coll=='1'&& $collection_225[0]['a']!="" && $collection_225[1]['a']!="") {
			
		$q="select coll_id from notices where notice_id='$notice_id' ";
		$r=mysql_query($q, $dbh);
		$coll_id = mysql_result($r,0,0);
		if ($coll_id!='0') { 
			/* sous collection */
			$subcollec['name']=clean_string($collection_225[1]['a']);
			$subcollec['coll_parent']=$coll_id;
			$subcoll_id = subcollection::import($subcollec);
			$requete="update notices set subcoll_id='$subcoll_id' where notice_id='$notice_id' ";
			mysql_query($requete);	
			
		}
	}
	
	
	$bulletin_id=0;
	// Effacement de la notice temporaire eventuelle
	list($num_opsys,$type_opsys)= split(" ",$info_001[0]);
	
	
	if(($type_opsys=='UMO:13') ){
		$requete="update notices set niveau_biblio='b', niveau_hierar='2' where notice_id='$notice_id' ";
		//print "$requete <br />";
		mysql_query($requete);	
	}	
	if(($type_opsys=='UMO:23') || ($type_opsys=='UMO:3')) { // Titre de périodique
		$requete="select * from notices_custom_values where notices_custom_small_text='".$num_opsys."'";	
		//print "new $type_opsys:    $requete <br />";
		$resultat=mysql_query($requete);
		//Notice existe-t-elle comme notice temporaire?
		if (@mysql_num_rows($resultat)) {
			//Si oui, récupération id notice temporaire a supprimer	
			$old_n=mysql_fetch_object($resultat);
			$notice_id_old=$old_n->notices_custom_origine;
			// modifie les anciennes relations sur la vrai notice
			$requete="update notices_relations set linked_notice='$notice_id' where linked_notice='$notice_id_old' ";
			//print "$requete <br />";
			mysql_query($requete);
			
			$requete="update bulletins set bulletin_notice='$notice_id' where bulletin_notice='$notice_id_old' ";
			//print "$requete <br />";
			mysql_query($requete);
			
			$requete="update notices set niveau_biblio='s', niveau_hierar='1' where notice_id='$notice_id' ";
			//print "$requete <br />";
			mysql_query($requete);	
					
			// suppression de la notice temporaire
			$requete="delete from notices where notice_id=".$notice_id_old;
			mysql_query($requete);
			$requete="delete from notices_custom_values where notices_custom_origine='".$notice_id_old."'";	
			mysql_query($requete);
			//print "$requete <br />";					
		} else {
			$requete="update notices set niveau_biblio='s', niveau_hierar='1' where notice_id='$notice_id' ";
			mysql_query($requete);	
		}	
	} else if(($type_opsys=='UMO:41') || ($type_opsys=='UMO:42')){	 // Dépouillement (hors article)
		//Rien
	} else if($type_opsys=='UMO:43'){	 // Dépouillement article de périodique		
		$requete="select * from notices_custom_values where notices_custom_small_text='".$num_opsys."'";	
		//print "new $type_opsys:    $requete <br />";
		$resultat=mysql_query($requete);
		//Notice existe-t-elle comme notice temporaire?
		if (@mysql_num_rows($resultat)) {
			//Si oui, récupération id notice temporaire a supprimer	
			$old_n=mysql_fetch_object($resultat);
			$notice_id_old=$old_n->notices_custom_origine;
			// modifie les anciennes relations sur la vrai notice
					
			$requete="update analysis set analysis_notice='$notice_id' where analysis_notice='$notice_id_old' ";
			//print "$requete <br />";
			mysql_query($requete);
			
			$requete="update notices set niveau_biblio='a', niveau_hierar='2' where notice_id='$notice_id' ";
			//print "$requete <br />";
			mysql_query($requete);	
					
			// suppression de la notice temporaire
			$requete="delete from notices where notice_id=".$notice_id_old;
			mysql_query($requete);
			$requete="delete from notices_custom_values where notices_custom_origine='".$notice_id_old."'";	
			mysql_query($requete);	
		} else {
			$requete="update notices set niveau_biblio='a', niveau_hierar='2' where notice_id='$notice_id' ";
			mysql_query($requete);	
			//print "$requete <br />";
		}	
				
	} else {	// UMO 1 2 4 8 21 22 24 28
		$is_serie=($type_opsys=='UMO:21')||($type_opsys=='UMO:22')||($type_opsys=='UMO:24')||($type_opsys=='UMO:28');
		if((($flag_titre_serie_recuperation)&&($is_serie))||(!$is_serie)) {
			$requete="select * from notices_custom_values where notices_custom_small_text='".$num_opsys."'";	
			//print "new $type_opsys:    $requete <br />";
			$resultat=mysql_query($requete);
			//Notice existe-t-elle comme notice temporaire?
			if (@mysql_num_rows($resultat)) {
				//Si oui, récupération id notice temporaire a supprimer	
				$old_n=mysql_fetch_object($resultat);
				$notice_id_old=$old_n->notices_custom_origine;
				// modifie les anciennes relations sur la vrai notice
				$requete="update notices_relations set linked_notice='$notice_id' where linked_notice='$notice_id_old' ";
				//print "$requete <br />";
				mysql_query($requete);
				// suppression de la notice temporaire
				$requete="delete from notices where notice_id=".$notice_id_old;
				mysql_query($requete);
				$requete="delete from notices_custom_values where notices_custom_origine='".$notice_id_old."'";	
				mysql_query($requete);
				//print "$requete <br />";					
			} 
		} else if ($is_serie) { // suprimer la notice car on en veut pas
				del_notice($notice_id);	
				return;							
		}
	}
	
	//Genre dans Thesaurus
	for ($i=0; $i<count($info_902_a); $i++) {
		for ($j=0; $j<count($info_902_a[$i]); $j++) {
			$resultat = categories::searchLibelle(addslashes($info_902_a[$i][$j]), $num_thesaurus, 'fr_FR');
			if (!$resultat){
				/*vérification de l'existence des categs, sinon création */
				$resultat = create_categ($thes,$thes->num_noeud_racine, $info_902_a[$i][$j], ' '.strip_empty_words($info_902_a[$i][$j]).' ');
			} 
			/* ajout de l'indexation à la notice dans la table notices_categories*/
			$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', num_noeud='".$resultat."' " ;
			$res_ajout = @mysql_query($rqt_ajout, $dbh);
		}
	}
	
	 
	//centre interet dans Thesaurus
	for ($i=0; $i<count($info_901); $i++) {
		
		$resultat = categories::searchLibelle(addslashes($info_901[$i]['a']), $num_thesaurus_centre_interet, 'fr_FR');
		if (!$resultat){
			/*vérification de l'existence des categs, sinon création */
			$resultat = create_categ($thes_centre_interet,$thes_centre_interet->num_noeud_racine, $info_901[$i]['a'], ' '.strip_empty_words($info_901[$i]['a']).' ',$info_901[$i][3]);
		} 
		/* ajout de l'indexation à la notice dans la table notices_categories*/
		$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', num_noeud='".$resultat."' " ;
		$res_ajout = @mysql_query($rqt_ajout, $dbh);
		
	}	
	
	
	// Pcdm (686) dans le plan de classement
//	print "<pre>";	print_r($info_686_a);print "</pre>"; 
	if(count($info_686_a) ) {
		
			//vérification de l'existence des categs, sinon création 
			$requete="select indexint_id from indexint where indexint_name='".addslashes($info_686_a[0])."' and num_pclass='2'";
			//print "$requete <br />"; 
			$result=mysql_query($requete);	
			if($row = mysql_fetch_row($result)){
				$indexint_id=$row[0];
			} else {
				$requete="insert into indexint SET indexint_name='".addslashes($info_686_a[0])."', num_pclass='2' ";
				//print "$requete <br />"; 
				mysql_query($requete);	
				$indexint_id=mysql_insert_id();
			}		
					
			$requete="update notices set indexint='$indexint_id' where notice_id='$notice_id' ";	
		//	print "$requete <br />"; 	
			@mysql_query($requete, $dbh);
			
	}
	
	
//	if (is_array($index_sujets)) $mots_cles = implode (" $pmb_keyword_sep ",$index_sujets);
//		else $mots_cles = $index_sujets;
	$mots_cles='';

	for ($a=0; $a<sizeof($info_600_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_600_a[$a][0] ;
		for ($j=0; $j<sizeof($info_600_b[$a]); $j++) $mots_cles .= " , ".$info_600_b[$a][0];		
		for ($j=0; $j<sizeof($info_600_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_600_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_600_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_600_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_601_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_601_a[$a][0] ;
		for ($j=0; $j<sizeof($info_601_b[$a]); $j++) $mots_cles .= " , ".$info_601_b[$a][0];	
		for ($j=0; $j<sizeof($info_601_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_601_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_601_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_601_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_602_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_602_a[$a][0] ;
		for ($j=0; $j<sizeof($info_602_b[$a]); $j++) $mots_cles .= " , ".$info_602_b[$a][0];	
		for ($j=0; $j<sizeof($info_602_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_602_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_602_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_602_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_605_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_605_a[$a][0] ;
		for ($j=0; $j<sizeof($info_605_b[$a]); $j++) $mots_cles .= " , ".$info_605_b[$a][0];	
		for ($j=0; $j<sizeof($info_605_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_605_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_605_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_605_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_606_a[$a][0] ;
		for ($j=0; $j<sizeof($info_606_b[$a]); $j++) $mots_cles .= " , ".$info_606_b[$a][0];	
		for ($j=0; $j<sizeof($info_606_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_606_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_606_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_606_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_607_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_607_a[$a][0] ;
		for ($j=0; $j<sizeof($info_607_b[$a]); $j++) $mots_cles .= " , ".$info_607_b[$a][0];	
		for ($j=0; $j<sizeof($info_607_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_607_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_607_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_607_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_z[$a][$j] ;
		}
		
	if($flag_import_610_in_mot_cles) {
		for ($a=0; $a<sizeof($info_610_a); $a++) {
			$mots_cles .= " $pmb_keyword_sep ".$info_610_a[$a][0] ;
			for ($j=0; $j<sizeof($info_610_b[$a]); $j++) $mots_cles .= " , ".$info_610_b[$a][0];	
			for ($j=0; $j<sizeof($info_610_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_610_j[$a][$j] ;
			for ($j=0; $j<sizeof($info_610_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_610_x[$a][$j] ;
			for ($j=0; $j<sizeof($info_610_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_610_y[$a][$j] ;
			for ($j=0; $j<sizeof($info_610_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_610_z[$a][$j] ;	
			if (sizeof($info_610_e[$a]))	{
				$mots_cles .= " ( ";
				for ($j=0; $j<sizeof($info_610_e[$a]); $j++) {
					if ($j) $mots_cles .= " , ";
					$mots_cles .= $info_610_e[$a][$j] ;
				}	
				$mots_cles .= " ) ";
			}	
		}	
	} 

	//print"<pre>";print_r($info_610_a);print_r($info_610_e);print"</pre>";
	
	for ($a=0; $a<sizeof($info_610_a); $a++) {
		
		$resultat = categories::searchLibelle(addslashes($info_610_a[$a][0]), $num_thesaurus_610, 'fr_FR', $thes_610->num_noeud_racine);		
		if (!$resultat){
			/*vérification de l'existence des categs, sinon création */
			$resultat = create_categ($thes_610,$thes_610->num_noeud_racine, $info_610_a[$a][0], ' '.strip_empty_words($info_610_a[$a][0]).' ',$info_610_3[$a][0]);
		} 
		for ($j=0; $j<sizeof($info_610_e[$a]); $j++) {
			if($info_610_e[$a][$j]){
				$num_parent=$resultat;
				$resultat = categories::searchLibelle(addslashes($info_610_e[$a][$j]), $num_thesaurus_610, 'fr_FR',$num_parent);		
				if (!$resultat){
					/*vérification de l'existence des categs, sinon création */
					$resultat = create_categ($thes_610,$num_parent, $info_610_e[$a][$j], ' '.strip_empty_words($info_610_e[$a][$j]).' ',$info_610_3[$a][0]);
				} 
			}	
		}	
		
		
		/* ajout de l'indexation à la notice dans la table notices_categories*/
		$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', num_noeud='".$resultat."' " ;
		$res_ajout = @mysql_query($rqt_ajout, $dbh);
		

	}	
	
		
	if( substr($mots_cles,0,2)== ' ;')$mots_cles=substr($mots_cles,2);
	$mots_cles ? $index_matieres = strip_empty_words($mots_cles) : $index_matieres = '';
	$rqt_maj = "update notices set index_l='".addslashes($mots_cles)."', index_matieres=' ".addslashes($index_matieres)." ' where notice_id='$notice_id' " ;
	$res_ajout = mysql_query($rqt_maj, $dbh);
	
	// insert du param perso mémorisant le numero Opsys de la notice
	if(!$id_notices_custom_opsys) {
		$rqt="select idchamp from notices_custom where name='num_opsys'";
		$res = mysql_query($rqt, $dbh);
		if ($res && ($r = mysql_fetch_object($res)))	$id_notices_custom_opsys= $r->idchamp;
		$rqt="select idchamp from notices_custom where name='type_opsys'";
		$res = mysql_query($rqt, $dbh);
		if ($res && ($r = mysql_fetch_object($res)))	$id_notices_custom_type_opsys= $r->idchamp;		
	}
		
	$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values($id_notices_custom_opsys,$notice_id,'".addslashes($num_opsys)."')";
	mysql_query($requete);
	$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values($id_notices_custom_type_opsys,$notice_id,'".addslashes($type_opsys)."')";
	mysql_query($requete);
	
	$requete="select * from notices where notice_id=$notice_id";
	$resultat=mysql_query($requete);
	$r=mysql_fetch_object($resultat);
	
	// $flag_depouillements_464_doc_sonore //	$flag_depouillements_464_doc_imprime=0;
	// Traiter les dépouillement du champ 464
	if ($flag_depouillements_464) {
		if(is_array($info_464)) {
			switch ($type_opsys) {
				case 'UMO:1':  // Documents imprimés Unimarc moyen
					$niveau_biblio='m';
					$niveau_hierar= '0';	
				break;
				case 'UMO:2': // Documents sonores Unimarc moyen
					$niveau_biblio='m';
					$niveau_hierar= '0';		
				break;					
				case 'UMO:13':  // bulletin
					$niveau_biblio='a';
					$niveau_hierar= '2';				
				break;					
				case 'UMO:4': // Documents audiovisuel Unimarc moyen
					$niveau_biblio='m';
					$niveau_hierar= '0';		
				break;					
				case 'UMO:8': // Logiciels - CDrom
					$niveau_biblio='m';
					$niveau_hierar= '0';						
				break;		 									 							
				default:
					$niveau_biblio='m';
					$niveau_hierar= '0';			
				break;	
			}	
			//print "<pre>";print_r	($info_464);print "</pre>";
			for ($i=0; $i<sizeof($info_464); $i++) {	
				$a_464=$e_464=$_3_464=array();
				$author_id=0;
				for ($j=0; $j<sizeof($info_464[$i]); $j++) {		
					if($info_464[$i][$j]['label']=='a') $a_464[]=$info_464[$i][$j]['content'];
					if($info_464[$i][$j]['label']=='e') $e_464[]=$info_464[$i][$j]['content'];		// Complément du titre	
					if($info_464[$i][$j]['label']=='3') $_3_464[]=$info_464[$i][$j]['content'];		// Complément du titre			
				}
				//print "<pre>";print_r	($_3_464);print "</pre>";
				for ($j=0; $j<sizeof($a_464); $j++) {	
					if($_3_464[$j]) {
						$requete="select author_id from authors where author_comment='".addslashes($_3_464[$j])."' ";			 
						$result=mysql_query($requete);	
						if($row = mysql_fetch_row($result)){
							$author_id=$row[0];
							//print $author_id."<br />";
							
							
						}
					}
					if($a_464[$j] != '...') {
						$requete="insert into notices set typdoc='$r->typdoc', tit1 ='".addslashes($a_464[$j])."',tit2 ='".addslashes($e_464[$j])."', niveau_biblio='$niveau_biblio',niveau_hierar='$niveau_hierar'  ";
						//if ($type_opsys="UMO:2") print $requete ."<br />";
						mysql_query($requete);
						$depouille_id=mysql_insert_id();
						if ($depouille_id) {
							//link notice
							$requete="insert into notices_relations set num_notice='$depouille_id', linked_notice ='$notice_id', relation_type='a' ";
							mysql_query($requete);
							if( $author_id ) {
								$requete="insert into responsability SET responsability_fonction='070' , responsability_notice='$depouille_id' , responsability_author = $author_id, responsability_type=0";
								//print $requete."<br />";
								$result=mysql_query($requete);
							}		
						}
					}	
				}	
			} 	
			
			/*
			for ($i=0; $i<sizeof($info_464); $i++) {	
				$a_464='';	
				$e_464='';
				for ($j=0; $j<sizeof($info_464[$i]); $j++) {		
					if($info_464[$i][$j]['label']=='a') $a_464=$info_464[$i][$j]['content'];
					if($info_464[$i][$j]['label']=='e') $e_464=$info_464[$i][$j]['content'];
				}	
				$requete="insert into notices set typdoc='$r->typdoc', tit1 ='".addslashes($a_464)."',tit2 ='".addslashes($e_464)."', niveau_biblio='$niveau_biblio',niveau_hierar='$niveau_hierar'  ";
				//if ($type_opsys="UMO:2") print $requete ."<br />";
				mysql_query($requete);
				$depouille_id=mysql_insert_id();
				if ($depouille_id) {
					//link notice
					$requete="insert into notices_relations set num_notice='$depouille_id', linked_notice ='$notice_id', relation_type='a' ";
					mysql_query($requete);
				}
			} 	
			*/
			
			
			
		}
	}



	// $info_461_3,$info_461_t : dépendance d'une notice chapeau 
	for ($_3=0; $_3<sizeof($info_461_3); $_3++) {	
		switch ($type_opsys) {
			case 'UMO:1': case 'UMO:2': case 'UMO:4': case 'UMO:8':		
				if($flag_titre_serie_recuperation) { // que si on veut 
					$requete="select notices_custom_origine from notices_custom_values where notices_custom_small_text='".$info_461_3[$_3]."'";
					//print "$requete  <br />";
					$resultat=mysql_query($requete);
					//Notice chapeau existe-t-elle ?
					if (@mysql_num_rows($resultat)) {
						//Si oui, récupération id
						$chapeau_id=mysql_result($resultat,0,0);					
					} else {
						$niveau_biblio='m';
						$niveau_hierar='1';
						// Création de la notice temporaire chapeau
						$requete="insert into notices set typdoc='$r->typdoc', tit1 ='".addslashes($info_461_t[$a])."' , niveau_biblio='$niveau_biblio',niveau_hierar='$niveau_hierar'  ";
						//print "$requete  <br />";			
						mysql_query($requete);
						$chapeau_id=mysql_insert_id();
						$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values($id_notices_custom_opsys,$chapeau_id,'".addslashes($info_461_3[$_3])."')";
						//print "$requete <br />";
						mysql_query($requete);
					}	
					if ($chapeau_id) {
						//link notice
						$requete="insert into notices_relations set num_notice='$notice_id', linked_notice ='$chapeau_id', relation_type='a' ";
						//print "$requete  <br />";
						mysql_query($requete);
					}		
				}	
			break;				
			case 'UMO:13': // bulletin  de périodique
				$requete="select notices_custom_origine from notices_custom_values where notices_custom_small_text='".$info_461_3[$_3]."'";
				//print "$requete  <br />";
				$resultat=mysql_query($requete);
				//Notice chapeau existe-t-elle ?
				if (@mysql_num_rows($resultat)) {
					//Si oui, récupération id
					$chapeau_id=mysql_result($resultat,0,0);					
				} else {
					$niveau_biblio='s';
					$niveau_hierar='1';
					// Création de la notice temporaire chapeau
					$requete="insert into notices set typdoc='$r->typdoc', tit1 ='".addslashes($info_461_t[$a])."' , niveau_biblio='$niveau_biblio',niveau_hierar='$niveau_hierar'  ";
					//print "$requete  <br />";			
					mysql_query($requete);
					$chapeau_id=mysql_insert_id();
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values($id_notices_custom_opsys,$chapeau_id,'".addslashes($info_461_3[$_3])."')";
					//print "$requete <br />";
					mysql_query($requete);
				}	
				if ($chapeau_id) {
					//link notice
					$requete="insert into notices_relations set num_notice='$notice_id', linked_notice ='$chapeau_id', relation_type='b' , rank ='1'";
					//print "$requete  <br />";
					mysql_query($requete);
										
					// création bulletin
					$info=array();
					$bulletin=new bulletinage("",$chapeau_id);
					$info['bul_titre']=addslashes($info_200_a[0]);
					$info['bul_no']=addslashes($info_200_e[0]);
					$info['bul_date']=addslashes($info_200_e[1]);
					
					$info['date_date']=gen_date($info_200_e[1],$info_210_d[0]);
					$bulletin->bull_num_notice=$notice_id;
					
					$bulletin_id=$bulletin->update($info,true);
				}											
			break;		 															
			default:
							
			break;	
		}	
	}	
	
	
	// $info_462_3,$info_462_t : Dépouillement, article 
	for ($_3=0; $_3<sizeof($info_462_3); $_3++) {	
		switch ($type_opsys) {
			case 'UMO:1': case 'UMO:2': case 'UMO:8':				
				$requete="select notices_custom_origine from notices_custom_values where notices_custom_small_text='".$info_462_3[$_3]."'";
				//print "$requete  <br />";
				$resultat=mysql_query($requete);
				//Notice chapeau existe-t-elle ?
				if (@mysql_num_rows($resultat)) {
					//Si oui, récupération id
					$chapeau_id=mysql_result($resultat,0,0);					
				} else {
					$niveau_biblio='m';
					$niveau_hierar='0';
					// Création de la notice temporaire chapeau
					$requete="insert into notices set typdoc='$r->typdoc', tit1 ='".addslashes($info_462_t[$a])."' , niveau_biblio='$niveau_biblio',niveau_hierar='$niveau_hierar'  ";
					//print "$requete  <br />";			
					mysql_query($requete);
					$chapeau_id=mysql_insert_id();
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values($id_notices_custom_opsys,$chapeau_id,'".addslashes($info_462_3[$_3])."')";
					//print "$requete <br />";
					mysql_query($requete);
				}	
				if ($chapeau_id) {
					//link notice
					$requete="insert into notices_relations set num_notice='$notice_id', linked_notice ='$chapeau_id', relation_type='a' ";
					//print "$requete  <br />";
					mysql_query($requete);
				}		
			break;				
			case 'UMO:13': // bulletin de périodique => création des articles
				$requete="select notices_custom_origine from notices_custom_values where notices_custom_small_text='".$info_462_3[$_3]."'";
				//print "$requete  <br />";
				$resultat=mysql_query($requete);
				//Notice article existe-t-elle ?
				if (@mysql_num_rows($resultat)) {
					//Si oui, récupération id
					$article_id=mysql_result($resultat,0,0);					
				} else {
					$niveau_biblio='a';
					$niveau_hierar='2';
					// Création de la notice temporaire de l'article
					$requete="insert into notices set typdoc='$r->typdoc', tit1 ='".addslashes($info_462_t[$a])."' , niveau_biblio='$niveau_biblio',niveau_hierar='$niveau_hierar'  ";
					//print "$requete  <br />";			
					mysql_query($requete);
					$article_id=mysql_insert_id();
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values($id_notices_custom_opsys,$article_id,'".addslashes($info_462_3[$_3])."')";
					//print "$requete <br />";
					mysql_query($requete);
				}	
				if ($article_id) {
					//lien article de bulletin créé dans table analitique
					$requete="insert into analysis set analysis_bulletin='$bulletin_id', analysis_notice ='$article_id' ";
					//print "$requete  <br />";		
					mysql_query($requete);
				}											
			break;		 															
			default:
							
			break;	
		}	
	}	
} // fin import_new_notice_suite
		
function gen_date($bul_date,$ed_date) {
	$d_field=split(' ',trim($bul_date));
	$nb=count($d_field);
	$mysql_date='';
	if($nb==0) {
		if(is_numeric ($ed_date)) {	
			$year=$ed_date;
			$month='01';
			$day='01';	
			$mysql_date = $year.'-'.$month.'-'.$day;				
		} else {
			$rqt= "SELECT curdate()";
		 	if($result=mysql_query($rqt))
				if($row = mysql_fetch_row($result))	$mysql_date = $row[0];
		}	
		return $mysql_date;	
	}	
	if($nb>0) {		
		$year=$d_field[count($d_field)-1];
	}	
	if($nb>1) {	
		$str_month=strtolower($d_field[count($d_field)-2]);
		switch($str_month) {
			case "janvier":$month='01';break;
			case "février":case "fevrier":$month='02';break;
			case "mars":$month='03';break;
			case "avril":$month='04';break;
			case "mai":$month='05';break;
			case "juin":$month='06';break;
			case "juillet":$month='07';break;
			case "aout":case "août":$month='08';break;
			case "septembre":$month='09';break;
			case "octobre":$month='10';break;
			case "novembre":$month='11';break;
			case "décembre":case "decembre":$month='12';break;
			default:
				$month='01';			
			break;	
		}			
	}	
	if($nb>2) {
		$day=$d_field[count($d_field)-3];
	} else $day='01';		
	
	$date = $year.'-'.$month.'-'.$day;
	$rqt= "SELECT DATE_ADD('" .$date. "', INTERVAL 0 YEAR)";
	if($result=mysql_query($rqt))
		if($row = mysql_fetch_row($result))	$mysql_date= $row[0];
	
	if(!$mysql_date) {
		$rqt= "SELECT curdate()";
 		if($result=mysql_query($rqt))
			if($row = mysql_fetch_row($result))	$mysql_date = $row[0];	
	}	
	return $mysql_date;	
}
		
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
	global $msg, $dbh ;
	global $nb_expl_ignores ;
	global $prix, $notice_id, $info_996, $info_995, $info_001,$info_345_d ;
	global $bulletin_id;
	global $id_expl_fournisseur_opsys,$id_expl_inventaire_opsys;
	// Afin de ne pas remettre en cause le script programmé en 995 :
	$info_995 = $info_996 ;
	// lu en 010$d de la notice
	$price = $prix[0];
	// prix dvd et video
	if($info_345_d[0]) {
		$price=$info_345_d[0];
	}
	// la zone 995 est répétable

	for ($nb_expl = 0; $nb_expl < sizeof ($info_995); $nb_expl++) {
		
		/* RAZ expl */
		$expl = array();
		/* préparation du tableau à passer à la méthode */
		$expl['notice']     = $notice_id ;
		$expl['cb'] 	    = $info_995[$nb_expl]['f'];
        $expl['cote'] 		= $info_995[$nb_expl]['k'];
		$expl['note']       = $info_995[$nb_expl]['u'].$info_995[$nb_expl]['5'];
		$expl['prix']       = $price;
		$expl['cote_mandatory'] = 0 ;
		
		$expl['date_depot'] = substr($info_995[$nb_expl]['m'],0,4)."-".substr($info_995[$nb_expl]['m'],4,2)."-".substr($info_995[$nb_expl]['m'],6,2) ;      
		$expl['date_retour'] = substr($info_995[$nb_expl]['n'],0,4)."-".substr($info_995[$nb_expl]['n'],4,2)."-".substr($info_995[$nb_expl]['n'],6,2) ;

		// propriétaire
		$owner=array();
		$owner['lender_libelle'] = $info_995[$nb_expl]['a'] ;
		if (!$owner['lender_libelle']) $owner['lender_libelle'] = $info_995[$nb_expl]['b'] ;
		if (!$owner['lender_libelle']) $owner['lender_libelle'] = 'defaut' ;
		$expl['expl_owner'] = lender::import($owner);
		$book_lender_id = $expl['expl_owner'] ;
		
		// docs_location
		$data_doc=array();
		$data_doc['location_libelle']  = $info_995[$nb_expl]['v'] ;
		$data_doc['locdoc_codage_import'] = $info_995[$nb_expl]['w'] ;
		if (!$data_doc['locdoc_codage_import']) $data_doc['locdoc_codage_import'] = $data_doc['location_libelle'] ;
		//$data_doc['locdoc_owner'] = $book_lender_id ;
		$data_doc['locdoc_owner'] = 0 ;
		$expl['location'] = docs_location::import($data_doc);

		// docs_section
		$data_doc=array();
		$data_doc['section_libelle']  = $info_995[$nb_expl]['x'] ;
		$data_doc['sdoc_codage_import'] = $info_995[$nb_expl]['y'] ;
		if (!$data_doc['sdoc_codage_import']) $data_doc['sdoc_codage_import'] = $data_doc['section_libelle'] ;
		//$data_doc['sdoc_owner'] = $book_lender_id ;
		$data_doc['sdoc_owner'] = 0;
		$expl['section'] = docs_section::import($data_doc);
		
		// typedoc
		$data_doc=array();
		$data_doc['tdoc_libelle'] = $info_995[$nb_expl]['e'];
		if(!$data_doc['tdoc_libelle']) $data_doc['tdoc_libelle']= $info_995[$nb_expl]['r']; 
		$data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['r'] ;
		if (!$data_doc['tdoc_codage_import']) $data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['e'] ;
		$data_doc['duree_pret'] = 28 ; /* valeur par défaut */
		$data_doc['tdoc_owner'] = $book_lender_id ;
		$expl['typdoc'] = docs_type::import($data_doc);		
		
		// statut doc
		$data_doc=array();
		$data_doc['statut_libelle'] = $info_995[$nb_expl]['1'];
		$data_doc['statusdoc_codage_import'] = $info_995[$nb_expl]['2'] ;
		if (!$data_doc['statusdoc_codage_import']) $data_doc['statusdoc_codage_import'] = $info_995[$nb_expl]['1'] ;
		$data_doc['pret_flag'] = $info_995[$nb_expl]['3'] ; 
		$data_doc['statusdoc_owner'] = $book_lender_id ;
		$expl['statut'] = docs_statut::import($data_doc);

		// codestat
		//$expl['codestat'] = 10 ;	
		$data_codestat['codestat_libelle'] = strtolower($info_995[$nb_expl]['x']) ;
		$expl['codestat'] = docs_codestat::import($data_codestat);
		
		// quoi_faire
		// $que_faire vient du formulaire de chargement, à utiliser en attente de l'info dans la zone 996
		global $que_faire ;	
		if ($que_faire=="") {
			if ($info_995[$nb_expl]['0']) $expl['quoi_faire'] = $info_995[$nb_expl]['0']  ;
				else $expl['quoi_faire'] = 2 ;
		} else {
			$expl['quoi_faire'] = $que_faire ;
		}
		// 0 : supprimer, 1 ou vide : Mettre à jour ou ajouter, 2 : ajouter si possible, sinon rien.
		//print "<pre>";print_r($info_995);print_r($expl);print "</pre>";
		$expl_id = exemplaire::import($expl);
		if ($expl_id == 0) {
			$nb_expl_ignores++;
		}
        	       	
		list($num_opsys,$type_opsys)= split(" ",$info_001[0]);
		if(($type_opsys=='UMO:13') ){
			$requete="update exemplaires set expl_notice='0', expl_bulletin='$bulletin_id' where expl_id='$expl_id' ";
			//print "$requete <br />";
			mysql_query($requete);	
		}      
		
		if(!$id_expl_fournisseur_opsys) {
			$rqt="select idchamp from expl_custom where name='fournisseur'";
			$res = mysql_query($rqt, $dbh);
			if ($res && ($r = mysql_fetch_object($res)))	$id_expl_fournisseur_opsys= $r->idchamp;
		}			
		if(!$id_expl_inventaire_opsys) {
			$rqt="select idchamp from expl_custom where name='inventaire'";
			$res = mysql_query($rqt, $dbh);
			if ($res && ($r = mysql_fetch_object($res)))	$id_expl_inventaire_opsys= $r->idchamp;		
		}
		//inventaire en champ perso
		if($field=$info_995[$nb_expl]['6']) {		
			$requete="insert into expl_custom_values (expl_custom_champ,expl_custom_origine,expl_custom_small_text) values($id_expl_inventaire_opsys,$expl_id,'".addslashes($field)."')";
			mysql_query($requete);
		}   
		//Fournisseur en champ perso
		if($field=$info_995[$nb_expl]['7']) {		
			$requete="insert into expl_custom_values (expl_custom_champ,expl_custom_origine,expl_custom_small_text) values($id_expl_fournisseur_opsys,$expl_id,'".addslashes($field)."')";
			mysql_query($requete);
		} 
		//Date de création
		if($field=$info_995[$nb_expl]['8']) {		
			$requete="update exemplaires set create_date='$field 12:00:00' where expl_id='$expl_id' ";
			//print $requete;
			mysql_query($requete);
		}   

	} // fin for
} // fin traite_exemplaires	TRAITEMENT DES EXEMPLAIRES JUSQU'ICI

// fonction spécifique d'export de la zone 995
function export_traite_exemplaires ($ex=array()) {
	global $msg, $dbh ;
	
	$subfields["a"] = $ex -> lender_libelle;
	$subfields["c"] = $ex -> lender_libelle;
	$subfields["f"] = $ex -> expl_cb;
	$subfields["k"] = $ex -> expl_cote;
	$subfields["u"] = $ex -> expl_note;

	if ($ex->statusdoc_codage_import) $subfields["o"] = $ex -> statusdoc_codage_import;
	if ($ex -> tdoc_codage_import) $subfields["r"] = $ex -> tdoc_codage_import;
		else $subfields["r"] = "uu";
	if ($ex -> sdoc_codage_import) $subfields["q"] = $ex -> sdoc_codage_import;
		else $subfields["q"] = "u";

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