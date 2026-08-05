<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../home.php?error=7');
    exit();
}

require_once "../../app/config/conexion.php";

//Esto no se ejecuta a menos que venga por el link y tenga el idFrase
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idFrase'])){
    $idEditar = (int)$_POST['idFrase'];
    $fraseActualizada = $_POST['fraseActualizada'];
    $stmt = $mysqli->prepare("UPDATE frases SET mensaje = ? WHERE id = ?");
    $stmt->bind_param("si", $fraseActualizada, $idEditar);
    //Este stmt no se ejecuta hasta que tenga el fraseActualizada, es decir hasta q complete el form dentro de la misma pagina
    if($stmt->execute()){
        $stmt->close();
        header("Location: crud_frases.php");
        exit();
    }
    $stmt->close();
}

//Si el usuario intenta ingresar por el link, lo redirige al crud
if(!isset($_GET['id'])){
    header("Location: crud_frases.php");
    exit();
}

$id = (int)$_GET['id'];
$fraseSeleccionada = "";

$stmt = $mysqli->prepare("SELECT mensaje FROM frases WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

//Me aseguro de que el id ingresado exista, sino lo devuelvo al crud
if($resultado->num_rows > 0){
    $array = $resultado->fetch_assoc();
    $fraseSeleccionada = $array['mensaje'];
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
        <form action="./editar_frase.php" method="POST">
            <label>Frase a actualizar:</label>
            <input type="text" name="fraseSeleccionada" value="<?php echo htmlspecialchars($fraseSeleccionada); ?>" readonly><br>
            <input type="hidden" name="idFrase" value="<?php echo htmlspecialchars((string)$id); ?>">
            <label>Frase actualizada:</label>
            <input type="text" name="fraseActualizada" value="<?php echo htmlspecialchars($fraseSeleccionada); ?>" required><br>
            <button type="submit">Enviar</button>
        </form>
        <a href="./crud_frases.php">Volver al CRUD</a>
    </main>
</body>
</html>

<?php 

?>