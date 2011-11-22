Pop HBase: HBase PHP REST client using Stargate
===============================================

Pop HBase is a PHP client to an HBase server. It use JSON Rest and the Curl extension to communicate with the HBase server and provides a very simple and convenient interface.

Its features include:

-   *Server information:* version, status and cluster information
-   *Table manipulations:* list, create, modify and delete
-   *Row access:* Create, update, retrieve

The source code has been tested agains PHP 5.2.x and PHP 5.3.x versions, however, it should also run on PHP 5.1.x versions.

By default, the connection is handled by the Curl base implementation ("PopHbaseConnectionCurl") and it requires PHP to be compiled with Curl support. A socket based implementation is also provided but less stable at the time.

Getting the source
------------------

	git clone http://github.com/pop/pop_hbase.git