<?php
    echo "<p>Welcome to Publisher.</p>";
    // Assume new page. 
    
    $pre = "<!--#include virtual='";
    $part = "head";
    $post = ".shtml' -->";
    
    
    // File reading.
    $fileName = "new_page.shtml";
    $someFile;
    echo "Attempting to open: '" . $fileName . ".'\n<br>";
    $someFile = fopen($fileName, 'r') or die("Failed to open file $fileName");
    $input = fread($someFile, filesize($fileName));
    echo "\n<br>\n<br>File contents:\n<br>-------------------------------------\n<br>" . $input;
    fclose($someFile)  or die ("Could not close file");



?>

