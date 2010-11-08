<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transfertutf8_upload.php,v 1.2 2008-03-12 10:29:23 gueluneau Exp $

//Restauration d'urgence
exit();
echo "bonjour ".$_FILES['archive_file']['tmp_name'];
move_uploaded_file($_FILES['archive_file']['tmp_name'], "../../backup/backups/".$_FILES['archive_file']['tmp_name']);

?>
<script>document.location="../restaureutf8.php?filename=<?php echo "../backup/backups/".rawurlencode($_FILES['archive_file']['tmp_name']); ?>&critical=1";</script>