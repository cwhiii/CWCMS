<html>
<head>
<title> CWCMS DB Testing </title>
    <script  async type="text/javascript" src="/scripting/jquery/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/playground/CWCMS/css/cwcms.css">
    <link href='https://fonts.googleapis.com/css?family=Cinzel:400,700,900' rel='stylesheet' type='text/css'> 
    <link href='https://fonts.googleapis.com/css?family=Cinzel+Decorative:400,700,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Domine:400,700' rel='stylesheet' type='text/css'>
    <LINK REL="SHORTCUT ICON" HREF="/images/parts/favicon.ico" />
    <meta charset="utf-8"> 
</head>
<body>
<div class="header"> <!-- USE??? <header> ??? -->
    <h1>
        <img src="/images/parts/kmv_logo_small.png" class="headerIcon">
        CWCMS DB Testing
    </h1>
</div>
<div style="padding: 2em; ">
    <div>
        <form method='post' action='index.php' enctype='multipart/form-data'>
            Name: <input type="text" name="pageName" size="25" value="wow"> 
            ID: <input type="text" name ="pageID" size="3" value="8"><br>
	    <textarea id="sql" name="pageContent" type="textarea" rows="10" cols="50"> Coming soon! </textarea><br>
	    <input type='submit' value='Save Changes'>
	</form>
    </div>
    <hr>

<?php
    require ("db_credentials.php");

    // Clear Error Log.
    $tmp = fopen("error_log", 'w') or die("Failed to open error log.");
    fwrite($tmp, "Cleared: " . date('Y-m-d H:i:s') . "\n\n") or die ("Could not clear error log.");
    fclose($tmp)  or die ("Could not close error log.");

    // $query = "CREATE TABLE test_table (id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY, name TEXT, more TEXT);";
    // query($query);
    
    // Updates table wirh conects from form.
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['pageContent'])){
	$inName = trim($_POST['pageName']);
        $inContent = trim($_POST['pageContent']);
        $inID = trim($_POST['pageID']);
        query("UPDATE test_table SET "
            . "name = '$inName', "
            . "more = '$inContent' "
            . "WHERE id = $inID;"
            );
	}
    //query($query);
    tableDump("test_table");

    
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
    
    // Pull all the contents of the given table, & display them. 
    function tableDump($aTable){
	$results = query("SELECT * FROM $aTable;");
	$cols = ["id","name","more"];

		
	// Output.
	echo "<br><table><tr> <th>$cols[0]</th> <th>$cols[1]</th><th>$cols[2]</th></tr>";
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


    displayErrors();    
    echo"<br><br><br><br><br><br><br><br><br><br><br><br><br>"
    //$conn->close(); 
?> 
</div>
</body>
</html>


