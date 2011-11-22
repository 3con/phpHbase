<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_hbase/license.html
 */

/**
 * Curl implementation of an HBase server connection.
 *
 * @author		David Worms info(at)adaltas.com
 */
class PopHbaseConnectionCurl implements PopHbaseConnection{

	public $options;
	
	/**
	 * Connection constructor with its related information.
	 * 
	 * Options may include:
	 * -   *host*
	 *     Hbase server host, default to "localhost"
	 * -   *port*
	 *     Hbase server port, default to "8080"
	 * 
	 * Accorging to Stargate API:
	 *     ./bin/hbase org.apache.hadoop.hbase.stargate.Main -p <port>
	 *     ./bin/hbase-daemon.sh start org.apache.hadoop.hbase.stargate.Main -p <port>
	 * Where <port> is optional, and is the port the connector should listen on. (Default is 8080.)
	 * 
	 * @param $options array Connection information
	 * @return null
	 */
	public function __construct(array $options = array()){
		if(!isset($options['host'])){
			$options['host'] = 'localhost';
		}
		if(!isset($options['port'])){
			$options['port'] = 8080;
		}
		if(!isset($options['alive'])){
			$options['alive'] = 1;
		}
		// todo, this suggest that password may be empty like with MySql, need to check this
		if(!empty($this->options['username'])&&!isset($this->options['password'])){
			$this->options['password'] = '';
		}
		$this->options = $options;
	}
	
	/**
	 * Magick method used to retrieve connected related information
	 */
	public function __get($property){
		switch($property){
			default:
				return $this->options[$property];
		}
	}
	
	/**
	 * Send HTTP REST command.
	 * 
	 * @return PopHbaseResponse Response object parsing the HTTP HBase response.
	 */
	public function execute($method,$url,$data=null,$raw=false) {
		$url = (substr($url, 0, 1) == '/' ? $url : '/'.$url);
		if(is_array($data)){
			$data = json_encode($data);
		}
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://'.$this->options['host'].':'.$this->options['port'].$url);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Accept: application/json',
			'Connection: ' . ( $this->options['alive'] ? 'Keep-Alive' : 'Close' ),
		));
		curl_setopt($curl, CURLOPT_VERBOSE, !empty($this->options['verbose'])); 
		switch(strtoupper($method)){
			case 'DELETE':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
			case 'PUT':
				curl_setopt($curl, CURLOPT_PUT, 1);
				$file = tmpfile();
				fwrite($file, $data);
				fseek($file, 0);
				curl_setopt($curl, CURLOPT_INFILE, $file);
				curl_setopt($curl, CURLOPT_INFILESIZE, strlen($data));
				break;
			case 'POST':
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case 'GET':
				curl_setopt($curl, CURLOPT_HTTPGET, 1);
				break;
		}
		
		$data = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		list($_headers,$body) = explode("\r\n\r\n", $data, 2);
		$_headers = explode("\r\n",$_headers);
		$headers = array();
		foreach($_headers as $_header){
			if ( preg_match( '(^HTTP/(?P<version>\d+\.\d+)\s+(?P<status>\d+))S', $_header, $matches ) ) {
				$headers['version'] = $matches['version'];
				$headers['status']  = (int) $matches['status'];
			} else {
				list( $key, $value ) = explode( ':', $_header, 2 );
				$headers[strtolower($key)] = ltrim( $value );
			}
		}
		
		curl_close($curl);
		switch(strtoupper($method)){
			case 'PUT':
				fclose($file); 
				break;
		}
		return new PopHbaseResponse($headers,$body,$raw);
	}

}
