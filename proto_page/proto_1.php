<?php 
	echo "<html><head>"; 
	echo "<title>"."PROTO PAGE"."</title>"; // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	echo file_get_contents('../parts/boilerplate.html'); 
	echo "</head><body>	";
	echo file_get_contents('../parts/header.html'); 
	echo file_get_contents('../parts/sidebar.html');
?>

<script>
	console.log('Spinning up the grill...');
	var asciidoctor = Asciidoctor();
	var raw = `<?php echo file_get_contents('../utilities/test_pages/testing.adoc');?>`; // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	var cooked = asciidoctor.convert(raw, { 'safe': 'unsafe' });
	document.write(cooked);
</script>
	
<?php 
	echo "</body></html>";
?>

		
