<?php
// dbHandler.php 

echo "Go for launch.<br>";
require_once ("../db_touch/db_credentials.php");

class DbHandler{
    function do_foo(){
        echo "Doing foo."; 
        }
        
        
    // Send a SQL query to the DB.
    function query($query){
        // Create & check connection.
        $conn = new mysqli(SERVER, UID, PASS, DB);
        if ($conn->connect_error) 
            {die("Connection failed: " . $conn->connect_error);}
        $result = $conn->query($query);
        if(!$result) {die($conn->error);}
        else {return $result;}
        }
        

    // Verbose query.
    function queryv($query){
        // Create & check connection.
        echo "<br><b>Running query:</b><div style='border:solid'>";
        $conn = new mysqli(SERVER, UID, PASS, DB);
        if ($conn->connect_error) 
            {die("Connection failed: " . $conn->connect_error);}
        else 
            {echo "Connected successfully...\n<br>";}
        $result = $conn->query($query);
        //Run the query.
        if(!$result) {
            die($conn->error);
            }
        else {
            echo "<p>Running:</p><pre>" . htmlspecialchars($query) . "</pre><p>...Executed.</p></div>";
            return $result;
            }
        }
}


        
        
        
?>
  