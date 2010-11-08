<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/emprunteur.class.php");

function show_import_choix_fichier($dbh) {
	global $msg;
	global $current_module ;

print "
<form class='form-$current_module' name='form1' ENCTYPE=\"multipart/form-data\" method='post' action=\"./admin.php?categ=empr&sub=implec&action=1\">
<h3>Choix du fichier</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_import_lec'>".$msg["import_lec_fichier"]."</label>
        <input name='import_lec' accept='text/plain' type='file' class='saisie-80em' size='40'>
	</div>
	<br />
	<div class='row'>
		<b>Pour plus de sécurité avant de lancer l'import faire une sauvegarde de la base de pmb</b>	
	</div>	
    <br />
	<div class='row'>
		Le fichier doit être au format cvs en ISO-8859-15 avec , comme séparateur de champ et le texte entre guillemets (ex : \"Nom\")	
	</div>
	<div class='row'>
		Fichier etudiant : \"MatriculeHenam\",\"Code_barres\",\"Nom\",\"Prenom\",\"Adresse\",\"Code_postal\",\"Ville\",\"Pays\",\"Telephone_1\",\"Telephone_2\",\"Email\",\"Sexe\",\"Date_naissance\",\"Email_perso\",\"Localisation\",\"Groupe_1\",\"Groupe_2\",\"Login\"
	</div>
	<div class='row'>
		Fichier professeur : \"MatriculeHenam\",\"Code_barres\",\"Nom\",\"Prenom\",\"Adresse\",\"Code_postal\",\"Ville\",\"Pays\",\"Telephone_1\",\"Telephone_2\",\"Email\",\"Sexe\",\"Date_naissance\",\"Login\"
	</div>
	<div class='row'>
		Si la première ligne du fichier comporte les entêtes des colonnes il faut que la première ligne, première colonne soit 'MatriculeHenam'	
	</div>
	<div class='row'>
		Les statuts de lecteur \"A supprimer\" et \"Importé\" ne doivent pas avoir été supprimés.
	</div>
	<div class='row'>
		Les codages d'import des localisations ne doivent pas être modifiés
	</div>
	<div class='row'>
		Le controle des lecteurs se fait sur le matricule et le code barres
	</div>
	<div class='row'>
		Si l'on choisi de supprimer les lecteurs présents dans le fichier et qu'ils ont des prêts en cours seul leur statut sera mis à \"A supprimer\"	
	</div>
</div>
<h3>Type d'utilisation</h3>			
<div class='form-contenu'>
	<div class='row'>
		<b>Choisisez si vous voulez faire un import ou une suppression de lecteurs : </b>	
	</div>
	<div class='row'>
		<input type=radio name='type_import' value='nouveau_lect' checked>
        <label class='etiquette' for='form_import_lec'>Importer</label>
        (ajoute ou modifie les lecteurs présents dans le fichier)
        <br />
        <input type=radio name='type_import' value='maj_complete'>
        <label class='etiquette' for='form_import_lec'>Supprimer</label>
        (supprime les lecteurs présents dans le fichier et dans la base s'ils n'ont pas de prêt en cours)
	</div>
	<br />
	<div class='row'>
		<b>Choisisez si vous voulez remettre tous les statuts des lecteurs à \"Importé\"</b> (actualisation annuelle)<b> : </b>
	</div>
	<div class='row'>
		<input type=radio name='type_modif' value='garder_statut' checked>
        <label class='etiquette' for='form_import_lec'>Ne pas modifier le statut</label>
        <br />
        <input type=radio name='type_modif' value='modif_statut'>
        <label class='etiquette' for='form_import_lec'>Mettre le statut \"Importé\" à tous les lecteurs</label>
	</div>
</div>
<div class='row'>
	<input name='imp_empr' type='submit' class='bouton' value='Importer les lecteurs'/>
</div>
</form>";
}

function import_lect_par_lect($tab,$dbh){
	global $lect_cree,$lect_erreur;
	//update empr set `empr_modif`= DATE_SUB(empr_modif, INTERVAL 6 MONTH),`empr_date_expiration`= DATE_SUB(`empr_date_expiration`, INTERVAL 6 MONTH)
	
	$data=array();
	if(count($tab) == 18){
		//Si on a 16 champs c'est un etudiant
		$data['categ_libelle_create']="Etudiants HENAM";
	}else{
		//Sinon c'est un prof
		$data['categ_libelle_create']="Professeurs HENAM";
	}

	$data['codestat_libelle_create']="-";
	
	$data['date_creation']=date('Y-m-j');
	$data['date_adhesion']=date('Y-m-j');
	$data['date_modif']=date('Y-m-j');
	
	if (($result = mysql_query("SELECT DATE_ADD('".addslashes(date('Y-m-j'))."', INTERVAL 1 YEAR)"))) {
		if (($row = mysql_fetch_row($result))) { 
			$data['date_expiration']= $row[0];		
		}
	}
	
	$empr_cb =$tab[1];
	if(!$empr_cb)$empr_cb="ind";
	$pb = 1 ;
	$num_cb=1 ;
	$empr_cb2=$empr_cb;
	while ($pb==1) {
		$q = "SELECT empr_cb FROM empr WHERE empr_cb='".addslashes($empr_cb2)."' LIMIT 1 ";
		$r = mysql_query($q, $dbh);
		$nb = mysql_num_rows($r);
		if ($nb) {
			$empr_cb2 =$empr_cb."-".$num_cb ;
			$num_cb++;
		} else $pb = 0 ;
	}
	$data['cb']=$empr_cb2;
	/*if($data['cb'] != $tab[1]){
		$lect_erreur++;
		echo "<b>Erreur : pour le lecteur ".$tab[2]." ".$tab[3]." le code barres ".$data['cb']." est déja utilisé comme code barre pour un autre lecteur</b><br />";
		return;
	}*/
	
	$data['nom']=$tab[2];
	
	$data['prenom']=$tab[3];

	if($tab[11] == "F"){
		$data['sexe']=2;
	}elseif($tab[11] == "M"){
		$data['sexe']=1;
	}else{
		$data['sexe']=0;
	}
	
	
	$data['adr1']=$tab[4];
	
	$data['adr2']="";

	$data['ville']=ucfirst(mb_strtolower($tab[6]));
	

	$data['pays']=$tab[7];
	
	$data['cp']=$tab[5];;
	
	$data['mail']=$tab[10];

	$data['tel1']=$tab[8];
	
	$data['tel2']=$tab[9];
	
	$data['prof']="";
	
	$date=explode("/",$tab[12]);
	$data['year']=$date[2];
	
	
	
	if(count($tab) == 18){
		//Si on a 18 champs c'est un etudiant
		$data['login'] = $tab[17];
	}else{
		//Sinon c'est un prof
		$data['login'] = $tab[13];
	}
	
	$data['password']=$tab[1];
	
	$data['location_libelle_create']=$tab[14];
	if(!$data['location_libelle_create']){
		$data['location_libelle_create']="Indéterminé";
	}
	
	$data['msg']="";

	$data['lang']='fr_FR';
	
	$data['statut_libelle_create']="Importé";
	
	$mon_emprunteur= new emprunteur();
	$id_empr=$mon_emprunteur->import($data);
	if(!$id_empr){
		$lect_erreur++;
		echo "Erreur : Lecteur non créé\n";
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}else{
		$lect_cree++;
		if ($tab[13] and count($tab) == 18) {
			$q="select idchamp from empr_custom where name='email_perso' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$idchamp=mysql_result($r,0,0);
				$q = "insert into empr_custom_values (empr_custom_champ, empr_custom_origine, empr_custom_small_text) ";
				$q.= "values('".$idchamp."', '".$id_empr."','".addslashes($tab[13])."' ) ";
				$r=mysql_query($q, $dbh);
			}
		}
		if ($tab[0]) {
			$q="select idchamp from empr_custom where name='matricule' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$idchamp=mysql_result($r,0,0);
				$q = "insert into empr_custom_values (empr_custom_champ, empr_custom_origine, empr_custom_small_text) ";
				$q.= "values('".$idchamp."', '".$id_empr."','".addslashes($tab[0])."' ) ";
				$r=mysql_query($q, $dbh);
			}
		}
		if(trim($tab[15])){
			//On créer le groupe si il n'existe pas et on y affecte le lecteur
			$requete="select id_groupe from groupe where libelle_groupe='".addslashes(trim($tab[15]))."'";
			$r = mysql_query($requete, $dbh);
			if (mysql_num_rows($r)) {
				$id_grp=mysql_result($r,0,0);
			}else{
				$q= "insert into groupe (libelle_groupe) values ('".addslashes(trim($tab[15]))."') ";
				$r = mysql_query($q, $dbh);
				$id_grp =mysql_insert_id($dbh);
			}
			$requete="insert into empr_groupe(empr_id,groupe_id) values ('".$id_empr."','".$id_grp."')";
			if(!mysql_query($requete)) echo "Requete echoué : ".$requete."<br>";	
		}
		
		if(trim($tab[16]) and trim($tab[16]) != trim($tab[15])){
			//On créer le groupe si il n'existe pas et on y affecte le lecteur
			$requete="select id_groupe from groupe where libelle_groupe='".addslashes(trim($tab[16]))."'";
			$r = mysql_query($requete, $dbh);
			if (mysql_num_rows($r)) {
				$id_grp=mysql_result($r,0,0);
			}else{
				$q= "insert into groupe (libelle_groupe) values ('".addslashes(trim($tab[16]))."') ";
				$r = mysql_query($q, $dbh);
				$id_grp =mysql_insert_id($dbh);
			}
			$requete="insert into empr_groupe(empr_id,groupe_id) values ('".$id_empr."','".$id_grp."')";
			if(!mysql_query($requete)) echo "Requete echoué : ".$requete."<br>";	
		}
	}
}

function supp_lect_par_lect($tab,$dbh){
	global $lect_erreur,$lect_supprime,$lect_interdit;
	$requete="select id_empr,pret_idexpl from empr left join pret on id_empr=pret_idempr join empr_custom_values on empr_custom_origine=id_empr where empr_cb like '".addslashes($tab[1])."%' and empr_custom_champ='2' and empr_custom_small_text='".addslashes($tab[0])."' group by id_empr";
	$select = mysql_query($requete,$dbh);
	$nb_enreg = mysql_num_rows($select);
	if($nb_enreg == 1){
		$id=mysql_result($select,0,0);
		if(!mysql_result($select,0,1)){
			//Si il n'a pas de pret en cours
			emprunteur::del_empr($id);
			$lect_supprime++;
		}else{
			//On modifi le statut
			$q="select idstatut from empr_statut where statut_libelle='A supprimer' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$id_statut =mysql_result($r,0,0);	
			} else {
				$q= "insert into empr_statut (statut_libelle) values ('A supprimer') ";
				$r = mysql_query($q, $dbh);
				$id_statut =mysql_insert_id($dbh);
			}
			$requete="update empr set empr_statut='".$id_statut."' where id_empr='".$id."' ";
			if(mysql_query($requete)){
				$lect_interdit++;
			}else{
				$lect_erreur++;
				echo "<b>Erreur : Pour le lecteur ".$tab[2]." ".$tab[3]." avec le code barre ".$tab[1]." un problème est survenu lors de la modification de son statut<b><br />";
			}
		}
	}elseif($nb_enreg > 1){
		$lect_erreur++;
		echo "<b>Erreur : Attention le code barre ".$tab[1]." est en double dans la base veuillez le modifier pour l'un des deux lecteurs<b><br />";
		return;
	}else{
		$lect_erreur++;
		echo "<b>Erreur : Attention le lecteur ".$tab[2]." ".$tab[3]." avec le code barre ".$tab[1]." n'existe pas dans la base, il ne sera pas supprimé<b><br />";
		return;
	}
	
}

function maj_lect_par_lect($tab,$dbh,$statut,$id_lect){
	global $lect_modif,$lect_erreur;
	/*Les informations qui sont mise à jour sont :
	* l'adresse, le code postal, la ville, le pays, le telephone, emailPerso, la locatisation et le groupe
	*/
	
	$requete = "update empr set ";
	$requete .= "empr_cp='".addslashes($tab[5])."'";
	$requete .= ", empr_ville='".addslashes(ucfirst(mb_strtolower($tab[6])))."'";
	$requete .= ", empr_adr1='".addslashes($tab[4])."'";
	$requete .= ", empr_pays='".addslashes($tab[7])."'";
	$requete .= ", empr_tel1='".addslashes($tab[8])."'";
	$requete .= ", empr_tel2='".addslashes($tab[9])."'";
	$requete .= ", empr_mail='".addslashes($tab[10])."'";
	
	if(count($tab) == 18){
		//Si on a 18 champs c'est un etudiant
		$requete .= ", empr_login='".addslashes($tab[17])."'";
	}else{
		//Sinon c'est un prof
		$requete .= ", empr_login='".addslashes($tab[13])."'";
	}
	
	if($tab[14]){
		$data2=array();
		$data2['location_libelle'] = $tab[14];	
		$data2['locdoc_codage_import'] = $tab[14];
		$data2['locdoc_owner'] = 0;
		$localisation = docs_location::import($data2);
		$requete .= ", empr_location='".$localisation."'";
	}
	
	if($statut){
		//On repasse le statut de tous les lecteurs à "Indétermiè" et on remet les dates
		$requete .= ", empr_date_adhesion='".addslashes(date('Y-m-j'))."'";
		$requete .= ", empr_modif='".addslashes(date('Y-m-j'))."'";
		
		if (($result = mysql_query("SELECT DATE_ADD('".addslashes(date('Y-m-j'))."', INTERVAL 1 YEAR)"))) {
			if (($row = mysql_fetch_row($result))) { 
				$requete .= ", empr_date_expiration='".addslashes($row[0])."'";
			}
		}
		$q="select idstatut from empr_statut where statut_libelle='Importé' limit 1";
		$r = mysql_query($q, $dbh);
		if (mysql_num_rows($r)) {
			$requete .= ", empr_statut='".mysql_result($r,0,0)."'";
		} else {
			$q= "insert into empr_statut (statut_libelle) values ('Importé') ";
			$r = mysql_query($q, $dbh);
			$requete .= ", empr_statut='".mysql_insert_id($dbh)."'";
		}
		
	}else{
		$requete .= ", empr_modif='".addslashes(date('Y-m-j'))."'";
	}
	$requete .= " where id_empr='".$id_lect."'";
	if(mysql_query($requete)){
		$lect_modif++;
	}else{
		$lect_erreur++;
		echo "Requete echoué : ".$requete."<br>";
	}
	
	//Traitement des groupes
	$requete="delete from empr_groupe where empr_id='".$id_lect."'";
	if(!mysql_query($requete)) echo "Requete echoué : ".$requete."<br>";
	
	if(trim($tab[15])){
		//On créer le groupe si il n'existe pas et on y affecte le lecteur
		$requete="select id_groupe from groupe where libelle_groupe='".addslashes(trim($tab[15]))."'";
		$r = mysql_query($requete, $dbh);
		if (mysql_num_rows($r)) {
			$id_grp=mysql_result($r,0,0);
		}else{
			$q= "insert into groupe (libelle_groupe) values ('".addslashes(trim($tab[15]))."') ";
			$r = mysql_query($q, $dbh);
			$id_grp =mysql_insert_id($dbh);
		}
		$requete="insert into empr_groupe(empr_id,groupe_id) values ('".$id_lect."','".$id_grp."')";
		if(!mysql_query($requete)) echo "Requete echoué : ".$requete."<br>";	
	}
	
	if(trim($tab[16]) and trim($tab[16]) != trim($tab[15])){
		//On créer le groupe si il n'existe pas et on y affecte le lecteur
		$requete="select id_groupe from groupe where libelle_groupe='".addslashes(trim($tab[16]))."'";
		$r = mysql_query($requete, $dbh);
		if (mysql_num_rows($r)) {
			$id_grp=mysql_result($r,0,0);
		}else{
			$q= "insert into groupe (libelle_groupe) values ('".addslashes(trim($tab[16]))."') ";
			$r = mysql_query($q, $dbh);
			$id_grp =mysql_insert_id($dbh);
		}
		$requete="insert into empr_groupe(empr_id,groupe_id) values ('".$id_lect."','".$id_grp."')";
		if(!mysql_query($requete)) echo "Requete echoué : ".$requete."<br>";	
	}
	
	if ($tab[13] and count($tab) == 18) {
		//Traitement du champs perso email
		$q="select idchamp from empr_custom where name='email_perso' limit 1";
		$r = mysql_query($q, $dbh);
		if (mysql_num_rows($r)) {
			$idchamp=mysql_result($r,0,0);
			//On supprime l'ancien
			$requete="delete from empr_custom_values where empr_custom_origine='".$id_lect."' and empr_custom_champ='".$idchamp."'";
			if(!mysql_query($requete)) echo "Requete echoué : ".$requete."<br>";
			//On créer le nouveau
			$q = "insert into empr_custom_values (empr_custom_champ, empr_custom_origine, empr_custom_small_text) ";
			$q.= "values('".$idchamp."', '".$id_lect."','".addslashes($tab[13])."' ) ";
			mysql_query($q, $dbh);
		}
	}
	
}

function decoup_fic_lect($fichier){
	$row = 0;
	$notices=array();
	while (($data = fgetcsv($fichier,0,",")) !== FALSE) {
		$notices[$row]=$data;
		$row++;
	}
	return $notices;	
}

function import_empr($dbh){
	global $lect_cree,$lect_erreur,$lect_modif,$type_import,$type_modif,$lect_supprime,$lect_interdit;
	$lect_tot=0;
	$lect_supprime=0;
	$lect_cree=0;
	$lect_erreur=0;
	$lect_modif=0;
	$lect_interdit=0;
	
	//La structure du fichier texte doit être la suivante avec ceci comme première ligne:
	// Etudiant 
    // "MatriculeHenam","empr_cb","empr_nom","empr_prenom","empr_adr1","empr_cp","empr_ville","empr_pays","empr_tel1","empr_tel2","empr_mail","empr_sexe","empr_year","EmailPerso","localisation","groupe"
    //Professeur
    // "MatriculeHenam","empr_cb","empr_nom","empr_prenom","empr_adr1","empr_cp","empr_ville","empr_pays","empr_tel1","empr_tel2","empr_mail","empr_sexe","empr_year"
    //Upload du fichier
    if (!($_FILES['import_lec']['tmp_name'])){
    	print "Cliquez sur Pr&eacute;c&eacute;dent et choisissez un fichier";
        return ;
    }elseif (!(move_uploaded_file($_FILES['import_lec']['tmp_name'], "./temp/".basename($_FILES['import_lec']['tmp_name'])))) {
        print "Le fichier n'a pas pu être téléchargé. Voici plus d'informations :<br />";
        print_r($_FILES)."<p>";
        return ;
    }
    $fichier = @fopen( "./temp/".basename($_FILES['import_lec']['tmp_name']), "r" );
       
    if ($fichier) {
        $lect=decoup_fic_lect($fichier);
        for($i=0;$i<count($lect);$i++){
        	$statut="";
        	if($type_modif == "modif_statut"){
        		$statut="Importé";
        	}
        	if(count($lect[$i]) == 1 or $lect[$i][0] == "MatriculeHenam"){
	        	//Passe ici pour l'entête et les ligne vide (la dernière)
	        }elseif(count($lect[$i]) != 18 && count($lect[$i]) != 14){
	        	$lect_tot++;
	        	$lect_erreur++;
	        	print("<b>Erreur : Personne non prise en compte car le nombre de champ n'est pas valide : </b><br />");
	        	echo "<pre>";
	      	 	print_r($lect[$i]);
	        	echo "</pre>";
	        }elseif(trim($lect[$i][0]) == "" or trim($lect[$i][1]) == "" or trim($lect[$i][2]) === ""){
	        	$lect_tot++;
	        	$lect_erreur++;
	        	print("<b>Erreur : Personne non prise en compte car elle n'a pas de nom, de code barres ou de matricule : </b><br />");
	        	echo "<pre>";
	      	 	print_r($lect[$i]);
	        	echo "</pre>";
	        }else{
	        	$lect_tot++;
	        	if($type_import == "nouveau_lect"){
		        	//Tout les lecteurs à traiter
	        		
	        		//On regarde si le lecteur existe déja en le recherchant par son badge
					$requete="select id_empr from empr join empr_custom_values on empr_custom_origine=id_empr where empr_cb LIKE '".addslashes($lect[$i][1])."%' and empr_custom_champ='2' and empr_custom_small_text='".addslashes($lect[$i][0])."' ";
					$select = mysql_query($requete,$dbh);
					$nb_enreg = mysql_num_rows($select);
					if($nb_enreg == 1){
						maj_lect_par_lect($lect[$i],$dbh,$statut,mysql_result($select,0,0));
					}elseif($nb_enreg > 1){
						$lect_erreur++;
						echo "<b>Erreur : Attention le code barre ".$lect[$i][0]." est en double dans la base veuillez le modifier pour l'un des deux lecteurs<b><br />";
						return;
					}else{
						import_lect_par_lect($lect[$i],$dbh);
					}
		        }else{
		        	supp_lect_par_lect($lect[$i],$dbh);
		        	$group_supp=0;
		        	if($i+1 == count($lect)){
		        		$requete="delete groupe from groupe left join empr_groupe on id_groupe=groupe_id where empr_id is null";
		        		$res=mysql_query($requete, $dbh);
		        		$group_supp=mysql_affected_rows();
		        	}
		        }	
	        }
        }
    	print("<br />_____________________<br />");
    	if($lect_erreur)echo "<b> Attention ".$lect_erreur." lecteur(s) n'a(ont) pas été traité(s) : voir erreur(s) ci-dessus </b><br />";
    	echo "Nombre total de lecteurs dans le fichier : ".$lect_tot."<br />";
        if($type_import == "nouveau_lect"){
	    	echo "Nombre de lecteurs créés : ".$lect_cree."<br />";
        	echo "Nombre de lecteurs modifiés : ".$lect_modif."<br />";
	    }else{
        	echo "Nombre d'anciens lecteurs supprimés : ".$lect_supprime."<br />";
	        echo "Nombre d'anciens lecteurs avec un statut Interdit (non supprimés car ils ont au moins un prêt en cours) : ".$lect_interdit."<br />";
	        echo "Nombre de groupes inutilisés supprimés : ".$group_supp."<br />";
        }
        fclose($fichier);
    }
}

switch($action) {
    case 1:
        if ($imp_empr){
            import_empr($dbh);
        }
        else {
            show_import_choix_fichier($dbh);
        }
        break;
    case 2:
        break;
    default:
        show_import_choix_fichier($dbh);
        break;
}

?>



