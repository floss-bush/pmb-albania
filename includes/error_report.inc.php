<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: error_report.inc.php,v 1.6 2007-03-10 09:46:47 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fichier de configuration générale pour les rapports d'erreur PHP

//error_reporting (E_ALL);
error_reporting (E_ERROR | E_PARSE | E_WARNING);
//error_reporting (E_ALL);

// commentaire de test