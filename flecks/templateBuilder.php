
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
            <input type='submit' value='Save Changes'>
            Name: <input type="text" name="fleckName" size="25" value="wow"> 
            ID: <input type="text" name ="fleckID" size="3" value="1" readonly style="color:grey;"><br>
	</form>
    </div>
    <hr>
    <div>
        <form method='post' action='templateBuilder.php' enctype='multipart/form-data'>
	    <input type='submit' value='Load'>
	</form>
    </div>
    <hr>

<?php
// templateBuilder.php
echo "Welcome.<br>";
//$tmp = fopen("error_log", 'w') or die("Failed to open error log.");


require_once ("dbHandler.php");
$dbh = new DbHandler;

//$bar->query("SELECT * FROM flecks;");
//$bar->queryv("SELECT * FROM flecks;");    


 // Updates table wirh contents from form.
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['fleckContent'])){
	$inName = trim($_POST['fleckName']);
        $inContent = trim($_POST['fleckContent']);
        $inID = trim($_POST['fleckID']);
        
        $dbh->query("UPDATE flecks SET "
            . "f_id = '$inID', "
            . "name = '$inName', "
            . "content = '$inContent' "
            . "WHERE f_id = $inID;"
            );
	}















displayErrors();


function displayErrors(){
	$input = file_get_contents("error_log");
	echo "<br><b>ERROR LOG</b><br><div style='border:solid'><pre>" . $input . "</pre></div>";
	}

        
        
        
        
        
        
        
        
        
        
        
        
?>
</div></body></html>
    
