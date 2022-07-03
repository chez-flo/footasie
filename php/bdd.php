<?php 
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die("Impossible de se connecter au serveur \"$host\"");
	//mysqli_select_db(DB_DATABASE) or die("Impossible de se connecter à la base de donnees \"$bdd\"");
	$mysqli->set_charset("utf8");
?>