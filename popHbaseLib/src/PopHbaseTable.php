<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_hbase/license.html
 */

/**
 * Wrap an Hbase table
 *
 * @author		David Worms info(at)adaltas.com
 */
class PopHbaseTable{
	
	public $hbase;
	public $name;
	
	public function __construct(PopHbase $hbase,$name){
		$this->hbase = $hbase;
		$this->name = $name;
	}
	
	public function __get($row){
		return $this->row($row);
	}
	
	public function create(){
		call_user_func_array(
			array($this->hbase->tables,'create'),
			array_merge(array($this->name),func_get_args()));
		return $this;
	}
	
	public function delete(){
		$this->hbase->tables->delete($this->name);
		return $this;
	}
	
	public function exists(){
		return $this->hbase->tables->exists($this->name);
	}
	
	public function row($row){
		return new PopHbaseRow($this->hbase,$this->name,$row);
	}

}
