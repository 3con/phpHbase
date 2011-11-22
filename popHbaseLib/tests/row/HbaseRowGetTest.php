<?php

/**
 * Copyright (c) 2008, SARL Adaltas & EDF. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbaseRow->get method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class HbaseRowGetTest extends PopHbaseTestCase{
	public function testUndefinedColumn(){
		$hbase = $this->hbase;
		// Test with undefined value
		$row = $hbase->tables->pop_hbase->row('row_test_count_1');
		$value = $row->get('column_test:get_undefined_my_column');
		$this->assertNull($value);
		// Test with inserted value
		$value = 'my_value_'.time();
		$row->put('column_test:my_column',$value);
		$this->assertSame($value,$row->get('column_test:my_column'));
		$this->assertSame($value,$row->{'column_test:my_column'});
	}
}
