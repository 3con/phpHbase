<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_hbase/license.html
 */

/**
 * Wrap a HBase server connection.
 *
 * @author		David Worms info(at)adaltas.com
 */
interface PopHbaseConnection{
	
	/**
	 * Connection constructor with its related information.
	 * 
	 * Options may include:
	 * -   *host*
	 *	 Hbase server host, default to "localhost"
	 * -   *port*
	 *	 Hbase server port, default to "5984"
	 * 
	 * @param $options
	 * @return unknown_type
	 */
	public function __construct(array $options = array());
	
	/**
	 * Send HTTP REST command.
	 * 
	 * @return PopHbaseResponse Response object parsing the HTTP HBase response.
	 */
	public function execute($method,$url,$data=null,$raw=false);

}
