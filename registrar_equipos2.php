<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include('conectar.php');
mysqli_set_charset($conexion,"utf8");

$id_equipo = $_POST['id_equipo'];
$tipo = $_POST['tipo'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$numero_serie = $_POST['numero_serie'];
$estado = $_POST['estado'];

$consulta = "SELECT * FROM equipos WHERE id_equipo = '$id_equipo'";
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
    // Alert y redirección si el equipo ya existe
    echo "<script type='text/javascript'>
            alert('⚠️ El código del equipo ya existe, intenta con otro.');
            window.location.href='registrar_equipos.php';
          </script>";
    mysqli_close($conexion);
    exit();
}

$agregar = "INSERT INTO equipos (id_equipo, tipo, marca, modelo, numero_serie, estado) 
VALUES ('$id_equipo', '$tipo', '$marca', '$modelo', '$numero_serie', '$estado')";
mysqli_query($conexion, $agregar);

if (mysqli_affected_rows($conexion) != 0) {
    // Alert y redirección en caso de éxito
    echo "<script type='text/javascript'>
            alert('✅ Equipo registrado correctamente');
            window.location.href='registrar_equipos.php';
          </script>";
} else {
    // Alert y redirección en caso de fallo
    echo "<script type='text/javascript'>
            alert('❌ No se pudo registrar el equipo, intenta de nuevo');
            window.location.href='registrar_equipos.php';
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