<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions_export.class.php,v 1.1 2009-07-31 14:37:10 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/suggestions.class.php");

class suggestions_export {
	
	var $liste_suggestion=array();
	var $current=0;
	
	function suggestions_export($liste_suggestions) {
		$this->liste_suggestion=$liste_suggestions;
	}
	
	function export_xml($sugg_id) {
		global $charset;
		
		$sugg=new suggestions($sugg_id);
		$notice="<notice>\n";
		if($sugg->code || $sugg->prix){
			$notice.="
				<f c='010' ind='  '>
					".($sugg->code?"<s c='a'>".htmlspecialchars($sugg->code,ENT_QUOTES,$charset)."</s>":"")."
					".($sugg->prix?"<s c='d'>".htmlspecialchars($sugg->prix,ENT_QUOTES,$charset)."</s>":"")."
				</f>";
		}
		$notice.="
			<f c='200' ind='  '>
				<s c='a'>".htmlspecialchars($sugg->titre,ENT_QUOTES,$charset)."</s>
			</f>";
		$notice.="
			<f c='210' ind='  '>				
				<s c='c'>".htmlspecialchars($sugg->editeur,ENT_QUOTES,$charset)."</s>
			</f>";
		if($sugg->date_publi){
			$notice.="
				<f c='210' ind='  '>				
					<s c='d'>".htmlspecialchars($sugg->date_publi,ENT_QUOTES,$charset)."</s>
				</f>";
		}
		$notice.="
			<f c='700' ind='  '>
				<s c='a'>".htmlspecialchars($sugg->auteur,ENT_QUOTES,$charset)."</s>
			</f>";
		if($sugg->url_suggestion){
			$notice.="
				<f c='856' ind='  '>
					<s c='u'>".htmlspecialchars($sugg->url_suggestion,ENT_QUOTES,$charset)."</s>
				</f>
			";
		}
		$notice.="</notice>";
		return $notice;
	}
	
	function get_next_notice() {
		if ($this->current<count($this->liste_suggestion)) {
			$notice=$this->export_xml($this->liste_suggestion[$this->current]);
			$this->current++;
			return $notice;
		}
	}
}

?>