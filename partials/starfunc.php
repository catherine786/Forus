<script>
//esto despues lo paso a un js directamente

//callapi(qt,v,funcion(result){--codigo--});  en vez de hacer el ajax haces esto y es mucho más rapido. No se puede usar para insertar comentarios o recetas pq usan otros campos
//makepager(recipeidarray, page) recibe un array con las recetas y la pagina y genera un paginador, que devuelve como string
//isfav (starid, swapstar=false) se reemplaza a la estrella con id starid con una estrella correspondiente. si se pasa true como segundo parametro, la estrella pasa de favorito a no favorito y vice versa
//setfavs() corre isfav por cada receta en la pagina
//genstar(id) genera una estrella con id de receta id y la devuelve como string
//gencard(id) genera una tarjeta con id,name,text,author,views, y la devuelve como string. Tambiejn tiene parametros opcionales a image="",code="",hasfav=true,haseditdel=false, isdis=false. Estos ultimos 3 determinan que botones tiene la tarjeta.

function callAPI (queryt,val,runfunction){
    $.ajax({
        url: window.location.pathname.split('/').slice(0,-1).join('/')+"/api/api.php",
        dataType:"json",
        data: {
            qt: queryt,
            v:  val
        },
        success: function(result){runfunction( result ) }
    });

}


function makepager(recipeidarray, page){
    var str="";
    var cantidadDePaginas = Math.ceil(recipeidarray.length/9); 
//    console.log(recipeidarray);
    if (page >=cantidadDePaginas-5){
        startpage=cantidadDePaginas-10;
        endpage=cantidadDePaginas;

    } else if (page>=5){
        startpage=page-5;
        endpage=page+5;

    } else{
        startpage=0;
        endpage=10;

    }
    if (startpage<0){
        startpage=0;
    }

    str+= `<div id = "paginator" class='container rounded mt-2' >
        <div class="row">
            <div class="col-12">
                <ul class="pagination dp-flex justify-content-center">
                <li class="page-item ${page ==0 ? 'disabled': ''}"> 
                    <a class="page-link" onclick = " page=0; updateCards ()"  ${page ==0 ? "disabled": ""}>
                        «
                    </a>
                </li>
                <li class="page-item ${page ==0 ? 'disabled': ''}"> 
                    <a class="page-link" onclick = " page-=1; updateCards ()"  ${page ==0 ? "disabled": ""}>
                        ‹
                    </a>
                </li>`;
                
    for (i=startpage;i<endpage;i++) {
                    str+= `<li class="page-item ${page ==i ? 'active disabled': ''} ">
                        <a class="page-link" onclick = " page=${i}; updateCards ()"  ${page ==i ? "disabled": ""}> ${i} </a>
                    </li>`;
                }
                
                
                str+=`<li class="page-item ${page ==cantidadDePaginas-1 ? 'disabled': ''}">
                    <a class="page-link" onclick = " page+=1; updateCards ()"  ${page ==cantidadDePaginas-1 ? "disabled": ""}>
                        ›
                    </a>
                </li>
                <li class="page-item ${page ==cantidadDePaginas-1 ? 'disabled': ''}">
                    <a class="page-link" onclick = " page=${cantidadDePaginas-1}; updateCards ()"  ${page ==cantidadDePaginas-1 ? "disabled": ""}> 
                        »
                    </a>
                </li>

                </ul>
            </div>
        </div>
    </div>`;
    return str;
}


function isfav (starid, swapstar=false){
    callAPI ((swapstar ? 'sf' : 'if'),starid.slice(1),function(result){
        starelement=document.getElementById(starid);
        if (result){
            starelement.innerHTML="★";
        } else {
            starelement.innerHTML="☆";
        }
        return(result);
    });
    
}

function setfavs(){
    starlist=document.getElementsByClassName("fstar");
    for (x=0;x<starlist.length;x++) {
        //console.log(starlist[x].id);
        isfav(starlist[x].id);
    }

}

function genstar(id){
    str=`<button id="s${id}" class="fstar" style="color:gold;font-size:30;border:none;background-color:transparent;transform: translate(0px, -28%);" onclick="isfav('s${id}', true);">hfg</button>`
    return(str);
}


function gencard(id,name,text,author,views,image="",code="",hasfav=true,haseditdel=false, isdis=false){
    str="";
    str+=`  <div id="c${id}" class="col-md-4 mt-2">
            
                <div class="card text-center" style="max-height:97%;">
					<div style="height:240px;width:100%;">
						${ code ?
						`<iframe src="//www.youtube.com/embed/${code}" style="width:100%;height:240px; border-radius:3px 3px 0px 0px" allowfullscreen="" frameborder="0"></iframe>` :
						`<img src="images/recipe/${image}" alt="Sin Imagen" onerror=this.src="images/noimage.png" style="object-fit: cover;width:100%;height:100%; border-radius:3px 3px 0px 0px">`
						}
					</div>
                    <div class="card-body container" style="">
                        <div class="row" style="display:flex;align-items:center;">
                        <h5 class="card-title" style="text-align:center;width=100%">${name}</h5>
                        <p class="card-title" style="width=100%;font-size:12px;color:gray;">por: ${author}</p>
                        </div>
                        <div class="row">
                        <div style=" ">
                            <div style="height:200px;overflow:hidden;">
                                <p class="card-text">${text}</p>
                            </div>
                            ${ isdis ? `<div style="padding:6px;"><a href="#" onclick="isdel('c${id}')" class="btn btn-warning mt-1">Reactivar</a></div>` :
                                `<div style="padding:6px;">
                                    <a href="recetaParticular.php?r=${id}" class="btn btn-primary mt-1">Ver Mas</a>
                                    ${ haseditdel ? 
                                        `<a href="richtext.php?r=${id}" class="btn btn-primary mt-1">Editar</a>
                                        <a href="#" onclick="isdel('c${id}')" class="btn btn-warning mt-1">Borrar</a>` : ""
                                    }
                                </div>
                                <div style="display:flex;justify-content:space-around;">
                                    <span>${views} 👁</span> 
                                    ${ hasfav ? genstar(id) :"" }
                                </div>`
                            }
                        </div>
                        </div>
                        <!--${ isdis ? `<div style="opacity:0.4;position:absolute;height:100%;width:100%;z-index:1000;">` : `` }${ isdis ? `</div>` : `` }-->
                    </div>
                </div>
            
            </div>`;
            
        return str;
}

function isdel (cardid){
    callAPI (  'dr', cardid.slice(1),function(result){
        cardelement=document.getElementById(cardid);
        cardelement.innerHTML="";
        
        return(result);
    });
    
} 



</script>