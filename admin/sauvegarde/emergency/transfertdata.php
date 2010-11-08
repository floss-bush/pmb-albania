<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transfertdata.php,v 1.2 2009-05-16 11:12:02 dbellamy Exp $

//Restauration d'urgence
?>
<html>
<head><title>Upload iso-8859-1 sql file which changing charset</title></head>
<body>
<h1>Upload iso-8859-1 sql file which changing charset</h1>
<br /><br />
<form class='form-$current_module' action="transfertdata_upload.php" method="post" enctype="multipart/form-data">
<table>
<tr><td>Upload archive file</td><td><input type="file" name="archive_file"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><center><b>Connection information</b></center></td></tr>
<tr><td>host</td><td><input name="host" type="text"></td></tr>
<tr><td>username</td><td><input name="db_user" type="text"></td></tr>
<tr><td>password</td><td><input name="db_password" type="password"></td></tr>
<tr><td>Database</td><td><input name="db" type="text"></td></tr>
<tr><td colspan=2 align=center><input type="submit" value="Click here to start restoring datas"></td></tr>
</table>


</form>
</body>
</html>