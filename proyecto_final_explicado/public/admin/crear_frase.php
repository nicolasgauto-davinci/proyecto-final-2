<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../home.php?error=7');   //Agregar mensaje de error
    exit();
}

require_once "../../app/config/conexion.php";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevaFrase'])){
    $nuevaFrase = $_POST['nuevaFrase'];
    $stmt = $mysqli->prepare("INSERT INTO frases(mensaje) VALUES ('?')");
    $stmt->bind_param("s", $nuevaFrase);
    $stmt->execute();
    $stmt->close();
    header("Location: crud_frases.php");
    exit();
}
else{
    header("Location: crud_frases.php");
    exit();
}

?>