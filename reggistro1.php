<?php 
session_start();
include("conexion1.php");

$mensaje_registro = "";

if (isset($_POST['accion']) && $_POST['accion'] === "registro") {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $cedula = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $ciudad = trim($_POST['ciudad']);
    $departamento = trim($_POST['departamento']);
    $clave_plana = $_POST['clave']; // Contraseña que elige el usuario

    $clave_hash = password_hash($clave_plana, PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE cedula_identidad = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mensaje_registro = "Esta cédula ya está registrada.";
    } else {
        $stmt = $conexion->prepare("INSERT INTO clientes (nombre, apellido, cedula_identidad, telefono, email, direccion, ciudad, departamento, clave) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nombre, $apellido, $cedula, $telefono, $email, $direccion, $ciudad, $departamento, $clave_hash);

        if ($stmt->execute()) {
            $mensaje_registro = "Cliente registrado correctamente. Puedes iniciar sesión ahora.";
        } else {
            $mensaje_registro = "Error al registrar cliente: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Clientes</title>
    <style>
        /* Paleta de colores */
        :root {
            --dark-brown: #561C24;  /* Rojo oscuro para texto */
            --medium-brown: #6D2932; /* Rojo más suave para detalles */
            --beige-dark: #C7B7A3; /* Tono beige para contrastar */
            --beige-light: #E8D8C4; /* Beige suave de fondo */
            --accent-orange: #c48168ff; /* Naranja cálido */
            --accent-gold: #FFD700; /* Dorado cálido */
            --white-transparent: rgba(255, 255, 255, 0.85); /* Blanco semitransparente */
        }

        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--beige-light);
            color: var(--dark-brown);
            line-height: 1.6;
            padding: 20px;
        }

        /* Estilo para el contenedor del formulario */
        .container {
            background: var(--white-transparent);
            padding: 30px 35px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(86, 28, 36, 0.15);
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 2.2em;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--dark-brown);
        }

        /* Estilo para los mensajes */
        .mensaje {
            color: var(--accent-orange);
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Estilos de los inputs */
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            margin: 12px 0;
            border: 2px solid var(--medium-brown);
            border-radius: 8px;
            font-size: 1rem;
            color: var(--dark-brown);
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: var(--accent-orange);
            outline: none;
            box-shadow: 0 0 6px var(--accent-orange);
        }

        button {
            width: 100%;
            padding: 14px 0;
            background: var(--accent-gold);
            color: var(--dark-brown);
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.3s ease;
            box-shadow: 0 4px 10px rgba(255, 215, 0, 0.4);
        }

        button:hover {
            background: var(--accent-orange);
            color: var(--beige-light);
            box-shadow: 0 6px 14px rgba(255, 127, 80, 0.6);
        }

        /* Estilo para el enlace */
        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: var(--medium-brown);
            color: var(--beige-light);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 3px 7px rgba(109, 41, 50, 0.5);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
        }

        /* Cambios al pasar el mouse */
        .btn-volver:hover {
            background: var(--dark-brown);
            color: var(--accent-gold);
            box-shadow: 0 5px 12px rgba(86, 28, 36, 0.8);
        }

        .btn-volver:link,
        .btn-volver:visited {
            color: var(--beige-light);
        }

        .btn-volver:focus,
        .btn-volver:hover {
            color: var(--accent-gold);
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Registrar Nuevo Cliente</h2>

    <?php if ($mensaje_registro): ?>
        <p class="mensaje"><?php echo $mensaje_registro; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="accion" value="registro">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required>

        <label for="cedula">Cédula:</label>
        <input type="text" name="cedula" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono">

        <label for="email">Email:</label>
        <input type="email" name="email">

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" required>

        <label for="ciudad">Ciudad:</label>
        <input type="text" name="ciudad">

        <label for="departamento">Departamento:</label>
        <input type="text" name="departamento">

        <label for="clave">Contraseña:</label>
        <input type="password" name="clave" required>

        <button type="submit">Registrar Cliente</button>
    </form>

    <a class="btn-volver" href="login2.php">¿Ya tienes cuenta? Inicia sesión aquí</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mensaje = document.querySelector('.mensaje');
    if (mensaje) {
        setTimeout(() => {
            mensaje.style.transition = 'opacity 0.8s ease';
            mensaje.style.opacity = '0';
            setTimeout(() => mensaje.remove(), 800);
        }, 4000);
    }
});
</script>

</body>
</html>
