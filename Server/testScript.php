<?php
 
	require_once 'include/DB_Functions.php';
	$db = new DB_Functions();
	$maxId = $db->maxID();
	$c = $_GET['c'];
	$myfile = fopen("data.csv", "w");

	echo"maj(MIN, ALL),weight(MIN, ALL),least(MIN, ALL),maj(MIN, DEV),weight(MIN, DEV),least(MIN, DEV),maj(MIN, ALL-D),weight(MIN, ALL-D),least(MIN, ALL-D),
			maj(AVG, ALL),weight(AVG, ALL),least(AVG, ALL),maj(AVG, DEV),weight(AVG, DEV),least(AVG, DEV),maj(AVG, ALL-D),weight(AVG, ALL-D),least(AVG, ALL-D),
			maj(MAX, ALL),weight(MAX, ALL),least(MAX, ALL),maj(MAX, DEV),weight(MAX, DEV),least(MAX, DEV),maj(MAX, ALL-D),weight(MAX, ALL-D),least(MAX, ALL-D)";

			echo"<br>";

	function singleTest(){

		global $db, $maxId, $myfile;

		$input = $db->randomEntry(100, $maxId);
		$fingerprint = $input[0];
		$tagInput = $input[1];
		//echo $fingerprint . "<br>";
		//$tagInput = $db->getTag($);
		echo $tagInput . ", ";
		fwrite($myfile, $tagInput . ", ");
		if (1) {


		    //lest avg error use the db entry that have at least m matches
		    $m = 13;

		    $man = $_GET['man'];
		    $prod = $_GET['prod'];
		    //$tabS = $_GET['s'];
		    //$tabF = $_GET['f'];

			/*if(strcmp($man, "all") == 0 ){
				$man = "*";
				$prod = "*";
			}*/
			$minS = "surveysmin";
			$minF = "fingerprintsmin";
			$avgS = "surveysavg";
			$avgF = "fingerprintsavg";
			$maxS = "surveysmax";
			$maxF = "fingerprintsmax";


			//echo "MIN <br>";
			//--------------------ALL
			//cho "ALL <br>";
		    $maj = $db->majorityRuleD($fingerprint, "all", "all", $minS, $minF, 0);
		    echo $maj . ", ";
		    fwrite($myfile, $maj . ", ");

		    $weight = $db->weightedMajorityRuleD($fingerprint, "all", "all", $minS, $minF, 0);
		    echo $weight . ", ";
		    fwrite($myfile, $weight . ", ");

		    $lae = $db->leastAvgErrorD($fingerprint, $m , "all", "all", $minS, $minF, 0);
		    echo $lae . ", ";
		    fwrite($myfile, $lae . ", ");
			//--------------------DEVICE
			//echo "SINGLE DEVICE <br>";
		    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $minS, $minF, 0);
		    echo $maj . ", ";
		    fwrite($myfile, $maj . ", ");

		    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $minS, $minF, 0);
		    echo $weight . ", ";
		    fwrite($myfile, $weight . ", ");

		    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $minS, $minF, 0);
		    echo $lae . ", ";
		    fwrite($myfile, $lae . ", ");
		    		//--------------------DEVICE DIFFERENT
		    //echo "ALL - SINGLE DEVICE<br>";
		    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $minS, $minF, 1);
		    echo $maj . ", ";
		    fwrite($myfile, $maj . ", ");

		    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $minS, $minF, 1);
		    echo $weight . ", ";
		    fwrite($myfile, $weight . ", ");

		    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $minS, $minF, 1);
		    echo $lae . ", ";
		    fwrite($myfile, $lae . ", ");
		    //echo "*****************************************************<br>";


		    //echo "AVG <br>";
			//--------------------ALL
			//echo "ALL <br>";
		    $maj = $db->majorityRuleD($fingerprint, "all", "all", $avgS, $avgF, 0);
		    echo $maj . ", ";
		    fwrite($myfile, $maj . ", ");

		    $weight = $db->weightedMajorityRuleD($fingerprint, "all", "all", $avgS, $avgF, 0);
		    echo $weight . ", ";
		    fwrite($myfile, $weight . ", ");

		    $lae = $db->leastAvgErrorD($fingerprint, $m , "all", "all", $avgS, $avgF, 0);
		    echo $lae . ", ";
		    fwrite($myfile, $lae . ", ");

			//--------------------DEVICE
			//echo "SINGLE DEVICE <br>";
		    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $avgS, $avgF, 0);
		    echo $maj . ", ";
		    fwrite($myfile, $maj . ", ");

			$weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $avgS, $avgF, 0);
		    echo $weight . ", ";
		    fwrite($myfile, $weight . ", ");

			$lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $avgS, $avgF, 0);
			echo $lae . ", ";
			fwrite($myfile, $lae . ", ");

		    		//--------------------DEVICE DIFFERENT
		    //echo "ALL - SINGLE DEVICE<br>";
		    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $avgS, $avgF, 1);
		    echo $maj . ", ";
		    fwrite($myfile, $maj . ", ");

		    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $avgS, $avgF, 1);
		    echo $weight . ", ";
		    fwrite($myfile, $weight . ", ");

		    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $avgS, $avgF, 1);
		    echo $lae . ", ";
		    fwrite($myfile, $lae . ", ");

		    //echo "*****************************************************<br>";


		    //echo "MAX <br>";
			//--------------------ALL
			//echo "ALL <br>";
		    $maj = $db->majorityRuleD($fingerprint, "all", "all", $maxS, $maxF, 0);
		    echo $maj . ", ";
		    fwrite($myfile, $maj . ", ");

		    $weight = $db->weightedMajorityRuleD($fingerprint, "all", "all", $maxS, $maxF, 0);
		    echo $weight . ", ";
		    fwrite($myfile, $weight . ", ");

		    $lae = $db->leastAvgErrorD($fingerprint, $m , "all", "all", $maxS, $maxF, 0);
		    echo $lae . ", ";
		    fwrite($myfile, $lae . ", ");

			//--------------------DEVICE
			//echo "SINGLE DEVICE <br>";
		    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $maxS, $maxF, 0);
		    echo $maj . ", ";
		    fwrite($myfile, $maj . ", ");

		    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $maxS, $maxF, 0);
		    echo $weight . ", ";
		    fwrite($myfile, $weight . ", ");

		    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $maxS, $maxF, 0);
		    echo $lae . ", ";
		    fwrite($myfile, $lae . ", ");
		    		//--------------------DEVICE DIFFERENT
		    //echo "ALL - SINGLE DEVICE<br>";
		    $maj = $db->majorityRuleD($fingerprint, $man, $prod, $maxS, $maxF, 1);
		    echo $maj . ", ";
		    fwrite($myfile, $maj . ", ");

		    $weight = $db->weightedMajorityRuleD($fingerprint, $man, $prod, $maxS, $maxF, 1);
		    echo $weight . ", ";
		    fwrite($myfile, $weight . ", ");

		    $lae = $db->leastAvgErrorD($fingerprint, $m , $man, $prod, $maxS, $maxF, 1);
		    echo $lae;
		    fwrite($myfile, $lae);

		    //echo "*****************************************************<br>";

		} else {

		}
	}


	for($i = 0;$i<$c;$i++){
		
		singleTest();
		echo "<br>";
		fwrite($myfile, "\n");
	}
	fclose($myfile);
?>
