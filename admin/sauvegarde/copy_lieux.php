<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: copy_lieux.php,v 1.8 2009-05-16 11:11:53 dbellamy Exp $

//if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Copie vers les lieux

$base_path="../..";
$base_auth="SAUV_AUTH|ADMINISTRATION_AUTH";
$base_title="\$msg[sauv_misc_transfert_running]";
require($base_path."/includes/init.inc.php");

require("lib/api.inc.php");

//Récupération du lieu
$requete="select sauv_lieu_nom,sauv_lieu_url, sauv_lieu_protocol, sauv_lieu_login, sauv_lieu_password, sauv_lieu_host from sauv_lieux where sauv_lieu_id=".$sauv_lieu_id;
$resultat=@mysql_query($requete);

$res=mysql_fetch_object($resultat);

//message
print "<div id=\"contenu-frame\">\n";
echo "<center><h1>".sprintf($msg["sauv_misc_warning_transfert"],$res->sauv_lieu_nom)."</h1></center>";
echo "<br /><br /><center><a href=\"\" onClick=\"self.close();\">".$msg["sauv_misc_close_window"]."</a></center>";
flush();
$tfilecopy=explode("/",$filename);
$filecopy=$tfilecopy[count($tfilecopy)-1];

switch ($res->sauv_lieu_protocol) {
	//Si protocol = file
	case "file" :
		if (!copy($filename,$res->sauv_lieu_url."/".$filecopy)) {
			abort("Copy : ".$res->sauv_lieu_nom." : Failed",$logid);	
		} else {
			write_log("Copy : ".$res->sauv_lieu_nom." : Succeed",$logid);
		}
	break;
	//Si protocol = ftp
	case "ftp" :
		$msg_="";
		//Connexion + passage dans le répertoire concerné
		$conn_id=connectFtp($res->sauv_lieu_host, $res->sauv_lieu_login, $res->sauv_lieu_password, $res->sauv_lieu_url, $msg_);
		if ($conn_id=="") {
			abort_copy("Copy : ".$res->sauv_lieu_nom." : Failed : ".$msg_,$logid);
		} else {
			//Transfert
			if (!ftp_put($conn_id, $filecopy, $filename, FTP_BINARY)) {
				abort_copy("Copy : ".$res->sauv_lieu_nom." : Failed",$logid);
			} else {
				write_log("Copy : ".$res->sauv_lieu_nom." : Succeed",$logid);
			}
		}
	break;
}
echo "<script>self.close();</script>";
echo "</div>";
?>