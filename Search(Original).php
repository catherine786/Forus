<?php
    require_once 'database/database.php';
    require_once 'partials/session_start.php'; 
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
    <style>
        .col-12{
            background-color: red;
        }
        #footer{
            body{margin-top:20px;}
        

        .recipe{
            display:flex;
            flex-direction: row;
            justify-content: space-around;
            max-height:100px;
            width= 90%;
        }  
        
        .recipe p{
            overflow: hidden;
        }
    </style>
    
</head>
<body>
    <?php include 'partials/header.php' ?>
<?php
    if (isset($_GET['q'])){
        $q=$_GET['q'];
        $perpage=2;
        if (isset($_GET['page'])){
            $page = $_GET['page'];
        } else {$page = 0;}



        $sqlquery=" FROM recipes WHERE Name LIKE '%".mysqli_real_escape_string($link, $q)."%' OR Recipe LIKE '%".mysqli_real_escape_string($link, $q)."%' ";
        $qlen=ceil(mysqli_fetch_assoc(qq($link, "SELECT COUNT(ID) AS cOC".$sqlquery))['cOC']/$perpage);
        qq($link, "SELECT *".$sqlquery."limit " . $page*$perpage . ",". $perpage);


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
        
        $temp=$page-1;
        $spchar=strpos($_SERVER['REQUEST_URI'],"?") ? '&' : '?';
        echo "<a href='".$_SERVER['REQUEST_URI'].$spchar."page=0'><<</a><span> </span>";
        echo $page!=0 ? "<a href='".$_SERVER['REQUEST_URI'].$spchar."page=". $temp ."'><</a><span> </span>" : '' ;
        for ($i=$startpage;$i<$endpage;$i++){
            echo "<a href='".$_SERVER['REQUEST_URI'].$spchar."page=${i}'>${i}</a><span> </span> " ;
        }
        $temp=$page+1;
        $temp2=$qlen-1;
        echo $page!=$temp2 ? "<a href='".$_SERVER['REQUEST_URI'].$spchar."page=". $temp ."'>></a><span> </span>" : '';
        echo "<a href='".$_SERVER['REQUEST_URI'].$spchar."page=" . $temp2 . "'>>></a><span> </span>";

        $rows=qq($link, "SELECT *".$sqlquery."limit " . $page*$perpage . ",". $perpage);
?>

        
        <div style='max-width:80%;margin:10px'>
        <?php

        while ($row=mysqli_fetch_assoc($rows)){
        //	print_r($row);
            ?>
            <div class="recipe container border border-secondary rounded">
			<div class="row">
				<div class="col-sm-4">
                <a class="image-link" href="recipe.php/?r=<?php echo $row['ID']; ?>"><img class="image" src="<?php echo isset($row['img_path']) ? 'images/fromusers/'.$row['img']:'images/noimage.png' ?>"></a>
                </div>
                <div class="col-sm-8">
                    <h4>
                        <a href="recipe.php/?r=<?php echo $row['ID']; ?>">
                            <?php echo $row['Name'] ?>
                        </a>
                    </h4>
                    <p class="info">
                        <?php
                        echo mysqli_fetch_assoc(qq($link, "SELECT UserName FROM users WHERE ID = ".$row['User_ID']))['UserName'];
                        ?>                            
                    </p>
                    <p class="description">
                        <?php echo $row['Recipe'];  ?>
                    </p>
                </div>

                <div class="col-sm-2 text-align-center">
                    <p class="value3 mt-sm"><?php echo $row['Views'] ?><span style="color:gray"> 👁</span></p>
                    <p class="fs-mini text-muted">
                        <?php
                            mysqli_fetch_assoc(qq($link, "SELECT COUNT(User_id) AS cOC FROM favorites WHERE Recipes_id = ".$row['ID']))['cOC']
                        ?> <span style="color:gold;font-size=20;">☆</span>
                    </p>
                    <a class="btn btn-primary btn-info btn-sm" href="recipe.php/?r=<?php echo $row['ID']; ?>">Ver más</a>
                </div>
            </div></div>
        <br>
			</div>
        <?php
        }

?>








<?php } else { ?>


<h1>
     esto tiene que quedar como la pagina principal de google (≧∇≦)ﾉ <br>
     Lucas was here >:)

</h1>



<?php } ?>

</body>