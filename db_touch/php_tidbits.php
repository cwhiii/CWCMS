<?php 

	// Dump Array.
        print_r($someArray);
	
	// For each loop.
        foreach($y as $thing){
            echo $thing . ", ";
            }
        echo "\n<br>\n<br>";
        
	// Pull info from GET request. 
	// extract($_GET, EXTR_PREFIX_ALL, "somePrefix");
        
	// File creation & writing.
	$fileName = "xyz23.txt";
	 
	$fileContents = "Hey, I have text!\n<br>second line.";
	$someFile;
	echo "Attempting to create: '" . $fileName . ".'\n<br>";
	if (file_exists($fileName))
	    {echo "\tFile '$fileName' already exists!";}
	else{
	    echo "\tCreated file: '$fileName'.";
	    $someFile = fopen($fileName, 'w') or die("Failed to create file $fileName");
	    fwrite($someFile, $fileContents) or die ("Could not write to file");
	    fclose($someFile)  or die ("Could not close  file");
	    }
	
	// File reading.
	$fileName = "xyz23.txt";
	$someFile;
	echo "Attempting to open: '" . $fileName . ".'\n<br>";
	$someFile = fopen($fileName, 'r') or die("Failed to open file $fileName");
	$input = fread($someFile, filesize($fileName));
	echo "\n<br>\n<br>File contents:\n<br>-------------------------------------\n<br>" . $input;
	fclose($someFile)  or die ("Could not close file");
	
	
	// upload File.php
	
	echo <<<_END
	  <div style="background:tan;"><form method='post' action='index.php' enctype='multipart/form-data'>
	  Select File: <input type='file' name='filename' size='10'>
	  <input type='submit' value='Upload'>
	  </form>
	  </div>
_END;

	if ($_FILES){
	    $name = $_FILES['filename']['name'];
	    move_uploaded_file($_FILES['filename']['tmp_name'], $name);
	    echo "Uploaded image '$name'<br><img src='$name'>";
	    }
	    
	    
	    
	//  Clear as day.
	move_uploaded_file($someFile) !!

	// File copying.
	if(!copy("file1.txt", "file2.txt")) echo "Fail.";
	
	// PHP Info dump.
        echo phpinfo();
?>
