<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbaseTables->exists method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class TablesExistsTest extends PopHbaseTestCase{
	public function testCount(){
		$hbase = $this->hbase;
		// Test with no table
		$tables = $hbase->tables();
		// Test with our custum table
		$this->assertFalse($hbase->tables->exists('pop_hbase_exists'));
		$hbase->tables->create('pop_hbase_exists','my_column');
		$this->assertTrue($hbase->tables->exists('pop_hbase_exists'));
		$hbase->tables->delete('pop_hbase_exists');
	}
}
