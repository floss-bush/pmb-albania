-- 
-- Dump dei dati per la tabella `caddie`
-- 

INSERT INTO `caddie` VALUES (1, 'Schede per esposizione', 'NOTI', 'Mettere in questa selezione le schede dell''esposizione virtuale', '1 2');
INSERT INTO `caddie` VALUES (2, 'Schede sulle restituzioni', 'NOTI', 'Riempire questa selezione con l''esito delle restituzioni', '1 2');
INSERT INTO `caddie` VALUES (3, 'Esemplari restituiti', 'EXPL', 'Mettere in questa selezione gli esemplari da restituire alla biblioteca', '1 2');
INSERT INTO `caddie` VALUES (4, 'Doppioni di schede sul Titolo', 'NOTI', 'Doppioni sul titolo principale', '1 2');
INSERT INTO `caddie` VALUES (5, 'Esempio di selezione di esemplari', 'EXPL', '', '1 4 3 2');
 
-- 
-- Dump dei dati per la tabella `caddie_procs`
-- 

INSERT INTO `caddie_procs` VALUES (3, 'SELECT', 'EXPL per sezione / proprietario', 0x73656c656374206578706c5f6964206173206f626a6563745f69642c20274558504c27206173206f626a6563745f747970652066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f73656374696f6e2c206c656e6465727320776865726520696473656374696f6e20696e2028212173656374696f6e21212920616e64206578706c5f6f776e65723d212170726f7072696f212120616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f73656374696f6e3d696473656374696f6e20616e64206578706c5f6f776e65723d69646c656e646572206f726465722062792073656374696f6e5f6c6962656c6c652c206578706c5f636f74652c206578706c5f636220, 'Ricerca di esemplari per sezione e proprietario', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="section" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Section]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idsection, section_libelle from docs_section order by section_libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n <FIELD NAME="proprio" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Propriétaire]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY>select idlender, lender_libelle from lenders order by lender_libelle</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `caddie_procs` VALUES (4, 'SELECT', 'EXPL sul dorso che comincia per', 0x73656c656374206578706c5f6964206173206f626a6563745f69642c20274558504c27206173206f626a6563745f747970652066726f6d206578656d706c6169726573207768657265206578706c5f636f7465206c696b6520272121636f6d6d655f636f746521212527, 'Ricerca di esemplari in base all''inizio del contenuto del dorso', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="comme_cote" MANDATORY="no">\r\n  <ALIAS><![CDATA[Début de la cote]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>20</SIZE>\r\n <MAXSIZE>20</MAXSIZE>\r\n</OPTIONS> \r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `caddie_procs` VALUES (6, 'ACTION', 'Restituzione alla biblioteca', 0x757064617465206578656d706c616972657320736574206578706c5f7374617475743d21216e6f75766561755f7374617475742121207768657265206578706c5f696420696e2028434144444945284558504c2929, 'Permette di cambiare lo stato degli esemplari in una selezione', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="nouveau_statut" MANDATORY="yes">\r\n  <ALIAS><![CDATA[nouveau_statut]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY>SELECT idstatut, statut_libelle FROM docs_statut</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `caddie_procs` VALUES (1, 'SELECT', 'Schede per autore', 0x53454c454354206e6f746963655f6964206173206f626a6563745f69642c20274e4f544927206173206f626a6563745f747970652046524f4d206e6f74696365732c20617574686f72732c20726573706f6e736162696c69747920574845524520617574686f725f6e616d65206c696b652027252121637269746572652121252720414e4420617574686f725f69643d726573706f6e736162696c6974795f617574686f7220414e44206e6f746963655f69643d726573706f6e736162696c6974795f6e6f746963650d0a, 'Ricerca di schede in base alle prime lettere del nome dell''autore', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="critere" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Caractères contenus dans le nom]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>25</SIZE>\r\n <MAXSIZE>25</MAXSIZE>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `caddie_procs` VALUES (2, 'SELECT', 'Doppioni di schede', 0x6372656174652054454d504f52415259205441424c4520746d702053454c45435420746974312046524f4d206e6f74696365732047524f5550204259207469743120484156494e4720636f756e74282a293e310d0a53454c454354206e6f746963655f6964206173206f626a6563745f69642c20274e4f544927206173206f626a6563745f747970652046524f4d206e6f74696365732c20746d70207748455245206e6f74696365732e746974313d746d702e74697431, 'Ricerca delle schede doppie per titolo principale', '1 2', NULL);


-- 
-- Dump dei dati per la tabella `docs_codestat`
-- 

INSERT INTO `docs_codestat` VALUES (1, 'Indeterminato BDP', 'x', 1);
INSERT INTO `docs_codestat` VALUES (2, 'Ragazzi BDP', 'r', 1);
INSERT INTO `docs_codestat` VALUES (3, 'Adulti BDP', 'a', 1);
INSERT INTO `docs_codestat` VALUES (4, 'Indeterminato fondo proprio', 'x', 0);
INSERT INTO `docs_codestat` VALUES (5, 'Ragazzi fondo proprio', 'r', 2);
INSERT INTO `docs_codestat` VALUES (6, 'Adulti fondo proprio', 'a', 2);
INSERT INTO `docs_codestat` VALUES (14, 'indeterminato', 'u', 1);

-- 
-- Dump dei dati per la tabella `docs_location`
-- 

INSERT INTO `docs_location` VALUES (1, 'Biblioteca principale', '', 2, '', 1, 'PMB: test biblioteca', 'Via per S.Marco', '', '23017', 'Morbegno', '', 'Italia', '0342 612597', 'biblioteca@saraceno.org', 'http://www.sigb.net', 'logo_default.jpg', 'logo_default_small.jpg');
INSERT INTO `docs_location` VALUES (2, 'Collocazione import BDP', 'bp', 1, '', 1, 'PMB: test biblioteca', 'Via per S.Marco', '', '23017', 'Morbegno', '', 'Italia', '0342 612597', 'biblioteca@saraceno.org', 'http://www.sigb.net', 'logo_default.jpg', 'logo_default_small.jpg');
INSERT INTO `docs_location` VALUES (3, 'Bib princip-import(1)', 'Bib princip', 1, '', 1, 'PMB: test biblioteca', 'Via per S.Marco', '', '23017', 'Morbegno', '', 'Italia', '0342 612597', 'biblioteca@saraceno.org', 'http://www.sigb.net', 'logo_default.jpg', 'logo_default_small.jpg');

-- 
-- Dump dei dati per la tabella `docs_section`
-- 

INSERT INTO `docs_section` VALUES (1, 'Racconti per bambini', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (2, 'Documentari', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (3, 'Documentari per bambini', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (4, 'Romanzi per bambini', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (5, 'Romanzi per ragazzi', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (6, 'Racconti per adulti', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (7, 'Racconti per ragazzi', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (8, 'Fumetti per adulti', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (9, 'Fumetti per bambini', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (10, 'SL (Storia locale)', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (11, 'Fumetti per ragazzi', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (12, 'Romanzi polizieschi', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (13, 'Romanzi di fantascienza', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (14, 'Romanzi e Romanzi stranieri', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (15, 'Documentari per ragazzi', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (16, 'Album per bambini', '', 2, '', 1);
INSERT INTO `docs_section` VALUES (17, 'indeterminato', 'x', 0, '', 1);
INSERT INTO `docs_section` VALUES (29, 'indeterminato', 'u', 1, '', 1);

-- 
-- Dump dei dati per la tabella `docs_statut`
-- 

INSERT INTO `docs_statut` VALUES (1, 'Escluso dal prestito', 0, '', 2);
INSERT INTO `docs_statut` VALUES (2, 'Documento in buono stato', 1, '', 2);
INSERT INTO `docs_statut` VALUES (3, 'Deteriorato', 0, '', 2);
INSERT INTO `docs_statut` VALUES (4, 'Smarrito', 0, '', 2);
INSERT INTO `docs_statut` VALUES (5, 'Documento in via di acquisiz.', 0, '', 2);
INSERT INTO `docs_statut` VALUES (6, 'In corso di battitura', 0, '', 2);
INSERT INTO `docs_statut` VALUES (7, 'Libri della BIBLIOTECA', 1, '', 2);

-- 
-- Dump dei dati per la tabella `docs_type`
-- 

INSERT INTO `docs_type` VALUES (1, '$r non conforme --', 0, 15, 1, '', 0.00);
INSERT INTO `docs_type` VALUES (2, 'Libro', 14, 15, 2, 'al', 0.00);
INSERT INTO `docs_type` VALUES (3, 'Documento BDP', 14, 15, 2, 'az', 0.00);
INSERT INTO `docs_type` VALUES (4, 'VHS', 14, 15, 2, 'mm', 0.00);
INSERT INTO `docs_type` VALUES (5, 'CD audio', 14, 15, 2, 'je', 0.00);
INSERT INTO `docs_type` VALUES (6, 'DVD film', 5, 15, 2, 'gf', 0.00);
INSERT INTO `docs_type` VALUES (7, 'DVDROM', 5, 15, 2, 'mf', 0.00);
INSERT INTO `docs_type` VALUES (8, 'Opera d''arte', 5, 15, 2, '', 0.00);
INSERT INTO `docs_type` VALUES (9, 'Cartografie e mappe', 30, 15, 2, 'an', 0.00);
INSERT INTO `docs_type` VALUES (10, 'CDROM', 10, 5, 2, 'le', 0.00);
INSERT INTO `docs_type` VALUES (11, 'Periodico', 8, 5, 0, '', 0.00);
INSERT INTO `docs_type` VALUES (12, 'Materiale grigio', 31, 15, 2, '', 0.00);
INSERT INTO `docs_type` VALUES (21, 'mat.stp-libro', 30, 15, 1, 'al', 0.00);
INSERT INTO `docs_type` VALUES (22, 'doc.mult-VHS', 0, 15, 1, 'mm', 0.00);
INSERT INTO `docs_type` VALUES (23, 'supp.inf-CDROM', 0, 15, 1, 'le', 0.00);
INSERT INTO `docs_type` VALUES (24, 'mat.stp-cart.mappe', 0, 15, 1, 'an', 0.00);


-- 
-- Dump dei dati per la tabella `empr_categ`
-- 

INSERT INTO `empr_categ` VALUES (3, 'Personale della biblioteca', 365, 0.00);
INSERT INTO `empr_categ` VALUES (6, 'Alunni', 9999, 0.00);
INSERT INTO `empr_categ` VALUES (7, 'Adulti (tariffa piena)', 365, 0.00);
INSERT INTO `empr_categ` VALUES (8, 'Docenti', 9999, 0.00);

-- 
-- Dump dei dati per la tabella `empr_codestat`
-- 

INSERT INTO `empr_codestat` VALUES (1, 'Fuori regione');
INSERT INTO `empr_codestat` VALUES (2, 'Comune');
INSERT INTO `empr_codestat` VALUES (3, 'Regione');
INSERT INTO `empr_codestat` VALUES (4, 'Europa');
INSERT INTO `empr_codestat` VALUES (5, 'Extra-europeo');
INSERT INTO `empr_codestat` VALUES (6, 'Italia');
INSERT INTO `empr_codestat` VALUES (7, 'ldap');


-- 
-- Dump dei dati per la tabella `lenders`
-- 

INSERT INTO `lenders` VALUES (1, 'BDP');
INSERT INTO `lenders` VALUES (2, 'Fondo proprio');

-- 
-- Dump dei dati per la tabella `procs`
-- 

INSERT INTO `procs` VALUES (1, 'Lista esemplari / stato', 0x73656c656374206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f7469636573207768657265206578706c5f7374617475743d2121706172616d31212120616e64206578706c5f6e6f746963653d6e6f746963655f6964206f72646572206279206578706c5f636f7465, 'Lista esemplari parametrizzata per  stato', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Stato]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idstatut,statut_libelle from docs_statut]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[Scegli uno stato]]></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (2, 'Stat: conteggio esemplari / stato', 0x73656c656374207374617475745f6c6962656c6c652066726f6d206578656d706c61697265732c20646f63735f7374617475742c20636f756e74282a29206173204e6272652077686572652069647374617475743d6578706c5f7374617475742067726f7570206279207374617475745f6c6962656c6c65206f72646572206279206964737461747574, 'Numero di esemplari per stato', '1 4 3 2', NULL);
INSERT INTO `procs` VALUES (3, 'Stat: conteggio esemplari / prestatori', 0x73656c656374206c656e6465725f6c6962656c6c652c20636f756e74282a29206173204e6272652066726f6d206578656d706c61697265732c206c656e64657273207768657265206578706c5f6f776e65723d69646c656e6465722067726f7570206279206c656e6465725f6c6962656c6c65206f72646572206279206c656e6465725f6c6962656c6c6520, 'Numero di esemplari per prestatori', '1 4 3 2', NULL);
INSERT INTO `procs` VALUES (4, 'Stat: conteggio  esemplari /prestatori /stato', 0x73656c656374206c656e6465725f6c6962656c6c652c2069647374617475742c207374617475745f6c6962656c6c65202c20636f756e74282a29206173204e6272652066726f6d206578656d706c61697265732c206c656e646572732c20646f63735f737461747574207768657265206578706c5f6f776e65723d69646c656e64657220616e64206578706c5f7374617475743d69647374617475742067726f7570206279206c656e6465725f6c6962656c6c652c7374617475745f6c6962656c6c65206f72646572206279206c656e6465725f6c6962656c6c652c7374617475745f6c6962656c6c6520, 'Nombre di esemplari per prestatori e stato', '1 4 3 2', NULL);
INSERT INTO `procs` VALUES (5, 'Lista esemplari per stato', 0x73656c656374206c656e6465725f6c6962656c6c652c207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f7374617475742c206c656e64657273207768657265206578706c5f7374617475743d2121737461747574212120616e64206578706c5f6f776e65723d212150726f707269657461697265212120616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f7374617475743d696473746174757420616e64206578706c5f6f776e65723d69646c656e646572206f72646572206279206c656e6465725f6c6962656c6c652c207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f636220, 'Lista degli esemplari dei depositi propri per stato dorso, codice a barre e titolo', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="statut" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Stato]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY>select idstatut, statut_libelle from docs_statut</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n <FIELD NAME="Proprietaire" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Proprietario]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY>select idlender, lender_libelle from lenders</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (6, 'Stat: conteggio esemplari / sezione', 0x73656c65637420696473656374696f6e2c2073656374696f6e5f6c6962656c6c652c20636f756e74282a29206173204e6272652066726f6d206578656d706c61697265732c20646f63735f73656374696f6e20776865726520696473656374696f6e3d6578706c5f73656374696f6e2067726f757020627920696473656374696f6e2c2073656374696f6e5f6c6962656c6c65206f7264657220627920696473656374696f6e, 'Numero di esemplari per sezione', '1 4 3 2', NULL);
INSERT INTO `procs` VALUES (7, 'Lista esemplari per prestatore per una o più sezioni', 0x73656c6563742073656374696f6e5f6c6962656c6c652c206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f73656374696f6e2c206c656e6465727320776865726520696473656374696f6e20696e2028212173656374696f6e7321212920616e64206578706c5f6f776e65723d212170726574657572212120616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f73656374696f6e3d696473656374696f6e20616e64206578706c5f6f776e65723d69646c656e646572206f726465722062792073656374696f6e5f6c6962656c6c652c206578706c5f636f74652c206578706c5f636220, 'Lista degli esemplari di un prestatore aventi una o più sezioni particolari', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="sections" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Sezione(i)]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idsection, section_libelle from docs_section]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n <FIELD NAME="preteur" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Prestatore]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idlender, lender_libelle from lenders order by idlender]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[Scegli un prestatore]]></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (8, 'Stat: conteggio esemplari / proprietario', 0x73656c656374206c656e6465725f6c6962656c6c652061732050726f7072696f2c20636f756e74282a29206173204e6272652066726f6d206578656d706c61697265732c206c656e646572732077686572652069646c656e6465723d6578706c5f6f776e65722067726f7570206279206578706c5f6f776e65722c206c656e6465725f6c6962656c6c65, 'Numero esemplari per proprietario', '1 4 3 2', NULL);
INSERT INTO `procs` VALUES (9, 'Lista degli esemplari dei depositi propri', 0x73656c656374207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f737461747574207768657265206578706c5f6f776e65723d3020616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f7374617475743d6964737461747574206f72646572206279207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f636220, 'Lista degli esemplari dei depositi propri per stato dorso, codice a barre e titolo', '1 4 3 2', NULL);
INSERT INTO `procs` VALUES (10, 'Lista esemplari di un prestatore', 0x73656c656374206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f7374617475742c206c656e64657273207768657265206578706c5f6f776e65723d212170726f707269657461697265212120616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f7374617475743d696473746174757420616e64206578706c5f6f776e65723d69646c656e646572206f7264657220627920206578706c5f636f74652c206578706c5f636220, 'Lista degli esemplari di un prestatore per dorso e codice a barre', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="proprietaire" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Proprietario]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY>select idlender, lender_libelle from lenders order by idlender</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE="">Scegli un prestatore</UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (11, 'Stat: conteggio lettori / categoria', 0x73656c656374206c6962656c6c652c20636f756e74282a2920617320274e627265206c65637465757273272066726f6d20656d70722c20656d70725f63617465672077686572652069645f63617465675f656d70723d656d70725f63617465672067726f7570206279206c6962656c6c65206f72646572206279206c6962656c6c65, 'Numero di lettori per categoria', '1 4 3 2', NULL);
INSERT INTO `procs` VALUES (13, 'Lista lettori / categoria', 0x73656c656374206c6962656c6c6520617320436174e9676f7269652c20656d70725f6e6f6d206173204e6f6d2c20656d70725f7072656e6f6d206173205072e96e6f6d2c20656d70725f7965617220617320446174654e61697373616e63652066726f6d20656d70722c20656d70725f63617465672077686572652069645f63617465675f656d70723d656d70725f6361746567206f72646572206279206c6962656c6c652c20656d70725f6e6f6d2c20656d70725f7072656e6f6d, 'Lista lettori per categoria di lettore', '1 4 3 2', NULL);
INSERT INTO `procs` VALUES (14, 'Lista prestiti per categoria', 0x53454c45435420656d70725f63617465672e6c6962656c6c6520617320436174e9676f7269652c20656d70722e656d70725f6e6f6d206173204e6f6d2c20656d70722e656d70725f7072656e6f6d206173205072e96e6f6d2c20656d70722e656d70725f6362206173204e756de9726f2c206578656d706c61697265732e6578706c5f636220617320436f646542617272652c206e6f74696365732e746974312061732054697472652046524f4d20707265742c656d70722c656d70725f63617465672c6578656d706c61697265732c6e6f746963657320574845524520656d70725f63617465672e69645f63617465675f656d707220696e2028212163617465676f72696521212920616e6420656d70722e656d70725f6361746567203d20656d70725f63617465672e69645f63617465675f656d707220616e6420707265742e707265745f6964656d7072203d20656d70722e69645f656d707220616e6420707265742e707265745f69646578706c203d206578656d706c61697265732e6578706c5f696420616e64206578656d706c61697265732e6578706c5f6e6f74696365203d206e6f74696365732e6e6f746963655f6964206f7264657220627920312c322c332c36, 'Lista degli esemplari in prestito per una o più categorie di lettori', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="categorie" MANDATORY="yes">\r\n  <ALIAS><![CDATA[categoria]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select id_categ_empr, libelle from empr_categ order by libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (20, 'Lista depositi propri per stato', 0x73656c656374207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f737461747574207768657265206578706c5f6f776e65723d3020616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f7374617475743d6964737461747574206f72646572206279207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f636220, 'Spunta depositi propri', '1 4 3 2', NULL);
INSERT INTO `procs` VALUES (21, 'Stat : conteggio lettori / età', 0x53454c45435420636f756e74282a292c2043415345205748454e2020282121706172616d312121202d20656d70725f7965617229203c3d203133205448454e20274a757371756520313320616e7327205748454e20282121706172616d312121202d20656d70725f7965617229203e313320616e6420282121706172616d312121202d20656d70725f79656172293c3d3234205448454e2027313420e020323420616e7327205748454e20282121706172616d312121202d20656d70725f79656172293e323420616e6420282121706172616d312121202d20656d70725f79656172293c3d3539205448454e2027323520e020323920616e7327205748454e20282121706172616d312121202d20656d70725f79656172293e3539205448454e2027363020616e7320657420706c7573272020454c5345202765727265757220737572206167652720454e442061732063617465675f6167652066726f6d20656d707220776865726520656d70725f636174656720696e2028212163617465676f72696521212920616e6420287965617228656d70725f646174655f65787069726174696f6e293d2121706172616d312121206f72207965617228656d70725f646174655f6164686573696f6e293d2121706172616d312121292067726f75702062792063617465675f616765, 'Numero dei lettori per fascia di età', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \r\n </FIELD>\r\n <FIELD NAME="categorie" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Categoria]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY>select id_categ_empr, libelle from empr_categ order by libelle</QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (22, 'Stat : conteggio lettori / sesso / età', 0x53454c45435420636f756e74282a292c2063617365207768656e20656d70725f736578653d273127207468656e2027486f6d6d657327207768656e20656d70725f736578653d273227207468656e202746656d6d65732720656c736520276572726575722073757220736578652720656e6420617320536578652c2043415345205748454e2020282121706172616d312121202d20656d70725f7965617229203c3d203133205448454e20274a757371756520313320616e7327205748454e20282121706172616d312121202d20656d70725f7965617229203e313320616e6420282121706172616d312121202d20656d70725f7965617229203c3d203234205448454e2027313420e020323420616e7327205748454e20282121706172616d312121202d20656d70725f7965617229203e323420616e6420282121706172616d312121202d20656d70725f7965617229203c3d203539205448454e2027323520e020353920616e7327205748454e20282121706172616d312121202d20656d70725f7965617229203e3539205448454e2027363020616e7320657420706c7573272020454c5345202765727265757220737572206167652720454e442061732063617465675f6167652066726f6d20656d707220776865726520656d70725f636174656720696e2028212163617465676f72696521212920616e6420287965617228656d70725f646174655f65787069726174696f6e293d2121706172616d312121206f72207965617228656d70725f646174655f6164686573696f6e293d2121706172616d312121292067726f757020627920736578652c2063617465675f616765, 'Numero dei lettori per sesso e fascia di età', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \r\n </FIELD>\r\n <FIELD NAME="categorie" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Categoria]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY>select id_categ_empr, libelle from empr_categ order by libelle</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (23, 'Stat : conteggio lettori per città e categoria', 0x73656c65637420656d70725f76696c6c652061732056696c6c652c20636f756e74282a29206173204e6272652066726f6d20656d707220776865726520656d70725f636174656720696e2028212163617465676f72696521212920616e6420287965617228656d70725f646174655f65787069726174696f6e293d2121616e6e65652121206f72207965617228656d70725f646174655f6164686573696f6e293d2121616e6e65652121292067726f757020627920656d70725f76696c6c65206f7264657220627920656d70725f76696c6c65, 'Numero dei lettori per città e categoria', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="categorie" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Categoria]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY>select id_categ_empr, libelle from empr_categ order by libelle</QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n <FIELD NAME="annee" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (24, 'Stat: conteggio lettori per categoria e anno', 0x53454c45435420636f756e74282a29206173206e6272655f656c6576652066726f6d20656d707220776865726520656d70725f636174656720696e2028212163617465676f72696521212920616e6420616e6420287965617228656d70725f646174655f65787069726174696f6e293d2121616e6e65652121206f72207965617228656d70725f646174655f6164686573696f6e293d2121616e6e6565212129, 'Numero dei lettori', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="categorie" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Categoria di lettori]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select id_categ_empr, libelle from empr_categ order by libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n <FIELD NAME="annee" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (25, 'Stat : Conteggio dei prestiti ad alunni e docenti', 0x53454c45435420636f756e74282a29206173206e6272655f707265745f656c6576652066726f6d20707265745f61726368697665207768657265206172635f656d70725f636174656720696e2028212163617465676f72696521212920616e642079656172286172635f646562757429203d20272121706172616d312121270d0a, 'Numero dei prestiti ad alunni e docenti', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="categorie" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Categoria]]></ALIAS>\r\n  <TYPE>query_list</TYPE>\r\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select id_categ_empr, libelle from empr_categ order by libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\r\n </FIELD>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS>\r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (26, 'Stat : Conteggio dei prestiti di Documentari per Bambini', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f707265745f446f63755f452046524f4d20707265745f6172636869766520776865726520286c65667420286172635f6578706c5f636f74652c32293d27452027206f72206c65667420286172635f6578706c5f636f74652c33293d2745422027206f72206c65667420286172635f6578706c5f636f74652c32293d27452e2729616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Numero dei prestiti di documentari per bambini in un anno dato', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (27, 'Stat : Conteggio dei prestiti di romanzi per bambini', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f66696374696f6e5f452046524f4d20707265745f6172636869766520776865726520286c65667420286172635f6578706c5f636f74652c33293d2745412027206f72206c65667420286172635f6578706c5f636f74652c33293d2745424427206f72206c65667420286172635f6578706c5f636f74652c33293d2745432027206f72206c65667420286172635f6578706c5f636f74652c33293d27455220272920616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Numero dei prestiti di romanzi per bambini in un anno dato', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (28, 'Stat : Conteggio dei prestiti di romanzi per giovani e alunni', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f66696374696f6e5f412046524f4d20707265745f6172636869766520776865726520286c65667420286172635f6578706c5f636f74652c31293d275227206f72206c65667420286172635f6578706c5f636f74652c33293d2742442027206f72206c65667420286172635f6578706c5f636f74652c32293d274a5227206f72206c65667420286172635f6578706c5f636f74652c33293d274a4244272920616e64206c65667420286172635f6578706c5f636f74652c33293c3e275245202720616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Numero dei prestiti di romanzi per giovani e alunni in un anno dato', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (29, 'Stat : Conteggio prestiti dei Documentari per Giovani e Adulti', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f446f63755f412046524f4d20707265745f6172636869766520776865726520286c65667420286172635f6578706c5f636f74652c32293d27482027206f72206c65667420286172635f6578706c5f636f74652c32293d27422027206f72206c65667420286172635f6578706c5f636f74652c33293d2746522027206f72206c65667420286172635f6578706c5f636f74652c32293d274a2027206f72206c65667420286172635f6578706c5f636f74652c32293d274a2e27206f72206c656674286172635f6578706c5f636f74652c3129206265747765656e2027302720616e64202739272920616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Numero dei prestiti dei Documentari per giovani e adulti in un dato anno', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (30, 'Stat : Conteggio di TUTTI i prestiti (Periodici esclusi)', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f544f54414c2046524f4d20707265745f61726368697665207768657265206172635f6578706c5f636f7465206e6f74206c696b6520275020252720616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Numero di TUTTI i prestiti (Periodici esclusi)', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \r\n </FIELD>\r\n</FIELDS>');
INSERT INTO `procs` VALUES (31, 'Stat : Conteggio dei prestiti dei periodici', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f544f54414c2046524f4d20707265745f61726368697665207768657265206172635f6578706c5f636f7465206c696b6520275020252720616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Numero dei prestiti dei periodici', '1 4 3 2', '<?xml version="1.0" encoding="iso-8859-1"?>\r\n<FIELDS>\r\n <FIELD NAME="param1" MANDATORY="yes">\r\n  <ALIAS><![CDATA[Anno di calcolo]]></ALIAS>\r\n  <TYPE>text</TYPE>\r\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \r\n </FIELD>\r\n</FIELDS>');

-- 
-- Dump dei dati per la tabella `sauv_lieux`
-- 

INSERT INTO `sauv_lieux` VALUES (1, 'locale', '/home/pmb-bak', 'file', '', '', '');

-- 
-- Dump dei dati per la tabella `sauv_sauvegardes`
-- 

INSERT INTO `sauv_sauvegardes` VALUES (1, 'tutto', 'tutto', '7', '1', '1,3', 1, 'internal::', 0, '', '');
INSERT INTO `sauv_sauvegardes` VALUES (2, 'auth', 'auth', '2', '', '1', 0, 'internal::', 0, '', '');
INSERT INTO `sauv_sauvegardes` VALUES (3, 'iscritti al prestito', 'prest', '5', '', '1', 0, 'internal::', 0, '', '');
INSERT INTO `sauv_sauvegardes` VALUES (4, 'applicazione', 'applicazione', '6', '', '1', 0, 'internal::', 0, '', '');
INSERT INTO `sauv_sauvegardes` VALUES (5, 'caddies', 'cadd', '9', '', '1', 0, 'internal::', 0, '', '');

-- 
-- Dump dei dati per la tabella `sauv_tables`
-- 

INSERT INTO `sauv_tables` VALUES (1, 'Biblio', 'analysis,bulletins,docs_codestat,docs_location,docs_section,docs_statut,docs_type,exemplaires,notices,etagere_caddie,notices_custom,notices_custom_lists,notices_custom_values');
INSERT INTO `sauv_tables` VALUES (2, 'Autorità', 'authors,collections,publishers,series,sub_collections,responsability');
INSERT INTO `sauv_tables` VALUES (3, 'Nessuna utilità', 'error_log,import_marc,sessions');
INSERT INTO `sauv_tables` VALUES (4, 'Z3950', 'z_attr,z_bib,z_notices,z_query');
INSERT INTO `sauv_tables` VALUES (5, 'Iscritti_al_prestito', 'empr,empr_categ,empr_codestat,empr_groupe,groupe,pret,pret_archive,resa,empr_custom,empr_custom_lists,empr_custom_values,expl_custom,expl_custom_lists,expl_custom_values');
INSERT INTO `sauv_tables` VALUES (6, 'Applicazione', 'categories,lenders,parametres,procs,sauv_lieux,sauv_log,sauv_sauvegardes,sauv_tables,users,explnum,indexint,notices_categories,origine_notice,quotas,etagere,resa_ranger');
INSERT INTO `sauv_tables` VALUES (7, 'TUTTO', 'analysis,authors,bulletins,categories,collections,docs_codestat,docs_location,docs_section,docs_statut,docs_type,empr,empr_categ,empr_codestat,empr_groupe,error_log,exemplaires,groupe,import_marc,lenders,notices,parametres,pret,pret_archive,procs,publishers,resa,sauv_lieux,sauv_log,sauv_sauvegardes,sauv_tables,series,sessions,sub_collections,users,z_attr,z_bib,z_notices,z_query,caddie,caddie_content,empr_custom,empr_custom_lists,empr_custom_values,explnum,indexint,notices_categories,origine_notice,quotas,responsability,etagere,etagere_caddie');
INSERT INTO `sauv_tables` VALUES (9, 'Caddies', 'caddie_procs,caddie,caddie_content');

-- 
-- Dump dei dati per la tabella `z_attr`
-- 

INSERT INTO `z_attr` VALUES (9, 'isbn', '7');
INSERT INTO `z_attr` VALUES (9, 'titre', '4');
INSERT INTO `z_attr` VALUES (9, 'sujet', '21');
INSERT INTO `z_attr` VALUES (9, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (10, 'sujet', '21');
INSERT INTO `z_attr` VALUES (10, 'isbn', '7');
INSERT INTO `z_attr` VALUES (10, 'titre', '4');
INSERT INTO `z_attr` VALUES (10, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (8, 'sujet', '21');
INSERT INTO `z_attr` VALUES (8, 'isbn', '7');
INSERT INTO `z_attr` VALUES (8, 'titre', '4');
INSERT INTO `z_attr` VALUES (8, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (7, 'sujet', '21');
INSERT INTO `z_attr` VALUES (7, 'titre', '4');
INSERT INTO `z_attr` VALUES (7, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (7, 'isbn', '7');
INSERT INTO `z_attr` VALUES (6, 'isbn', '7');
INSERT INTO `z_attr` VALUES (6, 'sujet', '21');
INSERT INTO `z_attr` VALUES (6, 'titre', '4');
INSERT INTO `z_attr` VALUES (6, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (5, 'sujet', '21');
INSERT INTO `z_attr` VALUES (5, 'isbn', '7');
INSERT INTO `z_attr` VALUES (5, 'titre', '4');
INSERT INTO `z_attr` VALUES (5, 'auteur', '1004');
INSERT INTO `z_attr` VALUES (4, 'isbn', '7');
INSERT INTO `z_attr` VALUES (4, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (4, 'titre', '4');
INSERT INTO `z_attr` VALUES (4, 'sujet', '21');
INSERT INTO `z_attr` VALUES (3, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (3, 'isbn', '7');
INSERT INTO `z_attr` VALUES (3, 'titre', '4');
INSERT INTO `z_attr` VALUES (3, 'sujet', '21');
INSERT INTO `z_attr` VALUES (2, 'isbn', '7');
INSERT INTO `z_attr` VALUES (2, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (2, 'titre', '4');
INSERT INTO `z_attr` VALUES (2, 'sujet', '21');
INSERT INTO `z_attr` VALUES (1, 'isbn', '7');
INSERT INTO `z_attr` VALUES (1, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (1, 'titre', '4');
INSERT INTO `z_attr` VALUES (1, 'sujet', '21');
INSERT INTO `z_attr` VALUES (16, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (16, 'isbn', '7');
INSERT INTO `z_attr` VALUES (16, 'sujet', '21');
INSERT INTO `z_attr` VALUES (12, 'sujet', '21');
INSERT INTO `z_attr` VALUES (12, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (12, 'isbn', '7');
INSERT INTO `z_attr` VALUES (12, 'titre', '4');
INSERT INTO `z_attr` VALUES (13, 'sujet', '21');
INSERT INTO `z_attr` VALUES (13, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (13, 'isbn', '7');
INSERT INTO `z_attr` VALUES (13, 'titre', '4');
INSERT INTO `z_attr` VALUES (14, 'sujet', '21');
INSERT INTO `z_attr` VALUES (14, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (14, 'isbn', '7');
INSERT INTO `z_attr` VALUES (14, 'titre', '4');
INSERT INTO `z_attr` VALUES (0, 'sujet', '21');
INSERT INTO `z_attr` VALUES (0, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (0, 'isbn', '7');
INSERT INTO `z_attr` VALUES (0, 'titre', '4');
INSERT INTO `z_attr` VALUES (15, 'sujet', '21');
INSERT INTO `z_attr` VALUES (15, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (15, 'isbn', '7');
INSERT INTO `z_attr` VALUES (15, 'titre', '4');
INSERT INTO `z_attr` VALUES (16, 'titre', '4');

-- 
-- Dump dei dati per la tabella `z_bib`
-- 

INSERT INTO `z_bib` VALUES (5, 'Univ. Perugia', 'CATALOG', 'alpha.unipg.it', '9909', 'UPG01', 'UNIMARC', '', '', '');
INSERT INTO `z_bib` VALUES (6, 'Univ. Siena', 'CATALOG', '193.205.4.18', '9909', 'sbs02', 'UNIMARC', '', '', '');
INSERT INTO `z_bib` VALUES (7, 'Auriga Biblioteche di Modena', 'CATALOG', 'delphi.cedoc.mo.it', '2210', 'default', 'UNIMARC', '', '', '');
INSERT INTO `z_bib` VALUES (8, 'Univ. Ca Foscari di Venezia', 'CATALOG', '157.138.47.40', '21210', 'ADVANCE', 'UNIMARC', '', '', '');
INSERT INTO `z_bib` VALUES (9, 'Sistema Bibliotecario Parmense', 'CATALOG', '160.78.97.202', '2100', 'sebinaopac', 'sutrs', '', '', 'sebina');
INSERT INTO `z_bib` VALUES (1, 'BN France', 'CATALOG', 'z3950.bnf.fr', '2211', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1456', 'UNIMARC', 'Z3950', 'Z3950_BNF', '');
INSERT INTO `z_bib` VALUES (2, 'Univ Oxford', 'CATALOG', 'library.ox.ac.uk', '210', 'ADVANCE', 'usmarc', '', '', '');
INSERT INTO `z_bib` VALUES (3, 'Univ Lib Edinburgh', 'CATALOG', 'catalogue.lib.ed.ac.uk', '7090', 'voyager', 'USMARC', '', '', '');
INSERT INTO `z_bib` VALUES (4, 'Library Of Congress', 'CATALOG', 'z3950.loc.gov', '7090', 'Voyager', 'USMARC', '', '', '');
INSERT INTO `z_bib` VALUES (10, 'Univ. Genova', 'CATALOG', 'cassandra.csita.unige.it', '9909', 'GEN01', 'UNIMARC', '', '', '');
INSERT INTO `z_bib` VALUES (12, 'IEI Istituto di Elaborazione della Informazione CNR Area della ricerca di Pisa', 'CATALOG', 'arca.iei.pi.cnr.it', '2100', 'IEI-books', 'SUTRS', '', '', 'it_IT');
INSERT INTO `z_bib` VALUES (13, 'Università degli studi di Brescia', 'CATALOG', 'isis.cilea.it', '2100', 'usmarc', 'SUTRS', '', '', '');
INSERT INTO `z_bib` VALUES (14, 'Biblioteche IUAV - sutrs', 'CATALOG', 'opac.iuav.it', '2100', 'NEW', 'sutrs', '', '', '');
INSERT INTO `z_bib` VALUES (15, 'Univ.MIlano', 'CATALOG', '131.175.1.190', '2100', 'sebinaopac', 'SUTRS', '', '', 'sebina');
INSERT INTO `z_bib` VALUES (16, 'SBN-online', 'CATALOG', 'opac.sbn.it', '2100', 'nopac', 'SUTRS', '', '', 'sbnsutrs');
