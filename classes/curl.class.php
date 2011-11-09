<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: curl.class.php,v 1.8.2.2 2011-06-14 06:07:36 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

/* Curl, CurlResponse
#
# Author Sean Huber - shuber@huberry.com
# Date May 2008
#
# A basic CURL wrapper for PHP
#
# See the README for documentation/examples or http://php.net/curl for more information about the libcurl extension for PHP
http://github.com/shuber/curl/tree/master/curl.php4
*/
	
class Curl {
	var $cookie_file;
	var $headers = array();
	var $options = array();
	var $referer = '';
	var $user_agent = '';
 	var $reponsecurl=array();
	# Protected
	var $error = '';
	var $handle;
	var $buffer="";
	
	# Variables qui empechent le dépassement mémoire
	var $limit=0;	
	var $body_overflow;
	var $timeout=0;
	
	function Curl() {
		$this->__construct();
	}
	
	function __construct() {
		// initialisation des libellés de réponse
		$this->reponsecurl['N/A'] = "Ikke HTTP";
		$this->reponsecurl[OK]    = "Valid hostname";
		$this->reponsecurl[FEJL]  = "Invalid hostname";
		$this->reponsecurl[Død]   = "No response";
		$this->reponsecurl[100]   = "Continue";
		$this->reponsecurl[101]   = "Switching Protocols";
		$this->reponsecurl[200]   = "OK";
		$this->reponsecurl[201]   = "Created";
		$this->reponsecurl[202]   = "Accepted";
		$this->reponsecurl[203]   = "Non-Authoritative Information";
		$this->reponsecurl[204]   = "No Content";
		$this->reponsecurl[205]   = "Reset Content";
		$this->reponsecurl[206]   = "Partial Content";
		$this->reponsecurl[300]   = "Multiple Choices";
		$this->reponsecurl[301]   = "Moved Permanently";
		$this->reponsecurl[302]   = "Found";
		$this->reponsecurl[303]   = "See Other";
		$this->reponsecurl[304]   = "Not Modified";
		$this->reponsecurl[305]   = "Use Proxy";
		$this->reponsecurl[307]   = "Temporary Redirect";
		$this->reponsecurl[400]   = "Bad Request";
		$this->reponsecurl[401]   = "Unauthorized";
		$this->reponsecurl[402]   = "Payment Required";
		$this->reponsecurl[403]   = "Forbidden";
		$this->reponsecurl[404]   = "Not Found";
		$this->reponsecurl[405]   = "Method Not Allowed";
		$this->reponsecurl[406]   = "Not Acceptable";
		$this->reponsecurl[407]   = "Proxy Authentication Required";
		$this->reponsecurl[408]   = "Request Timeout";
		$this->reponsecurl[409]   = "Conflict";
		$this->reponsecurl[410]   = "Gone";
		$this->reponsecurl[411]   = "Length Required";
		$this->reponsecurl[412]   = "Precondition Failed";
		$this->reponsecurl[413]   = "Request Entity Too Large";
		$this->reponsecurl[414]   = "Request-URI Too Long";
		$this->reponsecurl[415]   = "Unsupported Media Type";
		$this->reponsecurl[416]   = "Requested Range Not Satisfiable";
		$this->reponsecurl[417]   = "Expectation Failed";
		$this->reponsecurl[500]   = "Internal Server Error";
		$this->reponsecurl[501]   = "Not Implemented";
		$this->reponsecurl[502]   = "Bad Gateway";
		$this->reponsecurl[503]   = "Service Unavailable";
		$this->reponsecurl[504]   = "Gateway Timeout";
		$this->reponsecurl[505]   = "HTTP Version Not Supported";
		
		$this->cookie_file = realpath('.').'/curl_cookie.txt';
		$this->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ?
			$_SERVER['HTTP_USER_AGENT'] :
			'Curl/PHP ' . PHP_VERSION . ' (http://github.com/shuber/curl/)';
	}
	  
	function delete($url, $vars = array()) {
		return $this->request('DELETE', $url, $vars);
	}
	
	function error() {
		return $this->error;
	}
	
	function get($url, $vars = array()) {
		$this->buffer="";
		if (!empty($vars)) {
			$url .= (stripos($url, '?') !== false) ? '&' : '?';
			$url .= http_build_query($vars, '', '&');
		}
		return $this->request('GET', $url);
	}
	
	function post($url, $vars = array()) {
		return $this->request('POST', $url, $vars);
	}
	
	function put($url, $vars = array()) {
		return $this->request('PUT', $url, $vars);
	}
	
	function getBodyOverflow($curl,$contenu) {
		$taille_max  = $this->limit;
		$taille_bloc = strlen($contenu);
		if (strlen($this->body_overflow)+$taille_bloc<$taille_max) $this->body_overflow .= $contenu;	
		return strlen($contenu);
	}
	
	function saveBodyInFile($curl,$contenu) {
		if(!$this->header_detect) {
			$this->buffer.=$contenu;
			$pattern = '#HTTP/\d\.\d.*?$.*?\r\n\r\n#ims';
			if (preg_match($pattern,$this->buffer)) {
				$texte = preg_replace($pattern, '', $this->buffer);
				$this->header_detect=1;
			}			
		} else $texte=$contenu;
		if($texte) {
			$fd = fopen($this->save_file_name,"a");
			fwrite($fd,$texte);
			fclose($fd);	
		}	
		return strlen($contenu);
	}
	
	/*function getHeader($curl,$contenu_header){
				
		return strlen($contenu_header);
	}*/
	
	# Protected
	function request($method, $url, $vars = array()) {
		
		$this->handle = curl_init();
		
		# Set some default CURL options
		if ($this->timeout) {
			curl_setopt($this->handle, CURLOPT_CONNECTTIMEOUT, $this->timeout);
			curl_setopt($this->handle, CURLOPT_TIMEOUT, $this->timeout);
		}
		curl_setopt($this->handle, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->handle, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->handle, CURLOPT_HEADER, true);
		curl_setopt($this->handle, CURLOPT_POSTFIELDS, (is_array($vars) ? http_build_query($vars, '', '&') : $vars));
		curl_setopt($this->handle, CURLOPT_REFERER, $this->referer);
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handle, CURLOPT_URL, str_replace(" ","%20",preg_replace("/#.*$/","",$url)));
		/*On supprime ce qui suit le # car c'est une ancre pour le navigateur et avec on consière la validation fausse alors qu'elle est bonne
		 *On remplace les espaces par %20 pour la même raison
		 */
		curl_setopt($this->handle, CURLOPT_USERAGENT, $this->user_agent);		
		if($this->limit) 
			curl_setopt($this->handle, CURLOPT_WRITEFUNCTION,array(&$this,'getBodyOverflow'));
		
		if($this->save_file_name){
			$this->header_detect=0;					
			curl_setopt($this->handle, CURLOPT_WRITEFUNCTION,array(&$this,'saveBodyInFile'));
		}	
		configurer_proxy_curl($this->handle);			
		
		# Format custom headers for this request and set CURL option
		$headers = array();
		foreach ($this->headers as $key => $value) {
			$headers[] = $key.': '.$value;
		}
		
		curl_setopt($this->handle, CURLOPT_HTTPHEADER, $headers);
		
		# Determine the request method and set the correct CURL option
		switch ($method) {
			case 'GET':
				curl_setopt($this->handle, CURLOPT_HTTPGET, true);
				break;
			case 'POST':
				curl_setopt($this->handle, CURLOPT_POST, true);
				break;
			default:
				curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, $method);
		}
		
		# Set any custom CURL options
		foreach ($this->options as $option => $value) {
			curl_setopt($this->handle, constant('CURLOPT_'.str_replace('CURLOPT_', '', strtoupper($option))), $value);
		}

		$this->body_overflow="";		
		$response = curl_exec($this->handle);
		if($this->limit) $response=$this->body_overflow;
		
		if ($response) {
			$response = new CurlResponse($response);
		} else {
			$this->error = curl_errno($this->handle).' - '.curl_error($this->handle);
		}
		curl_close($this->handle);
		
		return $response;
	}
}
 
class CurlResponse {
	var $body = '';
	var $headers = array();
	
	function CurlResponse($response) {
		$this->__construct($response);
	}
	
	function __construct($response) {
		# Extract headers from response
		$pattern = '#HTTP/\d\.\d.*?$.*?\r\n\r\n#ims';
		preg_match_all($pattern, $response, $matches);
		$headers = split("\r\n", str_replace("\r\n\r\n", '', array_pop($matches[0])));
		
		# Extract the version and status from the first header
		$version_and_status = array_shift($headers);
		preg_match('#HTTP/(\d\.\d)\s(\d\d\d)\s(.*)#', $version_and_status, $matches);
		$this->headers['Http-Version'] = $matches[1];
		$this->headers['Status-Code'] = $matches[2];
		$this->headers['Status'] = $matches[2].' '.$matches[3];
		
		# Convert headers into an associative array
		foreach ($headers as $header) {
			preg_match('#(.*?)\:\s(.*)#', $header, $matches);
			$this->headers[$matches[1]] = $matches[2];
		}
		
		# Remove the headers from the response body
		$this->body = preg_replace($pattern, '', $response);
	}
	
	function __toString() {
		return $this->body;
	}
}
 
/**
* http_build_query exists from PHP >= 5.0
* If !function_exists then declare it.
*/
if(!function_exists('http_build_query')) {
	
	/**
	* Generate URL-encoded query string.
	* See http://php.net/http_build_query for more details.
	*
	* @param mixed $formdata
	* @param string $numeric_prefix
	* @param string $arg_separator
	* @param string $key
	* @return string
	* @link http://php.net/http_build_query
	*/
	function http_build_query($formdata, $numeric_prefix = null, $arg_separator = null, $key = null) {
		$res = array();
		
		foreach ((array)$formdata as $k => $v) {
			$tmp_key = urlencode(is_int($k) ? $numeric_prefix.$k : $k);
			if ($key !== null) {
				$tmp_key = $key.'['.$tmp_key.']';
			}
			if (is_array($v) || is_object($v)) {
				$res[] = http_build_query($v, null, $arg_separator, $tmp_key);
			} else {
				$res[] = $tmp_key . "=" . urlencode($v);
			}
		}
		
		if ($arg_separator === null) {
		  $arg_separator = ini_get("arg_separator.output");
		}
		
		return implode($arg_separator, $res);
	}
}