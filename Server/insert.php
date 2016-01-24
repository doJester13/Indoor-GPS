<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['man']) && isset($_POST['prod']) && isset($_POST['dt'])&& isset($_POST['lat']) && isset($_POST['lon']) && isset($_POST['fingerprint'])) {
 
    // receiving the post params
    $man = $_POST['man'];
    $prod = $_POST['prod'];
    $dt = $_POST['dt'];
    $lat = $_POST['lat'];
    $lon = $_POST['lon'];
    $fingerprint = $_POST['fingerprint'];
 

    // create a new survey
    $survey = $db->storeSurv($man, $prod, $dt, $lat, $lon, $fingerprint);
    if ($survey) {
        // survey stored successfully
        /*$response["error"] = FALSE;
        echo json_encode($response);*/
    } else {
        // survey failed to store
        /*$response["error"] = TRUE;
        $response["error_msg"] = "Unknown error occurred in registration!";
        echo json_encode($response);*/
    }

} else {
    /*$response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);*/
}
?>