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
$id_ticket = $_POST['id_ticket'] ?? '';
$id_cliente = $_POST['id_cliente'] ?? '';
$id_tecnico = $_POST['id_tecnico'] ?? '';
$fecha_creacion = $_POST['fecha_creacion'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$prioridad = $_POST['prioridad'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$estado = $_POST['estado'] ?? '';

// Validar campos obligatorios
if (empty($id_ticket) || empty($id_cliente)) {
    echo "<script>
            alert('❌ Faltan datos obligatorios');
            window.location.href='registrar_soporte.php';
          </script>";
    exit();
}

// Verificar que el ID de ticket no exista
$consulta = "SELECT * FROM soporte WHERE id_ticket = '$id_ticket'";
$busqueda = mysqli_query($conexion, $consulta);

if(mysqli_num_rows($busqueda) != 0){
    echo "<script>
            alert('⚠️ El ID del ticket ya existe, intenta con otro');
            window.location.href='registrar_soporte.php';
          </script>";
    mysqli_close($conexion);
    exit();
}

// Insertar en base de datos
$agregar = "INSERT INTO soporte (id_ticket, id_cliente, id_tecnico, fecha_creacion, categoria, prioridad, descripcion, estado) 
VALUES ('$id_ticket', '$id_cliente', '$id_tecnico', '$fecha_creacion', '$categoria', '$prioridad', '$descripcion', '$estado')";

mysqli_query($conexion, $agregar);

if (mysqli_affected_rows($conexion) != 0) {
    echo "<script>
            alert('✅ Ticket de soporte registrado correctamente');
            window.location.href='registrar_soporte.php';
          </script>";
} else {
    echo "<script>
            alert('❌ No se pudo registrar el ticket, intenta de nuevo');
            window.location.href='registrar_soporte.php';
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