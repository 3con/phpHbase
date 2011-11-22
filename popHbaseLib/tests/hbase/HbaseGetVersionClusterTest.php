<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_config/license.html
 */

require_once dirname(__FILE__).'/../PopHbaseTestCase.php';

/**
 * Test the PopHbase->getVersionCluster method.
 * 
 * @author		David Worms info(at)adaltas.com
 *
 */
class HbaseGetVersionClusterTest extends PopHbaseTestCase{
	public function testReturn(){
		$hbase = new PopHbase($this->config);
		$versionCluster = $hbase->getVersionCluster();
		$this->assertTrue((boolean)preg_match('/^\d[\d\.]+/',$versionCluster));
	}
}
