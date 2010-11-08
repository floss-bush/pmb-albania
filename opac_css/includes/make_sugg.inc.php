<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: make_sugg.inc.php,v 1.13 2009-11-30 16:44:16 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path.'/classes/suggestions_categ.class.php');
require_once($base_path.'/classes/docs_location.class.php');

$tooltip = str_replace("\\n","<br />",$msg["empr_sugg_ko"]);
$sug_form= "<div id='make_sugg'>
<h3>".htmlentities($msg['empr_make_sugg'], ENT_QUOTES, $charset)."</h3>";
if($opac_show_help) $sug_form .= "
<div class='row'>
	$tooltip
</div>";
$sug_form .= "
<div id='make_sugg-container'>
<form action=\"do_resa.php\" method=\"post\" name=\"empr_sugg\" enctype='multipart/form-data'>
	<input type='hidden' name='id_notice' value='!!id_notice!!' />
	<table width='60%' cellpadding='5'>
		<tr>	
			<td align=right>".htmlentities($msg["empr_sugg_tit"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"hidden\" name=\"lvl\" value=\"valid_sugg\"/>
				<input type=\"text\" id=\"tit\" name=\"tit\" size=\"50\" border=\"0\" value=\"!!titre_sugg!!\"/>
			</td>
		</tr>
		<tr>
			<td align=right>".htmlentities($msg["empr_sugg_aut"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"aut\" name=\"aut\" size=\"50\" border=\"0\" value=\"!!auteur_sugg!!\"/>
			</td>
		</tr>
		<tr>
			<td align=right>".htmlentities($msg["empr_sugg_edi"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"edi\" name=\"edi\" size=\"50\" border=\"0\" value=\"!!editeur_sugg!!\"/>
			</td>
		</tr>
		<tr>
			<td align=right>".htmlentities($msg["empr_sugg_code"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"code\" name=\"code\" size=\"20\" border=\"0\" value=\"!!code_sugg!!\"/>
			</td>
		</tr>
		<tr>
			<td align=right>".htmlentities($msg["empr_sugg_prix"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"prix\" name=\"prix\" size=\"20\" border=\"0\" value=\"!!prix_sugg!!\"/>
			</td>
		</tr>
		<tr>
			<td align=right>".htmlentities($msg["empr_sugg_url"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type='text' id=\"url_sug\" name=\"url_sug\" size=\"50\" border=\"0\" value=\"\"/>
			</td>
		</tr>
		<tr>
			<td align=right>".htmlentities($msg["empr_sugg_comment"], ENT_QUOTES, $charset)."<br /><i>".htmlentities($msg["empr_sugg_comment_jt"], ENT_QUOTES, $charset)."</i></td>
			<td>
				<textarea id=\"comment\" name=\"comment\" cols=\"50\" rows='4' wrap='virtual'></textarea>
			</td>
		</tr>
		<tr>
			<td align=right>".htmlentities($msg["empr_sugg_datepubli"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type='text' id='date_publi' name='date_publi'>
				<input type='button' class='bouton' id='date_publi_sug' name='date_publi_sug' value='...' onClick=\"window.open('./select.php?what=calendrier&caller=empr_sugg&param1=date_publi&param2=date_publi&auto_submit=NO&date_anterieure=YES', 'date_publi', 'toolbar=no, dependent=yes, width=250,height=250, resizable=yes')\"/>
			</td>
		</tr>		
		";
if(!$_SESSION["id_empr_session"]) {
	
	$sug_form.= "
		<tr>
			<td align=right>".htmlentities($msg["empr_sugg_mail"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"mail\" name=\"mail\" size=\"50\" border=\"0\" value=\"".$empr_mail."\"/>
			</td>
		</tr>";
}


if($id_notice){
	$requete = "SELECT tit1 as titre, ed_name as editeur, CONCAT(author_name,' ',author_rejete) as auteur, prix, code 
	FROM notices LEFT JOIN responsability ON responsability_notice=notice_id 
	LEFT JOIN authors ON responsability_author=author_id LEFT JOIN publishers ON ed1_id=ed_id
	WHERE notice_id=".$id_notice;
	$result = mysql_query($requete,$dbh);
	while($sug=mysql_fetch_object($result)){
		if(!$sug->titre) $sug->titre='';
		$sug_form = str_replace('!!titre_sugg!!',htmlentities($sug->titre,ENT_QUOTES,$charset),$sug_form);
		if(!$sug->editeur) $sug->editeur='';
		$sug_form = str_replace('!!editeur_sugg!!',htmlentities($sug->editeur,ENT_QUOTES,$charset),$sug_form);
		if(!$sug->auteur) $sug->auteur='';
		$sug_form = str_replace('!!auteur_sugg!!',htmlentities($sug->auteur,ENT_QUOTES,$charset),$sug_form);
		if(!$sug->code) $sug->code='';
		$sug_form = str_replace('!!code_sugg!!',htmlentities($sug->code,ENT_QUOTES,$charset),$sug_form);
		if(!$sug->prix) $sug->prix='';
		$sug_form = str_replace('!!prix_sugg!!',htmlentities($sug->prix,ENT_QUOTES,$charset),$sug_form);
		$sug_form = str_replace('!!id_notice!!',$id_notice,$sug_form);
	}
} else {
	$sug_form = str_replace('!!titre_sugg!!','',$sug_form);
	$sug_form = str_replace('!!editeur_sugg!!','',$sug_form);
	$sug_form = str_replace('!!auteur_sugg!!','',$sug_form);
	$sug_form = str_replace('!!code_sugg!!','',$sug_form);
	$sug_form = str_replace('!!prix_sugg!!','',$sug_form);
	$sug_form = str_replace('!!id_notice!!','',$sug_form);
}

if ($opac_sugg_categ == '1' ) {
	
	if (suggestions_categ::exists($opac_sugg_categ_default) ){
		$default_categ = $opac_sugg_categ_default;
	} else {
		$default_categ = '1';
	}
	//Selecteur de categories
	if ($acquisition_sugg_categ != '1') {
		$sel_categ="";
	} else {
		$tab_categ = suggestions_categ::getCategList();
		$sel_categ = "<select class='saisie-25em' id='num_categ' name='num_categ' >";
		foreach($tab_categ as $id_categ=>$lib_categ){
			$sel_categ.= "<option value='".$id_categ."' ";
			if ($id_categ==$default_categ) $sel_categ.= "selected='selected' "; 
			$sel_categ.= "> ";
			$sel_categ.= htmlentities($lib_categ, ENT_QUOTES, $charset)."</option>";
		}
		$sel_categ.= "</select>";
	}
	$sug_form.= "
		<tr>
			<td align=right>".htmlentities($msg['acquisition_categ'], ENT_QUOTES, $charset)."</td>
			<td>$sel_categ</td>
		</tr>";
	
}
// Localisation de la suggestion
if($_SESSION["id_empr_session"]) {		
	$requete = "SELECT * FROM empr WHERE id_empr=".$_SESSION["id_empr_session"];	
	$res = mysql_query($requete);
	if($res) {
		$empr = mysql_fetch_object($res);	
		if (!$empr->empr_location) $empr->empr_location=0 ;	
		$list_locs='';
		$locs=new docs_location();
		$list_locs=$locs->gen_combo_box_sugg($empr->empr_location,1,"");
		if ($opac_sugg_localises==1) {			
			$sug_form.= "
			<tr>
				<td align=right>".htmlentities($msg['acquisition_location'], ENT_QUOTES, $charset)."</td>
				<td>$list_locs</td>
			</tr>";
		} elseif ($opac_sugg_localises==2) {
			$sug_form.= "<input type=\"hidden\" name=\"sugg_location_id\" value=\"".$empr->empr_location."\"/>";			
		}
	}
}

//Affichage du selecteur de source
	$req = "select * from suggestions_source order by libelle_source";
	$res= mysql_query($req,$dbh);
	$option = "<option value='0' selected>".htmlentities($msg['empr_sugg_no_src'],ENT_QUOTES,$charset)."</option>";
	while(($src=mysql_fetch_object($res))){
		$option .= "<option value='".$src->id_source."' $selected >".htmlentities($src->libelle_source,ENT_QUOTES,$charset)."</option>";
	}
	$selecteur = "<select id='sug_src' name='sug_src'>".$option."</select>";

$sug_form .="<tr>
			<td align=right>".htmlentities($msg['empr_sugg_src'], ENT_QUOTES, $charset)."</td>
			<td>$selecteur</td>
		</tr>"
;

$sug_form.= "
		<tr>
			<td align=right>".htmlentities($msg["empr_sugg_piece_jointe"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"file\" id=\"piece_jointe_sug\" name=\"piece_jointe_sug\" size=\"40\" border=\"0\"/>
			</td>
		</tr>";
$sug_form.= "
		<tr>
			<td colspan=2 align=right>
				<input type='button' class='bouton' name='ok' value='&nbsp;".addslashes($msg[empr_bt_valid_sugg])."&nbsp;' onClick='this.form.submit()'/>
			</td>
		</tr>
	</table>
</form>

</div></div>";

print $sug_form;

?>