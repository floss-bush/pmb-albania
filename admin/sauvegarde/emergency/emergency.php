<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: emergency.php,v 1.6 2009-05-16 11:12:02 dbellamy Exp $

//Restauration d'urgence
?>
<html>
<head><title>Emergency restore database</title></head>
<body>
<h1>Emergency restore</h1>
<br /><br />
<form class='form-$current_module' action="emergency_upload.php" method="post" enctype="multipart/form-data">
<table>
<tr><td>Upload archive file</td><td><input type="file" name="archive_file"></td></tr>
<tr><td colspan=2 align=center><input type="submit" value="Click here to start restoring datas"></td></tr>
</table>
</form>
</body>
</html>