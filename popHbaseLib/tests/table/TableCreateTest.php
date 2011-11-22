<?php

/**
 * Copyright (c) 2008, SARL Adaltas & EDF. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbaseTable->create method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class TableCreateTest extends PopHbaseTestCase{
	public function testCount(){
		$hbase = $this->hbase;
		$tables = $hbase->tables;
		$this->assertSame(1,count($tables));
		// Test with no database
		$table = $tables->pop_hbase_create;
		// Test with one database
		$table->create('my_column');
		$this->assertSame(2,count($tables));
		$table->delete('pop_hbase_create');
	}
}
