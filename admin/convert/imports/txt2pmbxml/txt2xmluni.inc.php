<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: txt2xmluni.inc.php,v 1.22 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function convert_txt($notice, $s, $islast, $isfirst, $param_path) {
	global $TabSupport,$TabFonction,$TabLangue,$TabMonth,$sf;
	
	$is_notice_objet=false;
	
	//--------------------déclaration tableau d'autorités-----------------------------
		//déclaration du tableau de correspondance du support physique
	if (!count($TabSupport)) {
		$TabSupport["affiche"]="a";
		$TabSupport["audiocassette"]="i"; // ?? i ou j
		$TabSupport["carte"]="e";	// ?? e ou f
		$TabSupport["cédérom"]="m";
		$TabSupport["diapositive"]="g";
		$TabSupport["disque compact audio"]="i";	// ?? i ou j
		$TabSupport["disque vinyle"]="i";	// ?? i ou j
		$TabSupport["disquette"]="l"; // ?? l ou m
		$TabSupport["DVD-ROM"]="m";
		$TabSupport["DVD-vidéo"]="m";
		$TabSupport["fichier numérique"]="l";
		$TabSupport["film"]="g";
		$TabSupport["livre"]="a"; 
		$TabSupport["périodique"]="a"; 
		$TabSupport["photographie"]="k";
		$TabSupport["site Internet"]="l";
		$TabSupport["transparent"]="g";
		$TabSupport["vidéocassette"]="g";
		$TabSupport["texte manuscrit"]="b";
		$TabSupport["partition musicale imprimée"]="c";
		$TabSupport["partition musicale manuscrite"]="d";
		$TabSupport["document cartographique imprimé"]="e";
		$TabSupport["document cartographique manuscrit"]="f";
		$TabSupport["enregistrement sonore non musical"]="i";
		$TabSupport["enregistrement sonore musical"]="j";
		$TabSupport["document graphique à deux dimensions"]="k";
		$TabSupport["document électronique"]="l";
		$TabSupport["document multimédia"]="m";
		$TabSupport["objet à 3 dimensions"]="r";
		
	}
		//déclaration du tableau de correspondance des fonctions des auteurs secondaires
	if (!count($TabFonction)) {
		$TabFonction["adaptateur"]="010";
		$TabFonction["Adaptateur"]="010";
		$TabFonction["adaptatrice"]="010";
		$TabFonction["Adaptatrice"]="010";
		$TabFonction["cartographe"]="180";
		$TabFonction["Cartographe"]="180"; 
		$TabFonction["chef"]="250";
		$TabFonction["Chef"]="250";
		$TabFonction["collaborateur"]="205";
		$TabFonction["Collaborateur"]="205";
		$TabFonction["collaboratrice"]="205";
		$TabFonction["Collaboratrice"]="205";
		$TabFonction["concepteur"]="545";
		$TabFonction["Concepteur"]="545";
		$TabFonction["conceptrice"]="545";
		$TabFonction["Conceptrice"]="545";	
		$TabFonction["conseiller"]="695";
		$TabFonction["Conseiller"]="695";
		$TabFonction["conseillère"]="695";
		$TabFonction["Conseillère"]="695";
		$TabFonction["dessinateur"]="150";
		$TabFonction["Dessinateur"]="150";
		$TabFonction["dessinatrice"]="150";
		$TabFonction["Dessinatrice"]="150";
		$TabFonction["directeur"]="651";
		$TabFonction["Directeur"]="651"; 
		$TabFonction["directrice"]="651";
		$TabFonction["Directrice"]="651"; 
		$TabFonction["illustrateur"]="440";
		$TabFonction["Illustrateur"]="440";
		$TabFonction["illustratrice"]="440";
		$TabFonction["Illustratrice"]="440";
		$TabFonction["interviewer"]="470";
		$TabFonction["Interviewer"]="470";
		$TabFonction["photographe"]="600";
		$TabFonction["Photographe"]="600";
		$TabFonction["préfacier"]="080";
		$TabFonction["Préfacier"]="080";
		$TabFonction["préfaciere"]="080";
		$TabFonction["Préfaciere"]="080";
		$TabFonction["réalisateur"]="370";
		$TabFonction["Réalisateur"]="370";
		$TabFonction["réalisatrice"]="370";
		$TabFonction["Réalisatrice"]="370";
		$TabFonction["scénariste"]="690";
		$TabFonction["Scénariste"]="690";
		$TabFonction["traducteur"]="730";
		$TabFonction["Traducteur"]="730";
		$TabFonction["traductrice"]="730";
		$TabFonction["Traductrice"]="730";
	}		
	
	if (!count($TabLangue)) {
		$TabLangue=array(
			"albanais"=>"alb",
			"allemand"=>"ger",
			"anglais"=>"eng",
			"arabe"=>"ara",
			"breton"=>"bre",
			"chinois"=>"chi",
			"danois"=>"dan",
			"espagnol"=>"spa",
			"français"=>"fre",
			"français ancien"=>"fro",
			"grec classique"=>"grc",
			"grec moderne"=>"gre",
			"hébreu"=>"heb",
			"hongrois"=>"hun",
			"indien"=>"hin",
			"irlandais"=>"iri",
			"italien"=>"ita",
			"japonais"=>"jpn",
			"latin"=>"lat",
			"néerlandais"=>"dut",
			"norvégien"=>"nor",
			"polonais"=>"pol",
			"portugais"=>"por",
			"roumain"=>"rum",
			"russe"=>"rus",
			"suédois"=>"swe",
			"tchèque"=>"cze",
			"turc"=>"tus",
			"yiddish"=>"yid"
		);
	}
	
	if (!count($TabMonth)) {
		$TabMonth=array("janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre");
	}
	
	if (!$sf) {
		$param=$s["FIELDS"][0]["FIELD"];
		for ($i=0; $i<count($param); $i++) {
			$sf[$param[$i]["NAME"]]=$param[$i]["value"];
		}
	} 
	
	//-----------------------début de la notice---------------------------- 
	$data="<notice>\n<rs>n</rs>\n";
	
	//Explosion des champs dans un tableau
	$lignes=explode("\n",$notice);
	$before=false;
	$contenu="";
	for ($i=0; $i<count($lignes); $i++) {
		if ((!preg_match("/^[A-Z'\- ]+( )*\: /",$lignes[$i]))&&(trim($lignes[$i]))) {
			$contenu.=" ".trim($lignes[$i]);
		} else {
			if ($before) {
				if ($contenu[strlen($contenu)-1]=="/") $contenu=substr($contenu,0,strlen($contenu)-1);
				if (substr($contenu,0,3)=="#_#") {
					$f["URL"][0]=substr($contenu,3,strlen($contenu)-6);
				} else {
					$contenu=explode("/",$contenu);
					for ($j=0; $j<count($contenu); $j++) {
						$contenu[$j]=trim($contenu[$j]);
					}
					$f[$index]=$contenu;
				}
			}
			$ligne=explode(": ",trim($lignes[$i]));
			$index=trim($ligne[0]);
			unset($ligne[0]);
			$contenu=implode(": ",$ligne);
			$before=true;
		}
	}
	
	//Traitement
	//Si pas de titre alors erreur ou notice objet !
	if (!$f[$sf["titre"]]) {
		if ($f[$sf["nom_revue"]][0]) {
			$f[$sf["titre"]][0]="objet";
			$is_notice_objet=true;
		} else  {
			$r['VALID'] = false;
			$r['ERROR'] = "Le champ titre est vide ou inexistant";
			$r['DATA'] = "";
			return $r;
		}
	}
	
	//Champs généraux
	$valeur=$f[$sf["support_physique"]];
	$dt=$TabSupport[$valeur[0]];
	if (strtolower($valeur[0])=="périodique") {$bl="s";} else {$bl="m";}
	$data.="<dt>".$dt."</dt>\n";
	$data.="<bl>".$bl."</bl>\n";
	$data.="<hl>*</hl>\n<el>1</el>\n<ru>i</ru>\n";
	
	//ISBN ou autre
	if ($f[$sf["isbn"]][0]) {
		$data.="<f c='010'>\n";
		$data.="<s c='a'>".htmlspecialchars($f[$sf["isbn"]][0],ENT_QUOTES)."</s>\n";
		if ($f[$sf["prix"]][0]) {
			$data.="<s c='d'>".htmlspecialchars($f[$sf["prix"]][0],ENT_QUOTES)."</s>\n";
		}
		$data.="</f>\n";
	}
	
	//Langues
	if (($f[$sf["langue"]][0])||($f[$sf["langue_origine"]][0])) {
		if (($TabLangue[strtolower($f[$sf["langue"]][0])])||($TabLangue[strtolower($f[$sf["langue_origine"]][0])])) {
			$data.="<f c='101'>\n";
			if ($TabLangue[strtolower($f[$sf["langue"]][0])]) {
				$data.="<s c='a'>".$TabLangue[strtolower($f[$sf["langue"]][0])]."</s>\n";
			}
			if ($TabLangue[$f[$sf["langue_origine"]][0]]) {
				$data.="<s c='c'>".$TabLangue[strtolower($f[$sf["langue_origine"]][0])]."</s>\n";
			}
			$data.="</f>\n";
		}
	}
	
	//Titres
	$valeurs=$f[$sf["titre"]];
	$data.="<f c='200' ind='  '>\n";
	if ($f[$sf["article_titre"]]) {
		if ($f[$sf["article_titre"]][0][strlen($f[$sf["article_titre"]][0])-1]=="'") $espace=""; else $espace=" ";
		$valeurs[0]=$f[$sf["article_titre"]][0].$espace.$valeurs[0];
	}
	for ($i=0; $i<count($valeurs); $i++) {
		$data.="<s c='a'>".htmlspecialchars($valeurs[$i],ENT_QUOTES)."</s>\n";
	}
	$data.="</f>\n";
	
	//Série
	$valeurs=$f[$sf["serie"]];
	if (($valeurs[0])&&(!$is_notice_objet)) {
		$valeurs_=explode(".",$valeurs[0]);
		if ((count($valeurs_)>1)&&(($s["OPTIONS"][0]["OPTION"][0]["NAME"]=="coupe_titre_gen")&&($s["OPTIONS"][0]["OPTION"][0]["value"]=="yes"))) {
			$numero_serie=trim($valeurs_[count($valeurs_)-1]);
			unset($valeurs_[count($valeurs_)-1]);
			$valeurs[0]=implode(".",$valeurs_);
		} else $numero_serie="";
		$data.="<f c='461' ind='  '>\n";
		if ($f[$sf["article_serie"]]) {
			if ($f[$sf["article_serie"]][0][strlen($f[$sf["article_serie"]][0])-1]=="'") $espace=""; else $espace=" ";
			$valeurs[0]=$f[$sf["article_serie"]][0].$espace.$valeurs[0];
		}
		for ($i=0; $i<count($valeurs); $i++) {
			$data.="<s c='t'>".htmlspecialchars($valeurs[$i],ENT_QUOTES)."</s>\n";
			if ($numero_serie) {
				$data.="<s c='v'>".htmlspecialchars($numero_serie,ENT_QUOTES)."</s>\n";
			}
		}
		$data.="</f>\n";
	}
	
	//Auteurs
	$valeurs=$f[$sf["auteurs_principaux"]];
	if (($valeurs[0])&&(!$is_notice_objet)) {
		//Auteur principal
		$data.="<f c='700' ind='  '>\n";
		$elements=explode(",",$valeurs[0]);
		if (count($elements)>1) {
			$rejete=trim($elements[count($elements)-1]);
			unset($elements[count($elements)-1]);
			$entree=trim(implode(",",$elements));
			$data.="<s c='a'>".htmlspecialchars($entree,ENT_QUOTES)."</s>\n";
			$data.="<s c='b'>".htmlspecialchars($rejete,ENT_QUOTES)."</s>\n";
		} else $data.="<s c='a'>".htmlspecialchars($valeurs[0],ENT_QUOTES)."</s>\n";
		$data.="<s c='4'>070</s>\n";
		$data.="</f>\n";		
		
		//Co-auteurs
		for ($i=1; $i<count($valeurs); $i++) {
			$data.="<f c='701' ind='  '>\n";
			$elements=explode(",",$valeurs[$i]);
			if (count($elements)>1) {
				$rejete=trim($elements[count($elements)-1]);
				unset($elements[count($elements)-1]);
				$entree=trim(implode(",",$elements));
				$data.="<s c='a'>".htmlspecialchars($entree,ENT_QUOTES)."</s>\n";
				$data.="<s c='b'>".htmlspecialchars($rejete,ENT_QUOTES)."</s>\n";
			} else $data.="<s c='a'>".htmlspecialchars($valeurs[$i],ENT_QUOTES)."</s>\n";
			$data.="<s c='4'>070</s>\n";
			$data.="</f>\n";		
		}
		
		//Auteurs secondaires
		$valeurs=$f[$sf["auteurs_secondaires"]];
		if (($valeurs[0])&&(!$is_notice_objet)) {
			//Co-auteurs
			for ($i=0; $i<count($valeurs); $i++) {
				$data.="<f c='702' ind='  '>\n";
				$fonction="070";
				//Recherche de la fonction
				$elements=explode(" ",$valeurs[$i]);
				if ($TabFonction[strtolower(trim($elements[count($elements)-1]))]) {
						$fonction=$TabFonction[strtolower(trim($elements[count($elements)-1]))];
						unset($elements[count($elements)-1]);
				}
				$valeurs[$i]=implode(" ",$elements);
				$elements=explode(",",$valeurs[$i]);
				if (count($elements)>1) {
					$rejete=trim($elements[count($elements)-1]);
					unset($elements[count($elements)-1]);
					$entree=trim(implode(",",$elements));
					$data.="<s c='a'>".htmlspecialchars($entree,ENT_QUOTES)."</s>\n";
					$data.="<s c='b'>".htmlspecialchars($rejete,ENT_QUOTES)."</s>\n";
				} else $data.="<s c='a'>".htmlspecialchars($elements[0],ENT_QUOTES)."</s>\n";
				$data.="<s c='4'>".$fonction."</s>\n";
				$data.="</f>\n";		
			}
		}
	}
	
	//Editeurs
	if (($f[$sf["editeur"]][0])&&(!$is_notice_objet)) {
		$data.="<f c='210' ind='  '>\n";
		$data.="<s c='c'>".htmlspecialchars($f[$sf["editeur"]][0],ENT_QUOTES)."</s>\n";
		if (($f[$sf["annee_edition"]][0])&&($f[$sf["support_physique"]][0]!="périodique")) {
			$data.="<s c='d'>".htmlspecialchars($f[$sf["annee_edition"]][0],ENT_QUOTES)."</s>\n";
		}
		$data.="</f>\n";
	}
	
	
	//Périodique
	if ($f[$sf["support_physique"]][0]=="périodique") {
		$data.="<f c='464'>\n";
		//Nom de la revue
		if ($f[$sf["article_nom_revue"]][0]) {
			if ($f[$sf["article_nom_revue"]][0][strlen($f[$sf["article_nom_revue"]][0])-1]!="'") {
				$espace=" ";
			} else $espcae="";
			$article=$f[$sf["article_nom_revue"]][0];
		} else {
			$article="";
			$espace="";
		}
		$data.="<s c='t'>".htmlspecialchars($article.$espace.$f[$sf["nom_revue"]][0],ENT_QUOTES)."</s>\n";
		//Volume
		$vols=explode(",",$f[$sf["numero_revue"]][0]);
		if (count($vols)) {
			$vol=$vols[0];
			if ($f[$sf["numero_revue"]][1]=="cyclique") $vol.="/".$f[$sf["annee_edition"]][0];
			if (count($vols)==3) {
				$vol.=" ".$vols[1];
				$id_mention_date=2;
			} else $id_mention_date=1;
			$mention_date=trim($vols[$id_mention_date])." ".$f[$sf["annee_edition"]][0];
			$data.="<s c='v'>".htmlspecialchars(substr($vol,0,20),ENT_QUOTES)."</s>\n";
			$data.="<s c='d'>".htmlspecialchars($mention_date,ENT_QUOTES)."</s>\n";
			$date_p="";
			for ($m=0; $m<count($TabMonth); $m++) {
				$pm=strpos(strtolower($vols[1]),$TabMonth[$m]);
				if (!($pm===false)) {
					$date_p=$f[$sf["annee_edition"]][0]."-".($m+1)."-01";
					break;
				}
			}
			if ($date_p) $data.="<s c='e'>".htmlspecialchars($date_p,ENT_QUOTES)."</s>\n";
		}
		if ($f[$sf["collation_pagination"]][0]) {
			$data.="<s c='p'>".htmlspecialchars($f[$sf["collation_pagination"]][0],ENT_QUOTES)."</s>\n";
		}
		if ($is_notice_objet) $data.="<s c='z'>objet</s>";
		$data.="</f>\n";
	}
	
	//Collation
	if (($f[$sf["collation_pagination"]][0])&&($f[$sf["support_physique"]][0]!="périodique")) {
		$data.="<f c='215'>\n";
			$data.="<s c='a'>".htmlspecialchars($f[$sf["collation_pagination"]][0],ENT_QUOTES)."</s>\n";
		$data.="</f>\n";
	}
	
	//Notes
	if ($f[$sf["notes"]][0]) {
		$data.="<f c='300'>\n";
			$data.="<s c='a'>".htmlspecialchars($f[$sf["notes"]][0],ENT_QUOTES)."</s>\n";
		$data.="</f>\n";
	}
	
	//Note de contenu
	if ($f[$sf["notes_contenu"]][0]) {
		$data.="<f c='327'>\n";
			$data.="<s c='a'>".htmlspecialchars($f[$sf["notes_contenu"]][0],ENT_QUOTES)."</s>\n";
		$data.="</f>\n";
	}
	
	//Résumé
	if ($f[$sf["resume"]][0]) {
		$resume=implode("/",$f[$sf["resume"]]);
		$data.="<f c='330'>\n";
			$data.="<s c='a'>".htmlspecialchars($resume,ENT_QUOTES)."</s>\n";
		$data.="</f>\n";
	}
	
	//Collection et sous collection
	if (($f[$sf["collection"]][0])&&(!$is_notice_objet)) {
		$data.="<f c='225'>\n";
		$numero_coll=explode(";", $f[$sf["collection"]][0]);
		$numero_subcoll=explode(";", $f[$sf["collection"]][1]);
		$data.="<s c='a'>".htmlspecialchars(trim($numero_coll[0]),ENT_QUOTES)."</s>\n";
		if (trim($numero_coll[1])||(trim($numero_subcoll[1]))) {
			$nc=array();
			if (trim($numero_coll[1])) 
				$nc[0]=trim($numero_coll[1]);
			if (trim($numero_subcoll[1]))
				$nc[1]=trim($numero_subcoll[1]);
			$numero_collection=implode(" ; ",$nc);
		}
		if ($f[$sf["collection"]][1]) {
			$data.="<s c='i'>".htmlspecialchars(trim($numero_subcoll[0]),ENT_QUOTES)."</s>\n";
		}
		if  ($numero_collection) {
			$data.="<s c='v'>".htmlspecialchars($numero_collection,ENT_QUOTES)."</s>\n";
		}
		$data.="</f>\n";
	}
	
	//Mots clés
	if ($f[$sf["mots_cles"]][0]) {
		$data.="<f c='610'>\n";
		$mcle=array();
		for ($i=0; $i<count($f[$sf["mots_cles"]]); $i++) {
			$mcle[]=$f[$sf["mots_cles"]][$i];
		}
		$mcle_f=implode(" / ",$mcle);
		$data.="<s c='a'>".htmlspecialchars($mcle_f,ENT_QUOTES)."</s>\n";
		$data.="</f>\n";
	}
	
	//Thésaurus
	if (($f[$sf["descripteurs"]][0])&&(!$is_notice_objet)) {
		for ($i=0; $i<count($f[$sf["descripteurs"]]); $i++) {
			$data.="<f c='606'>\n";
			$data.="<s c='a'>".htmlspecialchars($f[$sf["descripteurs"]][$i],ENT_QUOTES)."</s>\n";
			$data.="</f>\n";
		}
	}
	
	//URL
	if ($f["URL"][0]) {
		$data.="<f c='856'>\n";
		$data.="<s c='u'>".htmlspecialchars($f["URL"][0],ENT_QUOTES)."</s>\n";
		$data.="</f>\n";
	}
	
	//Origine
	if ($f[$sf["origine"]][0]) {
		$data.="<f c='801'>\n";
		$data.="<s c='b'>".htmlspecialchars($f[$sf["origine"]][0],ENT_QUOTES)."</s>\n";
		$data.="</f>\n";
	}
	
	//Champs bizarres en 90x
	//Thèmes
	if ($f[$sf["themes"]][0]) {
		for ($i=0; $i<count($f[$sf["themes"]]); $i++) {
			$data.="<f c='900'>\n";
			$data.="<s c='a'>".htmlspecialchars($f[$sf["themes"]][$i],ENT_QUOTES)."</s>\n";
			$data.="</f>\n";
		}
	}
	
	//Genre ou forme
	if ($f[$sf["genre"]][0]) {
		for ($i=0; $i<count($f[$sf["genre"]]); $i++) {
			$data.="<f c='901'>\n";
			$data.="<s c='a'>".htmlspecialchars($f[$sf["genre"]][$i],ENT_QUOTES)."</s>\n";
			$data.="</f>\n";
		}
	}
	
	//Discipline
	if ($f[$sf["discipline"]][0]) {
		for ($i=0; $i<count($f[$sf["discipline"]]); $i++) {
			$data.="<f c='902'>\n";
			$data.="<s c='a'>".htmlspecialchars($f[$sf["discipline"]][$i],ENT_QUOTES)."</s>\n";
			$data.="</f>\n";
		}
	}
	
	//Année de péremption
	if ($f[$sf["annee_peremption"]][0]) {
		$data.="<f c='903'>\n";
		$data.="<s c='a'>".htmlspecialchars($f[$sf["annee_peremption"]][0],ENT_QUOTES)."</s>\n";
		$data.="</f>\n";
	}
	
	//Date de saisie
	if ($f[$sf["date_saisie"]][0]) {
		$annee=substr($f[$sf["date_saisie"]][0],0,4);
		$mois=substr($f[$sf["date_saisie"]][0],4,2);
		$jour=substr($f[$sf["date_saisie"]][0],6,2);
		if (checkdate($mois,$jour,$annee)) {
			$date=$annee."-".$mois."-".$jour;
			$data.="<f c='904'>\n";
			$data.="<s c='a'>".$date."</s>\n";
			$data.="</f>\n";
		}
	}
	
	//Type de nature
	if ($f[$sf["type_document"]][0]) {
		$data.="<f c='905'>\n";
		$data.="<s c='a'>".htmlspecialchars($f[$sf["type_document"]][0],ENT_QUOTES)."</s>\n";
		$data.="</f>\n";
	}
	
	//Niveau
	if ($f[$sf["niveau"]][0]) {
		for ($i=0; $i<count($f[$sf["niveau"]]); $i++) {
			$data.="<f c='906'>\n";
			$data.="<s c='a'>".htmlspecialchars($f[$sf["niveau"]][$i],ENT_QUOTES)."</s>\n";
			$data.="</f>\n";
		}
	}
	
	//Exemplaires
	if ($f[$sf["numero_ex"]][0]) {
		for ($i=0; $i<count($f[$sf["numero_ex"]]); $i++) {
			$data.="<f c='995'>\n";
			//Section
			$data.="<s c='t'>".htmlspecialchars($f[$sf["type_document"]][0],ENT_QUOTES)."</s>\n";
			//Code statistique
			if ($f[$sf["code_stat"]][0]) {
				$data.="<s c='q'>".htmlspecialchars($f[$sf["code_stat"]][0],ENT_QUOTES)."</s>\n";
			}
			//Numéro d'exemplaire
			$data.="<s c='f'>".htmlspecialchars($f[$sf["numero_ex"]][$i],ENT_QUOTES)."</s>\n";
			if ($f[$sf["cote"]][0]) {
			    $cote_bretagne=implode("/",$f[$sf["cote"]]);
				$data.="<s c='k'>".htmlspecialchars($cote_bretagne,ENT_QUOTES)."</s>\n";
			}
			//Type de document
			$data.="<s c='r'>".htmlspecialchars(strtolower($f[$sf["support_physique"]][0]),ENT_QUOTES)." ".htmlspecialchars($f[$sf["groupe_pret"]][0])."</s>\n";
			//Commentaire
			if ($f[$sf["commentaires_ex"]][0]) {
				$data.="<s c='u'>".htmlspecialchars($f[$sf["commentaires_ex"]][0],ENT_QUOTES)."</s>\n";
			}
			//Localisation
			if ($f[$sf["localisation"]][0]) {
				$data.="<s c='a'>".htmlspecialchars($f[$sf["localisation"]][0],ENT_QUOTES)."</s>\n";
			}
			$data.="</f>\n";
		}
	}

	$data.="</notice>\n";
	$r['VALID'] = true;
	$r['ERROR'] = "";
	$r['DATA'] = $data;
	return $r;
}
?>
