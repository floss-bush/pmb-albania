<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: searcher_templates.tpl.php,v 1.21 2010-04-22 07:37:05 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// page de switch recherche notice

//	Auteur/Titre
$NOTICE_author_query = "
<script type='text/javascript'>
    function aide_regex()
      {
            var fenetreAide;
            var prop = 'scrollbars=yes, resizable=yes';
            fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, prop);
      }

function test_form(form) {
	if ((form.ex_query.value.length == 0) && (form.title_query.value.length == 0) && (form.author_query.value.length == 0) && (form.all_query.value.length == 0)) {
		form.all_query.value='*';
		return true;
		}
	if ((form.ex_query.value.length != 0) && ((form.title_query.value.length != 0) || (form.author_query.value.length != 0) || (form.all_query.value.length != 0))) {
		if (confirm('$msg[1917]')) {
                  	form.title_query.value = '';
                  	form.all_query.value = '';
                  	form.author_query.value = '';
			return true;
			} else {
                  		return false;
                  		}
		}
	return true;
	}
</script>
<form class='form-$current_module' id='NOTICE_author_query' name='NOTICE_author_query' method='post' action='!!base_url!!' onSubmit='return test_form(this)'>
<h3>$msg[354]</h3>
<div class='form-contenu'>
<div class='row'>
	<label class='etiquette' for='all_query'>$msg[global_search]</label>
	</div>
	<div class='colonne'>
		<div class='row'>
			<input class='saisie-80em' type='text' value='!!all_query!!' name='all_query' id='all_query' />
		</div>
	</div>
	!!docnum_query!!
<div class='row'>
	<label class='etiquette' for='title_query'>$msg[233]</label>
	</div>
<div class='row'>
	<input class='saisie-80em' type='text' value='!!title_query!!' name='title_query' id='title_query' />
	</div>

<div class='row'>
	<label class='etiquette' for='author_query'>$msg[234]</label>
	</div>
<div class='row'>
	<input class='saisie-80em' id='author_query' type='text' value='!!author_query!!' size='36' name='author_query' />
	</div>
<div class='row'>
			<span class='saisie-contenu'>
				$msg[155]&nbsp;<a class='aide' title='$msg[1900]$msg[1901]$msg[1902]' href='./help.php?whatis=regex' onclick='aide_regex();return false;'>$msg[1550]</a>
				</span>
			</div>
<div class='colonne2'>
	<div class='row'>
	<label for='typdoc-query'>$msg[17]$msg[1901]</label>
	</div>
	<select id='typdoc-query' name='typdoc_query'>
		!!typdocfield!!
	</select>
	</div>
<div class='colonne_suite'>
	<div class='row'>
	<label for='statut-query'>$msg[noti_statut_noti]</label>
	</div>
	<select id='statut-query' name='statut_query'>
		!!statutfield!!
	</select>
</div>

<div class='colonne2'>
	<div class='row'>
		<label class='etiquette' for='ex_query'>$msg[940]</label>
	</div>
	<div class='row'>
			<input class='saisie-80em' type='text' name='ex_query' id='ex_query' value='!!ex_query!!'/>
	</div>
</div>";
if($pmb_show_notice_id)
	$NOTICE_author_query .= "
		<div class='colonne_suite'>
			<div class='row'>
				<label class='etiquette' for='f_notice_id'>".$msg['notice_id_libelle']."</label>
			</div>
			<div class='row'>
				<input class='saisie-30em' type='text' name='f_notice_id' id='f_notice_id' />
			</div>	
		</div>";
$NOTICE_author_query .= "
		<div class='row'>&nbsp;</div>
	</div>";
$NOTICE_author_query .= "
<!--	Bouton Rechercher	-->
<div class='row'>
	<input type='submit' class='bouton' value='$msg[142]' />
	</div>
<input type='hidden' name='etat' value='first_search'/>
</form>

<script type='text/javascript'>
      document.forms['NOTICE_author_query'].elements['all_query'].focus();
</script>
";

//Index/Sujet
$select3_prop = "scrollbars=yes, toolbar=no, dependent=yes, width=400, height=320, resizable=yes";

$search_form_categ = "
<form class='form-$current_module' name='subject_search_form' method='post' action='!!base_url!!'>
<h3>$msg[355]</h3>
	<div class='form-contenu'>

		<div class='row'>
			<div class='colonne'>
				<!-- sel_thesaurus -->		
				<input type='text' class='saisie-50em' name='search_subject' value='".htmlentities(stripslashes($search_subject),ENT_QUOTES,$charset)."' />
			</div>	
		</div>

		<div class='row'>
			<span class='saisie-contenu'>
				$msg[155]&nbsp;<a class='aide' title='$msg[1900]$msg[1901]$msg[1902]' href='./help.php?whatis=regex' onclick='aide_regex();return false;'>$msg[1550]</a>
			</span>
		</div>

		<!-- sel_langue -->

		<!--	Indexation interne	-->
		<div class='row'>
			<label for='f_indexint' class='etiquette'>$msg[indexint_catal_title]</label>
			</div>
		<div class='row'>
			<input type='text' class='saisie-50em' name='search_indexint' value=\"".htmlentities(stripslashes($search_indexint),ENT_QUOTES,$charset)."\" size='54' onChange=\"this.form.search_indexint_id.value='0';\"/>
			<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=indexint&caller=subject_search_form&param1=search_indexint_id&param2=search_indexint&parent=0&bt_ajouter=no', 'select_indexint', 600, 320, -2, -2, '$select3_prop')\" />
			<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.search_indexint.value=''; this.form.search_indexint_id.value='0'; \" />
			<input type='hidden' id='search_indexint_id' name='search_indexint_id' value='".htmlentities(stripslashes($search_indexint_id),ENT_QUOTES,$charset)."' />
		</div>
	</div>

	<!--	Bouton Rechercher -->
	<div class='row'>
		<input type='submit' class='bouton' value='$msg[142]' />
		</div>
		<input type='hidden' name='etat' value='first_search'/>
	</form>
	<script type='text/javascript'>
		document.forms['subject_search_form'].elements['search_subject'].focus();
		function aide_regex()
			{
				var fenetreAide;
				var prop = 'scrollbars=yes, resizable=yes';
				fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, prop);
			}
		</script>
	<br />";
	$browser="
	<div class='row'>
		<iframe name=\"collection_browser\" frameborder=\"0\" scrolling=\"yes\" width=\"100%\" height=\"300\" src=\"!!browser_url!!\">
	</div>";
	
	//Editeur collection
	$search_form_editeur = "
	<form class='form-$current_module' name='ed_search_form' method='post' action='!!base_url!!'>
	<h3>$msg[356]</h3>
	<div class='form-contenu'>
		<div class='row'>
			<input type='text' class='saisie-50em' name='search_ed' value='".htmlentities(stripslashes($search_ed),ENT_QUOTES,$charset)."'>
			</div>
		<div class='row'>
			<span class='saisie-contenu'>
				$msg[155]&nbsp;<a class='aide' title='$msg[1900]$msg[1901]$msg[1902]' href='./help.php?whatis=regex' onclick='aide_regex();return false;'>$msg[1550]</a>
				</span>
			</div>
		</div>
	<!--	Bouton Rechercher	-->
	<div class='row'>
		<input type='submit' class='bouton' value='$msg[142]' />
		</div>
		<input type='hidden' name='etat' value='first_search'/>
	</form>
	<script type='text/javascript'>
		document.forms['ed_search_form'].elements['search_ed'].focus()
		function aide_regex()
			{
				var fenetreAide;
				var prop = 'scrollbars=yes, resizable=yes';
				fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, prop);
			}
		</script>
	<br />";
	$browser_editeur="<iframe name=\"collection_browser\" frameborder=\"0\" scrolling=\"yes\" width=\"100%\" height=\"300\" src=\"!!browser_url!!\">
	";
	
	//titre uniforme
	$search_form_titre_uniforme = "
	<form class='form-$current_module' name='tu_search_form' method='post' action='!!base_url!!'>
	<h3>".$msg["search_by_titre_uniforme"]."</h3>
	<div class='form-contenu'>
		<div class='row'>
			<input type='text' class='saisie-50em' name='search_tu' value='".htmlentities(stripslashes($search_tu),ENT_QUOTES,$charset)."'>
			</div>
		<div class='row'>
			<span class='saisie-contenu'>
				$msg[155]&nbsp;<a class='aide' title='$msg[1900]$msg[1901]$msg[1902]' href='./help.php?whatis=regex' onclick='aide_regex();return false;'>$msg[1550]</a>
				</span>
			</div>
		</div>
	<!--	Bouton Rechercher	-->
	<div class='row'>
		<input type='submit' class='bouton' value='$msg[142]' />
		</div>
		<input type='hidden' name='etat' value='first_search'/>
	</form>
	<script type='text/javascript'>
		document.forms['tu_search_form'].elements['search_tu'].focus()
		function aide_regex() {
			var fenetreAide;
			var prop = 'scrollbars=yes, resizable=yes';
			fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, prop);
		}
		</script>
	<br />";
	$browser_titre_uniforme="<iframe name=\"titre_uniforme_browser\" frameborder=\"0\" scrolling=\"yes\" width=\"100%\" height=\"300\" src=\"!!browser_url!!\">";
?>