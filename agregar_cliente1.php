<?php
session_start();
include("conexion1.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";
$id_cliente_nuevo = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cedula = $_POST['cedula']; // Cedula del cliente
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $departamento = $_POST['departamento'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT); // Contraseña cifrada

    // Estado del cliente (por defecto 'Activo')
    $estado = 'Activo';

    // Fecha de registro (automáticamente con el timestamp)
    $fecha_registro = date("Y-m-d H:i:s");

    // Insertar el nuevo cliente en la base de datos
    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, apellido, cedula_identidad, telefono, email, direccion, ciudad, departamento, clave, estado, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $nombre, $apellido, $cedula, $telefono, $email, $direccion, $ciudad, $departamento, $clave, $estado, $fecha_registro);

    if ($stmt->execute()) {
        // Recuperar el id_cliente generado automáticamente
        $id_cliente_nuevo = $conexion->insert_id;
        $mensaje = "Cliente agregado correctamente. ID Cliente: " . $id_cliente_nuevo;
    } else {
        $mensaje = "Error al agregar cliente: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Cliente</title>
</head>
<body>
<h2>Agregar Nuevo Cliente</h2>
<form method="POST">
    Nombre: <input type="text" name="nombre" required><br>
    Apellido: <input type="text" name="apellido" required><br>
    Cédula: <input type="text" name="cedula" required><br>
    Teléfono: <input type="text" name="telefono"><br>
    Email: <input type="email" name="email"><br>
    Dirección: <input type="text" name="direccion" required><br>
    Ciudad: <input type="text" name="ciudad"><br>
    Departamento: <input type="text" name="departamento"><br>
    Contraseña: <input type="password" name="clave" required><br>
    <button type="submit">Agregar</button>
</form>

<p style="color:green;"><?php echo $mensaje; ?></p>
<a href="menu.php">Volver al menú</a>
</body>
</html>
