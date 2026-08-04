<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../home.php?error=7');   //Agregar mensaje de error
    exit();
}

require_once "../../app/config/conexion.php";

$stmt = $mysqli->prepare(DELETE FROM frases WHERE id = ?);
$stmt->bind_param(i, $_GET);  //Tengo que hacer que getee el id del link para eliminarlo
$stmt->execute();
$stmt->close();

//Tambien hay que agregar estas acciones al EventosCriticos.txt

header("Location: crud_frases.php");

?>
