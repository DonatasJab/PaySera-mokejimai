<?php
$dbhost = "localhost";
$dbname = "";
$dbuser = "root";
$dbpass = "";

try {
	$conn = new PDO("mysql:host=".$dbhost.";dbname=".$dbname.";charset=UTF8", $dbuser, $dbpass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo "KLAIDA: " . $e->getMessage();
}

$stmt = $conn->prepare("SELECT * FROM settings");
$stmt->execute();
$paysera = $stmt->fetch(PDO::FETCH_ASSOC);