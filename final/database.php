<?php
 
$servername = "lrgs.ftsm.ukm.my";
$username = "a193602";
$password = "smallorangesheep";
$dbname = "a193602";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
 
?>