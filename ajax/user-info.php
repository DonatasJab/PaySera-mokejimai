<?php

include("../includes/connection.php");

$stmt = $conn->prepare("SELECT * FROM sms WHERE nick = :nick");
$stmt->execute(array(':nick' => $_POST['gamenick']));
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if($stmt->rowCount() == 1 && $data['expires'] >= time()) {
	echo "<b><i>".$data['nick']."</i></b> turi užsisakęs <b><i>".$data['keyword']."</i></b> paslaugą<br>";
	echo "Paslauga galioja iki: <i>".date('Y-m-d H:i', $data['expires'])."</i>";
} else {
	echo "<b><i>".$_POST['gamenick']."</i></b> neturi aktyvių paslaugų";
}