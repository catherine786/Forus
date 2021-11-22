<?php
	include 'partials/session_start.php' ;
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
?>

<html>
	<head>
		<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script>
			$(function() {
				$('#save').click(function() {
						var mysave = $('.ck-content').html();
						console.log(mysave);
						
						$('#text-area').val(mysave);
				});
			});
		</script>
		
	</head>
	<body>
		<div class="cont1">
			<form action="http://localhost/Forus/api/api.php?qt=mr&v=0" method="post">
				<div name="texto" id="editor">
					
				</div>
				<textarea name="text-area" id="text-area" style="display:none;">
				</textarea>
				<input id="save" name="b" type='submit'>
			</form>
		</div>
		<script>
			ClassicEditor.create(document.querySelector('#editor'))
			.catch(error =>{
				console.log('Error');
			});
		</script>
	</body>
</html>