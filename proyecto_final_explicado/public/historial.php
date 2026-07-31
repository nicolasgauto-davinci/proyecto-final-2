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
            echo "<a href='../admin.php'>Admin</a>";
        }
        ?>
    </header>
    <main>
        //ESTO ES UN COPYPASTE DE ADMIN, CORREGIR PARA QUE TRAIGA SOLO LOS DEL USUARIO EN CUESTION
        <h1>Historial de frases obtenidas</h1>
        <?php
        $archivo = '../EventosCriticos.txt';
        $modo = "r";

        if(file_exists($archivo)){
            $manejador = fopen($archivo, $modo);
            if($manejador){
                while(!feof($manejador)){
                    $leer = fgets($manejador);
                    if($leer){
                        echo "<p>" . htmlspecialchars($leer) . "</p>";
                    }
                }
                fclose($manejador);
            }
        }
        else{
            echo "<p>Todavia no hay actividad de tu usuario registrada en el sitio</p>";
        }

        ?>
    </main>
</body>
</html>