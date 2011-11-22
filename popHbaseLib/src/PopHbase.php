<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_hbase/license.html
 */

/**
 * Central class around HBase server access.
 *
 * @author		David Worms info(at)adaltas.com
 */
class PopHbase{

	public $options;
	
	public function __construct($options=array()){
		if(empty($options['connection'])){
			$options['connection'] = 'PopHbaseConnectionCurl';
		}else if (class_exists('PopHbaseConnection'.ucfirst($options['connection']))){
			$options['connection'] = 'PopHbaseConnection'.ucfirst($options['connection']);
		}else if (!class_exists($options['connection'])){
			throw new Exception('Invalid connection class: "'.$options['connection'].'"');
		}
		$this->options = $options;
	}
	
	/**
	 * Detruct current instance, its potential circular references and close the HBase connection if opened.
	 */
	public function __destruct(){
		
	}
	
	public function __get($property){
		switch($property){
			case 'connection':
				return $this->connection = new $this->options['connection']($this->options);
				break;
			case 'tables':
				return $this->getTables();
				break;
			case 'request':
				return new PopHbaseRequest($this);
				break;
		}
	}
	
	public function __call($method,$args){
		switch($method){
			case 'tables':
				return call_user_func_array(array($this,'getTables'),$args);
		}
	}
	
	/**
	 * Shortcut to the PHP Magick destruct method.
	 */
	public function destruct(){
		
	}
	
	/**
	 * Retrieve the list of all databases in HBase.
	 */
	public function getTables(){
		if(isset($this->tables)) return $this->tables;
		return $this->tables = new PopHbaseTables($this);
	}
	
	/**
	 * Return the Stargate connection version information
	 */
	public function getVersion(){
		return $this->request->get('version')->getBody();
	}
	
	/**
	 * Return the Stargate version cluster information
	 */
	public function getVersionCluster(){
		return $this->request->get('version/cluster')->getBody();
	}
	
	/**
	 * Return the Stargate satus cluster information
	 */
	public function getStatusCluster(){
		return $this->request->get('status/cluster')->getBody();
	}
	
	
	/**
	 * Shortcut to the "PopHbaseTables->table" method.
	 * 
	 * See "PopHbaseTables->table" for usage information.
	 * 
	 */
	public function table($table){
		return $this->tables->table($table);
	}
	
	/**
	 * Shortcut to the "PopHbaseTables->create" method.
	 * 
	 * See "PopHbaseTables->create" for usage information.
	 * 
	 * @param $table string Name of the table to create
	 * @param $column string Name of the column family to create
	 * @return PopHbase Current instance
	 */
	public function create($table){
		return call_user_func_array(array($this,'tables'),$args)->hbase;
	}
	
	/**
	 * Shortcut to the "PopHbaseTables->delete" method.
	 * 
	 * See "PopHbaseTables->delete" for usage information.
	 * 
	 * @param $table string Name of the table to delete
	 * @return PopHbase Current instance
	 */
	public function delete($table){
		return $this->tables->delete($table)->hbase;
	}

}
