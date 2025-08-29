<?php
$host = "127.0.0.1";       // o "localhost"
$usuario = "root";          // tu usuario MySQL
$clave = "";                // tu contraseña MySQL
$base_datos = "internet";   // tu base de datos

// Crear conexión
$conexion = new mysqli($host, $usuario, $clave, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Charset
$conexion->set_charset("utf8mb4");
?>
