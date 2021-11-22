<?php
if (!isset($_SESSION)){
	session_start();
}

$loggedin=isset($_SESSION["user"]) ? 1 : 0; 


if (isset($_SESSION["msg"])){
	?>
    <script>
		
		function showErrMsg(){
			setTimeout(function() {
				Swal.fire({
					icon: "<?php echo $_SESSION["icon"]; ?>",
					title: "<?php echo $_SESSION["msg"]; ?>",
					backdrop: true,
					timer: 2000
				});
			}, 0.01);

		}
		
		window.onload=showErrMsg;
	</script>
    <?php
	$_SESSION["msg"]=null;
}





?>
