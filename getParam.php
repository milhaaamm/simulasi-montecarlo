<?php
include "conn.php";
if(isset($_GET["name"]))
{
	$paramname = $_GET["name"];
	$result = mysqli_query($connection,"SELECT * FROM parameter WHERE name='$paramname'");
	if($result)
	{
		$fetch = mysqli_fetch_array($result);
		echo $fetch["value"];
	}
	else
	{
		echo "falsename";
	}
}
else
{
	echo "noname";
}
?>