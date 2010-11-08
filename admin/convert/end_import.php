<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: end_import.php,v 1.10 2009-05-16 11:13:21 dbellamy Exp $

//Fin de la conversion
$base_path = "../..";
$base_auth = "ADMINISTRATION_AUTH|CATALOGAGE_AUTH";
$base_title = "\$msg[ie_import_running]";
require ($base_path."/includes/init.inc.php");

$percent=100;
echo "<center><h3>$msg[admin_conversion_end1] $import_type_l $msg[admin_conversion_end2].</h3></center><br />\n";

print "
<table align=center width=100%>
   <tr>
      <td style='border-width:1px;border-style:solid;border-color:#000;'>
         <img src='$base_path/images/jauge.png' width='".$percent."%' height='16'>
      </td></tr>
   <tr>
      <td align=center>".round($percent)."%</td>
      </tr>
</table>";

print "<center>".$n_current." ".$msg["admin_conversion_end3"];

if ($n_errors!=0) {
    print ", ".$n_errors." ".$msg["admin_conversion_end4"];
    $requete="select error_text from error_log where error_origin='convert.log ".$origine."'";
    $resultat=mysql_query($requete);
     while (list($err_)=mysql_fetch_row($resultat)) {
        $errors_msg.=$err_;
    }
}

require ($include_path."/templates/admin.tpl.php");

print $admin_convert_end;


