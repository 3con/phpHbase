<?php

/**
 * Copyright (c) 2008, SARL Adaltas & EDF. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbaseTables->names method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class TablesNamesTest extends PopHbaseTestCase{
	public function testList(){
		$hbase = $this->hbase;
		// Test with no table
		$tables = $hbase->tables();
		$this->assertSame(1,count($tables));
		$this->assertSame(array('pop_hbase'),$hbase->tables->names());
		// Test with one more table
		$hbase->tables->create('pop_hbase_list','my_column');
		$this->assertSame(array('pop_hbase','pop_hbase_list'),$hbase->tables->names());
		$hbase->tables->delete('pop_hbase_list');
	}
}
