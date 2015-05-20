<html>
<head>
<title>XML-RPC PHP Demo</title>
</head>
<body>
<h1>XML-RPC PHP Demo</h1>

<?php
	include 'lib/Client.class.php';
	include 'lib/Query.class.php';
	include 'lib/ResultSet.class.php';
	include 'lib/SimpleXMLResultSet.class.php';
	include 'lib/DOMResultSet.class.php';
	
	// these are the values the class will default to, so it is entirely possible to 
	// instantiate the class with no paramaters provided
	$connConfig = array(
		'protocol'=>'http',
		'user'=>'admin',
		'password'=>'alex',
		'host'=>'localhost',
		'port'=>'8080',
		'path'=>'/exist/xmlrpc'
	);
	// alternatively, you can specify the URI as a whole in the form
	// $connConfig = array('uri'=>'http://user:password@host:port/path');
	
	$conn = new \ExistDB\Client($connConfig);
	$conn->createCollection('music');
	$catalogAsSingleNode = simplexml_load_file('./xml/Faure-Introitus1.xml');
	$conn->storeDocument(
                'music/Faure-Introitus1.xml',
                $catalogAsSingleNode->asXML()
        );
?>
</body></html>
