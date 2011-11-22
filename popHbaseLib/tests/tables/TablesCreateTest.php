<?php

/**
 * Copyright (c) 2008, SARL Adaltas & EDF. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbaseTables->create method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class TablesCreateTest extends PopHbaseTestCase{
	public function testArg1StringArg2String(){
		$hbase = $this->hbase;
		// Test with no table
		$tables = $hbase->tables();
		$this->assertSame(1,count($tables));
		// Test with one table
		$hbase->tables->create('pop_hbase_create','my_column');
		$this->assertSame(2,count($tables));
		$this->assertTrue($tables->current() instanceof PopHbaseTable);
		$hbase->tables->delete('pop_hbase_create');
	}
	public function testInvalidArguments(){
		$hbase = $this->hbase;
		try{
			$hbase->tables->create();
		}catch(InvalidArgumentException $e){}
		$this->assertSame('Missing table schema definition',$e->getMessage());
		$hbase = $this->hbase;
		try{
			$hbase->tables->create('pop_hbase_create');
		}catch(InvalidArgumentException $e){}
		$this->assertSame('Missing at least one column schema definition',$e->getMessage());
	}
}
