<?php
	require_once("partials/session_start.php");
	if (isset($_GET['r'])){
		$rnum=$_GET['r'];
	} else {
		$rnum=0;
	}


?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="favicon.png">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
		<meta charset="UTF-8">
    	<title>Receta</title>
		<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script>
			$(function() {
				$('#save').click(function() {
						var mysave = $('.ck-content').html();
						//console.log(mysave);
						
						$('#text-area').val(mysave);
				});
			});
		</script>
		<?php require_once 'partials/starfunc.php'; ?>
		<style>
			@import url('https://fonts.googleapis.com/css2?family=Raleway&display=swap');
			.cont1{
				margin:60px;
			}
			.form_title{
				text-align: center;
				font-family: 'Raleway', sans-serif;
			}
			.form-row{
				text-align: center;
				font-family: 'Raleway', sans-serif;
			}
			.form2{
				margin:30px;
			}
			.img{
				width: 30%;
				border-radius: 10px;
			}
			.button1 {
				text-decoration:none;
				font-family: 'Raleway', sans-serif;
				font-weight: 600;
				font-size: 20px;
				color:#ffffff;
				padding-top:15px;
				padding-bottom:15px;
				padding-left:30px;
				padding-right:30px;
				background-color:#0c9129f8;
				border-color: #0c4715f8;
				border-width: 2px;
				border-style: solid;
				border-radius:10px;
			}

		</style>
	</head>
	<body>
	<?php include 'partials/header.php'?>

<div class="cont1 shadow">
	<div class="col p-3">
		<div class="text-end">
			<a href="index.php"><img src="images/LogoMakr-56L0gt.png" alt="logo" width="6%" href="index.php"></a>
		</div>      
			<h1 class= "form_title">Mi receta</h1>

		<form onsubmit="return saverecipe(event)" id="fullform">
			<div class="form-row">
				<div class="form2"> 
					<input id='qt' name='qt' type='hidden' value='mr'>
					<label for="name"><b>Titulo de la Receta: </b></label>
					<input id="name" name="name" type='text' class="form-control" placeholder="Escriba el nombre de su receta aquí">
				</div>
			</div>
		<br>
			<div class="form-row">
				<div class="form2">
					<label for="img" class="form-label"><b>Imagen:</b></label>
					<input id="img" name="img" type='file' onchange="displayimg(this)">
					<p><b>Imagen actual:</b></p>
					<img class="img"  src="" id="cimg">
				</div>
		<br>
			<div class="form-row">
				<div class="form2">
					<label for="code"><b>Video de youtube: </b></label>
					<input for="code" class="form-control" id="code" name="code" type='text' placeholder="Pegué aquí la URL de su video (opcional)">
				</div>
		<br>
			<div class="form2">
				<div name="texto" id="editor">
			</div>
					<textarea name="recipe" id="text-area" style="display:none;"></textarea>
					<?php if ($rnum){ ?>
						<input id='v' name='v' type='hidden' value='<?php echo $rnum; ?>'>
					<?php }  ?>
		<input  class="button1 mt-2"id="save" name="b" type='submit'>
			</form>
	<div>
</div>
		<script>
			editor=ClassicEditor.create(document.querySelector('#editor'))
			.catch(error =>{
				//console.log('Error');
			});
			<?php if ($rnum){ ?>
			callAPI('rd','<?php echo $rnum; ?>',function(result){
				if (result[0]['user_id']!=<?php echo $_SESSION['id']; ?>){
					alert('Esta no es tu receta!');
					window.location.href="misrecetas.php";
					throw new Error("User IDs Dont match");
				}
				document.getElementById('name').value=result[0]['name'];
				document.getElementById('cimg').src="images/recipe/"+result[0]['img_path']
				document.getElementById('code').value=result[0]['code'];
				//console.log(result);
				editor.then(editorobj =>{editorobj.setData(result[0]['recipe'])})
				
			});
			<?php }  ?>

			function saverecipe(event){
					event.preventDefault();
					var formData = new FormData(document.getElementById("fullform"));
					$.ajax({
						url: window.location.pathname.split('/').slice(0,-1).join('/')+"/api/api.php",
						dataType:"json",
						method:"post",
						data: formData,
						processData: false,
    					contentType: false,
						success: function(result){
							if(result){
								window.location.href="misrecetas.php"
							} else {
								alert('no se pudo subir');
							}
						}
					});
					return false;
					
   			 }

			function displayimg(input){
				if (input.files && input.files[0]) {
					var reader = new FileReader();

					reader.onload = function (image) {
						document.getElementById("cimg").src=image.target.result;
					};

					reader.readAsDataURL(input.files[0]);
        		}
			} 
		</script>
	</body>
</html>