<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : Marco VANINETTI                                                           |
// +-------------------------------------------------+
// $Id: z_progression_cache.php,v 1.29 2010-04-16 08:54:22 gueluneau Exp $

// définition du minimum nécéssaire 
$base_path="../..";
$base_auth = "CATALOGAGE_AUTH";  
$base_title = "";    
$base_nobody = 1;    
require_once ("$base_path/includes/init.inc.php");  

// les requis par z_progression_main.php ou ses sous modules
require_once ("$include_path/isbn.inc.php");
require_once ("$include_path/marc_tables/$lang/empty_words");
require_once ("$class_path/iso2709.class.php");
require_once ("z3950_func.inc.php");
require ('notice.inc.php');
// new for decoding record sutrs
require_once ("z3950_sutrs.inc.php");
include("$class_path/z3950_notice.class.php");


function critere_isbn ($val1) {
	$val=$val1;
	if(isEAN($val1)) {
		// la saisie est un EAN -> on tente de le formater en ISBN
		$val1 = z_EANtoISBN($val1);
		// si échec, on prend l'EAN comme il vient
		if(!$val1) $val1 = $val;
		} else {
			if(isISBN($val1)) {
				// si la saisie est un ISBN
				$val1 = z_formatISBN($val1,13);
				// si échec, ISBN erroné on le prend sous cette forme
				if(!$val1) $val1 = $val;
				} else {
					// ce n'est rien de tout ça, on prend la saisie telle quelle
					$val1 = $val;
					}
			}
	return $val1;
	}
	
	
$mioframe="frame1";
//affiche_jsscript ("configuring the connections...", "#FFB7B7", $mioframe);

////////////////////////////////////////////////////////////////////
// Fase 1: we prepare the query and the connection fore each biblio 
///////////////////////////////////////////////////////////////////

//si può mettere prima del ciclo while principale....?
// Remise à "" de tous les attributs de critère de recherche
$map=array();
		
$rqt_bib_attr=mysql_query("select attr_libelle from z_attr group by attr_libelle ");
while ($linea=mysql_fetch_array($rqt_bib_attr)) {
	$attr_libelle=$linea["attr_libelle"];
	$var = "attr_".strtolower($attr_libelle) ;
	$$var = "" ;
}

$rq_bib_z3950=mysql_query ("select * from z_bib $selection_bib order by bib_nom, bib_id ");
while ($ligne=mysql_fetch_array($rq_bib_z3950)) {
    	$bib_id=$ligne["bib_id"];
		$url=$ligne["url"];
		$port=$ligne["port"];
		$base=$ligne["base"];
		$format=$ligne["format"];
		$auth_user=$ligne["auth_user"];
		$auth_pass=$ligne["auth_pass"];
		$sutrs_lang=$ligne["sutrs_lang"];
		$auth=$auth_user.$auth_pass;
		$formato[$bib_id]=$format;

	// chargement des attributs de la bib sélectionnée
	$rqt_bib_attr=mysql_query("select * from z_attr where attr_bib_id='$bib_id'");
	while ($linea=mysql_fetch_array($rqt_bib_attr)) {
		$attr_libelle=$linea["attr_libelle"];
		$attr_attr=$linea["attr_attr"];
		$var = "attr_".strtolower($attr_libelle) ;
		$$var = $attr_attr ;
	}

	// On détermine la requête à envoyer
	$booleen="";
	$critere1="";
	$critere2="";
	$troncature="";
	if ($bool1 == "ET") $booleen="@and ";
	elseif ($bool1 == "OU") $booleen="@or ";
	elseif ($bool1 == "SAUF") $booleen="@not ";
	
	switch ($crit1) {
		case "titre" :
			$critere1=$attr_titre;
			break;
		case "mots" :
			$critere1=$attr_mots;
			break;
		case "resume" :
			$critere1=$attr_resume;
			break;
		case "type_doc" :
			$critere1=$attr_type_doc;
			break;
		case "auteur" :
			$critere1=$attr_auteur;
			break;
		case "sujet" :
			$critere1=$attr_sujet;
			break;
		case "isbn" :
			$critere1=$attr_isbn;
			//$val1=critere_isbn($val1);
			break;
		case "issn" :
			$critere1=$attr_issn;
			break;
		case "isrn" :
			$critere1=$attr_isrn;
			break;
		case "ismn" :
			$critere1=$attr_ismn;
			break;
		case "mk" :
			$critere1=$attr_mk;
			break;
		case "cbsonores" :
			$critere1=$attr_cbsonores;
			break;
		case "ean" :
			$critere1=$attr_ean;
			break;
		case "allfields" :
			$critere1=$attr_allfields;
			break;
		default :
			break;
		}
		
	
	switch ($crit2) {
		case "titre" :
			$critere2=$attr_titre;
			break;
		case "mots" :
			$critere2=$attr_mots;
			break;
		case "resume" :
			$critere2=$attr_resume;
			break;
		case "type_doc" :
			$critere2=$attr_type_doc;
			break;
		case "auteur" :
			$critere2=$attr_auteur;
			break;
		case "sujet" :
			$critere2=$attr_sujet;
			break;
		case "isbn" :
			$critere2=$attr_isbn;
			//$val2=critere_isbn($val2);
			break;
		case "issn" :
			$critere2=$attr_issn;
			break;
		case "isrn" :
			$critere2=$attr_isrn;
			break;
		case "ismn" :
			$critere2=$attr_ismn;
			break;
		case "mk" :
			$critere2=$attr_mk;
			break;
		case "cbsonores" :
			$critere2=$attr_cbsonores;
			break;
		case "ean" :
			$critere2=$attr_ean;
			break;
		case "allfields" :
			$critere2=$attr_allfields;
			break;
		default :
			break;
		}

	$term="";
	
	
	if ($val1 != "" AND $val2 == "" AND $critere1 != "" ) {
		$term="@attr 1=$critere1 @attr 4=1 \"$val1$troncature\" ";
		} 
	if ($val1 == "" AND $val2 != "" AND $critere2 != "" ) {
		$term="@attr 1=$critere2 @attr 4=1 \"$val2$troncature\" ";
		} 
	if ($val1 != "" AND $val2 != "" AND $critere1 != "" AND $critere2 != "" ) {
		$term="$booleen @attr 1=$critere1 @attr 4=1 \"$val1$troncature\"  @attr 1=$critere2 @attr 4=1 \"$val2$troncature\" ";
		}

	if ($term == "") {
		//$stato[$bib_id]=0;
		//$map[$bib_id] = 0;
		if ($val1 == "" AND $val2 == "") {
			affiche_jsscript ($msg[z3950_echec_no_champ], "#FF3333", $bib_id);
		} else {
			affiche_jsscript ($msg[z3950_echec_no_valid_attr], "#FF3333", $bib_id);
		}
	} else {
		
		//////////////////////////////////////////////////////////////////////////////////
		// the query is ok we prepare the Z 3950 process for this biblio and
		// save the $id to be able later to retrieve the records from the servers
		//////////////////////////////////////////////////////////////////////////////////
	
		//$stato[$bib_id] = 1;
		$auth = $auth_user.$auth_pass ;
		if ($auth != "") {
			$id = yaz_connect("$url:$port/$base", array("user" => $auth_user, "password" => $auth_pass, "piggyback"=>false)) or affiche_jsscript ("Echec : impossible de se connecter au Serveur", "#FF3333", $bib_id);
		} else {
			$id = yaz_connect("$url:$port/$base", array("piggyback"=>false)) or affiche_jsscript ($msg[z3950_echec_cnx], "#FF3333", $bib_id);
		}
		$map[$bib_id] = $id;
		yaz_element($id,"F");
		yaz_range ($id, 1, $limite);
		yaz_syntax($id,strtolower($format));
		echo $term;
		yaz_search($id,"rpn",$term);
	}
}
///////////////////////////////////////////////////////////////////////////
// Fase 2: all the possible connections are ready now start the researches 
//////////////////////////////////////////////////////////////////////////
affiche_jsscript ($msg['z3950_zmsg_wait'], "#FFCC99", $mioframe);

$options=array("timeout"=>45);
$t1=time();

//Override le timeout du serveur mysql, pour être sûr que le socket dure assez longtemps pour aller jusqu'aux ajouts des résultats dans la base. 
$sql = "set wait_timeout = 120";
mysql_query($sql);

yaz_wait($options);
$dt=time()-$t1;
$msgz=str_replace('!!time!!',$dt,$msg['z3950_zmsg_endw']);
hideJoke();
affiche_jsscript ($msgz, "#D8FFBE", $mioframe);
showButRes();

////////////////////////////////////////////////////////////////////
// Fase 3: Now get the results from the biblios 
// obviously if the query was ok and there weren't errors
///////////////////////////////////////////////////////////////////
while (list($bib_id,$id)=each($map)){
		$error = yaz_error($id);
		$error_info = yaz_addinfo($id);
		if (!empty($error)) {
			$msg1 = $msg[z3950_echec_rech]." : ".$error.", ". $error_info;
			affiche_jsscript ($msg1, "#FF3333", $bib_id);
			yaz_close ($id);
		} else {
			$hits = yaz_hits($id);
			if ($hits>$limite) {
				$lim_recherche=$limite;
				$msg1 = str_replace ("!!limite!!", $limite, $msg[z3950_recup_encours]) ;
				$msg1 = str_replace ("!!hits!!", $hits, $msg1) ;
				affiche_jsscript ($msg1, "#99FF99", $bib_id);
			} else {
				$lim_recherche=$hits;
				$msg1= str_replace ("!!hits!!", $hits, $msg[z3950_recup]) ;
				affiche_jsscript ($msg1, "#99FF99", $bib_id);
			}
			$total=0;
			for ($p = 1; $p <= $lim_recherche; $p++) {
				
				$rec = yaz_record($id,$p,"raw");
				
				// DEBUG 
				global $z3950_debug ;
 				if ($z3950_debug) {
 					$fp = fopen ("../../temp/raw".rand().".marc","wb");
	 				fwrite ($fp, $rec);
	 				fclose ($fp);
 					}
				if (strpos($rec,chr(0x1d))!==false)
 					$rec=substr($rec,0,strpos($rec,chr(0x1d))+1);
				$monEnr = new iso2709_record($rec);
				if($monEnr->valid()) {
					$messageframe = " $p ".$msg['z3950_lu_bin'];
					$pb = 0;
				} else {
					$rec = yaz_record($id,$p,"string");
					$monEnr2 = new iso2709_record($rec);
					if ($monEnr2->valid()) {
						$messageframe = "$p ".$msg['z3950_lu_cok'];
						$pb = 0;
					} else {
						// DEBUG 
						//$fp = fopen ("../../temp/raw".rand().".sutrs","wb");
						//fwrite ($fp, $rec);
						//fclose ($fp);
						$rec = sutrs_record($rec,$sutrs_lang);
						$messageframe = " $p ".$msg['z3950_lu_chs'];
						//$pb = 1;
						//$rec="";					
					}
				}
				if ($pb) $messageframe=$msg["z3950_reception_notice"].$messageframe;
					else $messageframe=$msg["z3950_reception_notice"].$messageframe;
					
				affiche_jsscript ($messageframe, "#B9FFD4", $bib_id);	
					
				if ($rec != "") {
					$total++;
					//if ($total % 10 == 0) {
					//   affiche_jsscript ($msg["z3950_reception_notice"]." $total / $lim_recherche", "#99FF99", $bib_id);
					//}
					$notice = new z3950_notice ($formato[$bib_id], $rec);
					$isbd_affichage = $notice->get_isbd_display ();

					$lu_isbn = $isbd_affichage[0];
					$lu_titre = $isbd_affichage[1];
					$lu_auteur = $isbd_affichage[2];
					$lu_isbd = $isbd_affichage[3];

					$sql2="insert into z_notices (znotices_id, znotices_query_id, znotices_bib_id, isbn, titre, auteur, isbd, z_marc) ";
					$sql2.="values(0,'$last_query_id', '$bib_id', '$lu_isbn', '".addslashes($lu_titre)."', '".addslashes($lu_auteur)."', '".addslashes($lu_isbd)."','".addslashes($rec)."') ";
					mysql_query ($sql2);
					$ID_notice = mysql_insert_id();
				} // fin du if qui vérifie que la notice n'est pas vide
			} // fin for
			yaz_close ($id);
			$msg1 = str_replace ("!!total!!", $total, $msg[z3950_recup_fini]) ;
			$msg1 = str_replace ("!!hits!!", $hits, $msg1) ;
			affiche_jsscript ($msg1, "#FFFFCC", $bib_id);
		} // fin if else error
}

$dt=time()-$t1;
$msg1=str_replace('!!time!!',$dt,$msg['z3950_zmsg_show']);
affiche_jsscript ($msg1, "#F3E6DF", $mioframe);
//showButRes();
?>
</body>
</html>