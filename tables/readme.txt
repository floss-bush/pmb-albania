------------------------------------------------------------------------------------------------------------------

Description des fichiers
bibli.sql : structure de la base de données uniquement, pas de données

minimum.sql : utilisateur admin/admin, paramètres de l'application

feed_essential.sql : ce dont vous avez besoin pour utiliser l'application en mode quick-start :
	Données de l'application préremplies, modifiables.
	Un jeu de sauvegarde prêt à l'emploi
	Un jeu de paramétrage de Z3950.
	
data_test.sql : une petite sélection de données de notices, lecteurs, afin de pouvoir tester de suite PMB.
	Notices, lecteurs, prêteurs, exemplaires, périodiques
	Se base sur les données de l'application fournies dans feed_essential.sql
	Doit charger le thésaurus UNESCO_FR unesco_fr.sql
	
Thésaurus : 3 thésaurus vous sont proposés :
	unesco_fr.sql : thésaurus hiérarchisé de l'UNESCO, assez important et bien fait.
	grumeau.sql : plus petit, plus simple mais bien fait aussi.
	environnement : un thésaurus possible pour un fonds documetaire axé Environnement.
	
Indexations internes : 4 indexations sont proposées :
	indexint_100.sql : 100 cases du savoir ou marguerite des couleurs, indexation décimale 
	style Dewey simplifiée pour l'éducation
	indexint_chambery.sql : indexation style Dewey de la BM de Chambéry, très bien conçue
	mais peu adaptée à des petites bibliothèques
	indexint_dewey.sql : indexation style Dewey
	indexint_small_en.sql : indexation style Dewey réduite et en anglais
	

************************************************************************************************
________________________________________________________________________________________________
Attention, si vous faites une mise à jour d'une base existante :
------------------------------------------------------------------------------------------------
*********** A faire suite à chaque installation ou mise à jour de l'application ****************
Quand vous installez une nouvelle version 
sur une version précédente, vous devez impérativement, 
après la copie des fichiers contenus dans cette archive 
sur le serveur web :

vérifiez que les paramètres contenus dans :
./includes/db_param.inc.php
./opac_css/includes/opac_db_param.inc.php

correspondent à votre configuration (faites une sauvegarde avant !)

En outre :
Vous devez faire la mise à jour du noyau de la base de données.
Rien ne sera perdu.

Connectez-vous de manière habituelle à PMB, le style graphique peut 
être différent, voire absent (affichage assez décousu sans couleur ni images)

Passez en Administration > Outils > maj base pour mettre à jour le noyau de
votre base de données.

Une série de messages vous indiqueront les mises à jour successives, 
poursuivez la mise à jour de la base par le lien en bas de page jusqu'à voir 
s'afficher 'Votre base est à jour en version...'

Vous pouvez alors éditer votre compte pour modifier éventuellement 
vos préférences, notamment le style d'affichage.

N'hésitez pas à nous faire part de vos problèmes ou idées 
par mail : pmb@sigb.net

En outre, nous serions heureux de vous compter parmi nos utilisateurs et
quelques chiffres tels que nombre de lecteurs, d'ouvrages, de CD... avec les
coordonnées de votre établissement (ou à titre particulier) nous suffiront
pour mieux vous connaitre.

Plus d'informations dans le répertoire ./doc ou bien 
sur notre site http://www.sigb.net

L'équipe des développeurs.


///////////////////// Liste des tables remplies par fichiers /////////////////

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            minimum.sql
# Contenu de la table `parametres`
# Contenu de la table `users`
	utilisateur admin/admin

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            feed_essential.sql
# Contenu de la table `docs_codestat`
# Contenu de la table `docs_location`
# Contenu de la table `docs_section`
# Contenu de la table `docs_statut`
# Contenu de la table `docs_type`

# Contenu de la table `empr_categ`
# Contenu de la table `empr_codestat`

# Contenu de la table `lenders`

# Contenu de la table `sauv_lieux`
# Contenu de la table `sauv_sauvegardes`
# Contenu de la table `sauv_tables`

# Contenu de la table `z_attr`
# Contenu de la table `z_bib`


\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            data_test.sql
# Contenu de la table `users`
	Utilisateurs supplémentaires
	
# Contenu de la table `empr`
# Contenu de la table `empr_custom`
# Contenu de la table `empr_custom_lists`
# Contenu de la table `empr_custom_values`
# Contenu de la table `empr_groupe`
# Contenu de la table `groupe`

# Contenu de la table `notices`
# Contenu de la table `notices_custom`
# Contenu de la table `notices_custom_lists`
# Contenu de la table `notices_custom_values`
# Contenu de la table `origine_notice`
# Contenu de la table `exemplaires`
# Contenu de la table `expl_custom`
# Contenu de la table `expl_custom_lists`
# Contenu de la table `expl_custom_values`
# Contenu de la table `explnum`

# Contenu de la table `authors`
# Contenu de la table `publishers`
# Contenu de la table `collections`
# Contenu de la table `sub_collections`
# Contenu de la table `responsability`
# Contenu de la table `series`

# Contenu de la table `notices_categories`

# Contenu de la table `bulletins`
# Contenu de la table `analysis`

# Contenu de la table `notices_categories`

# Contenu de la table `caddie`
# Contenu de la table `caddie_content`
# Contenu de la table `caddie_procs`

# Contenu de la table `etagere`
# Contenu de la table `etagere_caddie`


# Contenu de la table `resa`
# Contenu de la table `resa_ranger`

# Contenu de la table `quotas`
# Contenu de la table `pret`

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            agneaux.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            unesco_fr.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            environnement.sql
# Contenu de la table `categories`
# Contenu de la table `categ_assoc`

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_chambery.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_100.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_dewey.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_small_en.sql
# Contenu de la table `indexint`
