<?php
if (file_exists('wp-config.php')) {
	require_once('wp-config.php');

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$mysqli -> query('DELETE FROM '.$table_prefix."posts WHERE post_status = 'inherit' AND post_type = ‘attachment’") or die ('Nie udalo sie wykonac zapytania do DB!');
	echo 'Pomyslnie skasowano '.$mysqli -> affected_rows.' rewizji';
}
else {
	echo 'Nie znaleziono pliku konfiguracyjnego WordPressa';
}