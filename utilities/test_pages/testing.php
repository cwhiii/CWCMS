<html><head>
<title></title>
<?php echo file_get_contents('/home/cwholema/public_html/playground/CWCMS/parts/boilerplate.html'); ?> 
</head><body>
<?php echo file_get_contents('/home/cwholema/public_html/playground/CWCMS/parts/header.html'); ?> 
<?php echo file_get_contents('/home/cwholema/public_html/playground/CWCMS/parts/sidebar.html');?> 
  
				<script>
					console.log('Spinning up the grill...');
					var asciidoctor = Asciidoctor();
					Promise.all([fetch('/playground/CWCMS/utilities/test_pages/testing.adoc').then(x => x.text())]) 
						.then(([raw, sample2Resp]) => {
						$('body').append(asciidoctor.convert(raw, { 'safe': 'unsafe' }));
						});
				</script>
				
</body></html>