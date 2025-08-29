<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include("../menu/conectar.php");

$zona_datos = null;
$alert = "";

// Detectar acción
$accion = $_POST['accion'] ?? '';

if ($accion === 'buscar') {
    $id_zona = $_POST['id_zona'] ?? 0;

    if ($id_zona > 0) {
        $check_sql = "SELECT * FROM zonascobertura WHERE id_zona = ?";
        $stmt = $conexion->prepare($check_sql);

        if(!$stmt) die("Error en prepare: " . $conexion->error);

        $stmt->bind_param("i", $id_zona);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $zona_datos = $result->fetch_assoc();
        } else {
            $alert = "La zona con ID $id_zona no existe.";
        }

        $stmt->close();
    } else {
        $alert = "ID de zona inválido.";
    }
}

if ($accion === 'modificar') {
    $id_zona = $_POST['id_zona'] ?? 0;
    $nombre_zona = trim($_POST['nombre_zona'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $departamento = trim($_POST['departamento'] ?? '');
    $radio_km = $_POST['radio_km'] ?? '';

    if ($id_zona > 0 && !empty($nombre_zona) && !empty($ciudad) && !empty($departamento) && $radio_km !== '') {
        $update_sql = "UPDATE zonascobertura SET nombre_zona = ?, ciudad = ?, departamento = ?, radio_km = ? WHERE id_zona = ?";
        $stmt = $conexion->prepare($update_sql);

        if(!$stmt) die("Error en prepare: " . $conexion->error);

        $stmt->bind_param("sssdi", $nombre_zona, $ciudad, $departamento, $radio_km, $id_zona);

        if ($stmt->execute()) {
            $alert = "Datos modificados con éxito.";
            $zona_datos['nombre_zona'] = $nombre_zona;
            $zona_datos['ciudad'] = $ciudad;
            $zona_datos['departamento'] = $departamento;
            $zona_datos['radio_km'] = $radio_km;
            $zona_datos['id_zona'] = $id_zona;
        } else {
            $alert = "Error al modificar zona: " . $conexion->error;
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
<title>Modificar Zona de Cobertura</title>
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
input[type="text"] {
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

button, input[type="submit"] {
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

button:hover, input[type="submit"]:hover {
  background-color: var(--dark-brown);
  transform: scale(1.05);
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 10px;
  text-align: left;
}

th {
  background-color: var(--medium-brown);
  color: var(--accent-gold);
}

tr:nth-child(even) {
  background-color: rgba(243,243,243,0.8);
}
</style>
<script>
<?php if($alert != ""): ?>
  alert("<?php echo $alert; ?>");
<?php endif; ?>
</script>
</head>
<body>

<h2>Modificar Zona de Cobertura</h2>

<?php if($alert != ""): ?>
    <div class="mensaje"><?php echo $alert; ?></div>
<?php endif; ?>

<?php if (!$zona_datos): ?>
<!-- Formulario de búsqueda -->
<form method="POST">
    <input type="hidden" name="accion" value="buscar">
    <label>ID de la zona:</label>
    <input type="number" name="id_zona" required>
    <button type="submit">Buscar Zona</button>
</form>
<?php endif; ?>

<?php if ($zona_datos && isset($zona_datos['nombre_zona'])): ?>
<!-- Formulario de modificación -->
<h3>Modificando zona #<?php echo $zona_datos['id_zona']; ?></h3>
<form method="POST">
    <table border="1">
        <tr>
            <th>Nombre de Zona</th>
            <th>Ciudad</th>
            <th>Departamento</th>
            <th>Radio (km)</th>
        </tr>
        <tr>
            <td><input type="text" name="nombre_zona" value="<?php echo htmlspecialchars($zona_datos['nombre_zona']); ?>"></td>
            <td><input type="text" name="ciudad" value="<?php echo htmlspecialchars($zona_datos['ciudad']); ?>"></td>
            <td><input type="text" name="departamento" value="<?php echo htmlspecialchars($zona_datos['departamento']); ?>"></td>
            <td><input type="number" step="0.01" name="radio_km" value="<?php echo $zona_datos['radio_km']; ?>"></td>
        </tr>
    </table>
    <input type="hidden" name="accion" value="modificar">
    <input type="hidden" name="id_zona" value="<?php echo $zona_datos['id_zona']; ?>">
    <br>
    <button type="submit">Guardar Cambios</button>
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