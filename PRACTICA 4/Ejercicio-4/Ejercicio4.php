<!DOCTYPE html>
<html lang="es" dir = "ltr">
<head>
<title>Ejercicio 4</title>
<meta charset="UTF-8">
<meta name="author" content="Moisés Sanjurjo Sáncehz (UO270824)" />
<meta name="desciption" content="Ejercicio4" /> 
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="Ejercicio4.css"/>
</head>
<body>
<h1>Ejercicio 4</h1> 
<p>Se quiso implementar un buscador de imagenes similar a Google imagenes usando la API que ofrece Flickr.</p>
<h2>Instrucciones de uso</h2>
<p>Debe escribir en el cuadro de texto las palabras clave sobre la temática deseada separadas por ';'. Posteriormente pulse el botón 'Buscar' y se mostrarán las imagenes que
concuerdan con las temáticas introducidas.Por ejemplo con la cadena 'uniovi;arte', buscaría aquellas fotos que tuvieran la etiqueta uniovi o la etiqueta arte y las mostraría.
Cada imagen encontrada se diferenciará del resto con ayuda de varias líneas horizontales.
</p>
<?php 
//localhost/PRACTICA-4/Ejercicio-4/Ejercicio4.php

class Ejercicio4 {
    
    protected $perPage;
    protected $resultadoBusqueda;
    protected $key; 

    public function __construct(){
        $this->perPage = 20;
        $this->resultadoBusqueda = "<h2>Imagenes encontradas</h2>";
        $this->key = "0565634739c78dcdbf56368cb0991f0b"; 
    }

    /*
    * Método principal que se encarga de formatear la búsqueda introducida por el usuario y mostrar los resultados obtenidos
    */
    public function buscar(){
        //Imnicializamos la cadena que contendrá la información encontrada
        $resultado = "<h2>Imagenes encontradas</h2>"; 

        //Obtenemos la cadena introducida en el buscador y la formateamos quitandole espacios sobrantes y dividiendola por el carácter ;
        $listaTags = trim($_POST["buscador"]);
        $listaTags = explode(";",$listaTags);

        $resultado = "";

        //Recorremos la lista de etiquetas pidiendole a la API las imagenes resultantes para cada una de ellas
        foreach($listaTags as $tag){
            
            //Formamos la petición 
            $url = 'http://api.flickr.com/services/feeds/photos_public.gne?';
            $url.= '&api_key='.$this->key;
            $url.= '&tags='.$tag;
            $url.= '&per_page='.$this->perPage;
            $url.= '&format=xmlrpc'; //Como indica el ejercicio se usará formato XML.

            //Obtenemos la respuesta en xml 
            $datos = file_get_contents($url);
            $datos =preg_replace("/>\s*</",">\n<",$datos);

            

            // Se convierte el string en un objeto XML
            $xml = new SimpleXMLElement($datos); 
            //Recorremos cada resultado encontrado e imprimimos su conetenido (autor/imagen/descripción)
            for($i=0;$i < sizeof($xml->entry);$i++){
                $titulo = $xml->entry[$i]->title;
                $links =  $xml->entry[$i]->link;
                
                //Recojo el enlace de la fotod del XML
                $enlace = ""; 
                for($j=0;$j < sizeof($links);$j++){
                    $aux = $xml->entry[$i]->link[$j]["href"];

                    if(preg_match("/jpg+$/",$aux)){
                        $enlace = $xml->entry[$i]->link[$j]["href"];
                    }
                }

                //Formo la representacion HTML 
                $resultado .= "<img src='".$enlace."' title='".$titulo."' alt='".$titulo."'/><hr/>"; //Se añade una linea tambien para diferenciar las fotos
            }

        }

        //Almacenamos el resultado de la busqueda 
        $this->resultadoBusqueda = $resultado; 
    }

   
    /*
    * Procesa la accione a realizar al pulsar el boton buscar
    */
    public function procesarOpcion(){
        if(isset($_POST["buscar"])) $this->buscar();
    }

    /*
    * Devuelve el resultado obtenido de la última búsqueda
    */
    public function getesultado(){
        return $this->resultadoBusqueda;
    }

}
session_name("Ejercicio4"); 
session_start(); 

if(!isset($_SESSION["buscador"])){
    $_SESSION["buscador"] = new Ejercicio4(); 
}
if (count($_POST)>0) {   
    $_SESSION["buscador"]->procesarOpcion(); 
}

// Interfaz con el usuario. En el interior de comillas dobles se deben usar comillas simples
echo "  
<form action='#' method='post' name='interfaz'>
<p>
    <input type='text' title='Buscador' name='buscador' />
</p>
<p>
    <input type = 'submit' class='button' value='Buscar'  name='buscar' /> 
</p>
<p>Pulse el botón 'Buscar' para iniciar la búsqueda</p>
<div>".$_SESSION["buscador"]->getesultado()."</div>
</form>
";
?>
</body>
</html>