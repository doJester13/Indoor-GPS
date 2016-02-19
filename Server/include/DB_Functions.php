<?php
 
class DB_Functions {
 
    private $conn;

    private $arr = array(
        "00:3a:99:5d:1d:c1",
        "00:21:96:6c:7d:48",
        "00:3a:99:5d:1d:c0",
        "ec:35:86:36:46:20",
        "dc:a5:f4:ff:17:62",
        "00:3a:99:2c:76:71",
        "00:16:b6:d9:d1:8e",
        "dc:a5:f4:ff:17:61",
        "dc:a5:f4:ff:1d:0f",
        "dc:a5:f4:ff:1d:00",
        "00:3a:99:2c:5f:41",
        "00:3a:99:2c:5f:40",
        "dc:a5:f4:ff:1d:0e",
        "dc:a5:f4:ff:1d:01",
        "00:3a:99:5c:ff:f0",
        "00:3a:99:5c:ff:f1",
        "00:3a:99:48:18:21",
        "00:3a:99:48:18:20"
    );

    private $arr1 = array(
        "laboratorio wireless - 6",
        "laboratorio wireless - 1",
        "laboratorio wireless - 2",
        "laboratorio wireless - 3",
        "laboratorio wireless - 4",
        "laboratorio wireless - 5",
        "atrio -2",
        "atrio - 1",
        "atrio - 5",
        "atrio - 4",
        "atrio - 3",
        "bagno - 2",
        "bagno - 1",
        "atrio - 6",
        "atrio - 7",
        "lab a - 1",
        "lab a - 10",
        "lab a - 9",
        "lab a - 2",
        "lab a - 8",
        "lab a - 3",
        "lab a - 7",
        "lab a - 4",
        "lab a - 6",
        "lab a - 5",
        "lab b - 8",
        "lab b - 1",
        "lab b - 2",
        "lab b - 7",
        "lab b - 4",
        "lab b - 3",
        "lab b - 6",
        "lab b - 5",
        "cr - 1",
        "cr - 2",
        "cr - 3",
        "cr - 4",
        "cr - 5",
        "cr - 6",
        "sem 2 - 1",
        "sem 2 - 2",
        "sem 2 - 6",
        "sem 2 - 5",
        "sem 2 - 4",
        "sem 2 - 3"
    );
 
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    // destructor
    function __destruct() {
         
    }
 
    /**
     * Storing new survey
     */
    public function storeSurv($man, $prod, $dt, $lat, $lon, $fingerprint, $tag) {
        

        /*
        INSERT INTO foo (auto,text)
    VALUES(NULL,'text');         # generate ID by inserting NULL
INSERT INTO foo2 (id,text)
    VALUES(LAST_INSERT_ID(),'text');  # use ID in second table*/

        $stmt = $this->conn->prepare("INSERT INTO surveys(manufact, prod, dt, lat, lon, fingerprint, tag) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $man, $prod, $dt, $lat, $lon, $fingerprint, $tag);
        $result = $stmt->execute();
        $stmt->close();
        //printf ("New Record has id %d.\n", $this->conn->insert_id);

        $fid = $this->conn->insert_id;
        $rel = explode(",", $fingerprint);
        $len = count($rel);

        for($i = 0; $i<$len; $i++){
            $input = explode(": ", $rel[$i]);
            $stmt = $this->conn->prepare("INSERT INTO fingerprints(fid, bssid, power) VALUES(?, ?, ?)");
            $stmt->bind_param("iss", $fid, $input[0], $input[1]);
            $result = $stmt->execute();
            $stmt->close();
        }





 
        // check for successful store
        if ($result) {
            echo $result;
            return true;
        } else {
            return false;
        }
    }


    public function randomEntry($x, $y){
        $rid = rand($x, $y);
        $sql = "SELECT * FROM surveys WHERE id = '$rid'";
        $res = mysqli_query($this->conn, $sql);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_array($res,MYSQLI_ASSOC)) {
                $fin = $row["fingerprint"];
                return $fin;
            }
        }
    }

    public function ap(){
          //$device = array();
        $sql = " SELECT DISTINCT bssid FROM fingerprints ";
        $result = mysqli_query($this->conn,$sql);
        if (mysqli_num_rows($result)>0) {
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        //$device[$row["manufact"]] = $row["prod"];
                echo $row["bssid"] . "<br>";
                        //$this->min($row["manufact"], $row["prod"]);
                        //s$this->avg($row["manufact"], $row["prod"]);
                        //$this->max($row["manufact"], $row["prod"]);
            }
        }
        //print_r($device);
    }

    public function deviceType(){
        //$device = array();
        $sql = " SELECT DISTINCT manufact, prod FROM surveys ";
        $result = mysqli_query($this->conn,$sql);
        if (mysqli_num_rows($result)>0) {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        //$device[$row["manufact"]] = $row["prod"];
                        //echo $row["manufact"] ." ". $row["prod"] . "<br>";
                        //$this->min($row["manufact"], $row["prod"]);
                        //s$this->avg($row["manufact"], $row["prod"]);
                        //$this->max($row["manufact"], $row["prod"]);
                    }
        }
        //print_r($device);
    }

   

    public function avg($manufact, $prod){

        
        $conta = 1;
        $lat = "";
        $lon = "";
        
        foreach ($this->arr1 as $y) {
            $tag = mysqli_real_escape_string($this->conn, $y); 
            echo $tag ."<br>";
            foreach ($this->arr  as $x) {
                $media = 0;
                $bssid =mysqli_real_escape_string($this->conn,$x);
                $sql = "SELECT  s.lat, s.lon, bssid, power, manufact, prod  FROM surveys AS s, fingerprints AS f WHERE s.id = f.fid AND manufact = '$manufact' AND prod = '$prod'  AND tag = '$tag' AND bssid = '$bssid'";
                //echo $sql . "<br>";
                $result = mysqli_query($this->conn,$sql);
                if (mysqli_num_rows($result)>0) {
                    $tot = 0;

                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                   // echo "bssid: " . $row["bssid"]. " - power: " . $row["power"]."<br>";
                        $power = substr($row["power"], 0, -3);
                        $tot = $tot + $power;
                        $lat = $row["lat"];
                        $lon = $row["lon"];
                    }
                    $media = $tot / mysqli_num_rows($result);
                    echo  $bssid . "    " ."media =". $media ."<br>";

                    $stmt = $this->conn->prepare("INSERT INTO fingerprintsMiddle(fid, bssid, power) VALUES(?, ?, ?)");
                    $stmt->bind_param("dss", $conta, $bssid, $media);
                    $result = $stmt->execute();
                    $stmt->close();

                    

                } else {
                    echo  $bssid . "    " . "= " . "0 results" ."<br>";
                }
                echo "-------------------" ."<br>"; 
            }
            echo "******************************************" ."<br>";

            $st = $this->conn->prepare("INSERT INTO surveysmiddle(fid, manufact, prod, lat, lon, tag) VALUES(?, ?, ?, ?, ?, ?)");
            $st->bind_param("dsssss", $conta, $manufact, $prod, $lat, $lon, $tag);
            $result = $st->execute();
            $st->close();
            $conta  += 1;
            echo $lat . " " . $lon . " " . $tag . "<br>";
        }
        echo $conta;
    }


    public function min($manufact, $prod){

        $conta = 1;
        $lat = "";
        $lon = "";
        
        foreach ($this->arr1 as $y) {
            $tag = mysqli_real_escape_string($this->conn, $y); 
            echo $tag ."<br>";
            foreach ($this->arr  as $x) {
                
                $bssid =mysqli_real_escape_string($this->conn,$x);
                $sql = "SELECT  s.lat, s.lon, bssid, power FROM surveys AS s, fingerprints AS f WHERE s.id = f.fid AND manufact = '$manufact' AND prod = '$prod' AND tag = '$tag' AND bssid = '$bssid'";
                //echo $sql . "<br>";
                $result = mysqli_query($this->conn,$sql);
                if (mysqli_num_rows($result)>0) {
                    $tot = 0;
                    $minimo = 0;
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                   // echo "bssid: " . $row["bssid"]. " - power: " . $row["power"]."<br>";
                        $power = substr($row["power"], 0, -3);
                        $tot = $tot + $power;
                        $lat = $row["lat"];
                        $lon = $row["lon"];
                        if ($power < $minimo) {
                            $minimo = $power;
                        }
                    }
                    
                    echo  $bssid . "    " ." minimo =". $minimo ."<br>";

                    $stmt = $this->conn->prepare("INSERT INTO minfingerprints(fid, bssid, power) VALUES(?, ?, ?)");
                    $stmt->bind_param("dss", $conta, $bssid, $minimo);
                    $result = $stmt->execute();
                    $stmt->close();

                    

                } else {
                    echo  $bssid . "    " . "= " . "0 results" ."<br>";
                }
                echo "-------------------" ."<br>"; 
            }
            echo "******************************************" ."<br>";

            $st = $this->conn->prepare("INSERT INTO minsurveys(fid, manufact, prod, lat, lon, tag) VALUES(?, ?, ?, ?, ?, ?)");
            $st->bind_param("dsssss", $conta, $manufact, $prod, $lat, $lon, $tag);
            $result = $st->execute();
            $st->close();
            $conta  += 1;
            echo $lat . " " . $lon . " " . $tag . "<br>";
        }
        echo $conta;
    }


    public function max($manufact, $prod){

        $conta = 1;
        $lat = "";
        $lon = "";
        
        foreach ($this->arr1 as $y) {
            $tag = mysqli_real_escape_string($this->conn, $y); 
            echo $tag ."<br>";
            foreach ($this->arr  as $x) {
                
                $bssid =mysqli_real_escape_string($this->conn,$x);
                $sql = "SELECT  s.lat, s.lon, bssid, power FROM surveys AS s, fingerprints AS f WHERE s.id = f.fid AND manufact = '$manufact' AND prod = '$prod' AND tag = '$tag' AND bssid = '$bssid'";
                //echo $sql . "<br>";
                $result = mysqli_query($this->conn,$sql);
                if (mysqli_num_rows($result)>0) {
                    $tot = 0;
                    $max = -120;
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                   // echo "bssid: " . $row["bssid"]. " - power: " . $row["power"]."<br>";
                        $power = substr($row["power"], 0, -3);
                        $tot = $tot + $power;
                        $lat = $row["lat"];
                        $lon = $row["lon"];
                        if ($power > $max) {
                            $max = $power;
                        }
                    }
                    
                    echo  $bssid . "    " ." massimo =". $max ."<br>";

                    $stmt = $this->conn->prepare("INSERT INTO maxfingerprints(fid, bssid, power) VALUES(?, ?, ?)");
                    $stmt->bind_param("dss", $conta, $bssid, $max);
                    $result = $stmt->execute();
                    $stmt->close();

                    

                } else {
                    echo  $bssid . "    " . "= " . "0 results" ."<br>";
                }
                echo "-------------------" ."<br>"; 
            }
            echo "******************************************" ."<br>";

            $st = $this->conn->prepare("INSERT INTO maxsurveys(fid, manufact, prod, lat, lon, tag) VALUES(?, ?, ?, ?, ?, ?)");
            $st->bind_param("dsssss", $conta, $manufact, $prod, $lat, $lon, $tag);
            $result = $st->execute();
            $st->close();
            $conta  += 1;
            echo $lat . " " . $lon . " " . $tag . "<br>";
        }
        echo $conta;
    }



    public function majorityRule($fingerprint) {
        echo "majorityRule <br>";
        $rel = explode(",", $fingerprint);
        $len = count($rel);
        $sql = "SELECT s.id, s.lat, s.lon, COUNT(*) AS c FROM surveys AS s, fingerprints AS f WHERE s.id = f.fid AND (";

        for($i = 0; $i<$len; $i++){
            $input = explode(": ", $rel[$i]);
            $bssid = mysqli_real_escape_string($this->conn,$input[0]);
            $power = mysqli_real_escape_string($this->conn,$input[1]);
            $sql .= "(bssid = '$bssid' AND power = '$power') OR ";

        }
        $sql = substr($sql,0, -3);
        $sql .= ") GROUP BY s.id ORDER BY c DESC LIMIT 1";
        
        $result = mysqli_query($this->conn,$sql);
        if (mysqli_num_rows($result)>0) {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $id = $row["id"];
                        $count = $row["c"];
                        $lat =  $row["lat"];
                        $lon = $row["lon"];
                        echo $id . " " . $count ." / lat = ". $lat ." lon = ". $lon . "<br>" ;
                    }
                } else {
                    echo " 0 results <br>";
                }
        
        //echo $sql;
    }

    public function weightedMajorityRule($fingerprint) {
        echo "weightedMajorityRule <br>";
        $powersum = 0;
        
        $rel = explode(",", $fingerprint);
        $len = count($rel);
        $table = "SELECT s.id, COUNT(*) AS c FROM surveys AS s, fingerprints AS f WHERE s.id = f.fid AND (";

        for($i = 0; $i<$len; $i++){
            $input = explode(": ", $rel[$i]);
            $bssid = mysqli_real_escape_string($this->conn,$input[0]);
            $power = mysqli_real_escape_string($this->conn,$input[1]);
            $powersum = $powersum + substr($power, 0, -3);
            $table .= "(bssid = '$bssid' AND power = '$power') OR ";

        }
        $table = substr($table,0, -3);
        $table .= ") GROUP BY s.id";

        $sql = " SELECT * FROM (";
        $sql .= $table;
        $sql .= ") AS counts WHERE c = ( SELECT MAX(c) FROM (";
        $sql .= $table;
        $sql .= ") AS result )";

        $mindiff = abs($powersum);
        $minid = 0;
        $result = mysqli_query($this->conn,$sql);
        if (mysqli_num_rows($result)>0) {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $id = $row["id"];
                        /*$count = $row["c"];
                        $lat =  $row["lat"];
                        $lon = $row["lon"];
                        echo $id . " " .  "<br>" ;*/
                        $powersumThis = 0;
                        $request = "SELECT power FROM fingerprints WHERE fid = '$id' " ;
                        
                        $resReq = mysqli_query($this->conn,$request);
                        if(mysqli_num_rows($resReq)> 0 ){
                            while ($r = mysqli_fetch_array($resReq, MYSQLI_ASSOC)) {
                                $powersumThis = $powersumThis + substr($r["power"], 0, -3);
                            }
                           $diff = abs($powersum - $powersumThis);

                            if($mindiff> $diff){
                                $mindiff = $diff;
                                $minid = $id;

                            } 
                        }
                        
                        //echo abs($powersum - $powersumThis) . "<br>";
                    }
        } else {
                    echo " 0 results <br>";
        }

        if($minid > 0){
            $newRequest = " SELECT lat, lon FROM surveys WHERE id = '$minid' ";
            $newResult = mysqli_query($this->conn,$newRequest);
            if (mysqli_num_rows($newResult) > 0) {
                while ($row = mysqli_fetch_array($newResult,MYSQLI_ASSOC)) {
                    $lat = $row["lat"];
                    $lon = $row["lon"];
                    echo "$lat" . " " . $lon . "<br>"; 
                }
            }
        }

        //echo $powersum . "<br>";
        //echo $minid;
        //echo $sql;

    }


    public function leastAvgError($fingerprint, $m){
        echo "leastAvgError <br>";
        $ap = array(); 
        //$po = array();

        $sql = "SELECT * FROM surveys AS s, fingerprints AS f WHERE s.id = f.fid AND (";

        $rel = explode(",", $fingerprint);
        $len = count($rel);
        $sum = 0;
        for($i = 0; $i<$len; $i++){
            $input = explode(": ", $rel[$i]);
            $bssid = mysqli_real_escape_string($this->conn,$input[0]);
            $power = mysqli_real_escape_string($this->conn,$input[1]);
            $ap[$bssid] =  substr($power, 0, -3);
            $sum = $sum + substr($power, 0, -3);
            $sql .= "(bssid = '$bssid') OR ";
        }

        $sql = substr($sql,0, -3);
        $m = $m-1;
        $sql .= "  ) GROUP BY s.id HAVING COUNT(*) > '$m' ";

        //echo $sql;

        $minid = 0;
        $avg = abs($sum);

        $result = mysqli_query($this->conn,$sql);
        if (mysqli_num_rows($result)>0) {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $id = $row["fid"];
                        //righe
                        $powersumThis = 0;
                        $powersum = 0;
                        $cont = 0;
                        $request = "SELECT bssid, power FROM fingerprints WHERE fid = '$id' " ;
                        $resReq = mysqli_query($this->conn,$request);
                        if(mysqli_num_rows($resReq)> 0 ){
                            while ($r = mysqli_fetch_array($resReq, MYSQLI_ASSOC)) {
                                if(array_key_exists($r["bssid"], $ap)){  
                                    $powersum = $powersum + array_search($r["bssid"], $ap); //fingerprint
                                    //echo $powersum;
                                    $powersumThis = $powersumThis + substr($r["power"], 0, -3); //db
                                    //echo " " . $powersumThis;
                                    $cont += 1;
                                }
                            } 
                            if($cont > 0){       
                                $avg1 = (abs($powersum - $powersumThis) ) / $cont;
                                if($avg1 < $avg ){
                                    $avg = $avg1;
                                    $minid = $id;
                                }
                            }
                            

                        }
                        
                        //echo abs($powersum - $powersumThis) . "<br>";
                    }
        } else {
                    echo " 0 results <br>";
        }

        if($minid > 0){
            $newRequest = " SELECT lat, lon FROM surveys WHERE id = '$minid' ";
            $newResult = mysqli_query($this->conn,$newRequest);
            if (mysqli_num_rows($newResult) > 0) {
                while ($row = mysqli_fetch_array($newResult,MYSQLI_ASSOC)) {
                    $lat = $row["lat"];
                    $lon = $row["lon"];
                    echo "$lat" . " " . $lon . "<br>"; 
                }
            }
        }

    }

    /*-------------------------------------------------------------------------------------------------------------------------------------
    * -------------------------------------------------------------------------------------------------------------------------------------  
    *----------------------------------------------------------DEVICE FUNCTIONS------------------------------------------------------------
    -------------------------------------------------------------------------------------------------------------------------------------*/

    //-------------------------------------------------------------------------------------------------------------------------------------

        public function majorityRuleD($fingerprint, $man, $prod, $tabS, $tabF, $diffferent) {
        echo "majorityRuleD " . $man . " " . $prod . " " . $tabS . " " . $tabF ."<br>";



        $rel = explode(",", $fingerprint);
        $len = count($rel);
        if($diffferent == 1){
            $sql = "SELECT s.id, s.lat, s.lon, COUNT(*) AS c FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND s.manufact != '$man' AND s.prod != '$prod' AND (";

        }else{

            if(strcmp($man, "all") == 0 ){
                $sql = "SELECT s.id, s.lat, s.lon, COUNT(*) AS c FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND (";
            }else{
                        $sql = "SELECT s.id, s.lat, s.lon, COUNT(*) AS c FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND s.manufact = '$man' AND s.prod = '$prod' AND (";  
            }
  
        }

        

        for($i = 0; $i<$len; $i++){
            $input = explode(": ", $rel[$i]);
            $bssid = mysqli_real_escape_string($this->conn,$input[0]);
            $power = mysqli_real_escape_string($this->conn,$input[1]);
            $sql .= "(bssid = '$bssid' AND power = '$power') OR ";

        }
        $sql = substr($sql,0, -3);
        $sql .= ") GROUP BY s.id ORDER BY c DESC LIMIT 1";
        
        $result = mysqli_query($this->conn,$sql);
        if (mysqli_num_rows($result)>0) {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $id = $row["id"];
                        $count = $row["c"];
                        $lat =  $row["lat"];
                        $lon = $row["lon"];
                        echo $id . " " . $count ." / lat = ". $lat ." lon = ". $lon . "<br>" ;
                    }
                } else {
                    echo " 0 results <br>";
                }
        
        //echo $sql;
    }

    public function weightedMajorityRuleD($fingerprint, $man, $prod, $tabS, $tabF, $diffferent) {
        echo "weightedMajorityRuleD " . $man . " " . $prod . " " . $tabS . " " . $tabF ."<br>";
        $powersum = 0;
        
        $rel = explode(",", $fingerprint);
        $len = count($rel);
        if($diffferent == 1){
            $table = "SELECT s.fid, COUNT(*) AS c FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND s.manufact != '$man' AND s.prod != '$prod' AND (";

        }else{

            if(strcmp($man, "all") == 0 ){
                $table = "SELECT s.fid, COUNT(*) AS c FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND (";
            }else{
                $table = "SELECT s.fid, COUNT(*) AS c FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND s.manufact = '$man' AND s.prod = '$prod' AND (";  
            }

        }

        //$table = "SELECT s.fid, COUNT(*) AS c FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND s.manufact = '$man' AND s.prod = '$prod' AND (";

        for($i = 0; $i<$len; $i++){
            $input = explode(": ", $rel[$i]);
            $bssid = mysqli_real_escape_string($this->conn,$input[0]);
            $power = mysqli_real_escape_string($this->conn,$input[1]);
            $powersum = $powersum + substr($power, 0, -3);
            $table .= "(bssid = '$bssid' AND power = '$power') OR ";

        }
        $table = substr($table,0, -3);
        $table .= ") GROUP BY s.id";

        $sql = " SELECT * FROM (";
        $sql .= $table;
        $sql .= ") AS counts WHERE c = ( SELECT MAX(c) FROM (";
        $sql .= $table;
        $sql .= ") AS result )";

        $mindiff = abs($powersum);
        $minfid = 0;
        $result = mysqli_query($this->conn,$sql);
        if (mysqli_num_rows($result)>0) {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $fid = $row["fid"];
                        /*$count = $row["c"];
                        $lat =  $row["lat"];
                        $lon = $row["lon"];
                        echo $id . " " .  "<br>" ;*/
                        $powersumThis = 0;
                        $request = "SELECT power FROM $tabF WHERE fid = '$fid' " ;
                        
                        $resReq = mysqli_query($this->conn,$request);
                        if(mysqli_num_rows($resReq)> 0 ){
                            while ($r = mysqli_fetch_array($resReq, MYSQLI_ASSOC)) {
                                $powersumThis = $powersumThis + substr($r["power"], 0, -3);
                            }
                           $diff = abs($powersum - $powersumThis);

                            if($mindiff> $diff){
                                $mindiff = $diff;
                                $minfid = $fid;

                            } 
                        }
                        
                        //echo abs($powersum - $powersumThis) . "<br>";
                    }
        } else {
                    echo " 0 results <br>";
        }

        if($minfid > 0){
            $newRequest = " SELECT lat, lon FROM $tabS WHERE fid = '$minfid' ";
            $newResult = mysqli_query($this->conn,$newRequest);
            if (mysqli_num_rows($newResult) > 0) {
                while ($row = mysqli_fetch_array($newResult,MYSQLI_ASSOC)) {
                    $lat = $row["lat"];
                    $lon = $row["lon"];
                    echo "$lat" . " " . $lon . "<br>"; 
                }
            }
        }

        //echo $powersum . "<br>";
        //echo $minid;
        //echo $sql;

    }


    public function leastAvgErrorD($fingerprint, $m, $man, $prod, $tabS, $tabF, $diffferent){
        echo "leastAvgErrorD " . $man . " " . $prod . " ". $tabS . " ". $tabF ."<br>";
        $ap = array(); 
        //$po = array();
        if($diffferent == 1){
            $sql = "SELECT * FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND s.manufact != '$man' AND s.prod != '$prod' AND (";

        }else{

            if(strcmp($man, "all") == 0 ){
                $sql = "SELECT * FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND (";
            }else{
                $sql = "SELECT * FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND s.manufact = '$man' AND s.prod = '$prod' AND (";  
            }

        }
        //$sql = "SELECT * FROM $tabS AS s, $tabF AS f WHERE s.fid = f.fid AND s.manufact = '$man' AND s.prod = '$prod' AND (";

        $rel = explode(",", $fingerprint);
        $len = count($rel);
        $sum = 0;
        for($i = 0; $i<$len; $i++){
            $input = explode(": ", $rel[$i]);
            $bssid = mysqli_real_escape_string($this->conn,$input[0]);
            $power = mysqli_real_escape_string($this->conn,$input[1]);
            $ap[$bssid] =  substr($power, 0, -3);
            $sum = $sum + substr($power, 0, -3);
            $sql .= "(bssid = '$bssid') OR ";
        }

        $sql = substr($sql,0, -3);
        $m = $m-1;
        $sql .= "  ) GROUP BY s.id HAVING COUNT(*) > '$m' ";

        $minfid = 0;
        $avg = abs($sum);

        $result = mysqli_query($this->conn,$sql);
        if (mysqli_num_rows($result)>0) {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $fid = $row["fid"];
                        //righe
                        $powersumThis = 0;
                        $powersum = 0;
                        $cont = 0;
                        $request = "SELECT bssid, power FROM $tabF WHERE fid = '$fid' " ;
                        $resReq = mysqli_query($this->conn,$request);
                        if(mysqli_num_rows($resReq)> 0 ){
                            while ($r = mysqli_fetch_array($resReq, MYSQLI_ASSOC)) {
                                if(array_key_exists($r["bssid"], $ap)){  
                                    $powersum = $powersum + array_search($r["bssid"], $ap); //fingerprint
                                    //echo $powersum;
                                    $powersumThis = $powersumThis + substr($r["power"], 0, -3); //db
                                    //echo " " . $powersumThis;
                                    $cont += 1;
                                }
                            } 
                            if($cont > 0){       
                                $avg1 = (abs($powersum - $powersumThis) ) / $cont;
                                if($avg1 < $avg ){
                                    $avg = $avg1;
                                    $minfid = $fid;
                                }
                            }
                            

                        }
                        
                        //echo abs($powersum - $powersumThis) . "<br>";
                    }
        } else {
                    echo " 0 results <br>";
        }

        if($minfid > 0){
            $newRequest = " SELECT lat, lon, tag FROM $tabS WHERE fid = '$minfid' ";
            $newResult = mysqli_query($this->conn,$newRequest);
            if (mysqli_num_rows($newResult) > 0) {
                while ($row = mysqli_fetch_array($newResult,MYSQLI_ASSOC)) {
                    $lat = $row["lat"];
                    $lon = $row["lon"];
                    $tag = $row["tag"];
                    echo "$lat" . " " . $lon . " " . $tag . "<br>"; 
                }
            }
        }

    }



}
 
?>