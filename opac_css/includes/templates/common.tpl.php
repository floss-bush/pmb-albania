<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: common.tpl.php,v 1.126 2010-11-26 10:15:49 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// template for PMB OPAC

// éléments standards pour les pages :
// $short_header
// $std_header
//
//$footer qui contient
//	$liens_bas : barre de liens bibli, google, pmb
//	contenu du div bandeau (bandeau de gauche) soit
//		$home
//		$loginform
//		$meteo
//		$adresse
//
//Classes et IDs utilisés dans l'OPAC
//
//Tout est contenu dans #container
//
//Partie gauche (menu)
//	#bandeau
//		#accueil
//		#connexion
//		#meteo
//		#addresse
//		
//Partie droite (principale)
//	#intro (tout le bloc incluant pmb, nom de la bibli, message d'accueil)
//		#intro_pmb : pmb
//		#intro_message : message d'information s'il existe
//		#intro_bibli
//			h3 : nom de la bibli
//			p .intro_bibli_presentation_1 : texte de présentation de la bibli
//	
//	#main : contient les différents blocs d'affichage et de recherches (browsers)
//		div
//			h3 : nom du bloc
//			contenu du bloc
					
//	récupère les feuilles de styles du répertoire /styles/$css/
function link_styles($style) {
	// où $rep = répertoire de stockage des feuilles
	// retourne un tableau indexé avec les noms des CSS disponibles
	
	global $charset;
	
	$rep = './styles/';
	
	if(!preg_match('/\/$/', $rep)) $rep .= '/';
	
	$handle = @opendir($rep.$style);
	
	if(!$handle) {
		$result = array();
		return $result;
	}
	$feuilles_style="";
	while($css = readdir($handle)) {
		if(is_file($rep.$style."/".$css) && preg_match('/css$/', $css)) {
			$result[] = $css;
			$feuilles_style.="\n\t<link rel='stylesheet' type='text/css' href='".$rep.$style."/".$css."' />";
	    }
	}
	
	closedir($handle);
	return $feuilles_style;
}

//Récupération du login
if (!$_SESSION["user_code"]) {
	//Si pas de session
	$cb_=$msg[common_tpl_cardnumber_default];
} else {
	//Récupération des infos de connection
	$cb_=$_SESSION["user_code"];
}
$stylescsscodehtml=link_styles($css);
//HEADER : short_header = pour les popups
//         std_header = pour les pages standards

// pb de resize de page avec IE6 et 7 : on force le rechargement de la page (position absolue qui reste absolue !)
if ($opac_ie_reload_on_resize) $iecssresizepb="onresize=\"history.go(0);\"";

$std_header.="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" charset='".$charset."'>
<head>
	<meta http-equiv=\"content-type\" content=\"text/html; charset=$charset\" />
	<meta name=\"author\" content=\"PMB Group\" />

	<meta name=\"keywords\" content=\"OPAC, web, library, opensource, catalog, catalogue, bibliothèque, médiathèque, pmb, phpmybibli\" />
	<meta name=\"description\" content=\"".$msg['opac_title']." $opac_biblio_name.\" />

	<meta name=\"robots\" content=\"all\" />

	<title>".$msg['opac_title']." $opac_biblio_name.</title>
	!!liens_rss!!
	".$stylescsscodehtml."
	<link rel=\"SHORTCUT ICON\" href=\"images/site/favicon.ico\">
	<script type=\"text/javascript\" src=\"includes/javascript/drag_n_drop.js\"></script>
	<script type=\"text/javascript\" src=\"includes/javascript/handle_drop.js\"></script>
	<script type=\"text/javascript\" src=\"includes/javascript/popup.js\"></script>
</head>

<body onload=\"window.defaultStatus='".$msg["page_status"]."';\" $iecssresizepb id=\"pmbopac\">
<script type='text/javascript'>
function show_what(quoi, id) {
	var whichISBD = document.getElementById('div_isbd' + id);
	var whichPUBLIC = document.getElementById('div_public' + id);
	var whichongletISBD = document.getElementById('onglet_isbd' + id);
	var whichongletPUBLIC = document.getElementById('onglet_public' + id);
	
	var whichEXPL = document.getElementById('div_expl' + id);	
	var whichEXPL_LOC = document.getElementById('div_expl_loc' + id);	
	var whichongletEXPL = document.getElementById('onglet_expl' + id);
	var whichongletEXPL_LOC = document.getElementById('onglet_expl_loc' + id);
	if (quoi == 'ISBD') {
		whichISBD.style.display  = 'block';
		whichPUBLIC.style.display = 'none';
		whichongletPUBLIC.className = 'isbd_public_inactive';
		whichongletISBD.className = 'isbd_public_active';
	}else if(quoi == 'EXPL_LOC') {
		whichEXPL_LOC.style.display = 'block';
		whichEXPL.style.display = 'none';		
		whichongletEXPL.className = 'isbd_public_inactive';		
  		whichongletEXPL_LOC.className = 'isbd_public_active';
	}else if(quoi == 'EXPL') {
		whichEXPL_LOC.style.display = 'none';
		whichEXPL.style.display = 'block';
  		whichongletEXPL.className = 'isbd_public_active';
		whichongletEXPL_LOC.className = 'isbd_public_inactive';
	} else {
		whichISBD.style.display = 'none';
		whichPUBLIC.style.display = 'block';
  		whichongletPUBLIC.className = 'isbd_public_active';
		whichongletISBD.className = 'isbd_public_inactive';
	}
	
}
</script>

<script type='text/javascript' src='./includes/javascript/tablist.js'></script>
	<div id='att' style='z-Index:1000'></div>
	<div id=\"container\"><div id=\"main\"><div id='main_header'>!!main_header!!</div><div id=\"main_hors_footer\">!!home_on_top!!
						\n";

$inclus_header = "
!!liens_rss!!
".$stylescsscodehtml."	
<script type='text/javascript'>
function show_what(quoi, id) {
	var whichISBD = document.getElementById('div_isbd' + id);
	var whichPUBLIC = document.getElementById('div_public' + id);
	var whichongletISBD = document.getElementById('onglet_isbd' + id);
	var whichongletPUBLIC = document.getElementById('onglet_public' + id);
	if (quoi == 'ISBD') {
		whichISBD.style.display  = 'block';
		whichPUBLIC.style.display = 'none';
		whichongletPUBLIC.className = 'isbd_public_inactive';
		whichongletISBD.className = 'isbd_public_active';
		} else {
			whichISBD.style.display = 'none';
			whichPUBLIC.style.display = 'block';
  			whichongletPUBLIC.className = 'isbd_public_active';
			whichongletISBD.className = 'isbd_public_inactive';
			}
  	}
</script>
<script type='text/javascript' src='./includes/javascript/tablist.js'></script>
<script type='text/javascript' src='includes/javascript/drag_n_drop.js'></script>
<script type='text/javascript' src='includes/javascript/handle_drop.js'></script>
<script type='text/javascript' src='includes/javascript/popup.js'></script>
$inclure_recherche
		
<div id='att' style='z-Index:1000'></div>
	<div id=\"container\"><div id=\"main\"><div id='main_header'>!!main_header!!</div><div id=\"main_hors_footer\">!!home_on_top!!
						\n";
$short_header="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" charset='".$charset."'>
<head>
<script type='text/javascript'>
function show_what(quoi, id) {
	var whichISBD = document.getElementById('div_isbd' + id);
	var whichPUBLIC = document.getElementById('div_public' + id);
	var whichongletISBD = document.getElementById('onglet_isbd' + id);
	var whichongletPUBLIC = document.getElementById('onglet_public' + id);
	
	var whichEXPL = document.getElementById('div_expl' + id);	
	var whichEXPL_LOC = document.getElementById('div_expl_loc' + id);	
	var whichongletEXPL = document.getElementById('onglet_expl' + id);
	var whichongletEXPL_LOC = document.getElementById('onglet_expl_loc' + id);
	if (quoi == 'ISBD') {
		whichISBD.style.display  = 'block';
		whichPUBLIC.style.display = 'none';
		whichongletPUBLIC.className = 'isbd_public_inactive';
		whichongletISBD.className = 'isbd_public_active';
	}else if(quoi == 'EXPL_LOC') {
		whichEXPL_LOC.style.display = 'block';
		whichEXPL.style.display = 'none';		
		whichongletEXPL.className = 'isbd_public_inactive';		
  		whichongletEXPL_LOC.className = 'isbd_public_active';
	}else if(quoi == 'EXPL') {
		whichEXPL_LOC.style.display = 'none';
		whichEXPL.style.display = 'block';
  		whichongletEXPL.className = 'isbd_public_active';
		whichongletEXPL_LOC.className = 'isbd_public_inactive';
	} else {
		whichISBD.style.display = 'none';
		whichPUBLIC.style.display = 'block';
  		whichongletPUBLIC.className = 'isbd_public_active';
		whichongletISBD.className = 'isbd_public_inactive';
	}
	
}
</script>
!!liens_rss!!
	".$stylescsscodehtml."
</head>
<body>";



$short_footer="</body></html>";

$popup_header="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" charset='".$charset."'>
<head>
	".$stylescsscodehtml."
	<title>".$msg['opac_title']." $opac_biblio_name.</title>
</head>
<body>
<script type='text/javascript'>
function show_what(quoi, id) {
	var whichISBD = document.getElementById('div_isbd' + id);
	var whichPUBLIC = document.getElementById('div_public' + id);
	var whichongletISBD = document.getElementById('onglet_isbd' + id);
	var whichongletPUBLIC = document.getElementById('onglet_public' + id);
	if (quoi == 'ISBD') {
		whichISBD.style.display  = 'block';
		whichPUBLIC.style.display = 'none';
		whichongletPUBLIC.className = 'isbd_public_inactive';
		whichongletISBD.className = 'isbd_public_active';
		} else {
			whichISBD.style.display = 'none';
			whichPUBLIC.style.display = 'block';
  			whichongletPUBLIC.className = 'isbd_public_active';
			whichongletISBD.className = 'isbd_public_inactive';
			}
  	}
</script>
<script type='text/javascript' src='./includes/javascript/tablist.js'></script>
";

$popup_footer="</body></html>";

// liens du bas de la page
$liens_bas = "</div><!-- fin DIV main_hors_footer --><div id=\"footer\"><!-- rss -->
		$opac_lien_bas_supplementaire &nbsp;";
if ($opac_biblio_website)	$liens_bas .= "<a href=\"$opac_biblio_website\" title=\"$opac_biblio_name\">$opac_biblio_name</a> &nbsp;";
$liens_bas .= "$opac_lien_moteur_recherche &nbsp;
		<a class=\"lien_pmb_footer\" href=\"http://www.sigb.net\" title=\"".$msg[common_tpl_motto]."\" target='_blank'>pmb</a> 
		</div>" ;


// HOME
$home_on_left = "<div id=\"accueil\">\n
<h3><span onclick='document.location=\"./index.php?\"' style='cursor: pointer;'>!!welcome_page!!</span></h3>\n";

if ($opac_logosmall<>"") $home_on_left .= "<p class=\"centered\"><a href='./index.php?'><img src='".$opac_logosmall."'  border='0' align='center'/></a></p>\n";
else $home_on_left .= "<p class=\"centered\"><a href='./index.php?'><img src='./images/home.jpg' border='0' align='center'/></a></p>\n";
	
// affichage du choix de langue  
$common_tpl_lang_select="<div id='lang_select'><h3 ><span>!!msg_lang_select!!</span></h3><span>!!lang_select!!</span></div>\n";

$home_on_left.="!!common_tpl_lang_select!!
					</div><!-- fermeture #accueil -->\n" ;

// HOME lorsque le bandeau gauche n'est pas affiché
$home_on_top ="<div id='home_on_top'>
	<span onclick='document.location=\"./index.php?\"' style='cursor: pointer;'><img src='./images/home.gif' align='absmiddle' /> ".$msg["welcome_page"]."</span>
	</div>
	";

// LOGIN FORM=0
// Si le login est autorisé, alors afficher le formulaire de saisie utilisateur/mot de passe ou le code de l'utilisateur connecté
if ($opac_show_loginform) {
	$loginform ="<div id=\"connexion\">\n
			<h3>!!common_tpl_login_invite!!</h3><span id='login_form'>!!login_form!!</span>\n
			</div><!-- fermeture #connexion -->\n";
	} else {
		$loginform="";
		$_SESSION["user_code"]="";
		}

// METEO
if ($opac_show_meteo && $opac_show_meteo_url) {
	$meteo = "<div id=\"meteo\">\n
		<h3>$msg[common_tpl_meteo_invite]</h3>\n
		<p class=\"centered\">$opac_show_meteo_url</p>\n
		<small>$msg[common_tpl_meteo] $opac_biblio_town</small>\n
		</div><!-- fermeture # meteo -->\n";
	}

// ADRESSE
$adresse = "<div id=\"adresse\">\n
		<h3>!!common_tpl_address!!</h3>\n
		<span>
			$opac_biblio_name<br />
			$opac_biblio_adr1<br />
			$opac_biblio_cp $opac_biblio_town<br />
			$opac_biblio_country&nbsp;<br />
			$opac_biblio_phone<br />";
			if ($opac_biblio_email) $adresse.="<span id='opac_biblio_email'>
			<a href=\"mailto:$opac_biblio_email\" alt=\"$opac_biblio_email\">!!common_tpl_contact!!</a></span>";
$adresse.="</span>" ;
$adresse.="
	    </div><!-- fermeture #adresse -->" ;

// bloc post adresse
if ($opac_biblio_post_adress){
	$adresse .= "<div id=\"post_adress\">\n
		<span>".$opac_biblio_post_adress."
		</span>	
	    </div><!-- fermeture #post_adress -->" ;
}

// le footer clos le <div id=\"supportingText\"><span>, reste ouvert le <div id=\"container\">
$footer = "	
		!!div_liens_bas!! \n
		</div><!-- /div id=main -->\n
		<div id=\"intro\">\n";

$inclus_footer = "	
		</span>
		!!div_liens_bas!! \n
		</div><!-- /div id=main -->\n
		<div id=\"intro\">\n";
		
// Si $opac_biblio_important_p1 est renseigné, alors intro_message est affiché
// Ceci permet plus de liberté avec la CSS
$std_header_suite="<div id=\"intro_message\">";
if ($opac_biblio_important_p1) 	
		 $std_header_suite.="<div class=\"p1\">$opac_biblio_important_p1</div>";
// si $opac_biblio_important_p2 est renseigné alors suite d'intro_message
if ($opac_biblio_important_p2 && !$std_header_suite)
	   $std_header_suite.="<div class=\"p2\">$opac_biblio_important_p2</div>";
else $std_header_suite.="<div class=\"p2\">$opac_biblio_important_p2</div>";
// fin intro_message
$std_header_suite.="</div>";
	
$std_header.=$std_header_suite ;
$inclus_header.=$std_header_suite;

$footer.= $footer_suite ;
$inclus_footer.= $footer_suite ;
eval("\$opac_biblio_preamble_p1=\"".str_replace("\"","\\\"",$opac_biblio_preamble_p1)."\";");
eval("\$opac_biblio_preamble_p2=\"".str_replace("\"","\\\"",$opac_biblio_preamble_p2)."\";");
$footer_suite ="<div id=\"intro_bibli\">
			<h3>$opac_biblio_name</h3>
			<div class=\"p1\">$opac_biblio_preamble_p1</div>
			<div class=\"p2\">$opac_biblio_preamble_p2</div>
			</div>
		</div><!-- /div id=intro -->";

$footer.= $footer_suite ;
$inclus_footer.= $footer_suite ;
		
$footer .="		
		!!contenu_bandeau!!";
		
$footer .="</div><!-- /div id=container -->
		<script type='text/javascript'>init_drag();	//rechercher!!</script> 
		</body>
		</html>
		"; //".($surligne?"rechercher(1);":"")."

$inclus_footer .="
		!!contenu_bandeau!!
		</div><!-- /div id=container -->
		<script type='text/javascript'>init_drag(); //rechercher!!</script>
				";

$liens_opac['lien_rech_notice'] 		= "./index.php?lvl=notice_display&id=!!id!!";
$liens_opac['lien_rech_auteur'] 		= "./index.php?lvl=author_see&id=!!id!!";
$liens_opac['lien_rech_editeur'] 		= "./index.php?lvl=publisher_see&id=!!id!!";
$liens_opac['lien_rech_titre_uniforme']	= "./index.php?lvl=titre_uniforme_see&id=!!id!!";
$liens_opac['lien_rech_serie'] 			= "./index.php?lvl=serie_see&id=!!id!!";
$liens_opac['lien_rech_collection'] 	= "./index.php?lvl=coll_see&id=!!id!!";
$liens_opac['lien_rech_subcollection'] 	= "./index.php?lvl=subcoll_see&id=!!id!!";
$liens_opac['lien_rech_indexint'] 		= "./index.php?lvl=indexint_see&id=!!id!!";
$liens_opac['lien_rech_motcle'] 		= "./index.php?lvl=more_results&mode=keyword&user_query=!!mot!!";
$liens_opac['lien_rech_categ'] 			= "./index.php?lvl=categ_see&id=!!id!!";
$liens_opac['lien_rech_perio'] 			= "./index.php?lvl=notice_display&id=!!id!!";
$liens_opac['lien_rech_bulletin'] 		= "./index.php?lvl=bulletin_display&id=!!id!!";

$begin_result_liste = "<a href='javascript:expandAll()'><img class='img_plusplus' src='./images/expand_all.gif' border='0' id='expandall'></a>&nbsp;<a href='javascript:collapseAll()'><img class='img_moinsmoins' src='./images/collapse_all.gif' border='0' id='collapseall'></a>" ;
$affich_tris_result_liste= "&nbsp;<a href='index.php?lvl=sort&page_en_cours=!!page_en_cours!!' alt=\"".$msg['tris_dispos']."\" title=\"".$msg['tris_dispos']."\"><img src='./images/orderby_az.gif' align='middle' hspace='3' border='0' id='sort_icon'></a>";

define( 'AFF_ETA_NOTICES_NON', 0 );
define( 'AFF_ETA_NOTICES_ISBD', 1 );
define( 'AFF_ETA_NOTICES_PUBLIC', 2 );
define( 'AFF_ETA_NOTICES_BOTH', 4 );
define( 'AFF_ETA_NOTICES_BOTH_ISBD_FIRST', 5 );
define( 'AFF_ETA_NOTICES_REDUIT', 8 );
define( 'AFF_ETA_NOTICES_DEPLIABLES_NON', 0 );
define( 'AFF_ETA_NOTICES_DEPLIABLES_OUI', 1 );

define( 'AFF_BAN_NOTICES_NON', 0 );
define( 'AFF_BAN_NOTICES_ISBD', 1 );
define( 'AFF_BAN_NOTICES_PUBLIC', 2 );
define( 'AFF_BAN_NOTICES_BOTH', 4 );
define( 'AFF_BAN_NOTICES_BOTH_ISBD_FIRST', 5 );
define( 'AFF_BAN_NOTICES_REDUIT', 8 );
define( 'AFF_BAN_NOTICES_DEPLIABLES_NON', 0 );
define( 'AFF_BAN_NOTICES_DEPLIABLES_OUI', 1 );
