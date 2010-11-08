<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: acquisition.tpl.php,v 1.16 2009-07-31 14:37:09 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// $acquisition_menu : menu page acquisition
$acquisition_menu = "
<div id='menu'>
	<h3 onclick='menuHide(this,event)'>$msg[acquisition_menu_ach]</h3>
	<ul>
		<li><a href='./acquisition.php?categ=ach&sub=devi&id='>$msg[acquisition_menu_ach_devi]</a></li>
		<li><a href='./acquisition.php?categ=ach&sub=cmde&id='>$msg[acquisition_menu_ach_cmde]</a></li>
		<li><a href='./acquisition.php?categ=ach&sub=livr&id='>$msg[acquisition_menu_ach_livr]</a></li>
		<li><a href='./acquisition.php?categ=ach&sub=fact&id='>$msg[acquisition_menu_ach_fact]</a></li>
	</ul>
	<ul>
		<li><a href='./acquisition.php?categ=ach&sub=fourn&id='>$msg[acquisition_menu_ach_fourn]</a></li>
	</ul>	
	<ul>
		<li><a href='./acquisition.php?categ=ach&sub=bud&id='>$msg[acquisition_menu_ref_budget]</a></li>
	</ul>	
	<h3 onclick='menuHide(this,event)'>$msg[acquisition_menu_sug]</h3>
	<ul>
		<li><a href='./acquisition.php?categ=sug&sub=multi'>$msg[acquisition_menu_sug_multiple]</a></li>
		<li><a href='./acquisition.php?categ=sug&sub=import'>$msg[acquisition_menu_sug_import]</a></li>	
		<li><a href='./acquisition.php?categ=sug&sub=empr_sug'>$msg[acquisition_menu_sug_empr]</a></li>	
		<li><a href='./acquisition.php?categ=sug&id='>$msg[acquisition_menu_sug_todo]</a></li>
	</ul>
	<div id='div_alert' class='erreur'>$aff_alerte</div>
</div>
";
//	----------------------------------

// $acquisition_layout : layout page acquisition
$acquisition_layout = "
<div id='conteneur' class='$current_module'>
$acquisition_menu
<div id='contenu'>
";


// $acquisition_layout_end : layout page acquisition (fin)
$acquisition_layout_end = '
</div>
</div>
';


// $user_query : form de recherche
$user_query = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.user_input.value.length == 0)
			{
				alert(\"$msg[141]\");
				return false;
			}
		return true;
	}
	function aide_regex()
	{
		var fenetreAide;
		var prop = 'scrollbars=yes, resizable=yes';
		fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, prop);
	}
-->
</script>
<form class='form-$current_module' name='search' method='post' action='!!action!!'>
<h3><span>!!user_query_title!!</span></h3>
<div class='form-contenu'>
	<div class='row'>
		<div class='colonne'>
			<input type='text' class='saisie-50em' name='user_input' />
		</div>
		<div class='right'></div>
		<div class='row'></div>
	</div>
</div>
";


$user_query.="	
<div class='row'>
	<div class='left'>
		<input type='submit' class='bouton' value='$msg[142]' onClick=\"return test_form(this.form)\" />
		<input class='bouton' type='button' value='!!add_auth_msg!!' onClick=\"document.location='!!add_auth_act!!'\" />
	</div>
	<div class='right'>
		<!-- lien_derniers -->
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>
	document.forms['search'].elements['user_input'].focus();
</script>
<div class='row'></div>
";
?>