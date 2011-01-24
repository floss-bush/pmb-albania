<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import_empr.inc.php,v 0.11 2003/11/13 10:25

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/emprunteur.class.php");

//modif Massimo Mancini & Marco Vaninetti
// modif sauvegarde $OrdreImportEmpr en base 
$base_path=".";

$querry = "select valeur_param from parametres where type_param = 'empr' and sstype_param='corresp_import' ";
$res = mysql_query($querry, $dbh);
$obj = mysql_fetch_object($res) ;

if ($obj->valeur_param) {
	$OrdreImportEmpr=unserialize($obj->valeur_param);
} else {
	$querry = "DESCRIBE empr;";
	$res = mysql_query($querry, $dbh);
	$nbr = mysql_num_rows($res);
	if($nbr) {
		$k = 0;$OrdreImportEmpr=array();
		for($j=0;$j<$nbr;$j++) {
			$row=mysql_fetch_row($res);
			$OrdreImportEmpr[$row[0]] = 0;
		}
	}
	// recup des champs perso
	$querry = "SELECT * from empr_custom;";
	$res = mysql_query($querry, $dbh);
	if (mysql_num_rows($res)) {
		while (($row=mysql_fetch_array($res,MYSQL_ASSOC))){
			$OrdreImportEmpr[$row['name']] = 0;
		}
	}
}

print "
	<script type='text/javascript'>
		function aide_regex()
		{
			var fenetreAide;
			fenetreAide = openPopUp('./help.php?whatis=import_empr', 'regex_howto', 500,400,-2,-2, 'scrollbars=yes');
		}
	</script>";


function show_import_choix_fichier($dbh,$from_ldap) {
	global $msg;
	global $charset;
	global $current_module ;
	// $result = mysql_query("Select duree_adhesion, libelle From empr_categ;", $dbh) or die($msg["err_sql"]);
	// premier formulaire pour avoir le nom du fichier a importer, le s?parateur de champ
	// et indiquer dans quel groupe importation va allez

	// MaxMan
	$result = mysql_query("Select id_categ_empr, libelle From empr_categ;", $dbh) or die($msg["err_sql"]);

	$result2 = mysql_query("Select idcode, libelle From empr_codestat;", $dbh) or die($msg["err_sql"]);

	if (!$from_ldap) {
		$formtype="<form class='form-$current_module' name='form1' ENCTYPE=\"multipart/form-data\" method='post' action=\"./admin.php?categ=empr&sub=implec&action=FichierOK\">
		<h3>".$msg["empr_import"]."</h3>
		<div class='form-contenu'>
		<div class='row'>
				<div class='colonne60'>
					<label class='etiquette' for='form_import_lec'>".$msg["import_lec_fichier"]."</label>
					<input name='import_lec' accept='text/plain' type='file'  size='40'>
				</div>
				<div class='colonne_suite'>
					<label class='etiquette' for='form_import_lec'>". $msg["import_lec_separateur"]."</label>
					<input type='textfield'  value=',' name='Sep_Champs' class='saisie-2em'>
				</div>
		</div>
		<br /><br />";
	} else {
		$formtype="<form class='form-$current_module' name='form1' method='post' action=\"./admin.php?categ=empr&sub=implec&action=FichierOK&from_ldap=1\">
					<input type='hidden'  value='|' name='Sep_Champs'>
		<h3>".$msg["import_ldap"]."</h3>
		<div class='form-contenu'>";
	}

	print "
		$formtype
		<div class='row'>
			<div class='colonne2'>
				<label class='etiquette' for='form_import_lec'>". $msg["import_lec_Cat"]."</label>
				<select name='selectGroupe'>";
	while(($row = mysql_fetch_row($result))) // pour remplir la listBox
		print "<option value='".
					htmlentities($row[0],ENT_QUOTES, $charset)."'>".
					htmlentities($row[1],ENT_QUOTES, $charset).
				"</option>";
	print "
			</select>
		</div>
		<div class='colonne2'>
			<label class='etiquette' for='form_import_stat'>". $msg[60]."</label>
			<select name='selectStat'>";
	
	while(($row = mysql_fetch_row($result2))) // pour remplir la listBox
		print "<option value='".
					htmlentities($row[0],ENT_QUOTES, $charset)."'>".
					htmlentities($row[1],ENT_QUOTES, $charset).
				"</option>";
	
	print "
			</select>
		</div>
	</div>
	<br />
	</div>
	<div class='row'>
		<table class='table-but'><tr>
		<td class='td-lbut'>
			<input type='submit' class='bouton' name='envoyer' value='".$msg[502]."'/>
		</td>
		<td class='td-rbut'>
			<input type='submit' class='bouton' name='Deleter' value='".$msg["import_lec_effacer"]."'/>
		</td>
		</tr></table>
	
	</div>
	</div>
		<input type='hidden'  name='from_ldap' value='$from_ldap'/>
	</form>
	";

}

function show_import($dbh, $buffer,$from_ldap) {
	// formulaire o? l'on choisi dans quel champ on met les donn?es qui souvent
	// proviennent d'une autre base de donn?e, le code ne supporte que les fichiers
	// texte, il se peut que la page "plante" si vous envoyer des donn?es qui pourrait
	// ?tre incompatible, il faut faire en sorte qu'ils soient du m?me type sauf execptions
	// genre un vachar qui va dans le year(int), s'il n'y a pas de lettre. Le code est
	// loin d'?tre STABLE, il faut donc faire attantion ? ne pas envoyer des donn?es erron?es.
	global $msg;
	global $OrdreImportEmpr;
	global $import_lec;
	global $Sep_Champs;
	global $current_module ;
	print "<form class='form-$current_module' name='form2' method='post' action=\"./admin.php?categ=empr&sub=implec&action=enregistre\">";
	print "<h3>".$msg["select_col"]."&nbsp;&nbsp;&nbsp;<a href='#' onclick='aide_regex(); return false'>[".$msg[1900]."</a>]</h3><div class='form-contenu'><table width='98%' border='0' cellspacing='10'>";
//	print "  <tr></td>";
//	print "<table><tr>";
	print "        <td class='jauge'><b>".$msg["champ_dans_base_donnee"]."</b></td>";
	print "        <td class='jauge' width='27%'><center><b>".$msg["champ_dans_texte"]."</b></center></td>";
	print "        <td class='jauge' width='60%'><b>".$msg["first_line_file"]."</b></td>";
//	print "  </tr>";

	// pourrait utiliser la fonction desc_table, ? faire plus tard! parce que l? est d?ja fonctionnel
	$querry = "DESCRIBE empr;";
	$res = mysql_query($querry, $dbh);
	$nbr = mysql_num_rows($res);

	//printr($OrdreImportEmpr,'','ORDREIMPORT');
	
	if($nbr) {
		$k = 0;
		for($j=0;$j<$nbr;$j++) {
			
			$row=mysql_fetch_row($res);

			if (empty($_POST[$row[0]]))
				$ordre[$k] = $OrdreImportEmpr[$row[0]];
			else
				$ordre[$k] = $_POST[$row[0]];

			$val_buff = $buffer[ $ordre[$k] ];

			print "<tr>";
			if ($row[0] == "empr_adr1") {
				print "<td class='nobrd'>$row[0]</td>";
				print "<td class='nobrd'><center><input name='".$row[0]."' value='".$ordre[$k]."' type='text' size='1'>";
				$k++;
				if (empty($_POST[plus1]))
					$ordre[$k] = $OrdreImportEmpr[$row[0]];
				else
					$ordre[$k] = $_POST[plus1];
					
//					print "&nbsp;&nbsp;&nbsp;&nbsp;";
//					print "<input name='plus1' value='".$ordre[$k]."' type='text' size='1'></center></td>";
//					$val_buff2 = $buffer[ $ordre[$k] ];
//					print "<td class='nobrd'><input name='exem$k' value='$val_buff, $val_buff2' type='text' disabled size='40'></td>";

				print "<td class='nobrd'><input name='exem$k' value='$val_buff' type='text' disabled size='40'></td>";
			}
			// pas r?ussi a le faire fonctionn? comme du monde!! pas le temp

			/*elseif ($row[0] == "empr_date_adhesion")
			{
				print "<td><input name='".$row[0]."' value='".$ordre[$k]."' type='text' size='1'></td>";
				print "<input type='hidden' name='exem$k' value='".preg_replace('/-/', '', $empr->empr_date_adhesion)."'>";
				print "<td><input size='10' class='petit' name='exem$k' readonly value='".formatdate($empr->empr_date_adhesion)."' onClick=\"window.open('./select.php?what=calendrier&caller=empr_form&date_caller=".preg_replace('/-/', '', $empr->empr_date_adhesion)."&param1=form_expiration&param2=form_expiration_lib&auto_submit=NO&date_anterieure=YES', 'date_retour', 'toolbar=no, dependent=yes, width=200, height=200')\"></td>";
			}*/
			elseif ($row[0] == "id_empr" || $row[0] == "empr_categ" || $row[0] == "empr_codestat" || $row[0] == "empr_creation" || $row[0] == "empr_modif" || $row[0] == "empr_date_adhesion" || $row[0] == "empr_date_expiration" ||$row[0] == "empr_ldap") {
					print "<td class='nobrd'><font color='#FF0000'>$row[0]</font></td>";
					print "<td class='nobrd'><center><input name='".$row[0]."' value='".$ordre[$k]."' type='text' size='1' disabled></center></td>";
					print "<td class='nobrd'><input name='exem$k' value='$val_buff' type='text' disabled size='40'></td>";
				} else {
					print "<td class='nobrd'>$row[0]</td>";
					print "<td class='nobrd'><center><input name='".$row[0]."' value='".$ordre[$k]."' type='text' size='1'></center></td>";
					print "<td class='nobrd'><input name='exem$k' value='$val_buff' type='text' disabled size='40'></td>";
				}
			print "</tr>";
			$k++;
		}
	}
	// recup des champs perso
	$querry = "SELECT * from empr_custom ";
	$res = mysql_query($querry, $dbh);
	if (mysql_num_rows($res)) {
		print "<tr><td colspan='3' class='nobrd'><hr /></td></tr>";
		print "<tr><td colspan='3' class='nobrd'><b>".htmlentities($msg['1131'], ENT_QUOTES, $charset)."</b></td></tr>";
		while (($row=mysql_fetch_array($res,MYSQL_ASSOC))) {
			print "<tr>";
			
			if (empty($_POST[$row['name']]))
				$ordre[$k] = $OrdreImportEmpr[$row['name']];
			else
				$ordre[$k] = $_POST[$row['name']];

			$val_buff = $buffer[ $ordre[$k] ];
			print "<td class='nobrd'>".$row['name']."</td>";
			print "<td class='nobrd'><center><input name='".$row['name']."' value='".$ordre[$k]."' type='text' size='1'></center></td>";
			print "<td class='nobrd'><input name='exem$k' value='$val_buff' type='text' disabled size='40'></td>";
		
			print "</tr>";
			$k++;
		}
	}


	print "  </table>";
	/* if($import_lec[name])
		print "  <input name='import_lec' value='$import_lec[name]' type='hidden'>";
	else   */
	print "
		<input name='import_lec' value='$import_lec' type='hidden'>
		<input name='Sep_Champs' value='$Sep_Champs' type='hidden'>
		<input name='from_ldap' value='$from_ldap' type='hidden'>
		<input name='selectGroupe' value='$_POST[selectGroupe]' type='hidden''>
		<input name='selectStat' value='$_POST[selectStat]' type='hidden''>
		</div>
		<div class='row'>
				<input name='Actualiser' value='".$msg["actualiser_page"]."' type='submit' class='bouton'>
				<input name='Enregistrer' value='".$msg["enregistrer_tout"]."' type='submit' class='bouton'>
		</div></form>";
}

function choix_supp_empr($dbh,$from_ldap)
{
global $msg;
	$querry = "Select empr_date_expiration From empr order by empr_date_expiration;";
	$result = mysql_query($querry, $dbh) or die($msg["select_echoue"]."!<p>".$querry);
	if (mysql_num_rows($result) >= 1) {
		// choisir la date ? deleter d'apr?s les dates d'exiprations
		print "<form class='form-$current_module' name='form3' method='post' action=\"./admin.php?categ=empr&sub=implec&action=ConfirmationDel\">";
		print "<h3>".$msg["date_enlever"]."</h3>";
		$compteur = 0;
		$trouve = 0;
		print "<table border='0'>";
		while(($row = mysql_fetch_row($result))) {
			$compteur++;
			$empr_date_expiration_tmp = $row[0];
			if( $empr_date_expiration != $empr_date_expiration_tmp) {
				$empr_date_expiration = $row[0];
				print "<tr>";
				print "<td>".$msg["ut_date_exp"].$row[0]."</td>";
				print "<td><input type='checkbox' name='datedel".$compteur."' value='$row[0]'></td>";
				print "</tr>";
			}
		}
		print "</table>";
		print "
		<div cass='row'>
				<table class='table-but'><tr>
				<td class='td-rbut'>
					<input type='submit' class='bouton' name='Confirmation' value='".$msg["ut_deleter"]."'></center></td></table>
				</td></tr></table>
		</div>
		<input name='from_ldap' value='$from_ldap' type='hidden'>
		</form>";
	} else
		print ($msg["no_empr_del"]);
}

function desc_table($dbh, $table) {

	$querry = "Select * from $table";
	$res = mysql_query($querry, $dbh);
	$nbr = mysql_num_fields($res);

	if($nbr) {
		for($j=0;$j<$nbr;$j++) {
				$desc_table[$j][0] = mysql_field_name($res,$j);
				$desc_table[$j][1] = mysql_field_type($res,$j);
				$desc_table[$j][2] = mysql_field_len($res,$j);
				$desc_table[$j][3] = estNumerique($desc_table[$j][1], $desc_table[$j][2]);
				// j'ai seulement besoin de l'auto_increment, $desc_table[$j][4] est vrai si trouv?!
				$desc_table[$j][4] = array_search ( "auto_increment", explode( " ", mysql_field_flags($res,$j) ) );
		}
		//print_r($desc_table);
		return $desc_table;
	}
}

function estNumerique($field_type, $field_len) {
	// ceci est le m?me code que dans la class mysql_backup
	$is_numeric=false;

	switch(strtolower($field_type)) {
		case "int":
			$is_numeric=true;
			break;
		case "blob":
			$is_numeric=false;
			break;
		case "real":
			$is_numeric=true;
			break;
		case "string":
			$is_numeric=false;
			break;
		case "unknown":
			switch(intval($field_len))	{
				case 4:
					// little weakness here...
					// there is no way (thru the PHP/MySQL interface)
					// to tell the difference between a tinyint and a year field type
					$is_numeric=true;
					break;
				default:
					$is_numeric=true;
					break;
			}
			break;
		case "timestamp":
			$is_numeric=true;
			break;
		case "date":
			$is_numeric=false;
			break;
		case "datetime":
			$is_numeric=false;
			break;
		case "time":
			$is_numeric=false;
			break;
		default:
			//future support for field types that are not recognized
			//(hopefully this will work without need for future modification)
			$is_numeric=true;
			//I'm assuming new field types will follow SQL numeric syntax..
			// this is where this support will breakdown
			break;
	}
	return $is_numeric;
}

// string de la date d'aujourd'hui ou avec quelques jours de plus
function aujourdhui($nbjour=0) {
	$date1 = date("Y-m-d", time() + 3600*24*$nbjour);
	return strval($date1);
}

// sert a savoir si un etudiant existe d?j?
function return_cb($dbh, $cb) {
	$querry = "select empr_cb from empr where empr_cb = '".$cb."' ";
	$res = mysql_query($querry, $dbh);
	$row = mysql_fetch_row($res);    // lecture d'une seul ligne, le id de l'?diteur
#    return intval($row[0]);
	return ($row[0]);  // cb can be NOT numeric (MaxMan)!!!
}

// save fields/text association - MaxMan
function save_fields_association() {
	global $dbh ;
	$i=1;
	reset($_POST);
	while (list($kk,$vv)=each($_POST)) {
		if (!preg_match("/$kk/i","import_lec Sep_Champs selectGroupe Actualiser")) {
				$OrdreImportEmpr[$kk]=$vv;
		}
	}
	$querry = "update parametres set valeur_param='".serialize($OrdreImportEmpr)."' where type_param = 'empr' and sstype_param='corresp_import' ";
	mysql_query($querry, $dbh);

//	$h=fopen("$base_path/admin/empr/ordimport.txt",'w');
//	fwrite($h,serialize($OrdreImportEmpr));
//	fclose($h);
}


switch($action) {
	case 'FichierOK':
		if($envoyer) {
			$from_ldap=$_POST['from_ldap'];
			//print_r($import_lec)."<p>";
			if (!$from_ldap) {
				if (!($_FILES['import_lec']['tmp_name'])) {
					print $msg["click_prec_fic"];
				} elseif (!(move_uploaded_file($_FILES['import_lec']['tmp_name'], "./temp/".basename($_FILES['import_lec']['tmp_name'])))) {
					print $msg["fic_no_tel"]."<br />";					
				}
				$ficher = @fopen( "./temp/".basename($_FILES['import_lec']['tmp_name']), "r" );
				$import_lec = basename($_FILES['import_lec']['tmp_name']);
			} else {
				$ficher = @fopen( "./temp/ldap_users.txt", "r" );
				$import_lec = "./temp/ldap_users.txt";
				$Sep_Champs='|';
			}
			if($ficher)	{
				$buffer = fgets($ficher, 1000);
				$buffer = explode ($Sep_Champs, $buffer);
				$cpt = count($buffer) - 1;
				// de 1 jusqu'? la fin, le 0 est comme null!
				for($j=$cpt; $j>=0; $j--)
					$buffer[$j+1] = trim($buffer[$j]);
				$buffer[0] = "";
				fclose($ficher);
				show_import($dbh, $buffer, $from_ldap);
			}
		}
		elseif($Deleter || $Precedent)
				choix_supp_empr($dbh,$from_ldap);
		else
		{
				//print "Vous devez choisir un fichier!";
				show_import_choix_fichier($dbh,$from_ldap);
		}
		break;

	case 'enregistre':
		$from_ldap=$_POST['from_ldap'];
		if ($Actualiser){
			//print "Actualiser";
			//print_r ($import_lec);
			//print "test";
			//$ficher = @fopen($_POST[import_lec], 'rb');
			//print "<br />".$HTTP_POST_FILES['import_lec']."test";
			//$ficher = @fopen($import_lec, 'rb');

			save_fields_association();
			if ($from_ldap){
				$ficher = @fopen( "./temp/ldap_users.txt", "r" );
				$Sep_Champs='|';
			} else {
				$ficher = @fopen( "./temp/".$import_lec, "r" );
			}
			if($ficher)	{
				$buffer = fgets($ficher, 1000);
				$buffer = explode ($Sep_Champs, $buffer);
				$cpt = count($buffer) - 1;
				// de 1 jusqu'? la fin, le 0 est comme null!
				for($j=$cpt; $j>=0; $j--)
					$buffer[$j+1] = trim($buffer[$j]);
				$buffer[0] = "";
				fclose($ficher);
				show_import($dbh, $buffer,$from_ldap);
			}
		}
		if ($Enregistrer) {
			save_fields_association();
			// download le fichier au complet
			//print "Enregistrer<p>";
			//print "<br />".$import_lec;
			//$ficher = @fopen($import_lec, 'rb');
			//$ficher = @fopen($import_lec, 'rb');
			if ($from_ldap) {
				$file_lec = "./temp/ldap_users.txt";
				$Sep_Champs='|';
			} else {
				$file_lec = "./temp/$import_lec";
			}
			$ficher = fopen( $file_lec, "r" );
			if($ficher)	{
	
				//champs perso
				$perso=array();
				$querry = "SELECT * from empr_custom;";
				$res = mysql_query($querry, $dbh);
				$k=0;
				if (mysql_num_rows($res)) {
					while ($row=mysql_fetch_array($res,MYSQL_ASSOC)) {
						$perso[$k]=$row;
					}
				}
				//printr($perso,'','PERSO');die;
				
				$result = mysql_query("Select duree_adhesion From empr_categ where id_categ_empr='$_POST[selectGroupe]';", $dbh) or die($msg["err_sql"]);
				$row = mysql_fetch_row($result);
				$dur=htmlentities($row[0],ENT_QUOTES, $charset);
				// mise en tampon du ficher
				$buffer = fread ( $ficher, filesize ($file_lec));
				if (preg_match('/\r\n/',$buffer)) {
					//txt msdos
					$bufferLine = explode("\r\n", $buffer);
				} else {
					//txt linux
					$bufferLine = explode("\n", $buffer);
				}

				// on enl?ve les [enter] de trop en fin de fichiers
				// ? faire si le temp, enlever les enter(/r/n) en millieu du fichier
				// s'il y en a, le prog va ins?rer des donn?es vide et les affichers
				// comme erreur
				// si le fichier est vide sa va cr?er une boucle sans fin!!!
				while(end($bufferLine) == "")
					array_pop($bufferLine);

				// check la table empr
				$desc_empr = desc_table($dbh, "empr");
				/*
				print $desc_empr[0][0].", ";        //        mysql_field_nom
				print $desc_empr[0][1].", ";        //        mysql_field_type
				print $desc_empr[0][2].", ";        //        mysql_field_longeur
				print $desc_empr[0][3];", ";        //        estNumerique ou non!
				print $desc_table[$j][4];           //        auto_increment ou non!
				*/
				$nbChamp_empr = count($desc_empr);
				//
				//  traitement du buffer pour chaque ligne
				//
				foreach($bufferLine as $dummykey=>$tmp){
					$bufferChamp = explode ($Sep_Champs, $tmp);
					$cpt = count($bufferChamp) - 1;
					// de 1 ? la fin, le 0 est comme null!
					for($j=$cpt; $j>=0; $j--) {
						// el?ve les " et les espaces en debut et fin du string(pour chaque champ!)
						$bufferChamp[$j+1] = trim($bufferChamp[$j], "\"");
						$bufferChamp[$j+1] = mysql_escape_string ( $bufferChamp[$j+1] );
					}
					$bufferChamp[0] = "";

					if ( return_cb($dbh, $bufferChamp[$$desc_empr[1][0]]) )	{
						// ca veut dire que c'est un update d'une personne deja dans la BD
						for($i = 1; $i < $nbChamp_empr; $i++){
							if(!$desc_empr[$i][4] and $bufferChamp[$$desc_empr[$i][0]]){ // s'il n'est pas auto incr?mentable								
								if($desc_empr[$i][3])  // s'il est num?rique
									$query2 = 'update empr set '.$desc_empr[$i][0].' = '.substr ( $bufferChamp[$$desc_empr[$i][0]], 0, $desc_empr[$i][2]).' where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
								else
									$query2 = 'update empr set '.$desc_empr[$i][0].' = "'.substr ( $bufferChamp[$$desc_empr[$i][0]], 0, $desc_empr[$i][2]).'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
								if($i == 4) // l'exeption pour l'adresse (2 champs dans le 2e formulaire)
#									$query2 = 'update empr set '.$desc_empr[$i][0].' = "'.substr ( $bufferChamp[$$desc_empr[$i][0]].", ".$bufferChamp[$plus1], 0, $desc_empr[$i][2]).'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
#									MaxMan: pas d'exception pour l'addresse									
									$query2 = 'update empr set '.$desc_empr[$i][0].' = "'.substr ( $bufferChamp[$$desc_empr[$i][0]], 0, $desc_empr[$i][2]).'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
								$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<br />";
							}
						}
						// update pour inserer la date de creation , modif, date_adhesion...
						$query2 = 'update empr set empr_modif  = "'.aujourdhui(1).'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						$query2 = 'update empr set empr_categ = '.$_POST[selectGroupe].' where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						$query2 = 'update empr set empr_codestat =  '.$_POST[selectStat].' where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						$query2 = 'update empr set empr_date_expiration = "'.aujourdhui($dur).'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query."<hr />";
						if ($from_ldap){
							$query2 = "update empr set empr_ldap = '1' where empr_cb = '".$bufferChamp[$$desc_empr[1][0]]."';";
							$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						}								


					} else {
						// sinon le cb n'existe pas, donc c'est une nouvelle personne
						$fields = "";
						$values = "";
						for($i = 1; $i < $nbChamp_empr; $i++) {
							if(!$desc_empr[$i][4] and $bufferChamp[$$desc_empr[$i][0]]) { // s'il n'est pas auto incrementable et que le $bufferChamp ne soit pas vide
								// remplit le $fields dans : insert into empr($fields)...
								if($fields) $fields .= ', '.$desc_empr[$i][0];
									else $fields .= $desc_empr[$i][0];

								// WOW, ca c'est du code comme je l'aime!!!
								// remplit le $values dans : insert into empr($fields) values=($values);
								// et tronque s'il est trop long!! (import se fait pas avec les int
								// trop long, varchar et autres pas test?
								if($desc_empr[$i][3]){  // s'il est num?rique
									if($values) $values .= ', '.substr ( $bufferChamp[$$desc_empr[$i][0]], 0, $desc_empr[$i][2]);
										else $values .= substr ( $bufferChamp[$$desc_empr[$i][0]], 0, $desc_empr[$i][2]);
								}else{
									if($values) $values .= ', "'.substr ( $bufferChamp[$$desc_empr[$i][0]], 0, $desc_empr[$i][2]).'"';
										else $values .= '"'.substr ( $bufferChamp[$$desc_empr[$i][0]], 0, $desc_empr[$i][2]).'"';
								}
								if($i == 4) { // l'exception pour l'adresse (2 champs dans le 2e formulaire)
									if($values) {
										$values = substr($values, 0, strlen($values) - 1);
										$values .= ', '.substr ( $bufferChamp[$plus1], 0, $desc_empr[$i][2]).'"';
									} else 
										$values .= '"'.substr ( $bufferChamp[$plus1], 0, $desc_empr[$i][2]).'"';
								}
							}
						}
						$query = "insert into empr ($fields) values ($values);";
						
						//print $query;
						$res = mysql_query($query, $dbh) or print $msg["ins_echoue"]."<p>".$query."<hr />";
						// update pour ins?rer la date de cr?ation , modif, date_adhesion...
						$query2 = 'update empr set empr_creation = "'.aujourdhui(1).'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						$query2 = 'update empr set empr_modif  = "'.aujourdhui(1).'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						$query2 = 'update empr set empr_date_adhesion = "'.aujourdhui(1).'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						$query2 = 'update empr set empr_date_expiration = "'.aujourdhui($dur).'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						$query2 = 'update empr set empr_categ = '.$_POST[selectGroupe].' where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						$query2 = 'update empr set empr_codestat = '.$_POST[selectStat].' where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						if ($from_ldap){
							$query2 = "update empr set empr_ldap = '1' where empr_cb = '".$bufferChamp[$$desc_empr[1][0]]."';";
							$res = mysql_query($query2, $dbh) or print $msg["upd_echoue"]."<p>".$query2."<hr />";
						}
					}
					// Gestion de la localisation des lecteurs; prend la loc par défaut du user si non défini
					if ($pmb_lecteurs_localises) {
						if (!$empr_location) $empr_location = $deflt2docs_location ;
						$req_location = 'update empr set empr_location = "'.$empr_location.'" where empr_cb = "'.$bufferChamp[$$desc_empr[1][0]].'";';
						$res = mysql_query($req_location, $dbh) or print $msg["upd_echoue"]."<p>".$req_location."<hr />";							
					}
										
					//at this point the empr is OK (inserted or updated)
					//on passe aux champs perso
					//recup id_empr
					//printr($bufferChamp,'','TXTRECORD');
					$querry ="SELECT id_empr FROM empr WHERE empr_cb = '".$bufferChamp[$$desc_empr[1][0]]."';";
					$res = mysql_query($querry, $dbh);
					if (mysql_num_rows($res)==1) { //deve esistere un solo lettore! 
						$row=mysql_fetch_array($res,MYSQL_ASSOC);
						$empr_id=$row['id_empr'];
						reset($perso);
						foreach ($perso as $dummykey=>$cp) {
							$querry ="SELECT * FROM empr_custom_values WHERE empr_custom_champ = ".$cp['idchamp']." AND empr_custom_origine=".$empr_id.";";
							$res = mysql_query($querry, $dbh);
							if (mysql_num_rows($res)) { // le champ existe: update
								$field="empr_custom_".$cp['datatype'];
								$value="'".$bufferChamp[$_POST[$cp['name']]]."'" ;
								$querry = "UPDATE empr_custom_values SET $field = $value WHERE empr_custom_champ = ".$cp['idchamp']." AND empr_custom_origine=".$empr_id.";";
								//print "$querry<br />";
								$res = mysql_query($querry, $dbh) or print $msg["upd_echoue"]."<p>".$querry."<hr />";
									
							} else { //le champ n'existe pas: insert
								$fields="empr_custom_champ,empr_custom_origine,empr_custom_".$cp['datatype'];
								$values=$cp['idchamp'].",".$empr_id.",'".$bufferChamp[$_POST[$cp['name']]]."'" ;
								$querry = "INSERT INTO empr_custom_values ($fields) VALUES ($values);";
								//print "$querry<br />";
								$res = mysql_query($querry, $dbh) or print $msg["ins_echoue"]."<p>".$querry."<hr />";
							}
						}
					} else 
						$msg["upd_echoue"]."<p>".$querry."<hr />";
								
				}
				fclose($ficher);
				print $msg["personnes_upd"].count($bufferLine)."<p>";
				print "<a href='./admin.php?categ=empr&sub=implec' title='Retour'><img name='gg.gif' src='./images/gg.gif' width='38' height='26'></a>";
			} else
					die($msg["choix_fic"]);
		}
		break;

	case 'ConfirmationDel':
		print "<form class='form-$current_module' name='form4' method='post' action=\"./admin.php?categ=empr&sub=implec&action=delall\">";
		foreach ($HTTP_POST_VARS as $cle => $val) {
				if (substr($cle, 0, 7) == "datedel") {
					print " <input name='$cle' value='$val' type='hidden'>";
					// la confirmation delete nous montre les 5 premi?res personne pour chaque dates choisis
					print $val;
					$desc_empr = desc_table($dbh, "empr");
					print "<table border='2'>";
					print " <tr>";
					for($i=0;$i<4;$i++) {
						print " <th><strong>".$desc_empr[$i][0]."</strong></th>";
					}
					print " </tr>";

					$querry = "Select id_empr,empr_cb,empr_nom,empr_prenom From empr where empr_date_expiration = '$val';";
					$res = mysql_query($querry, $dbh) or die($msg["select_echoue"]."<p>".$querry);
					$nbr1 = mysql_num_fields($res);
					$nbr2 = mysql_num_rows($res);
					if($nbr2 > 5) $nbr2 = 5;        // max de 5 r?ponces par date
					for($j = 0;$j < $nbr2; $j++) {
						$row = mysql_fetch_row($res);
						print " <tr>";
						for ($i = 0; $i < $nbr1; $i++)
								print " <td>$row[$i]</td>";
						print " </tr>";
					}
					print "</table>";

				}
		}
		// Le Precedent=1 c'est pour eviter des erreurs en apuyant sur precedent, cause: <form class='form-$current_module' name='form1' ENCTYPE=\"multipart/form-data\"
					//print "<input name='Retour' value='Retour' type='hidden'";
		print "
		<div class='row'>
				<table class='table-but'><tr>
				<td class='td-lbut'>
					<a href='./admin.php?categ=empr&sub=implec&action=FichierOK&Precedent=1' title='".$msg[654]."'>
					<img name='gg.gif' src='./images/gg.gif' width='38' height='26'/>
					</a>
				</td>
				<td class='td-rbut'>
					<input type='submit' class='bouton' name='Effacer' value='".$msg["del_tout"]."'/>
				</td>
				</tr></table>
		</div>
		</form>";
		break;

	case 'delall':
		foreach ($HTTP_POST_VARS as $cle => $val) {
			$cmpt = 0;
			//print $cle.", ". $val;
			if (substr($cle, 0, 7) == "datedel") {
				$querry = "select id_empr,empr_cb,empr_nom,empr_prenom From empr where empr_date_expiration = '$val';";
				$res = mysql_query($querry, $dbh) or die($msg["sqlselect_errdel1"]."<br />".$msg["sqlselect_errdel2"].$querry);
				while($row = mysql_fetch_row($res)) {
					$querry1 = "select * From pret where pret_idempr = ".$row[0].";";
					$result3 = mysql_query($querry1, $dbh);
					$row2 = mysql_fetch_row($result3);
					//print $row2[0]."test";
					if ($row2[0] == "") {
						emprunteur::del_empr($row[0]);
					} else {
						$cmpt++;
						//print "compteur = ".$cmpt."<br />";
						if($cmpt == 1) {
							$desc_empr = desc_table($dbh, "empr");
							print $val."<br />";
							print '<font color="#FF0000" face="Geneva, Arial, Helvetica, sans-serif"><strong>'.$msg["personnes_nodel"]."<p>";
							print '</strong></font>';
							print "<table border='2'>";
							print " <tr>";
							foreach($desc_empr as $dummykey=>$empr)
								print " <td>".$empr[0]."</td>";
							print " </tr>";
						}
						$nbr1 = mysql_num_fields($res);
						print " <tr>";
						for ($i = 0; $i < $nbr1; $i++)
							print " <td>$row[$i]</td>";
						print " </tr>";
					}

				}
				// a cause des prets qui peuvent etre en cours (et qui ne sont pas deletes)
				// mysql_num_rows($res) - $cmpt($cmpt: sont ceux qui n'ont pas ete delete)
				print "</table>";
				print $msg["personnes_delete"].(mysql_num_rows($res) - $cmpt)."<p>";
			}
		}
		print "<a href='./admin.php?categ=empr&sub=implec' title='".$msg[654]."'><img name='gg.gif' src='./images/gg.gif' width='38' height='26'></a>";

		break;

	default:
		show_import_choix_fichier($dbh,$from_ldap);
		break;
}
