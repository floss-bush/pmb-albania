<?php
/*
					COPYRIGHT

Copyright 2007 Sergio Vaccaro <sergio@inservibile.org>

This file is part of JSON-RPC PHP.

JSON-RPC PHP is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

JSON-RPC PHP is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with JSON-RPC PHP; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The object of this class are generic jsonRPC 1.0 clients
 * http://json-rpc.org/wiki/specification
 *
 * @author sergio <jsonrpcphp@inservibile.org>
 * @author emartin <emartin@sigb.net>
 */
class pmb_jsonrpc_client {
	
	/**
	 * Debug state
	 *
	 * @var boolean
	 */
	private $debug;
	
	/**
	 * The server URL
	 *
	 * @var string
	 */
	private $url;
	/**
	 * The request id
	 *
	 * @var integer
	 */
	private $id;
	/**
	 * If true, notifications are performed instead of requests
	 *
	 * @var boolean
	 */
	private $notification = false;
	
	/**
	 * Authentication Type. Valid values: none, json, http
	 * 
	 * @var authentication_type string
	 */
	private $authentication_type = 'none';
	
	/**
	 * Authentification User. Only used when authentication_type != 'none'
	 * 
	 * @var authentication_user string
	 */
	private $authentication_user = '';
	
	/**
	 * Authentification Password. Only used when authentication_type != 'none'
	 * 
	 * @var authentication_password string
	 */
	private $authentication_password = '';
	
	/**
	 * Takes the connection parameters
	 *
	 * @param string $url
	 * @param boolean $debug
	 */
	public function __construct($url,$debug = false) {
		// server URL
		$this->url = $url;
		// proxy
		empty($proxy) ? $this->proxy = '' : $this->proxy = $proxy;
		// debug state
		empty($debug) ? $this->debug = false : $this->debug = true;
		// message id
		$this->id = 1;
	}
	
	public function setAuthenticationType($new_value='none') {
		if ($new_value == 'json')
			$this->authentication_type = 'json';
		else if ($new_value == 'http')
			$this->authentication_type = 'http';
		else
			$this->authentication_type = 'none';
	}
	
	public function setCredentials($user='', $password='') {
		$this->authentication_user = $user;
		$this->authentication_password = $password;
	}
	
	/**
	 * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
	 *
	 * @param boolean $notification
	 */
	public function setRPCNotification($notification) {
		empty($notification) ?
							$this->notification = false
							:
							$this->notification = true;
	}
	
	/**
	 * Performs a jsonRCP request and gets the results as an array
	 *
	 * @param string $method
	 * @param array $params
	 * @return array
	 */
	public function __call($method,$params) {
		
		// check
		if (!is_scalar($method)) {
			throw new Exception('Method name has no scalar value');
		}
		
		// check
		if (is_array($params)) {
			// no keys
			$params = array_values($params);
		} else {
			throw new Exception('Params must be given as array');
		}
		
		// sets notification or request task
		if ($this->notification) {
			$currentId = NULL;
		} else {
			$currentId = $this->id;
		}
		
		// prepares the request
		$request = array(
						'method' => $method,
						'params' => $params,
						'id' => $currentId
						);
						
		if ($this->authentication_type == 'json') {
			$request['auth_user'] = $this->authentication_user;
			$request['auth_pw'] = $this->authentication_password;
		}
		$request = json_encode($request);
		$this->debug && $this->debug.='***** Request *****'."\n".$request."\n".'***** End Of request *****'."\n\n";
		
		// performs the HTTP POST
		$opts = array ('http' => array (
							'method'  => 'POST',
							'header'  => 'Content-type: application/json',
							'content' => $request
							));
		if ($this->authentication_type == 'http') {
			$opts['http']['header'] .= sprintf("\r\nAuthorization: Basic %s", base64_encode($this->authentication_user.':'.$this->authentication_password));
		}
		$context  = stream_context_create($opts);
		if ($fp = fopen($this->url, 'r', false, $context)) {
			$response = '';
			while($row = fgets($fp)) {
				$response.= trim($row)."\n";
			}
			$this->debug && $this->debug.='***** Server response *****'."\n".$response.'***** End of server response *****'."\n";
			$response = json_decode($response,false);
		} else {
			throw new Exception('Unable to connect to '.$this->url);
		}
		fclose($fp);
		
		// debug output
		if ($this->debug) {
			echo nl2br($debug);
		}
		
		// final checks and return
		if (!$this->notification) {
			// check
			if ($response->id != $currentId) {
				throw new Exception('Incorrect response id (request id: '.$currentId.', response id: '.$response->id.')');
			}
			if (!is_null($response->error)) {
				throw new Exception('Request error: '.$response->error);
			}
			
			return $response->result;
			
		} else {
			return true;
		}
	}
}
?>