<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fichier.tpl.php,v 1.2 2010-09-14 07:53:42 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// $acquisition_menu : menu page acquisition
/*
  	<h3 onclick='menuHide(this,event)'>".$msg['fichier_menu_panier']."</h3>
	<ul>
		<li><a href='./fichier.php?categ=panier&mode=gestion'>".$msg['fichier_menu_panier_gestion']."</a></li>
		<li><a href='./fichier.php?categ=panier&mode=collect'>".$msg['fichier_menu_panier_collecter']."</a></li>
		<li><a href='./fichier.php?categ=panier&mode=pointer'>".$msg['fichier_menu_panier_pointer']."</a></li>
		<li><a href='./fichier.php?categ=panier&mode=action'>".$msg['fichier_menu_panier_action']."</a></li>				
	</ul>
 */
$fichier_menu = "
<div id='menu'>
	<h3 onclick='menuHide(this,event)'>".$msg['fichier_menu_consulter']."</h3>
	<ul>
		<li><a href='./fichier.php?categ=consult&mode=search'>".$msg['fichier_menu_search']."</a></li>
		<li><a href='./fichier.php?categ=consult&mode=search_multi'>".$msg['fichier_menu_search_multi']."</a></li>				
	</ul>	
	<h3 onclick='menuHide(this,event)'>".$msg['fichier_menu_saisie']."</h3>
	<ul>
		<li><a href='./fichier.php?categ=saisie'>".$msg['fichier_menu_new_fiche']."</a></li>
	</ul>
	
	<h3 onclick='menuHide(this,event)'>".$msg['fichier_menu_gerer']."</h3>
	<ul>
		<li><a href='./fichier.php?categ=gerer&mode=champs'>".$msg['fichier_gestion_champs']."</a></li>
		
		<li><a href='./fichier.php?categ=gerer&mode=reindex'>".$msg['fichier_gestion_reindex']."</a></li>				
	</ul>	
</div>
";

// $fichier_layout : layout page fichier
$fichier_layout = "
<div id='conteneur' class='$current_module'>
$fichier_menu
<div id='contenu'>
";


// $fichier_layout_end : layout page fichier (fin)
$fichier_layout_end = "
</div>
</div>
";


$fichier_menu_display = "
<h1>".htmlentities($msg['fichier_gestion_affichage'],ENT_QUOTES,$charset)." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=gerer&mode=display&sub=position").">
		<a title=".htmlentities($msg['fichier_display_position'],ENT_QUOTES,$charset)." href='./fichier.php?categ=gerer&mode=display&sub=position'>
			".htmlentities($msg['fichier_display_position'],ENT_QUOTES,$charset)."
		</a>
	</span>
	<span".ongletSelect("categ=gerer&mode=display&sub=list").">
		<a title='".htmlentities($msg['fichier_display_result_list'],ENT_QUOTES,$charset)."' href='./fichier.php?categ=gerer&mode=display&sub=list'>
			".htmlentities($msg['fichier_display_result_list'],ENT_QUOTES,$charset)."
		</a>
	</span>
</div>
";

$fichier_menu_collecter ="
<h1>".htmlentities($msg['fichier_menu_paniers'],ENT_QUOTES,$charset)." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=panier&mode=collect&sub=proc").">
		<a title=".htmlentities($msg['fichier_pointer_procedures'],ENT_QUOTES,$charset)." href='./fichier.php?categ=panier&mode=collect&sub=proc'>
			".htmlentities($msg['fichier_pointer_procedures'],ENT_QUOTES,$charset)."
		</a>
	</span>
</div>
";

$fichier_menu_pointer = "
<h1>".htmlentities($msg['fichier_menu_paniers'],ENT_QUOTES,$charset)." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=panier&mode=pointer&sub=proc").">
		<a title=".htmlentities($msg['fichier_pointer_procedures'],ENT_QUOTES,$charset)." href='./fichier.php?categ=panier&mode=pointer&sub=proc'>
			".htmlentities($msg['fichier_pointer_procedures'],ENT_QUOTES,$charset)."
		</a>
	</span>
</div>
";

$fichier_menu_actions = "
<h1>".htmlentities($msg['fichier_menu_paniers'],ENT_QUOTES,$charset)." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=panier&mode=action&sub=proc").">
		<a title=".htmlentities($msg['fichier_pointer_procedures'],ENT_QUOTES,$charset)." href='./fichier.php?categ=panier&mode=action&sub=proc'>
			".htmlentities($msg['fichier_pointer_procedures'],ENT_QUOTES,$charset)."
		</a>
	</span>
	<span".ongletSelect("categ=panier&mode=action&sub=mail").">
		<a title='".htmlentities($msg['fichier_action_mail'],ENT_QUOTES,$charset)."' href='./fichier.php?categ=panier&mode=action&sub=mail'>
			".htmlentities($msg['fichier_action_mail'],ENT_QUOTES,$charset)."
		</a>
	</span>
	<span".ongletSelect("categ=panier&mode=action&sub=sms").">
		<a title='".htmlentities($msg['fichier_action_sms'],ENT_QUOTES,$charset)."' href='./fichier.php?categ=panier&mode=action&sub=sms'>
			".htmlentities($msg['fichier_action_sms'],ENT_QUOTES,$charset)."
		</a>
	</span>
	<span".ongletSelect("categ=panier&mode=action&sub=edit").">
		<a title='".htmlentities($msg['fichier_action_edit'],ENT_QUOTES,$charset)."' href='./fichier.php?categ=panier&mode=action&sub=edit'>
			".htmlentities($msg['fichier_action_edit'],ENT_QUOTES,$charset)."
		</a>
	</span>
</div>
";