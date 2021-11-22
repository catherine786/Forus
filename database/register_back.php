<?php
require 'partials\session_start.php';

if (isset($_SESSION["id"])){
	$_SESSION["msg"]="Ya estas logeado y registrado!";
	$_SESSION["icon"]="info";
	header('Location: index.php');
	exit;
}

if(isset($_POST['registrarse'])){
	if(!empty($_POST['usr']) && !empty($_POST['pwd']) && !empty($_POST['pwdc']) && !empty($_POST['Email'])){
		if ($_POST['pwd']==$_POST['pwdc']){

			$sqlquery= 'select * from users where users.UserName = "' . mysqli_real_escape_string($link, $_POST['usr']) . '"';
			if(!($result = qq($link, $sqlquery))){exit(mysqli_error($link));}
			
			if (mysqli_num_rows($result) == 0) { 
				$sqlquery="insert into users VALUES(null,'" . mysqli_real_escape_string($link, htmlspecialchars($_POST['usr'])) . "' , md5('" . mysqli_real_escape_string($link, $_POST['pwd']) . "'), '". mysqli_real_escape_string($link, htmlspecialchars($_POST['Email'])) ."',now(),null)";

				if(!(qq($link, $sqlquery))){exit(mysqli_error($link));}
				//echo("account created");
				session_unset();
				session_destroy();
				session_start();

				$_SESSION["user"]=mysqli_real_escape_string($link, htmlspecialchars($_POST['usr']));
				$_SESSION["msg"]="Cuenta creada con exito!";
				$_SESSION["icon"]="success";
				$sqlquery='select * from users where users.UserName = "' . $_SESSION["user"] . '"';
				$_SESSION["id"]=mysqli_fetch_assoc(qq($link, $sqlquery))["ID"];
				header('Location: index.php');
				exit;
			} else { 
				$_SESSION["msg"]= 'Este usuario ya existe';
				$_SESSION["icon"]="error";
			}  
		
		} else {
			$_SESSION["msg"]= 'Las contraseñas son diferentes';
			$_SESSION["icon"]="error";
		}
	
} else {
	if(empty($_POST['usr']) || empty($_POST['pwd']) || empty($_POST['Email']) || empty($_POST['pwdc'])){
			$_SESSION["msg"]= 'Rellena todos los campos';
			$_SESSION["icon"]="error";
	   }}
}
	   
require 'partials\session_start.php';

?>