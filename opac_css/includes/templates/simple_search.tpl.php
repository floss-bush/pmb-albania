<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: simple_search.tpl.php,v 1.39 2009-11-30 16:55:02 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], "simple_search.tpl.php")) die("no access");

// template for PMB OPAC
switch ($search_type) {
	// éléments pour la recherche simple
	case "simple_search":
		$search_input = "
			<div id=\"search\">\n
			<ul class='search_tabs'>
				<li id='current'>".$msg["simple_search"]."</li>
				!!others!!".
				($opac_show_onglet_help ? "<li><a href=\"./index.php?lvl=infopages&pagesid=$opac_show_onglet_help\">".$msg["search_help"]."</a></li>": '')."
			</ul>
			<div id='search_crl'></div>\n
			<p class=\"p1\"><span>$msg[simple_search_tpl_text]</span></p>\n
			<div class='row'>\n
			<form name='search_input' action='./index.php?lvl=search_result' method='post' onSubmit=\"if (search_input.user_query.value.length == 0) { search_input.user_query.value='*'; return true; }\">\n
				<!--!!typdoc_field!!-->\n
				<input type='hidden' name='surligne' value='!!surligne!!'/>
				<input type='text' name='user_query' class='text_query' value=\"!!user_query!!\" size='65' />\n
				<input type='submit' name='ok' value='".$msg["142"]."' class='boutonrechercher'/>\n";
		if ($opac_show_help) $search_input .= "<input type='button' value='$msg[search_help]' class='bouton' onClick='window.open(\"$base_path/help.php?whatis=simple_search\", \"search_help\", \"scrollbars=yes, toolbar=no, dependent=yes, width=400, height=400, resizable=yes\"); return false' />\n";
		$search_input .= "		<!--!!ou_chercher!!-->\n
			</form>\n
			</div>\n
			<script type='text/javascript'>\n
				document.search_input.user_query.focus();\n
				</script>\n	
		</div>";
		break;
	case "external_search":
		$search_input = "
			<ul class='search_tabs'>
				!!others!!
				<li id='current'>".$msg["connecteurs_external_search"]."</li>".
				($opac_show_onglet_help ? "<li><a href=\"./index.php?lvl=infopages&pagesid=$opac_show_onglet_help\">".$msg["search_help"]."</a></li>": '')."
			</ul>
			<div id='search_crl'></div>\n
			<p class=\"p1\"><span>".sprintf($msg["connecteurs_search_multi"],"./index.php?search_type_asked=external_search&external_type=multi")."</span></p>\n
			<div class='row'>\n
			<form name='search_input' action='./index.php?lvl=search_result&search_type_asked=external_search' method='post' onSubmit=\"if (search_input.user_query.value.length == 0) { search_input.user_query.value='*'; return true; }\">\n
				<!--!!typdoc_field!!--><br />\n
				<input type='hidden' name='surligne' value='!!surligne!!'/>
				<input type='text' name='user_query' class='text_query' value=\"!!user_query!!\" size='65' />\n
				<input type='submit' name='ok' value='".$msg["142"]."' class='boutonrechercher'/>\n";
			if ($opac_show_help) $search_input .= "<input type='button' value='$msg[search_help]' class='bouton' onClick='window.open(\"$base_path/help.php?whatis=simple_search\", \"search_help\", \"scrollbars=yes, toolbar=no, dependent=yes, width=400, height=400, resizable=yes\"); return false' />\n";
			$search_input .= "<!--!!ou_chercher!!-->\n
				<br /><a href='javascript:expandAll()'><img class='img_plusplus' src='./images/expand_all.gif' border='0' id='expandall'></a>&nbsp;<a href='javascript:collapseAll()'><img class='img_moinsmoins' src='./images/collapse_all.gif' border='0' id='collapseall'></a>
				<div id='external_simple_search_zone'><!--!!sources!!--></div>
			</form>\n
			</div>\n
			<script type='text/javascript'>\n
				document.search_input.user_query.focus();\n
				
			function change_source_checkbox(changing_control, source_id) {
				var i=0; var count=0;
				onoff = changing_control.checked;
				for(i=0; i<document.search_input.elements.length; i++)
				{
					if(document.search_input.elements[i].name == 'source[]')	{
						if (document.search_input.elements[i].value == source_id)
							document.search_input.elements[i].checked = onoff;
					}
				}	
			}
				</script>\n	";
		break;
	case "tags_search":
		$search_input = "
		<div id=\"search\">\n
			<ul class='search_tabs'>!!others!!".
				($opac_show_onglet_help ? "<li><a href=\"./index.php?lvl=infopages&pagesid=$opac_show_onglet_help\">".$msg["search_help"]."</a></li>": '')."
			</ul>
			<div id='search_crl'></div>\n
			<p class=\"p1\"><span>$msg[tags_search_tpl_text]</span></p>\n
			<div class='row'>\n
			<form name='search_input' action='./index.php?lvl=search_result&search_type_asked=tags_search' method='post' onSubmit=\"if (search_input.user_query.value.length == 0) { search_input.user_query.value='*'; return true; }\">\n
				<!--!!typdoc_field!!--><br />\n
				<input type='text' name='user_query' class='text_query' value=\"!!user_query!!\" size='65' />\n
				<input type='submit' name='ok' value='".$msg["142"]."' class='boutonrechercher'/>\n
			</form>\n
			</div>\n
			<script type='text/javascript'>\n
				document.search_input.user_query.focus();\n
				</script>\n	
		</div>";
		break;

	case "connect_empr":
		$search_input = "
			<div id=\"search\">\n
				<ul class='search_tabs'>!!others!!".
					($opac_show_onglet_help ? "<li><a href=\"./index.php?lvl=infopages&pagesid=$opac_show_onglet_help\">".$msg["search_help"]."</a></li>": '')."
				</ul><div id='search_crl'></div>\n
				<p class=\"p1\">&nbsp;</p>\n
				<div class='row'>\n
				!!account_or_form_empr_connect!!
				</div>\n
			</div>";
		break;		
	case "search_perso":
		$search_input = "
			<div id=\"search\">\n
				<ul class='search_tabs'>!!others!!".
					($opac_show_onglet_help ? "<li><a href=\"./index.php?lvl=infopages&pagesid=2\">".$msg["search_help"]."</a></li>": '')."
				</ul>
				<div id='search_crl'></div>\n
				<p class=\"p1\">&nbsp;</p>\n
				<div class='row'>\n
				!!contenu!!
				</div>\n
			</div>";
		break;		
}