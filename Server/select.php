<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 

    // create a new survey
    /*$min = $db->min();
    $max = $db->max();
    $mid = $db->avg();*/
    $dev = $db->deviceType();

?>