<?php 
session_start();
session_unset();
session_destroy();

if (isset($_GET['url'])){
    header('Location: '.$_GET['url']);
} else {
    header('Location: index.php');
}


?>