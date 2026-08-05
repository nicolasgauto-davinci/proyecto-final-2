<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../home.php?error=7');   //Agregar mensaje de error
    exit();
}

//Generacion del archivo para registrar los eventos
$archivo = '../../EventosCriticos.txt';
$modo = "a";

require_once "../../app/config/conexion.php";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevaFrase'])){

    $nuevaFrase = filter_input(INPUT_POST, 'nuevaFrase');

    $regexNuevaFrase = "/^[a-zA-Z0-9.!?,]{15,}$/";
    if(!preg_match($regexNuevaFrase, $nuevaFrase)){
        header("Location: crud_frases.php?error=14");
        exit();
    }

    $stmt = $mysqli->prepare("INSERT INTO frases(mensaje) VALUES (?)");
    $stmt->bind_param("s", $nuevaFrase);
    $stmt->execute();
    $stmt->close();

    $fechaActual = date('d-m-Y H:i:s');
    $manejador = fopen($archivo, $modo);

    if($manejador){
        $contenido= "Se creo la nueva frase '" . htmlspecialchars($nuevaFrase) . "'. Fecha del suceso: " . $fechaActual . "\n";
        fwrite($manejador, $contenido);
        fclose($manejador);
    }

    header("Location: crud_frases.php");
    exit();
}
else{
    header("Location: crud_frases.php");
    exit();
}

?>