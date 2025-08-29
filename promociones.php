<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include("../menu/conectar.php");

$promo_datos = null;
$estado = "";
$alert = "";

// Detectar acción
$accion = $_POST['accion'] ?? '';

if ($accion === 'buscar') {
    $id_promocion = $_POST['id_promocion'] ?? 0;

    if ($id_promocion > 0) {
        $sql = "SELECT id_promocion, fecha_inicio, fecha_fin FROM promociones WHERE id_promocion = ?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) die("Error en prepare: " . $conexion->error);

        $stmt->bind_param("i", $id_promocion);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $promo_datos = $result->fetch_assoc();
            $fecha_actual = date('Y-m-d');
            $estado = ($fecha_actual >= $promo_datos['fecha_inicio'] && $fecha_actual <= $promo_datos['fecha_fin']) ? 'Vigente' : 'Expirada';
        } else {
            $alert = "La promoción con ID $id_promocion no existe.";
        }

        $stmt->close();
    } else {
        $alert = "ID de promoción inválido.";
    }
}

if ($accion === 'modificar') {
    $id_promocion = $_POST['id_promocion'] ?? 0;
    $fecha_fin = $_POST['fecha_fin'] ?? '';

    if ($id_promocion > 0 && !empty($fecha_fin)) {
        $sql = "UPDATE promociones SET fecha_fin = ? WHERE id_promocion = ?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) die("Error en prepare: " . $conexion->error);

        $stmt->bind_param("si", $fecha_fin, $id_promocion);
        if ($stmt->execute()) {
            $alert = "✅ Fecha final de la promoción actualizada.";

            // Cargar nuevamente los datos completos para mostrar en el formulario
            $sql2 = "SELECT id_promocion, fecha_inicio, fecha_fin FROM promociones WHERE id_promocion = ?";
            $stmt2 = $conexion->prepare($sql2);
            $stmt2->bind_param("i", $id_promocion);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if ($result2->num_rows > 0) {
                $promo_datos = $result2->fetch_assoc();
                $fecha_actual = date('Y-m-d');
                $estado = ($fecha_actual >= $promo_datos['fecha_inicio'] && $fecha_actual <= $promo_datos['fecha_fin']) ? 'Vigente' : 'Expirada';
            }
            $stmt2->close();
        } else {
            $alert = "❌ Error al actualizar la promoción: " . $conexion->error;
        }

        $stmt->close();
    } else {
        $alert = "Datos incompletos para modificar.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Verificar Promoción</title>
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
  font-size: 32px;
  color: var(--medium-brown);
  margin-bottom: 20px;
}

form {
  background-color: rgba(255,255,255,0.95);
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  width: 100%;
  max-width: 600px;
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
}

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

input:focus {
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

.estado {
  text-align: center;
  font-size: 20px;
  margin-bottom: 20px;
  color: var(--accent-orange);
}

</style>
<script>
<?php if($alert != ""): ?>
  alert("<?php echo $alert; ?>");
<?php endif; ?>
</script>
</head>
<body>

<h2>Verificar Promoción</h2>

<?php if (!$promo_datos): ?>
<!-- Formulario de búsqueda -->
<form method="POST">
    <input type="hidden" name="accion" value="buscar">
    <label>ID de la Promoción:</label>
    <input type="number" name="id_promocion" required>
    <button type="submit">Buscar Promoción</button>
</form>
<?php endif; ?>

<?php if ($promo_datos && isset($promo_datos['fecha_inicio'])): ?>
<!-- Formulario de modificación -->
<div class="estado">Estado de la Promoción: <strong><?php echo $estado; ?></strong></div>
<h3>Modificando promoción #<?php echo $promo_datos['id_promocion']; ?></h3>
<form method="POST">
    <label>Fecha Final:</label>
    <input type="date" name="fecha_fin" value="<?php echo $promo_datos['fecha_fin']; ?>" required>
    <input type="hidden" name="accion" value="modificar">
    <input type="hidden" name="id_promocion" value="<?php echo $promo_datos['id_promocion']; ?>">
    <button type="submit">Guardar Cambios</button>
</form>
<?php endif; ?>

<form action="menumodificar.php" method="get">
    <button type="submit">Regresar al Menú</button>
</form>
<script>
  function cerrarSesion() {
  localStorage.removeItem("logueado");
  window.location.href="../menu/login.php";
}
</script>
</body>
</html>