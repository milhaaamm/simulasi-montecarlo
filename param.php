<?php
include "conn.php";
$hargabt = is_numeric($_POST["bt_harga"]) ? $_POST["bt_harga"] : 10000;
$hargaas = is_numeric($_POST["as_harga"]) ? $_POST["as_harga"] : 12000;
$hargara = is_numeric($_POST["ra_harga"]) ? $_POST["ra_harga"] : 12500;
$mesinbt = is_numeric($_POST["bt_mesin"]) ? $_POST["bt_mesin"] : 250;
$mesinas = is_numeric($_POST["as_mesin"]) ? $_POST["as_mesin"] : 250;
$mesinra = is_numeric($_POST["ra_mesin"]) ? $_POST["ra_mesin"] : 250;

$sql = "UPDATE parameter SET value=$hargabt WHERE name='hargabt';
		UPDATE parameter SET value=$hargaas WHERE name='hargaas';
		UPDATE parameter SET value=$hargara WHERE name='hargara';
		UPDATE parameter SET value=$mesinbt WHERE name='mesinbt';
		UPDATE parameter SET value=$mesinas WHERE name='mesinas';
		UPDATE parameter SET value=$mesinra WHERE name='mesinra'; ";
if(!mysqli_multi_query($connection,$sql))
	echo mysqli_error($connection);
else
	echo "OK";
?>