<?php
require 'partials\session_start.php';

if (isset($_SESSION["id"])){
	$_SESSION["msg"]="Ya estas logeado!";
	$_SESSION["icon"]="info";
	header('Location: index.php');
	exit;
}

if (isset($_POST['loguearse'])){
	if(!empty($_POST['usr']) && !empty($_POST['pwd'])){
	
		$sqlquery= 'select * from users where users.UserName = "' . mysqli_real_escape_string($link, htmlspecialchars($_POST['usr'])) . '" OR users.Email = "' . mysqli_real_escape_string($link, htmlspecialchars($_POST['usr'])) . '"';
		if(!($result = qq($link, $sqlquery))){exit(mysqli_error($link));}
		
		if (mysqli_num_rows($result) == 0) { 
			$_SESSION["msg"]="El nombre de usuario o contraseña es incorrecto";
			$_SESSION["icon"]="error";
		} else { 
			if  (md5($_POST['pwd']) == ($assoc= mysqli_fetch_assoc($result))["Password"]){
			//   echo("logged in");
			   session_unset();
			   session_destroy();
			   session_start();
			   $_SESSION["user"]=mysqli_real_escape_string($link,$_POST['usr']);
			   $_SESSION["msg"]="Te logueaste correctamente!";
			   $_SESSION["icon"]="success";
			   $_SESSION["id"]=$assoc["ID"];
				header('Location: index.php');
				exit;
		   } else {
				$_SESSION["msg"]="El nombre de usuario o contraseña es incorrecto!"; // El usuario y/o contraseña esta mal
				$_SESSION["icon"]="error";
			}
		}  
			
		
		
	} else {
		$_SESSION["msg"]="Rellena todos los campos";
		$_SESSION["icon"]="error";
	}
}

require 'partials\session_start.php';
?>