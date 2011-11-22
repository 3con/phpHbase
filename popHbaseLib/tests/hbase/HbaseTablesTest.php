<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbase->getVersion method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class HbaseTablesTest extends PopHbaseTestCase{
	public function testProperty(){
		$hbase = new PopHbase($this->config);
		$this->assertTrue($hbase->tables instanceof PopHbaseTables);
	}
	public function testMethod(){
		$hbase = new PopHbase($this->config);
		$this->assertTrue($hbase->table('pop_hbase') instanceof PopHbaseTable);
	}
}
