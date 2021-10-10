<!DOCTYPE html>
<html lang="es" dir = "ltr">
<head>
<title>Calculadora científica</title>
<meta charset="UTF-8">
<meta name="author" content="Moisés Sanjurjo Sáncehz (UO270824)" />
<meta name="desciption" content="Ejercicio2" /> 
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="CalculadoraCientifica.css"/>
</head>
<body>
<h1>Calculadora científica</h1> 
<p>A continuación se muestra una calculadora científica</p>
<?php 
//localhost/PRACTICA-4/Ejercicio-2/CalculadoraCientifica.php
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
class CalculadoraCientifica extends CalculadoraBasica{
    
    public function __construct(){
        parent::__construct();
    }

 /*
    * Añade la constante PI a la pantalla  
    */
    public function ctePi(){
        $this->checkError(); 
        $this->pantalla .= pi(); 
    }

    /*
    * Añade la constante E a la pantalla  
    */
    public function cteE(){
        $this->checkError(); 
        $this->pantalla .= eval("return exp(1);");  
    }

    /*
    * Añade un paréntesis abierto a la pantalla   
    */
    public function parentesisAbierto(){
        $this->checkError(); 
        $this->pantalla .= "(";  
    }

    /*
    * Añade un paréntesis cerrado a la pantalla   
    */
    public function parentesisCerrado(){
        $this->checkError(); 
        $this->pantalla .= ")";  
    }

    /*
    * Realiza el logaritmo neperiano de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function logaritmoNeperiano(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".log($this->pantalla);  
    }

    /*
    * Realiza el logaritmo en base 10 de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function logaritmoBase10(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".log10($this->pantalla);  
    }

    /*
    * Realiza una potencia con base e y tomando como exponente el valor de la pantalla.
    * Si la pantalla tiene una expresión , primero se evalua
    */
    public function exponencialE(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".exp($this->pantalla);  
    }

    /*
    * Realiza una potencia con base 10 y tomando como exponente el valor de la pantalla.
    * Si la pantalla tiene una expresión , primero se evalua
    */
    public function exponencial10(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".pow(10,$this->pantalla);  
    }

    /*
    * Realiza una potencia con base 2 y tomando como exponente el valor de la pantalla.
    * Si la pantalla tiene una expresión , primero se evalua
    */
    public function exponencial2(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".pow(2,$this->pantalla);  
    }

    /*
    * Realiza la raíz cuadrada de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function raiz(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".sqrt($this->pantalla); 
    }

    /*
    * Realiza el seno de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function seno(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".sin($this->pantalla); 
    }

    /*
    * Realiza el arcoseno de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function arcoseno(){
        $this->checkError(); 
        $this->igual(); 
        $this->pantalla = "".asin($this->pantalla);  
    }

    /*
    * Realiza el coseno de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function coseno(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".cos($this->pantalla);    
    }
 
    /*
    * Realiza el arcocoseno de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function arcocoseno(){
        $this->checkError(); 
        $this->igual(); 
        $this->pantalla = "".acos($this->pantalla); 
    }
  
    /*
    * Realiza latangente de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function tangente(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".tan($this->pantalla);  
    }

    /*
    * Realiza la arcotangente de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function arcotangente(){
        $this->checkError(); 
        $this->igual(); 
        $this->pantalla = "".atan($this->pantalla); 
    }

    
    /*
    * Realiza el modulo / valor absoluto de lo que está actualmente en pantalla.
    * Si la pantalla tiene una expresión , primero se evalua 
    */
    public function modulo(){
        $this->checkError(); 
        $this->igual();
        $this->pantalla = "".abs($this->pantalla);  
    }

    
    /*
    * Elimina el último carácter introducido en la pantalla 
    */
    public function atras(){
        $this->checkError();
        $this->pantalla = substr($this->pantalla,0,strlen($this->pantalla)-1);  
    }

    /*
    * Sobrescribimos el metodo procesarOpcion para añadir mas opciones a nuestra calculadora 
    */
    public function procesarOpcion(){
        parent::procesarOpcion(); 
        if(isset($_POST["sqrt"])) $this->raiz();
        if(isset($_POST["ln"])) $this->logaritmoNeperiano();
        if(isset($_POST["log"])) $this->logaritmoBase10();
        if(isset($_POST["2^x"])) $this->exponencial2();
        if(isset($_POST["10^x"])) $this->exponencial10();
        if(isset($_POST["e^x"])) $this->exponencialE();
        if(isset($_POST["sen"])) $this->seno();
        if(isset($_POST["cos"])) $this->coseno();
        if(isset($_POST["tan"])) $this->tangente();
        if(isset($_POST[")"])) $this->parentesisCerrado();
        if(isset($_POST["("])) $this->parentesisAbierto();
        if(isset($_POST["mod"])) $this->modulo();
        if(isset($_POST["arcsen"])) $this->arcoseno();
        if(isset($_POST["arccos"])) $this->arcocoseno();
        if(isset($_POST["arctan"])) $this->arcotangente();
        if(isset($_POST["pi"])) $this->ctePi();
        if(isset($_POST["e"])) $this->cteE();
        if(isset($_POST["del"])) $this->atras();
    }


    /*
    * Sobrescribimos el metodo igual para que en aquellos casos donde la pantalla no represente un número,
    * se tomo como valor por defecto el 0 para realizar la operacion. 
    */
    public function igual(){
        parent::igual();       
        if(!is_numeric($this->pantalla) && ($this->pantalla != "Error de sintáxis")){
            $this->pantalla = "0"; 
        }
    }

}

session_name("CalculadoraCientifica"); 
session_start(); 

if(!isset($_SESSION["calculadora"])){
    $_SESSION["calculadora"] = new CalculadoraCientifica(); 
}
if (count($_POST)>0) {   
    $_SESSION["calculadora"]->procesarOpcion(); 
}

// Interfaz con el usuario. En el interior de comillas dobles se deben usar comillas simples
echo "  
<form action='#' method='post' name='interfaz'>
<div>
<p>
    <input type='text' title='Pantalla' name='pantalla' value='".$_SESSION["calculadora"]->getPantalla()."' disabled/>
</p>


<p>
    <input type = 'submit' class='button' value='sqrt' name='sqrt' />
    <input type = 'submit' class='button' value='ln' name='ln' />
    <input type = 'submit' class='button' value='log' name='log' />
    <input type = 'submit' class='button' value='m-' name='m-' />
    <input type = 'submit' class='button' value='m+' name='m+' />
    <input type = 'submit' class='button' value='mrc' name='mrc' />
</p>

<p>
    <input type = 'submit' class='button' value='2^x' name='2^x' />
    <input type = 'submit' class='button' value='e^x' name='e^x' />
    <input type = 'submit' class='button' value='10^x' name='10^x' />
    <input type = 'submit' class='button' value='sen' name='sen' />
    <input type = 'submit' class='button' value='cos' name='cos' />
    <input type = 'submit' class='button' value='tan' name='tan' />
</p>

<p>
    <input type = 'submit' class='button' value='(' name='(' />
    <input type = 'submit' class='button' value=')' name=')' />
    <input type = 'submit' class='button' value='mod' name='mod' />
    <input type = 'submit' class='button' value='arcsen' name='arcsen' />
    <input type = 'submit' class='button' value='arccos' name='arccos' />
    <input type = 'submit' class='button' value='arctan' name='arctan' />
</p>

<p>
    <input type = 'submit' class='button' value='7' name='7' />
    <input type = 'submit' class='button' value='8' name='8' />
    <input type = 'submit' class='button' value='9' name='9' />
    <input type = 'submit' class='button' value='/' name='/' />
    <input type = 'submit' class='button' value='pi' name='pi' />
    <input type = 'submit' class='button' value='e' name='e' />
</p>

<p>
    <input type = 'submit' class='button' value='4' name='4' />
    <input type = 'submit' class='button' value='5' name='5' />
    <input type = 'submit' class='button' value='6' name='6' />
    <input type = 'submit' class='button' value='*' name='*' />    
    <input type = 'submit' class='button' value='.' name='punto' />  
    <input type = 'submit' class='button' value='del' name='del' /> 
</p>

<p>
    <input type = 'submit' class='button' value='1' name='1' />
    <input type = 'submit' class='button' value='2' name='2' />
    <input type = 'submit' class='button' value='3' name='3' />
    <input type = 'submit' class='button' value='-' name='-' />
    <input type = 'submit' class='button' value='+' name='+' />
    <input type = 'submit' class='button' value='C' name='C' />
</p>

<p>
    <input type = 'submit' class='button' value='0' name='0' />
    <input type = 'submit' class='button' value='=' name='=' />  
</p>
</div>

</form>
";
?>
</body>
</html>