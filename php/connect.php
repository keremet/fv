<?php
try {
	$db = new PDO('mysql:host=localhost;dbname=cp516214_lapka', 'cp516214_true', '1q2w3e4r');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
	$db->query("SET lc_time_names = 'ru_RU'");
	$db->query("SET NAMES 'utf8'");
} catch (PDOException $e) {
	print "Connection error, das hat nicht geklabt :(<br/>";
        echo $e;
	die();
}