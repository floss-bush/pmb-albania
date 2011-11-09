<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: getimage.php,v 1.7.2.1 2011-06-13 13:43:44 gueluneau Exp $

$noticecode=$_GET['noticecode'];
$vigurl=$_GET['vigurl'];
require_once("./includes/isbn.inc.php");

$base_path     = ".";                            
$base_auth     = ""; //"CIRCULATION_AUTH";  
$base_title    = "";    
$base_noheader = 1;
$base_nocheck  = 1;
$base_nobody   = 1;
require_once ("$base_path/includes/init.inc.php"); 
require_once("$class_path/curl.class.php");

session_write_close();

$poids_fichier_max=1024*1024;//Limite la taille de l'image à 1 Mo

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

} else {
	$url_image=rawurldecode(stripslashes($_GET['url_image']));
}

if ($pmb_curl_available) {
	$image="";
	$aCurl = new Curl();
	$aCurl->limit=$poids_fichier_max;//Limite la taille de l'image à 1 Mo
	$aCurl->timeout=15;
	$content = $aCurl->get($vigurl);
	$image=$content->body;
	
	if(!$image || $content->headers['Status-Code'] != 200){
		$content = $aCurl->get($url_image10);
		$image=$content->body;
	}
	
	if(!$image || $content->headers['Status-Code'] != 200){
		$content = $aCurl->get($url_image13);
		$image=$content->body;
	}
	
	if(!$image || $content->headers['Status-Code'] != 200){
		$content = $aCurl->get($url_imageEAN);
		$image=$content->body;
	}
	
	if(!$image || $content->headers['Status-Code'] != 200){
		$content = $aCurl->get($url_image);
		$image=$content->body;
	}
	
	if(!$image || $content->headers['Status-Code'] != 200 || $content->headers['Content-Length'] > $aCurl->limit){//Si le fichier est trop gros image n'est pas vide mais ne contient que le début d'ou le dernier test
		$image_url = 'http';
		if ($_SERVER["HTTPS"] == "on") {$image_url .= "s";}
		$image_url .= "://";
		$image_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].dirname($_SERVER["SCRIPT_NAME"]).'/images/vide.png';
		$content = $aCurl->get($image_url);
		$image = $content->body;
	}
	
	if($image && $content->headers['Status-Code'] == 200){
		if ($img=imagecreatefromstring($image)) {
			header('Content-Type: image/png');
			$redim=false;
			if($_GET['empr_pic']){
				if(imagesx($img) <= $empr_pics_max_size){
					$largeur=imagesx($img);
				}else{
					$redim=true;
					$largeur=$empr_pics_max_size;
				}
				if(imagesy($img) <= $empr_pics_max_size){
					$hauteur=imagesy($img);
				}else{
					$redim=true;
					$hauteur=$empr_pics_max_size;
				}
			}
			
			if($redim){
				$dest = imagecreatetruecolor($largeur,$hauteur);
				imagecopyresampled($dest, $img, 0, 0, 0, 0, $largeur, $hauteur,imagesx($img),imagesy($img));
				imagepng($dest);
				imagedestroy($dest);
			}else{
				imagepng($img);
			}
			imagedestroy($img);
		}
	}else{
		//Je ne peux passer ici que si pmb/images/vide.png n'existe pas ou n'a pas les bons droits 
	}
}
else {
	// priorité à vigurl si fournie
	if ($fp=@fopen(rawurldecode(stripslashes($vigurl)), "rb")) {
	} elseif ($fp=@fopen(rawurldecode(stripslashes($url_image10)), "rb")) {
	} elseif ($fp=@fopen(rawurldecode(stripslashes($url_image13)), "rb")) {
	} elseif ($fp=@fopen(rawurldecode(stripslashes($url_imageEAN)), "rb")) {
	} elseif ($fp=@fopen(rawurldecode(stripslashes($url_image)), "rb")) {
	}
	
	if ($fp) {
		//Lecture et vérification de l'image
		$image="";
		$size=0;
		$flag=true;
		while (!feof($fp)) {
			$image.=fread($fp,4096);
			$size=strlen($image);
			if ($size>$poids_fichier_max) {
				$flag=false;
				break;
			}
		}
		if ($flag) {
			if ($img=imagecreatefromstring($image)) {
				header('Content-Type: image/png');
	    		$redim=false;
				if($_GET['empr_pic']){
					if(imagesx($img) <= $empr_pics_max_size){
						$largeur=imagesx($img);
					}else{
						$redim=true;
						$largeur=$empr_pics_max_size;
					}
					if(imagesy($img) <= $empr_pics_max_size){
						$hauteur=imagesy($img);
					}else{
						$redim=true;
						$hauteur=$empr_pics_max_size;
					}
				}
				
				if($redim){
					$dest = imagecreatetruecolor($largeur,$hauteur);
					imagecopyresampled($dest, $img, 0, 0, 0, 0, $largeur, $hauteur,imagesx($img),imagesy($img));
					imagepng($dest);
					imagedestroy($dest);
				}else{
					imagepng($img);
				}
				imagedestroy($img);
			}
		}else{
			header('Content-Type: image/png');
			$fp=@fopen('./images/vide.png', "rb");
			fpassthru($fp);
			fclose($fp) ;
		}
		fclose($fp) ;
	} else {
		header('Content-Type: image/png');
		$fp=@fopen('./images/vide.png', "rb");
		fpassthru($fp);
		fclose($fp) ;
	}		
}
?>