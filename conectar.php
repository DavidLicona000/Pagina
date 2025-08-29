<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$bdd = "internet";

$conexion = mysqli_connect($servidor, $usuario, $password, $bdd);

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Para soportar tildes y emojis
mysqli_set_charset($conexion, "utf8mb4");
?>
