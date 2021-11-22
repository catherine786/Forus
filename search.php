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
    <title>Recetario</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
		.info-search{
            margin-top: 15px;
			display: flex;
			justify-content: end;
		}
        #arriba{
            background:#024959;
            font-size:20px;
            color:#fff;
            border-radius: 15%;
            cursor:pointer;
            position:fixed;
            bottom:10px;
            right:10px;
            transform: translate(-20%, -20%);
        }
        .paginador a{
            text-decoration: none;
            color: black;
        }
        .container-fluid{
            max-height: 0px !important;
            max-width: 280px !important;
            border-line: 50px;
        }
        
        .image-link{
            max-width:100%;
        }
        .acortador{
            height: 210px !important;
            overflow: hidden;
        }
        #items-search{
            border-radius: 10px;
            border: 1px solid #BDBDBD;
            padding: 25px;
        }
		
		@media (min-width: 975px) {
			.buttonh {
				transform: translate(0%, -270px);
			}
			
			.vrmas{
			margin-top:200px
			}
			
			#items-search{
				max-height:400px;
			}
		}
		
	</style>
</head>
<body>
    <?php include 'partials/header.php' ?>
<?php
    if (isset($_GET['q'])){
        $q=$_GET['q'];
        $perpage=5;
        if (isset($_GET['page'])){
            $page = $_GET['page'];
        } else {$page = 0;}



        $sqlquery=" FROM recipes INNER JOIN users on recipes.User_ID=users.ID WHERE ( Name LIKE '%".mysqli_real_escape_string($link, $q)."%' OR Recipe LIKE '%".mysqli_real_escape_string($link, $q)."%' OR UserName LIKE  '%".mysqli_real_escape_string($link, $q)."%' ) AND recipes.Deleted_At IS NULL "; // Falta arreglar
        $qlen=ceil(mysqli_fetch_assoc(qq($link, "SELECT COUNT(recipes.ID) AS cOC".$sqlquery))['cOC']/$perpage);


        if ($page >=$qlen-5){
            $startpage=$qlen-10;
            $endpage=$qlen;
            
        } else if ($page>=5){
            $startpage=$page-5;
            $endpage=$page+5;
        
        } else{
            $startpage=0;
            $endpage=10;
            
        }
        if ($startpage<0){
            $startpage=0;
        }
        if ($endpage>$qlen){
            $endpage=$qlen;
        }
?>
        <?php
        $rows=qq($link, "SELECT *".$sqlquery."limit " . $page*$perpage . ",". $perpage);
        while ($row=mysqli_fetch_assoc($rows)){
        //	print_r($row);
            ?>
            <div class="dp-flex justify-items-center mt-5 p-4"> <!-- style="border-style:solid;border-color:lightgray;border-width:2px;border-radius:10px;width:70%;margin-right:auto;margin-left:5%;" --> <!-- style="border-style:solid;border-color:lightgray;border-width:2px;border-radius:10px;width:50%;margin-right:auto;margin-left:25%;" -->
            <div id="container-search" class="container dp-flex justify-content-center">
				<div id="items-search" class="row">
					<a class="image-link p-1 col-4" href="recetaParticular.php?r=<?php echo $row['ID']; ?>"><img class="image" src="<?php echo isset($row['img_path']) ? 'images/fromusers/'.$row['img']:'images/noimage.png' ?>" ></a>
					<div class="col-9 col-lg-8 dp-flex justify-content-center">
					
						<div style="justify-content:space-between" class="d-flex">
							<h4 style="display:inline-block">
								<a href="recetaParticular.php?r=<?php echo $row['ID']; ?>">
								 <?php echo $row['Name']?>
								</a>
							</h4>
						
							<span class="text-end text-muted"><?php echo $row['Created_At'] ?></span>
						</div>
						<p class="info text-muted">
                            Creado por:
							<?php
							echo mysqli_fetch_assoc(qq($link, "SELECT UserName FROM users WHERE ID = ".$row['User_ID']))['UserName'];
							?>                            
						</p>
                        <div class="acortador">
                            <p class="description">
						    	<?php echo $row['Recipe'];  ?>
						    </p>
                        </div>
                        <div class="container text-end p-2 buttonh">
				
									
                <span><?php echo $row['Views'] ?><span style="color:gray"> 👁</span>
                    <?php mysqli_fetch_assoc(qq($link, "SELECT COUNT(User_id) AS cOC FROM favorites WHERE Recipes_id = ".$row['ID']))['cOC'] ?> 
                    <?php echo $loggedin ? "<div id='replace${row['ID']}'>aa<script>document.getElementById('replace${row['ID']}').innerHTML=genstar(${row['ID']});</script></div>" : '';?>
                </span>
                <a class="btn btn-primary btn-info btn-sm vrmas" href="recetaParticular.php?r=<?php echo $row['ID']; ?>">Ver más</a>

        
            </div>
					</div>
                    
				</div>
                
            </div>
            </div>
            
  <?php } ?>
  <div class="container rounded mt-4" >
            <div class="row">
                <div class="col-12">
                    <?php if ($qlen>1){ ?>
                    <ul class="pagination dp-flex justify-content-center">
                    <?php
                    $temp=$page-1;
                    $spchar=strpos($_SERVER['REQUEST_URI'],"?") ? '&' : '?';
                    $isdis = 0==$page ? " disabled" : ""; ?>
                    <li class="page-item <?php echo $isdis; ?>"> 
                        <a class="page-link" href="<?php echo $_SERVER['SCRIPT_NAME']."?q=".$_GET["q"]."&page=0"; ?>"  <?php echo $isdis; ?>>
                            «
                        </a>
                    </li>
                    <li class="page-item <?php echo $isdis; ?>"> 
                        <a class="page-link" href="<?php echo $_SERVER['SCRIPT_NAME']."?q=".$_GET["q"]."&page=".$temp; ?>"  <?php echo $isdis; ?>>
                            ‹
                        </a>
                    </li>
                    <?php
                    for ($i=$startpage;$i<$endpage;$i++){
                        $isdis = $i==$page ? "disabled" : "";
                        $isact = $i==$page ? " active" : "";
                        ?>
                        <li class="page-item <?php echo $isact." ".$isdis ?>">
                            <a class="page-link" href="<?php echo $_SERVER['SCRIPT_NAME']."?q=".$_GET["q"]."&page=${i} "?>" <?php echo $isdis; ?> > <?php echo $i ?> </a>
                        </li>
                        <?php
                    }
                    $temp=$page+1;
                    $temp2=$qlen-1;
                    $isdis = $qlen-1==$page ? "disabled" : "";
                    ?>

                    <li class="page-item <?php echo $isdis; ?>">
                        <a class="page-link" href='<?php echo $_SERVER['SCRIPT_NAME']."?q=".$_GET["q"]."&page=". $temp?>' <?php echo $isdis ?>>
                            ›
                        </a>
                    </li>
                    <li class="page-item <?php echo $isdis; ?>">
                        <a class="page-link" href='<?php echo $_SERVER['SCRIPT_NAME']."?q=".$_GET["q"]."&page=" . $temp2; ?>' <?php echo $isdis; ?>>
                            »
                        </a>
                    </li>

                    </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="espaciador mt-4"></div>
<?php } else { ?>


<h1>
    esto tiene que quedar como la pagina principal de google (≧∇≦)ﾉ
</h1>



<?php } ?>
	<script>
		setfavs()
	</script>
</body>