------------------------------------------------------------------------------------------------------------------

Description of the files
bibli.sql : structure of the database only, no data

minimum.sql : admin/admin user, application parameters

feed_essential.sql : this is what you need to use the application in quick-start mode: 
	Preliminary, modifyable application data
	A set of backups ready to use
	A set of z3950 parameters.
	
data_test.sql : A small selection of data containing volumes, borrowers, allowing you to test the PMB suite.
	Volumes, borrowers, lenders, compies, serials
	Based on the application data found in feed_essential.sql
	Must load the thesaurus UNESCO_FR unesco_fr.sql
	
Thesaurus : 3 thesaurus are provided for you:
	unesco_fr.sql : UNESCO's hierarchical thesaurus, important enough and done well.
	grumeau.sql : smaller, simpler but also well done.
	environnement : a thesaurus potentially for use in an environmental library.
	
Internal indices: 4 indices are provided:
	indexint_small_en.sql : Reduced Dewey style index in English
	indexint_100.sql : 100 cases of knowlege, or a colour marguerite flower, Dewey decimal style index
	simplified for education
	indexint_chambery.sql : Dewey style index from the Chambéry library, very well conceived.
	but can be adapted for small libraries
	indexint_dewey.sql : Dewey style index


************************************************************************************************
________________________________________________________________________________________________
Attention, if you carry out an update from an existing database:
------------------------------------------------------------------------------------------------
*********** To do following each installation or application update ****************
When you install a new version
over a previous version, you must vitally,
after copying the files contained in this archive
onto the web server:

check that the parameters contained in :
./includes/db_param.inc.php
./opac_css/includes/opac_db_param.inc.php

correspond to your configuration (do a backup before you start!)

Moreover:
You must do the core update of the database.
Nothing will be lost.

Connect in your normal way to PMB, the graphical style can be different, even absent (display is usable enough without colour or images)

Go to Administration > Utils > update database to put your core database up to date  

A series of messages will indicate the successive updates, 
To continue the database update, use the link at the bottom of the page just after 
'Your database is at version...' is displayed.

You can then edit your account to eventually update
your preferences, in particular your graphical style.

Don't hesitate to tell us about your problems or ideas 
by email : pmb@sigb.net

Moreover, we would be happy to count you among our users and
some figures such as number of readers, items, CDs... with the 
location of your establishment (or specific name) would be enough for us

to get to know you better.

More information in the folder ./doc or also
on our website http://www.sigb.net

The development team


///////////////////// List of the tables filled by the files /////////////////

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            minimum.sql
# Contents of the table `parametres`
# Contents of the table `users`
	utilisateur admin/admin

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            feed_essential.sql
# Contents of the table `docs_codestat`
# Contents of the table `docs_location`
# Contents of the table `docs_section`
# Contents of the table `docs_statut`
# Contents of the table `docs_type`

# Contents of the table `empr_categ`
# Contents of the table `empr_codestat`

# Contents of the table `lenders`

# Contents of the table `sauv_lieux`
# Contents of the table `sauv_sauvegardes`
# Contents of the table `sauv_tables`

# Contents of the table `z_attr`
# Contents of the table `z_bib`


\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            data_test.sql
# Contents of the table `users`
	Utilisateurs supplémentaires
	
# Contents of the table `empr`
# Contents of the table `empr_custom`
# Contents of the table `empr_custom_lists`
# Contents of the table `empr_custom_values`
# Contents of the table `empr_groupe`
# Contents of the table `groupe`

# Contents of the table `notices`
# Contents of the table `notices_custom`
# Contents of the table `notices_custom_lists`
# Contents of the table `notices_custom_values`
# Contents of the table `origine_notice`
# Contents of the table `exemplaires`
# Contents of the table `expl_custom`
# Contents of the table `expl_custom_lists`
# Contents of the table `expl_custom_values`
# Contents of the table `explnum`

# Contents of the table `authors`
# Contents of the table `publishers`
# Contents of the table `collections`
# Contents of the table `sub_collections`
# Contents of the table `responsability`
# Contents of the table `series`

# Contents of the table `notices_categories`

# Contents of the table `bulletins`
# Contents of the table `analysis`

# Contents of the table `notices_categories`

# Contents of the table `caddie`
# Contents of the table `caddie_content`
# Contents of the table `caddie_procs`

# Contents of the table `etagere`
# Contents of the table `etagere_caddie`


# Contents of the table `resa`
# Contents of the table `resa_ranger`

# Contents of the table `quotas`
# Contents of the table `pret`

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            agneaux.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            unesco_fr.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            environnement.sql
# Contents of the table `categories`
# Contents of the table `categ_assoc`

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_chambery.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_100.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_dewey.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_small_en.sql
# Contents of the table `indexint`
