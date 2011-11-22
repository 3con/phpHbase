<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the add method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class HbaseSockDisconnectTest extends PopHbaseTestCase{
	public function testReturn(){
		$connection = new PopHbaseConnectionSock($this->config);
		// When not connected
		$this->assertSame($connection,$connection->disconnect());
		// When connected
		$connection->connect();
		$this->assertSame($connection,$connection->disconnect());
	}
}
