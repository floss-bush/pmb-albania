<?php
// +-------------------------------------------------+
//  2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.10 2008-11-17 06:14:51 maxman Exp $
// supporto ldap by MaxMan

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($sub) {
    case 'categ':
        $admin_layout = str_replace('!!menu_sous_rub!!', $msg["lecteurs_categories"], $admin_layout);
        print $admin_layout;
        echo window_title($database_window_title.$msg["lecteurs_categories"].$msg[1003].$msg[1001]);
        include("./admin/empr/categ_empr.inc.php");
        break;
    case 'codstat':
        $admin_layout = str_replace('!!menu_sous_rub!!', $msg[24], $admin_layout);
        print $admin_layout;
        echo window_title($database_window_title.$msg[24].$msg[1003].$msg[1001]);
        include("./admin/empr/cod_stat.inc.php");
        break;
    case 'statut':
        $admin_layout = str_replace('!!menu_sous_rub!!', $msg['empr_statut_menu'], $admin_layout);
        print $admin_layout;
        echo window_title($database_window_title.$msg['empr_statut_menu'].$msg[1003].$msg[1001]);
        include("./admin/empr/statut.inc.php");
        break;
    case 'implec':
        $admin_layout = str_replace('!!menu_sous_rub!!', $msg["import_lec_lien"], $admin_layout);
        print $admin_layout;
        echo window_title($database_window_title.$msg["import_lec_lien"].$msg[1003].$msg[1001]);
		if ($pmb_import_modele_lecteur) $import_modele=$pmb_import_modele_lecteur; else $import_modele="import_empr.inc.php";
        include("./admin/empr/$import_modele");
        break;
    case 'ldap':
        $admin_layout = str_replace('!!menu_sous_rub!!', $msg["import_ldap"], $admin_layout);
        print $admin_layout;
        echo window_title($database_window_title.$msg["import_ldap"].$msg[1003].$msg[1001]);
        include("./admin/empr/import_ldap.inc.php");
        break;
    case 'exldap':
        $admin_layout = str_replace('!!menu_sous_rub!!', "Cancella exLDAP", $admin_layout);
        print $admin_layout;
        echo window_title($database_window_title."Cancella exLDAP".$msg[1003].$msg[1001]);
        include("./admin/empr/empr_exldap.inc.php");
        break;
    case 'parperso':
        $admin_layout = str_replace('!!menu_sous_rub!!', $msg["parametres_perso_lec_lien"],$admin_layout);
        print $admin_layout;
        echo window_title($database_window_title.$msg["import_lec_lien"].$msg[1003].$msg[1001]);
        include("./admin/empr/parametres_perso_empr.inc.php");
        break;
    default:
        $admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
        print $admin_layout;
        echo window_title($database_window_title.$msg["lecteurs_categories"].$msg[1003].$msg[1001]);
        include("$include_path/messages/help/$lang/admin_empr.txt");
        break;
    }
