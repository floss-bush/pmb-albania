-- +-------------------------------------------------+
-- © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
-- +-------------------------------------------------+
-- $Id: indexint_100.sql,v 1.5 2007-08-29 05:44:28 touraine37 Exp $

truncate table pclassement;
truncate table indexint;

-- 
-- Contenu de la table `indexint`
-- 

INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (1, '000', 'Information Communication', ' 000 information communication ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (2, '010', 'Bibliographies Catalogues', ' 010 bibliographies catalogues ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (3, '020', 'Bibliothèques - et lecture, documentation', ' 020 bibliotheques lecture documentation ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (4, '030', 'Encyclopédies générales', ' 030 encyclopedies generales ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (5, '040', 'X', ' 040 x ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (6, '050', 'Périodiques généraux - annuaires', ' 050 periodiques generaux annuaires ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (7, '060', 'Organisations générales - congrès', ' 060 organisations generales congres ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (8, '070', 'Presse Edition', ' 070 presse edition ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (9, '080', 'Recueils - mélanges, discours', ' 080 recueils melanges discours ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (10, '090', 'Manuscrits Livres rares', ' 090 manuscrits livres rares ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (11, '100', 'Philosophie', ' 100 philosophie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (12, '110', 'Métaphysique', ' 110 metaphysique ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (13, '120', 'Connaissance', ' 120 connaissance ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (14, '130', 'Parapsychologie - astrologie, graphologie', ' 130 parapsychologie astrologie graphologie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (15, '140', 'Systèmes philosophiques', ' 140 systemes philosophiques ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (16, '150', 'Psychologie', ' 150 psychologie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (17, '160', 'Logique', ' 160 logique ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (18, '170', 'Morale - ethique', ' 170 morale ethique ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (19, '180', 'Philosophes anciens - et orientaux', ' 180 philosophes anciens orientaux ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (20, '190', 'Philosophes modernes - (XVIe S. à nos jours)', ' 190 philosophes modernes xvie s nos jours ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (21, '200', 'Religion', ' 200 religion ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (22, '210', 'Religion naturelle', ' 210 religion naturelle ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (23, '220', 'Bible Evangiles', ' 220 bible evangiles ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (24, '230', 'Théologie doctrinale chrétienne - (dogme)', ' 230 theologie doctrinale chretienne dogme ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (25, '240', 'Théologie spirituelle - vie religieuse', ' 240 theologie spirituelle vie religieuse ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (26, '250', 'Théologie pastorale', ' 250 theologie pastorale ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (27, '260', 'L''Eglise chrétienne et la société', ' 260 eglise chretienne societe ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (28, '270', 'Histoire de l''Eglise chrétienne', ' 270 histoire eglise chretienne ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (29, '280', 'Autres confessions chrétiennes', ' 280 autres confessions chretiennes ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (30, '290', 'Autres religions et mythologies', ' 290 autres religions mythologies ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (31, '300', 'Sciences sociales', ' 300 sciences sociales ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (32, '310', 'Statistiques', ' 310 statistiques ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (33, '320', 'Politique - l''Etat', ' 320 politique etat ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (34, '330', 'Economie - finances, production, consommation', ' 330 economie finances production consommation ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (35, '340', 'Droit - justice', ' 340 droit justice ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (36, '350', 'Administration de l''Etat', ' 350 administration etat ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (37, '360', 'Aide Assistance Secours', ' 360 aide assistance secours ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (38, '370', 'Education - enseignement', ' 370 education enseignement ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (39, '380', 'Commerce Transports Communication', ' 380 commerce transports communication ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (40, '390', 'Costumes et folklore', ' 390 costumes folklore ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (41, '400', 'Langage', ' 400 langage ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (42, '410', 'Linguistique', ' 410 linguistique ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (43, '420', 'Langue anglaise', ' 420 langue anglaise ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (44, '430', 'Langue allemande', ' 430 langue allemande ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (45, '440', 'Langue française - (dictionnaires, grammaire)', ' 440 langue francaise dictionnaires grammaire ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (46, '450', 'Langue italienne', ' 450 langue italienne ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (47, '460', 'Langue espagnole et portugaise', ' 460 langue espagnole portugaise ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (48, '470', 'Langue latine', ' 470 langue latine ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (49, '480', 'Langue grecque', ' 480 langue grecque ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (50, '490', 'Autres langues - russe, arabe, ?', ' 490 autres langues russe arabe ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (51, '500', 'Sciences', ' 500 sciences ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (52, '510', 'Mathématiques', ' 510 mathematiques ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (53, '520', 'Astronomie', ' 520 astronomie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (54, '530', 'Physique', ' 530 physique ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (55, '540', 'Chimie - minéralogie', ' 540 chimie mineralogie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (56, '550', 'Sciences de la Terre - géologie, météorologie', ' 550 sciences terre geologie meteorologie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (57, '560', 'Paléontologie - (les fossiles)', ' 560 paleontologie fossiles ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (58, '570', 'Sciences de la vie - biologie, génétique', ' 570 sciences vie biologie genetique ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (59, '580', 'Botanique - (les plantes)', ' 580 botanique plantes ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (60, '590', 'Zoologie - (les animaux)', ' 590 zoologie animaux ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (61, '600', 'Techniques', ' 600 techniques ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (62, '610', 'Médecine - hygiène, santé', ' 610 medecine hygiene sante ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (63, '620', 'Techniques industrielles - mécanique, électricité, radio, énergie?', ' 620 techniques industrielles mecanique electricite radio energie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (64, '630', 'Agriculture - forêt, élevage, pêche', ' 630 agriculture foret elevage peche ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (65, '640', 'Arts ménagers - cuisine, coutûre, soins de beauté', ' 640 arts menagers cuisine couture soins beaute ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (66, '650', 'Entreprise - travail de bureaux, vente, publicité', ' 650 entreprise travail bureaux vente publicite ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (67, '660', 'Industries chimiques et alimentaires', ' 660 industries chimiques alimentaires ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (68, '670', 'Fabrications industrielles - métallurgie, bois, textile', ' 670 fabrications industrielles metallurgie bois textile ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (69, '680', 'Articles manufacturés', ' 680 articles manufactures ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (70, '690', 'Bâtiment - construction', ' 690 batiment construction ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (71, '700', 'Arts et loisirs', ' 700 arts loisirs ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (72, '710', 'Urbanisme - art du paysage', ' 710 urbanisme art paysage ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (73, '720', 'Architecture', ' 720 architecture ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (74, '730', 'Sculpture', ' 730 sculpture ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (75, '740', 'Dessin - arts décoratifs', ' 740 dessin arts decoratifs ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (76, '750', 'Peinture', ' 750 peinture ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (77, '760', 'Arts graphiques - graphisme', ' 760 arts graphiques graphisme ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (78, '770', 'Photographie', ' 770 photographie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (79, '780', 'Musique', ' 780 musique ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (80, '790', 'Loisirs - spectacles, jeux, sports', ' 790 loisirs spectacles jeux sports ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (81, '800', 'Littérature', ' 800 litterature ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (82, '810', 'Littérature américaine', ' 810 litterature americaine ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (83, '820', 'Littérature anglaise', ' 820 litterature anglaise ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (84, '830', 'Littérature allemande', ' 830 litterature allemande ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (85, '840', 'Littérature française', ' 840 litterature francaise ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (86, '850', 'Littérature italienne', ' 850 litterature italienne ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (87, '860', 'Littérature espagnole et portugaise', ' 860 litterature espagnole portugaise ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (88, '870', 'Littérature latine', ' 870 litterature latine ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (89, '880', 'Littérature grecque', ' 880 litterature grecque ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (90, '890', 'Autres littératures', ' 890 autres litteratures ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (91, '900', 'Histoire géographie', ' 900 histoire geographie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (92, '910', 'Géographie - voyages', ' 910 geographie voyages ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (93, '920', 'Biographies - vie d''un personnage, généalogie', ' 920 biographies vie personnage genealogie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (94, '930', 'Histoire ancienne', ' 930 histoire ancienne ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (95, '940', 'Histoire de l''Europe', ' 940 histoire europe ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (96, '950', 'Histoire de l''Asie', ' 950 histoire asie ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (97, '960', 'Histoire de l''Afrique', ' 960 histoire afrique ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (98, '970', 'Histoire de l''Amérique du Nord', ' 970 histoire amerique nord ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (99, '980', 'Histoire de l''Amérique du Sud', ' 980 histoire amerique sud ', 1);
INSERT INTO indexint (indexint_id, indexint_name, indexint_comment, index_indexint, num_pclass) VALUES (100, '990', 'Histoire de l''Océanie', ' 990 histoire oceanie ', 1);

-- 
-- Contenu de la table `pclassement`
-- 

INSERT INTO pclassement (id_pclass, name_pclass, typedoc) VALUES (1, 'Dewey 100', 'abcdefgijklmr');
        