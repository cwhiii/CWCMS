<html>
<head>
<title> CWCMS Playground </title>
<head>
    <style>
	table, th, td {
	    border: 1px solid black;
	    }
	th {
	    font-weight: bold;
	    }   
    </style>
<body>

<div style="padding: 2em; background:tan;">

    <div style="border:solid;">
	<form method='post' action='index.php' enctype='multipart/form-data'>
	    <textarea id="sql" name="inputSQL" type="textarea" rows="10" cols="50"> Coming soon! </textarea><br>
	    <input type='submit' value='Execute SQL'>
	</form>
    </div>
    
<?php

	 
	require ("db_credentials.php");
	echo "<br><br><br>". UID . "<br><br>";

    // Clear Error Log.
    $tmp = fopen("error_log", 'w') or die("Failed to open error log.");
    fwrite($tmp, "Cleared: " . date('Y-m-d H:i:s') . "\n\n") or die ("Could not clear error log.");
    fclose($tmp)  or die ("Could not close error log.");

    // $query = "CREATE TABLE test_table (id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY, name TEXT, more TEXT);";
    // query($query);
    //$query = "INSERT INTO test_table (name, more) VALUES('Elizabeth', 'Fantastic')";
    //query($query);

    /*
    echo "<br><br><div style='border:dashed'>";
    // tableDump("test_table");
    $pile = query("DESC test_table;");
    foreach($pile as $hill){
	echo "----ROW----<br>";
	foreach($hill as $stone){
	    echo "$stone <br>";
	    }
	}
	
    echo "</div>";
    */

    tableDump("test_table");
    
	    
    // Send a SQL query to the DB.
    function query($query){
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
	    echo "<p>Running '$query'</p><p>...Executed.</p></div>";
	    return $result;
	    }
	}
    

    // Pull all the contents of the given table, & display them. 
    function tableDump($aTable){
	$results = query("SELECT * FROM $aTable;");
	$cols = ["id","name","more"];
	
	// Get column names & numbers.
	$colNames = query("DESC $aTable");
	
	
	// Output.
	echo "<table><tr> <th>$cols[0]</th> <th>$cols[1]</th><th>$cols[2]</th></tr>";
	for ($j = 0 ; $j < $results->num_rows; ++$j){
	      $results->data_seek($j);
	      $row = $results->fetch_array(MYSQLI_NUM);

	      echo "<tr>";
	      for ($k = 0 ; $k < 3 ; ++$k)		    // MAGIC VAR!!
		echo "<td>" . htmlspecialchars($row[$k]) . "</td>";
	      echo "</tr>";
	    }

	echo "</table>";

	}
	
function BACKUPtableDump($aTable){
	$results = query("SELECT * FROM $aTable;");
	echo "<br><b>Table Dump: $aTable.</b>";
	echo "<table >" .
	    "<tr>" .
	    "<th>ID</th>" .
	    "<th>Name</th>" .
	    "<th>More</th>" .
	    "</tr>" ;
	for($i = 0; $i < $results->num_rows; $i++){
	    $results->data_seek($i);
	    echo "<tr>";
	    echo "<td>" . $results->fetch_assoc()['id']   . "</td>";
	    echo "<td>" . $results->fetch_assoc()['name'] . "</td>";
	    echo "<td>" . $results->fetch_assoc()['more'] . "</td>";
	    echo "</tr>";
	    }
	echo "</table>";    
	}
	
    function displayErrors(){
	$input = file_get_contents("error_log");
	echo "<br><b>ERROR LOG</b><br><div style='border:solid'><pre>" . $input . "</pre></div>";
	}

	//Execute SQL in box.
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['inputSQL'])){
	query($_POST[inputSQL]);
	}




    displayErrors();    

    echo"<br><br><br><br><br><br><br><br><br><br><br><br><br>"
    //$conn->close(); 
?> 
</div>
</body>
</html>


