<?php
/**
 * \addtogroup server Serveur passerelle
 * \brief Implémentation d'une passerelle serveur de socket vers un serveur http
 * 
 * L'adresse ::http_url_login est utilisée pour s'autentifier par cookie,
 * l'adresse ::http_url est utilisée pour appeller le script qui traite le message reçu par la socket.
 * Le message est transmis en méthode POST sous la forme message=message_recu.\n
 * Exemple d'utilisation :
 * \verbatim
$s=new socket2http();
$s->http_url="https://gestion.bibli.fr/genes/sip2.php";
$s->http_url_login="https://gestion.bibli.fr/genes/main.php";
$s->http_port=80;
$s->http_use_cookie=true;
$s->http_cookie_login=array("user"=>"admin","password"=>"admin","database"=>"bibli");
$s->http_renew_pattern="/class\=\'erreur\'/";
$s->http_use_ssl=true;
$s->http_ssl_crt="/home/ftetart/.ssl/certificat.crt";
$s->http_ssl_key="/home/ftetart/.ssl/certificat.key";
$s->socket_bind_address="192.168.1.65";
$ret=$s->start_bind();
if (!$ret) print $s->error_message."\n";
	\endverbatim
 */

/**
 * \brief Passerelle socket vers un serveur http
 * 
 * La classe implément un serveur de socket qui transfère à un serveur http les données reçues via cette socket.\n
 * La réponse du serveur http est retransmise intégralement au client de la socket.
 * @author ftetart
 * \date Février 2008
 * \ingroup server
 */
class socket2http {
	var $http_url="http://localhost/pmb";	/*!< \brief Adresse du serveur http a contacter */
	var $http_url_login="http://localhost/pmb/main.php";	/*!< \brief URL du serveur http a contacter pour l'autentification par cookies */
	var $http_port="";						/*!< \brief Port http du serveur */
	var $http_use_cookie=false;				/*!< \brief Gestion d'une session par cookie sur le serveur : false=non, true=oui */
	/**
	 * \brief Variables de login par cookie
	 * 
	 * Variables a passer par POST au départ pour initier la session par cookie. C'est un tableau "nom de variable"=>"valeur"\n
	 * Exemple : array("user"=>"ftetart","password"=>"xxxxx");\n
	 * passe au serveur http les variables de connexion "user" et "password"
	 */
	var $http_cookie_login=array();
	var $http_cookie_renew_pattern="";		/*!< \brief Expression régulière qui permet de détecter qu'une session par cookie a expiré */
	var $http_use_ssl=false;				/*!< \brief Utiliser une connexion sécurisée par ssl : false=non, true=oui */
	var $http_ssl_key="";					/*!< \brief Clé privée pour la connexion ssl*/
	var $http_ssl_crt="";					/*!< \brief Clé publique pour l'autentification */
	/**
	 * \brief Cookies reçus après l'authentification par session
	 * \private
	 */
	var $http_cookies=array();
	/**
	 * \brief Corps de la réponse http 
	 * \private
	 */
	var $http_core="";
	/**
	 *  \brief Hearder de la réponse http
	 *  \private
	 */
	var $http_header="";
	/**
	 * \brief Lien curl courant
	 * \private
	 */
	var $curl_link;
	
	var $socket_max_connections=10;			/*!< \brief Nombre maximum de connexions autorisés au serveur de socket */
	var $socket_port=6001;					/*!< \brief Numéro de port a écouter */
	var $socket_bind_address="127.0.0.1"; 	/*!< \brief Adresse d'écoute */
	
	/**
	 * \brief Tableau des clients socket connectés
	 * \private
	 */
	var $socket_clients=array();
	/**
	 * \brief Socket serveur pour création d'une connexion
	 * \private
	 */
	var $socket_server="";
	
	var $error=false;						/*!< \brief Si true, il y a eu une erreur lors d'un traitement */
	var $error_message="";					/*!< \brief Si ::error = true, message d'explication de l'erreur */
	
	/**
	 * \brief Constructeur
	 * 
	 * Ne prend aucun argument, tout est exécuté par la méthode start_bind .
	 */
    function socket2http() {
    }
    
    /**
     * \brief Gestion de l'erreur socket
     * 
     * Positionne le flag error à vrai et affecte error_message avec la dernière erreur de socket
     * @return vide rien
     * \private
     */
    function make_socket_error() {
    	$this->error=true;
    	$this->error_message="Erreur ouverture du serveur de socket : ".socket_strerror(socket_last_error());
    }
    
    /**
     * \brief Initialisation du serveur de socket
     * 
     * Création de la socket serveur dans $this->socket_server. La fonction ne prend aucun argument mais utilise des variables internes
     * @param string ::socket_bind_address Adresse d'écoute de la scoket
     * @param integer ::socket_port Port d'écoute
     * @return boolean true : la création de la socket a fonctionné, false : la création a échoué (sockets2http::error est à true)
     * \note la variable interne ::socket_server contient la ressource de la socket serveur
     * \private
     */
    function init_socket() {
    	// Création d'une "TCP Stream socket"
		$this->socket_server = socket_create(AF_INET, SOCK_STREAM, 0);
		if (!$this->socket_server) {
			$this->make_socket_error(); 
			return false;
		} else {
			// Ecoute de la socket serveur sur l'adresse/port
			if (!socket_bind($this->socket_server, $this->socket_bind_address, $this->socket_port)) {
				$this->make_socket_error();
				return false; 
			} else {
				// Start listening for connections
				if (!socket_listen($this->socket_server)) {
					$this->make_socket_error();
					return false;
				}
			}
		}
		return true;
    }

	/**
	 * \brief Enregistrement des données de la réponse http dans ::http_core
	 * @param ressource $curl_ressource ressource curl qui gère la requête
	 * @param string $data contenu du corps de la réponse
	 * @return integer longueur des données reçues
	 * \private
	 * \ingroup http	 
	 */
	function get_http_core($curl_ressource,$data) {
		$this->http_core.=$data;
		return strlen($data);
	}
	
	/**
	 * \brief Enregistrement des entêtes de la réponse http dans ::http_header
	 * @param ressource $curl_ressource ressource curl qui gère la requête
	 * @param string $data contenu partiel des entêtes
	 * @return integer longueur des données reçues
	 * \note Si l'entête passée dans $data est un cookie (Set-Cookie: ...), le cookie est stocké dans le tableau ::http_cookies
	 * \private
	 */
	function get_http_header($curl_ressource,$data) {
		if (strpos($data,"Set-Cookie:")!==false) {
			$this->http_cookies[]=trim(substr($data,12));
		}
		$this->http_header.=$data;
		return strlen($data);
	}

	/**
	 * \brief Intialise et prépare les options curl pour la connexion http
	 * @param array $http_params tableau des options curl a initialiser sous la forme : "NOM_OPTION"=>"valeur".\n
	 * \note Exemple de tableau d'option array("URL"=>"http://localhost/pmb/main.php") sera traduit en 
	 * curl_setopt($this->curl_link,CURLOPT_URL,"http://localhost/pmb/main.php")
	 * \private
	 */
	function prepare_http($http_params) {
		//Initialisation de la connexion
    	$this->curl_link = curl_init();
		curl_setopt($this->curl_link, CURLOPT_WRITEFUNCTION,array(&$this,"get_http_core"));
		curl_setopt($this->curl_link, CURLOPT_HEADERFUNCTION,array(&$this,"get_http_header"));	
		curl_setopt($this->curl_link, CURLOPT_HEADER, 0);
		curl_setopt($this->curl_link, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curl_link, CURLOPT_TIMEOUT,30);
		//Gestion du SSL
		if ($this->http_use_ssl) {
			curl_setopt($this->curl_link,CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($this->curl_link,CURLOPT_SSLCERT,$this->http_ssl_crt);
			curl_setopt($this->curl_link,CURLOPT_SSLKEY,$this->http_ssl_key);
		}
		if (count($this->http_cookies)) {
			curl_setopt($this->curl_link,CURLOPT_COOKIE,implode(";",$this->http_cookies));
		}
		foreach ($http_params as $param=>$value) {
			curl_setopt($this->curl_link,constant("CURLOPT_".$param),$value);
		}
	}

	/**
	 * \brief Fermeture de la connexion curl
	 * \private
	 */
	function close_http() {
		curl_close($this->curl_link);
	}

	/**
	 * \brief fait une requête http en vérifiant la session par cookie
	 * 
	 * Fait une requête http en postant le paramètre message. La fonction vérifie que la session cookie est toujours valide
	 * et se reconnecte si nécéssaire.
	 * @param string $message message a poster au serveur http (le serveur recevra message=$message)
	 * @return boolean true : La requête a réussie (la réponse est dans ::http_core), false : la requête a échoué
	 * \private
	 */
	function make_logged_http_request($message,$id_client) {
		global $protocol_prolonge;
		//Initialisation de la requête
		$http_params=array(
			"URL"=>$this->http_url,
			"POST"=>true,
			"POSTFIELDS"=>"message=".rawurlencode($message)."&id=".rawurlencode($id_client).$protocol_prolonge
		);
		$this->prepare_http($http_params);
		if ($this->make_http_request()) {
			//Requete OK, on cherche un statut "erreur de session" si il y a une session cookie
			if ($this->http_use_cookie) {
				//Recherche du pattern erreur
				$p=preg_match($this->http_renew_pattern,$this->http_core);
				//Si session expirée, reconnexion
				if ($p) {
					//Reconnexion
					if ($this->http_do_login()) {
						$this->prepare_http($http_params);
						if ($this->make_http_request()) {
							//On s'est reconnecté, OK !
							return true;
						} else {
							//Erreur !
							$this->error=true;
							$this->error_message="Impossible d'obtenir une réponse à l'URL : ".$this->http_url;
							return false;
						}	
					} else {
						//Erreur !
						$this->error=true;
						$this->error_message="Impossible de recréer une session valide";
						return false;
					}
				}
			}
		} else {
			//Requete pas OK
			$this->error=true;
			$this->error_message="Impossible d'obtenir une réponse à l'URL : ".$this->http_url;
			return false;
		}
		return true;
	}
	
	/**
	 * \brief Exécute la requête http préparée par ::http_prepare()
	 * @return boolean true : la requête a réussi, false : la requête a échoué
	 * \private
	 */
	function make_http_request() {
		$this->http_headers="";
		$this->http_core="";
		$cexec=curl_exec($this->curl_link);
		$this->close_http();
		return $cexec;
	}
    
    /**
     * \brief Login pour une session cookie
     * 
     * Autentification sur le serveur http et ouverture d'une session par cookie si nécessaire
     * @param array ::http_cookie_login paramètres a poster pour la connexion, tableau sous la forme :\n
     * "nom_parametre"=>"valeur"
     * @param string ::http_url_login adresse a appeler pour le login session
     * 
     * \note L'adresse de login est appellée avec les paramètres de connexion (dans ::http_cookie_login) en POST.
     * La connexion est considérée comme réussie si on reçoit au moins un cookie en réponse dans l'entête.\n
     * Les cookies sont stockés dans le tableau ::http_cookies sous la forme "nom_cookie"=>"valeur"
     * 
     * @return boolean true : la connexion/ouverture de la session a réussi, true : la connexion a échoué
     * \private
     */
    function http_do_login() {
    	//Y-a-t-il une autentification par cookies ?
		if ($this->http_use_cookie) {
			//Préparation de la requête POST avec les éléments de login
			$post_vars=array();
			foreach($this->http_cookie_login as $key=>$val) {
				$post_vars[]=$key."=".rawurlencode($val);
			}
			
			//Initialisation de la requête http
			$http_params=array(
				"POST"=>true,
				"URL"=>$this->http_url_login,
				"POSTFIELDS"=>implode("&",$post_vars)
			);
			$this->prepare_http($http_params);
			
			//Remise à zero des cookies
			$this->http_cookies=array();
			
			//Autentification
			if ($this->make_http_request()) {
				//A-t-on reçu des cookies ?
				if (count($this->http_cookies)) {
					return true;
				} else {
					//L'autentification a échoué
					$this->error=true;
					$this->error_message="La session http n'a pû être créée";
					return false;
				}
			} else {
				//La requête POST n'a pas marché
				$this->error=true;
				$this->error_message="Impossible de s'autentifier sur le serveur http !";
				return false;
			}
		} else return true;
    }
    
    /**
     * \brief Test de la connexion http
     * 
     * Fait une requête http a l'adresse ::http_url pour vérifier que le serveur http répond
     * @return boolean true : le serveur répond bien, false : le serveur ne répond pas
     * \private
     */
    function test_http_connection() {
    	//Initialisation de la connexion
    	$http_params=array(
    		"URL"=>$this->http_url
    	);
    	$this->prepare_http($http_params);
	
		//Test de la liaison
		if ($this->make_http_request()) {
			return true;
		} else {
			$this->error=true;
			$this->error_message="Impossible d'obtenir une réponse à l'URL : ".$this->http_url;
			return false;
		}
    }
    
    /**
     * \brief Boucle infinie du serveur de socket
     * 
     * Accepte les connexions jusqu'à concurrence de ::socket_max_connections par la socket serveur 
     * et gère les transferts des données reçues sur les différentes sockets vers le serveur http.
     * \private
     */
    function make_loop() {
    	
		// $exec_cmd est la commande permettant de lancer le serveur de la borne de prêt, une fois ce service actif. 
		// Affecté dans init_automate.php
		global $exec_cmd,$socket_write_type;
		if ($exec_cmd)	exec($exec_cmd);
		//Boucle continue d'attente des évênements
		print "Entrée dans la boucle d'écoute du serveur de socket\n";
		while (true) {
		    //Affectation de la socket principale à l'élément 0 du tableau des sockets actives
		    $read[0] = $this->socket_server;
		    //On effecte au reste du tableau read les sockets en cours ouvertes
		    for ($i = 0; $i < $this->socket_max_connections; $i++) {
		        if ($this->socket_clients[$i]['sock']  != null)
		            $read[$i + 1] = $this->socket_clients[$i]['sock'] ;
		    }
		    //Préparation d'un appel bloquant à socket_select
		    $write=NULL;
		    $except=NULL;
		    $ready = socket_select($read,$write,$except,null);
		    //Si une nouvelle connextion est demandée, on l'enregistre dans le tableau des connexions courantes
		    if (in_array($this->socket_server, $read)) {
		    	//Recherche d'une case vide...
		        for ($i = 0; $i < $this->socket_max_connections; $i++) {
		            if ($this->socket_clients[$i]['sock'] == null) {
		                $this->socket_clients[$i]['sock'] = socket_accept($this->socket_server);
		                $this->socket_clients[$i]['id'] = microtime();
		                //Dit "Hello !"
		                //socket_write($this->socket_clients[$i]['sock'],"Connecte a ".$this->http_url_login."\r\n");
		                break;
		            }
		            elseif ($i == $this->socket_max_connections - 1)
		                print ("Le nombre maximum de client est atteint, connexion refusée !");
		        }
		        //Si l'appel à socket_select a échoué, on sort de la boucle
		        if (--$ready <= 0)
		            continue;
		    } //Fin de la gestion d'une nouvelle connexion
		    
		    // Si un client tente d'écrire, on le gère ici
		    for ($i = 0; $i < $this->socket_max_connections; $i++) {
		    	// pour chaque client
		        if (in_array($this->socket_clients[$i]['sock'] , $read)) {
		        	//Lecture des données (4096 car au max)
		            $input = socket_read($this->socket_clients[$i]['sock'] , 4096,PHP_BINARY_READ);
		            if ($input == null) {
		                //Si données = null, la socket client est déconnectée, on déconnecte
		                socket_close($this->socket_clients[$i]['sock']);
		                unset($this->socket_clients[$i]);
		            }
		            //On nettoie les caractères blancs
		            $n = trim($input);
		            if ($n == 'exit') {
		                //Si on reçoit exit, on déconnecte le client
		                socket_close($this->socket_clients[$i]['sock']);
		                unset($this->socket_clients[$i]);
		            } elseif ($n) {
		            	//print "Client ".$i." : ".strlen($input)." - ".(strlen($input)>1?$input:dechex(ord($input)))."\n";
		            	//Envoi du message
		            	if ($this->make_logged_http_request($input,$this->socket_clients[$i]['id'])) {
		            		print "Client ".$this->socket_clients[$i]['id']." / Received : ".$input;
		            		//print "Response ".$i." : ".$this->http_core."\n";
		            		//Ecriture de la réponse
		            		print "Client ".$this->socket_clients[$i]['id']." / Response : ".$this->http_core."\n";
		            		if($socket_write_type)	socket_write($this->socket_clients[$i]['sock'],ltrim($this->http_core)."");
		            		else socket_write($this->socket_clients[$i]['sock'],$this->http_core."\r\n");
		            	} else {
		            		//Si on a pas réussi a avoir une réponse du serveur http, on déconnecte
		            		socket_close($this->socket_clients[$i]['sock']);
		                	unset($this->socket_clients[$i]);
		            	}
		            }
		        }
		    }
		} //Fin de la boucle
		//Fermeture de la socket serveur
		socket_close($this->socket_server);
    }
    
    /**
     * \brief Lancement du service
     * 
     * Teste la connexion http, réalise l'autentification pour la session cookie si nécéssaire,
     * lance le serveur de socket et gère les évènements socket
     * @return boolean false : Il y a eu une erreur lors du lancement du service le flag sockets2http::error est à true, sinon ne retourne jamais !
     */
    function start_bind() {
    	//Tests de connexion au serveur http
    	if (!$this->test_http_connection()) return false; else
    	//Si c'est ok, test de connexion à la session par cookie
    	if (!$this->http_do_login()) return false; else {
    		print "Serveur HTTP ".$this->http_url_login." contacte\n";
    		//Si c'est OK, on initialise le serveur de socket
    		if (!$this->init_socket()) return false; else {
    			$this->make_loop();
    		}
    	} 	
    }
}

$s=new socket2http();

// Fichier de paramétrage
require_once("init_automate.php");

$ret=$s->start_bind();
if (!$ret) print $s->error_message."\n";
?>