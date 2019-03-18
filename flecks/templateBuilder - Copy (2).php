<?php   // templateBuilder.php //

$tmp = fopen("error_log", 'w') or die("Failed to open error log.");

require_once ("dbHandler.php");
//$dbh = new DbHandler;
$_SESSION['DB'] = new DbHandler; 

?>

<html>
<head>
<title> CWCMS Fleck Editor</title>
    <script  async type="text/javascript" src="/scripting/jquery/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/playground/CWCMS/css/cwcms.css">
    <link href='https://fonts.googleapis.com/css?family=Cinzel:400,700,900' rel='stylesheet' type='text/css'> 
    <link href='https://fonts.googleapis.com/css?family=Cinzel+Decorative:400,700,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Domine:400,700' rel='stylesheet' type='text/css'>
    <LINK REL="SHORTCUT ICON" HREF="/images/parts/favicon.ico" />
    <meta charset="utf-8"> 
</head>
<body>
<div class="header"> 
    <h1>
        <img src="../editor/pen.png" class="headerIcon">
        CWCMS Fleck Editor
    </h1>
</div>
    
<div style="padding: 2em; ">
    <div>
        <form method='post' action='templateBuilder.php' enctype='multipart/form-data'>
	    <textarea id="sql" name="fleckContent" type="textarea" rows="30" cols="150"> Type Here. </textarea><br>
            <input type='submit' value='Save' name="save">
            Name: <input type="text" name="fleckName" size="25" value="wow"> 
            ID: <input type="text" name ="currentFleckID" size="3" value="1" readonly style="color:grey;"><br>
	</form>
    </div>
    <hr>
    <details style="float:left; background:ivory;" open>
        <summary>Load Fleck</summary>
        <div style="float:left;">
            <?php
                displayFlecks();
            ?>
        </div>
        <div style="float:left;">
            <form method='post' action='templateBuilder.php' enctype='multipart/form-data'>
                Input ID: <input type="text" name ="loadFleckID" size="3" value="1">
                <input type='submit' value='Load' name="load">
            </form>
        </div>
    </details> 

<?php
function displayErrors(){
	$input = file_get_contents("error_log");
	echo  "<br><div style='float:left; clear:both;'>"
                . "<b>ERROR LOG</b><br>"
                . "<div style='border:solid'><pre>" . $input . "</pre></div>"
            . "</div>";
	}

function displayFlecks(){
        $results = $_SESSION['DB']->query("SELECT f_id, name, updated FROM flecks;");
	$cols = ["ID","Name","Updated"];
	// Output.
	echo "<br><table>"
            . "<tr> <th colspan='3'><h3>Existing Flecks</h3></th></tr>"
            . "<tr> <th>$cols[0]</th> <th>$cols[1]</th><th>$cols[2]</th></tr>";
	for ($i = 0 ; $i < $results->num_rows; ++$i){
	      $results->data_seek($i);
	      $row = $results->fetch_array(MYSQLI_NUM);
	      echo "<tr>";
	      for ($j = 0 ; $j < 3 ; ++$j)		    // MAGIC VAR!!
		echo "<td>" . htmlspecialchars($row[$j]) . "</td>";
	      echo "</tr>";
	    }
	echo "</table>";
	}

         
        
     // Updates table with contents from form.
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if($_POST['save']){
            echo "Saving.";
            $inName = trim($_POST['fleckName']);
            $inContent = trim($_POST['fleckContent']);
            $inID = trim($_POST['currentFleckID']);
            $_SESSION['DB']->query("UPDATE flecks SET "
                . "f_id = '$inID', "
                . "name = '$inName', "
                . "content = '$inContent' "
                . "WHERE f_id = $inID;"
                );
            }
        elseif($_POST['load']){
            echo "<b>Loading...</b><br>";
            $results = $_SESSION['DB']->query(""
                    . "SELECT f_id, name, content FROM flecks WHERE f_id = "
                    . trim($_POST['loadFleckID']) . ";");
            $results->data_seek(0);
            echo "ID: "      . htmlspecialchars($results->fetch_array(MYSQLI_NUM)[0][0]) . "<br>";
            echo "Name: "    . htmlspecialchars($results->fetch_array(MYSQLI_NUM)[0][1]) . "<br>";
            echo "Content: " . htmlspecialchars($results->fetch_array(MYSQLI_NUM)[0][2]) . "<br>";
            }
    }

displayErrors();   
?>
</div></body></html>
    
