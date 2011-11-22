<?php 
	
	require_once dirname(__FILE__) . '/popHbaseLib/src/pop_hbase.inc.php';

	try
	{
		// IMP
		// DEFINING CONNECTION TO HBASE. LAZY ONE
		$hbaseObj = new PopHbase
				(
					array('host' => '127.0.0.1', 'port' => '8080')
				);

		// GETTING THE VERSION INFO
		$versionInfo = $hbaseObj->getVersion();
		print '<pre>';
		print_r($versionInfo);
		print '</pre>';
		// GETTING THE CLUSTER INFO
		$versionClusterInfo = $hbaseObj->getStatusCluster();
		print '<pre>';
		print_r($versionClusterInfo);
		print '</pre>';
		
		// IMP
		// GETTING THE TABLE OBJECT
		$tableObj = $hbaseObj->tables;
		
		/*
		// CREATING A NEW TABLE ON HBASE
		$tableObj->create
					(
						array('name' => "usersInfo"),
						array('@name' => "metaData"),
						array('@name' => "basicInfo")
					);
		*/
		
		// GETTING THE TABLE NAMES
		$tableNames = $tableObj->names();
		print '<pre>';
		print_r($tableNames);
		print '</pre>';
		
		// IMP
		// ADDING SOME ROWS TO HBASE
		$userTblObj = $tableObj->usersInfo;

		$row1 = $userTblObj->row('user_1');
		
		print '<pre>';
		print_r($row1);
		print '</pre>';
		
		/*
		$row1->put('metaData:x','metaX');
		$row1->put('metaData:y','metaY');
		$row1->put('basicInfo:fname','FirstName');
		$row1->put('basicInfo:lname','LastName');
		*/
		
		// IMG
		// GETTING THE ROW COLUMN VALUE
		print_r($row1->get('metaData:y'));
		
		print '<pre>';
		print_r($row1->getFullRow());
		print '</pre>';
		
		// DELETING THE TABLE
		//$tableObj->delete('usersInfo');

	}
	catch(Exception $e)
	{
		print $e->getMessage();
		exit;
	}

?>