<?php   
    $tmp = fopen("error_log", 'w') or die("Failed to open error log.");
    $PATH = $_SERVER['DOCUMENT_ROOT'] . "/playground/CWCMS/";
    require_once ($PATH."utilities/dbHandler.php");
    $_SESSION['DB'] = new DbHandler;
    $_SESSION['loadID'] = "unSet"; 
    $_SESSION['loadName'] = "unSet"; 
    $_SESSION['loadContent'] = "unSet";

    function validateID($inID){
        $results = $_SESSION['DB']->query("SELECT f_id FROM flecks ;");
        // print_r($results);
        
        for($i=0; $i<$results->num_rows; ++$i){
            $results->data_seek($i);
            if ($inID == $results->fetch_assoc()['f_id']){
                    return true;
                    }    
                }
        return false;
    }
    
    function loadFleck($thisFleck){
        // Validate ID.
        if (validateID($thisFleck)){
            $results = $_SESSION['DB']->query("SELECT f_id, name, content FROM flecks WHERE f_id = $thisFleck;");
            $row = $results->fetch_array(MYSQLI_ASSOC);
            $_SESSION['loadID'] =  htmlspecialchars($row['f_id']);
            $_SESSION['loadName'] =  htmlspecialchars($row['name']);
            $_SESSION['loadContent'] =  htmlspecialchars($row['content']);
            }    
        else {
        echo "<div style='background:red;'><p style='font-size:2em;'>Could not load: Attempting to load invalid Fleck ID: '$thisFleck'.</p></div>";
        }           
    }
    
    // Handles form submissions.
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        // Save content from the form to the database.
        if($_POST['save']){
            // Pull the values from the form.
            $inName = trim($_POST['fleckName']);
            $inContent = trim($_POST['fleckContent']);
            $inID = trim($_POST['currentFleckID']);
            
            // Validate ID.
            if (validateID($inID)){
                $_SESSION['DB']->query("UPDATE flecks SET "
                    . "f_id = '$inID', "
                    . "name = '$inName', "
                    . "content = '$inContent', "
                    . "updated = CURRENT_TIMESTAMP "
                    . "WHERE f_id = $inID;"
                    );
                }    
            else {
                echo "<div style='background:red;'><p style='font-size:2em;'>Could not save: Attempting to save invalid Fleck ID: '$inID'.</p></div>";
                }
            loadFleck($inID);    
            }
            
            
        // Load the specified data from the database into the form. 
        elseif($_POST['load']){
            loadFleck(trim($_POST['loadFleckID']));
            }
            
            
        // Create new Fleck.    
        elseif($_POST['new']){
            // Confirm the fleck was created, then load it into the editor.
            if($_SESSION['DB']->query("INSERT INTO flecks (name, content, updated) VALUES (NULL, NULL, CURRENT_TIMESTAMP);")){
                loadFleck($_SESSION['DB']->query("SELECT MAX(f_id) AS maxID FROM flecks;")->fetch_array(MYSQLI_ASSOC)['maxID']);
                }
            else{
                echo "<div style='background:red;'><p style='font-size:2em;'>Failed to create new Fleck.</p></div>";
                }
            }       
    }
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
    <script>
        <!-- Prevents undesired loss of data due to unsaved changes. -->
        $changed = false;
        
        function hasChanged(){
            $changed = true;
            }
        
        
        function confirmDiscardLoad(){
            if($changed){
                return confirm('Warning! There are unsaved changes. \n\nDiscard changes & load anyway?');
                }
            else{
                document.getElementById("form_load").submit();
                }
            }
            

        function confirmDiscardNew(){
            if($changed){
                return confirm('Warning! There are unsaved changes. \n\nDiscard changes & create new Fleck?');
                }
            else{
                document.getElementById("form_new").submit();
                }
            }


        function cancel(){
            document.getElementById("discardDialog").close();
            }
    </script>       
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
        <form method='post' action='flecks_editor.php' enctype='multipart/form-data'>
	    <textarea id="content" name="fleckContent" type="textarea" rows="30" cols="150" onKeyPress="hasChanged();" > 
<?php echo $_SESSION['loadContent']; ?> 
            </textarea><br>
            <input type='submit' value='Save' name="save">
            Name: <?php echo "<input type= 'text' name='fleckName' id='name' size='25' onKeyPress='hasChanged();' value=".$_SESSION['loadName'].">  "; ?>
            ID: <?php echo "<input type='text' name='currentFleckID' size='3' value='" . $_SESSION['loadID'] . "' readonly style='color:grey;'>"; ?><br> 
	</form>
    </div>
    <hr>
    <details style="float:left; background:ivory;" open>
        <summary><h2 style='color:black; text-align:center;'>Load Fleck</h2></summary>
        <div style="float:left;">
            <?php
                displayFlecks();
            ?>
        </div>
        
        <div style="float:left; margin:1em;">    
            <form method='post' action='flecks_editor.php' enctype='multipart/form-data' onSubmit="return confirmDiscardLoad(this);">
                <hr>
                Input Fleck ID: <input type="text" name ="loadFleckID" size="3" value="3">
                <input type='submit' value='Load' name="load" id="load">
                <hr>
            </form>
            <form method='post' action='flecks_editor.php' enctype='multipart/form-data' onSubmit="return confirmDiscardNew(this);">
                <input type='submit' value='Create New Fleck' name="new">
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
        $results = $_SESSION['DB']->query("SELECT f_id, name, DATE_FORMAT(updated, '%Y-%m-%d @ %H:%i') AS 'updated' FROM flecks;");
	$cols = ["ID","Name","Updated"];
	// Output.
	echo "<br><table>"
            . "<tr> <th colspan='3'><h3 style='color:black;'>Existing Flecks</h3></th></tr>"
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


displayErrors();   
?>
</div></body></html>
    
