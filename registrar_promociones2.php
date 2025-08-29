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
$id_promocion = $_POST['id_promocion'] ?? '';
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$descuento_porcentaje = $_POST['descuento_porcentaje'] ?? '';
$aplicable_a = $_POST['aplicable_a'] ?? '';

// Validar campos obligatorios
if (empty($id_promocion) || empty($titulo)) {
    echo "<script>
            alert('❌ Faltan datos obligatorios');
            window.location.href='registrar_promociones.php';
          </script>";
    exit();
}

// Verificar que el ID de promoción no exista
$consulta = "SELECT * FROM promociones WHERE id_promocion = '$id_promocion'";
$busqueda = mysqli_query($conexion, $consulta);

if(mysqli_num_rows($busqueda) != 0){
    echo "<script>
            alert('⚠️ El ID de la promoción ya existe, intenta con otro');
            window.location.href='registrar_promociones.php';
          </script>";
    mysqli_close($conexion);
    exit();
}

// Insertar en base de datos
$agregar = "INSERT INTO promociones (id_promocion, titulo, descripcion, fecha_inicio, fecha_fin, descuento_porcentaje, aplicable_a) 
VALUES ('$id_promocion', '$titulo', '$descripcion', '$fecha_inicio', '$fecha_fin', '$descuento_porcentaje', '$aplicable_a')";

mysqli_query($conexion, $agregar);

if (mysqli_affected_rows($conexion) != 0) {
    echo "<script>
            alert('✅ Promoción registrada correctamente');
            window.location.href='registrar_promociones.php';
          </script>";
} else {
    echo "<script>
            alert('❌ No se pudo registrar la promoción, intenta de nuevo');
            window.location.href='registrar_promociones.php';
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