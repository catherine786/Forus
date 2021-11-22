<?php
require_once("database/database.php");
require_once("partials/session_start.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="shortcut icon" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    <meta charset="UTF-8">
    <title>Receta</title>
    <link rel="stylesheet" href="rich_text/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="rich_text/css/richtext.min.css">
	<script></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="rich_text/js/jquery.richtext.min.js"></script>
</head>
<body>
	<?php include 'partials/header.php'; 
	

	
	$r=isset($_GET['r']) ? $_GET['r'] : '3';
	$recipe=mysqli_fetch_assoc(qq($link, "SELECT * FROM Recipes WHERE ID = ".$r));
	
		if (!isset($_SESSION['id'])){ 
		$_SESSION['msg']="no estas logueado";
		?> 
		
		<script>
			window.location.href = "index.php";
			</script>
			<?php
			
			exit();
		
	} else if (!($recipe['User_ID']==$_SESSION['id'])){
		$_SESSION['msg']=$_SESSION['id']." Esta no es tu receta ".$recipe['User_ID'];
		?> 
		<script>
			window.location.href = "index.php";
			</script>
			<?php
			
			exit();
		
	}
	
	?>
	
	<form action='savepage.php'>
	<input type='text' name='title' placeholder='Titulo' value=<?php echo $recipe['Name'] ?>></input>
	
    <div id="contenedor">
        <input type="text" id="Receta">
        <script>
            $(document).ready(function(){
                $('#Receta').richText();
            });
        </script>
    </div>
    
	<input class="submittedText" type='hidden' name='title'  value=<?php $recipe['Recipe'] ?>>
	<input type="submit">	
	
	
	</form>
	
</body>
</html>