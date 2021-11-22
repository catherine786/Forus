<?php include 'partials/session_start.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donaciones</title>
    <link rel="shortcut icon" href="cutlery.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<style>
    #search{
        margin-bottom: 1.4%;
    }
</style>
<body>
    <?php include 'partials/header.php'?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-5"><h1 class="display-1">Te gusta la pagina?</h1></div>
            <div class="col-12 text-center mt-3"><h4>Podes ayudarnos con una donacion</h4></div>
            <div class="col-12 text-center"><h4><a href="https://www.paypal.com/ar/home?locale.x=es_AR">Haz click aca</a></h4></div>
            <img src="images/cocinero.png" width="15%" alt="asd" class="col-4 mt-4">
        </div>
        <?php include 'partials/footer.php' ?>
    </div>
</body>
</html>