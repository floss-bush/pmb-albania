<?php
/**
 * \brief Class pmb_remote permet de se connecter à un PMB client et d'effectuer des GET et POST tel un navigateur 
 * Voir l'exemple en fin de fichier pour s'initier à la classe
 * @author ngantier
 * \date mars 2008
 * \ingroup server
 */

class pmb_remote {
	private $http_url="http://localhost/pmb";	// Adresse du serveur http a contacter 
	private $http_url_login="http://localhost/pmb/main.php";	// URL du serveur http a contacter pour l'autentification par cookies
	private $http_port="";					// Port http du serveur 
	private $http_proxy='';					// pour utilisation d'un proxy 	
	private $http_use_cookie=true;			// Gestion d'une session par cookie sur le serveur : false=non, true=oui 
	private $http_cookie_login=array();		// memo cookie 
	private $http_cookie_renew_pattern="";	// Expression régulière qui permet de détecter qu'une session par cookie a expiré 
	private $http_use_ssl=false;				// Utiliser une connexion sécurisée par ssl : false=non, true=oui 
	private $http_ssl_key="";				// Clé privée pour la connexion ssl
	private $http_ssl_crt="";				// Clé publique pour l'autentification 
	private $http_renew_pattern="/login-box/"; // detection d'une erreur de connection de PMB
	private $http_cookies=array();
	private $http_core="";		// Corps de la réponse http 
	private $http_header="";	// Hearder de la réponse http
	private $curl_link; 		// Lien curl courant
	private $error=false;		// Si true, il y a eu une erreur lors d'un traitement
	
	public $error_message="";	// Si ::error = true, message d'explication de l'erreur 
	public $response=""; // contenue de la réponse: la page html demandée
	/**
	 * Constructeur
	 * @param string $pmb_url url de PMB avec / à la fin. Exemple: "https://gestion.bibli.fr/compte_client/"
	 * @param string $port Port http du serveur 
	 * @param string $proxy pour utilisation éventuel d'un proxy 	
	 * @param string $user user de la connection
	 * @param string $password password de la connection
	 * @param string $database Base de donnée utilisée
	 * @param string $ssl_path path des clés numérique avec / à la fin. Exemple: "/home/xxxxxx/.ssl/"
	 */
    function pmb_remote($pmb_url,$port,$proxy,$user,$password,$database,$ssl_path="") {
		$this->http_url=$pmb_url;
		$this->http_url_login=$pmb_url."main.php";
		$this->http_port=$port;
		$this->http_proxy=$proxy;
		$this->http_cookie_login=array("user"=>"$user","password"=>"$password","database"=>"$database");
		if ($ssl_path) {
			$this->http_ssl_crt= $ssl_path."pmb.crt";
			$this->http_ssl_key= $ssl_path."pmb.key";	
			$this->http_use_ssl=true;		
		}
    }
    

	// fonction appeler par curl pour mémoriser la réponse
	function get_http_core($curl_ressource,$data) {
		$this->http_core.=$data;
		return strlen($data);
	}
	
	// fonction appeler par curl pour mémoriserles entêtes de la réponse http dans ::http_header
	function get_http_header($curl_ressource,$data) {
		if (strpos($data,"Set-Cookie:")!==false) {
			$this->http_cookies[]=trim(substr($data,12));
		}
		$this->http_header.=$data;
		return strlen($data);
	}

	// Intialise et prépare les options curl pour la connexion http
	function prepare_http($http_params) {
		//Initialisation de la connexion
    	$this->curl_link = curl_init();
		curl_setopt($this->curl_link, CURLOPT_WRITEFUNCTION,array(&$this,"get_http_core"));
		curl_setopt($this->curl_link, CURLOPT_HEADERFUNCTION,array(&$this,"get_http_header"));	
		curl_setopt($this->curl_link, CURLOPT_HEADER, 0);
		curl_setopt($this->curl_link, CURLOPT_TIMEOUT,300);
		curl_setopt($this->curl_link, CURLOPT_PROXY, $this->http_proxy);
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

	//brief Fermeture de la connexion curl
	function close_http() {
		curl_close($this->curl_link);
	}

	
	// fait une requête http par GET ou POST en vérifiant la session par cookie
	function make_logged_http_request($url,$post=0,$post_data="") {
		//Initialisation de la requête
		if($post) {
			$http_params=array(
				"URL"=>$this->http_url.$url,
				"POST"=>true,
				"HTTPGET"=>false,
				"POSTFIELDS"=>$post_data);
		} else {
			$http_params=array(
				"URL"=>$this->http_url.$url,
				"POST"=>false,
				"HTTPGET"=>true,
				"POSTFIELDS"=>"");			
		}
		$this->prepare_http($http_params);
		if ($this->make_http_request()) {
			//Requete OK, on cherche un statut "erreur de session" si il y a une session cookie
			if ($this->http_use_cookie) {
				//Recherche du pattern erreur
				$p=preg_match($this->http_renew_pattern,$this->http_core);
				// Si session expirée, reconnexion
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
							$this->error_message="Impossible d'obtenir une réponse à l'URL : ".$this->http_url.$url;
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
			$this->error_message="Impossible d'obtenir une réponse à l'URL : ".$this->http_url.$url;
			return false;
		}
		return true;
	}
	
	// Exécute la requête http préparée par ::http_prepare()
	function make_http_request() {
		$this->http_headers="";
		$this->http_core="";
		$cexec=curl_exec($this->curl_link);
		$this->close_http();
		return $cexec;
	}
    
    // Login pour une session cookie
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
 
    // Effectue l'ouverture de session de pmb
    function connection() {
 		if(!$this->http_do_login()) return false; 
    	return true;  
    } 
       
    // Effectue la déconnection
	function disconnection() {
		 $this->response='';
  		if(!$this->make_logged_http_request("logout.php",1,"")) {
  			return false;
  		}
  		$this->response=$this->http_core; 
  		return true;				
    }   
    
    // requête http GET
    function http_get($url) {
    	$this->response='';
  		if(!$this->make_logged_http_request($url)) {
  			return false;
  		}
  		$this->response=$this->http_core; 
  		return true;		
    } 
    
    // requête http POST   
    function http_post($url,$param) {
    	$this->response='';
    	$postparam="";
    	foreach($param as $key=>$val) {
    		if($postparam)$postparam.="&";
    		$postparam.=rawurlencode($key)."=".rawurlencode($val);
    	}
  		if(!$this->make_logged_http_request($url,1,$postparam)){
  			return false;
  		}
  		$this->response=$this->http_core; 	
  		return true;	
    }
}

/*
 * 
 * Exemple d'utilisation de la classe: remplacer !!client!! et !!compte_utilisateur!!, et tester...
 * 
 * 
// instancier la classe pmb_remote
$pmb=new pmb_remote("https://gestion.bibli.fr/!!client!!/",443,'',"admin","admin","calyon","/home/!!compte_utilisateur!!/.ssl/");

// connection à PMB
if (!$pmb->connection()) {
	print $pmb->error_message."\n";
	exit;
}

// Faire un post 
$param["categ"]="isbd";
$param["id"]="1709";
if (!$pmb->http_post("catalog.php",$param)) {
	print $pmb->error_message."\n";
	exit;
}
print $pmb->response."\n";

// Faire un get
if (!$pmb->http_get("catalog.php?categ=isbd&id=1709")) {
	print $pmb->error_message."\n";
	exit;
}
print $pmb->response."\n";
*/

?>