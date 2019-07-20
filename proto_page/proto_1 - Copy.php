<html>
	<head>
		<meta charset='utf-8'/>
		<title>".$_SESSION['TITLE']."</title>
		<script src='/playground/CWCMS/utilities/asciidoctor.min.js'></script>
	</head>
	<body>						
		<script>
			var asciidoctor = Asciidoctor();
			var raw = `".$_SESSION['RAW_CONTENT']."`;
			console.log('Spinning up the grill...');
			var cooked = asciidoctor.convert(raw, { 'safe': 'unsafe' });
			document.write(cooked);
		</script>
	</body>
</html>