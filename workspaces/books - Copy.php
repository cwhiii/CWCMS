<?php
/*
	For managing Books and Sub-Volumes.
	A portion of CWCMS. 
	www.cwholemaniii.com
*/
    $tmp = fopen("error_log", 'w') or die("Failed to open error log.");
    $PATH = $_SERVER['DOCUMENT_ROOT'] . "/playground/CWCMS/";
	require_once ($PATH."utilities/dbHandler.php");
  
	$_SESSION['loadName'] = "unset";
	$_SESSION['loadB_id'] = "-1";
	$_SESSION['parentID'] = "unset";
	$_SESSION['parentName'] = "unknown";

if($_SERVER['REQUEST_METHOD'] == "POST"){
        // Save content from the form to the database.
        /*if($_POST['save']){
			//save();
			}
			*/
        // Load the specified data from the database into the form. 
        //elseif($_POST['load']){
		if($_POST['load']){
            loadBook(trim($_POST['b_id']));
            }
	}        

/* ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------HERE---------------

function loadPage($aPage){
        // Validate ID.
		//echo "<div style='background:tan;'><p style='font-size:2em;'>Attempting to load: '$aPage'.</p></div>";
        if (validateID($aPage)){
            $results = $_SESSION['DB']->query("SELECT p_id, b_id, name, title, content, DATE_FORMAT(updated, '%Y-%m-%d @ %H:%i') AS 'updated'  FROM pages WHERE p_id = $aPage;");
            $row = $results->fetch_array(MYSQLI_ASSOC);
            $_SESSION['loadID'] =  htmlspecialchars($row['p_id']);
            $_SESSION['loadName'] =  htmlspecialchars($row['name']);
			$_SESSION['loadTitle'] = htmlspecialchars($row['title']); 
			//echo $_SESSION['loadTitle'];
            $_SESSION['loadContent'] =  htmlspecialchars($row['content']);
            $_SESSION['updated'] =  htmlspecialchars($row['updated']);
            
			$_SESSION['loadBook'] =  htmlspecialchars($row['b_id']);
			}    
        else {
            echo "<div style='background:red;'><p style='font-size:2em;'>Could not load: Attempting to load invalid Page ID: '$aPage'.</p></div>";
            }           
        }
 ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------HERE---------------
*/
	function loadBook($b_id){
		c3Log("Loading Book: $b_id");
		echo "<script>alert($b_id);</script>";
		$_SESSION['loadName'] = "unset";
		$_SESSION['loadB_id'] = "-1";
		$_SESSION['parentID'] = "unset";
		$_SESSION['parentName'] = "unknown";
		}

	function c3Log($text) {
		echo "<script>console.log('$text');</script>";
		}
		
	function go(){	
		$aBook = new Book;
		$aBook->getBookByID(5);
		$aBook->getAllBooks();
		}
    

class Book {	
	public $b_id;
	public $name;
	public $parentID;
	public $isSubVolume;
	public $ping;
	public $pong;
	public $db;
	public $allBooks;
	
	
	function __construct(){
		c3Log("--------------------------------------------------------------------------------");
		c3Log("New Book class instantiated.");
		$this->isSubVolume = false;
		$this->db = new DbHandler;	
		}
 
 
	function getBookByID($b_id) {
		c3Log("--------------------------------------------------------------------------------");
		c3Log("getBookByID()");
		$results = $this->db->query("SELECT b_id, name, parentID, DATE_FORMAT(created, '%Y-%m-%d @ %H:%i') as created, DATE_FORMAT(updated, '%Y-%m-%d @ %H:%i') AS 'updated'  FROM books WHERE b_id = $b_id;");
		$row = $results->fetch_array(MYSQLI_ASSOC);
 		$this->b_id =  htmlspecialchars($row['b_id']);
		$this->name = htmlspecialchars($row['name']);;
		$this->parentID = htmlspecialchars($row['parentID']);
		$this->parentID ? $this->isSubVolume = true : $this->isSubVolume = false;
		c3Log("b_id: ".$this->b_id);
		c3Log("Name: ".$this->name);
		if($this->isSubVolume){			
			c3Log("Is a Sub Volume.");
			c3Log("Parent ID [".$this->parentID."].");
			}
		else{
			c3Log("Is top level.");
			}
		}


	function at() {
		c3Log("--------------------------------------------------------------------------------");
		c3Log("Array Test()");
		}


	function getAllBooks() {
		c3Log("--------------------------------------------------------------------------------");
		c3Log("getAllBooks()");
		
		//$results = $this->db->query("SELECT b_id, name, parentID, DATE_FORMAT(created, '%Y-%m-%d @ %H:%i') as created, DATE_FORMAT(updated, '%Y-%m-%d @ %H:%i') AS 'updated'  FROM books;");
		$q  = ""
			."SELECT a.b_id, a.name, a.parentID, b.name "
			."FROM books a "
			."LEFT OUTER JOIN books b ON(a.parentID = b.b_id); ";
		$results = $this->db->query($q);
		$cols = ["ID","Name","Parent ID", "Parent Name"];		
		$this->allBooks = array(
			array("","",""),
			array("","",""),
			array("","",""),
			array("","","")
			);
		
		for ($i = 0 ; $i < $results->num_rows; ++$i){
				$results->data_seek($i);
				$row = $results->fetch_array(MYSQLI_NUM);
				echo "<tr>"; // the tr needs an onMouseOver="setLoadID(current row)" . 
				for ($j = 0 ; $j < count($cols) ; ++$j)		   
					$this->allBooks[$i][$j] = htmlspecialchars($row[$j]);
				}
		//print_r($this->allBooks);
		echo "<table id='bookList' style='float:left;' name='load' onClick='document.getElementById(\"bookForm\").submit();'>"
			. "<tr> <th colspan='4'>Books List</th></tr>"
			. "<tr> <th>$cols[0]</th> <th>$cols[1]</th><th>$cols[2]</th><th>$cols[3]</th></tr>";
			for ($i = 0 ; $i < $results->num_rows; ++$i){
				echo "<tr>"; 
				for ($j = 0 ; $j < count($cols) ; ++$j)	{	   
					echo "<td> " . $this->allBooks[$i][$j] . "</td>";
					}
				echo "</tr>";
				}
			echo "</table>";
		}
		
	
	function c3bLog($text) {
		echo "<script>console.log('$text');</script>";
		}
	}



?>
<html>
	<head>
		<title>Books Workspace</title>
		<script  async type="text/javascript" src="/scripting/jquery/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/playground/CWCMS/css/cwcms.css">
		<link href='https://fonts.googleapis.com/css?family=Cinzel:400,700,900' rel='stylesheet' type='text/css'> 
		<link href='https://fonts.googleapis.com/css?family=Cinzel+Decorative:400,700,900' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Domine:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/playground/CWCMS/css/workspaces.css">
		<link rel="SHORTCUT ICON" HREF="/images/parts/favicon.ico" />
		<meta charset="utf-8"> 
		<script>
		//	To determine which row is clicked on, and therefore to determine which Book to then subsequently load. 
			$(document).ready(function(){
				$("#bookList td").click(function() {     
					var column_num = 0;
					var row_num = parseInt( $(this).parent().index() )+1;    
					
					x = $('td:first', $(this).parents('tr')).text();
					$("#result").html(x);   
					$("#b_id").val(x);
					
					$("#form").submit( function(eventObj) {
						$("<input />").attr("type", "hidden")
						.attr("name", "b_id")
						.attr("value", x)
						.appendTo("#form");
						return true;
						});
					
					
					
					 
				});
			});
		</script><!--
		<script>
			$("table").on('click','tr',function(e){
				e.preventDefault();
				var id = $(this).attr('value');
				alert(id);
				var $form = $('#leagueForm');
				// set the input value
				$form.find('input').val(id);
				$form.submit();
				});
		</script>-->
	</head>
	<body>
	<header class="leftBox">
		<h1>
			<img src="pen.png" class="headerIcon">
			CWCMS  <br> 
			Books <br>
			Workspace
		</h1>
	</header>
	<form method='post' action='books.php' enctype='multipart/form-data' id='bookForm'> <!-- RE ADD THIS: <form method='post' action='index.php' enctype='multipart/form-data' onSubmit="return confirmDiscardLoad(this);">  -->
		<div class="leftBox" style="clear:left;">
			<fieldset class="collapsible">
				<legend>Actions</legend>
					<input type='submit' value='Save Changes' name="save" >
					<hr>
					<div class="confirmBox" id="delete" title="DELETE THIS BOOK">
						<input name="confirmDelete" title="CONFIRM DELETE" type="checkbox">
						<button name="delete" title="DELETE">Delete</button>
					</div>
			</fieldset>
			<fieldset class="collapsible">
			<legend>Nav</legend>
				<input type='submit' value="<-- Exit Workspace" name="exit">
				<br>
				<input type="text" name ="loadID" size="3" value="3">
				<button style="width:3.5em" title="Create New Book" onClick="console.log('This should really do something... ')">New</button>
				<input type='submit' value='Load' name="load" id="load">
			</fieldset>
		</div>
		
		<div class="workspace" style="background:none;">
		<fieldset class="collapsible">
			<legend>Book Info</legend>
			<!-- POPULATE THE BOOKS TABLE-->
			<?php go(); ?> 
			
			<div style="overflow: auto;">
				<label for="name" title='WARNING: Changing this can break existing links!'>Name:</label> 
				<?php echo "<input class='labeled' type= 'text' name='name' id='name' size='25' value='".$_SESSION['loadName']."' style='color:orange;'>  "; ?><br>
				
				<label for="id">ID:</label>  
				<?php echo "<input class='labeled' type='text' name='id' id='b_id' size='3' value='".$_SESSION['loadB_id']."' style='color:grey;' readonly><br>"; ?> 
				
				<label for="parentID">Parent ID</label> 
				<input class='labeled' type="text" value="???" ><br>
				
				<label for="parentName">Parent Name</label> 
				<input class='labeled' type="text" value="???" ><br>
			</div>
		</fieldset>
			

		<hr>
		<div id="result">Result</div>

		</div>
	</form>
	</body>
</html>
