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
$id_plan = $_POST['id_plan'] ?? '';
$nombre_plan = $_POST['nombre_plan'] ?? '';
$velocidad_mbps = $_POST['velocidad_mbps'] ?? '';
$canales_tv = $_POST['canales_tv'] ?? '';
$incluye_telefonia = $_POST['incluye_telefonia'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$precio_mensual = $_POST['precio_mensual'] ?? '';
$estado = $_POST['estado'] ?? '';

// Validar campos obligatorios
if (empty($id_plan) || empty($nombre_plan)) {
    echo "<script>
            alert('❌ Faltan datos obligatorios');
            window.location.href='registrar_planes.html';
          </script>";
    exit();
}

// Verificar que el ID de plan no exista
$consulta = "SELECT * FROM planes WHERE id_plan = '$id_plan'";
$busqueda = mysqli_query($conexion, $consulta);

if(mysqli_num_rows($busqueda) != 0){
    echo "<script>
            alert('⚠️ El ID del plan ya existe, intenta con otro');
            window.location.href='registrar_planes.php';
          </script>";
    mysqli_close($conexion);
    exit();
}

// Insertar en base de datos
$agregar = "INSERT INTO planes (id_plan, nombre_plan, velocidad_mbps, canales_tv, incluye_telefonia, descripcion, precio_mensual, estado) 
VALUES ('$id_plan', '$nombre_plan', '$velocidad_mbps', '$canales_tv', '$incluye_telefonia', '$descripcion', '$precio_mensual', '$estado')";

mysqli_query($conexion, $agregar);

if (mysqli_affected_rows($conexion) != 0) {
    echo "<script>
            alert('✅ Plan registrado correctamente');
            window.location.href='registrar_planes.php';
          </script>";
} else {
    echo "<script>
            alert('❌ No se pudo registrar el plan, intenta de nuevo');
            window.location.href='registrar_planes.php';
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