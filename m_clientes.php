<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include("../menu/conectar.php");

$cliente = null;
$mensaje = "";

if (isset($_POST['buscar'])) {
    $id = $_POST['id_cliente'];
    $check_sql = "SELECT * FROM clientes WHERE id_cliente='$id'";
    $resultado = mysqli_query($conexion, $check_sql);

    if (mysqli_num_rows($resultado) > 0) {
        $cliente = mysqli_fetch_assoc($resultado);
    } else {
        $mensaje = "⚠️ No existe un cliente con ese ID.";
    }
}

if (isset($_POST['guardar'])) {
    $id = $_POST['id_cliente'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];

    $sql = "UPDATE clientes SET telefono='$telefono', email='$email', direccion='$direccion', ciudad='$ciudad' WHERE id_cliente='$id'";
    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('✅ Datos modificados correctamente.');</script>";
        // Recargar el cliente actualizado
        $check_sql = "SELECT * FROM clientes WHERE id_cliente='$id'";
        $resultado = mysqli_query($conexion, $check_sql);
        $cliente = mysqli_fetch_assoc($resultado);
    } else {
        echo "<script>alert('❌ Error al modificar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Modificar Cliente</title>
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
input[type="email"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 2px solid var(--medium-brown);
  border-radius: 8px;
  font-size: 16px;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

input[type="number"]:focus,
input[type="text"]:focus,
input[type="email"]:focus {
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

table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

th, td {
  padding: 10px;
  text-align: left;
}

@keyframes fadeIn {
  0% {opacity: 0; transform: translateY(-20px);}
  100% {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<h2>Modificar Cliente</h2>
<?php if($mensaje) echo "<div class='mensaje'>$mensaje</div>"; ?>

<?php if (!$cliente): ?>
<!-- Solo mostrar búsqueda si no hay cliente cargado -->
<form method="POST">
    <label>ID Cliente:</label>
    <input type="number" name="id_cliente" required>
    <button type="submit" name="buscar">Buscar</button>
</form>
<?php endif; ?>

<?php if ($cliente): ?>
<!-- Formulario de modificación -->
<form method="POST">
    <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">

    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?php echo $cliente['telefono']; ?>">

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $cliente['email']; ?>">

    <label>Dirección:</label>
    <input type="text" name="direccion" value="<?php echo $cliente['direccion']; ?>">

    <label>Ciudad:</label>
    <input type="text" name="ciudad" value="<?php echo $cliente['ciudad']; ?>">

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