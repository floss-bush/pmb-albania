<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rss_see.inc.php,v 1.3 2009-03-13 16:36:07 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage des infos d'un RSS
require_once($base_path."/classes/rss_flux.class.php");

print "<div id='aut_details'>\n";

print pmb_bidi("<h3>$logo_rss_si_rss &nbsp;<span>".$msg[show_rss_dispo]."</span></h3>\n");

if ($id) {
	//Récupération des infos du RSS
	$r = new rss_flux($id);
	
	print "<div id='aut_details_container'>\n";
	print "<div id='aut_see'>";
	print genere_page_rss($id);
	print $r->descr_rss_flux ;
	print "	</div><!-- fermeture #aut_see -->\n
			<div id='aut_details_liste'>\n";
	print affiche_rss_from_url($opac_url_base."/rss.php?id=$id") ;
	print "\n
				</div><!-- fermeture #aut_details_liste -->\n";
	print "</div><!-- fermeture #aut_details_container -->\n";

	} else {
		print genere_page_rss () ;
	}
print "</div><!-- fermeture #aut_see -->\n";	
