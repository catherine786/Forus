function showErrMsg(){
    setTimeout(function() { 
        alert('<?php echo $_SESSION["msg"]; ?>'); 
    }, 0.01);
}
window.onload=showErrMsg;