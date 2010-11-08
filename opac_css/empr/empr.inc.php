<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr.inc.php,v 1.23 2010-08-19 07:30:42 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// éléments pour la fiche lecteur

$empr_header = "
<div id='categories'>
<h3>$msg[empr_tpl_emprheader]</h3>	
";

$empr_footer ="
</div>";

$message_null_resa=$msg["empr_resa_empty"];
if ($opac_resa) {
	$message_null_resa .= "<br /><small><br />".$msg["empr_resa_how_to"]." <br />
	<form style='margin-bottom:0px;padding-bottom:0px;' action='empr.php' method='post' name='FormName'>
	<INPUT type='button' class='bouton' 'name='lvlx' value='".$msg["empr_make_resa"]."' onClick=\"document.location='./index.php'\">
	</form>
	</small>";
	if (!$opac_resa_dispo) $message_null_resa .= "<br /><small>".$msg["empr_resa_only_loaned_book"]."</small>";
}

// recherche des valeurs dans la table empr suivant id_empr
$query = "SELECT *, date_format(empr_date_adhesion, '".$msg["format_date_sql"]."') as aff_empr_date_adhesion, date_format(empr_date_expiration, '".$msg["format_date_sql"]."') as aff_empr_date_expiration FROM empr WHERE empr_login='$login'";
$result = mysql_query($query) or die("Query failed ".$query);

// récupération des valeurs MySQL du lecteur et injection dans les variables
while (($line = mysql_fetch_array($result, MYSQL_ASSOC))) {
	$id_empr=$line["id_empr"];
	$empr_cb = $line["empr_cb"];
	$empr_nom = $line["empr_nom"];
	$empr_prenom = $line["empr_prenom"];
	$empr_adr1 = $line["empr_adr1"];
	$empr_adr2 = $line["empr_adr2"];
	$empr_cp = $line["empr_cp"];
	$empr_ville = $line["empr_ville"];
	$empr_mail = $line["empr_mail"];
	$empr_tel1 = $line["empr_tel1"];
	$empr_tel2 = $line["empr_tel2"];
	$empr_prof = $line["empr_prof"];
	$empr_year = $line["empr_year"];
	$empr_categ = $line["empr_categ"];
	$empr_codestat = $line["empr_codestat"];
	$empr_sexe = $line["empr_sexe"];
	$empr_login = $line["empr_login"];
	$empr_password = $line["empr_password"];
	$empr_date_adhesion = $line["empr_date_adhesion"];
	$empr_date_expiration = $line["empr_date_expiration"];
	$aff_empr_date_adhesion = $line["aff_empr_date_adhesion"];
	$aff_empr_date_expiration = $line["aff_empr_date_expiration"];
}
	
$empr_identite = "
<div id='fiche-empr'><h3><span>$empr_prenom $empr_nom</span></h3>
	<div id='fiche-empr-container'>
		<table class='fiche-lecteur'>";

$i=0;
$tab_empr_info=array();
$tab_empr_info[$i]["titre"]=$msg["empr_tpl_cb"];
$tab_empr_info[$i++]["val"]=$empr_cb;

if($empr_adr1 || $empr_adr2 || $empr_cp || $empr_ville) {
	if($empr_adr1 && $empr_adr2) 	$empr_adr = $empr_adr1.$msg["empr_adr_separateur"].$empr_adr2;
	else $empr_adr = $empr_adr1.$empr_adr2;
	
	if($empr_adr &&($empr_cp || $empr_ville)) $empr_adr.=$msg["empr_ville_separateur"];
	$empr_adr.="$empr_cp <u>$empr_ville</u>";
	
	$tab_empr_info[$i]["titre"]=$msg["empr_adresse"];
	$tab_empr_info[$i++]["val"]=$empr_adr;
}	
if($empr_tel1 || $empr_tel2){
	if($empr_tel1 && $empr_tel2) $tel=$empr_tel1.$msg["empr_tel2_separateur"].$empr_tel2;
	else $tel.=$empr_tel1.$empr_tel2;
	$tab_empr_info[$i]["titre"]=$msg["empr_tel_titre"];
	$tab_empr_info[$i++]["val"]=$tel;	
}
if($empr_mail){
	$tab_empr_info[$i]["titre"]=$msg["empr_mail"];
	$tab_empr_info[$i++]["val"]="<a href='mailto:$empr_mail'>$empr_mail</a>";	
}
if ($empr_prof){
	$tab_empr_info[$i]["titre"]=$msg["empr_tpl_prof"];
	$tab_empr_info[$i++]["val"]=$empr_prof;	
}
if ($empr_year){
	$tab_empr_info[$i]["titre"]=$msg["empr_tpl_year"];
	$tab_empr_info[$i++]["val"]=$empr_year;
}

//Paramètres perso
require_once("$class_path/parametres_perso.class.php");
$p_perso=new parametres_perso("empr");
$perso_=$p_perso->show_fields($id_empr);
if (count($perso_["FIELDS"])) {
	for ($ipp=0; $ipp<count($perso_["FIELDS"]); $ipp++) {
		$p=$perso_["FIELDS"][$ipp];
		if($p[OPAC_SHOW]==1){				
			$tab_empr_info[$i]["titre"]=$p["TITRE_CLEAN"];
			$tab_empr_info[$i++]["val"]=$p["AFF"];						
		}		
	}
}

$adhesion=str_replace("!!date_adhesion!!","<strong>".$aff_empr_date_adhesion."</strong>",$msg["empr_format_adhesion"]);	
$adhesion=str_replace("!!date_expiration!!","<strong>".$aff_empr_date_expiration."</strong>",$adhesion);	
$tab_empr_info[$i]["titre"]=$msg["empr_tpl_adh"];
$tab_empr_info[$i++]["val"]=$adhesion;				

foreach ($tab_empr_info as $ligne){
	$empr_identite.=
	"<tr>
		<td class='bg-grey' align='right'><span class='etiq_champ'>".$ligne["titre"]."</span></td>	
		<td>".$ligne["val"]."</td>
	</tr>";
}

$empr_identite .= "
		</table>
	<br />
	</div>
</div>
";

