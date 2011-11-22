<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_hbase/license.html
 */

/**
 * Socket implementation of an HBase server connection.
 *
 * @author		David Worms info(at)adaltas.com
 */
class PopHbaseConnectionSock implements PopHbaseConnection{

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
	
	public function __destruct(){
		if(isset($this->options['sock'])){
			$this->disconnect();
		}
	}
	
	/**
	 * Magick method used to retrieve connected related information
	 */
	public function __get($property){
		switch($property){
			case 'sock':
				if(!isset($this->options['sock'])){
					$this->connect();
				}
			default:
				return $this->options[$property];
		}
	}
	
	/**
	 * Open the connection to the HBase server.
	 * 
	 * @return PopHbaseConnection Current connection instance
	 */
	public function connect() {
		if(isset($this->options['sock'])){
			$this->disconnect();
		}
		$this->options['sock'] = fsockopen($this->options['host'], $this->options['port'], $errNum, $errString);
		if(!$this->options['sock']) {
			throw new PopHbaseException('Failed connecting to '.(!empty($this->options['username'])?$this->options['username'].'@':'').$this->options['host'].':'.$this->options['port'].' ('.$errString.')');
		}
		return $this;
	}
	
	/**
	 * Destruct the current instance and close its connection if opened.
	 */
	public function destruct() {
		$this->__destruct();
	}
	
	/**
	 * Close the connection to the HBase server.
	 * 
	 * @return PopHbaseConnection Current connection instance
	 */
	public function disconnect() {
		if(!isset($this->options['sock'])){
			// nothing to do
			return $this;
		}
		fclose($this->options['sock']);
		unset($this->options['sock']);
		return $this;
	}
	
	/**
	 * Send HTTP REST command.
	 * 
	 * @return PopHbaseResponse Response object parsing the HTTP HBase response.
	 */
	public function execute($method,$url,$data=null,$raw=false) {
		$url = (substr($url, 0, 1) == '/' ? $url : '/'.$url);
		$request = $method.' '.$url.' HTTP/1.1'."\r\n";
		$request .= 'Host: '.$this->options['host'].':'.$this->options['port']."\r\n";
		// Add authentication only if username is provided and not empty
		if(!empty($this->options['username'])){
			$request .= 'Authorization: Basic '.base64_encode($this->options['username'].':'.$this->options['password'])."\r\n";
		}
		$request .= 'Accept: application/json'."\r\n";
		// Set keep-alive header, which helps to keep to connection
		// initilization costs low, especially when the database server is not
		// available in the locale net.
		$request .= 'Connection: ' . ( $this->options['alive'] ? 'Keep-Alive' : 'Close' ) . "\r\n";
		// Add request data and related headers if needed
		// otherwise add closing mark of the request header section.
		if($data) {
			if(is_array($data)){
				$data = json_encode($data);
			}
			$request .= 'Content-Length: '.strlen($data)."\r\n";
			$request .= 'Content-Type: application/json'."\r\n\r\n";
			$request .= $data."\r\n";
		} else {
			$request .= "\r\n";
		}
//		echo $request."\n\n";
		fwrite($this->sock, $request);
//		$response = '';
//		while(!feof($this->sock)) {
//			$response .= fgets($this->sock);
//		}
		
		$raw = '';
		$headers = '';
		$body = '';
		// Extract HTTP response headers
		while ( ( ( $line = fgets( $this->sock ) ) !== false ) &&
				   ( ( $line = rtrim( $line ) ) !== '' ) ){
			// Extract HTTP version and response code
			// as well as other headers
			if ( preg_match( '(^HTTP/(?P<version>\d+\.\d+)\s+(?P<status>\d+))S', $line, $matches ) ) {
				$headers['version'] = $matches['version'];
				$headers['status']  = (int) $matches['status'];
			} else {
				list( $key, $value ) = explode( ':', $line, 2 );
				$headers[strtolower( $key )] = ltrim( $value );
			}
		}
		// Extract HTTP response body
		$body = '';
		if ( !isset( $headers['transfer-encoding'] ) ||
			 ( $headers['transfer-encoding'] !== 'chunked' ) ) {
			// HTTP 1.1 supports chunked transfer encoding, if the according
			// header is not set, just read the specified amount of bytes.
			$bytesToRead = (int) ( isset( $headers['content-length'] ) ? $headers['content-length'] : 0 );
			// Read body only as specified by chunk sizes, everything else
			// are just footnotes, which are not relevant for us.
			if($this->options['alive']||$bytesToRead){
				while ( $bytesToRead > 0 ) {
					$body .= $read = fgets( $this->sock, $bytesToRead + 1 );
					$bytesToRead -= strlen( $read );
				}
			}else{
				while ( ( ( $line = fgets( $this->sock, 1024 ) ) !== false ) ){
					$body .= $line;
				}
			}
		} else {
			// When transfer-encoding=chunked has been specified in the
			// response headers, read all chunks and sum them up to the body,
			// until the server has finished. Ignore all additional HTTP
			// options after that.
			while ($chunk_length = hexdec(fgets($this->sock))){
				$responseContentChunk = '';
				$read_length = 0;
				while ($read_length < $chunk_length){
					$responseContentChunk .= fread($this->sock, $chunk_length - $read_length);
					$read_length = strlen($responseContentChunk);
				}
				$body.= $responseContentChunk;
				fgets($this->sock);
			}
		}
        // Reset the connection if the server asks for it.
        if ( isset($headers['connection']) && $headers['connection'] !== 'Keep-Alive' ) {
            $this->disconnect();
        }
        //$this->disconnect();
//        echo '------------------------'."\n";
//		print_r($headers);
//        echo '------------------------'."\n";
//		print_r($body);
//        echo '------------------------'."\n";
		return new PopHbaseResponse($headers,$body,$raw);
	}

}
