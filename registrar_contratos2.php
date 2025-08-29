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

$id_contrato   = $_POST['id_contrato'];
$id_cliente    = $_POST['id_cliente'];
$id_plan       = $_POST['id_plan'];
$fecha_inicio  = $_POST['fecha_inicio'];
$fecha_fin     = $_POST['fecha_fin'];
$tipo_contrato = $_POST['tipo_contrato'];
$estado        = $_POST['estado'];

$consulta = "SELECT * FROM contratos WHERE id_contrato = '$id_contrato'";
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
    // Alert y redirección si el contrato ya existe
    echo "<script type='text/javascript'>
            alert('⚠️ El código de contrato ya existe, intenta con otro.');
            window.location.href='registrar_contratos.php';
          </script>";
    mysqli_close($conexion);
    exit();
}

$agregar = "INSERT INTO contratos (id_contrato, id_cliente, id_plan, fecha_inicio, fecha_fin, tipo_contrato, estado) 
VALUES ('$id_contrato', '$id_cliente', '$id_plan', '$fecha_inicio', '$fecha_fin', '$tipo_contrato', '$estado')";
mysqli_query($conexion, $agregar);

if (mysqli_affected_rows($conexion) != 0) {
    // Alert y redirección en caso de éxito
    echo "<script type='text/javascript'>
            alert('✅ Contrato registrado correctamente');
            window.location.href='registrar_contratos.php';
          </script>";
} else {
    // Alert y redirección en caso de fallo
    echo "<script type='text/javascript'>
            alert('❌ No se pudo registrar el contrato, intenta de nuevo');
            window.location.href='registrar_contratos.php';
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