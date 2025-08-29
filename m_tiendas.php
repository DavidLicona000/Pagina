<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include("../menu/conectar.php");

$tienda = null;
$mensaje = "";

if (isset($_POST['buscar'])) {
    $id = $_POST['id_tienda'];
    $check_sql = "SELECT * FROM tiendas WHERE id_tienda = '$id'";
    $resultado = mysqli_query($conexion, $check_sql);

    if (mysqli_num_rows($resultado) > 0) {
        $tienda = mysqli_fetch_assoc($resultado);
    } else {
        $mensaje = "No existe una tienda con ese ID.";
    }
}

if (isset($_POST['guardar'])) {
    $id = $_POST['id_tienda'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $horario = $_POST['horario'];

    $sql = "UPDATE tiendas 
            SET direccion='$direccion', telefono='$telefono', horario='$horario' 
            WHERE id_tienda='$id'";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('✅ Datos de la tienda modificados correctamente.');</script>";
    } else {
        echo "<script>alert('❌ Error al modificar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Modificar Tienda</title>
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
  max-width: 500px;
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
</head>
<body>

<h2>Modificar Tienda</h2>
<?php if($mensaje != ""): ?><div class="mensaje"><?php echo $mensaje; ?></div><?php endif; ?>

<?php if (!$tienda): ?>
<!-- Formulario de búsqueda -->
<form method="POST">
    <label>ID Tienda:</label>
    <input type="number" name="id_tienda" required>
    <button type="submit" name="buscar">Buscar Tienda</button>
</form>
<?php endif; ?>

<?php if ($tienda): ?>
<!-- Formulario de modificación -->
<h3>Modificando tienda #<?php echo $tienda['id_tienda']; ?></h3>
<form method="POST">
    <input type="hidden" name="id_tienda" value="<?php echo $tienda['id_tienda']; ?>">
    <table border="1">
        <tr><th>Campo</th><th>Información</th></tr>
        <tr><td>Dirección</td>
            <td><input type="text" name="direccion" value="<?php echo htmlspecialchars($tienda['direccion']); ?>"></td>
        </tr>
        <tr><td>Teléfono</td>
            <td><input type="text" name="telefono" value="<?php echo htmlspecialchars($tienda['telefono']); ?>"></td>
        </tr>
        <tr><td>Horario</td>
            <td><input type="text" name="horario" value="<?php echo htmlspecialchars($tienda['horario']); ?>"></td>
        </tr>
    </table>
    <br>
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
