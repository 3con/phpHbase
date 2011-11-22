<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/porte/license.html
 */

/**
 *	BasicTest
 *
 *	@author     David Worms info(at)adaltas.com
 *	@copyright  2008-2009 Adaltas
 */
 
require_once 'PHPUnit/Framework.php';

error_reporting(E_ALL | E_STRICT);
require dirname(__FILE__).'/../src/pop_hbase.inc.php';
require(dirname(__File__).'/../../pur/src/pur.inc.php');

abstract class PopHbaseTestCase extends PHPUnit_Framework_TestCase{
	public function __get($key){
		switch($key){
			case 'config':
				return $this->config();
				break;
			case 'hbase':
				return $this->hbase();
		}
	}
	public function config(){
		$config = dirname(__FILE__).'/user.properties';
		if(file_exists($config)){
			$config = PurProperties::stringToArray(PurFile::read($config));
		}else{
			echo 'First running the test?'.PHP_EOL;
			echo 'A new file user.properties was created in tests directory.'.PHP_EOL;
			echo 'Modify it to feet your HBase installation, then the tests again.'.PHP_EOL;
			exit;
		}
		return $config;
	}
	public function hbase(){
		if(isset($this->hbase)) return $this->hbase;
		return $this->hbase = new PopHbase($this->config);
	}
	public function setUp(){
		if(!$this->hbase->tables->exists('pop_hbase')){
			$this->hbase->tables->create('pop_hbase','column_test');
		}
	}
	public function tearDown(){
	}
}
?>
