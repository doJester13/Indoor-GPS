<?php
 
	require_once 'include/DB_Functions.php';
	$db = new DB_Functions();
	 
	// json response array
	$response = array("error" => FALSE);
	 
	$tag = "tag";

	$cor = $db->correct($tag, "atrio -2", "atrio - 2");//correggere anche nell'array
	$cor = $db->correct($tag, "ww", "laboratorio wireless -");
	$cor = $db->correct($tag, "tt", "atrio -");
	$cor = $db->correct($tag, "bb", "bagno -");
	$cor = $db->correct($tag, "xx", "lab b -");
	$cor = $db->correct($tag, "yy", "lab a -");
	$cor = $db->correct($tag, "cc", "cr -");
	



?>