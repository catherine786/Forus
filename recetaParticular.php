<?php     
require_once 'database/database.php';
require_once 'partials/session_start.php';
require_once 'partials/starfunc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>
<style>
    .titulo-comentarios{
        display: flex;
        justify-content: space-between;
    }
    .comentario{
        background-color: white;
        border-radius: 5px;
    }
    body{
      background-color: #E9E9E9;
    }
    .comentarios{
        background-color: red;
        border-radius: 5px;

    }
    .fav-btn{
        height: 45px;
        width: 100px;
        border: none;
        color: white;
        font-size: 25px;
        background-color: rgb(255, 0, 68);
        border-radius: 15px;
        box-shadow: inset 0 0 0 0 #f9e506;
        transition: ease-out 0.3s;
        font-size: 2rem;
        outLine: none;
    }
    .fav-btn:hover{
        box-shadow: inset 100px 0 0 0 #f9e506;
        cursor: pointer;
        color: #000;
    }
    .fav-btn img{
        margin-bottom: 11px;
    }
    .options{
        display: flex;
        justify-content: space-between;
    }
	
	  #absoluto{
    color: white;
    width: 100%;
    height: 100%;
    top: 32%;
    right: 0;
    position: absolute;
  }
  
  #absoluto{
	  text-shadow: -1px 1px 0 #000,
                          1px 1px 0 #000,
                         1px -1px 0 #000,
                        -1px -1px 0 #000;
  }
  
    header{
    width: 100%;
    height: auto;
    position: relative;
    overflow: hidden;
  }
</style>
<script>
    // $.(document).ready(function(){
    //    $(".fav").click(function(){
    //         $(this).toggleClass("active");
    //    });
    // });
</script>
<body>
    <?php include 'partials/header.php'?>
	<header class="mt-0">
				<div style="min-height:100vh;">
				<img id="insImg" class="image mt-3" src="asfkh" alt="Sin Imagen" onerror='this.src="images/noimage.png"' width = "100%" style="object-fit: cover;width:100%;height:100%;">
				</div>
				<div class = "container">
				  <div id = "absoluto" class = "text-center">
					<h1 id="insTitle" class = "display-1 mt-5"></h1>
					<h3 class = "display-4">Escrito por: <strong id="insAuthor"></strong></h3>
				  </div>
				</div>
			</header>
    <div id="container-receta" class='container'>
        <section>
            
            <div class="search-result-item-body">
                <div class="row">
                    <div class="col-sm-9">
						  
                        <p class="fs-mini text-muted mt-3">Esta receta tiene: <span id="insViews"></span> 👁️ </p>
						<div id="insVid"></div>
                        <hr>
                        <div id="insRecipe"></div> 
                        <br>
                    </div>
                </div>
            </div>
        </section>
        <div class="container-fluid">
        <?php echo $loggedin ? "<div class='options col-10'>¿Te gusto la receta? ¡No olvides darle a favoritos!<div id='replace'>aa<script>document.getElementById('replace').innerHTML=genstar(${_GET['r']});</script></div></div>" : '';?>
            
                
               
            
        </div>
        <h4 class="display-6 mt-4">Comentarios</h4>
        <div id='comentarios-section' class="comentarios-section container-fluid">
            
              
        </div>
        <?php include 'partials/footer.php' ?>
    </div>

    <script>
    callAPI ('rd',<?php echo $_GET['r']; ?>,function( result ) {
        if (result){
            a=result;
            document.getElementById("insTitle").innerText=result[0]['name'];
            document.getElementById("insAuthor").innerText=result[0]['username'];
            document.getElementById("insImg").src="images/recipe/"+result[0]['img_path'];
            document.getElementById("insViews").innerText=result[0]['views'];
            document.getElementById("insRecipe").innerHTML=result[0]['recipe'];
			
			document.getElementById("insVid").innerHTML=result[0]['code'] ? `<iframe src="//www.youtube.com/embed/${result[0]['code']}" style="width:100%;height:500px; border-radius:3px" allowfullscreen="" frameborder="0"></iframe>` : "";
			

            callAPI ('sc',<?php echo $_GET['r']; ?>,function( result ) {
                let str="";
                console.log(result);
                result.forEach(comment=>{
                    //console.log(comment);
                    str+=`<div class="comentarios p-2 mt-2">
                            <div class="titulo-comentarios">
                                <h6>${comment[0]['UserName']}</h6>
                                <h6 class="text-end">Hecho el dia ${comment[0]['Created_At']}</h6>
                            </div>
                            <h3 class="comentario col-12 p-3">${comment[0]['Text']}</h3>
                        </div>  `;
                });
                document.getElementById('comentarios-section').innerHTML=str;

            });

            callAPI ('iv',<?php echo $_GET['r']; ?>,function( result ){});
            var str=genstar(<?php echo $_GET['r']; ?>);
			setfavs();


      } else {
        document.getElementById('container-receta').innerHTML="Esta receta no existe";
      }
    });
    

    setfavs();
    </script>
</body>
</html>