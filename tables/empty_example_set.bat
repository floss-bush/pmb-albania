@echo off
rem mysql.exe doit figurer dans le path, à défaut, préciser le chemin complet :
rem pour easyphp, mysql.exe peut être là: c:\easyphp\mysql\bin\mysql.exe
rem
rem -u bibli = utilisateur ayant les droits sur la base bibli
rem -pbibli = mot de passe de l'utilisateur
rem -h localhost = machine hébergeant le serveur mysql
rem bibli : la base de données sélectionnée
rem
rem < empty_example_set.sql : le script exécuté : va vider les tables :
rem             analysis
rem             authors           
rem             bulletins
rem             collections       
rem             empr              
rem             empr_groupe      
rem             exemplaires     
rem             groupe          
rem             notices        
rem             pret          
rem             publishers      
rem             series          
rem             sub_collections  
rem ce script doit recevoir qques parametres :
rem %1 le nom de la base de donnees
rem %2 le nom de la machine hote du serveur mysql
rem %3 le user de la base de donnees de PMB
rem %4 le mot de passe du user (qui peut etre vide)
if "%1"=="" goto syntaxe
if "%2"=="" goto syntaxe
if "%3"=="" goto syntaxe
goto suite
:syntaxe
echo syntaxe d'appel de ce script :
echo empty_example_set.cmd param1 param2 param3 param4
echo     ou :
echo        param1 = base de donnees de PMB, "bibli" a l'installation
echo        param2 = nom de la machine hote du serveur MySQL, "localhost" par defaut
echo        param3 = user de la base de donnees de PMB, "bibli" a l'installation
echo        param4 = mot de passe du user de la base, "bibli" a l'installation
goto fin
:suite
echo Si vous avez charge les donnees de test de PMB (data_test.sql),
echo vous disposez d'un jeu de notices et d'exemplaires pour tester PMB.
echo Ce script vous propose de vider ces tables d'exemple de votre application
echo afin de repartir d'une base PMB vierge :
echo ------------------------------------------
echo     table des emprunteurs              
echo     table des groupes d'emprunteurs
echo     table des prets          
echo     table des notices        
echo     table des exemplaires     
echo     table des bulletins de periodiques
echo     table de  depouillement des periodiques
echo     table des series          
echo     table des collections       
echo     table des sous-collections  
echo     table des auteurs           
echo     table des editeurs
echo ------------------------------------------
:start
echo e) VIDER LES TABLES D'EXEMPLES
echo q) Quitter

choice /c:eq /s Taper e pour continuer, q pour quitter
if errorlevel = 2 goto fin
if errorlevel = 1 goto empty
echo Choix invalide
goto start

:empty
echo commande executee : mysql -u %3 -p%4 -h %2 %1  empty_example_set.sql
echo .
mysql -u %3 -p%4 -h %2 %1 < empty_example_set.sql
echo .
echo ------------------------------------------
echo les tables ont ete videes
echo ------------------------------------------
goto sortie
:fin
echo ------------------------------------------
echo operation abandonnee
echo ------------------------------------------
:sortie
