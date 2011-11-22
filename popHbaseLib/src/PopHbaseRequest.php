<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_hbase/license.html
 */

/**
 * Wrap HTTP REST requests
 *
 * @author		David Worms info(at)adaltas.com
 */
class PopHbaseRequest{

	public $hbase;
	
	/**
	 * Request constructor.
	 * 
	 * @param PopHbase required $hbase instance
	 */
	function __construct(PopHbase $hbase){
		$this->hbase = $hbase;
	}
	
	/**
	 * Create a DELETE HTTP request.
	 * 
	 * @return PopHbaseResponse Response object
	 */
	public function delete($command){
		return $this->hbase->connection->execute('DELETE',$command);
	}
	
	/**
	 * Create a GET HTTP request.
	 * 
	 * @return PopHbaseResponse Response object
	 */
	public function get($command){
		return $this->hbase->connection->execute('GET',$command);
	}
	
	/**
	 * Create a POST HTTP request.
	 * 
	 * @return PopHbaseResponse Response object
	 */
	public function post($command,$data){
		return $this->hbase->connection->execute('POST',$command,$data);
	}
	
	/**
	 * Create a PUT HTTP request.
	 * 
	 * @return PopHbaseResponse Response object
	 */
	public function put($command,$data=null){
		return $this->hbase->connection->execute('PUT',$command,$data);
	}

}
