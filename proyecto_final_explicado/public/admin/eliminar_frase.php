<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../home.php?error=7');
    exit();
}

require_once "../../app/config/conexion.php";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idFrase'])){
    $idEliminar = (int)$_POST['idFrase'];
    $stmt = $mysqli->prepare("DELETE FROM frases WHERE id = ?");
    $stmt->bind_param("i", $idEliminar);
    $stmt->execute();
    $stmt->close();
}
else{
    header("Location: crud_frases.php");
    exit();
}



?>