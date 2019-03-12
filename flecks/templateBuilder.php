<?php

// templateBuilder.php
echo "Welcome.<br>";
$tmp = fopen("error_log", 'w') or die("Failed to open error log.");


require_once ("dbHandler.php");
$bar = new DbHandler;

//$bar->query("SELECT * FROM flecks;");
//$bar->queryv("SELECT * FROM flecks;");     

displayErrors();


function displayErrors(){
	$input = file_get_contents("error_log");
	echo "<br><b>ERROR LOG</b><br><div style='border:solid'><pre>" . $input . "</pre></div>";
	}

        
        
        
        
        
        
        
        
        
        
        
        
?>
    
