<!DOCTYPE html>
<html lang="es" dir = "ltr">
<head>
<title>Ejercicio 6</title>
<meta charset="UTF-8">
<meta name="author" content="Moisés Sanjurjo Sáncehz (UO270824)" />
<meta name="desciption" content="Ejercicio6" /> 
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="Ejercicio6.css"/>
</head>
<body>
<h1>Ejercicio 6</h1> 
<p>Menú del ejercicio 6:</p>

<?php 
//localhost/PRACTICA-4/Ejercicio-6/Ejercicio6.php

class BaseDatos {
    
    protected $servername; //Nombre del servidor 
    protected $username; //Nombre de usuario 
    protected $password; //Contraseña 
    protected $dataBaseName; //Nombre de la base de datos 
    protected $tipoInterfaz; //El tipo de interfaz que se está usando 
    protected $interfazUsuarioActual; //El contenido de la interfaz que se está usando 


    /*
    * Constructor de la clase
    */
    public function __construct(){
        $this->dataBaseName = "ejercicio6"; 
        $this->servername = "localhost";
        $this->username = "DBUSER2020";
        $this->password = "DBPSWD2020";
        $this->tipoInterfaz = ""; 
        $this->interfazUsuarioActual = "<h2>Detalles de la base de datos que se gestiona</h2>
        <ul>
        <li>La base de datos se llama 'ejercicio6' y cuenta con una sola tabla llamada 'PruebasUsabilidad'.</li>
        <li>A la hora de rellenar los formularios, no se puede dejar ningún campo en blanco.</li>
        <li>Los campos 'DNI','nombre','apellidos','email','sexo','estado de realización de la tarea','comentarios' y 'propuestas' son cadenas de texto.</li>
        <li>Los campos 'telefono','edad','Nivel o pericia informática','tiempo','valoración de la aplicación' solo admiten numeros enteros.Además, especificamente en los campos 
        'nivel o pericia informática' y 'valoración de la aplicación por parte del usuario' solo se admiten enteros dentro del intervalo [0,10].</li>
        <li>El campo 'sexo' solo admite dos valores: 'MASCULINO' o 'FEMENINO'. </li>
        <li>El campo 'Estado de realización de la tarea' solo admite dos valores: 'CORRECTO' o 'ERRONEO'.</li>
        </ul>"; 
    }


    /*
    * Crea la base de datos
    */
    public function crearBaseDeDatos(){
        
        // Conexión al SGBD local 
        $db = new mysqli($this->servername,$this->username,$this->password);
             
        //comprobamos conexión
        if($db->connect_error) {
            exit ("<p>ERROR de conexión:".$db->connect_error."</p>");  
        } else {$this->interfazUsuarioActual = "<p>Conexión establecida con " . $db->host_info . "</p>";}
       
        // Se crea la base de datos de trabajo "ejercicio6" utilizando ordenación en español
        $cadenaSQL = "CREATE DATABASE IF NOT EXISTS ejercicio6 COLLATE utf8_spanish_ci";
        if($db->query($cadenaSQL) === TRUE){
            $this->interfazUsuarioActual = "<p>Base de datos '".$this->dataBaseName."' creada con éxito</p>";
        } else { 
            $this->interfazUsuarioActual = "<p>ERROR en la creación de la Base de Datos '".$this->dataBaseName."'. Error: " . $db->error . "</p>";
            exit();
        }  

        //cerrar la conexión
        $db->close();    
    }

    
    /*
    * Crea la tabla inicial llamada PruebasUsabilidad
    */
    public function crearTabla(){

        // Conexión al SGBD local con XAMPP con el usuario creado 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);

        //Crear la tabla PruebasUsabilidad
        $crearTabla = "CREATE TABLE IF NOT EXISTS PruebasUsabilidad ( 
                    dni VARCHAR(9) NOT NULL,
                    nombre VARCHAR(255) NOT NULL, 
                    apellidos VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,  
                    telefono INT NOT NULL, 
                    edad INT NOT NULL,
                    sexo VARCHAR(255) NOT NULL,
                    nivel INT NOT NULL,
                    tiempo INT NOT NULL,
                    estadoRealizacion VARCHAR(255) NOT NULL,
                    comentarios VARCHAR(255) NOT NULL,
                    propuestas VARCHAR(255) NOT NULL,
                    valoracion INT NOT NULL,
                    CHECK(edad>=0),
                    CHECK(sexo in ('MASCULINO','FEMENINO')),
                    CHECK(nivel>=0 AND nivel<=10),
                    CHECK(tiempo>=0),
                    CHECK(estadoRealizacion in ('CORRECTO','ERRONEO')),
                    CHECK(valoracion>=0 AND valoracion<=10),
                    PRIMARY KEY (dni))";
                  
        if($db->query($crearTabla) === TRUE){
            $this->interfazUsuarioActual = "<p>Tabla 'PruebasUsabilidad' creada con éxito </p>";
        } else { 
            $this->interfazUsuarioActual = "<p>ERROR en la creación de la tabla PruebasUsabilidad. Error : ". $db->error . "</p>";
            exit();
        }   

        //cerrar la conexión
        $db->close();    
    }

    /*
    * Inserta los datos introducidos en el formulario de inserción en la tabla llamada PruebasUsabilidad
    */
    public function insertarDatos() {
        
        //Comprobamos que ninguno de los campos este vacío
        if(empty($_POST["dni"]) || empty($_POST["nombre"]) || empty($_POST["apellidos"]) || empty($_POST["telefono"]) || empty($_POST["edad"])
        || empty($_POST["nivel"]) || empty($_POST["tiempo"]) || empty($_POST["comentarios"]) || empty($_POST["propuestas"]) || empty($_POST["valoracion"])){
            $this->interfazUsuarioActual = "<p>No puede dejar campos vacíos.</p>"; 
            return; 
        }
        // Conexión al SGBD local con XAMPP con el usuario creado 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);

        // comprueba la conexion
        if($db->connect_error) {
            exit ("<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        } else {$this->interfazUsuarioActual = "<h2>Conexión establecida</h2>";}

        //prepara la sentencia de inserción
        $consultaPre = $db->prepare("INSERT INTO PruebasUsabilidad (dni, nombre, apellidos, email, telefono, edad, sexo, nivel, tiempo
        , estadoRealizacion, comentarios, propuestas, valoracion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");   
    
        //añade los parámetros de la variable Predefinida $_POST
        // Indicando los tipos de datos correspondientes
        $consultaPre->bind_param('ssssiisiisssi', 
                $_POST["dni"],$_POST["nombre"], $_POST["apellidos"], $_POST["email"], $_POST["telefono"], $_POST["edad"], $_POST["sexo"]
            , $_POST["nivel"], $_POST["tiempo"], $_POST["estado"], $_POST["comentarios"], $_POST["propuestas"], $_POST["valoracion"]);    

        //ejecuta la sentencia
        $consultaPre->execute();

        //muestra los resultados
        if($consultaPre->affected_rows == -1){
            $this->interfazUsuarioActual = "<p>No se agregó ninguna fila, compruebe que los datos introducidos siguen el formato correcto. </p>";
        }
        else{
            $this->interfazUsuarioActual = "<p>Filas agregadas: " . $consultaPre->affected_rows . "</p>";
        }
        
        $consultaPre->close();

        //cierra la base de datos
        $db->close();        
    }

    /*
    * Busca por el nombre introducido en el formulario en la tabla PruebasUsabilidad
    */
    public function buscarDatos(){
 
           // Conexión al SGBD local con XAMPP con el usuario creado 
           $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
           // comprueba la conexion
           if($db->connect_error) {
               exit ("<h2>ERROR de conexión:".$db->connect_error."</h2>");  
           } else {$this->interfazUsuarioActual =  "<h2>Conexión establecida</h2>";}
 
            // prepara la consulta
            $consultaPre = $db->prepare("SELECT * FROM PruebasUsabilidad WHERE nombre = ?");   
        
            // obtiene los parámetros de la variable predefinida $_POST
            // s indica que se le pasa un string
            $consultaPre->bind_param('s', $_POST["nombre"]);    

            //ejecuta la consulta
            $consultaPre->execute();

            //Obtiene los resultados como un objeto de la clase mysqli_result
            $resultado = $consultaPre->get_result();

            //Visualización de los resultados de la búsqueda
            if ($resultado->fetch_assoc()!=NULL) {
                $this->interfazUsuarioActual =  "<p>Las filas de la tabla 'PruebasUsabilidad' que coinciden con la búsqueda son:</p>";
                $resultado->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
                $this->interfazUsuarioActual .= "<p>". 'dni' . " - " . 'nombre' ." - ". 'apellidos' ." - ". 'email'
                ." - ". 'telefono'." - ". 'edad'." - ". 'sexo'." - ". 'nivel'." - ". 'tiempo'." - ". 'estadoRealizacion'." - "
                . 'comentarios'." - ". 'propuestas'." - ". 'valoración' ."</p>";
                $this->interfazUsuarioActual .= "<ul>";
                while($fila = $resultado->fetch_assoc()) {
                    $this->interfazUsuarioActual .= "<li>" . $fila["dni"] . " - " . $fila["nombre"] . " - ".$fila['apellidos']." - "
                    . $fila['email']. " - " . $fila["telefono"]. " - " . $fila["edad"]. " - " . $fila["sexo"].  
                    " - " . $fila["nivel"]." - " . $fila["tiempo"]." - " . $fila["estadoRealizacion"]." - " 
                    . $fila["comentarios"]. " - " . $fila["propuestas"]. " - " . $fila["valoracion"] ."</li>"; 
                } 
                $this->interfazUsuarioActual .= "</ul>";             
            } else {
                $this->interfazUsuarioActual = "<p>Búsqueda sin resultados</p>";
            }
           
            // cierre de la consulta y la base de datos
            $consultaPre->close();
            $db->close();      
    }

    /*
    * Borra datos en la tabla PruebasUsabilidad por dni (clave primaria)
    */
    public function borrarDatos(){
        
        // Conexión al SGBD local con el usuario creado previamente en XAMPP
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
        // compruebo la conexion
        if($db->connect_error) {
                exit ("<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        } else {$this->interfazUsuarioActual = "<h2>Conexión establecida</h2>";}

        //prepara la consulta
        $consultaPre = $db->prepare("SELECT * FROM PruebasUsabilidad WHERE dni = ?");   
    
        //obtiene los parámetros de la variable predefinida $_POST
        // s indica que dni es un string
        $consultaPre->bind_param('s', $_POST["dni"]);    

        //ejecuta la consulta
        $consultaPre->execute();

        //guarda los resultados como un objeto de la clase mysqli_result
        $resultado = $consultaPre->get_result();

        //Visualización de los resultados de la búsqueda
        if ($resultado->fetch_assoc()!=NULL) {
            $this->interfazUsuarioActual = "<p>La fila de la tabla 'PruebasUsabilidad' que va a ser eliminada es:</p>";
            $this->interfazUsuarioActual .= "<p>". 'dni' . " - " . 'nombre' ." - ". 'apellidos' ." - ". 'email'
            ." - ". 'telefono'." - ". 'edad'." - ". 'sexo'." - ". 'nivel'." - ". 'tiempo'." - ". 'estadoRealizacion'." - "
            . 'comentarios'." - ". 'propuestas'." - ". 'valoración' ."</p>";
            $resultado->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
            $fila = $resultado->fetch_assoc();
            $this->interfazUsuarioActual .= "<ul>";
            $this->interfazUsuarioActual .= "<li>" . $fila["dni"] . " - " . $fila["nombre"] . " - ".$fila['apellidos']." - "
            . $fila['email']. " - " . $fila["telefono"]. " - " . $fila["edad"]. " - " . $fila["sexo"].  
            " - " . $fila["nivel"]." - " . $fila["tiempo"]." - " . $fila["estadoRealizacion"]." - " 
            . $fila["comentarios"]. " - " . $fila["propuestas"]. " - " . $fila["valoracion"] ."</li>"; 
            $this->interfazUsuarioActual .= "</ul>"; 
        
            //Realiza el borrado
            //prepara la sentencia SQL de borrado
            $consultaPre = $db->prepare("DELETE FROM PruebasUsabilidad WHERE dni = ?");   
            //obtiene los parámetros de la variable almacenada
            $consultaPre->bind_param('s', $_POST["dni"]);    
            //ejecuta la consulta
            $consultaPre->execute();
            // cierra la consulta
            $consultaPre->close();
            $this->interfazUsuarioActual .= "<p>Borrados los datos</p>";               
        } 
        else {
            $this->interfazUsuarioActual = "<p>Búsqueda sin resultados. No se ha borrado nada</p>";
        }

        //consultar la tabla PruebasUsabilidad
        $resultado =  $db->query('SELECT * FROM PruebasUsabilidad');
        
        // compruebo los datos recibidos     
        if ($resultado->num_rows > 0) {
            // Mostrar los datos en un lista
            $this->interfazUsuarioActual .= "<p>Los datos en la tabla 'PruebasUsabilidad' tras el borrado son: </p>";
            $this->interfazUsuarioActual .= "<p>". 'dni' . " - " . 'nombre' ." - ". 'apellidos' ." - ". 'email'
            ." - ". 'telefono'." - ". 'edad'." - ". 'sexo'." - ". 'nivel'." - ". 'tiempo'." - ". 'estadoRealizacion'." - "
            . 'comentarios'." - ". 'propuestas'." - ". 'valoración' ."</p>";
            $this->interfazUsuarioActual .= "<ul>";
            while($fila = $resultado->fetch_assoc()) {
                $this->interfazUsuarioActual .= "<li>" . $fila["dni"] . " - " . $fila["nombre"] . " - ".$fila['apellidos']." - "
                    . $fila['email']. " - " . $fila["telefono"]. " - " . $fila["edad"]. " - " . $fila["sexo"].  
                    " - " . $fila["nivel"]." - " . $fila["tiempo"]." - " . $fila["estadoRealizacion"]." - " 
                    . $fila["comentarios"]. " - " . $fila["propuestas"]. " - " . $fila["valoracion"] ."</li>";  
            }
            $this->interfazUsuarioActual .= "</ul>";
        } else {
            $this->interfazUsuarioActual = "<p>Tabla vacía</p>";
        }          

        //cerrar la conexión
        $db->close();          
    }

    /*
    * Modifica una fila datos en la tabla PruebasUsabilidad. Se identifica la fila a modificar por el dni (clave primaria)
    */
    public function modificarDatos(){

        //Comprobamos que ninguno de los campos este vacío 
        if(empty($_POST["dni"]) || empty($_POST["nombre"]) || empty($_POST["apellidos"]) || empty($_POST["telefono"]) || empty($_POST["edad"])
        || empty($_POST["nivel"]) || empty($_POST["tiempo"]) || empty($_POST["comentarios"]) || empty($_POST["propuestas"]) || empty($_POST["valoracion"])){
            $this->interfazUsuarioActual = "<p>No puede dejar campos vacíos.</p>"; 
            return; 
        }
        
        // Conexión al SGBD local con el usuario creado previamente en XAMPP
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
        // compruebo la conexion
        if($db->connect_error) {
                exit ("<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        } else {$this->interfazUsuarioActual = "<h2>Conexión establecida</h2>";}

        //prepara la consulta
        $consultaPre = $db->prepare("SELECT * FROM PruebasUsabilidad WHERE dni = ?");   
    
        //obtiene los parámetros de la variable predefinida $_POST
        // s indica que dni es un string
        $consultaPre->bind_param('s', $_POST["dni"]);    

        //ejecuta la consulta
        $consultaPre->execute();

        //guarda los resultados como un objeto de la clase mysqli_result
        $resultado = $consultaPre->get_result();

        //Visualización de los resultados de la búsqueda
        if ($resultado->fetch_assoc()!=NULL) {
            $this->interfazUsuarioActual = "<p>La fila de la tabla 'PruebasUsabilidad' que va a ser modificada es:</p>";
            $this->interfazUsuarioActual .= "<p>". 'dni' . " - " . 'nombre' ." - ". 'apellidos' ." - ". 'email'
            ." - ". 'telefono'." - ". 'edad'." - ". 'sexo'." - ". 'nivel'." - ". 'tiempo'." - ". 'estadoRealizacion'." - "
            . 'comentarios'." - ". 'propuestas'." - ". 'valoración' ."</p>";
            $resultado->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
            $fila = $resultado->fetch_assoc(); 
            $this->interfazUsuarioActual .= "<ul>";
            $this->interfazUsuarioActual .= "<li>" . $fila["dni"] . " - " . $fila["nombre"] . " - ".$fila['apellidos']." - "
            . $fila['email']. " - " . $fila["telefono"]. " - " . $fila["edad"]. " - " . $fila["sexo"].  
            " - " . $fila["nivel"]." - " . $fila["tiempo"]." - " . $fila["estadoRealizacion"]." - " 
            . $fila["comentarios"]. " - " . $fila["propuestas"]. " - " . $fila["valoracion"] ."</li>";
            $this->interfazUsuarioActual .= "</ul>"; 
        
            //Realiza la actualizacion
            $consultaPre = $db->prepare("UPDATE PruebasUsabilidad SET nombre=?, apellidos=?, email=?, nivel=?, tiempo=?, estadoRealizacion=?,
            comentarios=?,propuestas=?,valoracion=? WHERE dni = ?");  
            //obtiene los parámetros de la variable almacenada
            $consultaPre->bind_param('sssiisssis', $_POST["nombre"],$_POST["apellidos"],$_POST["email"],$_POST["nivel"],$_POST["tiempo"],$_POST["estado"],
            $_POST["comentarios"],$_POST["propuestas"],$_POST["valoracion"],$_POST["dni"]);    
            //ejecuta la consulta
            $consultaPre->execute();

            if($consultaPre->affected_rows == -1){
                $this->interfazUsuarioActual = "<p>Nuevos datos erroneos, compruebe que los datos introducidos siguen el formato correcto. </p>";
            }
            else{
                $this->interfazUsuarioActual .= "<p>Actualizados los datos</p>";
                //prepara la consulta
                $consultaPre = $db->prepare("SELECT * FROM PruebasUsabilidad WHERE dni = ?");   
    
                //obtiene los parámetros de la variable predefinida $_POST
                // s indica que dni es un string
                $consultaPre->bind_param('s', $_POST["dni"]);    

                //ejecuta la consulta
                $consultaPre->execute();
        
                // compruebo los datos recibidos     
                if ($resultado->num_rows > 0) {
                    // Mostrar los datos en un lista
                    $this->interfazUsuarioActual .= "<p>Los datos en la tabla 'PruebasUsabilidad' tras la actualización son: </p>";
                    $this->interfazUsuarioActual .= "<p>". 'dni' . " - " . 'nombre' ." - ". 'apellidos' ." - ". 'email'
                    ." - ". 'telefono'." - ". 'edad'." - ". 'sexo'." - ". 'nivel'." - ". 'tiempo'." - ". 'estadoRealizacion'." - "
                    . 'comentarios'." - ". 'propuestas'." - ". 'valoración' ."</p>";
                    $resultado = $consultaPre->get_result();
                    $fila = $resultado->fetch_assoc(); 
                    $this->interfazUsuarioActual .= "<ul>";
                    $this->interfazUsuarioActual .= "<li>" . $fila["dni"] . " - " . $fila["nombre"] . " - ".$fila['apellidos']." - "
                    . $fila['email']. " - " . $fila["telefono"]. " - " . $fila["edad"]. " - " . $fila["sexo"].  
                    " - " . $fila["nivel"]." - " . $fila["tiempo"]." - " . $fila["estadoRealizacion"]." - " 
                    . $fila["comentarios"]. " - " . $fila["propuestas"]. " - " . $fila["valoracion"] ."</li>";
                    $this->interfazUsuarioActual .= "</ul>";       
                }       
            }      
        } 
        else {
            $this->interfazUsuarioActual = "<p>No se encontró el DNI introducido. No se ha actualizado nada</p>";
        }
      
        // cierra la consulta
        $consultaPre->close(); 

        //cerrar la conexión
        $db->close();          
    }


    /*
    * Genera un informe con los datos solicitados de la base de datos del ejercicio 
    */
    public function generarInformes(){
        // Conexión al SGBD local con XAMPP con el usuario creado 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
        // comprueba la conexion
        if($db->connect_error) {
            exit ("<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        } else {$this->interfazUsuarioActual =  "<h2>Conexión establecida</h2>";}

        // realizaos las consultas necesarias
        $mediaEdad =  $db->query('SELECT AVG(edad) AS edadMedia FROM PruebasUsabilidad');
        $mediaNivel =  $db->query('SELECT AVG(nivel) AS nivelMedio FROM PruebasUsabilidad');
        $mediaTiempo =  $db->query('SELECT AVG(tiempo) AS tiempoMedio FROM PruebasUsabilidad');
        $mediaValoracion =  $db->query('SELECT AVG(valoracion) AS valoracionMedia FROM PruebasUsabilidad');
        $totalCorrectos =  $db->query("SELECT COUNT(*) AS totalCorrectos FROM PruebasUsabilidad WHERE estadoRealizacion='CORRECTO'");
        $totalMasculino =  $db->query("SELECT COUNT(*) AS totalMasculino FROM PruebasUsabilidad WHERE sexo='MASCULINO'");
        $totalFemenino =  $db->query("SELECT COUNT(*) AS totalFemenino FROM PruebasUsabilidad WHERE sexo='FEMENINO'");

        $resultado =  $db->query('SELECT * FROM PruebasUsabilidad');
        $cadena = "<ul>";
        // compruebo los datos recibidos     
        if ($resultado->num_rows > 0) {       
            
            //Edad media de los usuarios 
            $mediaEdad->data_seek(0); 
            $fila = $mediaEdad->fetch_assoc();
            $cadena .= "<li>La edad media de los usuarios es de ".(int)$fila["edadMedia"]." años</li>"; 
            
            //Porcentaje de cada tipo de sexo entre los usuarios
            
            //Para el masculino
            $totalMasculino->data_seek(0); 
            $fila = $totalMasculino->fetch_assoc();
            $porcentaje = round(($fila["totalMasculino"]/$resultado->num_rows)*100, 2, PHP_ROUND_HALF_UP); 
            $cadena .= "<li>El porcentaje de usuarios masculinos es del ".$porcentaje." %</li>"; 

            //Para el femenino
            $totalFemenino->data_seek(0);
            $fila = $totalFemenino->fetch_assoc();
            $porcentaje = round(($fila["totalFemenino"]/$resultado->num_rows)*100, 2, PHP_ROUND_HALF_UP);  
            $cadena .= "<li>El porcentaje de usuarios femeninos es del ".$porcentaje." %</li>"; 
            
            //Valor medio del nivel de pericia informatica de los usuraios
            $mediaNivel->data_seek(0); 
            $fila = $mediaNivel->fetch_assoc();
            $cadena .= "<li>El valor medio del nivel de pericia informática de los usuarios es de ".(int)$fila["nivelMedio"]."</li>";  

            //Tiempo medio empleado para la tarea
            $mediaTiempo->data_seek(0); 
            $fila = $mediaTiempo->fetch_assoc();
            $tiempo = $porcentaje = round($fila["tiempoMedio"], 2, PHP_ROUND_HALF_UP); 
            $cadena .= "<li>El tiempo medio empleado para la tarea es de  ".$porcentaje." segundos</li>"; 
            
            //Porcentaje de los que realizaron la tarea correctamente 
            $totalCorrectos->data_seek(0);
            $fila = $totalCorrectos->fetch_assoc();
            $porcentaje = $porcentaje = round(($fila["totalCorrectos"]/$resultado->num_rows)*100, 2, PHP_ROUND_HALF_UP); 
            $cadena .= "<li>El porcentaje de los que realizaron la tarea correctamente es del ".$porcentaje." %</li>"; 


            //Valor medio de la puntuación de los usuarios sobre la aplicación
            $mediaNivel->data_seek(0); 
            $fila = $mediaNivel->fetch_assoc();
            $cadena .= "<li>El valor medio de la puntuación de los usuarios sobre la apliación es de  ".(int)$fila["nivelMedio"]."</li>";
            
            $cadena .= "</ul>";
        } 
        else {
            $cadena = "<p>Tabla vacía</p>";
        }
        
        // cierre de la consulta y la base de datos
        $db->close();  

        return $cadena;     
    }

    /*
    * Exporta la información almacenada en la base de datos actualmente a un archivo en formato csv 
    */
    public function exportarCSV(){

        // Conexión al SGBD local con XAMPP con el usuario creado 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
        //Comprobamos que no hubo errores al crear la conexión 
        if($db->connect_error) {
            exit ("<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        } else {$this->interfazUsuarioActual =  "<h2>Conexión establecida</h2>";}

        //consultar la tabla PruebasUsabilidad
        $resultado =  $db->query('SELECT * FROM PruebasUsabilidad');
        
        // compruebo los datos recibidos     
        if ($resultado->num_rows > 0) {

            //Creamos el fichero si no existia 
            $archivo_csv = fopen('pruebasUsabilidad.csv', 'w');
        
            //Recorremos los datos almacenados pasandolos a formato csv (separados por ;) y escribiendolos 
            while($fila = $resultado->fetch_assoc()) {
                fputcsv($archivo_csv,$fila,";"); 
            }

            //Actualizamos la interfaz de usuario
            $this->interfazUsuarioActual = "<p>Se ha exportado en un archivo csv llamado 'pruebasUsabilidad' correctamente la base de datos.Podrá ver y analizar el 
            archivo en la misma carpeta que el ejercicio.</p>";

        } else { //En caso de que la tabla este vavía 
            $this->interfazUsuarioActual = "<p>Tabla vacía</p>";
        }          
                
        // cierre de la consulta y la base de datos
        $db->close();  
    }


    /*
    * Carga un csv y añade la informacion a la base de datos
    */
    public function cargarCSV(){

        // Conexión al SGBD local con XAMPP con el usuario creado 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
        //Comprobamos que no hubo errores al crear la conexión 
        if($db->connect_error) {
            exit ("<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        }

        //Si hay ficheros 
        if ($_FILES) {        
            
            //Si es de tipo csv
            if( strcmp($_FILES['archivo']['type'], 'application/vnd.ms-excel') == 0) {

                //prepara la sentencia de inserción
                $consultaPre = $db->prepare("INSERT INTO PruebasUsabilidad (dni, nombre, apellidos, email, telefono, edad, sexo, nivel, tiempo
                , estadoRealizacion, comentarios, propuestas, valoracion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");   
    
                //Abrimos el fichero csv
                $file = fopen($_FILES['archivo']['tmp_name'], 'rb');
                
                //Lo recorremos
                while (($datos = fgetcsv($file,100,";")) == true) 
                {
                    //Asignamos los parametros y ejecutamos la consulta
                    $consultaPre->bind_param('ssssiisiisssi', 
                    $datos[0],$datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6]
                    , $datos[7], $datos[8], $datos[9], $datos[10], $datos[11], $datos[12]);

                    $consultaPre->execute();      
                }       

                //Cerramos el archivo
                fclose($file);

                // cierre de la consulta
                $consultaPre->close(); 
            }
        }
 
        //Actualizamos la interfaz de usuario 
        $this->interfazUsuarioActual = "<p>Operación de carga realizada.</p>";
        $db->close(); 
    }

    /*
    * Cambia el tipo de interfaz y la genera 
    */
    public function changeInterfaz($tipo){
        $this->tipoInterfaz = $tipo;
        $this->getInterfaz();  
    }

    public function getTipoInterfaz(){
        return $this->tipoInterfaz; 
    }

    /*
    * Genera la interfaz necesaria a partir del tipo de interfaz seleccionado 
    */
    public function getInterfaz(){

        $cadena = ""; 
        if($this->tipoInterfaz == "insercion"){
            $cadena .= "<form action='#' method='post' name='formulario' id='formulario'>";
            $cadena .= "<p>DNI: <input type='text' name='dni' title='DNI'/> </p>"; 
            $cadena .= "<p>Nombre: <input type='text' name='nombre' title='nombre'/> </p>"; 
            $cadena .= "<p>Apellidos: <input type='text' name='apellidos' title='apellidos' /> </p>"; 
            $cadena .= "<p>Email: <input type='text' name='email' title='email' /> </p>";
            $cadena .= "<p>Teléfono: <input type='text' name='telefono' title='telefono' /> </p>";
            $cadena .= "<p>Edad: <input type='text' name='edad' title='edad' /> </p>";
            $cadena .= "<p><label for='sexo'>Sexo:</label>
                        <select name='sexo' id='sexo'>
                            <option value='MASCULINO'>MASCULINO</option>
                            <option value='FEMENINO'>FEMENINO</option>
                        </select></p>"; 
            $cadena .= "<p>Nivel o pericia informática (de 0 a 10): <input type='text' name='nivel' title='nivel' /> </p>";
            $cadena .= "<p>Tiempo de realización de la tarea (segundos): <input type='text' name='tiempo' title='tiempo' /> </p>";
            $cadena .= "<p><label for='estado'>Estado de realización de la tarea:</label> 
                        <select name='estado' id='estado'>
                            <option value='CORRECTO'>CORRECTO</option>
                            <option value='ERRONEO'>ERRONEO</option>
                        </select></p>"; 
            $cadena .= "<p>Comentarios: <input type='text' name='comentarios' title='comentarios'/> </p>";
            $cadena .= "<p>Propuestas: <input type='text' name='propuestas' title='propuestas'/> </p>";
            $cadena .= "<p>Valoración de la aplicación por parte del usuario (de 0 1a 10): <input type='text' name='valoracion'  title='valoracion' /> </p>";
            $cadena .= "<p><input type='submit' class='button' value='Insertar Datos' name='opInsertar'/>  </p>";
            $cadena .= "</form>"; 
        }
        else if($this->tipoInterfaz == "busqueda"){
            $cadena .= "<form action='#' method='post' name='formulario' id='formulario'>";
            $cadena .= "<p>Nombre: <input type='text' name='nombre' title='nombre' /> </p>"; 
            $cadena .= "<input type='submit' value='Buscar' name='opBusqueda' />";       
            $cadena .= "</form>";      
        }
        else if($this->tipoInterfaz == "modificado"){
            $cadena .= "<form action='#' method='post' name='formulario' id='formulario'>";
            $cadena .= "<p>DNI de la fila a modificar: <input type='text' name='dni' title='DNI' /> </p>"; 
            $cadena .= "<p>Nuevo nombre: <input type='text' name='nombre' title='nombre' /> </p>"; 
            $cadena .= "<p>Nuevos apellidos: <input type='text' name='apellidos' title='apellidos'/> </p>"; 
            $cadena .= "<p>Nuevo email: <input type='text' name='email' title='email'/> </p>";
            $cadena .= "<p>Nuevo teléfono: <input type='text' name='telefono' title='telefono' /> </p>";
            $cadena .= "<p>Nueva edad: <input type='text' name='edad' title='edad' /> </p>";
            $cadena .= "<p><label for='sexo'>Nuevo sexo:</label>
                        <select name='sexo' id='sexo'>
                            <option value='MASCULINO'>MASCULINO</option>
                            <option value='FEMENINO'>FEMENINO</option>
                        </select></p>"; 
            $cadena .= "<p>Nuevo nivel o pericia informática (de 0 a 10): <input type='text' name='nivel' title='nivel' /> </p>";
            $cadena .= "<p>Nuevo tiempo de realización de la tarea (segundos): <input type='text' name='tiempo' title='tiempo' /> </p>";
            $cadena .= "<p><label for='estado'>Nuevo estado de realización de la tarea:</label> 
                        <select name='estado' id='estado'>
                            <option value='CORRECTO'>CORRECTO</option>
                            <option value='ERRONEO'>ERRONEO</option>
                        </select></p>"; 
            $cadena .= "<p>Nuevos comentarios: <input type='text' name='comentarios' title='comentarios' /> </p>";
            $cadena .= "<p>Nuevas propuestas: <input type='text' name='propuestas' title='propuestas' /> </p>";
            $cadena .= "<p>Nueva valoración de la aplicación por parte del usuario (de 0 a 10): <input type='text' name='valoracion' title='valoracion' /> </p>";
            $cadena .= "<p><input type='submit' class='button' value='Modificar datos' name='opModificado'/>  </p>";
            $cadena .= "</form>";  
            
        }
        else if($this->tipoInterfaz == "borrado"){
            $cadena .= "<form action='#' method='post' name='formulario' id='formulario'>";
            $cadena .= "<p>DNI: <input type='text' name='dni' title='dni' /> </p>"; 
            $cadena .= "<input type='submit' value='Buscar' name='opBorrado' />";
            $cadena .= "</form>";   
        }
        else if($this->tipoInterfaz == "informe"){
            $cadena .= "<form action='#' method='post' name='formulario' id='formulario'>"; 
            $cadena .= $this->generarInformes();
            $cadena .= "</form>"; 
        }
        else if($this->tipoInterfaz == "cargar"){
            $cadena .= "<form action='#' method='post' enctype='multipart/form-data' id='formulario'>";
            $cadena .= "<label for='archivo'>Archivo a cargar desde la máquina cliente</label>";
            $cadena .= "<p><input type='file' name='archivo' id='archivo'/></p>";
            $cadena .= "<p><input type='submit' value='Enviar'/></p>";
            $cadena .= "</form>";
        }
        else{
            $cadena .= "<p>Pulse el botón de inserción, de búsqueda, de modificado o de borrado para desplegar la intefaz de usuario correspondiente</p>"; 
        }

        $this->interfazUsuarioActual = $cadena;  
    }

    /*
    * Devuelve la interfaz de usuario actual 
    */
    public function getInterfazUsuarioActual(){
        return $this->interfazUsuarioActual; 
    }

    /*
    * Procesa la opción del botón pulsado 
    */
    public function procesarOpcion(){
        if(isset($_POST["crearBD"])) $this->crearBaseDeDatos();
        if(isset($_POST["crearTabla"])) $this->crearTabla();
        if(isset($_POST["insertar"])) $this->changeInterfaz("insercion");
        if(isset($_POST["buscar"])) $this->changeInterfaz("busqueda");
        if(isset($_POST["modificar"])) $this->changeInterfaz("modificado");
        if(isset($_POST["eliminar"])) $this->changeInterfaz("borrado");
        if(isset($_POST["informe"])) $this->changeInterfaz("informe");
        if(isset($_POST["cargar"])) $this->changeInterfaz("cargar");
        if(isset($_POST["exportar"])) $this->exportarCSV();
        if(isset($_POST["opInsertar"])) $this->insertarDatos();
        if(isset($_POST["opBusqueda"])) $this->buscarDatos();
        if(isset($_POST["opBorrado"])) $this->borrarDatos(); 
        if(isset($_POST["opModificado"])) $this->modificarDatos();
    }
}

session_name("Ejercicio6"); 
session_start(); 

if(!isset($_SESSION["baseDatos"])){
    $_SESSION["baseDatos"] = new BaseDatos(); 
}
if (count($_POST)>0) {   
    $_SESSION["baseDatos"]->procesarOpcion(); 
}

// Interfaz con el usuario. En el interior de comillas dobles se deben usar comillas simples
echo "  
<form action='#' method='post' name='menu' id='menu'>
<nav>
    <ul>
        <li><input type = 'submit' class='button' value='Crear Base de Datos'  name='crearBD' /> </li>
        <li><input type = 'submit' class='button' value='Crear una tabla'  name='crearTabla' /> </li>
        <li><input type = 'submit' class='button' value='Insertar datos en una tabla'  name='insertar' /> </li>
        <li><input type = 'submit' class='button' value='Buscar datos en una tabla'  name='buscar' /> </li>
        <li><input type = 'submit' class='button' value='Modificar datos en una tabla'  name='modificar' /> </li>
        <li><input type = 'submit' class='button' value='Eliminar datos de una tabla'  name='eliminar' /> </li>
        <li><input type = 'submit' class='button' value='Generar informe'  name='informe' /> </li>
        <li><input type = 'submit' class='button' value='Cargar datos desde un archivo en una tabla de la Base de Datos'  name='cargar' /> </li>
        <li><input type = 'submit' class='button' value='Exportar datos a un archivo los datos desde una tabla de la Base de Datos'  name='exportar' /> </li>
    </ul>
</nav>
</form>".$_SESSION["baseDatos"]->getInterfazUsuarioActual();

if($_SESSION["baseDatos"]->getTipoInterfaz() == "cargar"){
    $_SESSION["baseDatos"]->cargarCSV();
}

?>
</body>
</html>