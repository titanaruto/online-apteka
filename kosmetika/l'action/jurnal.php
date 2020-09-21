
	<style>
		body, html{
			margin: 0;
			padding: 0;
			height: 100%;
			overflow: hidden;
			display: block !important;
		}
		
		#content-frame{
			position: absolute;
			left: 0;
			right: 0;
			bottom: 0;
			top: 0;
		}
		#content-frame iframe html{
			display: none !important;
		}
	</style>
	
	<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js'></script>

</head>
<body>
	<div id="content-frame">
		<iframe width="100%" height="100%" src="https://online.fliphtml5.com/gnuj/ewln/#p=1" frameborder="0" allowfullscreen allowtransparency></iframe>
	</div>
	<script>
		$('#frame').load(function(){
			//alert('done');
			
			$(this).find('iframe').remove();
			  
		});
		</script>
</body>

 