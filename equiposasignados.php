<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
include '../menu/conectar.php'; // conexión

$asignacion = null; // para almacenar la fila encontrada
$mensaje = "";      // mensaje de alerta

if (isset($_POST['buscar'])) {
    $id_asignacion = $_POST['id_asignacion'];
    $check_sql = "SELECT * FROM equiposasignados WHERE id_asignacion='$id_asignacion'";
    $resultado = mysqli_query($conexion, $check_sql);

    if (mysqli_num_rows($resultado) > 0) {
        $asignacion = mysqli_fetch_assoc($resultado);
    } else {
        $mensaje = "⚠️ No existe una asignación con ese ID.";
    }
}

if (isset($_POST['guardar'])) {
    $id_asignacion   = $_POST['id_asignacion'];
    $id_equipo       = $_POST['id_equipo'];
    $id_contrato     = $_POST['id_contrato'];
    $fecha_devolucion = !empty($_POST['fecha_devolucion']) ? $_POST['fecha_devolucion'] : NULL;

    $sql = "UPDATE equiposasignados 
               SET id_equipo = ?, id_contrato = ?, fecha_devolucion = ?
             WHERE id_asignacion = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "iisi", $id_equipo, $id_contrato, $fecha_devolucion, $id_asignacion);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('✅ Asignación de equipo actualizada correctamente.');</script>";
        // recargar la asignación actualizada
        $check_sql = "SELECT * FROM equiposasignados WHERE id_asignacion='$id_asignacion'";
        $resultado = mysqli_query($conexion, $check_sql);
        $asignacion = mysqli_fetch_assoc($resultado);
    } else {
        echo "<script>alert('❌ Error al actualizar: " . mysqli_error($conexion) . "');</script>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Modificar Asignación de Equipos</title>
<style>
:root {
  --dark-brown: #561C24;
  --medium-brown: #6D2932;
  --beige-dark: #C7B7A3;
  --beige-light: #E8D8C4;
  --accent-orange: #FF7F50;
  --accent-gold: #FFD700;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, var(--beige-light), var(--beige-dark));
  color: var(--dark-brown);
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 50px 20px;
  min-height: 100vh;
  margin: 0;
}

h2 {
  color: var(--medium-brown);
  font-size: 32px;
  margin-bottom: 30px;
  text-align: center;
  animation: fadeIn 1s ease;
}

form {
  background-color: rgba(255,255,255,0.95);
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  width: 100%;
  max-width: 500px;
  margin-bottom: 20px;
  animation: fadeIn 1s ease forwards;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: var(--dark-brown);
}

input[type="text"],
input[type="number"],
input[type="date"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 2px solid var(--medium-brown);
  border-radius: 8px;
  font-size: 16px;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

input[type="text"]:focus,
input[type="number"]:focus,
input[type="date"]:focus {
  border-color: var(--accent-orange);
  box-shadow: 0 0 8px rgba(255,127,80,0.5);
  outline: none;
}

button {
  background-color: var(--medium-brown);
  color: var(--accent-gold);
  padding: 12px 25px;
  font-size: 16px;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease;
}

button:hover {
  background-color: var(--dark-brown);
  transform: scale(1.05);
}

.mensaje {
  text-align: center;
  font-size: 18px;
  margin-bottom: 20px;
  color: var(--accent-orange);
  animation: fadeIn 1s ease;
}

@keyframes fadeIn {
  0% {opacity: 0; transform: translateY(-20px);}
  100% {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<h2>Modificar Asignación de Equipos</h2>
<?php if($mensaje) echo "<div class='mensaje'>$mensaje</div>"; ?>

<?php if (!$asignacion): ?>
<!-- Solo mostrar búsqueda si no hay asignación cargada -->
<form method="POST">
    <label>ID Asignación:</label>
    <input type="number" name="id_asignacion" required>
    <button type="submit" name="buscar">Buscar</button>
</form>
<?php endif; ?>

<?php if ($asignacion): ?>
<!-- Formulario de modificación -->
<form method="POST">
    <input type="hidden" name="id_asignacion" value="<?php echo $asignacion['id_asignacion']; ?>">

    <label>ID Equipo:</label>
    <input type="number" name="id_equipo" value="<?php echo $asignacion['id_equipo']; ?>" required>

    <label>ID Contrato:</label>
    <input type="number" name="id_contrato" value="<?php echo $asignacion['id_contrato']; ?>" required>
    
    <label>Fecha de Asignacion:</label>
    <input type="date" name="fecha_asignacion" value="<?php echo $asignacion['fecha_asignacion']; ?>">

    <label>Fecha de Devolución:</label>
    <input type="date" name="fecha_devolucion" value="<?php echo $asignacion['fecha_devolucion']; ?>">

    <button type="submit" name="guardar">Guardar Cambios</button>
</form>
<?php endif; ?>

<form action="menumodificar.php" method="get">
    <button type="submit">Regresar al Menú</button>
</form>
<script>
  function cerrarSesion() {
  localStorage.removeItem("logueado");
  window.location.href="../menu/inicio.php";
}
</script>
</body>
</html>