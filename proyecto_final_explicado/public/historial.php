<?php
declare(strict_types=1);
session_start();

// Valido si hay una sesion
if (!isset($_SESSION['usuario'])){
    // Si no hay sesion, valido si esta la cookie de recordarme
    if(isset($_COOKIE['usuario'])){
        // Se le devuelve la sesion con la cookie guardada de recordar
        $_SESSION['usuario'] = $_COOKIE['usuario'];
    }
    else{
        // Si no hay sesión ni cookies lo manda al login
        header('Location: login.php');
        exit();
    }
}   

require_once "../app/config/conexion.php";

$stmt = $mysqli->prepare(
            "SELECT h.fecha_apertura, f.mensaje
            FROM historial_galletas h 
            JOIN usuarios u ON h.usuario_id = u.id
            JOIN frases f ON h.mensaje_id = f.id
            WHERE h.usuario_id = ?
            ORDER BY h.fecha_apertura ASC");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$historial = $stmt->get_result();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <link rel="stylesheet" href="./assets/css/global.css">
    <link rel="stylesheet" href="./assets/css/galleta.css">
</head>
<body>
    <header>
        <a href="./home.php">Inicio</a>
        <a href="./historial.php">Historial</a>
        <a href="./logout.php">Cerrar sesión</a>
        <?php
        if($_SESSION['usuario'] === 'admin'){
            echo "<a href='./admin/admin.php'>Admin</a>";
        }
        ?>
    </header>
    <main>
        <h1>Historial de frases obtenidas</h1>
        <?php
        if ($historial->num_rows > 0){
            while($array = $historial->fetch_assoc()){
                echo "<p>" . htmlspecialchars($array['fecha_apertura']) . " - " . htmlspecialchars($array['mensaje']) . "</p>";
            }
        }
        else{
            echo "<p>No se registra actividad de su ususario todavia</p>";
        }
        ?>
    </main>
</body>
</html>