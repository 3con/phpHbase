<?php

/**
 * Copyright (c) 2008, SARL Adaltas & EDF. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbaseRow->put method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class HbaseRowPutTest extends PopHbaseTestCase{
	public function testPut(){
		$hbase = $this->hbase;
		$value = 'my_value_'.time();
		$row = $hbase->tables->pop_hbase->row('row_test_count_1');
		$row->put('column_test:my_column',$value);
		$this->assertSame($value,$row->get('column_test:my_column'));
	}
}
