<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbaseTables->delete method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class TablesDeleteTest extends PopHbaseTestCase{
	public function testCount(){
		$hbase = $this->hbase;
		// Test with no database
		$tables = $hbase->tables();
		$this->assertSame(1,count($tables));
		// Test with one database
		$hbase->tables->create('pop_hbase_delete','my_column');
		$this->assertSame(2,count($tables));
		$hbase->tables->delete('pop_hbase_delete');
		$this->assertSame(1,count($tables));
		$this->assertTrue($tables->current() instanceof PopHbaseTable);
	}
}
