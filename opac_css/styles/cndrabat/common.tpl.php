<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: common.tpl.php,v 1.2 2009-05-16 10:52:56 dbellamy Exp $

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
					
//Récupération du login
if (!$_SESSION["user_code"]) {
	//Si pas de session
	$cb_=$msg[common_tpl_cardnumber_default];
	} else {
		//Récupération des infos de connection
		$cb_=$_SESSION["user_code"];
		}

//HEADER : short_header = pour les popups
//         std_header = pour les pages standards

$std_header.="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" >
<head>
	<meta http-equiv=\"content-type\" content=\"text/html; charset=$charset\" />
	<meta name=\"author\" content=\"PMB Group\" />

	<meta name=\"keywords\" content=\"OPAC, web, library, opensource, catalog, catalogue, bibliothèque, médiathèque, pmb, phpmybibli\" />
	<meta name=\"description\" content=\"".$msg['opac_title']." $opac_biblio_name.\" />

	<meta name=\"robots\" content=\"all\" />


	<title>".$msg['opac_title']." $opac_biblio_name.</title>

	<link rel=\"stylesheet\" href=\"./styles/".$css."/".$css.".css\" />
	<link rel=\"SHORTCUT ICON\" href=\"images/site/favicon.ico\">
</head>

<body onload=\"window.defaultStatus='".$msg["page_status"]."';\" id=\"pmbopac\">
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
	<div id=\"container\"><div id=\"main\">!!home_on_top!!
						\n";

$inclus_header = "
	<link rel=\"stylesheet\" href=\"./styles/".$css."/".$css.".css\" />
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
	<div id=\"container\"><div id=\"main\">!!home_on_top!!
						\n";
$short_header="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" >
<head>
	<link rel=\"stylesheet\" href=\"./styles/".$css."/".$css.".css\" />
</head>

<body>";

$short_footer="</body></html>";

$popup_header="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" >
<head>
	<link rel=\"stylesheet\" href=\"./styles/".$css."/".$css.".css\" />
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
$liens_bas = "<div id=\"footer\">
		$opac_lien_bas_supplementaire &nbsp;
		<a href=\"$opac_biblio_website\" title=\"$opac_biblio_name\">$opac_biblio_name</a> &nbsp;
		$opac_lien_moteur_recherche &nbsp;
		<a href=\"http://www.sigb.net\" title=\"pmb\" target='_blank'>pmb</a> &nbsp;
		</div>" ;

// HOME
$home_on_left = "<div id=\"accueil\">\n
<h3><span onclick='document.location=\"./index.php?css=$css\"' style='cursor: pointer;'>!!welcome_page!!</span></h3>\n";

	
if ($opac_logosmall<>"") 
	$home_on_left .= "<p class=\"centered\"><a href='./index.php?css=$css'><img src='".$opac_logosmall."'  border='0' align='center'/></a></p>\n";
	else $home_on_left .= "<p class=\"centered\"><a href='./index.php?css=$css'><img src='./images/home1.jpg' border='0' align='center'/></a></p>\n";
	
// affichage du choix de langue  
$common_tpl_lang_select="<div id='lang_select'><h3 ><span>!!msg_lang_select!!</span></h3><span>!!lang_select!!</span></div>\n";

$home_on_left.="!!common_tpl_lang_select!!
					</div><!-- fermeture #accueil -->\n" ;

// HOME lorsque le bandeau gauche n'est pas affiché



if (!$_SESSION["user_code"]) 
	$home_on_top ="<div>



	
	<a href='http://opacnd.hcp.ma/' target='_blank'/><img src='./images/home1.gif' title='Opac Site du Centre National de Documentation' border='0' align='absmiddle'/> 


		</div>\n

		";
	else $home_on_top="<div>

//<a href='http://opacnd.hcp.ma/' target='_blank'>


//<img src='./images/home.gif' title='Opac Site du Centre National de Documentation' border='0' align='absmiddle'/> 

//</a>
	
	//<span onclick='document.location=\"./index.php?css=$css\"' style='cursor: pointer;'>
	<a href='http://opacnd.hcp.ma/' target='_blank'/><img src='./images/home1.gif' title='Opac Site du Centre National de Documentation' border='0' align='absmiddle'/> //</span>

//<span onclick='document.location=\"./index.php?css=$css\"' style='cursor: pointer;'><a href='http://opacnd.hcp.ma/' target='_blank'/><img src='./images/home.gif' align='absmiddle'/> ".$msg["welcome_page"]."</span>
		</div>\n";


// LOGIN FORM
// Si le login est autorisé, alors afficher le formulaire de saisie utilisateur/mot de passe ou le code de l'utilisateur connecté
if ($opac_show_loginform) {
	$loginform ="<div id=\"connexion\">\n
			
			
				<h4><span>!!login_form!!</span></h4>\n
			</div><!-- feremeture #connexion -->\n";
	} else {
		$loginform="";
		$_SESSION["user_code"]="";
		}

// METEO
if ($opac_show_meteo && $opac_show_meteo_url) {
	$meteo = "<div id=\"meteo\">\n
		<h3><span>$msg[common_tpl_meteo_invite]</span></h3>\n
		<p class=\"centered\"></p>\n
		<small>$msg[common_tpl_meteo] $opac_biblio_town</small>\n
		$opac_show_meteo_url
		</div><!-- fermeture # meteo -->\n";
	}

// ADRESSE
$adresse = "<div id=\"adresse\">\n
		<h3><span>!!common_tpl_address!!</span></h3>\n
		<span>
			$opac_biblio_name<br />
			$opac_biblio_adr1<br />
			$opac_biblio_cp $opac_biblio_town<br />
			$opac_biblio_country&nbsp;<br />
			$opac_biblio_phone<br />
			";
if ($opac_biblio_email) $adresse.="
			<a href=\"mailto:$opac_biblio_email\" alt=\"$opac_biblio_email\">!!common_tpl_contact!!</a>";
$adresse.="
		</span>	
	    </div><!-- fermeture #adresse -->" ;
		
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
if ($opac_biblio_important_p1) {
	$std_header_suite="<div id=\"intro_message\">
			<p class=\"p1\"><span>$opac_biblio_important_p1</span></p>";
	// si $opac_biblio_important_p2 est renseigné alors suite d'intro_message
	if ($opac_biblio_important_p2) $std_header_suite.="<p class=\"p2\"><span>$opac_biblio_important_p2</span></p>";
	// fin intro_message
	$std_header_suite.="</div>";
	}

$footer.= $footer_suite ;
$inclus_footer.= $footer_suite ;
eval("\$opac_biblio_preamble_p1=\"".str_replace("\"","\\\"",$opac_biblio_preamble_p1)."\";");
eval("\$opac_biblio_preamble_p2=\"".str_replace("\"","\\\"",$opac_biblio_preamble_p2)."\";");
$footer_suite ="<div id=\"intro_bibli\">
			<h3>$opac_biblio_name</h3>
			<p class=\"p1\"><span>$opac_biblio_preamble_p1</span></p>
			<p class=\"p2\"><span>$opac_biblio_preamble_p2</span></p>
			</div>
		</div><!-- /div id=intro -->";

$footer.= $footer_suite ;
$inclus_footer.= $footer_suite ;
		
$footer .="		
		<div id=\"bandeau\">!!contenu_bandeau!!</div>";
if ($opac_show_liensbas) $footer .=" 
		<div id='all_footer'>
		<a href='http://www.sigb.net'>$msg[common_tpl_pmbname]</a><br />
		$msg[common_tpl_motto]
		</div>";
		
$footer .="</div><!-- /div id=container -->
		</body>
		</html>
		";

$inclus_footer .="	</div>
		<div id=\"bandeau\">!!contenu_bandeau!!</div>
		</div><!-- /div id=container -->
		";



$liens_opac['lien_rech_notice'] 		= "./index.php?css=1&lvl=notice_display&id=!!id!!";
$liens_opac['lien_rech_auteur'] 		= "./index.php?css=$css&lvl=author_see&id=!!id!!";
$liens_opac['lien_rech_editeur'] 		= "./index.php?css=$css&lvl=publisher_see&id=!!id!!";
$liens_opac['lien_rech_serie'] 			= "./index.php?css=$css&lvl=serie_see&id=!!id!!";
$liens_opac['lien_rech_collection'] 	= "./index.php?css=$css&lvl=coll_see&id=!!id!!";
$liens_opac['lien_rech_subcollection'] 	= "./index.php?css=$css&lvl=subcoll_see&id=!!id!!";
$liens_opac['lien_rech_indexint'] 		= "./index.php?css=$css&lvl=indexint_see&id=!!id!!";
$liens_opac['lien_rech_motcle'] 		= "./index.php?css=$css&lvl=more_results&mode=keyword&user_query=!!mot!!";
$liens_opac['lien_rech_categ'] 			= "./index.php?css=$css&lvl=categ_see&id=!!id!!";
$liens_opac['lien_rech_perio'] 			= "./index.php?css=$css&lvl=notice_display&id=!!id!!";
$liens_opac['lien_rech_bulletin'] 		= "./index.php?css=$css&lvl=bulletin_display&id=!!id!!";

$begin_result_liste = "<a href='javascript:expandAll()'><img src='./images/expand_all.gif' border='0' id='expandall'></a>&nbsp;<a href='javascript:collapseAll()'><img src='./images/collapse_all.gif' border='0' id='collapseall'></a><br />" ;

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
