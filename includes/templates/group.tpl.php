<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: group.tpl.php,v 1.13 2008-05-15 09:36:47 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// template pour la gestion des groupes

// propriétés du sélecteur d'emprunteur
$select2_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";

// header de la zone de groupe
$group_header = "
<h1>$msg[907]</h1>
<div class='row'>
";

// footer de la zone de groupe
$group_footer = "
</div>
";

// form de recherche
$group_search = "
<form class='form-$current_module' id='groupsearch' name='groupsearch' method='post' action='./circ.php?categ=groups&action=listgroups'>
<h3>$msg[917]</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='group_query'>$msg[908]</label>
		</div>
	<div class='row'>
		<input class='saisie-80em' id='group_query' type='text' value='!!group_query!!' name='group_query' title='$msg[3001]' />
		</div>
	<div class='row'>
		<span class='astuce'><strong>$msg[astuce]</strong>$msg[1901]$msg[3001]</span>
		</div>
	</div>
<div class='row'>
	<input type='submit' class='bouton' value='$msg[502]' />
	<input type='button' class='bouton' value='$msg[909]' onClick='document.location=\"./circ.php?categ=groups&action=create\"' />
	</div>
</form>
<script type=\"text/javascript\">
document.forms['groupsearch'].elements['group_query'].focus();
</script>";
//<!--	Extra commandes	-->
//<div class='row'>&nbsp;</div>
//<div class='row'>
//	<input type='submit' class='bouton' value='$msg[909]' onClick='document.location=\"./circ.php?categ=groups&action=create\"' />
//	</div>
//";

// form edition/modification du group
$group_form = "
<script type='text/javascript'>
<!--
	function test_form(form) {
		if(form.group_name.value.length == 0) {
			alert(\"$msg[915]\");
		    form.group_name.focus();
			return false;
		}
	return true;
	}
    function confirm_delete() {
        result = confirm(\"${msg[931]}\");
        if(result) document.location = \"./circ.php?categ=groups&action=delgroup&groupID=!!groupID!!\";
    }
-->
</script>
<form class='form-$current_module' name='group_form' method='post' action='./circ.php?categ=groups&action=update'>
<h3>!!titre!!</h3>
<div class='form-contenu'>
	<div class='row'>
		<div class=colonne2>
			<div class='row'>	
				<label class='etiquette' for='group_name'>$msg[911]</label>
			</div>
			<div class='row'>
				<input class='saisie-50em' type='text' id='group_name' name='group_name' value='!!group_name!!' />
			</div>
		</div>
		<div class=colonne_suite>
			<div class='row'>	
				<label class='etiquette' for='lettre_rappel'>$msg[group_lettre_rappel]</label>
			</div>
			<div class='row'>
				<input type='checkbox' id='lettre_rappel' name='lettre_rappel' !!lettre_rappel!! value='1' />
			</div>
		</div>
	</div>
	<div class='row'>
		<label class='etiquette' for='libelle_resp'>$msg[913]</label>
	</div>
	<div class='row'>
		<input class='saisie-50emr' type='text' id='libelle_resp' name='libelle_resp' value='!!nom_resp!!' size='33' />
		<input class='bouton' type='button' onclick=\"openPopUp('./select.php?what=emprunteur&caller=group_form&param1=respID&param2=libelle_resp', 'select_empr', 400, 400, -2, -2, '$select2_prop')\" title=\"$msg[grp_liste]\" value='$msg[parcourir]' />
		<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.libelle_resp.value=''; this.form.respID.value='0'; \" />
	</div>
	</div>
<div class='row'>
	<div class='left'>
		<input type='hidden' value='!!respID!!' name='respID' id='respID' />
		<input type='hidden' value='!!groupID!!' name='groupID' id='groupID' />
		<input type=\"button\" class=\"bouton\" value=\"${msg[76]}\" onClick=\"document.location='!!link_annul!!';\">
		<input type=\"submit\" class=\"bouton\" value=\"${msg[77]}\" onClick=\"return test_form(this.form)\">
	</div>
	<div class='right'>
		<!-- bouton_suppression -->
	</div>
</div>
<div class='row'></div>
</form>
<script type=\"text/javascript\">
document.forms['group_form'].elements['group_name'].focus();
</script>
";

// $group_list_tmpl : template pour la liste des groupes
$group_list_tmpl = "
<div class='row'>
	$msg[916] <b>!!cle!!</b>
	</div>
<div class='row'>
	<table border='0' width='100%'>
		!!list!!
		</table>
	</div>
<div class='row'>
	<div align='center'>
		!!nav_bar!!
		</div>
	</div>
";

// $group_form_add_membre : form d'ajout de membres dans un groupe
$group_form_add_membre = "
</div>
<script type=\"text/javascript\">
<!--
	function test_form(form)
	{
		if(form.memberID.value == 0)
			{
				alert(\"$msg[926]\");
				return false;
			}
		return true;
	}
-->
</script>
<form class='form-$current_module' name=\"addform\" method=\"post\" action=\"./circ.php?categ=groups&action=addmember&groupID=!!groupID!!\">
<h3>$msg[924]</h3>
<div class='form-contenu'>
	<div class='row'>
		<input type=\"text\" class='saisie-80emr' name=\"libelle_member\" readonly value=\"\" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=emprunteur&caller=addform&param1=memberID&param2=libelle_member&auto_submit=YES', 'select_empr', 400, 400, -2, -2, '$select2_prop')\" />
		<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.libelle_member.value=''; this.form.memberID.value='0'; \" />
		<input type=\"hidden\" value=\"0\" name=\"memberID\" />
		</div>
	</div>
<div class='row'>
	<input type=\"submit\" class=\"bouton\" value=\"${msg[925]}\" onClick=\"return test_form(this.form)\" />
	</div>
</form>
";
