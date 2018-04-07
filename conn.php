<?php
$connection = mysqli_connect("localhost","root","","simulasi_db");

if($connection -> connect_error)
	die("Connection Failed : ".$connection -> connect_error);

?>