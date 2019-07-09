<?php   
    /*      Page Editor 0.1 for CWCMS. 
     *      www.cwholemaniii.com
     *      codeMonkey@cwholemaniii.com
     *
     *      Created:        2 March 2019. 	
     *      Modified:       27 March 2019.
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

    
    function loadPage($aPage){
        // Validate ID.
        if (validateID($aPage)){
            $results = $_SESSION['DB']->query("SELECT p_id, name, content, DATE_FORMAT(updated, '%Y-%m-%d @ %H:%i') AS 'updated'  FROM pages WHERE p_id = $aPage;");
            $row = $results->fetch_array(MYSQLI_ASSOC);
            $_SESSION['loadID'] =  htmlspecialchars($row['p_id']);
            $_SESSION['loadName'] =  htmlspecialchars($row['name']);
            $_SESSION['loadContent'] =  htmlspecialchars($row['content']);
            $_SESSION['updated'] =  htmlspecialchars($row['updated']);
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

    
    
    // Handles form submissions.
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        // Save content from the form to the database.
        if($_POST['save']){
            echo "Saving.";
            // Pull the values from the form.
            $inName = trim($_POST['name']);
            $inContent = trim($_POST['content']);
            $inID = trim($_POST['id']);
            
            // Validate ID.
            if (validateID($inID)){
                $_SESSION['DB']->query("UPDATE pages SET "
                    . "p_id = '$inID', "
                    . "name = '$inName', "
                    . "content = '$inContent', "
                    . "updated = CURRENT_TIMESTAMP "
                    . "WHERE p_id = $inID;"
                    );
                }    
            else {
                echo "<div style='background:red;'><p style='font-size:2em;'>Could not save: Attempting to save invalid Page ID: '$inID'.</p></div>";
                }
            loadPage($inID);    
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
    <LINK REL="SHORTCUT ICON" HREF="/images/parts/favicon.ico" />
    <meta charset="utf-8"> 
    <script>
        <!-- Handles activation & deactivation of sub Groups. -->
        function toggleGroups(){		
            var value = document.getElementById("isGroup").value;

            if(value == "collection") {
                document.getElementById("whichGroup").disabled = false;
                $("#groupings").show();
                }
            else if(value == "series") {
                $("#groupings").show();
                document.getElementById("whichGroup").disabled = false;
                document.getElementById("sequenceNumber").disabled = false;
                }
            else{
                $("#groupings").hide();
                document.getElementById("whichGroup").disabled = true;
                document.getElementById("sequenceNumber").disabled = true;
                document.getElementById('whichGroup').value="-";
                document.getElementById('sequenceNumber').value="-";
                }
            }
    </script>
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
    <style>
        body {
            padding:0;
            margin:0;
            background:radial-gradient(circle at bottom left, rgba(78,179,147,1), rgba(49,125,157,1));
            }
        form {
            display: grid;
            height: 100vh;
            width: 100vw;
            grid-template-columns: 22vw 78vw;
            grid-template-rows: 15vh 85vh;
            padding:0;
            margin:0;

            }
        header {
            grid-column-start: 1;
            grid-column-end: 2;
            grid-row-start: 1; 
            grid-row-end: 2;
            padding: 1em; 
            background:none;
            }
        .tools {
            grid-column-start: 1;
            grid-column-end: 2;
            grid-row-start: 2;
            grid-row-end: 3;
            padding: 1em 0.25em 1em 0.25em;
            background:none;
            }
        .workspace {
            grid-column-start: 2;
            grid-column-end: 3;
            grid-row-start: 1;
            grid-row-end: 3;
            padding: 1em;
            background:ivory;
            }
        label{
            width: 30%;
            float: left;
            text-align: right;
            }      
        .labled{
            width: 65%;
            margin-left: 5%;
            float:left;
            }   

        .leftButton{
            width: auto;
            }     
        .confirmBox {
            background:grey;
            display:inline-block;
            margin: auto;
            text-align: center;            
            }
            
    </style>
</head>
<body>
    
<form method='post' action='index.php' enctype='multipart/form-data'>
    
    <header> <!-- USE??? <header> ??? -->
        <h1>
            <img src="pen.png" class="headerIcon">
            CWCMS  <br> Page Editor
        </h1>
        <h3><i>Some Page Title</i></h3>
    </header>
    <div class="tools">
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
                <label for="title">Title:</label>  <input class='labled' type="text" name="title" value="default"><br>
                <label for="title">Group:</label> 
                <div class='labled' >
                    <select id="isGroup" onChange="toggleGroups();" disabled>
                        <option type="radio" name="standalone" value="standalone">Standalone</option>
                        <option type="radio" name="collection" value="collection">Collection</option>
                        <option type="radio" name="series" value="series">Series</option>
                    </select><br>
                    <span style="display:none;" id="groupings" style="width:100%;"> 
                        <select id="whichGroup" disabled>
                            <option>-</option>
                            <option>Group 1</option>
                            <option>Group 2</option>
                            <option>New</option>
                        </select>
                        <select id="sequenceNumber" disabled>
                            <option>-</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                        </select>
                    </span>
                </div>
                <br>
                <label for="name">Name:</label>         <?php echo "<input class='labled' type= 'text' name='name' id='name' size='25' onKeyPress='hasChanged();' value=".$_SESSION['loadName'].">  "; ?><br>
                <label for="template">Template:</label> <input class='labled' type="text" name="tempalte" value="default" style="color:grey;" readonly><br>
                <label for="location">Location </label> <input class='labled' type="text" value="/some/url/is/specified/"  style="color:grey;"><br>
                <label for="id">ID:</label>             <?php echo "<input class='labled' type='text' name='id' size='3' value='" . $_SESSION['loadID'] . "' readonly style='color:grey;'><br>"; ?> 
                <label for="state">State:</label>       <?php echo "<input class='labled' type='text' name='state' size='8' value='" . $_SESSION['loadState'] . "' readonly style='color:grey;'><br>"; ?> 
                <label for="updated">Updated:</label>   <?php echo "<input class='labled' type='text' name='updated' size='8' value='" . $_SESSION['updated'] . "' readonly style='color:grey;'><br>"; ?> 
            </div>			
        </fieldset>
        <fieldset  class="collapsible">
            <legend>Nav</legend>
            <input type='submit' value="<-- Exit Editor" name="exit">
            <input type='submit' value="New" name="new">
            
            
            
            
            <hr>
            <div>
                <div><h2 style='color:black; text-align:center;'>Load Page</h2></div>
                <div style="float:left;">
                    <!-- < ?php
                        displayPages();
                    ?>-->
                </div>

                <div style="float:left; margin:1em;">    
                    <form method='post' action='.php' enctype='multipart/form-data' onSubmit="return confirmDiscardLoad(this);">
                        <hr>
                        Input Fleck ID: <input type="text" name ="loadFleckID" size="3" value="3">
                        <input type='submit' value='Load' name="load" id="load">
                        <hr>
                    </form>
                    <form method='post' action='index.php' enctype='multipart/form-data' onSubmit="return confirmDiscardNew(this);">
                        <input type='submit' value='Create New Fleck' name="new">
                    </form>
                </div>
            </div> 
    
            
            
            
            
            
            
        </fieldset>
    </div>

<textarea class="workspace" style="float:right;" id="content" name="content" type="textarea"    onKeyPress="hasChanged();" > 
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


