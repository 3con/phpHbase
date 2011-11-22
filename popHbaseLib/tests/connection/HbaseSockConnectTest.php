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
class HbaseSockConnectTest extends PopHbaseTestCase{
	public function testReturn(){
		$connection = new PopHbaseConnectionSock($this->config);
		$this->assertSame($connection,$connection->connect());
		$version = $connection->execute('get','version')->getBody();
		$this->assertTrue(is_array($version));
		$this->assertSame(array('Server','REST','OS','Jersey','JVM'),array_keys($version));
		
	}
}
