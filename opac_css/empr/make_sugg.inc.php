<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: make_sugg.inc.php,v 1.15 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path.'/classes/suggestions_categ.class.php');
require_once($base_path.'/classes/docs_location.class.php');
require_once($base_path.'/classes/suggestions.class.php');

$tooltip = str_replace("\\n","<br />",$msg["empr_sugg_ko"]);
$sug_form= "<div id='make_sugg'>
<h3><span>".htmlentities($msg['empr_make_sugg'], ENT_QUOTES, $charset)."</span></h3>";
if($opac_show_help) $sug_form .= "
<div class='row'>
$tooltip</div>
";
if($id_sug){
	$sugg = new suggestions($id_sug);
}
$sug_form.= "
<script >
	function confirm_suppr(){
		phrase = \"{$msg[empr_confirm_suppr_sugg]}\";
		result = confirm(phrase);
		if(result)
			return true;
		
		return false;
	}
</script>
<div id='make_sugg-container'>
<form action=\"empr.php\" method=\"post\" name=\"empr_sugg\" enctype='multipart/form-data'>
	<input type='hidden' name='id_sug' id='id_sug' value='$sugg->id_suggestion' />
	<table width='60%' cellpadding='5'>
		<tr>	
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_tit"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"hidden\" name=\"lvl\" />
				<input type=\"text\"' id=\"tit\" name=\"tit\" size=\"50\" border=\"0\" value=\"".htmlentities($sugg->titre, ENT_QUOTES, $charset)."\"/>
			</td>
		</tr>
		<tr>
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_aut"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"aut\" name=\"aut\" size=\"50\" border=\"0\" value=\"".htmlentities($sugg->auteur, ENT_QUOTES, $charset)."\"/>
			</td>
		</tr>
		<tr>
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_edi"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"edi\" name=\"edi\" size=\"50\" border=\"0\" value=\"".htmlentities($sugg->editeur, ENT_QUOTES, $charset)."\"/>
			</td>
		</tr>
		<tr>
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_code"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"code\" name=\"code\" size=\"20\" border=\"0\" value=\"".htmlentities($sugg->code, ENT_QUOTES, $charset)."\"/>
			</td>
		</tr>
		<tr>
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_prix"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"prix\" name=\"prix\" size=\"20\" border=\"0\" value=\"".htmlentities($sugg->prix, ENT_QUOTES, $charset)."\"/>
			</td>
		</tr>
		<tr>
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_url"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type='text' id=\"url_sug\" name=\"url_sug\" size=\"50\" border=\"0\" value=\"".htmlentities($sugg->url_suggestion, ENT_QUOTES, $charset)."\"/>
			</td>
		</tr>
		<tr>
			<td class='cell_header' align=right vertical-align=top>".htmlentities($msg["empr_sugg_comment"], ENT_QUOTES, $charset)."<br /><i>".htmlentities($msg["empr_sugg_comment_jt"], ENT_QUOTES, $charset)."</i></td>
			<td>
				<textarea id=\"comment\" name=\"comment\" cols=\"50\" rows='4' wrap='virtual'>".htmlentities($sugg->commentaires, ENT_QUOTES, $charset)."</textarea>
			</td>
		</tr>
		<tr>
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_datepubli"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type='text' id='date_publi' name='date_publi' value='$sugg->date_publi'>
				<input type='button' class='bouton' id='date_publi_sug' name='date_publi_sug' value='...' onClick=\"window.open('./select.php?what=calendrier&caller=empr_sugg&param1=date_publi&param2=date_publi&auto_submit=NO&date_anterieure=YES', 'date_publi', 'toolbar=no, dependent=yes, width=250,height=250, resizable=yes')\"/>
			</td>
		</tr>	";
if(!$_SESSION["id_empr_session"]) {
	
	$sug_form.= "
		<tr>
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_mail"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"text\" id=\"mail\" name=\"mail\" size=\"50\" border=\"0\" value=\"".$empr_mail."\"/>
			</td>
		</tr>";
}
if ($opac_sugg_categ == '1' ) {
	
	if($id_sug){
		$default_categ = $sugg->num_categ;
	} else {
		if (suggestions_categ::exists($opac_sugg_categ_default) ){
			$default_categ = $opac_sugg_categ_default;
		} else {
			$default_categ = '1';
		}
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
			<td class='cell_header' align=right>".htmlentities($msg['acquisition_categ'], ENT_QUOTES, $charset)."</td>
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
				<td class='cell_header' align=right>".htmlentities($msg['acquisition_location'], ENT_QUOTES, $charset)."</td>
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
	if($id_sug){
		$selected = ($sugg->sugg_src == $src->id_source ? 'selected' : '');
	}
	$option .= "<option value='".$src->id_source."' $selected >".htmlentities($src->libelle_source,ENT_QUOTES,$charset)."</option>";
}
$selecteur = "<select id='sug_src' name='sug_src'>".$option."</select>";
$sug_form .="<tr>
			<td class='cell_header' align=right>".htmlentities($msg['empr_sugg_src'], ENT_QUOTES, $charset)."</td>
			<td>$selecteur</td>
		</tr>"
;

if($sugg){
	
	if($sugg->get_explnum('nom')){
		$file_field = "<label>".htmlentities($sugg->get_explnum('nom'), ENT_QUOTES, $charset)."</label>";
	} else $file_field = "<input type=\"file\" id=\"piece_jointe_sug\" name=\"piece_jointe_sug\" size=\"40\" border=\"0\"/>";
	
	$sug_form.= "
		<tr>
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_piece_jointe"], ENT_QUOTES, $charset)."</td>
			<td>
				$file_field
			</td>
		</tr>";
		
	$btn_del = "<input type='button' class='bouton' name='ok' value='&nbsp;".htmlentities($msg[empr_suppr_sugg], ENT_QUOTES, $charset)."&nbsp;' onClick=\"if(confirm_suppr()) {this.form.lvl.value='suppr_sugg'; this.form.submit();}\"/>";
} else {
	$sug_form.= "
		<tr>
			<td class='cell_header' align=right>".htmlentities($msg["empr_sugg_piece_jointe"], ENT_QUOTES, $charset)."</td>
			<td>
				<input type=\"file\" id=\"piece_jointe_sug\" name=\"piece_jointe_sug\" size=\"40\" border=\"0\"/>
			</td>
		</tr>";
	$btn_del = "";
}
$sug_form.= "
		<tr>
			<td colspan=2 align=right>
				<input type='button' class='bouton' name='ok' value='&nbsp;".addslashes($msg[empr_bt_valid_sugg])."&nbsp;' onClick='this.form.lvl.value=\"valid_sugg\";this.form.submit()'/>
				$btn_del
			</td>
		</tr>
	</table>
</form>

</div></div>";

print $sug_form;