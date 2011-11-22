Row operations: Create, update and list table rows in HBase
===========================================================

Those operations are handled by the class "PopHbaseTable" which can be retrieved from an instance of "PopHbase" through the "tables" property.

Grab an instance of "PopHbaseRow"
---------------------------------

	$row = $hbase->tables->my_table->my_row;
	assert($row instanceof PopHbaseTableRow);
	assert($hbase->tables->my_table->my_row !== $row);

Retrieve a column value from HBase
----------------------------------

Getting a value from HBase is achieved through the "PopHbaseTableRow->get" method and its magick shortcut. It takes a single argument, the column name, in the form of the column family followed by colons followed by the column name.

	$row = $hbase->tables->my_table->my_row;
	
	// Using the "get" method
	$row->get('my_column_family:my_column');
	
	// Using the magick method
	$row->{'my_column_family:my_column'};

Insert and update of a row and a column value
---------------------------------------------

In HBase, inserting and updating operation of rows and column values are the same operation.

	$row = $hbase->tables->my_table->my_new_or_existing_row;
	$row->put('my_column_family:my_column','my_value');

Delete a row from an HBase table
--------------------------------

Row removal is achieved by calling the "PopHbaseRow->delete" method without any argument.

	$row = $hbase->tables->my_table->my_row;
	$row->delete();
