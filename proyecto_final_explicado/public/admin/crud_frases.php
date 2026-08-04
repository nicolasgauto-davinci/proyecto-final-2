<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../home.php?error=7');   //Agregar mensaje de error
    exit();
}

require_once "../../app/config/conexion.php";

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
      <h1>CRUD Frases</h1>
      <ul>
        <?php 
          $stmt = $mysqli->prepare("SELECT id, mensaje FROM frases ORDER BY id ASC");
          $stmt->execute();
          $historial = $stmt->get_result();
          $stmt->close();
          while($array = $historial->fetch_assoc()){
            echo "<li>ID #" . htmlspecialchars($array['id']) . " - " . htmlspecialchars($array['mensaje']) . 
              " <button>Editar</button> <button>Eliminar</button>" . "</li>";
          }
        ?>
      </ul>
    </main>
  </body>
</html>
