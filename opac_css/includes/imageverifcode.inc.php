<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

session_start();

// make a string with all the characters that we 
// want to use as the verification code
$alphanum  = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";

// generate the verication code 
$rand = substr(str_shuffle($alphanum), 0, 5);

// create an image object using the chosen background
$image = imagecreatefromjpeg("../images/fondcodeverif.jpg");

$textColor = imagecolorallocate ($image, 0, 0, 0); 

// write the code on the background image
imagestring ($image, 5, 5, 8,  $rand, $textColor); 

$x=imagesx($image);
$y=imagesy($image);

$agrandie=imagecreatetruecolor($x*2, $y*2);

imagecopyresized($agrandie, $image, 0, 0, 0, 0, $x*2, $y*2, $x, $y);

// create the hash for the verification code
// and put it in the session
$_SESSION['image_random_value'] = md5($rand);
	
// send several headers to make sure the image is not cached	
// taken directly from the PHP Manual
	
// Date in the past 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 

// always modified 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 

// HTTP/1.1 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false); 

// HTTP/1.0 
header("Pragma: no-cache"); 	


// send the content type header so the image is displayed properly
header('Content-type: image/jpeg');

// send the image to the browser
imagejpeg($agrandie);

// destroy the image to free up the memory
imagedestroy($agrandie);
imagedestroy($image);
?>