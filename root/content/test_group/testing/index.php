<html>
<head>
<title>Third</title>
<?php $_SERVER['PATH'] = $_SERVER['DOCUMENT_ROOT'].'/playground/CWCMS'; ?> 
<?php echo file_get_contents($_SERVER['PATH'].'/parts/boilerplate.html'); ?> 
</head><body>
<?php echo file_get_contents($_SERVER['PATH'].'/parts/header.html'); ?> 
<?php echo file_get_contents($_SERVER['PATH'].'/parts/sidebar.html');?> 
 
				<script>
					console.log('Spinning up the grill...');
					var asciidoctor = Asciidoctor();
					Promise.all([fetch('/playground/CWCMS/root/content/test_group/testing/ascii.adoc').then(x => x.text())])  
						.then(([raw, sample2Resp]) => {
							$('body').append(asciidoctor.convert(raw, { 'safe': 'unsafe' }));
						});
						
				</script>
				
</body>
</html>