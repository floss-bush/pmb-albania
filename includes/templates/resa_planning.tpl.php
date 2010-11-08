<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa_planning.tpl.php,v 1.6 2008-07-17 12:03:41 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// en-tête et pied de page
$layout_begin = "
<div class=\"row\">
  $msg[353] <a href='./circ.php?categ=pret&form_cb=!!cb_lecteur!!&groupID=$groupID'>!!nom_lecteur!!</a>
</div>
";


$menu_search_commun = "
	<div class='row'>
		<a href='./circ.php?categ=resa_planning&resa_action=search_resa&mode=0&id_empr=$id_empr&groupID=$groupID'>$msg[354]</a>
		<a href='./circ.php?categ=resa_planning&resa_action=search_resa&mode=1&id_empr=$id_empr&groupID=$groupID'>$msg[355]</a>
		<a href='./circ.php?categ=resa_planning&resa_action=search_resa&mode=5&id_empr=$id_empr&groupID=$groupID'>".$msg["search_by_terms"]."</a>
		<a href='./circ.php?categ=resa_planning&resa_action=search_resa&mode=2&id_empr=$id_empr&groupID=$groupID'>$msg[356]</a>
		<a href='./circ.php?categ=resa_planning&resa_action=search_resa&mode=3&id_empr=$id_empr&groupID=$groupID'>$msg[search_by_panier]</a>
		<a href='./circ.php?categ=resa_planning&resa_action=search_resa&mode=6&id_empr=$id_empr&groupID=$groupID'>".$msg["search_extended"]."</a>
	</div>
";


$menu_search[0] = $menu_search_commun;
$menu_search[1] = $menu_search_commun;
$menu_search[2] = $menu_search_commun;
$menu_search[3] = $menu_search_commun;
$menu_search[4] = $menu_search_commun;
$menu_search[6] = $menu_search_commun;


$form_resa_dates = "
<h3>".$msg['resa_planning_dates']."</h3>
<form action='./circ.php?categ=resa_planning&resa_action=add_resa_suite&id_empr=".$id_empr."&groupID=&id_notice=".$id_notice."' method='post' name='dates_resa'>
<div class='form-contenu'>		
		<div class='row' >
			<label>".$msg['resa_planning_date_debut']."</label>
		</div>
		<div class='row' >
			<input name='resa_deb' size='20' border='0' value='!!resa_deb!!'  type='hidden'>
			<input class='bouton' name='resa_date_debut' value='!!resa_date_debut!!' onclick=\"openPopUp('./select.php?what=calendrier&caller=dates_resa&date_caller=!!resa_deb!!&param1=resa_deb&param2=resa_date_debut&auto_submit=NO&date_anterieure=YES', 'date_date', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" type='button'>			
		</div>
		<div class='row' >
			<label>".$msg['resa_planning_date_fin']."</label>
		</div>
		<div class='row' >
			<input name='resa_fin' size='20' border='0' value='!!resa_fin!!'  type='hidden'>
			<input class='bouton' name='resa_date_fin' value='!!resa_date_fin!!' onclick=\"openPopUp('./select.php?what=calendrier&caller=dates_resa&date_caller=!!resa_fin!!&param1=resa_fin&param2=resa_date_fin&auto_submit=NO&date_anterieure=YES', 'date_date', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" type='button'>			
		</div>
		<div class='row' >
			<input type='hidden' name='id_notice' value='$id_notice'>
		</div>
		<div class='row' >
		</div>
</div>		
		<div class='row' >
			<input type='submit' name='ok' value='".$msg[77]."' class='bouton'>
		</div>
</form>
";


?>

