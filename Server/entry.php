<?php
 
	require_once 'include/DB_Functions.php';
	$db = new DB_Functions();
	 
	// json response array
	$response = array("error" => FALSE);
	 
	if (isset($_POST['fingerprint'])) {

	    $fingerprint = $_POST['fingerprint'];
	    //lest avg error use the db entry that have at least m matches
	    $m = 5;

	    $man = " ";
	    $prod = " ";



	    $maj = $db->majorityRule($fingerprint);
	    if ($maj) { 

	    } else {

	    }

	    $weight = $db->weightedMajorityRule($fingerprint);
	    if ($weight) { 

	    } else {

	    }

	    $lae = $db->leastAvgError($fingerprint, $m);
	    if ($lae) { 

	    } else {

	    }


	    /*$majD = $db->majorityRuleD($fingerprint, $man, $prod);
	    if ($majD) { 

	    } else {

	    }

	    $weightD = $db->weightedMajorityRuleD($fingerprint, $man, $prod);
	    if ($weightD) { 

	    } else {

	    }

	    $laeD = $db->leastAvgErrorD($fingerprint, $m, $man, $prod);
	    if ($laeD) { 

	    } else {

	    }*/



	} else {

	}
?>