<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<?php
include("../menu/conectar.php");
$mensaje = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente   = $_POST['id_cliente'];
    $nuevo_estado = $_POST['estado'];
    $telefono     = $_POST['telefono'];
    $email        = $_POST['email'];
    $direccion    = $_POST['direccion'];
    $ciudad       = $_POST['ciudad'];
    $departamento = $_POST['departamento'];

    $estados_validos = ['Activo','Suspendido','Cancelado'];
    if (!in_array($nuevo_estado, $estados_validos)) {
        $mensaje = "⚠️ Estado inválido.";
    } else {
        $sql = "UPDATE clientes 
                   SET estado = ?, telefono = ?, email = ?, direccion = ?, ciudad = ?, departamento = ?
                 WHERE id_cliente = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssi", $nuevo_estado, $telefono, $email, $direccion, $ciudad, $departamento, $id_cliente);

        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "✅ Datos del cliente actualizados correctamente.";
        } else {
            $mensaje = "❌ Error al actualizar: " . mysqli_error($conexion);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
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
  color: var(--dark-brown);
}

input[type="text"],
input[type="email"],
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

input[type="text"]:focus,
input[type="email"]:focus,
input[type="number"]:focus,
select:focus {
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
<?php if (!empty($mensaje)): ?>
  alert("<?php echo $mensaje; ?>");
<?php endif; ?>
</script>
</head>
<body>

<h2>Modificar Cliente</h2>

<form method="post" action="">
    <label>ID Cliente:</label>
    <input type="number" name="id_cliente" required>

    <label>Estado:</label>
    <select name="estado" required>
      <option value="Activo">Activo</option>
      <option value="Suspendido">Suspendido</option>
      <option value="Cancelado">Cancelado</option>
    </select>

    <label>Teléfono:</label>
    <input type="text" name="telefono">

    <label>Email:</label>
    <input type="email" name="email">

    <label>Dirección:</label>
    <input type="text" name="direccion">

    <label>Ciudad:</label>
    <input type="text" name="ciudad">

    <label>Departamento:</label>
    <input type="text" name="departamento">

    <input type="submit" value="Actualizar">
</form>

<form action="../modificar/menumodificar.html" method="get">
    <button type="submit">Regresar al Menú</button>
</form>

</body>
</html>
