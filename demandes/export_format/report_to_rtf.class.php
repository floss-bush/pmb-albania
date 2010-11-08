<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: report_to_rtf.class.php,v 1.5 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($base_path."/classes/rtf/Rtf.php");
require_once($include_path.'/parser.inc.php');


class report_to_rtf{
	
	var $intro = array();
	var $notes = array();
	
	/*
	 * Constructeur
	 */
	function report_to_rtf($xml=""){
		//Parse le fichier dans un tableau	
		$param=_parser_text_no_function_($xml,"REPORT");
		
		$this->intro = $param['INTRO'];
		$this->notes = $param['NOTES'][0]['NOTE'];
		
		$this->generate_RTF();
	}
	
	
	function generate_RTF(){
		
		global  $pmb_gestion_devise, $base_path, $msg, $biblio_logo;
		
		//Format des fonts		
		$fontHead = new Font(12, 'Arial','#0E298A');
		$fontHead->setBold();
		$fontSmall = new Font(1);
		$fontComment = new Font(10,'Arial');
		$fontComment->setItalic();
		$fontChapter = new Font(10,'Arial');
		$fontChapter->setBold();
		$fontSubChapter = new Font(10,'Arial');
		$fontSubChapter->setUnderline();		
		
		//Format des paragraphes
		$parPmb = new ParFormat();
		$parPmb->setIndentRight(12.5);
		$parPmb->setBackColor('#0E298A');
		$parPmb->setSpaceAfter(8);			
		$parHead = new ParFormat();
		$parHead->setSpaceBefore(5);
		//$parHead->setSpaceAfter(8);		
		$parChapter = new ParFormat();
		$parChapter->setSpaceBefore(2);
		$parChapter->setSpaceAfter(1);			
		$parComment = new ParFormat();
		$parComment->setIndentLeft(1);
		$parComment->setIndentRight(0.5);			
		$parContenu = new ParFormat('justify');
		$parContenu->setIndentLeft(1);				
		$parSubChapter = new ParFormat();
		$parSubChapter->setIndentLeft(0.5);		
		$parInfo = new ParFormat();
		$parInfo->setIndentLeft(0.5);
		$parInfo->setSpaceAfter(1.5);
		
		//Document
		$rtf = new Rtf();
		$rtf->setMargins(1, 1, 1 ,1);
		
		$sect = &$rtf->addSection();
		$table = &$sect->addTable();
		$table->addRows(2, 2);
		$table->addColumnsList(array(5, 15));
		$table->addImageToCell(1,1,$base_path."/images/".$biblio_logo,new ParFormat('center'),0,0);
		$cell = &$table->getCell(1,2);
		$titre_general = reg_diacrit($this->intro[0]['TITLE'][0]['value']);
		$cell->writeText($this->to_utf8(strtoupper($titre_general)), new Font(14,'Arial','#0E298A'), new ParFormat('left'));
		$table->setVerticalAlignmentOfCells('center', 1, 1, 2, 2);
		$table->writeToCell(2,1,$this->to_utf8("<u>".$msg['demandes_rapport_abstract']."</u> : "),new Font(12,'Arial','#0E298A'), new ParFormat('center'));
		$cell = &$table->getCell(2,2);
		$cell->writeText($this->to_utf8($this->intro[0]['ABSTRACT'][0]['value']),new Font(12,'Arial','#0E298A'), new ParFormat('left'));
		
		$sect->writeText($msg['demandes_rapport_intro'], $fontHead, $parHead);
		$sect->emptyParagraph($fontSmall, $parPmb);
		
		$date = "<u>".$msg['demandes_rapport_date']."</u> : ".$this->intro[0]['DATE'][0]['value'];
		$deadline = "<u>".$msg['demandes_rapport_deadline']."</u> : ".$this->intro[0]['DEADLINE'][0]['value'];
		//$resume = "<u>".$msg['demandes_rapport_abstract']."</u> : ".$this->intro[0]['ABSTRACT'][0]['value'];
		$doc = "<u>".$msg['demandes_rapport_documentaliste']."</u> : ".$this->intro[0]['DOCUMENTALISTE'][0]['value'];
		$dmde = "<u>".$msg['demandes_rapport_demandeur']."</u> : ".$this->intro[0]['DEMANDEUR'][0]['value'];
		$time = "<u>".$msg['demandes_action_time_elapsed']."</u> : ".$this->intro[0]['TIME'][0]['value'].$msg['demandes_action_time_unit'];
		$cout = "<u>".$msg['demandes_action_cout'] ."</u> : ".$this->intro[0]['COST'][0]['value'];
		
		$sect->writeText($this->to_utf8($doc), new Font(10,'Arial'), $parInfo);
		$sect->writeText($this->to_utf8($dmde), new Font(10,'Arial'), $parInfo);
		$sect->writeText($this->to_utf8($date), new Font(10,'Arial'), $parInfo);
		$sect->writeText($this->to_utf8($deadline), new Font(10,'Arial'), $parInfo);		
		$sect->writeText($this->to_utf8($time), new Font(10,'Arial'), $parInfo);
		$sect->writeText($this->to_utf8($cout).(html_entity_decode($pmb_gestion_devise,ENT_QUOTES,'utf-8')), new Font(10,'Arial'), $parInfo);
		//$sect->writeText($this->to_utf8($resume), new Font(10,'Arial'), $parInfo);
		
		
		$sect->writeText($this->to_utf8($msg['demandes_rapport']), $fontHead, $parHead);
		$sect->emptyParagraph($fontSmall, $parPmb);
		
		$indice = 1;
		for($i=0;$i<count($this->notes);$i++){						
			$chapter = "";
			$comment = "";					
			if($this->notes[$i]['TITRE'] == 'yes'){
				$chapter = $this->notes[$i]['CONTENT'][0]['value'];
				$sect->writeText("<br>".($indice)." - ".$this->to_utf8($chapter)."<br>",$fontChapter,$parChapter);
				$indice++;
				$sujet_old="";
			} else if($this->notes[$i]['COMMENTAIRE'] == 'yes') {
				$comment = $this->notes[$i]['CONTENT'][0]['value'];	
				$sect->writeText($this->to_utf8($comment),$fontComment,$parComment);
			} else {	
				$sujet = $this->notes[$i]['SUJET'][0]['value'];
				$contenu = $this->notes[$i]['CONTENT'][0]['value'];
				if($sujet != $sujet_old)
					$sect->writeText($this->to_utf8($sujet),$fontSubChapter,$parSubChapter);
				if($contenu)
					$sect->writeText($this->to_utf8($contenu),new Font(10,'Arial'),$parContenu);
				$sujet_old = $sujet;
			}
		}
		
		$rtf->sendRtf("rapport");
	}
	
	/*
	 * Conversion en UTF-8
	 */
	function to_utf8($string){
		global $charset;
		
		if($charset != 'utf-8'){
			return utf8_encode($string);
		}
		
		return utf8_decode($string);
	}
}


/*
 * Classe de génération du rapport PMI
 */
class report_to_rtf_pmi extends report_to_rtf {
	
	
	function report_to_pdf_pmi(){}
	
	function generate_RTF(){
		global $msg, $base_path, $pmb_gestion_devise, $charset;
		
		//Format de la police
		$fontTitle = new Font(16,'Palatino Linotype','#0067B1');
		$fontSubtitle = new Font(12,'Palatino Linotype','#0067B1');
		$fontTabintro = new Font(12,'Arial','#000000');
		$fontPart = new Font(11,'Palatino Linotype','#000000','#E6E6E6');
		$fontSubPart = new Font(9,'Arial');
		$fontSubPart->setBold();
		$fontSubPart->setItalic();
		$fontComment = new Font(10,'Arial');
		$fontComment->setItalic();
		$fontContenu = new Font(10,'Arial');
		
		//Format des paragraphes
		$parTitle = new ParFormat('center');
		$parTitle->setSpaceBefore(1.5);
		$parTitle->setSpaceAfter(20);		
		$parChapter = new ParFormat('left');
		$parChapter->setSpaceBefore(1);
		$parChapter->setSpaceAfter(1);		
		$parSeparator = new ParFormat();
		$parSeparator->setBackColor('#0067B1');
		$parSeparator->setSpaceAfter(0.5);	
		$parSeparator->setSpaceBefore(1);		
		$parPart = new ParFormat();
		$parPart->setSpaceBefore(2);
		$parPart->setSpaceAfter(1);				
		$parComment = new ParFormat();
		$parComment->setIndentLeft(1);
		$parComment->setIndentRight(0.5);		
		$parContenu = new ParFormat('justify');
		$parContenu->setIndentLeft(1);				
		$parSubPart = new ParFormat();
		$parSubPart->setIndentLeft(0.5);
		
		$rtf = new Rtf();
		$rtf->setMargins(1, 1, 1 ,1);
		$header = &$rtf->addHeader('first');
		$header->addImage($base_path.'/images/logo_pmi.png', new ParFormat(),19,2.5);
		$titre_general = $this->intro[0]['TITLE'][0]['value'];
		$header->writeText($this->to_utf8($titre_general),$fontTitle, $parTitle);
		
		$sect = &$rtf->addSection();
		$sect->writeText('Search Strategy',$fontSubtitle,$parChapter);
		$sect->emptyParagraph(new Font(0.5),$parSeparator);
		$sect->emptyParagraph(new Font(0.5),new ParFormat());
		
		$table = &$sect->addTable();
		$table->addRows(10, 0.5);
		$table->addColumnsList(array(4, 15));
		$table->setBordersOfCells(new BorderFormat(0.5, '#000000'), 1, 1, 10, 2);
		$table->setVerticalAlignmentOfCells('center', 1, 1, 10, 2);
		
		$doc = $this->intro[0]['DOCUMENTALISTE'][0]['value'];
		$dmde = $this->intro[0]['DEMANDEUR'][0]['value'];
		$time = $this->intro[0]['TIME'][0]['value'].$msg['demandes_action_time_unit'];
		$cout = $this->intro[0]['COST'][0]['value'];
		$date = $this->intro[0]['DATE'][0]['value'];
		$deadline = $this->intro[0]['DEADLINE'][0]['value'];
		$abstract = $this->intro[0]['ABSTRACT'][0]['value'];
		
		$table->writeToCell(1,1,'Requests',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(1,2);
		$cell->writeText($this->to_utf8($titre_general),$fontTabintro,new ParFormat());
		$table->writeToCell(2,1,'Searcher(s)',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(2,2);
		$cell->writeText($this->to_utf8($doc),$fontTabintro,new ParFormat());
		$table->writeToCell(3,1,'Patron',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(3,2);
		$cell->writeText($this->to_utf8($dmde),$fontTabintro,new ParFormat());
		$table->writeToCell(4,1,'Request date',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(4,2);
		$cell->writeText($this->to_utf8($date),$fontTabintro,new ParFormat());
		$table->writeToCell(5,1,'Deadline',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(5,2);
		$cell->writeText($this->to_utf8($deadline),$fontTabintro,new ParFormat());
		$table->writeToCell(6,1,'Sources',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(6,2);
		$cell->writeText('',$fontTabintro,new ParFormat());
		$table->writeToCell(7,1,'Keywords',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(7,2);
		$cell->writeText('',$fontTabintro,new ParFormat());
		$table->writeToCell(8,1,'Estimated Time for search',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(8,2);
		$cell->writeText($this->to_utf8($time),$fontTabintro,new ParFormat());
		$table->writeToCell(9,1,'Cost',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(9,2);
		$cell->writeText($this->to_utf8($cout).(html_entity_decode($pmb_gestion_devise,ENT_QUOTES,'utf-8')),$fontTabintro,new ParFormat());
		$table->writeToCell(10,1,'Abstract',$fontTabintro,new ParFormat());
		$cell = &$table->getCell(10,2);
		$cell->writeText($this->to_utf8($abstract),$fontTabintro,new ParFormat());
		
		$sect->writeText('Comments',$fontSubtitle,$parChapter);
		$sect->emptyParagraph(new Font(0.5),$parSeparator);
		$sect->emptyParagraph(new Font(0.5),new ParFormat());
		
		
		$indice = 1;
		for($i=0;$i<count($this->notes);$i++){						
			$chapter = "";
			$comment = "";					
			if($this->notes[$i]['TITRE'] == 'yes'){
				$chapter = $this->notes[$i]['CONTENT'][0]['value'];
				$sect->writeText("<br>".($indice)." - ".$this->to_utf8($chapter)."<br>",$fontPart,$parPart);
				$indice++;
				$sujet_old="";
			} else if($this->notes[$i]['COMMENTAIRE'] == 'yes') {
				$comment = $this->notes[$i]['CONTENT'][0]['value'];	
				$sect->writeText($this->to_utf8($comment),$fontComment,$parComment);
			} else {	
				$sujet = $this->notes[$i]['SUJET'][0]['value'];
				$contenu = $this->notes[$i]['CONTENT'][0]['value'];
				if($sujet != $sujet_old)
					$sect->writeText($this->to_utf8($sujet),$fontSubPart,$parSubPart);
				if($contenu)
					$sect->writeText($this->to_utf8($contenu),$fontContenu,$parContenu);
				$sujet_old = $sujet;
			}
		}
		
		$rtf->sendRtf("rapport");
	}
}
?>