<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fiche_catalographique.inc.php,v 1.10 2007-03-10 09:25:49 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// PDF de fiche catalographique
/* reçoit : un cb ou id d'exemplaire */

// modules propres à pdf.php ou à ses sous-modules
require_once("$class_path/notice.class.php");
require_once("$class_path/expl.class.php");
require_once("$class_path/indexint.class.php");
require_once("$include_path/notice_authors.inc.php");
require_once("$include_path/notice_categories.inc.php");

$exemplaire=new exemplaire($expl_cb, $expl_id);
$notice=new notice($exemplaire->id_notice);
$index=new indexint($notice->indexint);
$length=125;        // length of catalographic card
$width=76;          // width of catalographic card
$height=5;          // heigth of a line in a catalographic card
$header="";         // header of the catalographic card

class FPDF_Catalog extends FPDF
{
    function create() {
   // Create the catalographic card

	global $width, $height ;
	global $length;
	global $expl_cb;

        $this->FPDF("P", "mm", array($length,$width));
        $this->Open();
        $this->setMargins(5,5,5);
        $this->setAutoPageBreak(true,$height);
        $this->setDisplayMode(125);
        $this->setTiTle("Fiche catalographique de l'exemplaire :".$expl_cb);
        $this->setAuthor("PMB");
        $this->setCreator("PMB");
    }

    function Header() {
/*---------------------------------------------------------*
 ! Create the header of the catalographic card             !
 *---------------------------------------------------------*/

	global $header;
	global $width;
	global $length;
	global $height;
	global $exemplaire;
	global $index;
	global $pmb_pdf_font;

        $this->setFont($pmb_pdf_font,"B",10);
        $this->Cell($length,$height,$header);
        $this->Ln();
        $this->Cell($length/2,$height,$exemplaire->cote);
        $this->Cell($length/2,$height,$index->name);
        $this->Ln();
    }


    function Body() {
    /*---------------------------------------------------------*
     ! Create the body of the catalographic card               !
     *---------------------------------------------------------*/

	global $width;
	global $length;
	global $height;
	global $exemplaire;
	global $notice;
	global $auteur;
	global $pmb_pdf_font;

        $body="";
        $body=$body.$notice->tit1." : ";

    /* book title(s) */
        if (strlen($notice->tit2)>0) {
           $body=$body.$notice->tit2. " : ";
           if (strlen($notice->tit3)>0) {
              $body=$body.$notice->tit3. " : ";
              if (strlen($notice->tit4)>0) {
                $body=$body.$notice->tit4. " : ";
              }
           }
        }
        $body=pmb_substr($body,0,pmb_strlen($body)-3)." / ";

    /* book author(s) */
        for($i=0;$i<count($notice->responsabilites["auteurs"]);$i++) {
            unset($auteur);
            $auteur=new auteur($notice->responsabilites["auteurs"][$i]["id"]);
            $body=$body.$auteur->name;
            if ($auteur->rejete) $body=$body." ".$auteur->rejete;
            $body=$body.", ";
        }
        $body=pmb_substr($body,0,pmb_strlen($body)-2).".&nbsp;-";

    /* book editor(s) */
        if (strlen($notice->ed1)>0) {
           $body=$body.$notice->ed1. ", ";
           if (strlen($notice->ed2)>0) {
              $body=$body.$notice->ed2. ", ";
              if (strlen($notice->ed3)>0) {
                 $body=$body.$notice->ed3. ", ";
                 if (strlen($notice->ed4)>0) {
                   $body=$body.$notice->ed4. ", ";
                 }
              }
           }
        }
        $body=pmb_substr($body,0,pmb_strlen($body)-2);
        if (strlen($notice->year)>0) $body=$body." :".$notice->year;
        $body=$body.".&nbsp;-";

    /* book format */
        if ($notice->npages) {
           $body=$body.$notice->npages;
           if ($notice->size) $body=$body."; ".$notice->size;
           $body=$body.".&nbsp;-";
        }

    /* book collection */
        if (strlen($notice->coll)>0) {
           $body=$body."(".$notice->coll;
           if (strlen($notice->nocoll)>0)
              $body=$body."; ".$notice->nocoll;
           $body=$body.").&nbsp;-";
        }

    /* book note */
        if (strlen(trim($notice->n_contenu))>0) {
           $body=$body.trim($notice->n_contenu).".&nbsp;-";
        }

        $this->setFont($pmb_pdf_font,"",10);
        $this->Ln();
        $this->MultiCell($length*9/10,$height,$body);
        $this->Cell($length,$height,"ISBN : ".$notice->code);
    }

    function Footer() {
    /*---------------------------------------------------------*
     ! Create the footer of the catalographic card             !
     *---------------------------------------------------------*/

	global $width;
	global $length;
	global $height;
	global $exemplaire;
	global $pmb_pdf_fontfixed;

        $this->setFont($pmb_pdf_fontfixed,"",10);
        $this->setXY(5,$width-$height*2);
        $this->Cell($length,$height,$exemplaire->cb);

        $this->setFont('barcode',"",20);
        $this->setXY(40,$width-7);
        // Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, int fill [, mixed link]]]]]]])
        $this->Cell(80,0,"*".$exemplaire->cb."*",0,0,'R');
    }

}

class UFPDF_Catalog extends UFPDF
{
    function create() {
   /*---------------------------------------------------------*
    ! Create the catalographic card                           !
    *---------------------------------------------------------*/

	global $width, $height ;
	global $length;
	global $expl_cb;

        $this->FPDF("P", "mm", array($length,$width));
        $this->Open();
        $this->setMargins(5,5,5);
        $this->setAutoPageBreak(true,$height);
        $this->setDisplayMode(125);
        $this->setTiTle("Fiche catalograhique de l'exemplaire :".$expl_cb);
        $this->setAuthor("PMB");
        $this->setCreator("PMB");
    }

    function Header() {
/*---------------------------------------------------------*
 ! Create the header of the catalographic card             !
 *---------------------------------------------------------*/

	global $header;
	global $width;
	global $length;
	global $height;
	global $exemplaire;
	global $index;
	global $pmb_pdf_font;

        $this->setFont($pmb_pdf_font,"B",10);
        $this->Cell($length,$height,$header);
        $this->Ln();
        $this->Cell($length/2,$height,$exemplaire->cote);
        $this->Cell($length/2,$height,$index->name);
        $this->Ln();
    }


    function Body() {
    /*---------------------------------------------------------*
     ! Create the body of the catalographic card               !
     *---------------------------------------------------------*/

	global $width;
	global $length;
	global $height;
	global $exemplaire;
	global $notice;
	global $auteur;
	global $pmb_pdf_font;

        $body="";
        $body=$body.$notice->tit1." : ";

    /* book title(s) */
        if (strlen($notice->tit2)>0) {
           $body=$body.$notice->tit2. " : ";
           if (strlen($notice->tit3)>0) {
              $body=$body.$notice->tit3. " : ";
              if (strlen($notice->tit4)>0) {
                $body=$body.$notice->tit4. " : ";
              }
           }
        }
        $body=pmb_substr($body,0,pmb_strlen($body)-3)." / ";

    /* book author(s) */
        for($i=0;$i<count($notice->responsabilites["auteurs"]);$i++) {
            unset($auteur);
            $auteur=new auteur($notice->responsabilites["auteurs"][$i]["id"]);
            $body=$body.$auteur->name. ", ";
        }
        $body=pmb_substr($body,0,pmb_strlen($body)-2).".&nbsp;-";

    /* book editor(s) */
        if (strlen($notice->ed1)>0) {
           $body=$body.$notice->ed1. ", ";
           if (strlen($notice->ed2)>0) {
              $body=$body.$notice->ed2. ", ";
              if (strlen($notice->ed3)>0) {
                 $body=$body.$notice->ed3. ", ";
                 if (strlen($notice->ed4)>0) {
                   $body=$body.$notice->ed4. ", ";
                 }
              }
           }
        }
        $body=pmb_substr($body,0,pmb_strlen($body)-2);
        if (strlen($notice->year)>0) $body=$body." :".$notice->year;
        $body=$body.".&nbsp;-";

    /* book format */
        if (strlen($notice->npage)>0) {
           $body=$body.$notice->npage;
           if (strlen($notice->size)>0) $body=$body."; ".$notice->size;
           $body=$body.".&nbsp;-";
        }

    /* book collection */
        if (strlen($notice->coll)>0) {
           $body=$body."(".$notice->coll;
           if (strlen($notice->nocoll)>0)
              $body=$body."; ".$notice->nocoll;
           $body=$body.").&nbsp;-";
        }

    /* book note */
        if (strlen(trim($notice->n_contenu))>0) {
           $body=$body.trim($notice->n_contenu).".&nbsp;-";
        }

        $this->setFont($pmb_pdf_font,"",10);
        $this->Ln();
        $this->MultiCell($length*9/10,$height,$body);
        $this->Cell($length,$height,"ISBN : ".$notice->code);
    }

    function Footer() {
    /*---------------------------------------------------------*
     ! Create the footer of the catalographic card             !
     *---------------------------------------------------------*/

	global $width;
	global $length;
	global $height;
	global $exemplaire;
	global $pmb_pdf_font;

        $this->setFont($pmb_pdf_font,"",10);
        $this->setXY(5,$width-$height*2);
        $this->Cell($length,$height,$exemplaire->cb);
    }

}



/* Create the PDF catalographic card */
$nom_classe=$fpdf."_Catalog";
$card=new $nom_classe();
$card->create();
$card->AddFont('barcode', '', "barcode.php");

/* Create a catalographic card for each author */
for($i=0;$i<count($notice->responsabilites["auteurs"]);$i++) {
    unset($auteur);
    $auteur=new auteur($notice->responsabilites["auteurs"][$i]["id"]);
    $header=$auteur->name;
    $card->addPage();
    $card->Body();
}

/* Create a catalographic card for the index */
$header=$index->name;
$card->addPage();
$card->Body();
$card->output();
?>
