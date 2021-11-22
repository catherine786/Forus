<?php 



/*
Esto que pagina es? seria un include para el index? otra vez, ignora todo el procedimiento que esta detallado en la descripción del trello. Esto iba por js

*/



	include 'database.php';
	$Populares= 'SELECT Name, Views
	FROM recipes
	ORDER BY Views DESC
	';
	$resultado = mysqli_query($link, $sql);
	echo $Populares;
	if(!$resultado){
	die("Falló la consulta: " . mysqli_error($link));
echo "<table border='1'>";
echo "<tr>";
		echo "<th>Nombre</th>";
		echo "<th>Vistas</th>";
echo "</tr>";
while ($fila = mysqli_fetch_assoc($resultado)) {
	echo "<tr>";
		echo "<td>" . $fila["Name"] . "</td>";
		echo "<td>" . $fila["customerName"] . "</td>";
	echo "</tr>";
}
echo "</table>";
mysqli_close($link);


¨/*for($i=0; $i<3; $i++){
	while ($fila = mysqli_fetch_assoc($resultado)) {
	?>
		<div class="col-md-4 mt-2">
      <div class="card text-center">
        <img src="images/platillodeldia.png" alt="platillodeldia">
        <div class="card-body">
          <h5 class="card-title">HOLA</h5>
          <p class="card-text">Nose</p>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>
	<?php
}*/
?>

	