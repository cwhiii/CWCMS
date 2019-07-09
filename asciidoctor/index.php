<?php   
    /*      AsciiDoctor Tests 0.1 for CWCMS. 
     *      www.cwholemaniii.com
     *      codeMonkey@cwholemaniii.com
     *
     *      Created:        9 July  2019. 	
     *      Modified:       9 July 2019.
     */

    $tmp = fopen("error_log", 'w') or die("Failed to open error log.");
    $PATH = $_SERVER['DOCUMENT_ROOT'] . "/playground/CWCMS/";
    require_once ($PATH."utilities/dbHandler.php");
    $_SESSION['DB'] = new DbHandler;
    $_SESSION['loadID'] = "1"; 
    $_SESSION['loadName'] = "unSet"; 
    $_SESSION['loadContent'] = "unSet";
    $_SESSION['loadState'] = "unSet";
    $_SESSION['updated'] = "unSet";

	$aPage = 3;


 function loadPage(){
        echo "<div style='background:tan;'><p style='font-size:2em;'>Attempting to load: '$aPage'.</p></div>";
        if (validateID($aPage)){
            $results = $_SESSION['DB']->query("SELECT p_id, name, content, DATE_FORMAT(updated, '%Y-%m-%d @ %H:%i') AS 'updated'  FROM pages WHERE p_id = $aPage;");
            $row = $results->fetch_array(MYSQLI_ASSOC);
            $_SESSION['rawID'] =  htmlspecialchars($row['p_id']);
            $_SESSION['rawName'] =  htmlspecialchars($row['name']);
            $_SESSION['rawContent'] =  htmlspecialchars($row['content']);
            $_SESSION['rawUpdated'] =  htmlspecialchars($row['updated']);
            }    
        else {
            echo "<div style='background:red;'><p style='font-size:2em;'>Could not load: Attempting to load invalid Page ID: '$aPage'.</p></div>";
            }           
        }
    
?>
<html>
<head>
    <title> CWCMS AsciiDoctor Tests </title>
    <script  async type="text/javascript" src="/scripting/jquery/jquery.min.js"></script>
    <script src="node_modules/@asciidoctor/core/dist/browser/asciidoctor.js"></script>
	<link rel="stylesheet" type="text/css" href="/playground/CWCMS/css/cwcms.css">
    <link href='https://fonts.googleapis.com/css?family=Cinzel:400,700,900' rel='stylesheet' type='text/css'> 
    <link href='https://fonts.googleapis.com/css?family=Cinzel+Decorative:400,700,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Domine:400,700' rel='stylesheet' type='text/css'>
    <LINK REL="SHORTCUT ICON" HREF="/images/parts/favicon.ico" />
    <meta charset="utf-8"> 
    <style>
		*, *:before, *:after {
		-webkit-box-sizing: border-box; 
		-moz-box-sizing: border-box; 
		box-sizing: border-box;
		  }
	  
        body {
            padding:0;
            margin:0;
            background:radial-gradient(circle at bottom left, rgba(78,179,147,1), rgba(49,125,157,1));
            }
			
    </style>
	
	
</head>
<body>
	<header>
		<img src="pen.png" class="headerIcon">	
		<h1>
			CWCMS  <br> AsciiDoctor Tests
		</h1>
	</header>

	<div id="raw" style="background:ivory;">
===
This is text.

This is more text.
	</div>
	
	<br>
		<button onClick="cook();">Cook</button>
	<br>	
	
	
	<div id="cooked"  style="background:tan;">
		Cooked Goes Here.
	</div>
	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
</body>
<script>
	var asciidoctor = Asciidoctor();
	
	function cook(){
		alert("Spinning up the grill...");
		
		var cooked = asciidoctor.convert('Hello, _Asciidoctor_')console.log(html)
		document.getElementById(id).innerHTML = cooked;
		
		}

</script>
	
</html>


