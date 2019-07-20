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
	Promise.all([fetch('plain_jane.txt').then(x => x.text())])
		.then(([raw, sample2Resp]) => {
		$("body").append(asciidoctor.convert(raw, { 'safe': 'unsafe' }));
		});
</script>
	
<?php 
	echo "</body></html>";
?>

  