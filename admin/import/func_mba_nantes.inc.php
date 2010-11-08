<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_mba_nantes.inc.php,v 1.9 2010-03-30 15:22:30 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/thesaurus.class.php");
require_once("$class_path/noeuds.class.php");
require_once("$class_path/categories.class.php");
require_once($class_path."/serials.class.php");
require_once($include_path."/isbn.inc.php");

function recup_noticeunimarc_suite($notice) {
	global $info_999,$info_001,$doc_type,$info_901,$info_905,$info_906,$info_994_x,$info_994_y;
	global $info_503,$info_503_j,$info_503_d,$info_305;
	global $info_600_f, $info_600_b, $info_600_3;
	global $info_601_3;
	global $info_606_3;
	global $info_607_3;
	global $info_608_a, $info_608_j, $info_608_x, $info_608_y, $info_608_z, $info_608_3, $serie_200,$tit_200a;
	global $aut_700,$aut_701,$aut_702;
	$info_901=array();
	$info_905=array();
	$info_906=array();
	$info_999=array();
	$info_305=array();
	$info_503=array();
	$info_503_j=array();
	$info_503_d=array();
	$info_001=array();
	$info_200=array();
	$info_994_x=array();
	$info_994_y=array();
	$record = new iso2709_record($notice, AUTO_UPDATE);
	
	$info_001=$record->get_subfield("001");
	$info_200=$record->get_subfield("200","b");
	if(eregi("article",$info_200[0])){
		$doc_type="x";
	}
	$info_901=$record->get_subfield("901","a");
	$info_901=$record->get_subfield("905","a");
	$info_901=$record->get_subfield("906","a");
	$info_999=$record->get_subfield("999","a");
	$info_503=$record->get_subfield("503","a","e","f","h","m","n");
	$info_503_j=$record->get_subfield_array_array("503","j");
	$info_503_d=$record->get_subfield_array_array("503","d");
	
	$info_305=array();
	$info_305=$record->get_subfield("305","a");
	
	$info_600_3=array();
	$info_600_3=$record->get_subfield_array_array("600","3");
	$info_600_b=array();
	$info_600_b=$record->get_subfield_array_array("600","b");
	$info_600_f=array();
	$info_600_f=$record->get_subfield_array_array("600","f");
	
	$info_601_3=array();
	$info_601_3=$record->get_subfield_array_array("601","3");
	
	$info_606_3=array();
	$info_606_3=$record->get_subfield_array_array("606","3");
	
	$info_607_3=array();
	$info_607_3=$record->get_subfield_array_array("607","3");
	
	$info_608_3=array();
	$info_608_3=$record->get_subfield_array_array("608","3");
	$info_608_a=array();
	$info_608_a=$record->get_subfield_array_array("608","a");
	$info_608_j=array();
	$info_608_j=$record->get_subfield_array_array("608","j");
	$info_608_x=array();
	$info_608_x=$record->get_subfield_array_array("608","x");
	$info_608_y=array();
	$info_608_y=$record->get_subfield_array_array("608","y");
	$info_608_z=array();
	$info_608_z=$record->get_subfield_array_array("608","z");
	
	$aut_700=array();
	$aut_700=$record->get_subfield("700","a","b","c","d","4","N");
	$aut_701=array();
	$aut_701=$record->get_subfield("701","a","b","c","d","4","N");
	$aut_702=array();
	$aut_702=$record->get_subfield("702","a","b","c","d","4","N");
					
	$info_994_x=$record->get_subfield("994","x");
	$info_994_y=$record->get_subfield("994","y");
	
	if(count($serie_200)){
		$titre=implode (" ; ",$tit_200a);
		if(trim($serie_200[0]['i'])){
			$titre.=". ".trim($serie_200[0]['i']);
		}
		if(trim($serie_200[0]['h'])){
			$titre.=", ".trim($serie_200[0]['h']);
		}
		$tit_200a=array();
		$serie_200=array();
		$tit_200a[]=$titre;
		
	}
	
} // fin recup_noticeunimarc_suite = fin récupération des variables propres crespicardie : rien de plus
	
function import_new_notice_suite() {
	global $dbh,$q ;
	global $notice_id,$bulletin_ex;
	global $thesaurus_defaut;
	global $info_503,$info_503_j,$info_503_d,$info_305;
	global $info_999,$info_001,$info_994_x,$info_994_y,$info_901,$editor,$info_905,$info_906,$editor;
	$bulletin_ex=0;
	global $info_600_a, $info_600_j, $info_600_x, $info_600_y, $info_600_z, $info_600_f, $info_600_b, $info_600_3;
	global $info_601_a, $info_601_j, $info_601_x, $info_601_y, $info_601_z, $info_601_3;
	global $info_606_a, $info_606_j, $info_606_x, $info_606_y, $info_606_z, $info_606_3;
	global $info_607_a, $info_607_j, $info_607_x, $info_607_y, $info_607_z, $info_607_3;
	global $info_608_a, $info_608_j, $info_608_x, $info_608_y, $info_608_z, $info_608_3;
	//Indexation
	global $pmb_keyword_sep ;
	if (!$pmb_keyword_sep) $pmb_keyword_sep=" ; ";
	$champ_rameau="";
	
	for ($a=0; $a<sizeof($info_600_a); $a++) {
		$libelle_final="";
		$libelle_j="";
		for ($j=0; $j<sizeof($info_600_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= trim($info_600_j[$a][$j]) ;
				else $libelle_j .= " ** ".trim($info_600_j[$a][$j]) ;
		}
		if($info_600_b[$a][0]) $libelle_final.=" ".trim($info_600_b[$a][0]);
		if($info_600_f[$a][0]) $libelle_final.=" (".trim($info_600_f[$a][0]).")";
		
		if (!$libelle_j) $libelle_final = trim($info_600_a[$a][0]).$libelle_final ; else $libelle_final = trim($info_600_a[$a][0]).$libelle_final." ** ".$libelle_j ;
		if (!$libelle_final) break ;
		for ($j=0; $j<sizeof($info_600_x[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_600_x[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_600_y[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_600_y[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_600_z[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_600_z[$a][$j]) ;
		}
		//if($info_600_3[$a][0])$libelle_final.=" @@3 ".trim($info_600_3[$a][0]);		
		if ($champ_rameau) $champ_rameau.=$pmb_keyword_sep; 
		$champ_rameau.=$libelle_final;
	} 	
	
	for ($a=0; $a<sizeof($info_601_a); $a++) {
		$libelle_final="";
		$libelle_j="";
		for ($j=0; $j<sizeof($info_601_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= trim($info_601_j[$a][$j]) ;
				else $libelle_j .= " ** ".trim($info_601_j[$a][$j]) ;
		}
		if (!$libelle_j) $libelle_final = trim($info_601_a[$a][0]) ; else $libelle_final = trim($info_601_a[$a][0])." ** ".$libelle_j ;
		if (!$libelle_final) break ;
		for ($j=0; $j<sizeof($info_601_x[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_601_x[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_601_y[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_601_y[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_601_z[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_601_z[$a][$j]) ;
		}
		//if($info_601_3[$a][0])$libelle_final.=" @@3 ".trim($info_601_3[$a][0]);
		if ($champ_rameau) $champ_rameau.=$pmb_keyword_sep;
		$champ_rameau.=$libelle_final;
	} 		
	
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		$libelle_final="";
		$libelle_j="";
		for ($j=0; $j<sizeof($info_606_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= trim($info_606_j[$a][$j]) ;
				else $libelle_j .= " ** ".trim($info_606_j[$a][$j]) ;
		}
		if (!$libelle_j) $libelle_final = trim($info_606_a[$a][0]) ; else $libelle_final = trim($info_606_a[$a][0])." ** ".$libelle_j ;
		if (!$libelle_final) break ;
		for ($j=0; $j<sizeof($info_606_x[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_606_x[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_606_y[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_606_y[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_606_z[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_606_z[$a][$j]) ;
		}
		//if($info_606_3[$a][0])$libelle_final.=" @@3 ".trim($info_606_3[$a][0]);
		if ($champ_rameau) $champ_rameau.=$pmb_keyword_sep;
		$champ_rameau.=$libelle_final;
	} 
	
	for ($a=0; $a<sizeof($info_607_a); $a++) {
		$libelle_final="";
		$libelle_j="";
		for ($j=0; $j<sizeof($info_607_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= trim($info_607_j[$a][$j]) ;
				else $libelle_j .= " ** ".trim($info_607_j[$a][$j]) ;
		}
		if (!$libelle_j) $libelle_final = trim($info_607_a[$a][0]) ; else $libelle_final = trim($info_607_a[$a][0])." ** ".$libelle_j ;
		if (!$libelle_final) break ;
		for ($j=0; $j<sizeof($info_607_x[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_607_x[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_607_y[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_607_y[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_607_z[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_607_z[$a][$j]) ;
		}
		//if($info_607_3[$a][0])$libelle_final.=" @@3 ".trim($info_607_3[$a][0]);
		if ($champ_rameau) $champ_rameau.=$pmb_keyword_sep;
		$champ_rameau.=$libelle_final;
	} 
	
	for ($a=0; $a<sizeof($info_608_a); $a++) {
		$libelle_final="";
		$libelle_j="";
		for ($j=0; $j<sizeof($info_608_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= trim($info_608_j[$a][$j]) ;
				else $libelle_j .= " ** ".trim($info_608_j[$a][$j]) ;
		}
		if (!$libelle_j) $libelle_final = trim($info_608_a[$a][0]) ; else $libelle_final = trim($info_608_a[$a][0])." ** ".$libelle_j ;
		if (!$libelle_final) break ;
		for ($j=0; $j<sizeof($info_608_x[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_608_x[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_608_y[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_608_y[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_608_z[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_608_z[$a][$j]) ;
		}
		//if($info_608_3[$a][0])$libelle_final.=" @@3 ".trim($info_608_3[$a][0]);
		if ($champ_rameau) $champ_rameau.=$pmb_keyword_sep;
		$champ_rameau.=$libelle_final;
	}
	
	//A mettre en note
	if(count($info_305)){
		$n_gen= implode("\n",$info_305);
		$requete="update notices set n_gen=IF(TRIM(n_gen) != '', CONCAT(n_gen,'\n','".addslashes($n_gen)."'),'".addslashes($n_gen)."') WHERE notice_id='".$notice_id."' ";
		if(!mysql_query($requete)){
			echo "Requete echoué : ".$requete."<br>";
		}
	}
		
	if($champ_rameau){
		//echo "valeur : ".$champ_rameau."<br>";
		$requete="SELECT index_l FROM notices WHERE notice_id='".$notice_id."' ";
		$res=mysql_query($requete);
		if(mysql_num_rows($res)){
			$ind=mysql_result($res,0,0);
			if($ind){
				$requete="UPDATE notices SET index_l='".addslashes(trim($ind.$pmb_keyword_sep.$champ_rameau))."' WHERE notice_id='".$notice_id."'";
				if(!mysql_query($requete)){
					echo "Requete echoué : ".$requete."<br>";
				}
			}else{
				$requete="UPDATE notices SET index_l='".addslashes(trim($champ_rameau))."' WHERE notice_id='".$notice_id."'";
				if(!mysql_query($requete)){
					echo "Requete echoué : ".$requete."<br>";
				}
			}
		}else{
			$requete="UPDATE notices SET index_l='".addslashes(trim($champ_rameau))."' WHERE notice_id='".$notice_id."'";
			if(!mysql_query($requete)){
				echo "Requete echoué : ".$requete."<br>";
			}
		}
	}
	//echo "Valeur <br>";
	//echo $champ_rameau."<br>";
	/*$ligne=explode("@@@",$champ_rameau);
	for($i=0;$i<count($ligne);$i++){
		$data=explode("@@3",$ligne[$i]);
		if(trim($data[0])){
			$descripteur=trim($data[0]);
			$thes = new thesaurus($thesaurus_defaut);
			$id_categorie = categories::searchLibelle(addslashes($descripteur),$thesaurus_defaut,'fr_FR',$thes->num_noeud_racine);
			if(!$id_categorie){
				$n=new noeuds();
				$n->num_parent=$thes->num_noeud_racine;
				$n->num_thesaurus=$thesaurus_defaut;
				$n->autorite = trim($data[1]);
				$n->save();
				$id_n=$n->id_noeud;
				$c=new categories($id_n, 'fr_FR');
				$c->libelle_categorie=$descripteur;
				$c->index_categorie=" ".strip_empty_words($descripteur)." ";
				$c->save();
				$id_categorie = categories::searchLibelle(addslashes($descripteur),$thesaurus_defaut,'fr_FR',$thes->num_noeud_racine);
			}
			$requete = "INSERT INTO notices_categories (notcateg_notice,num_noeud,ordre_categorie) VALUES($notice_id,$id_categorie,$i)"; // Je fait le lien entre ma notice et la catégorie
			mysql_query($requete);
		}
	}*/
	
	//Champs perso 
	if(count($editor) > 2){
		$rqt = "SELECT idchamp FROM notices_custom WHERE name='autre_editeur'";
		$res = mysql_query($rqt);
		if (mysql_num_rows($res)>0){
			$id_champ=mysql_result($res,0);
			for($i=2;$i<count($editor);$i++){
				$ed['name']=clean_string($editor[$i]['c']);
				$ed['ville']=clean_string($editor[$i]['a']);
				$ed1_id = editeur::import($ed);
				$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values('".$id_champ."','".$notice_id."','".$ed1_id."')";
				if(!mysql_query($requete)){
					echo "requete echoué : ".$requete."<br>";
				} 
			}
		}
	}
	
	//Ancien num
	if($info_001[0]){
		$rqt = "SELECT idchamp FROM notices_custom WHERE name='ancien_num'";
		$res = mysql_query($rqt);
		if (mysql_num_rows($res)>0){
			$id_champ=mysql_result($res,0);
			$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes($info_001[0])."')";
			if(!mysql_query($requete)){
				echo "requete echoué : ".$requete."<br>";
			} 
		}
	}
	
	//Titre de forme
	$tab_non_rep=array();
	
	if(count($info_503)){
		for($i=0;$i<count($info_503);$i++){
			
			if($info_503[$i]["a"] && !($tab_non_rep["titre"])){
				$rqt = "SELECT idchamp FROM notices_custom WHERE name='t_d_f_titre'";
				$res = mysql_query($rqt);
				if (mysql_num_rows($res)>0){
					$id_champ=mysql_result($res,0);
					$requete="select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='".addslashes(trim($info_503[$i]["a"]))."' and notices_custom_champ='".$id_champ."'";
					$resultat=mysql_query($requete);
					if (mysql_num_rows($resultat)) {
						$value=mysql_result($resultat,0,0);
					} else {
						$requete="select max(notices_custom_list_value*1) from notices_custom_lists where notices_custom_champ='".$id_champ."'";
						$resultat=mysql_query($requete);
						$max=@mysql_result($resultat,0,0);
						$n=$max+1;
						$requete="insert into notices_custom_lists (notices_custom_champ,notices_custom_list_value,notices_custom_list_lib) values('".$id_champ."',$n,'".addslashes(trim($info_503[$i]["a"]))."')";
						mysql_query($requete);
						$value=$n;
					}
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values('".$id_champ."',$notice_id,$value)";
					$tab_non_rep["titre"]=1;
					if(!mysql_query($requete)){
						echo "requete echoué : ".$requete."<br>";
					} 
				}
			}
			
			if($info_503[$i]["e"] && !($tab_non_rep["nom"])){
				$rqt = "SELECT idchamp FROM notices_custom WHERE name='t_d_f_nom'";
				$res = mysql_query($rqt);
				if (mysql_num_rows($res)>0){
					$id_champ=mysql_result($res,0);
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes(trim($info_503[$i]["e"]))."')";
					$tab_non_rep["nom"]=1;
					if(!mysql_query($requete)){
						echo "requete echoué : ".$requete."<br>";
					} 
				}
			}
			
			if($info_503[$i]["f"] && !($tab_non_rep["prenom"])){
				$rqt = "SELECT idchamp FROM notices_custom WHERE name='t_d_f_prenom'";
				$res = mysql_query($rqt);
				if (mysql_num_rows($res)>0){
					$id_champ=mysql_result($res,0);
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes(trim($info_503[$i]["f"]))."')";
					$tab_non_rep["prenom"]=1;
					if(!mysql_query($requete)){
						echo "requete echoué : ".$requete."<br>";
					} 
				}
			}
			
			if($info_503[$i]["h"] && !($tab_non_rep["qual"])){
				$rqt = "SELECT idchamp FROM notices_custom WHERE name='t_d_f_qualificatif'";
				$res = mysql_query($rqt);
				if (mysql_num_rows($res)>0){
					$id_champ=mysql_result($res,0);
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes(trim($info_503[$i]["h"]))."')";
					$tab_non_rep["qual"]=1;
					if(!mysql_query($requete)){
						echo "requete echoué : ".$requete."<br>";
					} 
				}
			}

			if(count($info_503_j[$i]) && !($tab_non_rep["date"])){
				$rqt = "SELECT idchamp FROM notices_custom WHERE name='t_d_f_date'";
				$res = mysql_query($rqt);
				if (mysql_num_rows($res)>0){
					$date="";
					for($p=0;$p<count($info_503_j[$i]);$p++){
						if(trim($info_503_j[$i][$p])){
							$jm=trim($info_503_d[$i][$p]);
							if($jm){
								//Si j'ai aussi le mois et le jour
								if(strlen($jm) == 4){
									if($date){
										$date.=" - ".substr($jm,2,2)."/".substr($jm,0,2)."/".trim($info_503_j[$i][$p]);
									}else{
										$date=substr($jm,2,2)."/".substr($jm,0,2)."/".trim($info_503_j[$i][$p]);
									}
									
								}else{
									if($date){
										$date.=" - ".$jm." ".trim($info_503_j[$i][$p]);
									}else{
										$date=$jm." ".trim($info_503_j[$i][$p]);
									}	
								}
							}else{
								if($date){
									$date.=" - ".trim($info_503_j[$i][$p]);	
								}else{
									$date=trim($info_503_j[$i][$p]);
								}
							}
						}
					}
					
					if($date){
						$id_champ=mysql_result($res,0);
						$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes($date)."')";
						$tab_non_rep["date"]=1;
						if(!mysql_query($requete)){
							echo "requete echoué : ".$requete."<br>";
						} 
					}
				}
			}
			
			$lieu_etabl="";
			if(trim($info_503[$i]["m"]) and trim($info_503[$i]["n"])){
				$lieu_etabl=trim($info_503[$i]["m"]).", ".trim($info_503[$i]["n"]);
			}elseif(trim($info_503[$i]["m"])){
				$lieu_etabl=trim($info_503[$i]["m"]);
			}elseif(trim($info_503[$i]["m"]) and trim($info_503[$i]["n"])){
				$lieu_etabl=trim($info_503[$i]["n"]);
			}
			if($lieu_etabl){
				$rqt = "SELECT idchamp FROM notices_custom WHERE name='t_d_f_lieu_etabl'";
				$res = mysql_query($rqt);
				if (mysql_num_rows($res)>0){
					$id_champ=mysql_result($res,0);
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes($lieu_etabl)."')";
					if(!mysql_query($requete)){
						echo "requete echoué : ".$requete."<br>";
					} 
				}
			}
		}
	}
	
	//Autre isbn
	if(count($info_901)){
		$rqt = "SELECT idchamp FROM notices_custom WHERE name='autre_isbn'";
		$res = mysql_query($rqt);
		if (mysql_num_rows($res)>0){
			$id_champ=mysql_result($res,0);
			for($i=0;$i<count($info_901);$i++){
				$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes($info_901[$i])."')";
				if(!mysql_query($requete)){
					echo "requete echoué : ".$requete."<br>";
				}
			} 
		}
	}
	
	//Anomalie
	if($info_999[0]){
		$rqt = "SELECT idchamp FROM notices_custom WHERE name='pb_fiche'";
		$res = mysql_query($rqt);
		if (mysql_num_rows($res)>0){
			$id_champ=mysql_result($res,0);
			$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes($info_999[0])."')";
			if(!mysql_query($requete)){
				echo "requete echoué : ".$requete."<br>";
			} 
		}
	}
	
	//Donnée codées monographie
	if($info_905[0]){
		$rqt = "SELECT idchamp FROM notices_custom WHERE name='dc_mono'";
		$res = mysql_query($rqt);
		if (mysql_num_rows($res)>0){
			$id_champ=mysql_result($res,0);
			$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes($info_905[0])."')";
			if(!mysql_query($requete)){
				echo "requete echoué : ".$requete."<br>";
			} 
		}
	}
	
	//Donnée codées description physique
	if($info_906[0]){
		$rqt = "SELECT idchamp FROM notices_custom WHERE name='dc_phys'";
		$res = mysql_query($rqt);
		if (mysql_num_rows($res)>0){
			$id_champ=mysql_result($res,0);
			$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values('".$id_champ."','".$notice_id."','".addslashes($info_906[0])."')";
			if(!mysql_query($requete)){
				echo "requete echoué : ".$requete."<br>";
			} 
		}
	}
	//oeuvre du musée reproduite
	if(count($info_994_x)){
		$val=implode(" ; ",$info_994_x);
		$rqt = "SELECT idchamp FROM notices_custom WHERE name='oeuvre_reproduite'";
		$res = mysql_query($rqt);
		if (mysql_num_rows($res)>0){
			$id_champ=mysql_result($res,0);
			$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_text) values('".$id_champ."','".$notice_id."','".addslashes(trim($val))."')";
			if(!mysql_query($requete)){
				echo "requete echoué : ".$requete."<br>";
			} 
		}
	}
		
	//oeuvre du musée citéé
	if(count($info_994_y)){
		$val=implode(" ; ",$info_994_y);
		$rqt = "SELECT idchamp FROM notices_custom WHERE name='oeuvre_citee'";
		$res = mysql_query($rqt);
		if (mysql_num_rows($res)>0){
			$id_champ=mysql_result($res,0);
			$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_text) values('".$id_champ."','".$notice_id."','".addslashes(trim($val))."')";
			if(!mysql_query($requete)){
				echo "requete echoué : ".$requete."<br>";
			} 
		}
	}
	
} // fin import_new_notice_suite
			
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
		global $msg, $dbh ;
	global $nb_expl_ignores ;
	global $bulletin_ex;
	global $prix, $notice_id, $info_995, $typdoc_995, $tdoc_codage, $book_lender_id, 
		$section_995, $sdoc_codage, $book_statut_id, $locdoc_codage, $codstatdoc_995, $statisdoc_codage,
		$cote_mandatory, $book_location_id ;
		
	// lu en 010$d de la notice
	//$price = $prix[0];
	
	// la zone 995 est répétable
	for ($nb_expl = 0; $nb_expl < sizeof ($info_995); $nb_expl++) {
		/* RAZ expl */
		$expl = array();
		
		if($bulletin_ex == -1){
			return;
		}elseif ($bulletin_ex) {
			$expl['bulletin']=$bulletin_ex;
			$expl['notice']=0;
		} else {
			$expl['notice']     = $notice_id ;
			$expl['bulletin']=0;
		}
		
		/* préparation du tableau à passer à la méthode */
		$cbarre = $info_995[$nb_expl]['f'];
		if(!$cbarre)$cbarre="ind";
		$pb = 1 ;
		$num_login=1 ;
		$expl['cb']=$cbarre;
		while ($pb==1) {
			$q = "SELECT expl_cb FROM exemplaires WHERE expl_cb='".$expl['cb']."' LIMIT 1 ";
			$r = mysql_query($q, $dbh);
			//echo "requete : ".$q."<br>";
			$nb = mysql_num_rows($r);
			if ($nb) {
				$expl['cb'] =$cbarre."-".$num_login ;
				$num_login++;
			} else $pb = 0 ;
		}
		
		// $expl['typdoc']     = $info_995[$nb_expl]['r']; à chercher dans docs_typdoc
		$data_doc=array();
		//$data_doc['tdoc_libelle'] = $info_995[$nb_expl]['r']." -Type doc importé (".$book_lender_id.")";
		$data_doc['tdoc_libelle'] = $info_995[$nb_expl]['r'];
		$data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['r'] ;
		if(!$data_doc['tdoc_libelle']){
			$data_doc['tdoc_libelle'] ="Indéterminé";
			$data_doc['tdoc_codage_import'] ="Indéterminé";
		}
		$data_doc['duree_pret'] = 0 ; /* valeur par défaut */
		$data_doc['tdoc_owner'] = 0 ;
		$expl['typdoc'] = docs_type::import($data_doc);
		
		$expl['cote'] = $info_995[$nb_expl]['k'];
		if($expl['cote'] == "")$expl['cote'] = "Indetermine";
                      	
		// $expl['section']    = $info_995[$nb_expl]['q']; à chercher dans docs_section
		/*$data_doc=array();
		$info_995[$nb_expl]['q']=trim($info_995[$nb_expl]['q']);
		if (!$info_995[$nb_expl]['q']) 
			$info_995[$nb_expl]['q'] = "u";
		*/
		$data_doc=array();
		$data_doc['section_libelle'] = $info_995[$nb_expl]['q'];
		$data_doc['sdoc_codage_import'] = $info_995[$nb_expl]['q'] ;
		if(!$data_doc['section_libelle']){
			$data_doc['section_libelle'] = "Indéterminé";
			$data_doc['sdoc_codage_import'] = "Indéterminé";
		}
		$data_doc['sdoc_owner'] = 0 ;
		$expl['section'] = docs_section::import($data_doc);
		
		
		if(!($info_995[$nb_expl]['o'])){
			$expl['statut'] = $book_statut_id;
		}else{
			$info_995[$nb_expl]['o']="Indéterminé";
			$data_doc=array();
			$data_doc['statusdoc_codage_import']=$info_995[$nb_expl]['o'];
			$data_doc['statut_libelle']=$info_995[$nb_expl]['o'];
			$data_doc['statusdoc_owner'] = 0 ;
			$expl['statut'] = docs_statut::import($data_doc);
		}
		
		//$expl['statut'] = $book_statut_id;
		
		if($info_995[$nb_expl]['a'] != ""){
			$data_doc=array();
			$data_doc['locdoc_codage_import']=$info_995[$nb_expl]['a'];
			$data_doc['location_libelle']=$info_995[$nb_expl]['a'];
			$data_doc['locdoc_owner'] = 0 ;
			$expl['location'] = docs_location::import($data_doc);
		}else{
			$expl['location'] = $book_location_id;
		}
		
		
		// $expl['codestat']   = $info_995[$nb_expl]['q']; 'q' utilisé, éventuellement à fixer par combo_box
		$data_doc=array();
		$data_doc['codestat_libelle'] = $info_995[$nb_expl]['q'];
		$data_doc['statisdoc_codage_import'] = $info_995[$nb_expl]['q'];
		if(!$data_doc['codestat_libelle']){
			$data_doc['codestat_libelle'] = "Indéterminé";
			$data_doc['statisdoc_codage_import'] = "Indéterminé";
		}
		$data_doc['statisdoc_owner'] = 0 ;
		$expl['codestat'] = docs_codestat::import($data_doc);
		
		
		// $expl['creation']   = $info_995[$nb_expl]['']; à préciser
		// $expl['modif']      = $info_995[$nb_expl]['']; à préciser
                      	
		$expl['comment']       =  $info_995[$nb_expl]['u'];
		$expl['note']       = "" ;
		$expl['prix']= $prix[0];
		$expl['expl_owner'] = $book_lender_id ;
		$expl['cote_mandatory'] = $cote_mandatory ;
		
		$data['date_depot']=""; // Attention il faut en post migration mette cette info dans la date de création de l'exemplaire
		// quoi_faire 
		$expl['quoi_faire'] = 2 ;
		
		$expl_id = exemplaire::import($expl);
		if ($expl_id == 0) {
			$nb_expl_ignores++;
		}else{
			
		}  
			        
		} // fin for
	} // fin traite_exemplaires	TRAITEMENT DES EXEMPLAIRES JUSQU'ICI

// fonction spécifique d'export de la zone 995
function export_traite_exemplaires ($ex=array()) {

	}	