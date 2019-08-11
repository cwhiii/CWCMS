<?php   
    /*      Page Editor 0.3 for CWCMS. 
     *      www.cwholemaniii.com
     *      codeMonkey@cwholemaniii.com
     *
     *      Created:        2 March  2019. 	
     *      Modified:       9 August 2019.
     */

    $tmp = fopen("error_log", 'w') or die("Failed to open error log.");
    $PATH = $_SERVER['DOCUMENT_ROOT'] . "/playground/CWCMS/";
    require_once ($PATH."utilities/dbHandler.php");
	//NEEDED FOR PUBLISHING:
	require_once ($PATH."utilities/publisher.php");
	//c3Log($PATH."utilities/publisher.php");
		
    $_SESSION['DB'] = new DbHandler;
    $_SESSION['loadID'] = "1"; 
    $_SESSION['loadName'] = "unSet"; 
    $_SESSION['loadTitle'] = "unSet"; 
    $_SESSION['loadContent'] = "unSet";
    $_SESSION['loadState'] = "unSet";
    $_SESSION['updated'] = "unSet";
	$_SESSION['loadBook'] = "-1";
    
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

    function validateID($inID){
        $results = $_SESSION['DB']->query("SELECT p_id FROM pages;");
        // print_r($results);
        
        for($i=0; $i<$results->num_rows; ++$i){
            $results->data_seek($i);
            if ($inID == $results->fetch_assoc()['p_id']){
                    return true;
                    }    
                }
        return false;
        }
	
	// b_id 1 is hard-coded as Book "General."

	function save(){
		echo "Saving.";
		// Pull the values from the form.
		$inID = trim($_POST['id']); 
		//$inBook = determineBook();
		// Validate ID.
		if (validateID($inID)){
			$_SESSION['DB']->query("UPDATE pages SET "
				. "p_id = '$inID', "
				. "name = '".trim($_POST['name'])."', "
				. "title = '".trim($_POST['title'])."', "
				. "b_id = '".trim($_POST['book'])."', "
				. "content = '".addslashes(trim($_POST['content']))."', "
				. "updated = CURRENT_TIMESTAMP "
				. "WHERE p_id = $inID;"
				);
			}    
		else {
			echo "<div style='background:red;'><p style='font-size:2em;'>Could not save: Attempting to save invalid Page ID: '$inID'.</p></div>";
			}
		loadPage($inID);    
		}
			
			
	function c3Log($say){
		echo "<script>console.log(\"$say\");</script>";
		}

	function publish(){
		save();
		$pub = new Publisher();
		$pub->InitMe();
		$pub->publish(trim($_POST['id']));
		loadPage(trim($_POST['id']));
		}				
    
    // Handles form submissions.
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        // Save content from the form to the database.
        if($_POST['save']){
			save();
			}

        // Create new Page.    
        elseif($_POST['publish']){
            // Save, then run the Publisher utility. 
            //save();
			publish();
            }

        // Load the specified data from the database into the form. 
        elseif($_POST['load']){
            loadPage(trim($_POST['loadID']));
            }
            
        // Create new Page.    
        elseif($_POST['new']){
            // Confirm the Page was created, then load it into the editor.
            if($_SESSION['DB']->query("INSERT INTO pages (name, content, updated) VALUES (NULL, NULL, CURRENT_TIMESTAMP);")){
                loadPage($_SESSION['DB']->query("SELECT MAX(p_id) AS maxID FROM pages;")->fetch_array(MYSQLI_ASSOC)['maxID']);
                }
            else{
                echo "<div style='background:red;'><p style='font-size:2em;'>Failed to create new Page.</p></div>";
                }
            }       
	}
?>
<html>
<head>
    <title> CWCMS Page Editor </title>
    <script  async type="text/javascript" src="/scripting/jquery/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/playground/CWCMS/css/cwcms.css">
    <link href='https://fonts.googleapis.com/css?family=Cinzel:400,700,900' rel='stylesheet' type='text/css'> 
    <link href='https://fonts.googleapis.com/css?family=Cinzel+Decorative:400,700,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Domine:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../css/workspaces.css">
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
                return confirm('Warning! There are unsaved changes. \n\nDiscard changes & create new Page?');
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
	<header class="leftBox">
		<h1>
			<img src="pen.png" class="headerIcon">
			CWCMS  <br> Page Editor
		</h1>
		<h3><i>Some Page Title</i></h3>
	</header>
		
		
		
<!-- TODO Re-add confirm discard-->
<form method='post' action='pages.php' enctype='multipart/form-data'> <!-- RE ADD THIS: <form method='post' action='index.php' enctype='multipart/form-data' onSubmit="return confirmDiscardLoad(this);">  -->
<div class="leftBox" style="clear:left;">
	
			<fieldset class="collapsible">
				<legend>Actions</legend>
					<input type='submit' value='Save Draft' name="save" >
					<input type='submit' value='Publish' name="publish" > 
					<hr>
					<div class="confirmBox" title="DISCARD EDITS">
						<input  name="confirmDelete" title="CONFIRM REFRESH" type="checkbox">
						<button name="delete" title="DISCARD EDITS, and reload previously saved draft.">Reset</button>
					</div> 
					<div class="confirmBox" id="delete" title="DELETE THIS PAGE">
						<input name="confirmDelete" title="CONFIRM DELETE" type="checkbox">
						<button name="delete" title="DELETE">Delete</button>
					</div>
				
			</fieldset>

			<fieldset class="collapsible">	
				<legend>Page Info</legend>
				<div>
					<label for="book" title="A group or collection.">Book:</label> 
					
					<div class='labeled' > 
						<nobr>
						<button style="width:3.5em" title="Create New Book" onClick="console.log('This should really do something... ')">New</button>
						<?php echo " <select id='book' name='book'>"; ?>
							<?php displayBooks(); ?>
						</select>
						</nobr><br>
					</div>
					
					<div></div>
					
					<br>
					<label for="title">Title:</label>       <?php echo "<input class='labeled' type= 'text' name='title' id='title' size='25' value=".$_SESSION['loadTitle'].">  "; ?><br>
					<label for="name" title='WARNING: Changing this can break existing links!'>Name:</label> <?php echo "<input class='labeled' type= 'text' name='name' id='name' size='25' onKeyPress='hasChanged();' value=".$_SESSION['loadName']." style='color:orange;'>  "; ?><br>
					<label for="template">Template:</label> <input class='labeled' type="text" name="template" value="default" style="color:grey;" readonly><br>
					<label for="location">Location </label> <input class='labeled' type="text" value="/some/url/is/specified/"  style="color:grey;"><br>
					<label for="id">ID:</label>             <?php echo "<input class='labeled' type='text' name='id' size='3' value='" . $_SESSION['loadID'] . "' readonly style='color:grey;'><br>"; ?> 
					<label for="state">State:</label>       <?php echo "<input class='labeled' type='text' name='state' size='8' value='" . $_SESSION['loadState'] . "' readonly style='color:grey;'><br>"; ?> 
					<label for="updated">Updated:</label>   <?php echo "<input class='labeled' type='text' name='updated' size='8' value='" . $_SESSION['updated'] . "' readonly style='color:grey;'><br>"; ?> 
				</div>			
			</fieldset>
	 
		<fieldset class="collapsible">
		<legend>Nav</legend>
			<input type='submit' value="<-- Exit Editor" name="exit">
			<input type='submit' value='Create New Page' name="new">
			<br>
			<input type="text" name ="loadID" size="3" value="3">
			<input type='submit' value='Load' name="load" id="load">
		<br>
		<details style="padding:2px; margin-left:2px; background:none;">
		 <summary>Existing Page List</summary>
			<?php displayPages(); ?>
		</details>		
		</fieldset>		
	

	</div>


<textarea class="workspace" id="content" name="content" type="textarea" onKeyPress="hasChanged();" >  
<?php echo $_SESSION['loadContent']; ?> 
</textarea>
        
</form> 	       
	   
	
	   
</body>
<script>

	
	
(function($) {
        $.fn.collapsible = function(options) {
            this.each(function() {
            var $fieldset = $(this);
            var $legend = $fieldset.children("legend");
            var isCollapsed = $fieldset.hasClass("collapsed");
            var starter = $fieldset.hasClass("starter");

            $legend.click(function() {
                collapse($fieldset, !isCollapsed);
                isCollapsed = !isCollapsed;
                });
            // Perform initial collapse.
            collapse($fieldset, isCollapsed);
            if(starter) {
                collapse($fieldset, true);
                }
            });
        };

    // Collapse/uncollapse the specified fieldset.
    function collapse($fieldset, doCollapse) {
    $container = $fieldset.find("div");
    if(doCollapse) {
        $container.hide();
        $fieldset.removeClass("expanded").addClass("collapsed");
        } 


    else {
        $container.show();
        $fieldset.removeClass("collapsed").addClass("expanded");
        }
    };
    })(jQuery);
</script>
<script>
    $("fieldset.collapsible").collapsible();
</script>



</html>
<?php



    function displayPages(){
			//echo "inside";	
			$results = $_SESSION['DB']->query("SELECT p_id, name, DATE_FORMAT(updated, '%Y-%m-%d \n @%H:%i') AS 'updated' FROM pages;");
			$cols = ["ID","Name","Updated"];
			// Output.
			echo "<table>"
					. "<tr> <th>$cols[0]</th> <th>$cols[1]</th><th>$cols[2]</th></tr>";
			for ($i = 0 ; $i < $results->num_rows; ++$i){
				  $results->data_seek($i);
				  $row = $results->fetch_array(MYSQLI_NUM);
				echo "<tr>"; // the tr needs an onMouseOver="setLoadID(current row)" . 
				for ($j = 0 ; $j < count($cols) ; ++$j)		   
				echo "<td> " . htmlspecialchars($row[$j]) . "</td>";
				echo "<td><input type='submit' value='Load' name='load' id='load'></td></tr>";
				}
			echo "</table>";
			}


    function displayBooks(){
			//echo "inside";	
			
			$results = $_SESSION['DB']->query("SELECT b_id, name FROM books;");
            //$row = $results->fetch_array(MYSQLI_ASSOC);
			$cols = ["b_id", "name"];
			
			//echo "<span style='background:red;'>Some Book List<span>";
			//echo "<option name='book' value='$cols[0]'>$cols[1]</option>";
			
			
			for ($i = 0 ; $i < $results->num_rows; ++$i){
				  $results->data_seek($i);
				  $row = $results->fetch_array(MYSQLI_NUM);
				  if($row[0] == $_SESSION['loadBook']){
					echo "<option name='book' value='".htmlspecialchars($row[0])."' selected>".htmlspecialchars($row[1]) ."</option>";
					}
				  else{
					echo "<option name='book' value='".htmlspecialchars($row[0])."'>".htmlspecialchars($row[1]) ."</option>";
					}
				}
			}



			/*
				$results = $_SESSION['DB']->query("SELECT b_id, name FROM books;");
				$row = $results->fetch_array(MYSQLI_ASSOC);
				$_SESSION['B_ID'] =  htmlspecialchars($row['b_id']);
				$_SESSION['loadBook'] =  htmlspecialchars($row['name']);
			*/	



?>











