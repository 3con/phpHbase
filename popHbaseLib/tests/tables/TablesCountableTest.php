<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbaseTables countable interface.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class TablesCountableTest extends PopHbaseTestCase{
	public function testCount(){
		$hbase = $this->hbase;
		// Count the number of tables
		$tables = $hbase->tables();
		$count = count($tables);
		// Add a new table
		$hbase->tables->create('pop_hbase_test1','column_family');
		$this->assertSame($count+1,count($tables));
		// Drop the table
		$hbase->tables->delete('pop_hbase_test1');
		$this->assertSame($count,count($tables));
	}
}
