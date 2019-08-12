<?php
	$submittedID = $_POST['cat'];
	echo $submittedID ;
?>

<html>
	<head>
		<script  async type="text/javascript" src="/scripting/jquery/jquery.min.js"></script>
		
	</head>
	<body>
		<form method="post" id="leagueForm" action="test.php" enctype="multipart/form-data">
			<input name="cat" id="cat" type="text"  />
			
			<input type="submit" value="go" />
		</form>
		<button id="setText" onClick='$("#cat").val("HELLO");'>set</button>
	</body>
</html>

