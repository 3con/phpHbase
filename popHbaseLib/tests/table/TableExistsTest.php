<?php

/**
 * Copyright (c) 2008, SARL Adaltas & EDF. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbaseTable->exists method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class TableExistsTest extends PopHbaseTestCase{
	public function testCount(){
		$hbase = $this->hbase;
		$tables = $hbase->tables;
		// Test with no database
		$table = $tables->pop_hbase_exists;
		// Test with our custome table
		$this->assertFalse($table->exists());
		$table->create('my_column');
		$this->assertTrue($table->exists());
		$table->delete('pop_hbase_exists');
	}
}
