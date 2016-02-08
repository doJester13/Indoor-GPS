<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['fingerprint'])) {

    $fingerprint = $_POST['fingerprint'];


    $maj = $db->majorityRule($fingerprint);
    if ($maj) { 

    } else {

    }

} else {

}
?>