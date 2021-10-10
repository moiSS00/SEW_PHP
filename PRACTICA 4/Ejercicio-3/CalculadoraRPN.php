<!DOCTYPE html>
<html lang="es" dir = "ltr">
<head>
<title>Calculadora RPN</title>
<meta charset="UTF-8">
<meta name="author" content="Moisés Sanjurjo Sáncehz (UO270824)" />
<meta name="desciption" content="Ejercicio3" /> 
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="CalculadoraRPN.css"/>
</head>
<body>
<h1>Calculadora RPN</h1> 
<p>A continuación se muestra una calculadora RPN</p>
<?php 
//localhost/PRACTICA-4/Ejercicio-3/CalculadoraRPN.php

class CalculadoraRPN {
    
    protected $pila;
    protected $numero;
    protected $representacionPila; 

    public function __construct(){
        $this->pila = array(); //Inicializa la pila de la calculadora 
        $this->numero = ""; //Cadena que representa al numero que se esta introduciendo 
        $this->representacionPila = ""; 
    }

    /*
    * Escribe en pantalla el dígito que se le pasa como parámetro 
    */
    public function dígitos($digito) {
       $this->numero .= "".$digito;  
    }

    /*
    * Escribe en pantalla un punto decimal
    */
    public function punto(){ 
        $this->numero .= ".";
    }

    /*
    * Extrae y devuelve el ultimo elemento de la pila 
    */
    public function del(){ 
        $aux = array_shift($this->pila)/1; //Divide entre uno, para facilitar la inferencia de tipos 
        return $aux; 
    }


    /*
    * Suma los 2 últimos elementos de la pila 
    */
    public function suma(){  
        $a =  (double) array_shift($this->pila); 
        $b =  (double) array_shift($this->pila); 
        $resultado = ($b + $a); 
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Resta los 2 últimos elementos de la pila 
    */
    public function resta(){ 
        $a =  (double) array_shift($this->pila); 
        $b =  (double) array_shift($this->pila); 
        $resultado = ($b - $a); 
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }
 
    /*
    * Multiplica los 2 últimos elementos de la pila 
    */
    public function multiplicación(){ 
        $a =  (double) array_shift($this->pila); 
        $b =  (double) array_shift($this->pila); 
        $resultado = ($b * $a); 
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Divide los 2 últimos elementos de la pila 
    */
    public function división(){ 
        $a =  (double) array_shift($this->pila); 
        $b =  (double) array_shift($this->pila); 
        $resultado = ($b / $a); 
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Realiza la raíz del último elemento de la pila 
    */
    public function raiz(){ 
        $a =  (double) array_shift($this->pila); 
        $resultado = sqrt($a);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Realiza el logaritmo neperiano del último elemento de la pila 
    */
    public function logaritmoNeperiano(){ 
        $a =  (double) array_shift($this->pila); 
        $resultado = log($a);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Realiza el logaritmo en base 10 del último elemento de la pila 
    */
    public function logaritmoBase10(){ 
        $a =  (double) array_shift($this->pila);
        $resultado = log10($a);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Realiza  la potencia tomando como base el ultimo elemento de la pila y como 
    * exponente el penultimo elemento de la pila
    */
    public function potencia(){ 
        $base =  (double) array_shift($this->pila);
        $exponente =  array_shift($this->pila);
        $resultado = pow($base,$exponente);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Realiza el seno del último elemento de la pila 
    */
    public function seno(){
        $a =  (double) array_shift($this->pila);
        $resultado = sin($a);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Realiza el coseno del último elemento de la pila 
    */
    public function coseno(){
        $a =  (double) array_shift($this->pila);
        $resultado = cos($a);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Realiza la tangente del último elemento de la pila 
    */
    public function tangente(){
        $a =  (double) array_shift($this->pila);
        $resultado = tan($a);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }


    /*
    * Realiza el arcoseno del último elemento de la pila 
    */
    public function arcoseno(){
        $a =  (double) array_shift($this->pila);
        $resultado = asin($a);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Realiza el arcocoseno del último elemento de la pila 
    */
    public function arcocoseno(){
        $a =  (double) array_shift($this->pila);
        $resultado = acos($a);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero(); 
    }

    /*
    * Realiza la arcotangente del último elemento de la pila 
    */
    public function arcotangente(){
        $a =  (double) array_shift($this->pila);
        $resultado = atan($a);  
        array_unshift($this->pila, $resultado);
        $this->limpiarNumero();  
    }

    /*
    * Añade la constante PI a la pantalla 
    */
    public function ctePi(){
        $this->numero .= pi();
    }

    /*
    * Añade la constante E a la pantalla 
    */
    public function cteE(){
        $this->numero .= exp(1);
    }

    /*
    * Limpia la pantalla que representa el número introducido
    */
    public function limpiarNumero(){  
        $this->numero = ""; 
    }

    /*
    * Muestra en la pantalla el estado actual de la pila
    */
    public function getRepresentacionPila() {
        $cadena = "<div id='pantalla'><ul>";
        if(!empty($this->pila)){
            for($i=sizeof($this->pila)-1;$i>=0;$i--){
                $cadena .= "<li>Pila[".$i."] = ".$this->pila[$i]."</li>";
            }
            $cadena .= "</ul></div>";
            return $cadena; 
        }
        return $cadena."<li>Pila vacía</li></ul></div>";
    }

    /*
    * Devuelve el número introducido
    */
    public function getNumero(){  
        return $this->numero; 
    }

    /*
    * Introduce un numero en la pila 
    */
    public function enter(){ 
        array_unshift($this->pila, $this->numero);
        $this->limpiarNumero(); 
    }

    /*
    * Procesa las acciones a realizar al pulsar uno de los botones de la calculadora 
    */
    public function procesarOpcion(){
        if(isset($_POST["sqrt"])) $this->raiz();
        if(isset($_POST["x^y"])) $this->potencia();
        if(isset($_POST["ln"])) $this->logaritmoNeperiano();
        if(isset($_POST["log"])) $this->logaritmoBase10();
        if(isset($_POST["e"])) $this->cteE();
        if(isset($_POST["sen"])) $this->seno();
        if(isset($_POST["cos"])) $this->coseno();
        if(isset($_POST["tan"])) $this->tangente();
        if(isset($_POST["delNum"])) $this->limpiarNumero();
        if(isset($_POST["pi"])) $this->ctePi();
        if(isset($_POST["arccos"])) $this->arcoseno();
        if(isset($_POST["arcsen"])) $this->arcoseno();
        if(isset($_POST["arctan"])) $this->arcotangente();
        if(isset($_POST["delPila"])) $this->del();
        if(isset($_POST["ENTER"])) $this->enter();
        if(isset($_POST["7"])) $this->dígitos(7);
        if(isset($_POST["8"])) $this->dígitos(8);
        if(isset($_POST["9"])) $this->dígitos(9);
        if(isset($_POST["*"])) $this->multiplicación();
        if(isset($_POST["/"])) $this->división();
        if(isset($_POST["4"])) $this->dígitos(4);
        if(isset($_POST["5"])) $this->dígitos(5);
        if(isset($_POST["6"])) $this->dígitos(6);
        if(isset($_POST["+"])) $this->suma();
        if(isset($_POST["-"])) $this->resta();
        if(isset($_POST["0"])) $this->dígitos(0);
        if(isset($_POST["1"])) $this->dígitos(1);
        if(isset($_POST["2"])) $this->dígitos(2);
        if(isset($_POST["3"])) $this->dígitos(3);
        if(isset($_POST["punto"])) $this->punto();
    }
}
session_name("CalculadoraRPN"); 
session_start(); 

if(!isset($_SESSION["calculadora"])){
    $_SESSION["calculadora"] = new CalculadoraRPN(); 
}
if (count($_POST)>0) {   
    $_SESSION["calculadora"]->procesarOpcion(); 
}

// Interfaz con el usuario. En el interior de comillas dobles se deben usar comillas simples
echo "  
<form action='#' method='post' name='interfaz'>
<div>".$_SESSION["calculadora"]->getRepresentacionPila()."


<input type='text' title='PantallaNumero' name='Número' value ='".$_SESSION["calculadora"]->getNumero()."' disabled/>

<p>
    <input type = 'submit' class='button' value='sqrt' name='sqrt' />
    <input type = 'submit' class='button' value='x^y' name='x^y' />
    <input type = 'submit' class='button' value='ln' name='ln' />
    <input type = 'submit' class='button' value='log' name='log' />
    <input type = 'submit' class='button' value='e' name='e' />
</p>

<p>
    <input type = 'submit' class='button' value='sen' name='sen' />
    <input type = 'submit' class='button' value='cos' name='cos' />
    <input type = 'submit' class='button' value='tan' name='tan' />
    <input type = 'submit' class='button' value='delNum' name='delNum' />
    <input type = 'submit' class='button' value='pi' name='pi' />
</p>

<p>
    <input type = 'submit' class='button' value='arcsen' name='arcsen' />
    <input type = 'submit' class='button' value='arccos' name='arccos' />
    <input type = 'submit' class='button' value='arctan' name='arcotangente' />
    <input type = 'submit' class='button' value='delPila' name='delPila' /> 
    <input type = 'submit' class='button' value='ENTER' name='ENTER' />
</p>

<p>
    <input type = 'submit' class='button' value='7' name='7' />
    <input type = 'submit' class='button' value='8' name='8' />
    <input type = 'submit' class='button' value='9' name='9' />
    <input type = 'submit' class='button' value='*' name='*' />   
    <input type = 'submit' class='button' value='/' name='/' />
</p>

<p>
    <input type = 'submit' class='button' value='4' name='4' />
    <input type = 'submit' class='button' value='5' name='5' />
    <input type = 'submit' class='button' value='6' name='6' />
    <input type = 'submit' class='button' value='+' name='+' />
    <input type = 'submit' class='button' value='-' name='-' />  
</p>

<p>
    <input type = 'submit' class='button' value='0' name='0' />
    <input type = 'submit' class='button' value='1' name='1' />
    <input type = 'submit' class='button' value='2' name='2' />
    <input type = 'submit' class='button' value='3' name='3' />
    <input type = 'submit' class='button' value='.' name='punto' /> 
</p>
</div>
</form>
";
?>
</body>
</html>