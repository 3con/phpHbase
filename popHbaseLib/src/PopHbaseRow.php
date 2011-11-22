<?php

/**
 * Copyright (c) 2008, SARL Adaltas. All rights reserved.
 * Code licensed under the BSD License:
 * http://www.php-pop.org/pop_hbase/license.html
 */

/**
 * Wrap an Hbase table row.
 *
 * @author		David Worms info(at)adaltas.com
 */
class PopHbaseRow{
	
	public $hbase;
	public $key;
	
	/**
	 * Contruct a new row instance.
	 * 
	 * The identified row does not have to be yet persisted in HBase, it
	 * will automatically be created if not yet present.
	 * 
	 * @param PopHbase $hbase
	 * @param string $table
	 * @param string $key
	 */
	public function __construct(PopHbase $hbase,$table,$key){
		$this->hbase = $hbase;
		$this->table = $table;
		$this->key = $key;
	}
	
	public function __get($column){
		return $this->get($column);
	}
	
	/**
	 * Deletes an entire row, a entire column family, or specific cell(s).
	 * 
	 * Delete a entire row
	 *    $hbase
	 *        ->table('my_table')
	 *            ->row('my_row')
	 *                ->delete();
	 * 
	 * Delete a entire column family
	 *    $hbase
	 *        ->table('my_table')
	 *            ->row('my_row')
	 *                ->delete('my_column_family');
	 * 
	 * Delete all the cells in a column
	 *    $hbase
	 *        ->table('my_table')
	 *            ->row('my_row')
	 *                ->delete('my_column_family:my_column');
	 * 
	 * Delete a specific cell
	 *    $hbase
	 *        ->table('my_table')
	 *            ->row('my_row')
	 *                ->delete('my_column_family:my_column','my_timestamp');
	 */
	public function delete(){
		$args = func_get_args();
		$url = $this->table .'/'.$this->key;
		switch(count($args)){
			case 1;
				// Delete a column or a column family
				$url .= '/'.$args[0];
			case 2:
				// Delete a specific cell
				$url .= '/'.$args[1];
		}
		return $this->hbase->request->delete($url);
	}
	
	
	/**
	 * Retrieve a value from a column row.
	 * 
	 * Usage:
	 * 
	 *    $hbase
	 *        ->table('my_table')
	 *            ->row('my_row')
	 *                ->get('my_column_family:my_column');
	 */
	public function get($column){
		$body = $this->hbase->request->get($this->table .'/'.$this->key.'/'.$column)->body;
		if(is_null($body)){
			return null;
		}
		return base64_decode($body['Row'][0]['Cell'][0]['$']);
	}
	
	// ADDED BY MUHAMMAD
	public function getFullRow()
	{
		$body = $this->hbase->request->get($this->table .'/'.$this->key)->body;
		//return base64_decode($body);
		return $body;
	}
	
	/**
	 * Create or update a column row.
	 * 
	 * Usage:
	 * 
	 *    $hbase
	 *        ->table('my_table')
	 *            ->row('my_row')
	 *                ->put('my_column_family:my_column','my_value');
	 * 
	 * Note, in HBase, creation and modification of a column value is the same concept.
	 */
	public function put($column,$value){
		$value = array(
			'Row' => array(array(
				'key' => base64_encode($this->key),
				'Cell' => array(array(
					'column' => base64_encode($column),
					'$' => base64_encode($value)
				))
			))
		);
		$this->hbase->request->put($this->table .'/'.$this->key.'/'.$column,$value);
		return $this;
	}

}
