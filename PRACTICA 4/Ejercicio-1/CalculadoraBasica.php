<!DOCTYPE html>
<html lang="es" dir = "ltr">
<head>
<title>Calculadora básica</title>
<meta charset="UTF-8">
<meta name="author" content="Moisés Sanjurjo Sáncehz (UO270824)" />
<meta name="desciption" content="Ejercicio1" /> 
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="CalculadoraBasica.css"/>
</head>
<body>
<h1>Calculadora básica</h1> 
<p>A continuación se muestra una calculadora básica</p>
<?php 
//localhost/PRACTICA-4/Ejercicio-1/CalculadoraBasica.php
class CalculadoraBasica {

    protected $pantalla;
    protected $memoria;

    
    public function __construct(){
        $this->pantalla = ""; //Almacena expresión algrebraica que se irá formando 
        $this->memoria = ""; //Representa a la memoria de la calculadora 
    }
    
    /*
    * Añade un dígito que se le pasa como 
    * parámetro a la pantalla 
    */
    public function digitos($digito){
        $this->checkError(); 
        $this->pantalla .= $digito; 
    }

    /*
    * Añade un punto decimal a la pantalla 
    */
    public function punto(){
        $this->checkError(); 
        $this->pantalla .= "."; 
    }

    /*
    * Añade el símbolo de suma a la pantalla 
    */
    public function suma(){
        $this->checkError(); 
        $this->pantalla .= "+"; 
    }

    /*
    * Añade el símbolo de resta a la pantalla 
    */
    public function resta(){
        $this->checkError(); 
        $this->pantalla .= "-"; 
    }

    /*
    * Añade el símbolo de multiplicación a la pantalla 
    */
    public function multiplicacion(){
        $this->checkError(); 
        $this->pantalla .= "*";  
    }

    /*
    * Añade el símbolo de división a la pantalla 
    */
    public function division(){
        $this->checkError(); 
        $this->pantalla .= "/"; 
    }

    /*
    * Añade a la pantalla el valor acumulado en memoria  
    */
    public function mrc(){
        $this->checkError(); 
        $this->pantalla .= $this->memoria; 
    }

    /*
    * Resta el numero en pantalla a la memoria. En caso, de que sea una operación 
    * lo que hay en pantalla, se evaluará y se restará el resultado obtenido a la memoria 
    */
    public function mMenos(){
        $this->checkError(); 
        $this->igual(); 
        if($this->pantalla[0] == "-"){ //Menos y menos es más 
            $this->memoria .= "+" . substr($this->pantalla,1,strlen($this->pantalla)); 
        }
        else{ //Menos y más es menos 
            $this->memoria .= "-";
            $this->memoria .= $this->pantalla; 
        }
        try{ //Se controla si no se inserta en memoria nada malicioso
            $this->memoria = eval("return $this->memoria;");
        }
        catch(Exception $e){
            $this->pantalla = "Error de sintáxis"; 
            $this->memoria = ""; 
        }
    }

    /*
    * Suma el numero en pantalla a la memoria. En caso, de que sea una operación 
    * lo que hay en pantalla, se evaluará y se sumará el resultado obtenido a la memoria 
    */
    public function mMas(){
        $this->checkError(); 
        $this->igual();
        $this->memoria .= "+"; 
        $this->memoria = $this->pantalla; 
        try{ //Se controla si no se inserta en memoria nada malicioso
            $this->memoria = eval("return $this->memoria;");
        }
        catch(Exception $e){
            $this->pantalla = "Error de sintáxis"; 
            $this->pantalla = ""; 
            $this->memoria = ""; 
        }
    }

    /*
    * Se limpia la memoria y la pantalla 
    */
    public function borrar(){
        $this->checkError(); 
        $this->pantalla = ""; 
        $this->memoria = ""; 
    }

    /*
    * Se realiza la operación escrita en la pantalla con eval 
    */
    public function igual(){
        try{
            $this->pantalla = "".eval("return $this->pantalla;");
        }
        catch(ParseError $pe){
            $this->pantalla = "Error de sintáxis"; 
            $this->memoria = ""; 
        }
    }

    /*
    * Actualiza la pantalla en caso de error
    */
    public function checkError(){
        if($this->pantalla == "Error de sintáxis"){
            $this->pantalla = ""; 
        }
    }

    /*
    * Devuelve el valor de la pantalla
    */
    public function getPantalla(){
        return $this->pantalla; 
    }

    /*
    * Procesa una opción
    */
    public function procesarOpcion(){
        if(isset($_POST["mrc"])) $this->mrc();
        if(isset($_POST["m-"])) $this->mMenos();
        if(isset($_POST["m+"])) $this->mMas();
        if(isset($_POST["/"])) $this->division();
        if(isset($_POST["7"])) $this->digitos(7);
        if(isset($_POST["8"])) $this->digitos(8);
        if(isset($_POST["9"])) $this->digitos(9);
        if(isset($_POST["*"])) $this->multiplicacion();
        if(isset($_POST["4"])) $this->digitos(4);
        if(isset($_POST["5"])) $this->digitos(5);
        if(isset($_POST["6"])) $this->digitos(6);
        if(isset($_POST["-"])) $this->resta();
        if(isset($_POST["1"])) $this->digitos(1);
        if(isset($_POST["2"])) $this->digitos(2);
        if(isset($_POST["3"])) $this->digitos(3);
        if(isset($_POST["+"])) $this->suma();
        if(isset($_POST["0"])) $this->digitos(0);
        if(isset($_POST["punto"])) $this->punto();
        if(isset($_POST["C"])) $this->borrar();
        if(isset($_POST["="])) $this->igual();    
    }
}

session_name("CalculadoraBasica"); 
session_start(); 

if(!isset($_SESSION["calculadora"])){
    $_SESSION["calculadora"] = new CalculadoraBasica(); 
}
if (count($_POST)>0) {   
    $_SESSION["calculadora"]->procesarOpcion(); 
}

// Interfaz con el usuario. En el interior de comillas dobles se deben usar comillas simples
echo "  
<form action='#' method='post' name='interfaz'>
<div>
<p>
    <input type='text' title='Pantalla' value='".$_SESSION["calculadora"]->getPantalla()."' name='pantalla' disabled/>
</p>

<p>
    <input type = 'submit' class='button' value='mrc' name='mrc' />
    <input type = 'submit' class='button' value='m-' name='m-' />
    <input type = 'submit' class='button' value='m+' name='m+' />
    <input type = 'submit' class='button' value='/' name='/' />
</p>

<p>
    <input type = 'submit' class='button' value='7' name='7' />
    <input type = 'submit' class='button' value='8' name='8' />
    <input type = 'submit' class='button' value='9' name='9' />
    <input type = 'submit' class='button' value='*' name='*' />
</p>

<p>
    <input type = 'submit' class='button' value='4' name='4' />
    <input type = 'submit' class='button' value='5' name='5' />
    <input type = 'submit' class='button' value='6' name='6' />
    <input type = 'submit' class='button' value='-' name='-' />
 </p>

<p>
    <input type = 'submit' class='button' value='1' name='1' />
    <input type = 'submit' class='button' value='2' name='2' />
    <input type = 'submit' class='button' value='3' name='3' />
    <input type = 'submit' class='button' value='+' name='+' />
</p>

<p>
    <input type = 'submit' class='button' value='0' name='0' />
    <input type = 'submit' class='button' value='.' name='punto' />
    <input type = 'submit' class='button' value='C' name='C' />
    <input type = 'submit' class='button' value='=' name='=' />
</p>
</div>
</form>
";
?>
</body>
</html>