<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: catalog.tpl.php,v 1.34 2010-04-15 12:53:18 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");


// Valeurs pour l'affichage de la page par defaut 
// (selection de l'onglet)
// note : l'autre solution serait de faire un menu général (voir en admin) 
//plutôt que d'afficher un sous menu par défaut.
if(!$categ){
	$categ="search";
	$mode=0;
} elseif($categ=="caddie" and !$sub){
	//Paniers > Gestion : selection de "Gestion des paniers par defaut"
	$sub="gestion";
	$quoi="panier";	
}

// ---------------------------------------------------------------------------
//		$catalog_menu : Menu vertical du catalogage
// ---------------------------------------------------------------------------
// ancien début de catalog_menu <h3>$msg[129]</h3>
$catalog_menu = "
<div id='menu'>
<h3 onclick='menuHide(this,event)'>".$msg['recherche']."</h3>
<ul>
	<li><a href='./catalog.php'>".$msg["recherche_catalogue"]."</a></li>
	<li><a href='./catalog.php?categ=serials'>".$msg["recherche_periodique"]."</a></li>
	<li><a href='./catalog.php?categ=last_records'>$msg[938]</a></li>
	<li><a href='./catalog.php?categ=search_perso'>".$msg["search_perso_menu"]."</a></li>	
</ul>

<h3 onclick='menuHide(this,event)'>$msg[4057]</h3>
<ul>
	<li><a href='./catalog.php?categ=create'>$msg[270]</a></li>";
	if ($opac_avis_allow) $catalog_menu .=	"<li><a href='./catalog.php?categ=avis'>$msg[menu_gestion_avis]</a></li>";
	if ($opac_allow_add_tag) $catalog_menu .=	"<li><a href='./catalog.php?categ=tags'>$msg[menu_gestion_tags]</a></li>";

$catalog_menu .= "
</ul>
<h3 onclick='menuHide(this,event)'>$msg[771]</h3>
<ul>
	<li><a href='./catalog.php?categ=serials&sub=serial_form&id=0'>".$msg["new_serial"]."</a></li>
	<li><a href='./catalog.php?categ=serials&sub=pointage&id=0'>".$msg["pointage_menu_pointage"]."</a></li>
</ul>
<h3 onclick='menuHide(this,event)'>$msg[caddie_menu]</h3>
<ul>
	<li><a href='./catalog.php?categ=caddie'>$msg[caddie_menu_gestion]</a></li>
	<li><a href='./catalog.php?categ=caddie&sub=collecte'>$msg[caddie_menu_collecte]</a></li>
	<li><a href='./catalog.php?categ=caddie&sub=pointage'>$msg[caddie_menu_pointage]</a></li>
	<li><a href='./catalog.php?categ=caddie&sub=action'>$msg[caddie_menu_action]</a></li>
</ul>
<h3 onclick='menuHide(this,event)'>$msg[etagere_menu]</h3>
<ul>
	<li><a href='./catalog.php?categ=etagere'>$msg[etagere_menu_gestion]</a></li>
	<li><a href='./catalog.php?categ=etagere&sub=constitution'>$msg[etagere_menu_constitution]</a></li>
</ul>
<h3 onclick='menuHide(this,event)'>".$msg["externe_menu"]."</h3>
<ul>";

if ($z3950_accessible) {
	$catalog_menu.= "<li><a href='./catalog.php?categ=z3950'>".$msg["externe_z3950"]."</a></li>";
}
$catalog_menu.= "<li><a href='./catalog.php?categ=search&mode=7&external_type=simple'>".$msg["externe_connecteurs"]."</a></li>
</ul>";

if ($acquisition_active) {
	$catalog_menu.= "<h3 onclick='menuHide(this,event)'>$msg[acquisition_menu_sug]</h3>
	<ul>
		<li><a href='./catalog.php?categ=sug&action=modif&id_bibli=0'>$msg[acquisition_sug_do]</a></li>
	</ul>";
}
$catalog_menu.= "
<div id='div_alert' class='erreur'>$aff_alerte</div>
</div>
";

// ---------------------------------------------------------------------------
//		Menus horizontaux : sous-onglets
// ---------------------------------------------------------------------------
// $catalog_menu_panier_gestion : menu gestion des paniers en catalogage
$catalog_menu_panier_gestion = "
<h1>$msg[caddie_menu] <span>> $msg[caddie_menu_gestion] > <!--!!sous_menu_choisi!! --></span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=caddie&sub=gestion&quoi=panier").">
		<a title='$msg[caddie_menu_gestion_panier]' href='./catalog.php?categ=caddie&sub=gestion&quoi=panier'>
			$msg[caddie_menu_gestion_panier]
		</a>
	</span>
	<span".ongletSelect("categ=caddie&sub=gestion&quoi=procs").">
		<a title='$msg[caddie_menu_gestion_procs]' href='./catalog.php?categ=caddie&sub=gestion&quoi=procs'>
			$msg[caddie_menu_gestion_procs]
		</a>
	</span>
	<span".ongletSelect("categ=caddie&sub=gestion&quoi=remote_procs").">
		<a title='$msg[remote_procedures_catalog_title]' href='./catalog.php?categ=caddie&sub=gestion&quoi=remote_procs'>
			$msg[remote_procedures_catalog_title]
		</a>
	</span>
</div>
";

// $catalog_menu_panier_collecte : menu collecte des contenus de paniers
$catalog_menu_panier_collecte = "
<h1>$msg[caddie_menu] <span>> $msg[caddie_menu_collecte] > <!--!!sous_menu_choisi!! --></span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=caddie&sub=collecte&moyen=douchette").">
		<a title='$msg[caddie_menu_collecte_cb]' href='./catalog.php?categ=caddie&sub=collecte&moyen=douchette'>
			$msg[caddie_menu_collecte_cb]
		</a>
	</span>
	<!-- <span".ongletSelect("categ=caddie&sub=collecte&moyen=import").">
		<a title='$msg[caddie_menu_collecte_import]' href='./catalog.php?categ=caddie&sub=collecte&moyen=import'>
			$msg[caddie_menu_collecte_import]
		</a>
	</span> -->
	<span".ongletSelect("caddie&sub=collecte&moyen=selection").">
		<a title='$msg[caddie_menu_collecte_selection]' href='./catalog.php?categ=caddie&sub=collecte&moyen=selection'>
			$msg[caddie_menu_collecte_selection]
		</a>
	</span>
</div>
";

// $catalog_menu_panier_pointage : menu pointage des contenus de paniers
$catalog_menu_panier_pointage = "
<h1>$msg[caddie_menu] <span>> $msg[caddie_menu_pointage] > <!--!!sous_menu_choisi!! --></span></h1>
<div class='hmenu'>
	<span".ongletSelect("caddie&sub=pointage&moyen=douchette").">
		<a title='$msg[caddie_menu_pointage_cb]' href='./catalog.php?categ=caddie&sub=pointage&moyen=douchette'>
			$msg[caddie_menu_pointage_cb]
		</a>
	</span>
	<!-- <span>
		<a title='$msg[caddie_menu_pointage_import]' href='./catalog.php?categ=caddie&sub=pointage&moyen=import'>
			$msg[caddie_menu_pointage_import]
		</a>
	</span> -->
	<!-- <span".ongletSelect("caddie&sub=pointage&moyen=importunimarc").">
		<a title='$msg[caddie_menu_pointage_import_unimarc]' href='./catalog.php?categ=caddie&sub=pointage&moyen=importunimarc'>
			$msg[caddie_menu_pointage_import_unimarc]
		</a>
	</span> -->
	<span".ongletSelect("categ=caddie&sub=pointage&moyen=selection").">
		<a title='$msg[caddie_menu_pointage_selection]' href='./catalog.php?categ=caddie&sub=pointage&moyen=selection'>
			$msg[caddie_menu_pointage_selection]
		</a>
	</span>
	<span".ongletSelect("categ=caddie&sub=pointage&moyen=raz").">
		<a title='$msg[caddie_menu_pointage_raz]' href='./catalog.php?categ=caddie&sub=pointage&moyen=raz'>
			$msg[caddie_menu_pointage_raz]
		</a>
	</span>
</div>
";

// $catalog_menu_panier_action : menu action des contenus de paniers
$catalog_menu_panier_action = "
<h1>$msg[caddie_menu] <span>> $msg[caddie_menu_action] > <!--!!sous_menu_choisi!! --></span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=caddie&sub=action&quelle=supprpanier").">
		<a title='$msg[caddie_menu_action_suppr_panier]' href='./catalog.php?categ=caddie&sub=action&quelle=supprpanier'>
			$msg[caddie_menu_action_suppr_panier]
		</a>
	</span>
	<span".ongletSelect("categ=caddie&sub=action&quelle=transfert").">
		<a title='$msg[caddie_menu_action_transfert]' href='./catalog.php?categ=caddie&sub=action&quelle=transfert'>
			$msg[caddie_menu_action_transfert]
		</a>
	</span> 
	<span".ongletSelect("categ=caddie&sub=action&quelle=edition").">
		<a title='$msg[caddie_menu_action_edition]' href='./catalog.php?categ=caddie&sub=action&quelle=edition'>
			$msg[caddie_menu_action_edition]
		</a>
	</span>
	<span".ongletSelect("categ=caddie&sub=action&quelle=impr_cote").">
		<a title=\"".$msg['caddie_menu_action_impr_cote']."\" href='./catalog.php?categ=caddie&sub=action&quelle=impr_cote'>
			$msg[caddie_menu_action_impr_cote]
		</a>
	</span>
	<span".ongletSelect("categ=caddie&sub=action&quelle=export").">
		<a title='$msg[caddie_menu_action_export]' href='./catalog.php?categ=caddie&sub=action&quelle=export'>
			$msg[caddie_menu_action_export]
		</a>
	</span> 
	<span".ongletSelect("categ=caddie&sub=action&quelle=expdocnum").">
		<a title='$msg[caddie_menu_action_exp_docnum]' href='./catalog.php?categ=caddie&sub=action&quelle=expdocnum'>
			$msg[caddie_menu_action_exp_docnum]
		</a>
	</span> 
	<span".ongletSelect("categ=caddie&sub=action&quelle=selection").">
		<a title=\"".$msg['caddie_menu_action_selection']."\" href='./catalog.php?categ=caddie&sub=action&quelle=selection'>
			$msg[caddie_menu_action_selection]
		</a>
	</span>
	<span".ongletSelect("categ=caddie&sub=action&quelle=supprbase").">
		<a title='$msg[caddie_menu_action_suppr_base]' href='./catalog.php?categ=caddie&sub=action&quelle=supprbase'>
			$msg[caddie_menu_action_suppr_base]
		</a>
	</span>
	<!-- <span".ongletSelect("caddie&sub=action&quelle=changebloc").">
		<a title='$msg[caddie_menu_action_change_bloc]' href='./catalog.php?categ=caddie&sub=action&quelle=changebloc'>
			$msg[caddie_menu_action_change_bloc]
		</a>
	</span> -->
</div>
";

// $catalog_layout : layout page catalogage
$catalog_layout = "
<div id='conteneur' class='$current_module'>
$catalog_menu
<div id='contenu'>
<!--!!menu_contextuel!! -->
";

// $catalog_layout_end : layout page catalogue (fin)
$catalog_layout_end = '
</div></div>
';

// $biblio_query : form de recherche : semble ne plus être utilisé.....
$biblio_query = "
<script type='text/javascript'>
	function aide_regex()
	{
		var fenetreAide;
		var prop = 'scrollbars=yes, resizable=yes';
		fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, prop);
	}

	function test_form(form)
	{
		if((form.ex_query.value.length == 0) && (form.ISBN_query.value.length == 0) && (form.title_query.value.length == 0) && (form.author_query.value.length == 0))
		{
			alert(\"${msg[348]}\");
			return false;
		}

		return true;
	}
</script>
<h1>$msg[235]</h1>
<form class='form-$current_module' id='biblio_query' name='biblio_query' method='post' action='./catalog.php?categ=search' onSubmit='return test_form(this)'>
<div class='form-contenu'>
<div class='row'>
	<label class='etiquette' for='ex_query'>$msg[232]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-50em' name='ex_query' id='ex_query' />
	</div>
<div class='row'>
	<label class='etiquette' for='ISBN_query'>$msg[231]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-50em' name='ISBN_query' id='ISBN_query' />
	</div>
<div class='row'>
	<label class='etiquette' for='title_query'>$msg[233]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-50em' value='' size='36' name='title_query' id='title_query' />
	</div>
<div class='row'>
	<label class='etiquette' for='author_query'>$msg[234]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-50em' value='' name='author_query' id='author_query' />
	</div>
<div class='row'>
	<span>$msg[155] <a href='./help.php?whatis=regex' onclick='aide_regex();return false;'></a></span>
	</div>
</div>
<div class='row'>
	<input type='submit' class='bouton' value='$msg[142]' />
	</div>
</form>
<script type='text/javascript'>	document.forms['biblio_query'].elements['ex_query'].focus();
</script>
";

//  $saisie_cb_form: form de saisie code barre
$saisie_cb_form = "
<h1>$msg[270]</h1>
<form class='form-$current_module' id='saisie_cb' name='saisie_cb' method='post' action='./catalog.php?categ=create_form&id=0'>
<div class='form-contenu'>
<div class='row'>
	<label class='etiquette' for='saisieISBN'>$msg[255]</label>
	</div>
<div class='row'>
	<input class='saisie-20em' type='text' id='saisieISBN' name='saisieISBN' value='' />
	</div>
</div>
<div class='row'>
	<input class='bouton' type='submit' value=' $msg[502] ' />
	</div>
</form>
<script type='text/javascript'>document.forms['saisie_cb'].elements['saisieISBN'].focus();</script>
";

//  $search_bar: code qui fait la barre de ranking en résultat recherche
// si vous changez la taille, il faut mettre à jour $lengtha et $lengthb
// dans classes/notice_display.class.php
$search_bar = "
<table border=\"0\" class=\"result-bar\" cellspacing=\"0\" width=\"25\">
  <tr>
    <td class=\"bar-left\"><img src=\"./images/bar_spacer.gif\" width=\"!!la!!\" height=\"3\" alt=\"rank: !!indice!!%\"></td>
    <td class=\"bar-right\"><img src=\"./images/bar_spacer.gif\" width=\"!!lb!!\" height=\"3\"></td>
  </tr>
</table>
";
