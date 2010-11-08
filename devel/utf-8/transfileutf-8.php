<?php
/*
 * Created on 21 sept. 2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 header ("Content-Type: text/html; charset=utf-8")
?>
<html>
<head>
<meta http-equiv="Content-Language" content="fr" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transfert fichier en utf-8</title>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#FF9966" vlink="#FF9966" alink="#FFCC99">
<?php 

$ListeFicTransfert = 'listefictrans.txt';
$FormatFicOrig = 'windows-1252';

if (!file_exists($ListeFicTransfert)) {
	print("Erreur de lecture sur le fichier de transfert $ListeFicTransfert");
}
else
   $pointeurliste = fopen($ListeFicTransfert, "r");
   while (!feof($pointeurliste)) {
   	    $ligneliste= fgets($pointeurliste, 4096);
   	    $liste = split("\t",$ligneliste);
		$NomFicOrig = trim($liste[0]);
		$NomFicDest = trim($liste[1]);

		echo $NomFicDest."<br />";
		echo $NomFicOrig."<br />"; 

		if (!file_exists($NomFicOrig)) {
			print ("Fichier inexistant $NomFicOrig<br />");
		}
		elseif (file_exists($NomFicDest) ) {
			print ("Fichier deja existant $NomFicDest<br />");
		}
		else {
			$pointeurDest = fopen($NomFicDest, "a");
			print ("Ouverture fichier de destination $NomFicDest - Pointeur $pointeurDest<br />");
			$pointeurOrig = fopen($NomFicOrig, "r");
			print ("Ouverture fichier d'origine $NomFicOrig - Pointeur $pointeurOrig<br />");
		
			while(!feof($pointeurOrig)) {
				$lignepar = fgets($pointeurOrig, 4096);
				$lignetrad = iconv($FormatFicOrig, "UTF-8", $lignepar);
				$lignetrad = preg_replace('/iso-8859-1/', 'utf-8', $lignetrad);
				$lignetrad = preg_replace('/ISO-8859-1/', 'utf-8', $lignetrad);
		
				fputs($pointeurDest, $lignetrad);	
			}
			fclose($pointeurDest);
			fclose($pointeurOrig);
			echo "ficher $liste[0] fini <br />";
		}
   }
   echo "c'est fini";
   		
?>
</body>
</html>
  