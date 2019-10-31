<?php
$host = "localhost";
$username = "root";
$password = "";
$db = "pedestrian_counter";

$mysqli = new mysqli($host, $username, $password, $db);

if ($mysqli->connect_error){
	die("Connection falied: " . $mysqli->connect_error);
}
?>