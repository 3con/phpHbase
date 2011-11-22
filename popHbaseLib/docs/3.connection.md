Connection: Connecting to an HBase server
=========================================

A new instance of "PopHbase" may be instantiated with an array containing the following properties:

-   *alive*
    boolean, default to true
    Http alive directive
-   *connection*
    string or boolean, default to "PopHbaseConnectionCurl"
    Class used to handle the connection.
-   *host*
	string, optional, default to "localhost"
	Domain or IP of the HBase Stargate server
-   *port*
	string or int, optional, default to "8080"
	Port of the HBase Stargate server
-   *verbose*
    boolean or string, optional, default to "false", used by "PopHbaseConnectionCurl"
    Outpout verbose information to stdout (or a file if value is a string) 
    about HTTP traffic

The library is lazy, meaning the connection to the server will be opened only when it is really required.

Creating a new connection
-------------------------

No parameter, all parameters being optional:

	$hbase = new PopHbase();
	
With user defined configuration:

	$hbase = new PopHbase(array(
		"host" => "127.0.0.1",
		"port" => "8080",
	));