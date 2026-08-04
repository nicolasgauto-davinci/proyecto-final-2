<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../home.php?error=7');   //Agregar mensaje de error
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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
        <h1>Panel ADMIN</h1>
        <a href="./eventos_criticos.php">Eventos Criticos</a>
        <a href="./crud_frases.php">CRUD Frases</a>
    </main>
</body>
</html>

