<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_hbase/license.html
 */

/**
 * Hold a list of tables.
 *
 * @author		David Worms info(at)adaltas.com
 */
class PopHbaseTables extends PopHbaseIterator{
	
	public $hbase;
	
	public function __construct(PopHbase $hbase){
		$this->hbase = $hbase;
		$this->__data = array();
	}
	
	/**
	 * Provide a shortcut to the "table" method.
	 * 
	 * Usage
	 *     assert($hbase->tables->{'my_table'} instanceof PopHbaseTable);
	 * 
	 */
	public function __get($table){
		return $this->table($table);
	}
	
//	public function __unset($property){
//		unset($this->__data['data'][$property]);
//	}
	
	public function reload(){
		unset($this->__data['data']);
		unset($this->__data['loaded']);
	}
	
	public function load(){
		if(isset($this->__data['data'])&&isset($this->__data['loaded'])){
			return $this;
		}
		$tables = $this->hbase->request->get('/')->body;
		$this->__data['data'] = array();
		$this->__data['loaded'] = array();
		if(is_null($tables)){ // No table
			return $this;
		}
		foreach($tables['table'] as $table){
			$this->__data['data'][$table['name']] = new PopHbaseTable($this->hbase,$table['name']);
			$this->__data['loaded'][$table['name']] = true;
		}
		return $this;
	}
	
	/**
	 * Create a new table and associated column families schema.
	 * 
	 * The first argument is expected to be the table name while the following
	 * arguments describle column families.
	 * 
	 * Usage
	 *     $hbase->tables->create(
	 *         'table_name',
	 *         'column_1',
	 *         array('name'=>'column_2'),
	 *         array('NAME'=>'column_3'),
	 *         array('@NAME'=>'column_4',...);
	 * 
	 * @param $table string Name of the table to create
	 * @param $column string Name of the column family to create
	 * @return PopHbase Current instance
	 */
	public function create(){
		$args = func_get_args();
		if(count($args)===0){
			throw new InvalidArgumentException('Missing table schema definition');
		}

		$table = array_shift($args);
		
		switch(gettype($table))
		{
			case 'string':
				$schema = array('name' => $table);
				break;
			case 'array':
				// name is required
				// other keys include IS_META and IS_ROOT
				$schema = array();
				foreach($table as $k=>$v){
					if(substr($k,0,1)=='@'){
						$k = substr($k,1);
					}
					if($k=='NAME'){
						$k = 'name';
					}
					/*
					else{
						$k = strtoupper($k);
					}*/
					$schema[$k] = $v;
				}
				if(!isset($schema['name'])){
					throw new InvalidArgumentException('Table schema definition not correctly defined "'.PurLang::toString($table).'"');
				}
				break;
			default:
				throw new InvalidArgumentException('Table schema definition not correctly defined: "'.PurLang::toString($table).'"');
		}
		
		if(count($args)===0){
			throw new InvalidArgumentException('Missing at least one column schema definition');
		}
		$schema['ColumnSchema'] = array();
		foreach($args as $arg){
			switch(gettype($arg)){
				case 'string':
					$schema['ColumnSchema'][] = array('name' => $arg);
					break;
				case 'array':
					// name is required
					// other keys include BLOCKSIZE, BLOOMFILTER,
					// BLOCKCACHE, COMPRESSION, LENGTH, VERSIONS, 
					// TTL, and IN_MEMORY
					$columnSchema = array();
					foreach($arg as $k=>$v){
						if(substr($k,0,1)=='@'){
							$k = substr($k,1);
						}
						if($k=='NAME'){
							$k = 'name';
						}
						/*
						else{
							$k = strtoupper($k);
						}
						*/
						$columnSchema[$k] = $v;
					}
					if(!isset($columnSchema['name'])){
						throw new InvalidArgumentException('Column schema definition not correctly defined "'.PurLang::toString($table).'"');
					}
					$schema['ColumnSchema'][] = $columnSchema;
					break;
				default:
				throw new InvalidArgumentException('Column schema definition not correctly defined: "'.PurLang::toString($table).'"');
			}
		}

		$this->hbase->request->put($schema['name'].'/schema',$schema);
		$this->reload();
	}
	
	/**
	 * Delete a table from an HBase server.
	 * 
	 * Note, manipulate with care since datas are not recoverable.
	 * 
	 * Usage
	 *     $hbase->tables->delete('table_name');
	 */
	public function delete($table){
		$body = $this->hbase->request->delete($table.'/schema');
		$this->reload();
		return $this;
	}
	
	/**
	 * Check wether a table exist in HBase.
	 */
	public function exists($table){
		foreach($this as $t){
			if($t->name==$table) return isset($this->__data['loaded'][$table]);
		}
		return false;
	}
	
	/**
	 * List the table names present in HBase.
	 */
	public function names(){
		$tables = array();
		foreach($this as $table){
			if(isset($this->__data['loaded'][$table->name])){
				$tables[] = $table->name;
			}
		}
		return $tables;
	}
	
	/**
	 * Return a PopHbaseTable instance.
	 * 
	 * If the same table is requested twice, the same instance is returned.
	 * 
	 * Usage
	 *     assert($hbase->tables->table('my_table') instanceof PopHbaseTable);
	 * 
	 */
	public function table($table){
		if(!isset($this->__data['data'][$table])){
			$this->__data['data'][$table] = new PopHbaseTable($this->hbase,$table);
		}
		return $this->__data['data'][$table];
	}

}
