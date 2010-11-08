------------------------------------------------------------------------------------------------------------------

Descrizione dei file
bibli.sql : struttura della base dati - nessun dato

minimum.sql : utente admin/admin, parametri dell'applicazione

feed_essential.sql : quello di cui avete bisogno per essere subito operativi:
	Dati di sistema precaricati e modificabili.
	Uno schema di backup pronto all'uso
	Uno schema di parametraggio Z3950.
	
data_test.sql : un piccolo insieme di dati bibliografici e di utenti per poter provare immediatamente PMB.

************************************************************************************************
________________________________________________________________________________________________
Attenzione, in caso di aggiornamento di una base dati esistente:
------------------------------------------------------------------------------------------------
******* Da fare immediatamente dopo l'installazione o l'aggiornamento dell'applicazione ********

Quando installate una versione nuova su una precedente
dovete obbligatoriamente, dopo la copia dei file contenuti 
in questo archivio scaricato dal web:

Verificare che i parametri contenuti in:
./includes/db_param.inc.php
./opac_css/includes/opac_db_param.inc.php

corrispondano alla vostra configurazione (salvatevela prima!)

Inoltre:
Dovete aggiornare la base dati.
Non andrà perso nulla.

Connettetevi nel solito modo a PMB, la grafica potrebbe essere 
differente.

Scegliete Amministrazione > Strumenti > Aggiorna database per aggiornare 
la struttura del database.

Una serie di messaggi indicheranno gli aggiornamenti successivi, 
continuate con gli aggiornamenti, utilizzando il link in basso, finchè non compare 
 'La vostra base dati è aggiornata alla versione ...'

A questo punto potete accedere ai vostri dati per eventuali modifiche alle preferenze,
specialmente lo stile di visualizzazione.

Non esitate a comunicarci i vostri problemi o i vostri suggerimenti
per mail : pmb@sigb.net (francia)   pmb-italia@reteisi.org (italia)

Inoltre, vi saremo grati se potessimo elencarvi tra i utenti comunicandoci qualche numero
come il numero dei lettori, delle opere, dei CD, VHS, DVD   ecc.ecc unitamente alle
coordinate della vostra installazione ci servirà a conoscervi meglio.

Maggiori informazioni nella cartella  ./doc o anche  
sul nostro sito http://www.sigb.net

Il gruppo di sviluppo.


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
