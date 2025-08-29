<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include("../menu/conectar.php");

$beneficio_datos = null;
$alert = "";

// Detectar acción
$accion = $_POST['accion'] ?? '';

if ($accion === 'buscar') {
    $id_beneficio = $_POST['id_beneficio'] ?? 0;

    if ($id_beneficio > 0) {
        $check_sql = "SELECT * FROM beneficios WHERE id_beneficio = ?";
        $stmt = $conexion->prepare($check_sql);

        if(!$stmt){
            die("Error en prepare: " . $conexion->error);
        }

        $stmt->bind_param("i", $id_beneficio);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $beneficio_datos = $result->fetch_assoc();
        } else {
            $alert = "⚠️ No existe un beneficio con ID $id_beneficio.";
        }

        $stmt->close();
    } else {
        $alert = "⚠️ ID de beneficio inválido.";
    }
}

if ($accion === 'modificar') {
    $id_beneficio = $_POST['id_beneficio'] ?? 0;
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $condiciones = trim($_POST['condiciones'] ?? '');

    if ($id_beneficio > 0 && !empty($nombre) && !empty($descripcion) && !empty($condiciones)) {
        $update_sql = "UPDATE beneficios SET nombre = ?, descripcion = ?, condiciones = ? WHERE id_beneficio = ?";
        $stmt = $conexion->prepare($update_sql);

        if(!$stmt){
            die("Error en prepare: " . $conexion->error);
        }

        $stmt->bind_param("sssi", $nombre, $descripcion, $condiciones, $id_beneficio);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Datos modificados con éxito.');</script>";
            // recargar beneficio actualizado
            $beneficio_datos = [
                'id_beneficio' => $id_beneficio,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'condiciones' => $condiciones
            ];
        } else {
            echo "<script>alert('❌ Error al modificar beneficio: " . $conexion->error . "');</script>";
        }

        $stmt->close();
    } else {
        $alert = "⚠️ Datos incompletos para modificar.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Modificar Beneficio</title>
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
}

input[type="number"],
input[type="text"],
textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 2px solid var(--medium-brown);
  border-radius: 8px;
  font-size: 16px;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

textarea {
  resize: vertical;
  min-height: 60px;
}

input[type="number"]:focus,
input[type="text"]:focus,
textarea:focus {
  border-color: var(--accent-orange);
  box-shadow: 0 0 8px rgba(255,127,80,0.5);
  outline: none;
}

input[type="submit"], button {
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

input[type="submit"]:hover, button:hover {
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
<script>
<?php if($alert != ""): ?>
    alert("<?php echo $alert; ?>");
<?php endif; ?>
</script>
</head>
<body>

<h2>Modificar Beneficio</h2>

<?php if (!$beneficio_datos): ?>
<!-- Formulario de búsqueda -->
<form method="POST" action="">
    <input type="hidden" name="accion" value="buscar">
    <label>ID del beneficio:</label>
    <input type="number" name="id_beneficio" required>
    <input type="submit" value="Buscar Beneficio">
</form>
<?php endif; ?>

<?php if ($beneficio_datos): ?>
<!-- Formulario de modificación -->
<form method="POST" action="">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($beneficio_datos['nombre']); ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required><?php echo htmlspecialchars($beneficio_datos['descripcion']); ?></textarea>

    <label>Condiciones:</label>
    <textarea name="condiciones" required><?php echo htmlspecialchars($beneficio_datos['condiciones']); ?></textarea>

    <input type="hidden" name="accion" value="modificar">
    <input type="hidden" name="id_beneficio" value="<?php echo $beneficio_datos['id_beneficio']; ?>">

    <input type="submit" value="Guardar Cambios">
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