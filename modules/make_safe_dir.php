<?php   
    /*   Make Dirs
     */

function safeMkDir($path){
	if (!file_exists($path)) {
		mkdir($path, 0777, true);
		}
	}

function touchdirs(){
		echo "touched Safe Make Dir.";
	}
?>