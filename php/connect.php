<?php
try {
	$db = new PDO('pgsql:dbname=orv;user=postgres');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	print "Connection error, das hat nicht geklabt :(<br/>";
        echo $e;
	die();
}

function doNull($v){
	return ($v!=='')?$v:null;
}
