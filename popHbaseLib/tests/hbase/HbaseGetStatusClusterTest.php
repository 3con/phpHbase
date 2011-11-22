<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbase->getStatusCluster method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class HbaseGetStatusClusterTest extends PopHbaseTestCase{
	public function testReturn(){
		$hbase = new PopHbase($this->config);
		$statusCluster = $hbase->getStatusCluster();
		$this->assertSame(array('requests','regions','averageLoad','DeadNodes','LiveNodes'),array_keys($statusCluster));
	}
}
