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
$id_pago = $_POST['id_pago'] ?? '';
$id_factura = $_POST['id_factura'] ?? '';
$fecha_pago = $_POST['fecha_pago'] ?? '';
$monto = $_POST['monto'] ?? '';
$metodo_pago = $_POST['metodo_pago'] ?? '';
$referencia = $_POST['referencia'] ?? '';

// Validar campos obligatorios
if (empty($id_pago) || empty($id_factura) || empty($fecha_pago) || empty($monto)) {
    echo "<script>
            alert('❌ Faltan datos obligatorios');
            window.location.href='registrar_pagos.php';
          </script>";
    exit();
}

// Verificar que el ID de pago no exista
$consulta = "SELECT * FROM pagos WHERE id_pago = '$id_pago'";
$busqueda = mysqli_query($conexion, $consulta);

if(mysqli_num_rows($busqueda) != 0){
    echo "<script>
            alert('⚠️ El ID de pago ya existe, intenta con otro');
            window.location.href='registrar_pagos.php';
          </script>";
    mysqli_close($conexion);
    exit();
}

// Insertar en base de datos
$agregar = "INSERT INTO pagos (id_pago, id_factura, fecha_pago, monto, metodo_pago, referencia) 
VALUES ('$id_pago', '$id_factura', '$fecha_pago', '$monto', '$metodo_pago', '$referencia')";

mysqli_query($conexion, $agregar);

if (mysqli_affected_rows($conexion) != 0) {
    echo "<script>
            alert('✅ Pago registrado correctamente');
            window.location.href='registrar_pagos.php';
          </script>";
} else {
    echo "<script>
            alert('❌ No se pudo registrar el pago, intenta de nuevo');
            window.location.href='registrar_pagos.php';
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