<?php
class Publisher{
		// ------------------------------------- Init. Setup. ------------------------------------- 
		// Housekeeping.
		function InitMe(){
			error_reporting(E_ALL);
			$tmp = fopen("error_c3Log", 'w') or die("Failed to open error c3Log.");
			$_SERVER['PATH'] = $_SERVER['DOCUMENT_ROOT'] . "/playground/CWCMS/";
			$_SERVER['HTML_PATH'] = "/playground/CWCMS";
			$_SESSION['BOOK'] = "unSet"; 
			$_SESSION['NAME'] = "unSet"; 
			require_once ($_SERVER['PATH']."utilities/dbHandler.php");
			$_SESSION['DB'] = new DbHandler;
			$_SESSION['DB2'] = new DbHandler;
			require_once "../modules/make_safe_dir.php";
			// Create Page Details
			$_SESSION['P_ID'] = 0;
			$_SESSION['RAW_CONTENT'] = "unSet"; 
			$_SESSION['TITLE'] = "unSet"; 
			$_SESSION['URL'] = "unSet"; 
			$_SESSION['UPDATED'] = "unSet"; 
			//$_SESSION['COOKED_CONTENT'] = "unSet"; 
			}

		// -------------------------------------  START Processing & Code execution. ------------------------------------- 
			
		function publish($pid){
			$this->getRaw($pid);
			$this->echoAll();
			$this->c3Log("Final Page URL:".$_SESSION['FINAL_PAGE_URL']);
			$this->buildAsciiDoc();
			
			$this->buildPage();
			}
		
		
		// -------------------------------------  FUNCTION DEFINITIONS ------------------------------------- 
		
		function publishTouch(){
			c3Log("HELLO, This is the Publisher utility.");
			}


		function c3Log($say){
			echo "<script>console.log(\"$say\");</script>";
			//echo $say;
			}


		function getRaw($rawP_id){
			//$this->c3Log("getRaw()...");
			$_SESSION['P_ID']  = $rawP_id;
			//$this->c3Log("Publishing page ID: ".$_SESSION['P_ID']);
			$results = $_SESSION['DB']->query("SELECT p_id, b_id, name, title, content, url, DATE_FORMAT(updated, '%Y-%m-%d @ %H:%i') AS 'updated'  FROM pages WHERE p_id = ".$_SESSION['P_ID'].";");
			$row = $results->fetch_array(MYSQLI_ASSOC);
			$b_id = htmlspecialchars($row['b_id']);
			echo "<script>console.log('b_id: $b_id.');</script>";
			
			$_SESSION['NAME'] =  strtolower(htmlspecialchars($row['name']));
			$_SESSION['TITLE'] = htmlspecialchars($row['title']);
			$_SESSION['RAW_CONTENT'] = htmlspecialchars($row['content']);
			$_SESSION['URL'] = htmlspecialchars($row['url']);
			$_SESSION['UPDATED'] = htmlspecialchars($row['updated']);
			
			$db2 = new DbHandler;
			$bookInfo = $db2->query("SELECT name FROM books WHERE b_id = $b_id;");
			$bookRow = $bookInfo->fetch_array(MYSQLI_ASSOC);
			$_SESSION['BOOK'] = htmlspecialchars($bookRow['name']); 	// This should be pulled from the DB. 
			echo "<script>console.log('Book Name: ".$_SESSION['BOOK'].".');</script>";
		
			$_SESSION['FULLPATH'] = $_SERVER['PATH']."root/content/".$_SESSION['BOOK']."/".$_SESSION['NAME']."/";
			$_SESSION['FINAL_PAGE_URL'] = $_SESSION['FULLPATH'] . "index.php";
			$_SESSION['FINAL_ASCIIDOC_URL'] = $_SESSION['FULLPATH'] .  "/ascii.adoc";
			}
			
		function echoAll(){
			$this->c3Log("echoAll()...");
			$this->c3Log("P_ID: ". $_SESSION['P_ID']);
			$this->c3Log("\$NAME: ".$_SESSION['NAME']);
			$this->c3Log("\$CONTENT ".$_SESSION['RAW_CONTENT']);
			$this->c3Log("\$TITLE: ".$_SESSION['TITLE']);
			$this->c3Log("\$URL: ".$_SESSION['URL']);
			$this->c3Log("\$UPDATED: ".$_SESSION['UPDATED']);
			}
		

		function buildAsciiDoc(){
			if (file_put_contents($_SESSION['FINAL_ASCIIDOC_URL'], $_SESSION['RAW_CONTENT']) !== false) {
				$this->c3Log("File created: " . basename($_SESSION['FINAL_ASCIIDOC_URL']) ."." );
				} 
			else {
				$this->c3Log("Cannot create file (" . basename($_SESSION['FINAL_ASCIIDOC_URL']) );
				echo "<script>console.log('Cannot create file: ".basename($_SESSION['FINAL_ASCIIDOC_URL']).".');</script>";
				}
			}
			

		function buildPage(){
			safeMkDir($_SESSION['FULLPATH']);	
			$asciiDocConfigs = "\n:imagesdir: ../images/ \n"; // This will need to be enhanced when there are sub books. 
			$cookedContent = "";
			$cookedContent .= "<html>\n<head>\n";
			$cookedContent .= "<title>".$_SESSION['TITLE']."</title>\n";
			$cookedContent .= "<?php \$_SERVER['PATH'] = \$_SERVER['DOCUMENT_ROOT'].'/playground/CWCMS'; ?> \n";
			$cookedContent .= "<?php echo file_get_contents(\$_SERVER['PATH'].'/parts/boilerplate.html'); ?> \n";
			$cookedContent .= "</head><body>\n";
			$cookedContent .= "<?php echo file_get_contents(\$_SERVER['PATH'].'/parts/header.html'); ?> \n";
			$cookedContent .= "<?php echo file_get_contents(\$_SERVER['PATH'].'/parts/sidebar.html');?> \n";
			$cookedContent .=" 
				<script>
					console.log('Spinning up the grill...');
					var asciidoctor = Asciidoctor();
					Promise.all([fetch('".$_SERVER['HTML_PATH']."/root/content/".$_SESSION['BOOK']."/".$_SESSION['NAME'] ."/ascii.adoc').then(x => x.text())])  
						.then(([raw, sample2Resp]) => {
						$('body').append(asciidoctor.convert(raw, { 'safe': 'unsafe' }));
						});
				console.log('Grilling Complete.');
				</script>
				\n ";
			$cookedContent .= "</body>\n</html>";
			
			if (file_put_contents($_SESSION['FINAL_PAGE_URL'], $cookedContent) !== false) {
				$this->c3Log("File created: " . basename($_SESSION['FINAL_PAGE_URL']) ."." );
				} 
			else {
				$this->c3Log("Cannot create file (" . basename($_SESSION['FINAL_PAGE_URL']) );
				}
			}
	}
?>




