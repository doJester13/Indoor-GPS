<?php
 
	require_once 'include/DB_Functions.php';
	$db = new DB_Functions();

	$fingerprint = $db->randomEntry(100,200);

	echo $fingerprint . "<br>";

	
	 
	if (1) {


	    //lest avg error use the db entry that have at least m matches
	    $m = 1;

	    $man = $_GET['man'];
	    $prod = $_GET['prod'];
	    //$tabS = $_GET['s'];
	    //$tabF = $_GET['f'];

		/*if(strcmp($man, "all") == 0 ){
			$man = "*";
			$prod = "*";
		}*/
		$minS = "minsurveys";
		$minF = "minfingerprints";
		$avgS = "surveysmiddle";
		$avgF = "fingerprintsmiddle";
		$maxS = "maxsurveys";
		$maxF = "maxfingerprints";


		echo "MIN <br>";
		//--------------------ALL
		echo "ALL <br>";
	    $maj = $db->majorityRuleD($fingerprint, "all", "all", $minS, $minF, 0);


	    $weight = $db->weightedMajorityRuleD($fingerprint, "all", "all", $minS, $minF, 0);


	    $lae = $db->leastAvgErrorD($fingerprint, $m , "all", "all", $minS, $minF, 0);

		//--------------------DEVICE
		echo "SINGLE DEVICE <br>";
	    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $minS, $minF, 0);


	    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $minS, $minF, 0);


	    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $minS, $minF, 0);

	    		//--------------------DEVICE DIFFERENT
	    echo "ALL - SINGLE DEVICE<br>";
	    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $minS, $minF, 1);


	    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $minS, $minF, 1);


	    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $minS, $minF, 1);
	    echo "*****************************************************<br>";


	    echo "AVG <br>";
		//--------------------ALL
		echo "ALL <br>";
	    $maj = $db->majorityRuleD($fingerprint, "all", "all", $avgS, $avgF, 0);


	    $weight = $db->weightedMajorityRuleD($fingerprint, "all", "all", $avgS, $avgF, 0);


	    $lae = $db->leastAvgErrorD($fingerprint, $m , "all", "all", $avgS, $avgF, 0);

		//--------------------DEVICE
		echo "SINGLE DEVICE <br>";
	    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $avgS, $avgF, 0);


	    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $avgS, $avgF, 0);


	    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $avgS, $avgF, 0);

	    		//--------------------DEVICE DIFFERENT
	    echo "ALL - SINGLE DEVICE<br>";
	    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $avgS, $avgF, 1);


	    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $avgS, $avgF, 1);


	    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $avgS, $avgF, 1);
	    echo "*****************************************************<br>";


	    echo "MAX <br>";
		//--------------------ALL
		echo "ALL <br>";
	    $maj = $db->majorityRuleD($fingerprint, "all", "all", $maxS, $maxF, 0);


	    $weight = $db->weightedMajorityRuleD($fingerprint, "all", "all", $maxS, $maxF, 0);


	    $lae = $db->leastAvgErrorD($fingerprint, $m , "all", "all", $maxS, $maxF, 0);

		//--------------------DEVICE
		echo "SINGLE DEVICE <br>";
	    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $maxS, $maxF, 0);


	    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $maxS, $maxF, 0);


	    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $maxS, $maxF, 0);

	    		//--------------------DEVICE DIFFERENT
	    echo "ALL - SINGLE DEVICE<br>";
	    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $maxS, $maxF, 1);


	    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $maxS, $maxF, 1);


	    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $maxS, $maxF, 1);
	    echo "*****************************************************<br>";

	} else {

	}
?>