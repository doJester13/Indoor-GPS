<?php
 
class DB_Functions {
 
    private $conn;
 
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

    public function avg(){

        $arr = array(
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

        $arr1 = array(
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
        
        foreach ($arr1 as $y) {
            $tag = mysqli_real_escape_string($this->conn, $y); 
            echo $tag ."<br>";
            foreach ($arr as $x) {
                $media = 0;
                $bssid =mysqli_real_escape_string($this->conn,$x);
                $sql = "SELECT bssid, power FROM surveys AS s, fingerprints AS f WHERE s.id = f.fid AND tag = '$tag' AND bssid = '$bssid'";
                //echo $sql . "<br>";
                $result = mysqli_query($this->conn,$sql);
                if (mysqli_num_rows($result)>0) {
                    $tot = 0;

                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                   // echo "bssid: " . $row["bssid"]. " - power: " . $row["power"]."<br>";
                        $power = substr($row["power"], 0, -3);
                        $tot = $tot + $power;
                    }
                    $media = $tot / mysqli_num_rows($result);
                    echo  $bssid . "    " ."=". $media ."<br>";
                } else {
                    echo  $bssid . "    " . "= " . "0 results" ."<br>";
                }
                echo "-------------------" ."<br>"; 
            }
            echo "******************************************" ."<br>";
        }
    }

    public function majorityRule($fingerprint) {
        
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

}
 
?>