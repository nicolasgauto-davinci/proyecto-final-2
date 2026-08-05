<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: ../home.php?error=7');
    exit();
}

//Generacion del archivo para registrar los eventos
$archivo = '../../EventosCriticos.txt';
$modo = "a";

require_once "../../app/config/conexion.php";

//Creo un id con valor 0 (osea no existe) y trato de sacar el id de post o get
$id = 0;

if (isset($_POST['id_frase'])) {
    $id = (int)$_POST['id_frase'];
}
elseif (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
}
elseif (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
}
//Si sigue siendo 0, devuelve al crud
if($id === 0){
    header("Location: crud_frases.php");
    exit();
}

$stmt = $mysqli->prepare("SELECT mensaje FROM frases WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

$fraseSeleccionada = "";
//Me aseguro de que el id ingresado exista, sino lo devuelvo al crud
if($resultado->num_rows > 0){
    $array = $resultado->fetch_assoc();
    $fraseSeleccionada = $array['mensaje'];
}
else{
    header("Location: crud_frases.php");
    exit();
}

//Esto no se ejecuta a menos que venga por el link, tenga el idFrase y la nueva frase desde el form de esta pagina
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idFrase'], $_POST['fraseActualizada'])){

    $idEditar = (int)$_POST['idFrase'];
    $fraseActualizada = filter_input(INPUT_POST, 'fraseActualizada');

    $regexFraseActualizada = "/^[a-zA-Z0-9.!?,]{15,}$/";
    if(!preg_match($regexFraseActualizada, $fraseActualizada)){
        header("Location: editar_frase.php?error=13");
        exit();
    }

    $stmt = $mysqli->prepare("UPDATE frases SET mensaje = ? WHERE id = ?");
    $stmt->bind_param("si", $fraseActualizada, $idEditar);
    //Este stmt no se ejecuta hasta que tenga el fraseActualizada, es decir hasta q complete el form dentro de la misma pagina
    if($stmt->execute()){
        $stmt->close();

        //Si se ejecuta, guardo el suceso en eventos criticos
        $fechaActual = date('d-m-Y H:i:s');
        $manejador = fopen($archivo, $modo);

        if($manejador){
            $contenido= "Se edito la frase con id #" . htmlspecialchars($idEditar) . " de '" . htmlspecialchars($fraseSeleccionada) . "' a '" . htmlspecialchars($fraseActualizada) . "'. Fecha del suceso: " . $fechaActual . "\n";
            fwrite($manejador, $contenido);
            fclose($manejador);
        }

        header("Location: crud_frases.php");
        exit();
    }
    $stmt->close();
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
        <?php 
        if (isset($_GET['error'])) {
            if($_GET['error'] === '13'){
                echo "<p style='color:red;'>La frase es inválida</p>";
            }
        }
        ?>
        <a href="./crud_frases.php">Volver al CRUD</a>
    </main>
</body>
</html>

<?php 

?>