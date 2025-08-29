<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include("../menu/conectar.php");

$plan = null;
$mensaje = "";

if (isset($_POST['buscar'])) {
    $id = $_POST['id_plan'];
    $check_sql = "SELECT * FROM planes WHERE id_plan = '$id'";
    $resultado = mysqli_query($conexion, $check_sql);

    if (mysqli_num_rows($resultado) > 0) {
        $plan = mysqli_fetch_assoc($resultado);
    } else {
        $mensaje = "No existe un plan con ese ID.";
    }
}

if (isset($_POST['guardar'])) {
    $id = $_POST['id_plan'];
    $velocidad = $_POST['velocidad_mbps'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio_mensual'];

    $sql = "UPDATE planes 
            SET velocidad_mbps='$velocidad', descripcion='$descripcion', precio_mensual='$precio' 
            WHERE id_plan='$id'";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('✅ Datos del plan modificados correctamente.');</script>";
    } else {
        echo "<script>alert('❌ Error al modificar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Modificar Plan</title>
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

@keyframes fadeIn {
  0% {opacity: 0; transform: translateY(-20px);}
  100% {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<h2>Modificar Plan</h2>
<?php if($mensaje != ""): ?><div class="mensaje"><?php echo $mensaje; ?></div><?php endif; ?>

<?php if (!$plan): ?>
<!-- Formulario de búsqueda -->
<form method="POST">
    <label>ID Plan:</label>
    <input type="number" name="id_plan" required>
    <button type="submit" name="buscar">Buscar Plan</button>
</form>
<?php endif; ?>

<?php if ($plan): ?>
<!-- Formulario de modificación -->
<h3>Modificando plan #<?php echo $plan['id_plan']; ?></h3>
<form method="POST">
    <input type="hidden" name="id_plan" value="<?php echo $plan['id_plan']; ?>">
    <table border="1">
        <tr><th>Campo</th><th>Información</th></tr>
        <tr><td>Velocidad (Mbps)</td>
            <td><input type="number" name="velocidad_mbps" value="<?php echo htmlspecialchars($plan['velocidad_mbps']); ?>"></td>
        </tr>
        <tr><td>Descripción</td>
            <td><input type="text" name="descripcion" value="<?php echo htmlspecialchars($plan['descripcion']); ?>"></td>
        </tr>
        <tr><td>Precio Mensual</td>
            <td><input type="number" step="0.01" name="precio_mensual" value="<?php echo htmlspecialchars($plan['precio_mensual']); ?>"></td>
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