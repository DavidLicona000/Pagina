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
$id_tecnico = $_POST['id_tecnico'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$email = $_POST['email'] ?? '';
$especialidad = $_POST['especialidad'] ?? '';
$zona_cobertura = $_POST['zona_cobertura'] ?? '';

// Validar campos obligatorios
if (empty($id_tecnico) || empty($nombre)) {
    echo "<script>
            alert('❌ Faltan datos obligatorios');
            window.location.href='registrar_tecnicos.php';
          </script>";
    exit();
}

// Verificar que el ID de técnico no exista
$consulta = "SELECT * FROM tecnicos WHERE id_tecnico = '$id_tecnico'";
$busqueda = mysqli_query($conexion, $consulta);

if(mysqli_num_rows($busqueda) != 0){
    echo "<script>
            alert('⚠️ El ID del técnico ya existe, intenta con otro');
            window.location.href='registrar_tecnicos.php';
          </script>";
    mysqli_close($conexion);
    exit();
}

// Insertar en base de datos
$agregar = "INSERT INTO tecnicos (id_tecnico, nombre, apellido, telefono, email, especialidad, zona_cobertura) 
VALUES ('$id_tecnico', '$nombre', '$apellido', '$telefono', '$email', '$especialidad', '$zona_cobertura')";

mysqli_query($conexion, $agregar);

if (mysqli_affected_rows($conexion) != 0) {
    echo "<script>
            alert('✅ Técnico registrado correctamente');
            window.location.href='registrar_tecnicos.php';
          </script>";
} else {
    echo "<script>
            alert('❌ No se pudo registrar el técnico, intenta de nuevo');
            window.location.href='registrar_tecnicos.php';
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