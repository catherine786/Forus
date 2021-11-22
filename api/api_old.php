<?php 

//Todas las variables se pueden mandar por post o get. get tiene prioridad

//'v' es el valor que se ingresa como query (id o limit o numeros separados por coma o lo que sea). 
///////'qt' significa query type. puede llevar los valores:

//////valores publicos (no requieren un usuario logueado). esta seria el api abierto, que se puede acceder por cualquier usuario o pagina (principalmente la nuestra)
//vd: devuelve los datos de el/los videos indicados en $v. $v recibe un número de id de video o una lista de numeros separados por coma 
//rd: devuelve los datos de las recetas, igual que el anterior
//vl: listar ids de videos con limit puesto en string con formato:  "idInicial,cantidad"
//rl: **MEJOR USAR SR** listar ids de recetas con limit puesto en string con formato:  "idInicial,cantidad"
//sr: devuelve un array de todos los ids de recetas en el orden pedidio. $v recibe dos letras juntas: a,c,f,v,f para sortear por orden alfabetico, cronologico, por favoritos, vistas o popularidad; y 'a' o 'd' siendo ascendiente o descendiente. 
//cf: devuelve la cantidad de favoritos y la cantidad de favoritos en la ultima semana (popularidad), en un array. Recibe el id de receta en $v
//////valores privados (requieren un usuario logueado con el usuario correcto)
//dr: NO IMPLEMENTADO recibe un id de receta en $v. La cambia de estado (si esta borrada es recuperada y si no la borra). Devuelve 1 si se borró, 2 si se recuperó, y 0 si no ocurrió nada
//dv: NO IMPLEMENTADO recibe un id de receta en $v. Lo cambia de estado (si esta borrado es recuperado y si no lo borra). Devuelve 1 si se borró, 2 si se recuperó, y 0 si no ocurrió nada
//mr: NO IMPLEMENTADO recibe El id de la receta en $v (0 si es nueva), el nombre nuevo en $n y el contenido en $r. Hay que usarlo por post debido al limite del get. Devuelve True si se modificó/creó , False si falló, y el numero de receta si es nuevo
//mv: NO IMPLEMENTADO recibe El id del video en $v (0 si es nuevo), el nombre nuevo en $n, el codigo en en $c y el autor en $a. Devuelve True si se modificó/creó , False si falló, y el numero del video si es nuevo
//yr: NO IMPLEMENTADO Devuelve los ids de las recetas del usuario en un array
//yv: NO IMPLEMENTADO Devuelve los ids de las recetas del usuario en un array
//ys: NO IMPLEMENTADO funciona igual que sr, pero busca las recetas guardadas del usuario ordenadas


//ejemplos
//http://localhost/Tarea/proyecto/Forus/api/api.php?qt=rl&v=2,5
/////////devuelve una lista con strings numericos mostrando las 5 recetas que no fueron borradas despues de la que tiene id 2.
//http://localhost/Tarea/proyecto/Forus/api/api.php?qt=rd&v=2,5,8,3
/////////devuelve una lista con arrays asociativos para las recetas 2, 3 y 5. La ocho no la devuelve porque esta borrada. Para acceder a la 8 se deberá usar el api privada desde la cuenta correspondiente
//http://localhost/Tarea/proyecto/Forus/api/api.php?qt=cf&v=3
/////////devuelve un array ["2","1"] con el primer numero siendo el total de favoritos de la receta 3 y el segundo siendo solo los de la ultima semana. Este segundo se puede usar para elejir los mas populares. 
//http://localhost/Tarea/proyecto/Forus/api/api.php?qt=sr&v=fd
/////////devuelve un array con los ids de todos las recetas, en orden de cantidad de favoritos decendiente

function privQSt(){
    require_once '..\partials\session_start.php'; 
    if (isset($_SESSION['id'])){
        return $_SESSION['id'];
    } else {
        exit('{"error":"This call requires being logged in"}');
    }
}


if (isset($_GET['qt'])) {
    $qt=$_GET['qt'];
} else if  (isset($_POST['qt'])){
    $qt=$_POST['qt'];
} else {
    exit('{"error":"no query type (qt) on get or post"}');
}

if (isset($_GET['v'])) {
    $value=$_GET['v'];

} else if  (isset($_POST['v'])){
    $value=$_POST['v'];

} else if ($qt=='vd' ||$qt=='rd') {
    $value='0,25';
} else if ($qt=='sr') {
    $value='aa';
} else {
    exit('{"error":"no query value"}');
}


require_once '..\database\database.php';
$json=[];

switch($qt){
    case 'vd':
        if (is_numeric($value)){
            $query="SELECT * FROM videos WHERE ID =".$value." AND Deleted_At IS NULL";
        }
        else {
            
            $idarr=explode (",", $value);
            $query="SELECT * FROM videos WHERE ( 0 ";
            foreach ($idarr as $subid){
                $query.="OR ID=".$subid." ";
            }
            $query.=" ) AND Deleted_At IS NULL";
        }
        $result=qq($link, $query);
        while ($row=mysqli_fetch_assoc($result)){
            array_push($json, [
                'id'=>$row['ID'],
                'user_id'=>$row['User_ID'],
                'author'=>$row['Author'],
                'name'=>$row['Name'],
                'code'=>$row['Code'],
                'created_at'=>$row['Created_At'],
            ]);
        }
        break;


    case 'rd':
        if (is_numeric($value)){
            $query="SELECT * FROM recipes WHERE ID =".$value." AND Deleted_At IS NULL";
        }
        else {
            
            $idarr=explode (",", $value);
            $query="SELECT * FROM recipes WHERE ( 0 ";
            foreach ($idarr as $subid){
                $query.="OR ID=".$subid." ";
            }
            $query.=" ) AND Deleted_At IS NULL";
        }
        $result=qq($link, $query);
        while ($row=mysqli_fetch_assoc($result)){
            array_push($json, [
                'id'=>$row['ID'],
                'user_id'=>$row['User_ID'],
                'name'=>$row['Name'],
                'recipe'=>$row['Recipe'],
                'views'=>$row['Views'],
                'img_path'=>$row['img_path'],
                'created_at'=>$row['Created_At'],
            ]);
        }
        break;

    
    case 'rl':
        if (is_numeric($value)){
            $query="SELECT * FROM recipes WHERE ID >".$value." AND Deleted_At IS NULL LIMIT 0,25";
        }
        else {
            $idarr=explode (",", $value);
            $query="SELECT * FROM recipes WHERE ID >".$idarr[0]." AND Deleted_At IS NULL LIMIT 0,".$idarr[1];
        }   
        $result=qq($link, $query);
        while ($row=mysqli_fetch_assoc($result)){
            array_push($json,$row['ID']);
        }
        break;



    case 'vl':
        if (is_numeric($value)){
            $query="SELECT * FROM videos WHERE ID >".$value." AND Deleted_At IS NULL LIMIT 0,25";
        }
        else {
            $idarr=explode (",", $value);
            $query="SELECT * FROM videos WHERE ID >".$idarr[0]." AND Deleted_At IS NULL LIMIT 0,".$idarr[1];
        }   
        $result=qq($link, $query);
        while ($row=mysqli_fetch_assoc($result)){
            array_push($json,$row['ID']);
        }
        break;

    case 'cf':
        $query="SELECT COUNT('User_ID') as tf FROM favorites WHERE Recipes_ID=".$value;
        $result1=mysqli_fetch_assoc(qq($link, $query))['tf'];
        $query="SELECT COUNT('User_ID') as rf FROM favorites WHERE Recipes_ID=".$value." AND Created_At + INTERVAL 7 DAY > NOW()";
        $result2=mysqli_fetch_assoc(qq($link, $query))['rf'];
        array_push($json,$result1,$result2);
        
        break;

    case 'sr':
        $orderarr = str_split($value);
        $query="SELECT recipes.*, COUNT(favorites.Recipes_ID) as favcount FROM recipes LEFT JOIN favorites ON favorites.Recipes_ID = recipes.ID WHERE Deleted_At IS NULL GROUP BY recipes.ID ORDER BY ";
        switch ($orderarr[0]){
            case 'a':
                $query.="Name";
                break;
            case 'c':
                $query.="Created_At";
                break;
            case 'f':
                $query.="favcount";
                break;
            case 'v':
                $query.="Views";
                break;
            case 'p':
                $query="SELECT recipes.*, COUNT(favorites.Recipes_ID) as popularity FROM recipes LEFT JOIN favorites ON favorites.Recipes_ID = recipes.ID AND favorites.Created_At + INTERVAL 7 DAY > NOW() WHERE Deleted_At IS NULL GROUP BY recipes.ID ORDER BY popularity";
                break;
            }
            switch ($orderarr[1]){
                case 'a':
                    $query.=" ASC";
                    break;
                case 'd':
                    $query.=" DESC";
                    break;
            }
        $result=qq($link, $query);
        while ($row=mysqli_fetch_assoc($result)){
            array_push($json,$row['ID']);
        }
        break;
    

    case 'ed':
        break;
    case 'em':
        break;
    case 'ec':
        break;
    case 'mr':
        break;
    case 'mv':
        break;
    case 'ms':
        break;
        
}
echo json_encode($json);
?>