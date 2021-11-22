 <?php
    define('DB_SERVER',"localhost");
    define('DB_USER',"root");
    define('DB_PASS',"");
    define('DB_NAME',"pasteleria");
    
    $link = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    mysqli_set_charset($link, "utf8");
    if(!$link){
        echo "Error en con la conexion a MySQL " . "<br>";
        echo "Error de depuracion " . mysqli_error() . "<br>";
        echo "Errno de depuracion " . mysqli_errno() . "<br>";
        exit();
    }

    function qq ($link, $query){
        if(!($tres = mysqli_query($link, $query))){exit(mysqli_error($link)." , in query ".$query);}
        return $tres;
    }
	

   
?>
