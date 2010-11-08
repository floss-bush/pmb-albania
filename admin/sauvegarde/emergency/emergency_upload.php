<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: emergency_upload.php,v 1.7 2007-03-14 18:06:42 gueluneau Exp $

//Restauration d'urgence
@set_time_limit(1200);
echo "bonjour ".$_FILES['archive_file']['tmp_name'];
move_uploaded_file($_FILES['archive_file']['tmp_name'], "../../backup/backups/".basename($_FILES['archive_file']['tmp_name']));

?>
<script>document.location="../restaure.php?filename=<?php echo "../backup/backups/".rawurlencode(basename($_FILES['archive_file']['tmp_name'])); ?>&critical=1";</script>