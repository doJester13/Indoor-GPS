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
        $stmt = $this->conn->prepare("INSERT INTO surveys(manufact, prod, dt, lat, lon, fingerprint, tag) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $man, $prod, $dt, $lat, $lon, $fingerprint, $tag);
        $result = $stmt->execute();
        $stmt->close();
 
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