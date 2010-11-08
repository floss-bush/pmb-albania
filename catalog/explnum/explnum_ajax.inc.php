<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: explnum_ajax.inc.php,v 1.3 2010-01-28 16:38:03 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($quoifaire){
	
	case 'exist_file':
		existing_file($id,$id_repertoire);	
	break;
	
}

function existing_file($id,$id_repertoire){
	
	global $dbh,$fichier;
	
	if(!$id){
		$rqt = "select repertoire_path, explnum_path, repertoire_utf8, explnum_nomfichier as nom, explnum_extfichier as ext from explnum join upload_repertoire on explnum_repertoire=repertoire_id  where explnum_repertoire='$id_repertoire' and explnum_nomfichier ='$fichier'";
		$res = mysql_query($rqt,$dbh);
			
		if(mysql_num_rows($res)){			
			$expl = mysql_fetch_object($res);
			$path = str_replace('//','/',$expl->repertoire_path.$expl->explnum_path);
			if($expl->repertoire_utf8)
				$path = utf8_encode($path);
					
			if($expl->ext)
				$file = substr($expl->nom,0,strpos($expl->nom,"."));
			else $file = $expl->nom;
			$exist = false;
			$i=0;
			while(!$exist){
				$i++;
				$filename = ($i ? $file."_".$i : $file).($expl->ext ? ".".$expl->ext : "");
				if(!file_exists($path.$filename)){
					print $filename;
					$exist = true;
				}
			}		
		} else print "0";
	} else print "0";
	 
}
?>