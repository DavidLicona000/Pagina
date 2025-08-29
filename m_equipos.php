<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include("../menu/conectar.php");

$equipo = null;
$mensaje = "";

// Acción buscar
if (isset($_POST['buscar'])) {
    $id_equipo = $_POST['id_equipo'];
    $check_sql = "SELECT * FROM equipos WHERE id_equipo = ?";
    $stmt = $conexion->prepare($check_sql);
    $stmt->bind_param("i", $id_equipo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $equipo = $resultado->fetch_assoc();
    } else {
        $mensaje = "⚠️ No existe un equipo con ese ID.";
    }
    $stmt->close();
}

// Acción guardar
if (isset($_POST['guardar'])) {
    $id_equipo = $_POST['id_equipo'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $numero_serie = $_POST['numero_serie'];

    $sql = "UPDATE equipos SET marca=?, modelo=?, numero_serie=? WHERE id_equipo=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $marca, $modelo, $numero_serie, $id_equipo);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Datos del equipo modificados correctamente.');</script>";
        // recargar datos actualizados
        $equipo = ['id_equipo'=>$id_equipo, 'marca'=>$marca, 'modelo'=>$modelo, 'numero_serie'=>$numero_serie];
    } else {
        echo "<script>alert('❌ Error al modificar.');</script>";
    }

    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Modificar Equipo</title>
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

.mensaje {
  text-align: center;
  font-size: 18px;
  margin-bottom: 20px;
  color: var(--accent-orange);
}

@keyframes fadeIn {
  0% {opacity: 0; transform: translateY(-20px);}
  100% {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<h2>Modificar Equipo</h2>
<?php if($mensaje != ""): ?><div class="mensaje"><?php echo $mensaje; ?></div><?php endif; ?>

<?php if (!$equipo): ?>
<!-- Formulario de búsqueda solo si no hay equipo -->
<form method="POST">
    <label>ID Equipo:</label>
    <input type="number" name="id_equipo" required>
    <button type="submit" name="buscar">Buscar Equipo</button>
</form>
<?php endif; ?>

<?php if ($equipo): ?>
<!-- Formulario de modificación -->
<h3>Modificando equipo #<?php echo $equipo['id_equipo']; ?></h3>
<form method="POST">
    <input type="hidden" name="id_equipo" value="<?php echo $equipo['id_equipo']; ?>">
    <table border="1">
        <tr><th>Campo</th><th>Información</th></tr>
        <tr><td>Marca</td><td><input type="text" name="marca" value="<?php echo htmlspecialchars($equipo['marca']); ?>"></td></tr>
        <tr><td>Modelo</td><td><input type="text" name="modelo" value="<?php echo htmlspecialchars($equipo['modelo']); ?>"></td></tr>
        <tr><td>Número de Serie</td><td><input type="text" name="numero_serie" value="<?php echo htmlspecialchars($equipo['numero_serie']); ?>"></td></tr>
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