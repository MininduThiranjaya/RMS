<?php

	$server = "localhost";
	$user = "root";
	$pass = "";
	$database = "result_system";
	
	//create the connection with the database
	$conn = new mysqli($server, $user, $pass, $database);
	
	if(!$conn) {
		die("Connection Failed..! ");
	}
?>