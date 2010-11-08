<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: iimport_expl.php,v 1.62 2009-12-22 15:53:44 mbertin Exp $

// définition du minimum necessaire
$base_path="../..";
$base_auth = "ADMINISTRATION_AUTH";
$base_title = "";
require_once ("$base_path/includes/init.inc.php");

// les requis par iimport_expl.php ou ses sous modules
require_once ("$include_path/isbn.inc.php");
require_once ("$include_path/marc_tables/$lang/empty_words");
require_once ("$class_path/iso2709.class.php");
require_once ("$class_path/author.class.php");
require_once ("$class_path/serie.class.php");
require_once ("$class_path/editor.class.php");
require_once ("$class_path/collection.class.php");
require_once ("$class_path/subcollection.class.php");
require_once ("$class_path/expl.class.php");
require_once ("$class_path/lender.class.php");
require_once ("$class_path/docs_type.class.php");
require_once ("$class_path/docs_section.class.php");
require_once ("$class_path/docs_statut.class.php");
require_once ("$class_path/docs_location.class.php");
require_once ("$class_path/docs_codestat.class.php");
require_once ("$class_path/indexint.class.php");
require_once ("$class_path/origine_notice.class.php");
require_once ("$class_path/marc_table.class.php");
require_once ("$class_path/lender.class.php");
require_once ("$class_path/notice.class.php");
require_once ("$class_path/titre_uniforme.class.php");

require_once("import_func.inc.php");
if (!$pmb_import_modele) {
	require_once("func_bdp.inc.php");
} else {
	require_once($pmb_import_modele);
}

print "<div id='contenu-frame'>" ;

$nom_fichier_transfert_ftp = "unimarc".(defined("LOCATION")?"_".constant("LOCATION"):"").".fic";

$tmp_file = $_FILES['userfile']['tmp_name'];
if (!isset($from_file)) $from_file = $_FILES['userfile']['name'];
$to_file = $base_path.'/temp/'.basename($tmp_file);

if ($sub == "import_expl") {
	echo window_title($msg[520].$msg[1003].$msg[1001]);
	/* the name of the lender is read in the table */
	if ($book_lender_id!="") {
		$sql_rech="select lender_libelle from lenders where idlender = '".$book_lender_id."' ";
		$sql_result_rech = mysql_query($sql_rech) or die ("Couldn't select lenders ! = ".$sql_rech);
		if (mysql_num_rows($sql_result_rech)==0) {
			$book_lender_name = $msg[561];
		} else {
			$book_lender_name = mysql_result ($sql_result_rech,0,"lender_libelle");
		}
	} else {
		$book_lender_name = $msg[561];
	}
} else {
	echo window_title($msg[500].$msg[1003].$msg[1001]);
}

switch ($action) {
	case 'beforeupload':
		if ($sub == "import_expl") {
			$book_lender_id = "";
			$book_statut_id = "";
			print $tpl_beforeupload_expl ;
		} else {
			// import de notice
			 print $tpl_beforeupload_notices ;
		}
	break;
	case 'afterupload':
		if (!$statutnot) $statutnot = 1 ;
		if ($sub == "import_expl") {
			if ($book_lender_id==0 || $book_lender_id=="") {
				print $msg[561];
				break;
			}
			if ($book_statut_id==0 || $book_statut_id=="") {
				print $msg[561];
				break;
			}
			if ($tmp_file=="") {
				printf ($msg[503],$from_file); /* wrong permissions to copy the file %s ... Contact your admin... */
				break;
			}
			if (!move_uploaded_file($tmp_file,$to_file)) {
				printf ($msg[504],$from_file); /* Fail to copy %s, Contact your admin... */
			} else {
				printf ($msg[505],$from_file); /* File transfered, Loading is about to go on */
				print "<form class='form-$current_module' NAME=\"afterupload\" METHOD=\"post\" ACTION=\"iimport_expl.php\">";
				print "<INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />";
				print "<INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import_expl\" />";
				print "<INPUT NAME=\"action\" TYPE=\"hidden\" value=\"preload\" />";
				print "<INPUT NAME=\"book_lender_id\" TYPE=\"hidden\" value=\"".$book_lender_id."\" />";
				print "<INPUT NAME=\"book_statut_id\" TYPE=\"hidden\" value=\"".$book_statut_id."\" />";
				print "<input type=\"hidden\" name=\"file_submit\" value=\"$to_file\" />";
				print "<input type=\"hidden\" name=\"from_file\" value=\"$from_file\" />";
				print "<INPUT TYPE=\"hidden\" NAME=\"isbn_mandatory\" VALUE=\"$isbn_mandatory\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"isbn_only\" VALUE=\"$isbn_only\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"isbn_dedoublonnage\" VALUE=\"$isbn_dedoublonnage\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"cote_mandatory\" VALUE=\"$cote_mandatory\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"tdoc_codage\" VALUE=\"$tdoc_codage\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"statisdoc_codage\" VALUE=\"$statisdoc_codage\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"sdoc_codage\" VALUE=\"$sdoc_codage\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"statutnot\" VALUE=\"$statutnot\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"book_location_id\" VALUE=\"$book_location_id\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"que_faire\" VALUE=\"$que_faire\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"link_generate\" VALUE=\"$link_generate\" />\n";
				print "</FORM>";
				print "<SCRIPT>setTimeout(\"document.afterupload.submit()\",2000);</SCRIPT>";
			}
		} else {
			// import de notice
			if ($to_file=="") {
			    printf ($msg[503],$from_file); /* wrong permissions to copy the file %s ... Contact your admin... */
			    break;
			}
 			if (!move_uploaded_file($tmp_file,$to_file)) {
			    printf ($msg[504],$from_file); /* Fail to copy %s, Contact your admin... */
			} else {
				printf ($msg[505],$from_file); /* File transfered, Loading is about to go on */
				print "<form class='form-$current_module' NAME=\"afterupload\" METHOD=\"post\" ACTION=\"iimport_expl.php\">";
				print "<INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />";
				print "<INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import\" />";
				print "<INPUT NAME=\"action\" TYPE=\"hidden\" value=\"preload\" />";
				print "<input type=\"hidden\" name=\"file_submit\" value=\"$to_file\" />";
				print "<input type=\"hidden\" name=\"from_file\" value=\"$from_file\" />";
				print "<INPUT TYPE=\"hidden\" NAME=\"isbn_mandatory\" VALUE=\"$isbn_mandatory\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"isbn_dedoublonnage\" VALUE=\"$isbn_dedoublonnage\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"isbn_only\" VALUE=\"$isbn_only\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"statutnot\" VALUE=\"$statutnot\" />\n";
				print "<INPUT TYPE=\"hidden\" NAME=\"link_generate\" VALUE=\"$link_generate\" />\n";
				print "</FORM>";
				print "<SCRIPT>setTimeout(\"document.afterupload.submit()\",2000);</SCRIPT>";
			}
		}
		break;
	case 'preload':
		if ($sub == "import_expl") {
			/* Does the file exist ? */
			if ($file_submit=="") {
				/* l'utilisateur n'est pas passé par le téléchargement du fichier */
				$filename = $base_path."/admin/import/unimarc".(defined("LOCATION")?"_".constant("LOCATION"):"").".fic";
				$from_file = "unimarc".(defined("LOCATION")?"_".constant("LOCATION"):"").".fic";
			} else {
				$filename=$file_submit;
			}
			if ($book_lender_id=="" || $book_statut_id=="" || $isbn_mandatory=="" || $cote_mandatory=="" || $book_location_id=="" || $statutnot=="") {
				/* l'utilisateur n'est pas passé par le téléchargement du fichier, il faut qu'il choisisse un prêteur s'il n'en a pas communiqué auparavant */
				print "
					<form class='form-$current_module' NAME=\"preload\" METHOD=\"post\" ACTION=\"iimport_expl.php\">
						<h3>".$msg['import_expl_form_titre']."</h3>
						<div class='form-contenu'>
						<div class='row'>
							<div class='colonne2'>
								<label class='etiquette' for='isbn_obligatoire'>$msg[564]</label><br />
								<INPUT TYPE='radio' NAME='isbn_mandatory' id='io1' VALUE='1' CLASS='radio' /><label for='io1'> $msg[40] </label>
								<INPUT TYPE='radio' NAME='isbn_mandatory' id='io0' VALUE='0' CLASS='radio' checked='checked' /><label for='io0'> $msg[39] </label>
							</div>
							<div class='colonne-suite'>
								<label class='etiquette' for='isbn_dedoublonnage'>$msg[568]</label><br />
								<INPUT TYPE='radio' NAME='isbn_dedoublonnage' id='di1' VALUE='1' CLASS='radio' checked='checked' /><label for='di1'> $msg[40] </label>
								<INPUT TYPE='radio' NAME='isbn_dedoublonnage' id='di0' VALUE='0' CLASS='radio' /><label for='di0'> $msg[39] </label>
								<input type='checkbox' name='isbn_only' id='ionly' value='1'/><label for='ionly'> ".$msg["ignore_issn"]." </label>
							</div>
						</div>
						<div class='row'>&nbsp;</div>
						<div class='row'>
	                        <div class='colonne2'>	
	                    		<label class='etiquette' for='statutnot'>$msg[import_statutnot]</label>
	                    		<div>
	                    		".gen_liste_multiple ("select id_notice_statut, gestion_libelle from notice_statut order by 2", "id_notice_statut", "gestion_libelle", "id_notice_statut", "statutnot", "", 1, "", "","","",0)."
	                    		</div>
	                    	</div>
	                    	<div class='colonne-suite'>
	                    		<label class='etiquette' for='generer_lien'>".$msg['import_genere_liens']."</label><br />
	                    		<INPUT TYPE='radio' NAME='link_generate' id='link1' VALUE=' 1' CLASS='radio' /><label for='link1'> $msg[40] </label>
	                            <INPUT TYPE='radio' NAME='link_generate' id='link0' VALUE='0' CLASS='radio' checked='checked' /><label for='link0'> $msg[39] </label>
	                    	</div>
                    	</div>
						<div class='row'><hr /></div>
						<div class='row'>
                            <label class='etiquette' for='prêteur statut'>$msg[560]</label>
                            </div>
                        <div class='row'>".
                            lender::gen_combo_box($book_lender_id)."&nbsp;&nbsp;".
                            docs_statut::gen_combo_box($book_statut_id)."
                            </div>
	                    <div class='row'>
    	                    <label class='etiquette' for='localisation'>$msg[import_localisation]</label>
        	                </div>
            	        <div class='row'>".
                	        docs_location::gen_combo_box($deflt_docs_location)."
                    	    </div>
                        <div class='row'><hr /></div>
                    	<div class='row'>
                            <label class='etiquette' for='cote_obligatoire'>$msg[566]</label>
                            </div>
                        <div class='row'>
                            <INPUT TYPE='radio' NAME='cote_mandatory' VALUE='1' CLASS='radio' /> $msg[40]
                            <INPUT TYPE='radio' NAME='cote_mandatory' VALUE='0' CLASS='radio' checked='checked' /> $msg[39]
                            </div>
                        <div class='row'><hr /></div>
                        <div class='row'>
                            <label class='etiquette'>$msg[17]</label>
                            </div>
                        <div class='row'>
                            <INPUT TYPE='radio' NAME='tdoc_codage' VALUE='1' CLASS='radio' /> ".$msg["import_expl_codage_proprio"]."
                            <INPUT TYPE='radio' NAME='tdoc_codage' VALUE='0' CLASS='radio' checked='checked' /> ".$msg["import_expl_codage_generique"]."
                            </div>
                        <div class='row'>
                            <label class='etiquette'>$msg[24]</label>
                            </div>
                        <div class='row'>
                            <INPUT TYPE='radio' NAME='statisdoc_codage' VALUE='1' CLASS='radio' /> ".$msg["import_expl_codage_proprio"]."
                            <INPUT TYPE='radio' NAME='statisdoc_codage' VALUE='0' CLASS='radio' checked='checked' /> ".$msg["import_expl_codage_generique"]."
                            </div>
                        <div class='row'>
                            <label class='etiquette'>$msg[19]</label>
                            </div>
                        <div class='row'>
                            <INPUT TYPE='radio' NAME='sdoc_codage' VALUE='1' CLASS='radio' /> ".$msg["import_expl_codage_proprio"]."
                            <INPUT TYPE='radio' NAME='sdoc_codage' VALUE='0' CLASS='radio' checked='checked' /> ".$msg["import_expl_codage_generique"]."
                            </div>
                        <div class='row'>
                            <label class='etiquette'>$msg[21]</label>
                            </div>
                        </div>
                    <INPUT TYPE=\"SUBMIT\"  class='bouton' NAME=\"upload\" VALUE=\"".$msg[502]."\" />
                    <INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />
                    <INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import_expl\" />
                    <INPUT NAME=\"action\" TYPE=\"hidden\" value=\"preload\" />
                    </FORM>";
                break;
			}
            loadfile_in_table() ;
            if ($pb_fini=="EOF") {
                $formulaire="<form class='form-$current_module' name=\"load\" method=\"post\" action=\"iimport_expl.php\">";
                $formulaire.="<INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />";
                $formulaire.="<INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import_expl\" />";
                $formulaire.="<INPUT NAME=\"action\" TYPE=\"hidden\" value=\"load\" />";
                $formulaire.="<INPUT NAME=\"book_lender_id\" TYPE=\"hidden\" value=\"".$book_lender_id."\" />";
                $formulaire.="<INPUT NAME=\"book_statut_id\" TYPE=\"hidden\" value=\"".$book_statut_id."\" />";
                $formulaire.="<input type=\"hidden\" name=\"filename\" value=\"$filename\" />";
                $formulaire.="<input type=\"hidden\" name=\"from_file\" value=\"$from_file\" />";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_mandatory\" VALUE=\"$isbn_mandatory\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_dedoublonnage\" VALUE=\"$isbn_dedoublonnage\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_only\" VALUE=\"$isbn_only\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"cote_mandatory\" VALUE=\"$cote_mandatory\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"tdoc_codage\" VALUE=\"$tdoc_codage\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"statisdoc_codage\" VALUE=\"$statisdoc_codage\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"sdoc_codage\" VALUE=\"$sdoc_codage\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"statutnot\" VALUE=\"$statutnot\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"book_location_id\" VALUE=\"$book_location_id\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"recharge\" value=\"YES\" />";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"que_faire\" VALUE=\"$que_faire\" />\n";
                $formulaire.= "<INPUT TYPE=\"hidden\" NAME=\"link_generate\" VALUE=\"$link_generate\" />\n";
                $formulaire.="</form>";
                printf ($msg[512], $from_file); /* File %s... . End of preload... */
                
                $fo = fopen("$base_path/temp/liste_id".SESSid.".txt","w");
				fwrite($fo,"");
				fclose($fo);
				//file_put_contents("$base_path/temp/liste_id".SESSid.".txt","");
                $script = "<script>setTimeout(\"document.load.submit()\",2000);</script>";
			} else {
				$formulaire="<form class='form-$current_module' name=\"preload\" method=\"post\" action=\"iimport_expl.php\">";
				$formulaire.="<INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />";
				$formulaire.="<INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import_expl\" />";
				$formulaire.="<INPUT NAME=\"action\" TYPE=\"hidden\" value=\"preload\" />";
				$formulaire.="<INPUT NAME=\"book_lender_id\" TYPE=\"hidden\" value=\"".$book_lender_id."\" />";
				$formulaire.="<INPUT NAME=\"book_statut_id\" TYPE=\"hidden\" value=\"".$book_statut_id."\" />";
				$formulaire.="<input type=\"hidden\" name=\"recharge\" value=\"$recharge\" />";
				$formulaire.="<input type=\"hidden\" name=\"file_submit\" value=\"$filename\" />";
				$formulaire.="<input type=\"hidden\" name=\"from_file\" value=\"$from_file\" />";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_mandatory\" VALUE=\"$isbn_mandatory\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_dedoublonnage\" VALUE=\"$isbn_dedoublonnage\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_only\" VALUE=\"$isbn_only\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"cote_mandatory\" VALUE=\"$cote_mandatory\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"tdoc_codage\" VALUE=\"$tdoc_codage\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"statisdoc_codage\" VALUE=\"$statisdoc_codage\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"sdoc_codage\" VALUE=\"$sdoc_codage\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"statutnot\" VALUE=\"$statutnot\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"book_location_id\" VALUE=\"$book_location_id\" />\n";
				$formulaire.="<input type=\"hidden\" name=\"noticenumber\" value=\"".($noticenumber+$j)."\" />";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"que_faire\" VALUE=\"$que_faire\" />\n";
				$formulaire.= "<INPUT TYPE=\"hidden\" NAME=\"link_generate\" VALUE=\"$link_generate\" />\n";
				$formulaire.="</form>";
				$script = "<script>setTimeout(\"document.preload.submit()\",2000);</script>";
			}
            print $formulaire;
            print $script;
		} else {
		    // import de notice
		    /* Does the file exist ? */
		    if ($file_submit=="") {
		    	$filename = $base_path."/admin/import/unimarc".(defined("LOCATION")?"_".constant("LOCATION"):"").".fic";
		    	$from_file = "unimarc".(defined("LOCATION")?"_".constant("LOCATION"):"").".fic";
		    } else {
		    	$filename=$file_submit;
		    }
		
		    if ($isbn_mandatory=="") {
		        /* l'utilisateur n'est pas passé par le téléchargement du fichier, il faut qu'il nous dise si l'ISBN est obligatoire */
		        print "
		            <form class='form-$current_module' NAME=\"preload\" METHOD=\"post\" ACTION=\"iimport_expl.php\">
		            <h3>".$msg['import_noti_form_titre']."</h3>
		            <div class='form-contenu'>
		        	<div class='row'>
		        	    <div class='colonne2'>
		    	            <label class='etiquette' for='isbn_obligatoire'>$msg[564]</label><br />
			                <INPUT TYPE='radio' NAME='isbn_mandatory' id='io1' VALUE='1' CLASS='radio' /><label for='io1'> $msg[40] </label>
		                    <INPUT TYPE='radio' NAME='isbn_mandatory' id='io0' VALUE='0' CLASS='radio' checked='checked' /><label for='io0'> $msg[39] </label>
		                </div>
		                <div class='colonne-suite'>
		            	    <label class='etiquette' for='isbn_dedoublonnage'>$msg[568]</label><br />
		        	        <INPUT TYPE='radio' NAME='isbn_dedoublonnage' id='di1' VALUE='1' CLASS='radio' checked='checked' /><label for='di1'> $msg[40] </label>
		    	            <INPUT TYPE='radio' NAME='isbn_dedoublonnage' id='di0' VALUE='0' CLASS='radio' /><label for='di0'> $msg[39] </label>
			                <input type='checkbox' name='isbn_only' id='ionly' value='1' /><label for='ionly'> ".$msg["ignore_issn"]." </label>
		                </div>
		             </div>
					<div class='row'>&nbsp;</div>
		            <div class='row'>
                        <div class='colonne2'>	
                    		<label class='etiquette' for='statutnot'>$msg[import_statutnot]</label>
                    		<div>
                    		".gen_liste_multiple ("select id_notice_statut, gestion_libelle from notice_statut order by 2", "id_notice_statut", "gestion_libelle", "id_notice_statut", "statutnot", "", 1, "", "","","",0)."
                    		</div>
                    	</div>
                    	<div class='colonne-suite'>
                    		<label class='etiquette' for='generer_lien'>".$msg['import_genere_liens']."</label><br />
                    		<INPUT TYPE='radio' NAME='link_generate' id='link1' VALUE=' 1' CLASS='radio' /><label for='link1'> $msg[40] </label>
                            <INPUT TYPE='radio' NAME='link_generate' id='link0' VALUE='0' CLASS='radio' checked='checked' /><label for='link0'> $msg[39] </label>
                    	</div>
                    </div>
		            <INPUT TYPE=\"SUBMIT\"  class='bouton' NAME=\"upload\" VALUE=\"".$msg[502]."\" />
		            <INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />
		            <INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import\" />
		            <INPUT NAME=\"action\" TYPE=\"hidden\" value=\"preload\" />
		            </FORM>";
		        break;
		    }
		    loadfile_in_table() ;
			if ($pb_fini=="EOF") {
				$formulaire="<form class='form-$current_module' name=\"load\" method=\"post\" action=\"iimport_expl.php\">";
				$formulaire.="<INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />";
				$formulaire.="<INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import\" />";
				$formulaire.="<INPUT NAME=\"action\" TYPE=\"hidden\" value=\"load\" />";
				$formulaire.="<input type=\"hidden\" name=\"filename\" value=\"$filename\" />";
				$formulaire.="<input type=\"hidden\" name=\"from_file\" value=\"$from_file\" />";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_mandatory\" VALUE=\"$isbn_mandatory\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_dedoublonnage\" VALUE=\"$isbn_dedoublonnage\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_only\" VALUE=\"$isbn_only\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"statutnot\" VALUE=\"$statutnot\" />\n";
				$formulaire.="<input type=\"hidden\" name=\"recharge\" value=\"YES\" />";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"link_generate\" VALUE=\"$link_generate\" />\n";
				$formulaire.="</form>";
				printf ($msg[509].$msg[512], $from_file, $from_file); /* File %s... . End of preload... */
				
				$fo = fopen("$base_path/temp/liste_id".SESSid.".txt","w");
				fwrite($fo,"");
				fclose($fo);
				//file_put_contents("$base_path/temp/liste_id".SESSid.".txt","");
				$script = "<script>setTimeout(\"document.load.submit()\",2000);</script>";
			} else {
				$formulaire="<form class='form-$current_module' name=\"preload\" method=\"post\" action=\"iimport_expl.php\">";
				$formulaire.="<INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />";
				$formulaire.="<INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import\" />";
				$formulaire.="<INPUT NAME=\"action\" TYPE=\"hidden\" value=\"preload\" />";
				$formulaire.="<input type=\"hidden\" name=\"recharge\" value=\"$recharge\" />";
				$formulaire.="<input type=\"hidden\" name=\"file_submit\" value=\"$filename\" />";
				$formulaire.="<input type=\"hidden\" name=\"from_file\" value=\"$from_file\" />";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_mandatory\" VALUE=\"$isbn_mandatory\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_dedoublonnage\" VALUE=\"$isbn_dedoublonnage\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_only\" VALUE=\"$isbn_only\" />\n";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"statutnot\" VALUE=\"$statutnot\" />\n";
				$formulaire.="<input type=\"hidden\" name=\"noticenumber\" value=\"".($noticenumber+$j)."\" />";
				$formulaire.="<INPUT TYPE=\"hidden\" NAME=\"link_generate\" VALUE=\"$link_generate\" />\n";
				$formulaire.="</form>";
				$script = "<script>setTimeout(\"document.preload.submit()\",2000);</script>";
			}
		    print $formulaire;
		    print $script;
		}
        break;
    case 'load':
		if (!$statutnot) $statutnot=1;
		printf ($msg[509], $from_file);
		if ($nbtot_notice=="") {
			$sql = "select count(1) from import_marc where origine='".addslashes(SESSid)."' ";
			$sql_result = mysql_query($sql) or die ("Couldn't select count(1) from import table !");
			$nbtot_notice=mysql_result($sql_result, 0, 0);
       	}
		if ($sub == "import_expl") {
			$section_995_=new marc_list("section_995");
			$section_995=$section_995_->table;
			$typdoc_995_=new marc_list("typdoc_995");
			$typdoc_995=$typdoc_995_->table;
			$codstatdoc_995_=new marc_list("codstatdoc_995");
			$codstatdoc_995=$codstatdoc_995_->table;
			/* let's initialize the counters if necessary */
			if ($nb_expl_ignores == "") $nb_expl_ignores=0;
			printf ($msg[511], "\"".$book_lender_name."\"") ;
		}

        $sql = "select notice, id_import from import_marc where origine='".addslashes(SESSid)."' limit $pmb_import_limit_record_load ";
        $sql_result_import = mysql_query($sql) or die ("Couldn't select import table !");
        $n_notice=mysql_num_rows($sql_result_import);
        if ($notice_deja_presente=="") {
        	$notice_deja_presente=0;
        }
        $inotice=0;
        $txt="";

		while (($notobj = mysql_fetch_object($sql_result_import))) {
            $notice=$notobj->notice ;
            $idnotice_import=$notobj->id_import ;
            $inotice++;
			
            $res_lecture = recup_noticeunimarc($notice) ;
            if($link_generate) $res_link = recup_noticeunimarc_link($notice);
			if (!$res_lecture || !$tit_200a[0]) {
                $res_lecture = 0;
                // ".$inotice."
                $fp = fopen ("../../temp/err_import.unimarc","a+");
                fwrite ($fp, $notice);
                fclose ($fp);
                $notice_rejetee++;
			}
            
			if ($res_lecture) {
                recup_noticeunimarc_suite($notice) ;

                /* We've got everything, let's have a look if ISBN already exists in notices table */
                if($isbn[0]=="NULL") $isbn[0]="";
                // si isbn vide, on va tenter de prendre l'EAN stocké en 345$b
                if ($isbn[0]=="") $isbn[0]=$EAN[0] ;
                // si isbn vide, on va tenter de prendre le serial en 011
                if ($isbn[0]=="") $isbn[0]=$issn_011[0];
                // si ISBN obligatoire et isbn toujours vide :
                if ($isbn_mandatory == 1 && $isbn[0]=="") {
                    // on va tenter de prendre l'ISSN stocké en 225$x
                    $isbn[0]=$collection_225[0]['x'] ;
                    // si isbn toujours vide, on va tenter de prendre l'ISSN stocké en 410$x
                    if ($isbn[0]=="") $isbn[0]=$collection_410[0]['x'] ;
                }

				// on commence par voir ce que le code est (basé sur la recherche par code du module catalogage 
				$ex_query = clean_string($isbn[0]);
				
				$EAN = '';
				$isbn = '';
				$code = '';
				$code10 = '' ;
				
				if(isEAN($ex_query)) {
					// la saisie est un EAN -> on tente de le formater en ISBN
					$EAN=$ex_query;
					$isbn = EANtoISBN($ex_query);
					// si échec, on prend l'EAN comme il vient
					if(!$isbn) 
						$code = str_replace("*","%",$ex_query);
					else {
						$code=$isbn;
						$code10=formatISBN($code,10);
					}
				} else {
					if(isISBN($ex_query)) {
						// si la saisie est un ISBN
						$isbn = formatISBN($ex_query);
						// si échec, ISBN erroné on le prend sous cette forme
						if(!$isbn) 
							$code = str_replace("*","%",$ex_query);
						else {
							$code10=$isbn ;
							$code=formatISBN($code10,13);
						}
					} else {
						// ce n'est rien de tout ça, on prend la saisie telle quelle
						$code = str_replace("*","%",$ex_query);
					}
				}
				
				$isbn_OK=$code;
                $new_notice = 0;
                $notice_id = 0 ;
				// le paramétrage est-il : dédoublonnage sur code ? / Ne dédoublonner que sur code ISBN (ignorer les ISSN) ?
                if ((($isbn_dedoublonnage)&&(!$isbn_only))||(($isbn_dedoublonnage)&&($isbn_only)&&(isISBN($isbn)))) {
					
					$trouvees=0;
					if ($EAN && $isbn) {
						// cas des EAN purs : constitution de la requête
						$requete = "SELECT distinct notice_id FROM notices ";
						$requete.= " WHERE notices.code in ('$code','$EAN'".($code10?",'$code10'":"").") limit 1";
						$myQuery = mysql_query($requete, $dbh);
						$trouvees=mysql_num_rows($myQuery);
					} elseif ($isbn) {
						// recherche d'un isbn
						$requete = "SELECT distinct notice_id FROM notices ";
						$requete.= " WHERE notices.code in ('$code'".($code10?",'$code10'":"").") limit 1";
						$myQuery = mysql_query($requete, $dbh);
						$trouvees=mysql_num_rows($myQuery);
					} elseif ($code) {
						// note : le code est recherché dans le champ code des notices
						// (cas des code-barres disques qui échappent à l'EAN)
						//
						$requete = "SELECT notice_id FROM notices ";
						$requete.= " WHERE notices.code like '$code' limit 10";
						$myQuery = mysql_query($requete, $dbh);
						$trouvees=mysql_num_rows($myQuery);
					}

                    // dédoublonnage sur isbn
                    if ($EAN  || $isbn || $code) {
                        if ($trouvees==0) {
                            $new_notice=1;
                        } else {
                            $new_notice=0;
                            $notice_id = mysql_result ($myQuery,0,"notice_id");
                            $sql_log = mysql_query("insert into error_log (error_origin, error_text) values ('import_expl_".addslashes(SESSid).".inc', '".$msg[542]." $EAN  || $isbn || $code ".addslashes($tit[0]['a'])."') ") ;
                        }
                    } else {
                        if ($isbn_mandatory == 1) {
                            $sql_log = mysql_query("insert into error_log (error_origin, error_text) values ('import_".addslashes(SESSid).".inc', '".$msg[543]."') ") ;
                        } else {
                            $new_notice = 1;
                            $sql_log = mysql_query("insert into error_log (error_origin, error_text) values ('import_".addslashes(SESSid).".inc', '".$msg[565]."') ") ;
                        }
                    }
                } else {
                    // pas de dédoublonnage
                    if ($isbn_mandatory == 1 && $isbn_OK=="") {
                       $sql_log = mysql_query("insert into error_log (error_origin, error_text) values ('import_".addslashes(SESSid).".inc', '".$msg[543]."') ") ;
                    }elseif($isbn_OK){
                        $new_notice = 1;
                    }else{
                    	 $new_notice = 1;
                         $sql_log = mysql_query("insert into error_log (error_origin, error_text) values ('import_".addslashes(SESSid).".inc', '".$msg[565]."') ") ;
                    }
                }
                /* the notice is new, we are going to import it... */
                if ($new_notice==1) {                	
                    import_new_notice() ; 
                    if($link_generate) import_notice_link();                   
    				import_new_notice_suite() ;    				
    				// Mise à jour de la table "notices_global_index"
    				notice::majNoticesGlobalIndex($notice_id);
    				// Mise à jour de la table "notices_mots_global_index"
    				notice::majNoticesMotsGlobalIndex($notice_id);
                } else {
                	$notice_deja_presente++;
                	
					//TRAITEMENT DES DOCS NUMERIQUES SUR NOTICE EXISTANTE
					if ($add_explnum===TRUE) ajoute_explnum();
				}

                // TRAITEMENT DES EXEMPLAIRES ICI
                if ($sub == "import_expl") {
                    traite_exemplaires () ;
                } // fin if $sub=import_expl
    		}
                
                
            /* this has been succesfuly read, it can be deleted */
	        $sql_del = "delete from import_marc where id_import = '".$idnotice_import."' ";
            $sql_result_del = mysql_query($sql_del) or die ("Couldn't delete import_marc $idnotice_import !");
        } /* end while records in import table */
        $sql = "select count(1) as reste from import_marc where origine='".addslashes(SESSid)."'";
        $sql_result = mysql_query($sql) or die ("Couldn't select count import table !");
        $reste=mysql_result($sql_result,0,"reste");

        if ($sub == "import_expl") {
            if ($reste > 0 ) {
                $formulaire="<form class='form-$current_module' name=\"load\" method=\"post\" action=\"iimport_expl.php\">\n";
                $formulaire.="<INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />";
                $formulaire.="<INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import_expl\" />";
                $formulaire.="<INPUT NAME=\"action\" TYPE=\"hidden\" value=\"load\" />";
                $formulaire.="<INPUT NAME=\"book_lender_id\" TYPE=\"hidden\" value=\"".$book_lender_id."\" />";
                $formulaire.="<INPUT NAME=\"book_statut_id\" TYPE=\"hidden\" value=\"".$book_statut_id."\" />";
                $formulaire.="<input type=\"hidden\" name=\"filename\" value=\"$filename\" />";
                $formulaire.="<input type=\"hidden\" name=\"from_file\" value=\"$from_file\" />";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_mandatory\" VALUE=\"$isbn_mandatory\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_dedoublonnage\" VALUE=\"$isbn_dedoublonnage\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_only\" VALUE=\"$isbn_only\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"cote_mandatory\" VALUE=\"$cote_mandatory\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"tdoc_codage\" VALUE=\"$tdoc_codage\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"statisdoc_codage\" VALUE=\"$statisdoc_codage\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"sdoc_codage\" VALUE=\"$sdoc_codage\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"statutnot\" VALUE=\"$statutnot\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"book_location_id\" VALUE=\"$book_location_id\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"recharge\" value=\"YES\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"reste\" value=\"".($reste)."\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"nbtot_notice\" value=\"".($nbtot_notice)."\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"notice_deja_presente\" value=\"".($notice_deja_presente)."\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"notice_rejetee\" value=\"".($notice_rejetee)."\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"nb_expl_ignores\" value=\"".($nb_expl_ignores)."\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"que_faire\" VALUE=\"$que_faire\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"link_generate\" VALUE=\"$link_generate\" />\n";
                $formulaire.="</form>";
                   
                 //On enregistre les ids utilisés avant le rechargement
                global $notices_crees, $notices_a_creer,$bulletins_crees,$bulletins_a_creer;
                $tabimport_id['notices_existantes'] = $notices_crees;
                $tabimport_id['notices_a_creer'] = $notices_a_creer;
                $tabimport_id['bulletins_crees'] = $bulletins_crees;
                $tabimport_id['bulletins_a_creer'] = $bulletins_a_creer;
                $fo = fopen("$base_path/temp/liste_id".SESSid.".txt","w");                
				fwrite($fo,serialize($tabimport_id));
                $formulaire.="<script> setTimeout(\"document.load.submit()\",2000); </script>\n";
                printf ($msg[513], $reste, $nbtot_notice); /* File %s ... . Still %s notices to load (total = %s) ... */
                if ($notice_deja_presente==1) {
                	printf ($msg[514],$notice_deja_presente) ; /* <br />($notice_deja_presente notice already in the database has been ignored since the begining) */
                } elseif ($notice_deja_presente>1) {
                	printf ($msg[515],$notice_deja_presente) ; /* <br />($notice_deja_presente notices already in the database have been ignored since the begining) */
                }
                if ($notice_rejetee>0) {
                	print "<br /> ".$notice_rejetee." ".$msg['notices_invalides'];
                }
                printf ($msg[521], $nb_expl_ignores); /* ## exemplaire(s) ignoré(s) */
            } else {
                $formulaire="";
                $script="";
                printf ($msg[516], $nbtot_notice); /* There were <b>$nbtot_notice</b> notice(s) to load, everything is OK...</b> */
                if ($notice_deja_presente==1) {
                	printf ($msg[517], $notice_deja_presente); /*  <b>$notice_deja_presente</b> notice already in the database has been ignored... */
                } elseif ($notice_deja_presente>1) {
                	printf ($msg[518], $notice_deja_presente); /*  dont <b>$notice_deja_presente</b> notices already in the database have been ignored... */
                }
                if ($notice_rejetee>0) {
                	print "<br /> ".$notice_rejetee." ".$msg['notices_invalides'];
                }
                printf ($msg[521], $nb_expl_ignores); /* ## exemplaire(s) ignoré(s) */
                /* ajouter ici SELECT error_origin, error_text, count(*) FROM error_log group by error_origin, error_text */
                $gen_liste_log="";
                $resultat_liste=mysql_query("SELECT error_origin, error_text, count(*) as nb_error FROM error_log where error_origin in ('expl_".addslashes(SESSid).".class','import_expl_".addslashes(SESSid).".inc','iimport_expl_".addslashes(SESSid).".inc','import_".addslashes(SESSid).".inc.php','import_".addslashes(SESSid).".inc','import_func_".addslashes(SESSid).".inc.php') group by error_origin, error_text" );
                $nb_liste=mysql_numrows($resultat_liste);
                if ($nb_liste>0) {
                    $gen_liste_log = "<br /><br /><b>".$msg[538]."</b><br /><table border='1'>" ;
                    $gen_liste_log.="<tr><th>".$msg[539]."</th><th>".$msg[540]."</th><th>".$msg[541]."</th></tr>";
                    $i_log=0;
                    while ($i_log<$nb_liste) {
                        $gen_liste_log.="<tr>";
                        $gen_liste_log.="<td>".mysql_result($resultat_liste,$i_log,"error_origin")."</td>" ;
                        $gen_liste_log.="<td><b>".mysql_result($resultat_liste,$i_log,"error_text")."</b></td>" ;
                        $gen_liste_log.="<td>".mysql_result($resultat_liste,$i_log,"nb_error")."</td>" ;
                        $gen_liste_log.="</tr>" ;
                        $i_log++;
                       }
                    }
                $gen_liste_str.="</table>\n" ;
                print $gen_liste_log;
                @unlink("$base_path/temp/liste_id".SESSid.".txt");
            }
            print $formulaire;
            print $script;
        } else {
            // import de notices
            if ($reste > 0 ) {
                $formulaire="<form class='form-$current_module' name=\"load\" method=\"post\" action=\"iimport_expl.php\">\n";
                $formulaire.="<INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />";
                $formulaire.="<INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import\" />";
                $formulaire.="<INPUT NAME=\"action\" TYPE=\"hidden\" value=\"load\" />";
                $formulaire.="<input type=\"hidden\" name=\"filename\" value=\"$filename\" />";
                $formulaire.="<input type=\"hidden\" name=\"from_file\" value=\"$from_file\" />";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_mandatory\" VALUE=\"$isbn_mandatory\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_dedoublonnage\" VALUE=\"$isbn_dedoublonnage\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"isbn_only\" VALUE=\"$isbn_only\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"statutnot\" VALUE=\"$statutnot\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"recharge\" value=\"YES\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"reste\" value=\"".($reste)."\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"nbtot_notice\" value=\"".($nbtot_notice)."\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"notice_deja_presente\" value=\"".($notice_deja_presente)."\" />\n";
                $formulaire.="<input type=\"hidden\" name=\"notice_rejetee\" value=\"".($notice_rejetee)."\" />\n";
                $formulaire.="<INPUT TYPE=\"hidden\" NAME=\"link_generate\" VALUE=\"$link_generate\" />\n";
                $formulaire.="</form>";
                
                //On enregistre les ids utilisés avant le rechargement
                global $notices_crees, $notices_a_creer,$bulletins_crees,$bulletins_a_creer;
                $tabimport_id['notices_existantes'] = $notices_crees;
                $tabimport_id['notices_a_creer'] = $notices_a_creer;
                $tabimport_id['bulletins_crees'] = $bulletins_crees;
                $tabimport_id['bulletins_a_creer'] = $bulletins_a_creer;
                $fo = fopen("$base_path/temp/liste_id".SESSid.".txt","w");                
				fwrite($fo,serialize($tabimport_id));	
                $formulaire.="<script> setTimeout(\"document.load.submit()\",2000); </script>\n";
                printf ($msg[509].$msg[513],$from_file, $reste, $nbtot_notice); /* File %s ... . Still %s notices to load (total = %s) ... */
                if ($notice_deja_presente==1) {
                	printf ($msg[514],$notice_deja_presente) ; /* <br />($notice_deja_presente notice already in the database has been ignored since the begining) */
                } elseif ($notice_deja_presente>1) {
                	printf ($msg[515],$notice_deja_presente) ; /* <br />($notice_deja_presente notices already in the database have been ignored since the begining) */
                }
                if ($notice_rejetee>0) {
                	print "<br /> ".$notice_rejetee." ".$msg['notices_invalides'];
                }
            } else {
                $formulaire="";
                $script="";
                printf ($msg[509].$msg[516], $from_file, $nbtot_notice); /* There were <b>$nbtot_notice</b> notice(s) to load, everything is OK...</b> */
                if ($notice_deja_presente==1) {
                	printf ($msg[517], $notice_deja_presente); /*  <b>$notice_deja_presente</b> notice already in the database has been ignored... */
                } elseif ($notice_deja_presente>1) {
                	printf ($msg[518], $notice_deja_presente); /*  dont <b>$notice_deja_presente</b> notices already in the database have been ignored... */
                }
                if ($notice_rejetee>0) {
                	print "<br /> ".$notice_rejetee." ".$msg['notices_invalides'] ;
                }
                /* ajouter ici SELECT error_origin, error_text, count(*) FROM error_log group by error_origin, error_text */
                $gen_liste_log="";
                $resultat_liste=mysql_query("SELECT error_origin, error_text, count(*) as nb_error FROM error_log where error_origin in ('expl_".addslashes(SESSid).".class','import_expl_".addslashes(SESSid).".inc','iimport_expl_".addslashes(SESSid).".inc','import_".addslashes(SESSid).".inc.php', 'import_".addslashes(SESSid).".inc','import_func_".addslashes(SESSid).".inc.php') group by error_origin, error_text" );
                $nb_liste=mysql_numrows($resultat_liste);
                if ($nb_liste>0) {
                    $gen_liste_log = "<br /><br /><b>".$msg[538]."</b><br /><table width=\"100%\"  border='1'>" ;
                    $gen_liste_log.="<tr><td>".$msg[539]."</td><td>".$msg[540]."</td><td>".$msg[541]."</td></tr>";
                    $i_log=0;
                    while ($i_log<$nb_liste) {
                        $gen_liste_log.="<tr>";
                        $gen_liste_log.="<td>".mysql_result($resultat_liste,$i_log,"error_origin")."</td>" ;
                        $gen_liste_log.="<td><b>".mysql_result($resultat_liste,$i_log,"error_text")."</b></td>" ;
                        $gen_liste_log.="<td>".mysql_result($resultat_liste,$i_log,"nb_error")."</td>" ;
                        $gen_liste_log.="</tr>" ;
                        $i_log++;
                        }
                    }
                $gen_liste_str.="</table>\n" ;
                print $gen_liste_log;
                @unlink("$base_path/temp/liste_id".SESSid.".txt");
            }

	        print $formulaire;
	        print $script;
        }
        break;
    default:
        if ($sub == "import_expl") include("$include_path/messages/help/$lang/import_expl.txt") ;
            else include("$include_path/messages/help/$lang/import.txt") ;
        break;
    }

print "</div></body></html>";

