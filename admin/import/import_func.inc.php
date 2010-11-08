<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import_func.inc.php,v 1.90 2010-08-17 08:29:32 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/serials.class.php");
// templates
$tpl_beforeupload_expl = "
                <form class='form-$current_module' ENCTYPE=\"multipart/form-data\" METHOD=\"post\" ACTION=\"iimport_expl.php\">
                <h3>".$msg['import_expl_form_titre']."</h3>
                <div class='form-contenu'>
                    <div class='row'>
                        <div class='colonne2'>
                            <label class='etiquette' >$msg[564]</label><br />
                            <INPUT TYPE='radio' NAME='isbn_mandatory' id='io1' VALUE='1' CLASS='radio' /><label for='io1'> $msg[40] </label>
                            <INPUT TYPE='radio' NAME='isbn_mandatory' id='io0' VALUE='0' CLASS='radio' checked='checked' /><label for='io0'> $msg[39] </label>
                        </div>
                        <div class='colonne-suite'>
                            <label class='etiquette' >$msg[568]</label><br />
                            <INPUT TYPE='radio' NAME='isbn_dedoublonnage' id='di1' VALUE='1' CLASS='radio' checked='checked' /><label for='di1'> $msg[40] </label>
                            <INPUT TYPE='radio' NAME='isbn_dedoublonnage' id='di0' VALUE='0' CLASS='radio' /><label for='di0'> $msg[39] </label>
                            <input type='checkbox' name='isbn_only' id='ionly' value='1' checked='checked' /><label for='ionly'> ".$msg["ignore_issn"]." </label>
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
                    		<label class='etiquette' >".$msg['import_genere_liens']."</label><br />
                    		<INPUT TYPE='radio' NAME='link_generate' id='link1' VALUE=' 1' CLASS='radio' /><label for='link1'> $msg[40] </label>
                            <INPUT TYPE='radio' NAME='link_generate' id='link0' VALUE='0' CLASS='radio' checked='checked' /><label for='link0'> $msg[39] </label>
                    	</div>
                    </div>
                    <div class='row'><hr /></div>
					<div class='row'>
                        <label class='etiquette' for='preteur statut'>$msg[560]</label>
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
                        <INPUT TYPE='radio' NAME='cote_mandatory' id='cm1' VALUE='1' CLASS='radio' /><label for='cm1'> $msg[40] </label>
                        <INPUT TYPE='radio' NAME='cote_mandatory' id='cm0' VALUE='0' CLASS='radio' checked='checked' /><label for='cm0'> $msg[39] </label>
                    </div>
                    <div class='row'><hr /></div>
                    <div class='row'>
                        <label class='etiquette'>$msg[17]</label>
                    </div>
                    <div class='row'>
                        <INPUT TYPE='radio' NAME='tdoc_codage' id='td1' VALUE='1' CLASS='radio' /><label for='td1'> ".$msg["import_expl_codage_proprio"]."</label>
                        <INPUT TYPE='radio' NAME='tdoc_codage' id='td0' VALUE='0' CLASS='radio' checked='checked' /><label for='td0'> ".$msg["import_expl_codage_generique"]."</label>
                    </div>
                    <div class='row'>
                        <label class='etiquette'>$msg[24]</label>
                    </div>
                    <div class='row'>
                        <INPUT TYPE='radio' NAME='statisdoc_codage' id='sd1' VALUE='1' CLASS='radio' /><label for='sd1'> ".$msg["import_expl_codage_proprio"]."</label>
                        <INPUT TYPE='radio' NAME='statisdoc_codage' id='sd0' VALUE='0' CLASS='radio' checked='checked' /><label for='sd0'> ".$msg["import_expl_codage_generique"]."</label>
                    </div>
                    <div class='row'>
                        <label class='etiquette'>$msg[19]</label>
                    </div>
                    <div class='row'>
                        <INPUT TYPE='radio' NAME='sdoc_codage' id='sdc1' VALUE='1' CLASS='radio' /><label for='sdc1'> ".$msg["import_expl_codage_proprio"]."</label>
                        <INPUT TYPE='radio' NAME='sdoc_codage' id='sdc0' VALUE='0' CLASS='radio' checked='checked' /><label for='sdc0'> ".$msg["import_expl_codage_generique"]."</label>
                    </div>
                    <div class='row'><hr /></div>

                    <div class='row'>
                        <label class='etiquette' for='txt_suite'>$msg[501]</label>
                        </div>
                    <div class='row'>
                        <INPUT NAME='userfile' class='saisie-80em' TYPE='file' size='60' />
                        <INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\" />
                        <INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import_expl\" />
                        <INPUT NAME=\"action\" TYPE=\"hidden\" value=\"afterupload\" />
                    </div>
                    </div>
                <INPUT TYPE='SUBMIT' class='bouton' NAME='upload' VALUE='".$msg[502]."' />
                </FORM>"; 

$tpl_beforeupload_notices = "
                    <form class='form-$current_module' ENCTYPE='multipart/form-data' METHOD='post' ACTION='iimport_expl.php' />
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
	                    		<INPUT TYPE='radio' NAME='link_generate' id='link1' VALUE='1' CLASS='radio' /><label for='link1'> $msg[40] </label>
	                            <INPUT TYPE='radio' NAME='link_generate' id='link0' VALUE='0' CLASS='radio' checked='checked' /><label for='link0'> $msg[39] </label>
	                    	</div>
                   		</div>
	                    <div class='row'>&nbsp;</div>
                        <div class='row'>
                            <label class='etiquette' for='txt_suite'>$msg[501]</label>
                            </div>
                        <div class='row'>
                            <INPUT NAME='userfile' class='saisie-80em' TYPE='file' size='60' />
                            <INPUT NAME='categ' TYPE='hidden' value='import' />
                            <INPUT NAME='sub' TYPE='hidden' value='import' />
                            <INPUT NAME='action' TYPE='hidden' value='afterupload' />
                            </div>
                        </div>
                    <INPUT TYPE='SUBMIT' class='bouton' NAME='upload' VALUE='".$msg[502]."' />
                    </FORM>";

// PRELOAD
function loadfile_in_table () {
	global $msg ;
	global $sub, $book_lender_name ;
	global $noticenumber, $filename, $from_file, $pb_fini, $recharge ;
	global $pmb_import_limit_read_file ;

	if ($noticenumber=="") $noticenumber=0;

	if (!file_exists($filename)) {
		printf ($msg[506],$from_file); /* The file %s doesn't exist... */
		return;
	}
	
	if (filesize($filename)==0) {
		printf ($msg[507],$from_file); /* The file % is empty, it's going to be deleted */
		unlink ($filename);
		return;
	}
	
	$handle = fopen ($filename, "rb");
	if (!$handle) {
		printf ($msg[508],$from_file); /* Unable to open the file %s ... */
		return;
	}
	
	if ($sub=="import_expl") {
		printf ($msg[509], $from_file) ;
		printf ($msg[511], "\"".$book_lender_name."\"") ;
	}
	
	$file_size=filesize ($filename);

	$contents = fread ($handle, $file_size);
	fclose ($handle);
	
	/* First load of the shot, let's empty the import table */
	if ($recharge=="") {
		$sql = "DELETE FROM import_marc WHERE origine='".addslashes(SESSid)."' ";
		$sql_result = mysql_query($sql) or die ("Couldn't delete import table !");
		$sql = "DELETE FROM error_log WHERE error_origin LIKE '%_".addslashes(SESSid).".%' ";
		$sql_result = mysql_query($sql) or die ("Couldn't delete error_log table !");
	}
	
	/* The whole file is in $contents, let's read it */
	$str_lu="";
	$j=0;
	$i=0;
	$pb_fini="";
	$txt="";
	while ( ($i<=strlen($contents)) && ($pb_fini=="") ) {
		$car_lu=substr($contents,$i,1) ;
		$i++;
		if ($i<=strlen($contents)) {
			if ($car_lu != chr(0x1d)) {
				/* the read car isn't the end of the notice */
				$str_lu = $str_lu.$car_lu;
			} else {
				/* the read car is the end of a notice */
				$str_lu = $str_lu.$car_lu;
				$j++;
				$sql = "INSERT INTO import_marc (notice,origine) VALUES('".addslashes($str_lu)."','".addslashes(SESSid)."')";
				$sql_result = mysql_query($sql) 
					or die ("Couldn't insert record!");
				if ($j>=$pmb_import_limit_read_file && $i<strlen($contents)) {
					/* let's rewrite the file with the remaing string  */
					$handle = fopen ($filename, "wb");
					fwrite ($handle, substr($contents,$i, $file_size-$i));
					fclose ($handle);
					printf (" ".$msg[510], ($file_size-$i)) ;
					$pb_fini="NOTEOF";
				} else if ($j>=$pmb_import_limit_read_file && $i>=strlen($contents)){
					$pb_fini = "EOF";					
				}
				$str_lu="";
			}
		} else { /* the wole file has been read */
			$pb_fini="EOF";
		}
	} /* end while red file */	
	
	if ($pb_fini=="NOTEOF") $recharge="YES"; else $recharge="NO" ;
	if ($pb_fini=="EOF") { /* The file has been read, we can delete it */
		unlink ($filename);
	}
} // fin fonction de load
	

function recup_noticeunimarc($notice) {
	
	global $id_unimarc;
	global $doc_type		;
	global $hierarchic_level;
	global $bibliographic_level	;
	global $bibliographic_level_origine;
	global $hierarchic_level_origine;
	global $isbn			;
	global $issn_011		;
	global $prix			;
	global $prix_cd			;
	global $cb				;
	global $lang_code		;
	global $org_lang_code	;
	global $tit_200a		;
	global $tit_200c		;
	global $tit_200d		;
	global $tit_200e		;
	global $tit_200v		;
	global $serie_200		;
	global $editor			;
	global $no_edition		;
	global $npages			;
	global $ill				;
	global $size			;
	global $accomp			;
	global $collection_225	;
	global $n_contenu		;
	global $n_resume		;
	global $n_gen			;
	global $EAN				;
	global $collection_410	;
	global $collection_411	;
	global $serie			;
	global $index_sujets	;
	global $dewey			;
	global $dewey_l			;
	global $tu_500			;
	global $tu_500_r 		;
	global $tu_500_s		;
	global $tu_500_j		;
	global $aut_700			;
	global $aut_701			;
	global $aut_702			;
	global $aut_710			;
	global $aut_711			;
	global $aut_712			;
	global $origine_notice	;
	global $lien, $eformat	;
	global $info_995		;
	global $info_996		;
	global $info_852		;
	global $analytique		;

	global $info_600_a, $info_600_j, $info_600_x, $info_600_y, $info_600_z ;
	global $info_601_a, $info_601_j, $info_601_x, $info_601_y, $info_601_z ;
	global $info_602_a, $info_602_j, $info_602_x, $info_602_y, $info_602_z ;
	global $info_605_a, $info_605_j, $info_605_x, $info_605_y, $info_605_z ;
	global $info_606_a, $info_606_j, $info_606_x, $info_606_y, $info_606_z ;
	global $info_607_a, $info_607_j, $info_607_x, $info_607_y, $info_607_z ;

	global $info_686;
	global $indicateur;
	global $link_generate;
	$id_import=array();
	$id_unimarc=0;
	$indicateur=array();
	$doc_type		= "";
	$hierarchic_level	= '0';
	$bibliographic_level	= 'm';
	$isbn			= array();
	$issn_011		= array();
	$prix			= array();
	$prix_cd		= array();
	$cb				= "";
	$lang_code		= array();
	$org_lang_code	= array();
	$tit_200a		= array();
	$tit_200c		= array();
	$tit_200d		= array();
	$tit_200e		= array();
	$tit_200v		= array();
	$serie_200		= array();
	$editor			= array();
	$npages			= "";
	$no_edition		= "";
	$ill			= "";
	$size			= "";
	$accomp			= "";
	$collection_225	= array();
	$collection_410	= array();
	$collection_411	= array();
	$n_contenu		= array();
	$n_resume		= array();
	$n_gen			= array();
	$EAN			= array();
	$serie			= array();
	$index_sujets	= array();
	$dewey			= array();
	$dewey_l		= array();
	$tu_500			= array();
	$tu_500_r 		= array();
	$tu_500_s 		= array();
	$tu_500_j 		= array();
	$aut_700		= array();
	$aut_701		= array();
	$aut_702		= array();
	$aut_710		= array();
	$aut_711		= array();
	$aut_712		= array();
	$origine_notice	= array() ;
	$lien			= array();
	$eformat		= array();
	$info_995		= array();
	$info_852		= array();
	$analytique 	= array() ;
	
	$info_600_a = array();
	$info_600_j = array();
	$info_600_x = array();
	$info_600_y = array();
	$info_600_z  = array();
	
	$info_601_a = array();
	$info_601_j = array();
	$info_601_x = array();
	$info_601_y = array();
	$info_601_z  = array();
	
	$info_602_a = array();
	$info_602_j = array();
	$info_602_x = array();
	$info_602_y = array();
	$info_602_z  = array();
	
	$info_605_a = array();
	$info_605_j = array();
	$info_605_x = array();
	$info_605_y = array();
	$info_605_z  = array();
	
	$info_606_a = array();
	$info_606_j = array();
	$info_606_x = array();
	$info_606_y = array();
	$info_606_z  = array();
	
	$info_607_a = array();
	$info_607_j = array();
	$info_607_x = array();
	$info_607_y = array();
	$info_607_z  = array();

	$info_686 = array();
	$info_996 = array();  
	
	$record = new iso2709_record($notice, AUTO_UPDATE);
	if(!$record->valid()){
		// On ne traite pas les notices invalides
		/*echo "<pre>";
		print_r($record->inner_data);
		echo "</pre>";
		die();*/
		$num_notice=$record->get_subfield("001");
		$titr=$record->get_subfield_array("200", 'a');
		$requete="insert into error_log(error_origin,error_text) values('import_func_".addslashes(SESSid).".inc.php','".addslashes("La notice (numéro : ".$num_notice[0].", titre : ".$titr[0].") n'a pas été reprise")."')";
		mysql_query($requete);
		return false;
	}
	$doc_type=$record->inner_guide['dt'];
	
	$bibliographic_level_origine=$record->inner_guide['bl'];
	$hierarchic_level_origine=$record->inner_guide['hl'];

	// traitements particuliers, solution d'urgence pour les pério et autres.
	if($link_generate){
		//Si on choisit d'importer les liens on reprend le niveau
			switch ($bibliographic_level_origine) {
			case 'a':
				$hierarchic_level = '2';
				$bibliographic_level = 'a';
				break;
			case 's':
				if($hierarchic_level_origine == '1'){
					$hierarchic_level = '1';
					$bibliographic_level = 's';
				} else {
					$hierarchic_level = '2';
					$bibliographic_level = 'b';
				}
				break;
			case 'm':
			case 'c':
			default :
				// suite à pb d'export Orphée : si inconnu, non conforme, on force à 0 et m
				$hierarchic_level = '0';
				$bibliographic_level = 'm';
				break;
		}
	}else{
		//Sinon on reprend tous en temps que monographie
		$hierarchic_level = '0';
		$bibliographic_level = 'm';
	}

	
	for ($i=0;$i<count($record->inner_directory);$i++) {
		$cle=$record->inner_directory[$i]['label'];
		//$length=$record->inner_directory[$i]['length'];
		//$adress=$record->inner_directory[$i]['adress'];
		$flag_cle=0;
		if(!array_key_exists($cle,$indicateur)) {
			$flag_cle=1;
		}	
		// memo indicateur de champ
		$indicateur[$cle][]=substr($record->inner_data[$i]['content'],0,2);
		if($flag_cle) {
			switch($cle) {
				case "001" :
					$id_import=$record->get_subfield($cle);
					$id_unimarc = $id_import[0];
					break;
				case "010": /* isbn */
					$isbn=$record->get_subfield($cle,'a');
					$prix=$record->get_subfield($cle,'d');
					break;
				case "011": /* issn_011 */
					$issn_011=$record->get_subfield($cle,'a');
					break;
				case "071": /* barcode */
					$cb=$record->get_subfield($cle,"a");
					break;
				case "101": /* language */
					$lang_code=$record->get_subfield_array($cle,"a");
					$org_lang_code=$record->get_subfield_array($cle,"c");
					break;
				case "200": /* titles */
					$tit_200a=$record->get_subfield_array($cle, 'a');
					$tit_200c=$record->get_subfield_array($cle, 'c');
					$tit_200d=$record->get_subfield_array($cle, 'd');
					$tit_200e=$record->get_subfield_array($cle, 'e');
					$tit_200v=$record->get_subfield_array($cle, 'v');
					$serie_200=$record->get_subfield($cle,"h","i");
					break;
				case "205": /* no_edition */
					$no_edition=$record->get_subfield($cle,"a");
					break;
				case "210": /* publisher */ // b: adr
					$editor=$record->get_subfield($cle,"a","b","c","d");
					break;
				case "215": /* description */
					$npages=$record->get_subfield($cle,"a");
					$ill=$record->get_subfield($cle,"c");
					$size=$record->get_subfield($cle,"d");
					$accomp=$record->get_subfield($cle,"e");
					break;
				case "225": /* collection */
					$collection_225=$record->get_subfield($cle,"a","i","v","x");
					break;
				case "300": /* inside */
					$n_gen=$record->get_subfield($cle,"a");
					break;
				case "327": /* inside */
					$n_contenu=$record->get_subfield_array($cle,"a");
					break;
				case "330": /* abstract */
					$n_resume=$record->get_subfield($cle,"a");
					break;
				case "345": /* EAN */
					$EAN=$record->get_subfield($cle,"b");
					$prix_cd=$record->get_subfield($cle,"d");
					break;
				case "410": /* collection */
					$collection_410=$record->get_subfield($cle,"v","t","x");
					break;
				case "411": /* sub-collection */
					$collection_411=$record->get_subfield($cle,"v","t","x");
					break;
				case "461": /* series */
					$serie=$record->get_subfield($cle,"t","v");
					break;
				case "464": /* analytique */
					// $a pour le tout-venant le reste pour les périodiques bretons ! C'est un periodique donc un depouillement ou une notice objet
					$analytique=$record->get_subfield_array_array($cle);
					$info_464=$record->get_subfield($cle,"t","v","p","d","z","e");
					break;
				case "500": // titres uniformes
					$tu_500=$record->get_subfield($cle,"a","i","k","l","m","n","q","u","w","3");
					$tu_500_r=$record->get_subfield_array_array($cle,"r");
					$tu_500_s=$record->get_subfield_array_array($cle,"s");
					$tu_500_j=$record->get_subfield_array_array($cle,"j");
					break;
				case "600": // 600 PERSONAL NAME USED AS SUBJECT
					$info_600_a=$record->get_subfield_array_array($cle,"a");
					$info_600_j=$record->get_subfield_array_array($cle,"j");
					$info_600_x=$record->get_subfield_array_array($cle,"x");
					$info_600_y=$record->get_subfield_array_array($cle,"y");
					$info_600_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "601": // 601 CORPORATE BODY NAME USED AS SUBJECT
					$info_601_a=$record->get_subfield_array_array($cle,"a");
					$info_601_j=$record->get_subfield_array_array($cle,"j");
					$info_601_x=$record->get_subfield_array_array($cle,"x");
					$info_601_y=$record->get_subfield_array_array($cle,"y");
					$info_601_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "602": // 602 FAMILY NAME USED AS SUBJECT
					$info_602_a=$record->get_subfield_array_array($cle,"a");
					$info_602_j=$record->get_subfield_array_array($cle,"j");
					$info_602_x=$record->get_subfield_array_array($cle,"x");
					$info_602_y=$record->get_subfield_array_array($cle,"y");
					$info_602_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "605": // 605 TITLE USED AS SUBJECT
					$info_605_a=$record->get_subfield_array_array($cle,"a");
					$info_605_j=$record->get_subfield_array_array($cle,"j");
					$info_605_x=$record->get_subfield_array_array($cle,"x");
					$info_605_y=$record->get_subfield_array_array($cle,"y");
					$info_605_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "606": // RAMEAU / TOPICAL NAME USED AS SUBJECT
					$info_606_a=$record->get_subfield_array_array($cle,"a");
					$info_606_j=$record->get_subfield_array_array($cle,"j");
					$info_606_x=$record->get_subfield_array_array($cle,"x");
					$info_606_y=$record->get_subfield_array_array($cle,"y");
					$info_606_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "607": // 607 GEOGRAPHICAL NAME USED AS SUBJECT
					$info_607_a=$record->get_subfield_array_array($cle,"a");
					$info_607_j=$record->get_subfield_array_array($cle,"j");
					$info_607_x=$record->get_subfield_array_array($cle,"x");
					$info_607_y=$record->get_subfield_array_array($cle,"y");
					$info_607_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "610": /* mots clé */
					$index_sujets=$record->get_subfield($cle,"a");
					break;
				case "676": /* Dewey */
					$dewey=$record->get_subfield($cle,"a");
					$dewey_l=$record->get_subfield($cle,"l");
					break;
				case "686": /* pcdm3 */
					$info_686=$record->get_subfield($cle,"a","l");
					break;
				case "700":
					$aut_700=$record->get_subfield($cle,"a","b","c","d","4","f","N");
					break;
				case "701":
					$aut_701=$record->get_subfield($cle,"a","b","c","d","4","f","N");
					break;
				case "702":
					$aut_702=$record->get_subfield($cle,"a","b","c","d","4","f","N");
					break;
				case "710":
					$aut_710=$record->get_subfield($cle,"a","b","c","g","d","4","f","e","k","l","m","n");
					break;
				case "711":
					$aut_711=$record->get_subfield($cle,"a","b","c","g","d","4","f","e","k","l","m","n");
					break;
				case "712":
					$aut_712=$record->get_subfield($cle,"a","b","c","g","d","4","f","e","k","l","m","n");
					break;
				case "801": /* origine du catalogage */
					$origine_notice=$record->get_subfield($cle,"a","b");
					break;
				case "852": /* infos du SCD de Lyon 3 */
					$info_852=$record->get_subfield($cle, "b", "h", "p", "y");
					break;
				case "856": /* url */
					$lien=$record->get_subfield($cle,"u");
					$eformat=$record->get_subfield($cle,"q");
					break;
				case "995": /* infos de la BDP */
					$info_995=$record->get_subfield($cle, "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z", "0","1","2","3","4","5","6","7","8","9","A","B","C","D","M","N","O","P","R","S","T","U","V","W","Z");
					break;
				case "996": /* infos supplémentaires... ? */
					$info_996=$record->get_subfield($cle, "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z", "0","1","2","3","4","5","6","7","8","9","A","B","C","D","M","N","O","P","R","S","T","U","V","W","Z");
					break;
				default:
					break;
		
			} /* end of switch */
		}
	} /* end of for */
	return true ;
	
} // fin recup_noticeunimarc = fin récupération des variables lues dans la notice UNIMARC


/*
 *  Récupération des liens des notices
 */
function recup_noticeunimarc_link($notice){
	global $notices_liees, $titre_ppal_200, $titre_perio_530a, $champ_210 ;
	
	$record = new iso2709_record($notice,AUTO_UPDATE);
	$titre_ppal_200 = $record->get_subfield('200','a','h','i');
	$champ_210 = $record->get_subfield('210','d','h');
	$titre_perio_530a = $record->get_subfield('530','a');
	$notices_liees=$record->get_all_fields('4..');
}

/*
 * Import d'une nouvelle notice
 */
function import_new_notice() {
	
	global $dbh ;
	global $notice_id ;
	global $bulletin_ex;//Identifiant du bulletin
	$bulletin_ex=0;
	global $id_unimarc;
	global $notices_crees;
	global $bulletins_crees;
	global $notices_a_creer;
	global $bulletins_a_creer;
	global $base_path;
	global $pmb_keyword_sep;
	global $link_generate;
	global $doc_type 		;
	global $hierarchic_level	;
	global $bibliographic_level	;
	global $isbn_OK			;
	global $prix			;
	global $prix_cd			;
	global $cb				;
	global $tit_200a		;
	global $tit_200c		;
	global $tit_200d		;
	global $tit_200e		;
	global $tit_200v		;
	global $serie_200		;
	global $editor			;
	global $no_edition		;
	global $npages			;
	global $ill				;
	global $size			;
	global $accomp			;
	global $collection_225	;
	global $n_contenu		;
	global $n_resume		;
	global $n_gen			;
	global $EAN				;
	global $collection_410	;
	global $collection_411	;
	global $tu_500		;
	global $tu_500_r		;
	global $tu_500_s		;
	global $tu_500_j		;
	global $serie			;
	global $index_sujets	;
	global $dewey			;
	global $dewey_l			;
	global $aut_700			;
	global $aut_701			;
	global $aut_702			;
	global $aut_710			;
	global $aut_711			;
	global $aut_712			;
	global $origine_notice	;
	global $lien			;
	global $eformat			;
	global $analytique		;
	global $statutnot 		;
	global $indicateur;
	global $add_explnum;
	global $info_686;
	$add_explnum=FALSE;
	
	/* traitement des éditeurs */
	$coll_id=0;
	$subcoll_id=0;
	$serie_id=0;
	$tnvol_ins="";
	$ed1_id=0;
	$ed2_id=0;
	$year="";
	$date_parution="0000-00-00";
	
	//On récupère le tableau des notices créées à partir du fichier temporaire (rechargement d'iframe)
	if($link_generate && !isset($notices_crees)){
		$tabimport_id= file_get_contents("$base_path/temp/liste_id".SESSid.".txt");
		if($tabimport_id){
			$tabimport_id = unserialize($tabimport_id);
			$notices_crees = $tabimport_id['notices_existantes'];
            $notices_a_creer = $tabimport_id['notices_a_creer'];
            $bulletins_crees = $tabimport_id['bulletins_crees'];
            $bulletins_a_creer = $tabimport_id['bulletins_a_creer'];
		}
	}
	
	if(!is_array($notices_crees)) $notices_crees = array();
	
	//Pour le cas ou on est sur une notice (bidon) d'article juste pour les exemplaires de bulletin
	if($bibliographic_level == "a" && $tit_200d[0] == "Article_expl_bulletin"){
		//On importe rien
		return;
	}elseif($tit_200d[0] == "Article_expl_bulletin"){
		//Pour le cas ou on import sans les liens un fichier exporter avec les exemplaires et les liens
		$tit_200d[0]="";
	}
	
	if($bibliographic_level != "a" && $bibliographic_level != "b"){
		//Pour les articles et les bulletins on ne garde pas les informations suivantes
		$year=clean_string($editor[0]['d']);
		$date_parution=notice::get_date_parution($year);
		
		$ed['name']=clean_string($editor[0]['c']);
		$ed['adr']=clean_string($editor[0]['b']);
		$ed['ville']=clean_string($editor[0]['a']);
		$ed1_id = editeur::import($ed);
		
		$ed['name']=clean_string($editor[1]['c']);
		$ed['adr']=clean_string($editor[1]['b']);
		$ed['ville']=clean_string($editor[1]['a']);
		$ed2_id = editeur::import($ed);
		if($bibliographic_level != "s"){
			//Pour les periodiques on ne garde pas les informations suivantes
			/* traitement des collections */
			$coll_name="";
			$subcoll_name="";
			$coll_issn="";
			$subcoll_issn="";
			$nocoll_ins="";
			/* traitement de 225$a, si rien alors 410$t pour la collection */
			if ($collection_225[0]['a']!="") {
				$coll_name=$collection_225[0]['a'];
				$coll_issn=$collection_225[0]['x'];
			} elseif ($collection_410[0]['t']!="") {
				$coll_name=$collection_410[0]['t'];
				$coll_issn=$collection_410[0]['x'];
			}
			/* traitement de 225$i, si rien alors 411$t pour la sous-collection */
			if ($collection_225[0]['i']!="") {
				$subcoll_name=$collection_225[0]['i'];
				$subcoll_issn=$collection_225[1]['x'];
			} elseif ($collection_411[0]['t']!="") {
					$subcoll_name=$collection_411[0]['t'];
					$subcoll_issn=$collection_411[0]['x'];
			}
			/* gaffe au nocoll, en principe en 225$v selon FL  sinon en 410$v ou 411$v*/
			if ($collection_225[0]['v']!="")
				$nocoll_ins=$collection_225[0]['v'];
			elseif($collection_410[0]['v']!="")
				$nocoll_ins=$collection_410[0]['v'];
			elseif($collection_411[0]['v']!="")
				$nocoll_ins=$collection_411[0]['v'];
			else 
				$nocoll_ins="";
			
			$collec['name']=clean_string($coll_name);
			$collec['parent']=$ed1_id;
			$collec['issn']=clean_string($coll_issn);
			$coll_id = collection::import($collec);
			
			/* sous collection */
			$subcollec['name']=clean_string($subcoll_name);
			$subcollec['coll_parent']=$coll_id;
			$subcollec['issn']=clean_string($subcoll_issn);
			$subcoll_id = subcollection::import($subcollec);
		
			/* traitement des séries */
			$serie_id = serie::import(clean_string($serie[0]['t']));
			$tnvol_ins=$serie[0]['v'];
			if(!$serie_id){
				$serie_id = serie::import(clean_string($serie_200[0]['i']));
				$serie[0]['t'] = $serie_200[0]['i'];
				$tnvol_ins=$serie_200[0]['h'];
			}
			$serie[0]['t'] ? $index_serie = ' '.strip_empty_words($serie[0]['t']).' ' : $index_serie='';
			
		}
	}
	
	/* traitement de Dewey */
	$indexint_id = indexint::import(clean_string($dewey[0]),$dewey_l[0]);
	if(!$indexint_id and count($info_686)){
		$indexint_id = indexint::import(clean_string($info_686[0]["a"]),$info_686[0]["l"],99);
	}

	/* Traitement des notes */				
	$n_resume_total  = "";
	$n_gen_total     = "";
	$n_contenu_total = "";
	
	if (!$n_resume) $n_resume=array();
	$n_resume_total= implode("\n",$n_resume);
	
	if (!$n_gen) $n_gen=array();
	$n_gen_total= implode("\n",$n_gen);
	
	if (!$n_contenu) $n_contenu=array();
	$n_contenu_total= implode("\n",$n_contenu);
	
	// ajout : les 464$a sont ajouté aux notes de contenu à déporter éventuellement dans func_bdp41 si besoin
	for ($i = 0; $i< count($analytique); $i++) {
		$ana=array();
		for ($j = 0; $j< count($analytique[$i]); $j++) {
			$ana[$analytique[$i][$j]["label"]][]=$analytique[$i][$j]["content"];
		}
		for ($j=0; $j<count($ana["a"]); $j++) {
			$n_contenu_total.=$ana["a"][$j].($ana["e"][$j]?" ; ".$ana["e"][$j]:"").($ana["f"][$j]?" / ".$ana["f"][$j]:"")."\n";
		}
	}	
	
	
	
	// Préparation des titres 
	$tit[0]['a'] = implode (" ; ",$tit_200a);
	if($bibliographic_level != "a" && $bibliographic_level != "b"  && $bibliographic_level != "s"){
		//Pour les articles et les bulletins on ne garde pas les informations suivantes
		$tit[0]['c'] = implode (" ; ",$tit_200c);
	}else{
		$tit[0]['c']="";
	}
	
	$tit[0]['d'] = implode (" ; ",$tit_200d);
	$tit[0]['e'] = implode (" ; ",$tit_200e);
	
	$ind_wew = $serie[0]['t']." ".$tnvol_ins." ".$tit[0]['a']." ".$tit[0]['c']." ".$tit[0]['d']." ".$tit[0]['e'] ;
	$ind_sew = strip_empty_words($ind_wew) ; 

	// MODIF = index_matiere = strip_empty_words(index_l)
	//$index_matieres = strip_empty_words($n_contenu_total." ".$n_gen_total." ".$n_resume_total);

	if (is_array($index_sujets)) 
		$index_l = implode (' '.$pmb_keyword_sep.' ',$index_sujets);
	else 
		$index_l = $index_sujets;
	$index_l ? $index_matieres = ' '.strip_empty_words($index_l).' ' : $index_matieres = '';
	
	$n_gen_total		?	$index_n_gen = ' '.strip_empty_words($n_gen_total).' ' : $index_n_gen = '';
	$n_contenu_total 	?	$index_n_contenu = ' '.strip_empty_words($n_contenu_total).' ' : $index_n_contenu = '';
	$n_resume_total  	?	$index_n_resume = ' '.strip_empty_words($n_resume_total).' ' : $index_n_resume = '';
	
	// if (trim($n_resume_total)=="") $n_resume_total = $n_gen_total." ".$n_contenu_total ;

	/* Origine de la notice */
	$origine_not['nom']=clean_string($origine_notice[0]['b']);
	$origine_not['pays']=clean_string($origine_notice[0]['a']);
	$orinot_id = origine_notice::import($origine_not);
	if ($orinot_id==0) $orinot_id=1 ;
	
	if($bibliographic_level != "a" && $bibliographic_level != "s"){
		//Pour les articles et les periodiques on ne garde pas les informations suivantes
		// prix
		$price = $prix[0];
		//Pour les CDs
		if(!$price){
			$price=$prix_cd[0];
		}
		
		$illustration=$ill[0];
		$taille=$size[0];
		$mat_accomp=$accomp[0];
		if($bibliographic_level != "b"){
			$mention_edit=$no_edition[0];
		}else{
			$mention_edit="";
		}
		
	}else{
		$illustration="";
		$taille="";
		$mat_accomp="";
	}
	
	if($bibliographic_level != "s"){
		//Pour les periodiques on ne garde pas les informations suivantes
		$nbpages=$npages[0];
	}else{
		$nbpages="";
	}
	
	
		/* and at least, the insertion in notices table */
		$sql_ins = "insert into notices (
						typdoc			,
						code        	,
						statut			,
		                tit1            ,
		                tit2            ,
		                tit3            ,
		                tit4            ,
		                tparent_id      ,
		                tnvol           ,
		                ed1_id          ,
		                ed2_id          ,
		                year            ,
		                npages          ,
		                ill             ,
		                size            ,
		                accomp          ,
		                coll_id         ,
		                subcoll_id      ,
		                nocoll          ,
		                mention_edition	,
		                n_gen           ,
		                n_contenu       ,
		                n_resume        ,
		                index_serie,
		                index_sew,
		                index_wew,
		                index_l,
		                indexint,
		                index_matieres,
		                niveau_biblio,
		                niveau_hierar,
		                lien,
		                eformat,
		                origine_catalogage,
		                prix,
		                index_n_gen,
		                index_n_contenu,
		                index_n_resume,
						create_date,
						date_parution
				) values (
						'".$doc_type."',	
						'".addslashes($isbn_OK)."',	
						'".$statutnot."',
		                '".addslashes(clean_string($tit[0]['a']))."',
		                '".addslashes(clean_string($tit[0]['c']))."',
		                '".addslashes(clean_string($tit[0]['d']))."',
		                '".addslashes(clean_string($tit[0]['e']))."',
		                '".$serie_id."',
		                '".addslashes($tnvol_ins)."',
		                 ".$ed1_id." ,
		                 ".$ed2_id." ,
		                '".addslashes($year)."',
		                '".addslashes($nbpages)."',
		                '".addslashes($illustration)."',
		                '".addslashes($taille)."',
		                '".addslashes($mat_accomp)."',
		                 ".$coll_id." ,
		                 ".$subcoll_id." ,
		                '".addslashes($nocoll_ins)."',
		                '".addslashes($mention_edit)."',
		                '".addslashes($n_gen_total     )."',
		             	'".addslashes($n_contenu_total )."',
		             	'".addslashes($n_resume_total  )."',
		             	'".addslashes($index_serie)."',
		                ' ".addslashes($ind_sew)." ',
		                '".addslashes($ind_wew)."',
		                '".addslashes($index_l)."',
		                '".$indexint_id."',
		                '".addslashes($index_matieres)."',
		                '".$bibliographic_level."',
		                '".$hierarchic_level."',
		                '".addslashes($lien[0])."',
		                '".addslashes($eformat[0])."',
		                '".$orinot_id."',
		                '".addslashes($price)."',
		                '".addslashes($index_n_gen)."',
						'".addslashes($index_n_contenu)."',
						'".addslashes($index_n_resume)."',
						sysdate(),
						'".addslashes($date_parution)."'
						)";
		mysql_query($sql_ins) or die ("Couldn't insert into notices ! = ".$sql_ins);
		$notice_id = mysql_insert_id($dbh) ;
		
		audit::insert_creation(AUDIT_NOTICE,$notice_id);
		/* INSERT de la notice OK, on va traiter les auteurs
		70# : personnal : type auteur 70                71# : collectivités : type auteur 71
		1 seul en 700                                   idem pour les déclinaisons          
		n en 701 n en 702
		les 7#0 tombent en auteur principal : responsability_type = 0
		les 7#1 tombent en autre auteur : responsability_type = 1
		les 7#2 tombent en auteur secondaire : responsability_type = 2
		*/
		$aut_array = array();
		/* on compte tout de suite le nbre d'enreg dans les répétables */
		$nb_repet_701=sizeof($aut_701);
		$nb_repet_711=sizeof($aut_711);
		$nb_repet_702=sizeof($aut_702);
		$nb_repet_712=sizeof($aut_712);
		//indicateur["710"];
		/* renseignement de aut0 */
		if ($aut_700[0][a]!="") { /* auteur principal en 700 ? */
			$aut_array[] = array(
				"entree" => $aut_700[0]['a'],
				"rejete" => $aut_700[0]['b'],
				"author_comment" => $aut_700[0]['c']." ".$aut_700[0]['d'],
				"date" => $aut_700[0]['f'],
				"type_auteur" => "70",
				"fonction" => $aut_700[0][4],
				"id" => 0,
				"responsabilite" => 0,
				"ordre" => 0 ) ;
		} elseif ($aut_710[0]['a']!="") { /* auteur principal en 710 ? */
			if(substr($indicateur["710"][0],0,1)=="1")	$type_auteur="72";
				else $type_auteur="71";	
	
			$lieu=$aut_710[0]['e'];
			if(!$lieu)$lieu=$aut_710[0]['k'];		
			$aut_array[] = array(
				"entree" => $aut_710[0]['a'],
				"rejete" => $aut_710[0]['g'],
				"subdivision" => $aut_710[0]['b'],
				"author_comment" => $aut_710[0]['c'],
				"numero" => $aut_710[0]['d'],
				"lieu" => $lieu,
				"ville" => $aut_710[0]['l'],
				"pays" => $aut_710[0]['m'],
				"web" => $aut_710[0]['n'],
				"date" => $aut_710[0]['f'],
				"type_auteur" => $type_auteur,
				"fonction" => $aut_710[0][4],
				"id" => 0,
				"responsabilite" => 0,
				"ordre" => 0 ) ;
		} 
		
		/* renseignement de aut1 */
		for ($i=0 ; $i < $nb_repet_701 ; $i++) {
			$aut_array[] = array(
				"entree" => $aut_701[$i]['a'],
				"rejete" => $aut_701[$i]['b'],
				"author_comment" => $aut_701[$i]['c']." ".$aut_701[$i]['d'],
				"date" => $aut_701[$i]['f'],
				"type_auteur" => "70",
				"fonction" => $aut_701[$i][4],
				"id" => 0,
				"responsabilite" => 1,
				"ordre" => ($i+1) ) ;
		}
		
		for ($i=0 ; $i < $nb_repet_711 ; $i++) {
			if(substr($indicateur["711"][$i],0,1)=="1")	$type_auteur="72";
			else $type_auteur="71";	
	
			$lieu=$aut_711[$i]['e'];
			if(!$lieu)$lieu=$aut_711[$i]['k'];		
			$aut_array[] = array(
				"entree" => $aut_711[$i]['a'],
				"rejete" => $aut_711[$i]['g'],
				"subdivision" => $aut_711[$i]['b'],
				"author_comment" => $aut_711[$i]['c'],
				"numero" => $aut_711[$i]['d'],
				"lieu" => $lieu,
				"ville" => $aut_711[$i]['l'],
				"pays" => $aut_711[$i]['m'],
				"web" => $aut_711[$i]['n'],
				"date" => $aut_711[$i]['f'],
				"type_auteur" => $type_auteur,
				"fonction" => $aut_711[$i][4],
				"id" => 0,
				"responsabilite" => 1,
				"ordre" => ($i+1) ) ;
		}
		
		/* renseignement de aut2 */
		for ($i=0 ; $i < $nb_repet_702 ; $i++) {
			$aut_array[] = array(
				"entree" => $aut_702[$i]['a'],
				"rejete" => $aut_702[$i]['b'],
				"author_comment" => $aut_702[$i]['c']." ".$aut_702[$i]['d'],
				"date" => $aut_702[$i]['f'],
				"type_auteur" => "70",
				"fonction" => $aut_702[$i][4],
				"id" => 0,
				"responsabilite" => 2,
				"ordre" => ($i+1) ) ;
		}
		
		for ($i=0 ; $i < $nb_repet_712 ; $i++) {
			if(substr($indicateur["712"][$i],0,1)=="1")	$type_auteur="72";
			else $type_auteur="71";	
	
			$lieu=$aut_712[$i]['e'];
			if(!$lieu)$lieu=$aut_712[$i]['k'];		
			$aut_array[] = array(
				"entree" => $aut_712[$i]['a'],
				"rejete" => $aut_712[$i]['g'],
				"subdivision" => $aut_712[$i]['b'],
				"author_comment" => $aut_712[$i]['c'],
				"numero" => $aut_712[$i]['d'],
				"lieu" => $lieu,
				"ville" => $aut_712[$i]['l'],
				"pays" => $aut_712[$i]['m'],
				"web" => $aut_712[$i]['n'],
				"date" => $aut_712[$i]['f'],
				"type_auteur" => $type_auteur,
				"fonction" => $aut_712[$i][4],
				"id" => 0,
				"responsabilite" => 2,
				"ordre" => ($i+1) ) ;
		}
		
		// récup des infos auteurs et mise en tableau :
		// appel de la fonction membre d'importation et insertion en table
		$rqt_ins = "insert into responsability (responsability_author, responsability_notice, responsability_fonction, responsability_type, responsability_ordre) VALUES ";
		for ($i=0 ; $i<sizeof($aut_array) ; $i++ ){
			$aut['name']=clean_string($aut_array[$i]['entree']);
			$aut['rejete']=clean_string($aut_array[$i]['rejete']);
			$aut['type']=$aut_array[$i]['type_auteur'];
			$aut['date']=clean_string($aut_array[$i]['date']);		
			$aut['subdivision']=clean_string($aut_array[$i]['subdivision']);
			$aut['numero']=clean_string($aut_array[$i]['numero']);
			$aut['lieu']=clean_string($aut_array[$i]['lieu']);
			$aut['ville']=clean_string($aut_array[$i]['ville']);
			$aut['pays']=clean_string($aut_array[$i]['pays']);
			$aut['web']=clean_string($aut_array[$i]['web']);
			$aut['author_comment']=clean_string($aut_array[$i]['author_comment']);
			$aut_array[$i]["id"] = auteur::import($aut);
			$aut_array[$i]['fonction'] = trim($aut_array[$i]['fonction']);
			if ($aut_array[$i]["id"]) {
				$rqt = $rqt_ins . " ('".$aut_array[$i]["id"]."','".$notice_id."','".addslashes($aut_array[$i]['fonction'])."','".$aut_array[$i]['responsabilite']."','".$aut_array[$i]['ordre']."') " ; 
				@mysql_query($rqt, $dbh);
			}
		}
	
		// Titres uniformes
		global $pmb_use_uniform_title;
		if ($pmb_use_uniform_title) {
			$nb_tu=sizeof($tu_500);
			for ($i=0 ; $i<$nb_tu ; $i++ ) {
				$value_tu[$i]['name'] = $tu_500[$i]['a'];
				$value_tu[$i]['tonalite'] = $tu_500[$i]['u'];
				$value_tu[$i]['comment'] = $tu_500[$i]['n'];
			
				for($j=0;$j<count($tu_500_r[$i]);$j++) {	
					$value_tu[$i]['distrib'][$j]= $tu_500_r[$i][$j];	
				}
				for($j=0;$j<count($tu_500_s[$i]);$j++) {		
					$value_tu[$i]['ref'][$j]= $tu_500_s[$i][$j];			
				}	
				for($j=0;$j<count($tu_500_j[$i]);$j++) {		
					$value_tu[$i]['subdiv'][$j]= $tu_500_j[$i][$j];			
				}			
				$tu_id = titre_uniforme::import($value_tu[$i]);
				if($tu_id) {
					$requete = "INSERT INTO notices_titres_uniformes SET 
					ntu_num_notice='$notice_id', 
					ntu_num_tu='$tu_id', 
					ntu_titre='".addslashes($tu_500[$i]['i'])."', 
					ntu_date='".addslashes($tu_500[$i]['k'])."', 
					ntu_sous_vedette='".addslashes($tu_500[$i]['l'])."', 
					ntu_langue='".addslashes($tu_500[$i]['m'])."', 
					ntu_version='".addslashes($tu_500[$i]['q'])."', 
					ntu_mention='".addslashes($tu_500[$i]['w'])."',
					ntu_ordre=$i 				
					";
					mysql_query($requete, $dbh);		
				}
			}
		}	
			
		global $lang_code		;
		global $org_lang_code		;
		for ($i=0; $i<count($lang_code); $i++) {
			$lang_code[$i]=trim($lang_code[$i]);
			$rqt_ins = "insert into notices_langues (num_notice, type_langue, code_langue) VALUES ('$notice_id',0, '".addslashes($lang_code[$i])."') " ;
			@mysql_query($rqt_ins, $dbh); 
		}
		for ($i=0; $i<count($org_lang_code); $i++) {
			$org_lang_code[$i]=trim($org_lang_code[$i]);
			$rqt_ins = "insert into notices_langues (num_notice, type_langue, code_langue) VALUES ('$notice_id',1, '".addslashes($org_lang_code[$i])."') " ;
			@mysql_query($rqt_ins, $dbh);
		}

	//Si on a un id de notice et qu'il n'est pas dans le tableau des notices créées, on l'ajoute
	if($link_generate && trim($id_unimarc) !== "" && !$notices_crees[$id_unimarc]){
		$notices_crees[$id_unimarc]=$notice_id;
		
	}elseif($link_generate && trim($id_unimarc) !== "" && $notices_crees[$id_unimarc]){
		//Si la notice a déjà été créé (Export des liens dans les notices liées) on remplace celle précédemment par celle en cour de traitement
		 $niveau_biblio=$bibliographic_level.$hierarchic_level;
		 switch ($niveau_biblio) {
		 	case 'm0':
		 		//On a une notice de monographie
		 		$notice_a_supp=$notices_crees[$id_unimarc];
		 		$ma_notice= new notice($notice_a_supp);
		 		$ma_notice->replace($notice_id);
		 		break;
		 	case 's1':
		 		//On a une notice de periodique
		 		$notice_a_supp=$notices_crees[$id_unimarc];
		 		$ma_notice= new serial($notice_a_supp);
		 		$ma_notice->replace($notice_id);
		 		break;
		 	case 'b2':
		 		//On a une notice de bulletin
		 		$notice_a_supp=$notices_crees[$id_unimarc];
		 		//Dans les bulletins
		 		$requete="update bulletins set num_notice='".$notice_id."' where num_notice='".$notice_a_supp."' ";
		 		mysql_query($requete,$dbh);
		 		//Dans les relations entre notice
		 		$requete="update notices_relations set num_notice='".$notice_id."' where num_notice='".$notice_a_supp."'";
		 		mysql_query($requete,$dbh);
		 		$requete="update notices_relations set linked_notice='".$notice_id."' where linked_notice='".$notice_a_supp."'";
		 		mysql_query($requete,$dbh);
		 		notice::del_notice($notice_a_supp);
		 		break;
		 	case 'a2':
		 		//On a une notice d'article
		 		$notice_a_supp=$notices_crees[$id_unimarc];
		 		//Dans les bulletins
		 		$requete="update analysis set analysis_notice='".$notice_id."' where analysis_notice='".$notice_a_supp."' ";
		 		mysql_query($requete,$dbh);
		 		//Dans les relations entre notice
		 		$requete="update notices_relations set num_notice='".$notice_id."' where num_notice='".$notice_a_supp."'";
		 		mysql_query($requete,$dbh);
		 		$requete="update notices_relations set linked_notice='".$notice_id."' where linked_notice='".$notice_a_supp."'";
		 		mysql_query($requete,$dbh);
		 		notice::del_notice($notice_a_supp);
		 		break;
		}
		$notices_crees[$id_unimarc]=$notice_id;
	}

} // fin import_new_notice


/*
 * Importer les liens entre les notices
 */

function import_notice_link(){
	global $notices_liees, $notices_a_creer, $id_unimarc, $dbh, $notices_crees, $bulletins_crees; 
	global $notice_id, $titre_ppal_200, $titre_perio_530a, $champ_210, $bulletins_a_creer;
	global $hierarchic_level, $bibliographic_level, $tit_200d,$bulletin_ex;
	
	if($bibliographic_level == "a" && $hierarchic_level == "2" && $tit_200d[0] == "Article_expl_bulletin"){
		//Quand on est sur le cas de la reconstruction d'un exemplaire de bulletin
		$tab_bull= array();
		$tab_bull = get_infos_notices_liees($notices_liees,'463','bull_expl');

		$id_perio=creer_notice_periodique($tab_bull[0]["0"][0],$tab_bull[0]["t"][1],$tab_bull[0]["x"][0]);
		$bulletin=array();
		$bulletin=array("titre"=>$tab_bull[0]["t"][0],"date"=>$tab_bull[0]["d"][0],"mention"=>$tab_bull[0]["e"][0],"num"=>$tab_bull[0]["v"][0]);
		$bulletin_ex=creer_bulletin($id_perio,$bulletin);
		return;
	}
	
	
	//Traitements des liens entre notices
	$requete = "SELECT * FROM notices WHERE notice_id='".$notice_id."'";
	$res = mysql_query($requete,$dbh);
	while(($notice_creee=mysql_fetch_object($res))){				
		$niveau_biblio = $notice_creee->niveau_biblio.$notice_creee->niveau_hierar;
		$tab_field = $tab_art = $tab_bull = $tab_perio = array();
		
		switch($niveau_biblio){			
			case 's1' :
				//Lien(s) vers Périodique
				$tab_art = get_infos_notices_liees($notices_liees,'464','art');
				$tab_bull = get_infos_notices_liees($notices_liees,'462','bull');
				creer_bulletinage_et_articles($tab_bull,$tab_art);
				break;			
			case 'b2' :
				//Lien(s) vers Notice de bulletin				
				creer_liens_pour_bull_notice($titre_ppal_200, $titre_perio_530a, $champ_210, get_infos_notices_liees($notices_liees,'461','parent','b'));
				break;			
			case 'a2' :
				//Lien(s) vers Article
				$tab_perio = get_infos_notices_liees($notices_liees,'461','perio');	
				$tab_bull = get_infos_notices_liees($notices_liees,'463','bull');	
				$tab_field = array("id_base"=>$id_unimarc, "titre"=> $titre_ppal_200);	
				/*print "xxxxxxxxxxxxxxxxxxxxxxxx".$notice_id;		
				print "<pre>";
				print_r($tab_bull);
				print_r($tab_perio);
				print_r($tab_field);
				print "</pre><br />";*/	
				creer_liens_pour_articles($tab_bull,$tab_perio, $tab_field);
				break;
			default:
				break;
		}
	} 
	
	//Traitement des relations mères/filles	
	$parents = get_infos_notices_liees($notices_liees,'','parent');
	if(count($parents)){
		creer_relation_notice($parents);
	}
	
	$enfants = get_infos_notices_liees($notices_liees,'','child');
	if(count($enfants)){
		creer_relation_notice($enfants);
	}

	//On traite les notices qui ont été mises en attente de création
	if($notices_a_creer[$id_unimarc]) {
		for($i=0;$i<sizeof($notices_a_creer[$id_unimarc]);$i++){
			if($notices_a_creer[$id_unimarc][$i]['lnk'] == 'parent'){		
				//on a une relation vers un parent
				$req_insert_relation = "insert into notices_relations (num_notice, linked_notice, relation_type, rank) 
					values( '".addslashes($notices_a_creer[$id_unimarc][$i]['id_asso'])."', '".addslashes($notice_id)."', '".addslashes($notices_a_creer[$id_unimarc][$i]['type_lnk'])."', '".addslashes($notices_a_creer[$id_unimarc][$i]['rank'])."' )";		
				mysql_query($req_insert_relation,$dbh);
				
			} elseif($notices_a_creer[$id_unimarc][$i]['lnk'] == 'child'){		
				//on a une relation vers un enfant
				$req_insert_relation = "insert into notices_relations (num_notice, linked_notice, relation_type, rank) 
					values( '".addslashes($notice_id)."', '".addslashes($notices_a_creer[$id_unimarc][$i]['id_asso'])."', '".addslashes($notices_a_creer[$id_unimarc][$i]['type_lnk'])."', '".addslashes($notices_a_creer[$id_unimarc][$i]['rank'])."' )";	
				mysql_query($req_insert_relation,$dbh);
				
			} elseif($notices_a_creer[$id_unimarc][$i]['lnk'] == 'art'){							
				//on a un lien d'un article vers un periodique
				if(!$notices_crees[$id_unimarc] ){//On ne peut pas passer par la
					$req_insert_art = "insert into notices (tit1, niveau_biblio, niveau_hierar, npages) 
												values( '".addslashes($notices_a_creer[$id_unimarc][$i]['titre_art'])."', 'a', '2','".addslashes($notices_a_creer[$id_unimarc][$i]['page'])."' )";		
					mysql_query($req_insert_art,$dbh);
					$id_art = mysql_insert_id();
					$notices_crees[$id_unimarc] = $id_art;
				}
				$id_perio=$notices_a_creer[$id_unimarc][$i]['id_asso'];
				$bulletin=array();
				$bulletin=array("titre"=>$notices_a_creer[$id_unimarc][$i]['titre'],"date"=>$notices_a_creer[$id_unimarc][$i]['date'],"mention"=>$notices_a_creer[$id_unimarc][$i]['mention'],"num"=>$notices_a_creer[$id_unimarc][$i]['num']);
				creer_notice_article("","","",$id_art?$id_art:$notice_id,$bulletin,"","",$id_perio);
			} elseif($notices_a_creer[$id_unimarc][$i]['lnk'] == 'perio'){
				//On a un lien d'un periodique vers un article
				if(!$notices_crees[$id_unimarc]){//On ne peut pas passer par la
					$req_insert_perio = "insert into notices (tit1, code, niveau_biblio, niveau_hierar) 
												values( '".addslashes($notices_a_creer[$id_unimarc][$i]['titre_perio'])."', '".addslashes($notices_a_creer[$id_unimarc][$i]['code']).", 's', '1' )";		
					mysql_query($req_insert_perio,$dbh);
					$id_perio = mysql_insert_id();
					$notices_crees[$id_unimarc] = $id_perio;
				}else{
					$id_perio=$notices_crees[$id_unimarc];
				}
				$bulletin=array();
				$bulletin=array("titre"=>$notices_a_creer[$id_unimarc][$i]['titre_bull'],"date"=>$notices_a_creer[$id_unimarc][$i]['date'],"mention"=>$notices_a_creer[$id_unimarc][$i]['mention'],"num"=>$notices_a_creer[$id_unimarc][$i]['num']);
				creer_notice_article("","","",$notices_a_creer[$id_unimarc][$i]['id_asso'],$bulletin,"","",$id_perio);
			}	
		}	
		unset($notices_a_creer[$id_unimarc]);		
	}
	//On rattache le perio a ses notices de bulletin
	if ($bulletins_a_creer[$id_unimarc]){
		$id_perio=$notice_id;
		for($i=0;$i<sizeof($bulletins_a_creer[$id_unimarc]);$i++){
			//Si on décomante la suite et qu on commente la fin ya un bulletin de créé qui ne doit pas exister (test avec export tt lien)
			$bulletin=array();
			$bulletin=array("titre"=>$bulletins_a_creer[$id_unimarc][$i]['titre'],"date"=>$bulletins_a_creer[$id_unimarc][$i]['date_date'],"mention"=>$bulletins_a_creer[$id_unimarc][$i]['mention'],"num"=>$bulletins_a_creer[$id_unimarc][$i]['bull_num']);
			$id_bulletin= creer_bulletin($id_perio,$bulletin,"","");
			creer_lien_notice_bulletin("",$id_perio,$id_bulletin,$bulletins_a_creer[$id_unimarc][$i]['bull_notice'],"");
		}
		unset($bulletins_a_creer[$id_unimarc]);
	}
}
/*
 * Récupération de sous tableaux correspondant aux critères
 */
function get_infos_notices_liees($notices_liees=array(), $cle_uni='', $lien='', $type_lien=''){	
	$result_tab = array();
	$link_tab = array();
	$type_link_tab = array();
	if($cle_uni){
		$result_tab = $notices_liees[$cle_uni];	
		if($lien && $result_tab){
			foreach($result_tab as $field){				
				//on récupère toutes les options du $9 dans un tableau
			   $options=array();
			   for($i=0;$i<sizeof($field['9']);$i++){
					$chaine_parse = explode(':',$field['9'][$i]);
					if($chaine_parse[0] == 'lnk'){
						$options["lien"] = $chaine_parse[1];
					}					
				}
			    if($options["lien"] == $lien) $link_tab[] = $field;	
			} 
			$result_tab = $link_tab;
			if($type_lien){
				foreach($result_tab as $field){				
					//on récupère toutes les options du $9 dans un tableau
				   $options=array();
				   for($i=0;$i<sizeof($field['9']);$i++){
						$chaine_parse = explode(':',$field['9'][$i]);
						if($chaine_parse[0] == 'type_lnk'){
							$options["type_lnk"] = $chaine_parse[1];
						}					
					}
				    if($options["type_lnk"] == $type_lien) $type_link_tab[] = $field;	
				} $result_tab = $type_link_tab;
			}
		}
	} elseif($lien){
		foreach($notices_liees as $fields){
			foreach($fields as $field){
				//on récupère toutes les options du $9 dans un tableau
			   $options=array();
			   for($i=0;$i<sizeof($field['9']);$i++){
					$chaine_parse = explode(':',$field['9'][$i]);
					if($chaine_parse[0] == 'lnk'){
						$options["lien"] = $chaine_parse[1];
					}					
				}
			    if($options["lien"] == $lien) $link_tab[] = $field;	
			}
	   	$result_tab = $link_tab;
		}
		if($type_lien && $result_tab){
			foreach($result_tab as $field){				
				//on récupère toutes les options du $9 dans un tableau
			   $options=array();
			   for($i=0;$i<sizeof($field['9']);$i++){
					$chaine_parse = explode(':',$field['9'][$i]);
					if($chaine_parse[0] == 'type_lnk'){
						$options["type_lnk"] = $chaine_parse[1];
					}					
				}
			    if($options["type_lnk"] == $type_lien) $type_link_tab[] = $field;	
			} 
			$result_tab = $type_link_tab;
		}			
	} elseif($type_lien){
		foreach($notices_liees as $fields){
			foreach($fields as $field){
				//on récupère toutes les options du $9 dans un tableau
			   $options=array();
			   for($i=0;$i<sizeof($field['9']);$i++){
					$chaine_parse = explode(':',$field['9'][$i]);
					if($chaine_parse[0] == 'type_lnk'){
						$options["type_lnk"] = $chaine_parse[1];
					}					
				}
			    if($options["type_lnk"] == $type_lien) $type_link_tab[] = $field;	
			}
	   	$result_tab = $type_link_tab;
		}
	}
	return $result_tab;
}
/*
 *  Fonction qui génère les liens pour les notices de bulletin
 */
function creer_liens_pour_bull_notice($titre200=array(), $titre530=array(), $champ210=array(), $tab_perio=array()){
	global $notice_id, $dbh, $notices_crees, $id_unimarc, $bulletins_a_creer, $bulletins_crees;
	if(!$tab_perio){
		if(!$notices_crees[$id_unimarc]){//On passe ici si on importe les liens pour une notice de bulletin qui n'a ni numéro ni lien vers un periodique (très improbable)
			//On passe la notice en monographie
			$requete="update notices set niveau_biblio='m' and niveau_hierar='0' where notice_id='".$notice_id."'";
			mysql_query($requete,$dbh);
			
		}else{
			if(!trim($titre530[0]))$titre530[0]="Sans titre";
			$id_perio=creer_notice_periodique(0,trim($titre530[0]),"");
			$bulletin=array();
			$bulletin=array("titre"=>trim($titre200[0]['i']),"date"=>trim($champ210[0]['h']),"mention"=>trim($champ210[0]['d']),"num"=>trim($titre200[0]['h']));
			$id_bull=creer_bulletin($id_perio,$bulletin);
			creer_lien_notice_bulletin($id_unimarc,$id_perio,$id_bull,$notice_id,"");
		}
	} else {
		if(!$notices_crees[get_valeur_champ9($tab_perio[0]['9'],'id')] && !$tab_perio[0]['0']['0']){
			//On créé le periodique car il n'est pas créé et n'est pas à créé
			if(!trim($titre530[0]))$titre530[0]="Sans titre";
			$id_perio=creer_notice_periodique(0,trim($titre530[0]),"");
			$bulletin=array();
			$bulletin=array("titre"=>trim($titre200[0]['i']),"date"=>trim($champ210[0]['h']),"mention"=>trim($champ210[0]['d']),"num"=>trim($titre200[0]['h']));
			$id_bull=creer_bulletin($id_perio,$bulletin);
			creer_lien_notice_bulletin($id_unimarc,$id_perio,$id_bull,$notice_id,"");
		}elseif($notices_crees[get_valeur_champ9($tab_perio[0]['9'],'id')]){
			//La notice de perio est déja créé
			$id_perio=$notices_crees[get_valeur_champ9($tab_perio[0]['9'],'id')];
			$bulletin=array();
			$bulletin=array("titre"=>trim($titre200[0]['i']),"date"=>trim($champ210[0]['h']),"mention"=>trim($champ210[0]['d']),"num"=>trim($titre200[0]['h']));
			$id_bull=creer_bulletin($id_perio,$bulletin);
			creer_lien_notice_bulletin($id_unimarc,$id_perio,$id_bull,$notice_id,"");
		}elseif($tab_perio[0]['0']['0']){
			//Le lien sera a refaire plus tard
			$bulletins_a_creer[$tab_perio[0]['0'][0]][] = array ("bull_notice"=>$notices_crees[$id_unimarc], "bull_num"=>$titre200[0]['h'], "date_date"=>$champ210[0]['h'],"mention"=>$champ210[0]['d'],"titre"=>$titre200[0]['i']);
		}else{
			//Si j'ai un bulletin avec un 461 qui ne rentre pas dans les autres cas je passe le bulletin en monographie (cas très peu probable)
			$requete="select bulletin_id from bulletins where num_notice='".addslashes($notice_id)."' ";
			$res = mysql_query($requete,$dbh);
			if (!mysql_num_rows($res))  {
				//Si il n'est pas déja relié je le passe en monographie sinon je n'y touche pas
				$requete="update notices set niveau_biblio='m', niveau_hierar='0' where notice_id='".$notice_id."' ";
				mysql_query($requete,$dbh);
			}
		}
	}
}
/*
 *  Génère la création des liens pour les articles
 */
function creer_liens_pour_articles($tab_bull=array(),$tab_perio=array(), $tab_field=array()){
	global $notice_id, $dbh, $notices_crees, $bulletins_crees, $notices_a_creer,$serie,$champ210;
		if(!$tab_bull && !$tab_perio){
			//On regarde si on a les informations de bulletinage dans le 461
			if(trim($serie[0]["t"]) and trim($serie[0]["v"])){
				//J'ai les informations pour recréer le bulletinage
				$bulletin=array();
				$bulletin=array("titre"=>"Bulletin N°".trim($serie[0]["v"]),"date"=>"","mention"=>trim($champ210[0]['d']),"num"=>trim($serie[0]["v"]));
				creer_notice_article("","","",$notice_id,$bulletin,trim($serie[0]["t"]),"",0);	
			}else{
				//Si elle a été créée mais qu'elle n'est pas a créer
				if( !$tab_field['id_base'] || ($notices_crees[$tab_field['id_base']] && !$notices_a_creer[$tab_field['id_base']])){
					//On regarde si elle n'est pas reliée a un bulletin
					$requete="select analysis_bulletin from analysis where analysis_notice='".addslashes($notices_crees[$tab_field['id_base']])."' ";
					$res=mysql_query($requete,$dbh);
					if(!mysql_num_rows($res)){
						//Si elle n'est pas reliée on la passe en monographie, sinon c'est bien un article
						$requete="update notices set niveau_biblio='m', niveau_hierar='0' where notice_id='".addslashes($notice_id)."' ";
						mysql_query($requete,$dbh);
					}
				}elseif(!$notices_crees[$tab_field['id_base']] && !$notices_a_creer[$tab_field['id_base']]){
					//on ne doit pas passer par là				
					$req_insert_art = "insert into notices (tit1, niveau_biblio, niveau_hierar) 
												values( '".addslashes($tab_field['titre'][0]['a'])."', 'm', '0' )";		
					mysql_query($req_insert_art,$dbh);
					$notices_crees[$tab_field['id_base']] = mysql_insert_id();
				}
			}
		} elseif($tab_bull && !$tab_perio){
			//On créé un periodique sans titre (On regarde avant si on en a pas déja créé une)
			$bulletin=array();
			$bulletin=array("titre"=>$tab_bull[0]['v'][0],"date"=>$tab_bull[0]['d'][0],"mention"=>$tab_bull[0]['e'][0],"num"=>$tab_bull[0]['v'][0]);
			creer_notice_article("","","",$notice_id,$bulletin,"Sans titre","",0);
		} elseif(!$tab_bull && $tab_perio){
			//On créé un bulletin générique pour rattacher les articles au pério		
			if(!$notices_crees[get_valeur_champ9($tab_perio[0]['9'],'id')] && !$tab_perio[0]['0'][0]){// Si le periodique n'est pas déja créé et si il ne sera pas a créer	
				$id_perio=creer_notice_periodique(get_valeur_champ9($tab_perio[0]['9'],'id'),$tab_perio[0]['t'][0],$tab_perio[0]['x'][0]);
				$bulletin=array();
				$bulletin=array("titre"=>"bull_générique","date"=>"0000-00-00","mention"=>"00/00/0000","num"=>"0");
				creer_notice_article("","","",$notice_id,$bulletin,"","",$id_perio);
			}elseif($notices_crees[get_valeur_champ9($tab_perio[0]['9'],'id')]){//Si il est déja créé
				$id_perio=$notices_crees[get_valeur_champ9($tab_perio[0]['9'],'id')];
				$bulletin=array();
				$bulletin=array("titre"=>"bull_générique","date"=>"0000-00-00","mention"=>"00/00/0000","num"=>"0");
				creer_notice_article("","","",$notice_id,$bulletin,"","",$id_perio);
			}else{
				// Les liens seront à creer plus tard pour cet article
				$id=get_valeur_champ9($tab_perio[0]['9'],'id');
				$type_lien =get_valeur_champ9($tab_perio[0]['9'],'type_lnk');
				$lien=get_valeur_champ9($tab_perio[0]['9'],'lnk');
				$rank=get_valeur_champ9($tab_perio[0]['9'],'rank')*1;
				$notices_a_creer[$id][] = array( "type_lnk"=> $type_lien, "lnk"=> $lien, "rank"=>$rank, "titre_perio"=>$tab_perio[0]['t'][0], 
										"code"=>$tab_perio[0]['x'][0], "id_asso"=>$notice_id, "num"=>"0",
										"mention"=>"00/00/0000","date"=>"0000-00-00","titre_bull"=>"bull_générique");
			}
		} else {
			for($i=0;$i<sizeof($tab_perio);$i++){	
				if(!$notices_crees[get_valeur_champ9($tab_perio[$i]['9'],'id')] && !$tab_perio[$i]['0'][0]){		
					//On a les deux liens, on regarde si le perio existe déjà dans la base
					$id_perio=creer_notice_periodique(get_valeur_champ9($tab_perio[$i]['9'],'id'),$tab_perio[$i]['t'][0],$tab_perio[$i]['x'][0]);
					$bulletin=array();
					$bulletin=array("titre"=>$tab_bull[$i]['t'][0],"date"=>$tab_bull[$i]['d'][0],"mention"=>$tab_bull[$i]['e'][0],"num"=>$tab_bull[$i]['v'][0]);
					creer_notice_article("","","",$notice_id,$bulletin,"","",$id_perio);
				} else{
					if($notices_crees[get_valeur_champ9($tab_perio[$i]['9'],'id')]){
						//Si il est créé on récupère son identifiant
						$id_perio=	$notices_crees[get_valeur_champ9($tab_perio[$i]['9'],'id')];
						//On regarde si le bulletin est déja créé
						$bulletin=array();
						$bulletin=array("titre"=>$tab_bull[$i]['t'][0],"date"=>$tab_bull[$i]['d'][0],"mention"=>$tab_bull[$i]['e'][0],"num"=>$tab_bull[$i]['v'][0]);
						creer_notice_article("","","",$notice_id,$bulletin,"","",$id_perio);
					} else {
						$id=get_valeur_champ9($tab_perio[$i]['9'],'id');
						$type_lien =get_valeur_champ9($tab_perio[$i]['9'],'type_lnk');
						$lien=get_valeur_champ9($tab_perio[$i]['9'],'lnk');
						$rank=get_valeur_champ9($tab_perio[$i]['9'],'rank')*1;
						$notices_a_creer[$id][] = array( "type_lnk"=> $type_lien, "lnk"=> $lien, "rank"=>$rank, "titre_perio"=>$tab_perio[$i]['t'][0], 
												"code"=>$tab_perio[$i]['x'][0], "id_asso"=>$notice_id, "num"=>$tab_bull[$i]['v'][0],
												"mention"=>$tab_bull[$i]['e'][0],"date"=>$tab_bull[$i]['d'][0],"titre_bull"=>$tab_bull[$i]['t'][0]);
					}
				}
			}
		}
}

/*
 * Fonction qui génère la création du bulletinage et les articles pour les périos
 */
function creer_bulletinage_et_articles($bull=array(), $art=array()){
	global $notice_id, $dbh, $notices_a_creer, $notice_id, $bulletins_crees, $notices_crees,$tit_200a,$isbn_OK,$id_unimarc,$msg;
	//On regarde si la notice n'existe pas déjà dans la base
	$requete="select notice_id from notices where tit1 LIKE '".addslashes(clean_string(implode (" ; ",$tit_200a)))."' and niveau_biblio='s' and niveau_hierar='1' and notice_id !='".addslashes($notice_id)."' ";
	if($isbn_OK) $requete.= "and code = '".addslashes($isbn_OK)."'";
	$res=mysql_query($requete,$dbh);
	if(mysql_num_rows($res)){
		$id_perio_garde=0;
		while (($r=mysql_fetch_object($res)) && !$id_perio_garde) {
			if(!array_search($r->notice_id,$notices_crees)){
				//Si le periodique ne fait pas parti des notices créées (il était déja dans la base)
				$id_perio_garde=$r->notice_id;
			}
		}
		if($id_perio_garde){
			mysql_query("insert into error_log (error_origin, error_text) values ('import_".addslashes(SESSid).".inc', '".$msg[542]." $id_unimarc "." $isbn_OK ".addslashes(clean_string(implode (" ; ",$tit_200a)))."') ",$dbh) ;
			//Si j'ai déja une notice dans la base avec ce titre et ce code je supprime celle que je suis en train d'importer
			$perio_traite = new serial($notice_id);
			$perio_traite->replace($id_perio_garde);
			$perio_traite->serial_delete();
			//Je travail avec le periodique qui était dans la base
			$notice_id=$id_perio_garde;
			$notices_crees[$id_unimarc]=$id_perio_garde;
		}
	}
	if($bull){
		for($i=0;$i<sizeof($bull);$i++){
			$bulletin=array();
			$bulletin=array("titre"=>$bull[$i]['t'][0],"date"=>$bull[$i]['d'][0],"mention"=>$bull[$i]['e'][0],"num"=>$bull[$i]['v'][0]);
			creer_bulletin($notice_id,$bulletin,"","");
		}
	}
	if($art){
		for($i=0;$i<sizeof($art);$i++){
			if(!$notices_crees[get_valeur_champ9($art[$i]['9'],'id')] && !$art[$i]['0'][0]){
				$bulletin=array();
				$bulletin=array("titre"=>$art[$i]['t'][1],"date"=>$art[$i]['d'][0],"mention"=>$art[$i]['e'][0],"num"=>$art[$i]['v'][0]);
				creer_notice_article(get_valeur_champ9($art[$i]['9'],'id'),$art[$i]['t'][0],get_valeur_champ9($art[$i]['9'],'page'),0,$bulletin,"","",$notice_id);
			}elseif($art[$i]['0'][0]){
				$id=get_valeur_champ9($art[$i]['9'],'id');
				$type_lien =get_valeur_champ9($art[$i]['9'],'type_lnk');
				$lien=get_valeur_champ9($art[$i]['9'],'lnk');
				$rank=get_valeur_champ9($art[$i]['9'],'rank')*1;
				$page=get_valeur_champ9($art[$i]['9'],'page');
				//On enregistre les informations pour créer l'article plus tard
				$notices_a_creer[$id][] = array( "type_lnk"=> $type_lien, "lnk"=> $lien, "rank"=>$rank, "titre_art"=>$art[$i]['t'][0], "titre"=>$art[$i]['t'][1], "num"=>$art[$i]['v'][0], "mention"=>$art[$i]['e'][0], "date"=>$art[$i]['d'][0], "id_asso"=>$notice_id, "page"=>$page); 
			}	
		}
	}
}
			
/*
 * Récupère la valeur du champ $9 en fonction du critère
 */
function get_valeur_champ9($champ9=array(),$crit=''){
	$options=array();
	$options[$crit]='';
   for($i=0;$i<sizeof($champ9);$i++){
		$chaine_parse = explode(':',$champ9[$i]);
		if($chaine_parse[0] == $crit) {					
			$options[$crit] = $chaine_parse[1];
		}								
	}	
	return $options[$crit];
}

/*
 * Créer les relations entre notice
 */
function creer_relation_notice($notice_liee=array()){
	global $notice_id,$notices_crees;
	for($i=0;$i<count($notice_liee);$i++){
		$id=get_valeur_champ9($notice_liee[$i]['9'],'id');
		$type_lien =get_valeur_champ9($notice_liee[$i]['9'],'type_lnk');
		$lien=get_valeur_champ9($notice_liee[$i]['9'],'lnk');
		$rank=get_valeur_champ9($notice_liee[$i]['9'],'rank')*1;
		$id_mere=0;
		$id_fille=0;
		$id_notice_liee=0;
		$ancien_id=get_valeur_champ9($notice_liee[$i]['9'],'id');
		if($notices_crees[$ancien_id]){
			//Si la notice lié est créé
			$id_notice_liee=$notices_crees[$ancien_id];
		}elseif($notice_liee[$i]['0'][0]){
			//Le lien sera a creer plus tard
			$notices_a_creer[$ancien_id][] = array( "type_lnk"=> $type_lien, "lnk"=> $lien, "rank"=>$rank, "id_asso"=>$notice_id);
		}else{
			//Il faut la créer
			$niveau_bilio=get_valeur_champ9($notice_liee[$i]['9'],'bl');
			switch ( $niveau_bilio ) {
				case 'm0':
					$id_notice_liee=creer_notice_monographie($ancien_id,$notice_liee[$i]['t'][0],$notice_liee[$i]['y'][0]);
					break;
				case 's1':
					$id_notice_liee=creer_notice_periodique($ancien_id,$notice_liee[$i]['t'][0],$notice_liee[$i]['x'][0]);
					break;
				case 'a2':
					$bulletin=array("titre"=>$notice_liee[$i]['t'][1],"date"=>$notice_liee[$i]['d'][0],"mention"=>$notice_liee[$i]['e'][0],"num"=>$notice_liee[$i]['v'][0]);
					$id_notice_liee=creer_notice_article($ancien_id,$notice_liee[$i]['t'][0],"","",$bulletin,$notice_liee[$i]['t'][2],$notice_liee[$i]['x'][0],"");
					break;
				case 'b2':
					$bulletin=array("titre"=>$notice_liee[$i]['t'][1],"date"=>$notice_liee[$i]['d'][0],"mention"=>$notice_liee[$i]['e'][0],"num"=>$notice_liee[$i]['v'][0]);
					$id_notice_liee=creer_notice_bulletin($ancien_id,$notice_liee[$i]['t'][0],$bulletin,$notice_liee[$i]['t'][2],$notice_liee[$i]['x'][0]);
					break;
			}
		}

		if($lien == 'child'){
			//Si on a un lien mere -> fille
			$id_mere=$notice_id;
			$id_fille=$id_notice_liee;
		}elseif($lien == 'parent'){
			//Sinon on a un lien fille -> mere
			$id_mere=$id_notice_liee;
			$id_fille=$notice_id;
		}

		if($id_mere && $id_fille){
			//Je créer le lien entre les deux notices
			$requete="insert into notices_relations(num_notice,linked_notice,relation_type,rank) values ('".$id_fille."','".$id_mere."','".addslashes($type_lien)."','".addslashes($rank)."')";
			mysql_query($requete);
		}
	}
}
/*
 * Créer les notices de monographie
 */
function creer_notice_monographie($ancien_id=0,$titre="",$code=""){
	global $notices_crees,$dbh;
	$requete="insert into notices (tit1, code, niveau_biblio, niveau_hierar) values ('".addslashes($titre)."','".addslashes($code)."', 'm', '0')";
	mysql_query($requete,$dbh);
	$id=mysql_insert_id();
	// Mise à jour de la table "notices_global_index"
	notice::majNoticesGlobalIndex($id);
	// Mise à jour de la table "notices_mots_global_index"
	notice::majNoticesMotsGlobalIndex($id);
	$notices_crees[$ancien_id]=$id;
	return $id;
}
/*
 * Créer les notices de periodique
 */
function creer_notice_periodique($ancien_id=0,$titre="",$code=""){
	global $notices_crees,$dbh;
	//On regarde si il existe
	if($ancien_id){
		if($notices_crees[$ancien_id]){
			$id_perio=$notices_crees[$ancien_id];
		}else{
			$requete="select notice_id from notices where tit1 LIKE '".addslashes(clean_string($titre))."' and niveau_biblio='s' and niveau_hierar='1'";
			if($code) $requete.=" and code='".addslashes($code)."'";
			$res_perio = mysql_query($requete,$dbh);
			if (mysql_num_rows($res_perio))  {
				$id_perio = mysql_result($res_perio,0,0);
			}else{
				$requete="insert into notices (tit1,code, niveau_biblio, niveau_hierar) values('".addslashes(clean_string($titre))."','".addslashes($code)."', 's', '1')";
				mysql_query($requete, $dbh);
				$id_perio = mysql_insert_id();
				// Mise à jour de la table "notices_global_index"
				notice::majNoticesGlobalIndex($id_perio);
				// Mise à jour de la table "notices_mots_global_index"
				notice::majNoticesMotsGlobalIndex($id_perio);
			}
			$notices_crees[$ancien_id]=$id_perio;
		}
	}else{
		$requete="select notice_id from notices where tit1 LIKE '".addslashes(clean_string($titre))."' and niveau_biblio='s' and niveau_hierar='1'";
		if($code) $requete.=" and code='".addslashes($code)."'";
		$res_perio = mysql_query($requete,$dbh);
		if (mysql_num_rows($res_perio))  {
			$id_perio = mysql_result($res_perio,0,0);
		}else{
			$requete="insert into notices (tit1,code, niveau_biblio, niveau_hierar) values('".addslashes(clean_string($titre))."','".addslashes($code)."', 's', '1')";
			mysql_query($requete, $dbh);
			$id_perio = mysql_insert_id();
			// Mise à jour de la table "notices_global_index"
			notice::majNoticesGlobalIndex($id_perio);
			// Mise à jour de la table "notices_mots_global_index"
			notice::majNoticesMotsGlobalIndex($id_perio);
		}
	}
	return $id_perio;
}
/*
 * Créer les bulletins
 * Bulletin est un tableau avec les clés : titre, date, mention, num
 */
function creer_bulletin($id_perio=0,$bulletin=array(),$titre_perio="",$code_perio=""){
	global $bulletins_crees,$dbh;
	
	if(!$id_perio){
		//Si je n'ai pas d'identifiant de periodique je vais en chercher un ou le créer
		$id_perio=creer_notice_periodique(0,$titre_perio,$code_perio);
	}
	
	if(!$bulletins_crees[$id_perio][$bulletin["num"]][$bulletin["date"].$bulletin["mention"]]){
		//Si il n'est pas déja créé, on regarde si le bulletin est présent dans la base avant de le créer
		$requete="select bulletin_id from bulletins where bulletin_notice='".addslashes($id_perio)."' and bulletin_numero='".addslashes($bulletin["num"])."' and mention_date='".addslashes($bulletin["mention"])."'";
		if($bulletin["date"])$requete.=" and date_date='".addslashes($bulletin["date"])."' ";
		$res=mysql_query($requete, $dbh);
		if(mysql_num_rows($res)){
			$id_bull = mysql_result($res,0,0);
		}else{
			$requete_bulletin = "insert into bulletins (bulletin_numero, bulletin_notice, mention_date, date_date, bulletin_titre) values ('".addslashes($bulletin["num"])."', '".addslashes($id_perio)."', '".addslashes($bulletin["mention"])."', '".addslashes($bulletin["date"])."', '".addslashes($bulletin["titre"])."')";	
			mysql_query($requete_bulletin, $dbh);
			$id_bull = mysql_insert_id();
		}
		$bulletins_crees[$id_perio][$bulletin["num"]][$bulletin["date"].$bulletin["mention"]] = $id_bull;
	} else {
		$id_bull = $bulletins_crees[$id_perio][$bulletin["num"]][$bulletin["date"].$bulletin["mention"]];
	}
	return $id_bull;
}

/*
 * Créer les notices d'article
 * Bulletin est un tableau avec les clés : titre, date, mention, num
 */
function creer_notice_article($ancien_id=0,$titre_article="",$npage_article="",$id_article=0,$bulletin=array(),$titre_perio="",$code_perio="",$id_perio=0){
	global $notices_crees,$dbh;
	if($ancien_id){
		if($notices_crees[$ancien_id]){
			$id_article=$notices_crees[$ancien_id];
		}else{
			//On va chercher le bulletin
			if($id_perio){
				$id_bulletin=creer_bulletin($id_perio,$bulletin,$titre_perio,$code_perio);
			}else{
				$id_bulletin=creer_bulletin(0,$bulletin,$titre_perio,$code_perio);
			}
			//On créer l'article
			if(!$id_article){
				$requete="insert into notices (tit1, npages, niveau_biblio, niveau_hierar) values ('".addslashes(clean_string($titre_article))."','".addslashes($npage_article)."', 'a', '2')";
				mysql_query($requete,$dbh);
				$id_article=mysql_insert_id();
				// Mise à jour de la table "notices_global_index"
				notice::majNoticesGlobalIndex($id_article);
				// Mise à jour de la table "notices_mots_global_index"
				notice::majNoticesMotsGlobalIndex($id_article);
			}
			//On créer le lien entre le bulletin et l'article
			$requete="insert into analysis (analysis_bulletin, analysis_notice) values ( '".addslashes($id_bulletin)."', '".addslashes($id_article)."' )";
			mysql_query($requete,$dbh);
			$notices_crees[$ancien_id]=$id_article;
		}
	}else{
		//On va chercher le bulletin
		if($id_perio){
			$id_bulletin=creer_bulletin($id_perio,$bulletin,$titre_perio,$code_perio);
		}else{
			$id_bulletin=creer_bulletin(0,$bulletin,$titre_perio,$code_perio);
		}
		//On créer l'article
		if(!$id_article){
			$requete="insert into notices (tit1, npages, niveau_biblio, niveau_hierar) values ('".addslashes(clean_string($titre_article))."','".addslashes($npage_article)."', 'a', '2')";
			mysql_query($requete,$dbh);
			$id_article=mysql_insert_id();
			// Mise à jour de la table "notices_global_index"
			notice::majNoticesGlobalIndex($id_article);
			// Mise à jour de la table "notices_mots_global_index"
			notice::majNoticesMotsGlobalIndex($id_article);
		}
		//On créer le lien entre le bulletin et l'article
		$requete="insert into analysis (analysis_bulletin, analysis_notice) values ( '".addslashes($id_bulletin)."', '".addslashes($id_article)."' )";
		mysql_query($requete,$dbh);
	}
	return $id_article;
}

/*
 * Créer les notices de bulletin
 * Bulletin est un tableau avec les clés : titre, date, mention, num
 */
function creer_notice_bulletin($ancien_id=0,$titre_notice_bulletin="",$bulletin=array(),$titre_perio="",$code_perio=""){
	global $notices_crees,$dbh;
	if($ancien_id){
		if($notices_crees[$ancien_id]){
			$id_notice_bulletin=$notices_crees[$ancien_id];
		}else{
			//On va chercher le bulletin
			$id_perio=creer_notice_periodique(0,$titre_perio,$code_perio);
			$id_bulletin=creer_bulletin($id_perio,$bulletin,$titre_perio,$code_perio);
			$id_notice_bulletin= creer_lien_notice_bulletin($ancien_id,$id_perio,$id_bulletin,0,$titre_notice_bulletin);
		}
	}else{
		//On va chercher le bulletin
		$id_perio=creer_notice_periodique(0,$titre_perio,$code_perio);
		$id_bulletin=creer_bulletin($id_perio,$bulletin,$titre_perio,$code_perio);
		$id_notice_bulletin= creer_lien_notice_bulletin($ancien_id,$id_perio,$id_bulletin,0,$titre_notice_bulletin);
	}
	return $id_notice_bulletin;
}

/*
 * Faire les liens d'une notice de bulletin
 */

function creer_lien_notice_bulletin($ancien_id=0,$id_perio=0,$id_bulletin=0,$id_not_bull=0,$titre_not_bull=""){
	global $dbh, $msg,$isbn_OK,$tit_200a,$notice_id,$notices_crees;
	//On control que ce bulletin n'a pas déjà une notice
	$requete="select num_notice from bulletins where bulletin_id='".$id_bulletin."'";
	if($id_not_bull)$requete.=" and num_notice!='".$id_not_bull."'";
	$res=mysql_query($requete,$dbh);
	if(mysql_num_rows($res) && mysql_result($res,0,0)){
		//Si j'ai déja une notice associé à ce bulletin je la récupère
		if($id_not_bull){
			//Si j'ai aussi un identifiant de notice de bulletin, je supprime le plus récent
			notice::del_notice($id_not_bull);
			mysql_query("insert into error_log (error_origin, error_text) values ('import_".addslashes(SESSid).".inc', '".$msg[542]." $id_unimarc "." $isbn_OK ".addslashes(clean_string(implode (" ; ",$tit_200a)))."') ",$dbh) ;
			$id_notice_bulletin=mysql_result($res,0,0);//A voir pr modif
		}else{
			$id_notice_bulletin= mysql_result($res,0,0);
		}
		$notice_id=$id_notice_bulletin;
	}else{
		if($titre_not_bull){
			//Si j'ai un titre je créé la notice de bulletin
			$requete="insert into notices (tit1,niveau_biblio, niveau_hierar) values ('".addslashes(clean_string($titre_not_bull))."', 'b', '2')";
			mysql_query($requete,$dbh);
			$id_notice_bulletin=mysql_insert_id();
			// Mise à jour de la table "notices_global_index"
			notice::majNoticesGlobalIndex($id_notice_bulletin);
			// Mise à jour de la table "notices_mots_global_index"
			notice::majNoticesMotsGlobalIndex($id_notice_bulletin);
		}else{
			$id_notice_bulletin=$id_not_bull;
		}
		//On créer le lien entre le bulletin et la notice de bulletin
		$requete="update bulletins set num_notice='".$id_notice_bulletin."' where bulletin_id='".$id_bulletin."'";
		mysql_query($requete,$dbh);
	}
	$notices_crees[$ancien_id]=$id_notice_bulletin;
	//Lien entre la notice de bulletin et la notice de periodique
	$requete="insert into notices_relations(num_notice,linked_notice,relation_type) values ('".$id_notice_bulletin."','".$id_perio."','b')";
	mysql_query($requete);
	return $id_notice_bulletin;
}