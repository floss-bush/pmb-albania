<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: explnum.inc.php,v 1.22 2010-07-02 08:15:13 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


// charge le tableau des extensions/mimetypes, on en a besoin en maj comme en affichage
function create_tableau_mimetype() {
	
	global $lang;
	global $charset;
	global $base_path;
	global $include_path;
	
	global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
	
	if (sizeof($_mimetypes_bymimetype_)) return;
	
	$_mimetypes_bymimetype_ = array();
	$_mimetypes_byext_ = array();

	require_once ($include_path.'/parser.inc.php') ;
	
	$fonction = array ("MIMETYPE" => "__mimetype__");
	
	_parser_("$include_path/mime_types/$lang.xml", $fonction, "MIMETYPELIST" ) ;
	
	/*
	echo "<pre>" ;
	print_r ($_mimetypes_bymimetype_) ;
	print_r ( $_mimetypes_byext_ ) ;
	echo "</pre>" ;
	*/
	
}

function extension_fichier($fichier) {
	
	$f = strrev($fichier);
	$ext = substr($f, 0, strpos($f,"."));
	return strtolower(strrev($ext));
}

function trouve_mimetype ($fichier, $ext='') {
	
	global $_mimetypes_byext_ ;
	if ($ext!='') {
		// chercher le mimetype associe a l'extension : si trouvee nickel, sinon : ""
		if ($_mimetypes_byext_[$ext]["mimetype"]) return $_mimetypes_byext_[$ext]["mimetype"] ;
	}
	if (extension_loaded('mime_magic')) {
		print("toto");
		$mime_type = mime_content_type($fichier) ;
		if ($mime_type) return $mime_type ;
	}
	return '';
}
	
function __mimetype__($param) {
	
	global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
	
	$mimetype_rec = array() ;
	$mimetype_rec["plugin"] = $param["PLUGIN"] ;
	$mimetype_rec["icon"] = $param["ICON"] ;
	$mimetype_rec["label"] = $param["LABEL"] ;
	$mimetype_rec["embeded"] = $param["EMBEDED"] ;
	
	$_mimetypes_bymimetype_[$param["NAME"]] = $mimetype_rec ;
	
	for ($i=0; $i<count($param["EXTENSION"]) ; $i++  ) {
		$mimetypeext_rec = array() ;
		$mimetypeext_rec = $mimetype_rec ;
		$mimetypeext_rec["mimetype"] = $param["NAME"] ;
		if ($param["EXTENSION"][$i]["LABEL"]) $mimetypeext_rec["label"] =  $param["EXTENSION"][$i]["LABEL"] ;
		$_mimetypes_byext_[$param["EXTENSION"][$i]["value"]] = $mimetypeext_rec ;
	}
}


function icone_mimetype ($mimetype, $ext) {
	
	global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
	// trouve l'icone associée au mimetype
	// sinon trouve l'icone associée à l'extension
	/*
	echo "<pre>" ;
	print_r ($_mimetypes_bymimetype_) ;
	print_r ( $_mimetypes_byext_ ) ;
	echo "</pre>" ;
	echo "<br />-- $mimetype<br />-- $ext";
	*/
	if ($_mimetypes_bymimetype_[$mimetype]["icon"]) return $_mimetypes_bymimetype_[$mimetype]["icon"] ;
	if ($_mimetypes_byext_[$ext]["icon"]) return $_mimetypes_byext_[$ext]["icon"] ;
	return "unknown.gif" ;
} // fin icone_mimetype


// fonction retournant les infos d'exemplaires numériques pour une notice ou un bulletin donné
function show_explnum_per_notice($no_notice, $no_bulletin, $link_expl='') {
	
	// params :
	// $link_expl= lien associé à l'exemplaire avec !!explnum_id!! à mettre à jour
	global $dbh;
	global $charset;
	global $opac_url_base ;
	
	if (!$no_notice && !$no_bulletin) return "";
	
	global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
	create_tableau_mimetype() ;
	
	// récupération du nombre d'exemplaires
	$requete = "SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, explnum_vignette, explnum_nomfichier, explnum_extfichier FROM explnum WHERE ";
	if ($no_notice && !$no_bulletin) $requete .= "explnum_notice='$no_notice' ";
	elseif (!$no_notice && $no_bulletin) $requete .= "explnum_bulletin='$no_bulletin' ";
	elseif ($no_notice && $no_bulletin) $requete .= "explnum_bulletin='$no_bulletin' or explnum_notice='$no_notice' ";
	$requete .= " order by explnum_mimetype, explnum_id ";
	$res = mysql_query($requete, $dbh);
	$nb_ex = mysql_num_rows($res);
	
	if($nb_ex) {
		// on récupère les données des exemplaires
		$i = 1 ;
		global $search_terms;
		
		while (($expl = mysql_fetch_object($res))) {
			if ($i==1) $ligne="<tr><td class='docnum' width='33%'>!!1!!</td><td class='docnum' width='33%'>!!2!!</td><td class='docnum' width='33%'>!!3!!</td></tr>" ;
			if ($link_expl) {
				$tlink = str_replace("!!explnum_id!!", $expl->explnum_id, $link_expl);
				$tlink = str_replace("!!notice_id!!", $expl->explnum_notice, $tlink);					
				$tlink = str_replace("!!bulletin_id!!", $expl->explnum_bulletin, $tlink);					
				} 
			$alt = htmlentities($expl->explnum_nom." - ".$expl->explnum_mimetype,ENT_QUOTES, $charset) ;
			
			if ($expl->explnum_vignette) $obj="<img src='".$opac_url_base."/vig_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' border='0'>";
				else // trouver l'icone correspondant au mime_type
					$obj="<img src='".$opac_url_base."/images/mimetype/".icone_mimetype($expl->explnum_mimetype, $expl->explnum_extfichier)."' alt='$alt' title='$alt' border='0'>";		
			$expl_liste_obj = "<center>";
			
			$words_to_find="";
			if(($expl->explnum_mimetype=='application/pdf') ||($expl->explnum_mimetype=='URL' && (strpos($expl->explnum_nom,'.pdf')!==false))){
				$words_to_find = "#search=\"".trim(str_replace('*','',implode(' ',$search_terms)))."\"";
			}
			$expl_liste_obj .= "<a href='".$opac_url_base."/doc_num.php?explnum_id=$expl->explnum_id$words_to_find' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;
			
			if ($_mimetypes_byext_[$expl->explnum_extfichier]["label"]) $explmime_nom = $_mimetypes_byext_[$expl->explnum_extfichier]["label"] ;
				elseif ($_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"]) $explmime_nom = $_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"] ;
					else $explmime_nom = $expl->explnum_mimetype ;
			
			
			if ($tlink) {
				$expl_liste_obj .= "<a href='$tlink'>";
				$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."</a><div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
				} else {
					$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."<div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
					}
			$expl_liste_obj .= "</center>";
			$ligne = str_replace("!!$i!!", $expl_liste_obj, $ligne);
			$i++;
			if ($i==4) {
				$ligne_finale .= $ligne ;
				$i=1;
				}
			}
		if (!$ligne_finale) $ligne_finale = $ligne ;
			elseif ($i!=1) $ligne_finale .= $ligne ;
		$ligne_finale = str_replace('!!2!!', "&nbsp;", $ligne_finale);
		$ligne_finale = str_replace('!!3!!', "&nbsp;", $ligne_finale);
		
		} else return "";
	$entry .= "<table class='docnum'>$ligne_finale</table>";
	return $entry;

}


function &reduire_image_middle(&$data) {
	
	global $opac_photo_mean_size_x ;
	global $opac_photo_mean_size_y ;
	global $opac_photo_watermark;
	global $opac_photo_watermark_transparency;
	if ($opac_photo_watermark_transparency=="") $opac_photo_watermark_transparency=50;
	
	$src_img=imagecreatefromstring($data);
	if ($src_img) {
		$photo_mean_size_x=imagesx($src_img);
		$photo_mean_size_y=imagesy($src_img);
	} else {
		$photo_mean_size_x=200 ;
		$photo_mean_size_y=200 ;
	}
	if ($opac_photo_mean_size_x) $photo_mean_size_x=$opac_photo_mean_size_x;
	if ($opac_photo_mean_size_y) $photo_mean_size_y=$opac_photo_mean_size_y;
	
	if ($opac_photo_watermark) {
		$size = @getimagesize("images/".$opac_photo_watermark);
	/*   ".gif"=>"1",
	                   ".jpg"=>"2",
	                   ".jpeg"=>"2",
	                   ".png"=>"3",
	                   ".swf"=>"4",
	                   ".psd"=>"5",
	                   ".bmp"=>"6");
	*/
		switch ($size[2]) {
			case 1:
				$wat_img = imagecreatefromgif("images/".$opac_photo_watermark);
			 	break;
			case 2:
				$wat_img = imagecreatefromjpeg("images/".$opac_photo_watermark);
				break;
			case 3:
				$wat_img = imagecreatefrompng("images/".$opac_photo_watermark);
				break;
			case 6:
				$wat_img = imagecreatefromwbmp("images/".$opac_photo_watermark);
				break;
			default:
				$wat_img="";
				break;
		}
	}
	
	$erreur_vignette = 0 ;
	if ($src_img) {
		$rs=$photo_mean_size_x/$photo_mean_size_y;
		$taillex=imagesx($src_img);
		$tailley=imagesy($src_img);
		if (!$taillex || !$tailley) return "" ;
		if (($taillex>$photo_mean_size_x)||($tailley>$photo_mean_size_y)) {
			$r=$taillex/$tailley;
			if (($r<1)&&($rs<1)) {
				//Si x plus petit que y et taille finale portrait 
				//Si le format final est plus large en proportion
				if ($rs>$r) {
					$new_h=$photo_mean_size_y; 
					$new_w=$new_h*$r; 
				} else {
					$new_w=$photo_mean_size_x;
					$new_h=$new_w/$r;
				}
			} else if (($r<1)&&($rs>=1)){ 
				//Si x plus petit que y et taille finale paysage
				$new_h=$photo_mean_size_y;
				$new_w=$new_h*$r;  
			} else if (($r>1)&&($rs<1)) {
				//Si x plus grand que y et taille finale portrait
				$new_w=$photo_mean_size_x;
				$new_h=$new_w/$r;
			} else {
				//Si x plus grand que y et taille finale paysage
				if ($rs<$r) {
					$new_w=$photo_mean_size_x;
					$new_h=$new_w/$r;
				} else {
					$new_h=$photo_mean_size_y;
					$new_w=$new_h*$r;
				}
			}
		} else {
			$new_h = $tailley ;
			$new_w = $taillex ;
		}
			
		$dst_img=imagecreatetruecolor($photo_mean_size_x,$photo_mean_size_y);
		ImageSaveAlpha($dst_img, true);
		ImageAlphaBlending($dst_img, false);
		imagefilledrectangle($dst_img,0,0,$photo_mean_size_x,$photo_mean_size_y,imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
		imagecopyresized($dst_img,$src_img,round(($photo_mean_size_x-$new_w)/2),round(($photo_mean_size_y-$new_h)/2),0,0,$new_w,$new_h,ImageSX($src_img),ImageSY($src_img));
		if ($wat_img) {
			$wr_img=imagecreatetruecolor($photo_mean_size_x,$photo_mean_size_y);
			ImageSaveAlpha($wr_img, true);
			ImageAlphaBlending($wr_img, false);
			imagefilledrectangle($wr_img,0,0,$photo_mean_size_x,$photo_mean_size_y,imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
			imagecopyresized($wr_img,$wat_img,round(($photo_mean_size_x-$new_w)/2),round(($photo_mean_size_y-$new_h)/2),0,0,$new_w,$new_h,ImageSX($wat_img),ImageSY($wat_img));
			imagecopymerge($dst_img,$wr_img,0,0,0,0,$photo_mean_size_x,$photo_mean_size_y,$opac_photo_watermark_transparency);
		}
		imagepng($dst_img, "./temp/".session_id());
		$fp = fopen("./temp/".session_id() , "r" ) ;
		$contenu_vignette = fread ($fp, filesize("./temp/".session_id()));
		if (!$fp || $contenu_vignette=="") $erreur_vignette++ ;
		fclose ($fp) ;
		unlink("./temp/".session_id());
	} else $contenu_vignette = "" ;
	return $contenu_vignette ;
} // fin reduire_image