<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // ajusta la ruta según tu carpeta
    exit;
}
?>
<?php
include('conectar.php');
mysqli_set_charset($conexion,"utf8");

$id_cliente = $_POST['id_cliente'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula_identidad = $_POST['cedula_identidad'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];
$ciudad = $_POST['ciudad'];
$departamento = $_POST['departamento'];
$estado = $_POST['estado'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];

$consulta = "SELECT * FROM clientes WHERE id_cliente = '$id_cliente'";
$busqueda = mysqli_query($conexion, $consulta);
$nr = mysqli_num_rows($busqueda);

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
    <link rel="stylesheet" href="internet.css">
</head>
<body>';

if ($nr != 0) {
    // Solo alert + redirección automática
    echo "<script type='text/javascript'>
            alert('⚠️ El ID del cliente ya existe, intenta con otro.');
            window.location.href='registrar_clientes.php';
          </script>";
    mysqli_close($conexion);
    exit();
}

$agregar = "INSERT INTO clientes 
(id_cliente, nombre, apellido, cedula_identidad, telefono, email, direccion, ciudad, departamento, estado, latitud, longitud) 
VALUES ($id_cliente, '$nombre', '$apellido', $cedula_identidad, $telefono, '$email', '$direccion', '$ciudad', '$departamento', '$estado', $latitud, $longitud)";
mysqli_query($conexion, $agregar);

if (mysqli_affected_rows($conexion) != 0) {
    echo "<script type='text/javascript'>
            alert('✅ Cliente registrado correctamente');
            window.location.href='registrar_clientes.php';
          </script>";
} else {
    echo "<script type='text/javascript'>
            alert('❌ No se pudo registrar el cliente, intenta de nuevo');
            window.location.href='registrar_clientes.php';
          </script>";
}

mysqli_close($conexion);
echo '</body></html>';
?>
<script>
  function cerrarSesion() {
  localStorage.removeItem("logueado");
  window.location.href="../menu/login.php";
}
</script>