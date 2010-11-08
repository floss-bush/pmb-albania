<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: edit.tpl.php,v 1.19 2009-08-11 12:32:36 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// $edit_menu : menu page Editions
$edit_menu = "
<div id='menu'>
<h3 onclick='menuHide(this,event)'>$msg[1130]</h3>
<ul>
<li><a href='./edit.php?categ=procs'>$msg[1131]</a></li>
</ul>
<h3 onclick='menuHide(this,event)'>$msg[1110]</h3>
<ul>
<li><a href='./edit.php?categ=expl&sub=encours'>$msg[1111]</a></li>
<li><a href='./edit.php?categ=expl&sub=retard'>$msg[1112]</a></li>
<li><a href='./edit.php?categ=expl&sub=retard_par_date'>$msg[edit_expl_retard_par_date]</a></li>
<li><a href='./edit.php?categ=expl&sub=ppargroupe'>$msg[1114]</a></li>
<li><a href='./edit.php?categ=expl&sub=rpargroupe'>".$msg["menu_retards_groupe"]."</a></li>
</ul>
<h3 onclick='menuHide(this,event)'>$msg[350]</h3>
<ul>
<li><a href='./edit.php?categ=notices&sub=resa'>".$msg['edit_resa_menu']."</a></li>
<li><a href='./edit.php?categ=notices&sub=resa_a_traiter'>".$msg['edit_resa_menu_a_traiter']."</a></li>
</ul>
<h3 onclick='menuHide(this,event)'>$msg[1120]</h3>
<ul>
<li><a href='./edit.php?categ=empr&sub=encours'>$msg[1121]</a></li>
<li><a href='./edit.php?categ=empr&sub=limite'>$msg[edit_menu_empr_abo_limite]</a></li>
<li><a href='./edit.php?categ=empr&sub=depasse'>$msg[edit_menu_empr_abo_depasse]</a></li>
</ul>
<h3 onclick='menuHide(this,event)'>$msg[1150]</h3>
<ul>
<li><a href='./edit.php?categ=serials&sub=collect'>$msg[1151]</a></li>
<!-- <li><a href='./edit.php?categ=serials&sub=manquant'>$msg[1154]</a></li> -->
</ul>
<h3 onclick='menuHide(this,event)'>$msg[1140]</h3>
<ul>
<li><a href='./edit.php?categ=cbgen&sub=libre'>$msg[1141]</a></li>
</ul>

<h3 onclick='menuHide(this,event)'>".$msg["edit_tpl_menu"]."</h3>
<ul>
<li><a href='./edit.php?categ=tpl&sub=notice'>".$msg["edit_notice_tpl_menu"]."</a></li>
</ul>";

if ($pmb_transferts_actif=="1") {
	$edit_menu .= "
		<h3 onclick='menuHide(this,event)'>".$msg["transferts_edition_titre"]."</h3>
		<ul>";
	if ($transferts_validation_actif=="1")
		$edit_menu .= "
			<li><a href='./edit.php?categ=transferts&sub=validation'>".$msg["transferts_edition_validation"]."</a></li>";
	$edit_menu .= "
		<li><a href='./edit.php?categ=transferts&sub=envoi'>".$msg["transferts_edition_envoi"]."</a></li>
		<li><a href='./edit.php?categ=transferts&sub=retours'>".$msg["transferts_edition_retours"]."</a></li>
		</ul>
		";
}

if($pmb_logs_activate){
	$edit_menu .= "<h3 onclick='menuHide(this,event)'>$msg[opac_admin_menu]</h3>
	<ul>
	<li><a href='./edit.php?categ=stat_opac'>$msg[stat_opac_menu]</a></li>
	</ul>";
}
$edit_menu .= "</div>";

// $edit_layout : layout page edition
$edit_layout = "
<div id='conteneur' class='$current_module'>
$edit_menu
<div id='contenu'>";

// $edit_layout_end : layout page edition (fin)
$edit_layout_end = "</div></div>";
