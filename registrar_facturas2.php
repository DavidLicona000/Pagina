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

// Recibir datos
$id_factura = $_POST['id_factura'] ?? '';
$id_contrato = $_POST['id_contrato'] ?? '';
$fecha_emision = $_POST['fecha_emision'] ?? '';
$fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
$subtotal = floatval($_POST['subtotal'] ?? 0);
$estado = $_POST['estado'] ?? '';

// Calcular impuestos y total automáticamente
$impuestos = $subtotal * 0.15; // 15%
$total = $subtotal + $impuestos;

// Validar campos obligatorios
if (empty($id_factura) || empty($id_contrato) || empty($fecha_emision) || empty($fecha_vencimiento)) {
    echo "<script>
            alert('❌ Faltan datos obligatorios');
            window.location.href='registrar_facturas.php';
          </script>";
    exit();
}

// Verificar que el ID de factura no exista
$consulta = "SELECT * FROM facturas WHERE id_factura = '$id_factura'";
$busqueda = mysqli_query($conexion, $consulta);

if(mysqli_num_rows($busqueda) != 0){
    echo "<script>
            alert('⚠️ El ID de factura ya existe, intenta con otro');
            window.location.href='registrar_facturas.php';
          </script>";
    mysqli_close($conexion);
    exit();
}

// Insertar en base de datos
$agregar = "INSERT INTO facturas (id_factura, id_contrato, fecha_emision, fecha_vencimiento, subtotal, impuestos, total, estado) 
VALUES ('$id_factura', '$id_contrato', '$fecha_emision', '$fecha_vencimiento', '$subtotal', '$impuestos', '$total', '$estado')";

mysqli_query($conexion, $agregar);

if (mysqli_affected_rows($conexion) != 0) {
    echo "<script>
            alert('✅ Factura registrada correctamente');
            window.location.href='registrar_facturas.php';
          </script>";
} else {
    echo "<script>
            alert('❌ No se pudo registrar la factura, intenta de nuevo');
            window.location.href='registrar_facturas.php';
          </script>";
}

mysqli_close($conexion);
?>
<script>
  function cerrarSesion() {
  localStorage.removeItem("logueado");
  window.location.href="../menu/login.php";
}
</script>