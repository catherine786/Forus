<?php include '../database/database.php';?>
<?php include '../partials/header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>RECETAS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <h1 class="title">Listado de Recetas</h1>
	<div class="conteiner col-sm-12">
		<div class="row">
			<div class="conteiner">
			<div class="conteiner">
            <?php for($i=0; $i < 30; $i++) {?> 
            <div class="card">
            <img src="../images/descarga.jpg"> 
            <p>Un platillo esquisito y fácil de cocinar</P> 
            <a href="#">leer mas..</a> 
            </div>
        <?php } ?>
   </div>
</body>
</html>
