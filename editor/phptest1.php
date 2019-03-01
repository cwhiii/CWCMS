<html>
<head>
<!-- cwholema_playground cwholema_cwhiii playground1 -->
<title> CWCMS Playground </title>
<head>
<body>
<p> Hello, World! This is <?php echo date("l") ?>. </p>

<p>
<?php
	$servername = "localhost";
	$username = "cwholema_cwhiii";
	$password = "playground1";

	// Create connection
	$conn = new mysqli($servername, $username, $password);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	echo "Connected successfully";

	$conn->close(); 
?>

</p>
<p>

Tags: <br>

footer, nav, abbr, mark, picture, progress, q, code, section, template, wbr
 </p>
 
 
 <details>
  <summary>Copyright 1999-2018.</summary>
  <p> - by Refsnes Data. All Rights Reserved.</p>
  <p>All content and graphics on this web site are the property of the company Refsnes Data.</p>
</details>  


<dl>
  <dt>Coffee</dt>
  <dd>Black hot drink</dd>
  <dt>Milk</dt>
  <dd>White cold drink</dd>
</dl>


<figure>
  <img src="../html/pic_trulli.jpg" alt="Trulli" style="width:100%">
  <figcaption>Fig.1 - Trulli, Puglia, Italy.</figcaption>
</figure>

<form>
 <fieldset>
  <legend>Personalia:</legend>
  Name: <input type="text"><br>
  Email: <input type="text"><br>
  Date of birth: <input type="text">
 </fieldset>
</form>	

</body>
</html>


