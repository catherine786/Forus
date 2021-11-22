<?php 
include 'partials/session_start.php' ;
require_once 'partials/starfunc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Recetas</title>
	<link rel="stylesheet" href="css/estilos.css">
    <link rel="shortcut icon" href="cutlery.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	
</head>
<style>
	#cartita{
		max-width: 25%;
	}
	#cartita img{
		margin-top: 10px;
	}
	.page-item{
		cursor: pointer;
	}
	.selects{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
	}
	.selects form{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
	}

</style>
<body> 

<?php include 'partials/session_start.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="cutlery.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  <script>
	  let page=0;
	  let condition=0;
	  let direction=0;
	  let public=0;
  </script>
    <?php include 'partials/header.php'?>
	<div class="container mt-2">
		<div class="row d-flex justify-content-end"> 
			<div class="selects">
				<form class="p-3">
					<select id="condition" name="condition" onchange="updateCards ()" class="form-select">
						<option value='a'>Alfabético</option>
						<option value='c'>Cronológico</option>
						<option value='f'>Por Favoritos</option>
						<option value='v'>Por Vistas</option>
						<option value='p'>Por Popularidad</option>
					</select>
					<select id="direction" name="direction" onchange="updateCards ()" class="form-select">
						<option value='a'>Ascendiente</option>
						<option value='d'>Descendiente</option>
					</select>
					<select id="public" name="public" onchange="updateCards ()" class="form-select">
						<option value='ev'>visibles</option>
						<option value='nv'>No visible</option>
					</Select>
				</form>
			</div>
		</div>
	</div>

    <div id="container" class="container">
		<div class = "container mt-3">
			<h1 class ="display-1 text-center">Mis Recetas</h1>
			<div id="cardbox" class = "row mt-5">
		</div>
	</div>
	<script>

		function updateCards (){
			conditionElement = document.getElementById('condition');
			condition=conditionElement.options[conditionElement.selectedIndex].value;
			directionElement = document.getElementById('direction');
			direction=directionElement.options[directionElement.selectedIndex].value;
			directionElement = document.getElementById('public');
			direction=directionElement.options[directionElement.selectedIndex].value;
			let str="";
			callAPI('yr',`${condition}${direction}${public}`, function( result ) {

				str+=makepager(result[0], page);
				//console.log(result);
				let ajaxvalues=[];
				for (i=page*9;i<page*9+9;i++){
					if(result[0][i]){
					ajaxvalues.push(result[0][i]);
					}
				}
				callAPI('rd',[`${condition}${direction}${public}`]+','+ajaxvalues.join(','), function( result ) {
						
						result.forEach(recipe=>{
							str+=gencard(recipe['id'],recipe['name'],recipe['recipe'],recipe['username'],recipe['views'],recipe['img_path'], recipe['code'], true, true, false);
						});
						document.getElementById('cardbox').innerHTML=str;
						setfavs()
				});
			});
			
			
		}
		updateCards ()
	</script>
	<?php include 'partials/footer.php' ?>
  	</div>
</body>
</html>


</body>
</html>