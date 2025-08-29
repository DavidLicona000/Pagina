<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include '../menu/conectar.php'; // conexión

$factura = null;  // para almacenar la fila encontrada
$mensaje = "";    // mensaje de alerta

if (isset($_POST['buscar'])) {
    $id_factura = $_POST['id_factura'];
    $check_sql = "SELECT * FROM facturas WHERE id_factura='$id_factura'";
    $resultado = mysqli_query($conexion, $check_sql);

    if (mysqli_num_rows($resultado) > 0) {
        $factura = mysqli_fetch_assoc($resultado);
    } else {
        $mensaje = "⚠️ No existe una factura con ese ID.";
    }
}

if (isset($_POST['guardar'])) {
    $id_factura   = $_POST['id_factura'];
    $nuevo_estado = $_POST['estado'];

    // Validar estado permitido
    $estados_validos = ['Pendiente','Pagada','Vencida'];
    if (!in_array($nuevo_estado, $estados_validos)) {
        $mensaje = "⚠️ Estado inválido.";
    } else {
        // Actualizar estado
        $sql = "UPDATE facturas SET estado = ? WHERE id_factura = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "si", $nuevo_estado, $id_factura);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('✅ Estado de la factura actualizado correctamente.');</script>";
            // recargar la factura actualizada
            $check_sql = "SELECT * FROM facturas WHERE id_factura='$id_factura'";
            $resultado = mysqli_query($conexion, $check_sql);
            $factura = mysqli_fetch_assoc($resultado);
        } else {
            echo "<script>alert('❌ Error al actualizar: " . mysqli_error($conexion) . "');</script>";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Modificar Factura</title>
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
  max-width: 450px;
  margin-bottom: 20px;
  animation: fadeIn 1s ease forwards;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: var(--dark-brown);
}

input[type="number"],
select {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 2px solid var(--medium-brown);
  border-radius: 8px;
  font-size: 16px;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

input[type="number"]:focus,
select:focus {
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

<h2>Modificar Estado de Factura</h2>
<?php if($mensaje) echo "<div class='mensaje'>$mensaje</div>"; ?>

<?php if (!$factura): ?>
<!-- Solo mostrar búsqueda si no hay factura cargada -->
<form method="POST">
    <label>ID Factura:</label>
    <input type="number" name="id_factura" required>
    <button type="submit" name="buscar">Buscar</button>
</form>
<?php endif; ?>

<?php if ($factura): ?>
<!-- Formulario de modificación -->
<form method="POST">
    <input type="hidden" name="id_factura" value="<?php echo $factura['id_factura']; ?>">

    <label>Nuevo Estado:</label>
    <select name="estado" required>
      <option value="Pendiente" <?php if($factura['estado']=='Pendiente') echo 'selected'; ?>>Pendiente</option>
      <option value="Pagada" <?php if($factura['estado']=='Pagada') echo 'selected'; ?>>Pagada</option>
      <option value="Vencida" <?php if($factura['estado']=='Vencida') echo 'selected'; ?>>Vencida</option>
    </select>

    <button type="submit" name="guardar">Actualizar</button>
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