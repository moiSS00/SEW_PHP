<!DOCTYPE html>
<html lang="es" dir = "ltr">
<head>
<title>Ejercicio 7</title>
<meta charset="UTF-8">
<meta name="author" content="Moisés Sanjurjo Sáncehz (UO270824)" />
<meta name="desciption" content="Ejercicio7" /> 
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="Ejercicio7.css"/>
</head>
<body>
<h1>Ejercicio 7</h1> 
<p>Menú del ejercicio 7:</p>

<?php 
//localhost/PRACTICA-4/Ejercicio-7/Ejercicio7.php

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
        $this->dataBaseName = "ejercicio7"; 
        $this->servername = "localhost";
        $this->username = "DBUSER2020";
        $this->password = "DBPSWD2020";
        $this->tipoInterfaz = ""; 
        $this->interfazUsuarioActual = "<h2>Explicacion del funcionamiento de los botones</h2>
        <ul>
        <li>'Crear base de datos': Crea la base de datos 'ejercicio7' necesaria para la realización del ejercicio.</li>
        <li>'Crear las tablas': Crea las 4 tablas que componenen nuestra base de datos ('Director','Actor','Pelicula','Actua').</li>
        <li>'Insertar datos en las tablas': Inserta los datos necesarios en la base de datos para poder realizar las pruebas de la aplicación con comodidad.</li>
        <li>'Mostrar todos los datos disponibles': Muestra todos los datos que contiene la base de datos organizados por la tabla a la que pertenecen.</li>
        <li>'Obtener reparto por id de película': Despliega una nueva interfaz de usuario. En esta podemos introducir el id de una película y nos devolverá en forma 
        de texto el director de esa película y los actores que actuaron en ella.</li>
        <li>'Buscar películas por id de actor':  Despliega una nueva interfaz de usuario. En esta podemos introducir el id de un actor y nos devolverá en forma 
        de texto las películas en las que ha actuado.</li>
        <li>'Buscar películas por id de director':  Despliega una nueva interfaz de usuario. En esta podemos introducir el id de un director y nos devolverá en forma 
        de texto las películas que ha dirigido.</li>
        <li>'Generar informe':  Despliega un informe con varios datos estadísticos sobre el contenido actual de la base de datos.</li>
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
       
        // Se crea la base de datos de trabajo "ejercicio7" utilizando ordenación en español
        $cadenaSQL = "CREATE DATABASE IF NOT EXISTS ejercicio7 COLLATE utf8_spanish_ci";
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
    * Creamos las 4 tablas que componen nuestra base de datos
    */
    public function crearTablas(){

        // Conexión al SGBD local con XAMPP con el usuario creado 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);

        //Crear la tabla Pelicula
        $crearTablaPelicula = "CREATE TABLE IF NOT EXISTS Pelicula ( 
                    id_pelicula INT NOT NULL AUTO_INCREMENT,
                    titulo VARCHAR(255) NOT NULL, 
                    fecha_salida DATE NOT NULL,
                    categoria VARCHAR(255) NOT NULL,  
                    valoracion INT NOT NULL,
                    id_director INT NOT NULL,
                    CHECK(categoria in ('SUSPENSE','COMEDIA','ACCIÓN')),
                    CHECK(valoracion>=0 AND valoracion<=10),
                    FOREIGN KEY (id_director) REFERENCES Director(id_director),
                    PRIMARY KEY (id_pelicula))";

        //Crear la tabla Actor
        $crearTablaActor = "CREATE TABLE IF NOT EXISTS Actor ( 
                    id_actor INT NOT NULL AUTO_INCREMENT,
                    nombre_actor VARCHAR(255) NOT NULL, 
                    apellidos_actor VARCHAR(255) NOT NULL,
                    sexo_actor VARCHAR(255) NOT NULL,  
                    edad_actor INT NOT NULL, 
                    CHECK(sexo_actor in ('MASCULINO','FEMENINO')),
                    CHECK(edad_actor>=0),
                    PRIMARY KEY (id_actor))";

        //Crear la tabla Director
        $crearTablaDirector = "CREATE TABLE IF NOT EXISTS Director ( 
                    id_director INT NOT NULL AUTO_INCREMENT,
                    nombre_director VARCHAR(255) NOT NULL, 
                    apellidos_director VARCHAR(255) NOT NULL,
                    sexo_director VARCHAR(255) NOT NULL,  
                    edad_director INT NOT NULL,
                    reconocimiento INT NOT NULL, 
                    CHECK(sexo_director in ('MASCULINO','FEMENINO')),
                    CHECK(edad_director>=0),
                    CHECK(reconocimiento>=0 AND reconocimiento<=10),
                    PRIMARY KEY (id_director))";

        //Crear la tabla Actua
        $crearTablaActua = "CREATE TABLE IF NOT EXISTS Actua ( 
                    id_actor INT NOT NULL,
                    id_pelicula INT NOT NULL,
                    PRIMARY KEY (id_actor,id_pelicula),
                    FOREIGN KEY (id_actor) REFERENCES Actor(id_actor),
                    FOREIGN KEY (id_pelicula) REFERENCES Pelicula(id_pelicula))";
                  
            
        //Comprobamos que no haya habido algún error en la creación de alguna tabla
        if($db->query($crearTablaDirector) === TRUE){
            $this->interfazUsuarioActual = "<p>Tabla 'Director' creada con éxito </p>";
        } else { 
            $this->interfazUsuarioActual = "<p>ERROR en la creación de la tabla Director. Error : ". $db->error . "</p>";
            exit();
        }  
                        
        if($db->query($crearTablaPelicula) === TRUE){
            $this->interfazUsuarioActual .= "<p>Tabla 'Pelicula' creada con éxito </p>";
        } else { 
            $this->interfazUsuarioActual = "<p>ERROR en la creación de la tabla Pelicula. Error : ". $db->error . "</p>";
            exit();
        }

        if($db->query($crearTablaActor) === TRUE){
            $this->interfazUsuarioActual .= "<p>Tabla 'Actor' creada con éxito </p>";
        } else { 
            $this->interfazUsuarioActual = "<p>ERROR en la creación de la tabla Actor. Error : ". $db->error . "</p>";
            exit();
        }

        if($db->query($crearTablaActua) === TRUE){
            $this->interfazUsuarioActual .= "<p>Tabla 'Actua' creada con éxito </p>";
        } else { 
            $this->interfazUsuarioActual = "<p>ERROR en la creación de la tabla Actua. Error : ". $db->error . "</p>";
            exit();
        }


        //cerrar la conexión
        $db->close();    
    }

    /*
    * Inserta datos en la base de datos para realizar las pruebas con comodidad
    */
    public function insertarDatos() {
        
        // Conexión al SGBD 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);

        // comprueba la conexion
        if($db->connect_error) {
            exit ("<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        } 
    
        //Insertamos datos en la tabla Director
        $db->query("INSERT INTO Director (nombre_director, apellidos_director, sexo_director, edad_director, reconocimiento) 
        VALUES ('Lana','Wachowski','FEMENINO',30,6)"); 
        $db->query("INSERT INTO Director (nombre_director, apellidos_director, sexo_director, edad_director, reconocimiento) 
        VALUES ('Rob','Reiner','MASCULINO',46,5)"); 
        $db->query("INSERT INTO Director (nombre_director, apellidos_director, sexo_director, edad_director, reconocimiento) 
        VALUES ('Taylor','Hackford','MASCULINO',69,8)"); 

        //Insertamos datos en la tabla Pelicula
        $db->query("INSERT INTO Pelicula (titulo, fecha_salida, categoria, valoracion, id_director) 
        VALUES ('The matrix','1999-06-23','ACCIÓN',7,1)"); 
        $db->query("INSERT INTO Pelicula (titulo, fecha_salida, categoria, valoracion, id_director) 
        VALUES ('The matrix Reloaded','2003-5-23','ACCIÓN',6,1)"); 
        $db->query("INSERT INTO Pelicula (titulo, fecha_salida, categoria, valoracion, id_director) 
        VALUES ('The Devils Advocte','1998-01-12','SUSPENSE',6,3)"); 
        $db->query("INSERT INTO Pelicula (titulo, fecha_salida, categoria, valoracion, id_director) 
        VALUES ('A few Good men','1992-12-09','SUSPENSE',5,2)"); 
 

        //Insertamos datos en la tabla Actor
        $db->query("INSERT INTO Actor (nombre_actor, apellidos_actor, sexo_actor, edad_actor) 
        VALUES ('Keanu','Reeves','MASCULINO',56)"); 
        $db->query("INSERT INTO Actor (nombre_actor, apellidos_actor, sexo_actor, edad_actor) 
        VALUES ('Carrie-Anne','Moss','FEMENINO',53)"); 
        $db->query("INSERT INTO Actor (nombre_actor, apellidos_actor, sexo_actor, edad_actor) 
        VALUES ('Alfredo','James Pacino','MASCULINO',80)");
        $db->query("INSERT INTO Actor (nombre_actor, apellidos_actor, sexo_actor, edad_actor) 
        VALUES ('Jack','Nicholson','MASCULINO',83)"); 
        $db->query("INSERT INTO Actor (nombre_actor, apellidos_actor, sexo_actor, edad_actor) 
        VALUES ('Tom','Cruise','MASCULINO',58)");  

        //Insertamos datos en la tabla Actua
        $db->query("INSERT INTO Actua (id_actor, id_pelicula) 
        VALUES (1,1)"); 
        $db->query("INSERT INTO Actua (id_actor, id_pelicula) 
        VALUES (1,2)"); 
        $db->query("INSERT INTO Actua (id_actor, id_pelicula) 
        VALUES (1,3)");  
        $db->query("INSERT INTO Actua (id_actor, id_pelicula) 
        VALUES (2,1)"); 
        $db->query("INSERT INTO Actua (id_actor, id_pelicula) 
        VALUES (2,3)");
        $db->query("INSERT INTO Actua (id_actor, id_pelicula) 
        VALUES (3,2)"); 
        $db->query("INSERT INTO Actua (id_actor, id_pelicula) 
        VALUES (4,4)");
        $db->query("INSERT INTO Actua (id_actor, id_pelicula) 
        VALUES (5,4)");

        //Notificación de que ya ha acabado la inserción de datos 
        $this->interfazUsuarioActual = "<p>Se han insertado los datos.</p>";
        
        //cierra la base de datos
        $db->close();        
    }


    /*
    * Muestra el contenido de toda la base de datos organizado por tablas
    */
    public function mostrarTodo(){

        //Creo una conexión 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
        // comprueba la conexion
        if($db->connect_error) {
            exit ($this->interfazUsuarioActual = "<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        } 

        //Obtenemos los datos
        $peliculas =  $db->query('SELECT * FROM Pelicula');
        $actores =  $db->query('SELECT * FROM Actor');
        $directores =  $db->query('SELECT * FROM Director');
        $actuaciones =  $db->query('SELECT * FROM Actua');

        //Datos de las peliculas 
        $this->interfazUsuarioActual = "<p>Las películas disponibles actualmente son:</p>";

        if ($peliculas->num_rows > 0) {
            $peliculas->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
            $this->interfazUsuarioActual .= "<p>". 'id' . " - " . 'título' ." - ". 'fecha_salida' ." - ". 'categoria'
                ." - ". 'valoración (de 0 a 10)'."</p>";
            $this->interfazUsuarioActual .= "<ul>";
            while($fila = $peliculas->fetch_assoc()) {
                $this->interfazUsuarioActual .= "<li>" . $fila["id_pelicula"] . " - " . $fila["titulo"] . " - ".$fila['fecha_salida']." - "
                    . $fila['categoria']. " - " . $fila["valoracion"] . "</li>";
            }
            $this->interfazUsuarioActual .= "</ul>";               
        } else {
            $this->interfazUsuarioActual .= "<p>Tabla vacía.</p>";
        }  

        //Datos de los directores 
        $this->interfazUsuarioActual .= "<p>Los directores disponibles actualmente son:</p>";
        if ($directores->num_rows > 0) {
            $directores->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
            $this->interfazUsuarioActual .= "<p>". 'id' . " - " . 'nombre' ." - ". 'apellidos' ." - ". 'sexo'
                ." - ". 'edad'." - ". 'reconocimiento (de 0 a 10)'."</p>";
            $this->interfazUsuarioActual .= "<ul>";
            while($fila = $directores->fetch_assoc()) {
                $this->interfazUsuarioActual .= "<li>" . $fila["id_director"] . " - " . $fila["nombre_director"] . " - ".$fila['apellidos_director']." - "
                    . $fila['sexo_director']. " - " . $fila["edad_director"] . $fila["reconocimiento"] ."</li>";
            }
            $this->interfazUsuarioActual .= "</ul>";               
        } else {
            $this->interfazUsuarioActual .= "<p>Tabla vacía.</p>";
        }  

        //Datos de los actores 
        $this->interfazUsuarioActual .= "<p>Los actores disponibles actualmente son:</p>";
        if ($actores->num_rows > 0) {
            $actores->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
            $this->interfazUsuarioActual .= "<p>". 'id' . " - " . 'nombre' ." - ". 'apellidos' ." - ". 'sexo'
                ." - ". 'edad'."</p>";
            $this->interfazUsuarioActual .= "<ul>";
            while($fila = $actores->fetch_assoc()) {
                $this->interfazUsuarioActual .= "<li>" . $fila["id_actor"] . " - " . $fila["nombre_actor"] . " - ".$fila['apellidos_actor']." - "
                    . $fila['sexo_actor']. " - " . $fila["edad_actor"] . "</li>";
            }    
            $this->interfazUsuarioActual .= "</ul>";           
        } else {
            $this->interfazUsuarioActual .= "<p>Tabla vacía.</p>";
        }  

        //Datos de las actuaciones  
        $this->interfazUsuarioActual .= "<p>Las actuaciones que se han realizado hasta el momento son:</p>";
        if ($actuaciones->num_rows > 0) {
            $actuaciones->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
            $this->interfazUsuarioActual .= "<p>". 'id del actor' . " - " . 'id de la película en la que actuó</p>';
            $this->interfazUsuarioActual .= "<ul>";
            while($fila = $actuaciones->fetch_assoc()) {
                $this->interfazUsuarioActual .= "<li>" . $fila["id_actor"] . " - " . $fila["id_pelicula"]  . "</li>";
            }
            $this->interfazUsuarioActual .= "</ul>";               
        } else {
            $this->interfazUsuarioActual .= "<p>Tabla vacía.</p>";
        }  

        //Cerramos la base de datos 
        $db->close();  
    }


    /*
    * Busca el director y de los actores de la película cuyo id es igual al introducido en el formulario 
    */
    public function buscarReparto(){
 
        //Creo una conexión 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
        // comprueba la conexion
        if($db->connect_error) {
            exit ($this->interfazUsuarioActual = "<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        } 

        //Comprobamos si existe el id introducido 
        $consultaPre = $db->prepare("SELECT * FROM Pelicula WHERE id_pelicula = ?");
        $consultaPre->bind_param('i', $_POST["idPelicula"]); 
        $consultaPre->execute();

        $resultado = $consultaPre->get_result();

        if($resultado->num_rows > 0){

            //Obtenemos el director de la pelicula e imprimimos sus datos
            $consultaPre =  $director = $db->prepare("SELECT d.id_director, d.nombre_director, d.apellidos_director, d.sexo_director, d.edad_director ,d.reconocimiento 
            FROM pelicula AS p, director AS d WHERE p.id_director = d.id_director AND p.id_pelicula = ?"); 

            $consultaPre->bind_param('i', $_POST["idPelicula"]);  
            $consultaPre->execute(); 
            $resultado = $consultaPre->get_result();

            $this->interfazUsuarioActual = "<p>Director de la película:</p>";
            $this->interfazUsuarioActual .= "<p>". 'id' . " - " . 'nombre' ." - ". 'apellidos' ." - ". 'sexo'
                ." - ". 'edad'." - ". 'reconocimiento (de 0 a 10)'."</p>";
            $this->interfazUsuarioActual .= "<ul>";
            $resultado->data_seek(0); 
            $fila = $resultado->fetch_assoc();
            $this->interfazUsuarioActual .= "<li>" . $fila["id_director"] . " - " . $fila["nombre_director"] . " - ".$fila['apellidos_director']." - "
                . $fila['sexo_director']. " - " . $fila["edad_director"]. " - " . $fila["reconocimiento"] . "</li>"; 
            $this->interfazUsuarioActual .= "</ul>";

            //Obtenemos los actores de la película e imprimos sus datos 
            $consultaPre = $db->prepare("SELECT act.id_actor, act.nombre_actor, act.apellidos_actor, act.sexo_actor, act.edad_actor 
            FROM actua AS a, actor AS act WHERE a.id_actor = act.id_actor AND a.id_pelicula = ?"); 

            $consultaPre->bind_param('i', $_POST["idPelicula"]);  
            $consultaPre->execute();
            $resultado = $consultaPre->get_result();
            
            $this->interfazUsuarioActual .=  "<p>Los actores de la película son:</p>";

            //Si se encontró al menos un actor 
            if ($resultado->num_rows > 0) {
                $resultado->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
                $this->interfazUsuarioActual .= "<p>". 'id' . " - " . 'nombre' ." - ". 'apellidos' ." - ". 'sexo'
                    ." - ". 'edad'."</p>";
                $this->interfazUsuarioActual .= "<ul>";
                while($fila = $resultado->fetch_assoc()) {
                    $this->interfazUsuarioActual .= "<li>" . $fila["id_actor"] . " - " . $fila["nombre_actor"] . " - ".$fila['apellidos_actor']." - "
                        . $fila['sexo_actor']. " - " . $fila["edad_actor"] . "</li>";
                }   
                $this->interfazUsuarioActual .= "</ul>";            
            } else {
                $this->interfazUsuarioActual .= "<p>No tiene actores</p>";
            }  
        }
        else{
            $this->interfazUsuarioActual = "<p>El id de la película no existe.Introduzca otro.</p>";
        }

        //Cerramos la consulta y la base de datos 
        $consultaPre->close();
        $db->close();      
    }

    /*
    * Buscamos las peliculas en las que actuó el actor cuyo id es igual al introducido en el formulario 
    */
    public function buscarPeliculasPorActor(){
        
        //Creamos la conexion
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
        // comprueba la conexion
        if($db->connect_error) {
            exit ($this->interfazUsuarioActual = "<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        }   

        //Comprobamos si existe el actor con el id introducido 
        $consultaPre = $db->prepare("SELECT * FROM Actor WHERE id_actor = ?");
        $consultaPre->bind_param('i', $_POST["idActor"]); 
        $consultaPre->execute();
        $resultado = $consultaPre->get_result();

        if($resultado->num_rows > 0){

            //Obtenemos las películas en las que actuó 
            $this->interfazUsuarioActual = "<p>Las películas en las que actuó el actor con id ". $_POST["idActor"] ." son:</p>";
            $consultaPre = $db->prepare("SELECT p.id_pelicula, p.titulo, p.fecha_salida, p.categoria, p.valoracion
            FROM Actua AS a, Pelicula AS p WHERE a.id_pelicula = p.id_pelicula AND a.id_actor = ?");
            $consultaPre->bind_param('i', $_POST["idActor"]); 
            $consultaPre->execute();
            $resultado = $consultaPre->get_result();

            if($resultado ->num_rows > 0){
                $resultado->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
                $this->interfazUsuarioActual .= "<p>". 'id' . " - " . 'título' ." - ". 'fecha_salida' ." - ". 'categoria'
                    ." - ". 'valoración (de 0 a 10)'."</p>";
                $this->interfazUsuarioActual .= "<ul>";
    
                while($fila = $resultado->fetch_assoc()) {
                    $this->interfazUsuarioActual .= "<li>" . $fila["id_pelicula"] . " - " . $fila["titulo"] . " - ".$fila['fecha_salida']." - "
                        . $fila['categoria']. " - " . $fila["valoracion"] . "</li>";
                }
                $this->interfazUsuarioActual .= "</ul>";
            }
            else{
                $this->interfazUsuarioActual = "<p>No ha actuado en ninguna pelicula hasta la fecha.</p>";
            }
        }
        else{
            $this->interfazUsuarioActual = "<p>El id del actor no existe. Introduzca otro.</p>";
        }


        //Cerramos la consulta y la conexion 
        $consultaPre->close(); 
        $db->close();    
    }

    /*
    * Buscamos las peliculas que fueron dirigidas por el director cuyo id es igual al introducido en el formulario 
    */
    public function buscarPeliculasPorDirector(){
 
        //Creo una conexión 
        $db = new mysqli($this->servername,$this->username,$this->password,$this->dataBaseName);
 
        // comprueba la conexion
        if($db->connect_error) {
            exit ($this->interfazUsuarioActual = "<h2>ERROR de conexión:".$db->connect_error."</h2>");  
        } 

        //Nos aseguramos de que exista un director con el id introducido 
        $consultaPre = $db->prepare("SELECT * FROM Director WHERE id_director = ?");
        $consultaPre->bind_param('i', $_POST["idDirector"]); 
        $consultaPre->execute();
        $resultado = $consultaPre->get_result();
        
        if($resultado->num_rows > 0){
        
            $this->interfazUsuarioActual = "<p>Las película que dirigió el director con " .$_POST["idDirector"] . " son:</p>";
            //Buscar las peliculas que dirigio 
            $consultaPre = $db->prepare("SELECT id_pelicula,titulo,fecha_salida,categoria,valoracion FROM Pelicula WHERE id_director = ?");
            $consultaPre->bind_param('i', $_POST["idDirector"]); 
            $consultaPre->execute();
            $resultado = $consultaPre->get_result();
        
            //Si se encontró al menos una pelicula 
            if($resultado->num_rows > 0){
                $resultado->data_seek(0); //Se posiciona al inicio del resultado de búsqueda
                $this->interfazUsuarioActual .= "<p>". 'id' . " - " . 'título' ." - ". 'fecha_salida' ." - ". 'categoria'
                    ." - ". 'valoración (de 0 a 10)'."</p>";
                $this->interfazUsuarioActual .= "<ul>";
                while($fila = $resultado->fetch_assoc()) {
                    $this->interfazUsuarioActual .= "<li>" . $fila["id_pelicula"] . " - " . $fila["titulo"] . " - ".$fila['fecha_salida']." - "
                        . $fila['categoria']. " - " . $fila["valoracion"] . "</li>";
                }
                $this->interfazUsuarioActual .= "</ul>";    
            }
            else{
                $this->interfazUsuarioActual .= "<p>No ha dirigido ninguna pelicula hasta la fecha.</p>";
            }
        }
        else{
            $this->interfazUsuarioActual = "<p>El id del director no existe.Introduzca otro.</p>";
        }
        
        //Cerramos la consulta y la conexion
        $consultaPre->close(); 
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

        $mediaValoracion = $db->query("SELECT AVG(valoracion) AS mediaValoracion FROM Pelicula");
        $mediaReconocimiento = $db->query("SELECT AVG(reconocimiento) AS mediaReconocimiento FROM Director");
        $peliculasMayorValoracion = $db->query("SELECT * FROM Pelicula AS p1 WHERE p1.valoracion = (SELECT MAX(p2.valoracion) FROM Pelicula AS p2)"); 
        $peliculasMenorValoracion = $db->query("SELECT * FROM Pelicula AS p1 WHERE p1.valoracion = (SELECT MIN(p2.valoracion) FROM Pelicula AS p2)"); 
        $directoresMayorReconocimiento = $db->query("SELECT * FROM Director AS d1 WHERE d1.reconocimiento = (SELECT MAX(d2.reconocimiento) FROM Director AS d2)"); 
        $directoresMenorReconocimiento = $db->query("SELECT * FROM Director AS d1 WHERE d1.reconocimiento = (SELECT MIN(d2.reconocimiento) FROM Director AS d2)");
        
        $cadena = "<h2>Informe actual:</h2>";

        //Valoración media de las películas 
        $mediaValoracion->data_seek(0); 
        $fila = $mediaValoracion->fetch_assoc();
        $cadena .= "<p>La valoración media de las peliculas es de ".(int)$fila["mediaValoracion"]." puntos</p>"; 

        //Reconocimiento medio de los directores
        $mediaReconocimiento->data_seek(0); 
        $fila = $mediaReconocimiento->fetch_assoc();
        $cadena .= "<p>El reconocimiento medio de los directores es de ".(int)$fila["mediaReconocimiento"]." puntos</p>"; 


        $cadena .= "<p>Las películas con mayor valoración son:</p>";
        //Peliculas con mayor valoración 
        if($peliculasMayorValoracion->num_rows > 0){
            $peliculasMayorValoracion->data_seek(0); 
            $cadena .= "<ul>";
            while($fila = $peliculasMayorValoracion->fetch_assoc()) {
                $cadena .= "<li>La película con id '" . $fila["id_pelicula"] . "' y con título '" . $fila["titulo"] . "',tiene una 
                valoración de " . $fila["valoracion"] .  " puntos.</li>";
            } 
            $cadena .= "</ul>";   
        }


        $cadena .= "<p>Las películas con menor valoración son:</p>";
        //Peliculas con menor valoración 
        if($peliculasMenorValoracion->num_rows > 0){
            $peliculasMenorValoracion->data_seek(0); 
            $cadena .= "<ul>";
            while($fila = $peliculasMenorValoracion->fetch_assoc()) {
                $cadena .= "<li>La película con id '" . $fila["id_pelicula"] . "' y con título '" . $fila["titulo"] . "',tiene una 
                valoración de " . $fila["valoracion"] .  " puntos.</li>";
            }   
            $cadena .= "</ul>"; 
        }


        $cadena .= "<p>Los directores con mayor reconocimiento son:</p>";
        //Directores con mayor reconocimiento 
        if($directoresMayorReconocimiento->num_rows > 0){
            $directoresMayorReconocimiento->data_seek(0); 
            $cadena .= "<ul>";
            while($fila = $directoresMayorReconocimiento->fetch_assoc()) {
                $cadena .= "<li>El director con id '" . $fila["id_director"] . "' y con nombre '" . $fila["nombre_director"] . "',tiene un 
                reconocimiento de " . $fila["reconocimiento"] .  " puntos.</li>";
            }  
            $cadena .= "</ul>";  
        }


        $cadena .= "<p>Los directores con menor reconocimiento son:</p>";
        //Directores con menor reconocimiento 
        if($directoresMenorReconocimiento->num_rows > 0){
            $directoresMenorReconocimiento->data_seek(0); 
            $cadena .= "<ul>";
            while($fila = $directoresMenorReconocimiento->fetch_assoc()) {
                $cadena .= "<li>El director con id '" . $fila["id_director"] . "' y con nombre '" . $fila["nombre_director"] . "',tiene un 
                reconocimiento de " . $fila["reconocimiento"] .  " puntos.</li>";
            }   
            $cadena .= "</ul>"; 
        }


        // cierre de la conexion
        $db->close();  

        return $cadena;     
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
        if($this->tipoInterfaz == "reparto"){
            $cadena .= "<form action='#' method='post' name='formulario' id='formulario'>";
            $cadena .= "<p>Id de la película: <input type='text' name='idPelicula' title='idPelicula' /> </p>"; 
            $cadena .= "<input type='submit' value='Obtener reparto' name='opReparto' />";       
            $cadena .= "</form>";      
        }
        else if($this->tipoInterfaz == "informe"){
            $cadena .= "<form action='#' method='post' name='formulario' id='formulario'>"; 
            $cadena .= $this->generarInformes();
            $cadena .= "</form>"; 
        }
        else if($this->tipoInterfaz == "actor"){
            $cadena .= "<form action='#' method='post' name='formulario' id='formulario'>";
            $cadena .= "<p>Id del actor: <input type='text' name='idActor' title='idActor' /> </p>"; 
            $cadena .= "<input type='submit' value='Buscar' name='opActor' />";       
            $cadena .= "</form>";   
        }
        else if($this->tipoInterfaz == "director"){
            $cadena .= "<form action='#' method='post' name='formulario' id='formulario'>";
            $cadena .= "<p>Id del director: <input type='text' name='idDirector' title='idDirector' /> </p>"; 
            $cadena .= "<input type='submit' value='Buscar' name='opDirector' />";       
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
        if(isset($_POST["crearTablas"])) $this->crearTablas();
        if(isset($_POST["insertar"])) $this->insertarDatos();
        if(isset($_POST["mostrar"])) $this->mostrarTodo();
        if(isset($_POST["reparto"])) $this->changeInterfaz("reparto");
        if(isset($_POST["informe"])) $this->changeInterfaz("informe");
        if(isset($_POST["actor"])) $this->changeInterfaz("actor");
        if(isset($_POST["director"])) $this->changeInterfaz("director");
        if(isset($_POST["opReparto"])) $this->buscarReparto();
        if(isset($_POST["opActor"])) $this->buscarPeliculasPorActor();
        if(isset($_POST["opDirector"])) $this->buscarPeliculasPorDirector();
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
        <li><input type = 'submit' class='button' value='Crear las tablas'  name='crearTablas' /> </li>
        <li><input type = 'submit' class='button' value='Insertar datos en las tablas'  name='insertar' /> </li>
        <li><input type = 'submit' class='button' value='Mostrar todos los datos disponibles'  name='mostrar' /> </li>
        <li><input type = 'submit' class='button' value='Obtener reparto por id de película'  name='reparto' /> </li>
        <li><input type = 'submit' class='button' value='Buscar películas por id de actor'  name='actor' /> </li>
        <li><input type = 'submit' class='button' value='Buscar películas por id de director'  name='director' /> </li>
        <li><input type = 'submit' class='button' value='Generar informe'  name='informe' /> </li>
    </ul>
</nav>
</form>".$_SESSION["baseDatos"]->getInterfazUsuarioActual();
?>
</body>
</html>