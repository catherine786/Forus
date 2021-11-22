<?php 
header("Content-type: application/json; charset=utf-8");
//las variables v y qt se pueden mandar por post o get. get tiene prioridad

//'v' es el valor que se ingresa como query (id o limit o numeros separados por coma o lo que sea). 
///////'qt' significa query type. puede llevar los valores:

//////valores publicos (no requieren un usuario logueado). esta seria el api abierto, que se puede acceder por cualquier usuario o pagina (principalmente la nuestra)
//rd: (Recipe data) devuelve los datos de la/s recetas indicados en $v. $v recibe un número de id de video o una lista de numeros separados por coma. Se puede poner al principio de la lista dos letras en el mismo formato que sr para que las recetas las devuelva en ese orden
//sr: (Search recipe)devuelve un array de todos los ids de recetas en el orden pedidio. $v recibe dos letras juntas: a,c,f,v,p para sortear por orden alfabetico, cronologico, por favoritos, vistas o popularidad; y 'a' o 'd' siendo ascendiente o descendiente. 
//cf: (Count favorites)devuelve la cantidad de favoritos y la cantidad de favoritos en la ultima semana (popularidad), en un array. Recibe el id de receta en $v
//////valores privados (requieren un usuario logueado con el usuario correcto)
//dr: (delete recipe)recibe un id de receta en $v. La cambia de estado (si esta borrada es recuperada y si no la borra). Devuelve True si quedo sin borra y False si quedó borrada
//mr: SIN PROBAR (modify recipe) recibe El id de la receta en $v (0 si es nueva). Usa variables : name,recipe,code,img. Hay que usarlo por post debido al limite del get. Devuelve True si se modificó , False si falló, y el numero de receta si es nuevo

//yr: (Your recipes)funciona igual que sr, pero busca las recetas del usuario ordenadas. Devuelve una lista con als recetas sin borrar y otra con borradas
//yf: (Your favorites) funciona igual que sr, pero busca las recetas guardadas del usuario ordenadas. ESTO PROXIMO NO ES CIERTO PERO LO AGREGO SI TENGO TIEMPO Tambien permite usar el valor 'm' como primera letra para ver guardadas mas recientes
//sf: (Swap favorites)cambia el estado de favorito de una receta. Devuelve True si queda en favoritos y False si queda no en favorito.

//sc: SIN PROBAR (show comments) devuelve array con todos los comentarios del post ingresado en $v. este array contiene el id, id del autor, texto, y fecha
//mc: SIN PROBAR (modify comment) recibe el id del comentario en $v. Usa variables : receta,text, .Hay que usarlo por post debido al limite del get. Devuelve True si se modificó , False si falló, y el id de comentario si es nuevo. Si es para crear un comentario nuevo, no mandar nada o mandar 0 en id
//dc: SIN Probar (delete comment) recibe un id de comentario en $v. La borra si es del usuario logueado. Devuelve True si se borro y False si no

//iv: (increase viwews) recibe un id de receta en $v. La incrementa las vistas por 1
//if: (is favorite) recibe un id de receta en $v. devuelve true o false correspondientemente


//ejemplos
//http://localhost/Forus/api/api.php?qt=rd&v=2,5,8,3
/////////devuelve una lista con arrays asociativos para las recetas 2, 3 y 5. La ocho no la devuelve porque esta borrada. Para acceder a la 8 se deberá usar el api privada desde la cuenta correspondiente
//http://localhost/Forus/api/api.php?qt=cf&v=3
/////////devuelve un array ["2","1"] con el primer numero siendo el total de favoritos de la receta 3 y el segundo siendo solo los de la ultima semana. Este segundo se puede usar para elejir los mas populares. 
//http://localhost/Forus/api/api.php?qt=sr&v=fd
/////////devuelve un array con los ids de todos las recetas, en orden de cantidad de favoritos decendiente

function privQSt(){
    require_once '..\partials\session_start.php'; 
    
    if (isset($_SESSION['id'])){
        return $_SESSION['id'];
    } else {
        var_dump($_SESSION);
        exit('{"error":"This call requires being logged in","$_SESSION:"'.print_r($_SESSION).'}');
    }
}

function orderAndPush($query,$link){
    $json=[];
    //echo $query;
    $result=qq($link, $query);
    while ($row=mysqli_fetch_assoc($result)){
        $json[]=$row['ID'];
        //echo $row['Views'];
    }
    if (count($json)==0){
        $json[]="0";
    }
    return $json;
};

function sortorder($sorttype, $order, $where){
	$query="SELECT recipes.*, COUNT(favorites.Recipes_ID) as favcount FROM recipes LEFT JOIN favorites ON favorites.Recipes_ID = recipes.ID ".$where." GROUP BY recipes.ID ORDER BY ";
	switch ($sorttype){
		case 'a': $query.="Name"; break;
		case 'c': $query.="Created_At"; break;
		case 'f': $query.="favcount"; break; 
		case 'v': $query.="Views"; break; 
		case 'p': $query="SELECT recipes.*, COUNT(favorites.Recipes_ID) as popularity FROM recipes LEFT JOIN favorites ON favorites.Recipes_ID = recipes.ID AND favorites.Created_At + INTERVAL 7 DAY > NOW() ".$where." GROUP BY recipes.ID ORDER BY popularity"; break;
        }
	switch ($order){
        case 'a': $query.=" ASC"; break;
        case 'd': $query.=" DESC"; break;
    }
	//echo $query;
	return $query;
	
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

} else if ($qt=='rd') {
    $value='0,25';
} else if ($qt=='sr') {
    $value='aa';
} else if($qt=='mr' || $qt=='mc'){
    $value=0;
} else {
    exit('{"error":"no query value"}');
}


require_once '..\database\database.php';
$json=[];

switch($qt){
    case 'rd':
        if (is_numeric($value)){ // if there is only just one recipe
            $query="SELECT recipes.*,UserName FROM recipes INNER JOIN users ON users.ID=recipes.User_ID WHERE recipes.ID =".$value." AND recipes.Deleted_At IS NULL";
        }
        else {
            
            $idarr=explode (",", $value);
            $orderstr="";
            if (!is_numeric($idarr[0])){ //if the recepies are given a sort order
                
                $sortarr=str_split(array_shift($idarr));
                $orderstr=" ORDER BY ";
                switch ($sortarr[0]){
                    case 'a': $orderstr.="Name"; break;
                    case 'c': $orderstr.="Created_At"; break;
                    case 'f': $orderstr.="favcount"; break; 
                    case 'v': $orderstr.="Views"; break; 
                    case 'p': $orderstr.="popularity"; break;
                    }
                switch ($sortarr[1]){
                    case 'a': $orderstr.=" ASC"; break;
                    case 'd': $orderstr.=" DESC"; break;
                }
            }
            $query="SELECT recipes.*,UserName, COUNT(favorites.Recipes_ID) as favcount FROM recipes INNER JOIN users ON users.ID=recipes.User_ID LEFT JOIN favorites ON favorites.Recipes_ID = recipes.ID WHERE ( 0 ";
            if (!empty($orderstr) && $sortarr[0]=='p'){
                $query="SELECT recipes.*,UserName, COUNT(favorites.Recipes_ID) as popularity FROM recipes INNER JOIN users ON users.ID=recipes.User_ID LEFT JOIN favorites ON favorites.Recipes_ID = recipes.ID AND favorites.Created_At + INTERVAL 7 DAY > NOW() WHERE ( 0 ";
            }
            foreach ($idarr as $subid){
                $query.="OR recipes.ID=".mysqli_real_escape_string($link, $subid)." ";
            }
            $query.=" ) AND recipes.Deleted_At IS NULL"." GROUP BY recipes.ID".$orderstr;
        }
        $result=qq($link, $query);
        if(mysqli_fetch_assoc($result)){ //foreach result of query
            mysqli_data_seek($result,0);
            while ($row=mysqli_fetch_assoc($result)){
                $json[]=[
                    'id'=>$row['ID'],
                    'user_id'=>$row['User_ID'],
                    'username'=>$row['UserName'],
                    'name'=>$row['Name'],
                    'recipe'=>$row['Recipe'],
                    'views'=>$row['Views'],
                    'img_path'=>$row['img_path'],
                    'created_at'=>$row['Created_At'],
                    'code'=>$row['Code'],
                ];
            }
        } else {
            die('false');
        }
        break;


    case 'cf':
        $query="SELECT COUNT('User_ID') as tf FROM favorites WHERE Recipes_ID=".mysqli_real_escape_string($link, $value);
        $result1=mysqli_fetch_assoc(qq($link, $query))['tf'];
        $query="SELECT COUNT('User_ID') as rf FROM favorites WHERE Recipes_ID=".mysqli_real_escape_string($link, $value)." AND Created_At + INTERVAL 7 DAY > NOW()";
        $result2=mysqli_fetch_assoc(qq($link, $query))['rf'];
        $json[]=$result1;
        $json[]=$result2;        
        break;

    case 'sr':
        $orderarr = str_split($value);
        $query=sortorder($orderarr[0],$orderarr[1],"WHERE Deleted_At IS NULL");
        $json=orderAndPush($query,$link);
        break;
    

    case 'dr':
        $id=privQSt();
        $isdel= mysqli_fetch_assoc(qq($link, "SELECT User_ID,Deleted_At FROM recipes WHERE ID = ".mysqli_real_escape_string($link, $value)));
		if ($isdel['User_ID']==$id){		
			$query="UPDATE recipes SET Deleted_At = ".($isdel['Deleted_At'] ? "NULL" : "NOW()")." WHERE ID = ".mysqli_real_escape_string($link, $value);
			qq($link, $query);
			die( $isdel['Deleted_At'] ? 'true' : 'false');
		} else {
			die( $isdel['Deleted_At'] ? 'false' : 'true');
		}
		
        break;


    case 'mr':
		$id=privQSt();
		if ($value!=0){ //Se fija si la receta es nueva. 
			if (!(mysqli_fetch_assoc(qq($link, "SELECT User_ID from recipes where ID = ".mysqli_real_escape_string($link, $value)))['User_ID']==$id)){ //se fija si la receta es del usuario
				die('false');
			} else {
				$imagefile=$value;
			}
		} else { //da el numero de la receta nueva
			$imagefile=mysqli_fetch_assoc(qq($link, "SELECT MAX(ID) AS maxID FROM recipes"))['maxID']+1;
		}
		if (isset($_POST['name']) && isset($_POST['recipe'])) {		 //si estan los requerimientos minimos	
			if (isset($_FILES['img'])){ //si mandaron imagen
				if (strpos($_FILES['img']['type'],'image') && $_FILES['img']['type']<20000 && !($_FILES['img']['error']>0)){ //si la imagen es válida
					
					move_uploaded_file($_FILES['img']['tmp_name'],"..\\images\\recipe\\".$imagefile.end(explode('.',$_FILES['img']['tmp_name']))); //mueve la imagen y le pone de nombre el id de receta.png/jpg/etc
					$img_exists=1;
				} else { $img_exists=0; }
			} else { $img_exists=0; }
			if ($value==0){ //si la receta es nueva
				$query="INSERT INTO recipes VALUES(
									NULL,
                                    ".$id.",
									'".mysqli_real_escape_string($link, htmlspecialchars($_POST['name']))."',
									'".mysqli_real_escape_string($link, $_POST['recipe'])."',
									0,
									".($img_exists ?  '"'.$imagefile.end(explode('.',$_FILES['img']['tmp_name'])).'"' : "NULL" ).",
                                    '".mysqli_real_escape_string($link, htmlspecialchars((isset($_POST['code']) ? $_POST['code'] : "NULL")))."',
                                    NOW(),
                                    NULL
                                    
                                )" ;
                qq($link, $query);
                $json[]= $imagefile;
                
				
			} else { //si la receta ya existe
                $query="UPDATE recipes SET 
                    `Name` = '".mysqli_real_escape_string($link, htmlspecialchars($_POST['name']))."', 
                    Recipe = '".mysqli_real_escape_string($link, $_POST['recipe'])."'";
                $query.= $img_exists ? ",img_path = '".$imagefile.end(explode('.',$_FILES['img']['tmp_name']))."'" : "";
                $query.= $isset($_POST['code']) ? ",Code = '".mysqli_real_escape_string($link, htmlspecialchars($_POST['code']))."'" : "";
                $query.= "WHERE recipes.ID = ".mysqli_real_escape_string($link, $value);
                qq($link, $query);
                die('true');
            }
		} else {
			die('false');
		}
		break; //FALTARIA PROBAR< NO SE SI FUNCIONA< PERO NADIE HIZO EL FORM DE PREUBA TODAVIA
	

    case 'yr':
		$id=privQSt();
        $orderarr = str_split($value);
        $query=sortorder($orderarr[0],$orderarr[1],"WHERE recipes.User_ID = ".$id." AND Deleted_At IS NULL");
        $json[]=orderAndPush($query,$link);
		$query=sortorder($orderarr[0],$orderarr[1],"WHERE recipes.User_ID = ".$id." AND NOT Deleted_At IS NULL");
        $json[]=orderAndPush($query,$link);
		break;


    case 'yf':
		$id=privQSt();
        $orderarr = str_split($value);
        $query=sortorder($orderarr[0],$orderarr[1],"WHERE recipes.ID = favorites.Recipes_ID AND favorites.User_ID = ".$id." AND Deleted_At IS NULL");
        $json=orderAndPush($query,$link);
		break;
		
    case 'sf':
		$id=privQSt();
        $isfav= mysqli_num_rows(qq($link, "SELECT Created_At FROM favorites WHERE Recipes_ID = ".mysqli_real_escape_string($link, $value)." AND USER_ID=".$id));
        if ($isfav){
			$query="DELETE FROM favorites WHERE Recipes_ID = ".mysqli_real_escape_string($link, $value)." AND USER_ID=".$id;
		} else {
			$query="INSERT INTO favorites VALUES(" . $id . ", " . mysqli_real_escape_string($link, $value) . ", NOW() )";
		}
        qq($link, $query);
        die( $isfav ? 'false' : 'true');
        break;


    case 'sc':
         $query="SELECT comments.*,UserName FROM comments INNER JOIN users on users.ID=comments.User_ID WHERE comments.Recipe_ID=".mysqli_real_escape_string($link, $value). " ORDER BY comments.Created_At DESC";
         $result=qq($link, $query);
        while ($row=mysqli_fetch_assoc($result)){
            $json[]=[$row];
        }
        break;

    case 'mc':
        $id=privQSt();
        if (isset($_POST['text'])){
            if ($value!=0){ //Se fija si el comment es nuevo. 
                if (!(mysqli_fetch_assoc(qq($link, "SELECT User_ID from comments where ID = ".mysqli_real_escape_string($link, $value)))['User_ID']==$id)){ //se fija si la receta es del usuario
                    die('false');
                } else {
                    $query="UPDATE comments SET `Text` = '".mysqli_real_escape_string($link, htmlspecialchars($_POST['text']))."'WHERE ID = ".$value;
                    qq($link, $query);
                    die('true');
                }
            } else if (isset($_POST['recipe'])) { //da el numero de la receta nueva
                $query="INSERT INTO comments VALUES(null,${id},".mysqli_real_escape_string($link, $_POST['recipe']).",'".mysqli_real_escape_string($link, htmlspecialchars($_POST['text']))."',NOW())" ;
                qq($link, $query);
                $json[]=mysqli_fetch_assoc(qq("SELECT MAX(ID) AS maxID FROM comments"))['maxID'];
            } else {
                die('false');
            }
        } else {
            die('false');
        }
    break; //FALTARIA PROBAR< NO SE SI FUNCIONA< PERO NADIE HIZO EL FORM DE PREUBA TODAVIA
    
    case 'dc':
        $id=privQSt();
        $isdel= mysqli_fetch_assoc(qq($link, "SELECT User_ID FROM comments WHERE ID = ".mysqli_real_escape_string($link, $value)));
        if ($isdel['User_ID']==$id){		
            $query="DELETE FROM comments WHERE ID=".mysqli_real_escape_string($link, $value);
            qq($link, $query);
            die('true');
        } else {
            die('false');
        }
        
        break;

    case 'iv':
        
        $query="UPDATE recipes SET Views=Views+1 WHERE ID=".mysqli_real_escape_string($link, $value);
        qq($link, $query);
        die('true');
        break;

    case 'if':
        $id=privQSt();
        $isfav= mysqli_num_rows(qq($link, "SELECT Created_At FROM favorites WHERE Recipes_ID = ".mysqli_real_escape_string($link, $value)." AND USER_ID=".$id));
        if ($isfav){
            die('true');
        } else {
            die('false');
        }
        break;
}




//echo filter_var(json_encode($json), FILTER_SANITIZE_STRING);

echo json_encode($json);