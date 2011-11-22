<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_hbase/license.html
 */

/**
 * Wrap a HBase response
 *
 * @author		David Worms info(at)adaltas.com
 */
class PopHbaseResponse{
	
	public function __construct($headers,$body,$raw=false) {
		$this->headers = $headers;
		$this->body = $raw?$body:json_decode($body,true);
		$this->raw = $raw;
//		preg_match('/^(\d{3})/',trim($this->headers['status']),$matches);
//		echo $this->headers['status'].' - '.$matches[1]."\n";
//		$status = $matches[1];
//		switch($status){
//			case '500':
//				throw new PopHbaseException(constant('PurHTTP::CODE_'.$status));
//		}
	}
	
//	public function __get($property){
//		switch($property){
//			case 'body':
//				return $this->getBody();
//		}
//	}
	
	public function __call($method,$args) {
		switch($method){
			case 'body':
				return call_user_func_array(array($this,'getBody'),$args);
		}
	}
	
	public function getRaw() {
		return $this->raw;
	}

    function getHeaders() {
        return $this->headers;
    }

    function getBody() {
		return $this->body;
    }

}
