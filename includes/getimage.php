<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: getimage.php,v 1.5 2008-08-05 14:16:07 touraine37 Exp $

$noticecode=$_GET['noticecode'];
$vigurl=$_GET['vigurl'];
require_once("./includes/isbn.inc.php");

// priorité à vigurl si fournie
if ($vigurl) {
	$fp=@fopen(rawurldecode(stripslashes($vigurl)), "rb");
} else {
	if ($noticecode) { 
		if (isEAN($noticecode)) {
			if (isISBN($noticecode)) {
				if (isISBN10($noticecode)) {
					$url_image10=str_replace("!!isbn!!", str_replace("-","",$noticecode), $_GET['url_image']);
					$url_image13=str_replace("!!isbn!!", str_replace("-","",formatISBN($noticecode,"13")), $_GET['url_image']);
				} else {
					$url_image10=str_replace("!!isbn!!", str_replace("-","",EANtoISBN10($noticecode)), $_GET['url_image']);
					$url_image13=str_replace("!!isbn!!", str_replace("-","",$noticecode), $_GET['url_image']);
				}
			} else {
				$url_imageEAN=str_replace("!!isbn!!", str_replace("-","",$noticecode), $_GET['url_image']);
			}
		} 
		$url_image=str_replace("!!isbn!!", $noticecode, $_GET['url_image']);
		
		if ($fp=@fopen(rawurldecode(stripslashes($url_image10)), "rb")) {
		} elseif ($fp=@fopen(rawurldecode(stripslashes($url_image13)), "rb")) {
		} elseif ($fp=@fopen(rawurldecode(stripslashes($url_imageEAN)), "rb")) {
		} elseif ($fp=@fopen(rawurldecode(stripslashes($url_image)), "rb")) {
		}
	} else {
		$fp=@fopen(rawurldecode(stripslashes($_GET['url_image'])), "rb");
	}
}
if ($fp) {
	//Lecture et vérification de l'image
	$image="";
	$size=0;
	$flag=true;
	while (!feof($fp)) {
		$image.=fread($fp,4096);
		$size=count($image);
		if ($size>1024*1024) {
			$flag=false;
			break;
		}
	}
	if ($flag) {
		if ($img=imagecreatefromstring($image)) {
			header('Content-Type: image/png');
    		imagepng($img);
		}
	}
	fclose($fp) ;
} else {
	header('Content-Type: image/png');
	$fp=@fopen('./images/vide.png', "rb");
	fpassthru($fp);
	fclose($fp) ;
}

?>