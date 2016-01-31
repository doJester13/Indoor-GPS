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
 
 
}
 
?>