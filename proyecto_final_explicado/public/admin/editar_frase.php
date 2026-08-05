<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../home.php?error=7');
    exit();
}

require_once "../../app/config/conexion.php";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idFrase'])){
    $idEditar = (int)$_POST['idFrase'];
    $fraseActualizada = //Necesito que esta variable guarde lo que sea que se ponga en el input de frase actualizada
    $stmt = $mysqli->prepare("UPDATE frases SET mensaje = ? WHERE id = ?");
    $stmt->bind_param("si", $fraseActualizada, $idEditar);
    $stmt->execute();
    $stmt->close();
}
else{
    header("Location: crud_frases.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Frases</title>
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>
    <header>
        <a href="../home.php">Inicio</a>
        <a href="../historial.php">Historial</a>
        <a href="../logout.php">Cerrar sesión</a>
        <a href="./admin.php">Admin</a>
    </header>
    <main>
        <h1>Editar frase</h1>
        <form action="./crud_frases.php">
            <label>Frase a actualizar:</label>
            <input type="text" name="fraseVieja"><br>
            <label>Frase actualizada:</label>
            <input type="text" name="fraseActualizada"><br>
            <button type="submit">Enviar</button>
        </form>
    </main>
</body>
</html>

<?php 

?>