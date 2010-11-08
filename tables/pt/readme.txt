------------------------------------------------------------------------------------------------------------------

Descrição dos arquivos
bibli.sql : estrutura da base de dados, sem dados

minimum.sql : utilizador admin/admin, parâmetros da aplicação

feed_essential.sql : o que se necessita para iniciar a aplicação em modo início rápido :
	Dados da aplicação preenchidos, modificáveis.
	Um conjunto de cópia de segurança pronto a empregar
	Um conjunto de parâmetros de Z3950.
	
data_test.sql : uma pequena selecção de dados de registos, utilizadores, para poder experimentar o PMB.
	Registos, utilizadores, empréstimos, exemplares, publicações periódicas
	Baseia-se nos dados da aplicação incluídos também em feed_essential.sql
	Deve-se carregar o thesaurus UNESCO_FR unesco_fr.sql
	
Tesauros : propõem-se 3 thesaurus :
	unesco_fr.sql : thesaurus hierárquico da UNESCO, bastante importante e bem construído.
	grumeau.sql : um poco mais pequeno, mais simples más também bem construído.
	environnement : um thesaurus útil para un fundo documental relacionado com o meio ambiente.
	
Indexations internes : propõem-se 4 indexações :
	indexint_100.sql : 100 casos do saber ou margarida de cores, indexação decimal 
	style Dewey simplificada para educação
	indexint_chambery.sql : indexação de estilo Dewey da BM de Chambéry, bem concebido mas pouco adaptada
	a bibliotecas pequenas
	indexint_dewey.sql : indexação estilo Dewey
	indexint_small_en.sql : indexação estilo Dewey reduzida e em inglês
	

************************************************************************************************
________________________________________________________________________________________________
Atenção, se está a fazer uma actualização de uma versão anterior :
------------------------------------------------------------------------------------------------
*********** A realizar a cada instalação ou actualização da aplicação  ****************
Quando instala uma nova versão
sobre uma versão anterior deve, obrigatoriamente,
antes de copiar os arquivos novos contidos no arquivo zip
no servidor web :

comprovar que os parâmetros incluídos em :
./includes/db_param.inc.php
./opac_css/includes/opac_db_param.inc.php

correspondem à sua configuração (faça uma cópia antes !)

Adicionalmente :
Deve fazer uma actualização da base de dados.
Não se perderá nada.

Ligue-se da forma habitual ao PMB, o estílo gráfico pode ser diferente, 
ausente (visualização sem cores nem imagens)

Vá a Administração > Ferramentas > act base de dados para actualizar a
base de dados.

Uma série de mensagens irão indicando as actualizações sucessivas, 
para continuar a actualização clique no link da parte inferior da página 
até que apareça a mensagem 'A sua base de dados está actualizada com a versão...'

Pode editar a sua conta de utilizador para modificar as suas preferências, mudando
o estilo de visualização.

Faça chegar as suas dúvidas, problemas ou sugestões por correio
electrónico : pmb@sigb.net

Por outro lado, gostaremos de contar consigo como um dos nossos
utilizadores, e se nos facilitar alguns dados como o número de utilizadores, de obras
de CD... junto com os dados do seu estabelecimento (ou a título particular) 
nos ajudará a conhecê-lo melhor.

Encontrará mais informação no directório ./doc ou 
na nossa página web http://www.sigb.net

A equipa de desenvolvimento.


///////////////////// Lista de tabelas incluídas nos arquivos /////////////////

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            minimum.sql
# Conteúdo da tabela `parametres`
# Conteúdo da tabela `users`
	utilizador admin/admin

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            feed_essential.sql
# Conteúdo da tabela `docs_codestat`
# Conteúdo da tabela `docs_location`
# Conteúdo da tabela `docs_section`
# Conteúdo da tabela `docs_statut`
# Conteúdo da tabela `docs_type`

# Conteúdo da tabela `empr_categ`
# Conteúdo da tabela `empr_codestat`

# Conteúdo da tabela `lenders`

# Conteúdo da tabela `sauv_lieux`
# Conteúdo da tabela `sauv_sauvegardes`
# Conteúdo da tabela `sauv_tables`

# Conteúdo da tabela `z_attr`
# Conteúdo da tabela `z_bib`


\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            data_test.sql
# Conteúdo da tabela `users`
	Utilizadores suplementares
	
# Conteúdo da tabela `empr`
# Conteúdo da tabela `empr_custom`
# Conteúdo da tabela `empr_custom_lists`
# Conteúdo da tabela `empr_custom_values`
# Conteúdo da tabela `empr_groupe`
# Conteúdo da tabela `groupe`

# Conteúdo da tabela `notices`
# Conteúdo da tabela `notices_custom`
# Conteúdo da tabela `notices_custom_lists`
# Conteúdo da tabela `notices_custom_values`
# Conteúdo da tabela `origine_notice`
# Conteúdo da tabela `exemplaires`
# Conteúdo da tabela `expl_custom`
# Conteúdo da tabela `expl_custom_lists`
# Conteúdo da tabela `expl_custom_values`
# Conteúdo da tabela `explnum`

# Conteúdo da tabela `authors`
# Conteúdo da tabela `publishers`
# Conteúdo da tabela `collections`
# Conteúdo da tabela `sub_collections`
# Conteúdo da tabela `responsability`
# Conteúdo da tabela `series`

# Conteúdo da tabela `notices_categories`

# Conteúdo da tabela `bulletins`
# Conteúdo da tabela `analysis`

# Conteúdo da tabela `notices_categories`

# Conteúdo da tabela `caddie`
# Conteúdo da tabela `caddie_content`
# Conteúdo da tabela `caddie_procs`

# Conteúdo da tabela `etagere`
# Conteúdo da tabela `etagere_caddie`


# Conteúdo da tabela `resa`
# Conteúdo da tabela `resa_ranger`

# Conteúdo da tabela `quotas`
# Conteúdo da tabela `pret`

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            agneaux.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            unesco_fr.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            environnement.sql
# Conteúdo da tabela `categories`
# Conteúdo da tabela `categ_assoc`

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_chambery.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_100.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_dewey.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_small_en.sql
# Conteúdo da tabela `indexint`
