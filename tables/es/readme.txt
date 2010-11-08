------------------------------------------------------------------------------------------------------------------

Descripción de los archivos
bibli.sql : estructura de la base de datos sola, sin datos

minimum.sql : usuario admin/admin, parámetros de la aplicación

feed_essential.sql : lo que se necesita para inciiar la aplicación en modo inicio rápido :
	Datos de la aplicación rellenados, modificables.
	Un juego de copia de seguridad listo a emplear
	Un juego de parámetros de Z3950.
	
data_test.sql : una pequeña selección de datos de registros, usuarios, para poder probar PMB.
	Registros, usuarios, préstamos, ejemplares, publicaciones periódicas
	Se basa en los datos de la aplicación incluídos también en feed_essential.sql
	Debe cargarse el tesauro UNESCO_FR unesco_fr.sql
	
Tesauros : se proponen 3 tesauros :
	unesco_fr.sql : tesauro jerárquico de la UNESCO, bastante importante y bien hecho.
	grumeau.sql : un poco más pequeño, más sencillo pero bien construído también.
	environnement : un tesauro útil para un fondo documental relacionado con el Medio ambiente.
	
Indexations internes : se proponen 4 indexaciones :
	indexint_100.sql : 100 casos del saber o margarita de colores, indexación decimal 
	style Dewey simplificada para educación
	indexint_chambery.sql : indexación de estilo Dewey de la BM de Chambéry, bien concebido pero poco adaptada
	a bibliotecas pequeñas
	indexint_dewey.sql : indexación estilo Dewey
	indexint_small_en.sql : indexación estilo Dewey reducida y en inglés
	

************************************************************************************************
________________________________________________________________________________________________
Atención, si estás haciendo una actualización de una versión anterior :
------------------------------------------------------------------------------------------------
*********** A realizar con cada instalación o actualización de la aplicación  ****************
Cuando instalas una nueva versión
sobre una versión anterior debes, obligatoriamente,
antes de copiar los arhivos nuevos contenidos en el archivo zip
al servidor web :

comprobar que los parámetros incluidos en :
./includes/db_param.inc.php
./opac_css/includes/opac_db_param.inc.php

corresponden a tu configuración (haz una copia antes !)

Además :
Debes hacer una actualización de la base de datos.
No se perderá nada.

Conéctate de forma habitual a PMB, el estilo gráfico puede ser diferente, 
ausente (visualización sin colores ni imágines)

Ve a Administración > Herramientas > act base de datos para actualizar la
base de datos.

Una serie de mensajes te irán indicando las actualizaciones sucesivas, 
para continuar la actualización haz clic en el enlace de la parte inferior de la página 
hasta que aparezca el mensaje 'Tu base de datos está al día con la versión...'

Puedes editar tu cuenta de usuario para modificar tus preferencias, cambiando
el estilo de visualización.

No dudes en hacernos llegar tus dudas, problemas o sugerencias por correo
electrónico : pmb@sigb.net

Por otro lado, estaremos encantados de contar contigo como uno de nuestros
usuarios, y si nos facilitaras algunos datos como número de usuarios, de obras
de CD... junto con los datos de tu establecimiento (o a título particular) 
nos ayudarás a conocerte mejor.

Encontrarás más información en el directorio ./doc o bien 
en nuestra página web http://www.sigb.net

El equipo de desarrolladores.


///////////////////// Lista de tablas incluidas en los archivos /////////////////

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            minimum.sql
# Contenido de la tabla `parametres`
# Contenido de la tabla `users`
	usuario admin/admin

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            feed_essential.sql
# Contenido de la tabla `docs_codestat`
# Contenido de la tabla `docs_location`
# Contenido de la tabla `docs_section`
# Contenido de la tabla `docs_statut`
# Contenido de la tabla `docs_type`

# Contenido de la tabla `empr_categ`
# Contenido de la tabla `empr_codestat`

# Contenido de la tabla `lenders`

# Contenido de la tabla `sauv_lieux`
# Contenido de la tabla `sauv_sauvegardes`
# Contenido de la tabla `sauv_tables`

# Contenido de la tabla `z_attr`
# Contenido de la tabla `z_bib`


\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            data_test.sql
# Contenido de la tabla `users`
	Usuarios suplementarios
	
# Contenido de la tabla `empr`
# Contenido de la tabla `empr_custom`
# Contenido de la tabla `empr_custom_lists`
# Contenido de la tabla `empr_custom_values`
# Contenido de la tabla `empr_groupe`
# Contenido de la tabla `groupe`

# Contenido de la tabla `notices`
# Contenido de la tabla `notices_custom`
# Contenido de la tabla `notices_custom_lists`
# Contenido de la tabla `notices_custom_values`
# Contenido de la tabla `origine_notice`
# Contenido de la tabla `exemplaires`
# Contenido de la tabla `expl_custom`
# Contenido de la tabla `expl_custom_lists`
# Contenido de la tabla `expl_custom_values`
# Contenido de la tabla `explnum`

# Contenido de la tabla `authors`
# Contenido de la tabla `publishers`
# Contenido de la tabla `collections`
# Contenido de la tabla `sub_collections`
# Contenido de la tabla `responsability`
# Contenido de la tabla `series`

# Contenido de la tabla `notices_categories`

# Contenido de la tabla `bulletins`
# Contenido de la tabla `analysis`

# Contenido de la tabla `notices_categories`

# Contenido de la tabla `caddie`
# Contenido de la tabla `caddie_content`
# Contenido de la tabla `caddie_procs`

# Contenido de la tabla `etagere`
# Contenido de la tabla `etagere_caddie`


# Contenido de la tabla `resa`
# Contenido de la tabla `resa_ranger`

# Contenido de la tabla `quotas`
# Contenido de la tabla `pret`

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            agneaux.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            unesco_fr.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            environnement.sql
# Contenido de la tabla `categories`
# Contenido de la tabla `categ_assoc`

\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_chambery.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_100.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_dewey.sql
\_/-\_/-\_/-\_/-\_/-\_/-\_/-\            indexint_small_en.sql
# Contenido de la tabla `indexint`
