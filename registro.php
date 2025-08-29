<?php
session_start();  // Inicia sesión para manejar variables de sesión si es necesario
include("conectar.php");  // Incluye el archivo de conexión a la base de datos

$mensaje_registro = "";  // Variable para almacenar mensajes de error o éxito

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtiene y limpia los datos enviados desde el formulario
    $usuario = trim($_POST['usuario']);
    $nombre = trim($_POST['nombre']);
    $password = trim($_POST['password']);

    // Validación básica: verificar que no estén vacíos
    if ($usuario === "" || $nombre === "" || $password === "") {
        $mensaje_registro = "Por favor, complete todos los campos.";
    } else {
        // Preparar consulta para verificar si el usuario ya existe en la base de datos
        $stmt_check = $conexion->prepare("SELECT id_cliente FROM usuarios WHERE usuario = ?");
        $stmt_check->bind_param("s", $usuario);
        $stmt_check->execute();
        $resultado_check = $stmt_check->get_result();

        if ($resultado_check->num_rows > 0) {
            // Si ya existe un usuario con ese nombre, mostrar mensaje de error
            $mensaje_registro = "El usuario ya existe. Por favor elija otro.";
        } else {
            // Si el usuario es nuevo, hashear la contraseña para seguridad
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            // Obtener la fecha y hora actual para registro
            $fecha_actual = date("Y-m-d H:i:s");

            // Preparar sentencia para insertar nuevo usuario
            $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, nombre, password, fecha_registro) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $usuario, $nombre, $password_hash, $fecha_actual);

            if ($stmt->execute()) {
                // Si se insertó correctamente, redirigir a la página de login
                header("Location: login.php");
                exit();
            } else {
                // En caso de error al insertar, mostrar mensaje
                $mensaje_registro = "Error al registrar. Intente de nuevo.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Registrar Nuevo Usuario</title>
<style>
    /* Variables de colores para uso consistente */
    :root {
        --dark-brown: #561C24;
        --medium-brown: #b43b4bff;
        --beige-dark: #C7B7A3;
        --beige-light: #E8D8C4;
        --accent-orange: #be3300ff;
        --accent-gold: #d8b800ff;
        --white-transparent: rgba(255,255,255,0.85);
    }

    /* Reseteo y configuración global */
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Helvetica Neue', sans-serif;
        height: 100vh;
        /* Imagen de fondo, ajustada para cubrir todo y centrada */
        background: linear-gradient(-45deg, #fe8998ff, #c44253ff, #731c28ff, #390f15ff);
        background-size: 400% 400%;
        animation: animarFondo 15s ease infinite;
 
        background-position: center;
        background-repeat: no-repeat;
        /* Centrar contenido vertical y horizontalmente con flexbox */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Contenedor principal del formulario */
    .container {
        background: var(--white-transparent); /* Fondo blanco con transparencia */
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15); /* Sombra suave */
        width: 100%;
        max-width: 400px;
        animation: fadeIn 1s ease; /* Animación de entrada */
    }

    /* Animación para suavizar la aparición */
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes animarFondo {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

    /* Título del formulario */
    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: var(--dark-brown);
    }

    /* Estilos para las etiquetas de los campos */
    label {
        display: block;
        margin: 10px 0 5px;
        color: var(--dark-brown);
        font-weight: 600;
    }

    /* Campos de texto y contraseña */
    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px 14px;
        font-size: 16px;
        border: 1px solid var(--medium-brown);
        border-radius: 8px;
        background-color: var(--beige-light);
        color: var(--dark-brown);
        transition: border-color 0.3s;
    }

    /* Cambiar borde al enfocar */
    input[type="text"]:focus,
    input[type="password"]:focus {
        outline: none;
        border-color: var(--accent-orange);
    }

    /* Mensajes de error o información */
    .mensaje {
        text-align: center;
        color: var(--accent-orange);
        margin-bottom: 15px;
        font-weight: bold;
        animation: fadeIn 0.4s ease-in-out;
    }

    /* Botón para enviar formulario */
    button[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: var(--dark-brown);
        border: none;
        color: white;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 10px;
    }

    /* Efecto hover para botón enviar */
    button[type="submit"]:hover {
        background-color: var(--medium-brown);
    }

    /* Botón para volver al login */
    .btn-volver {
        margin-top: 15px;
        background-color: var(--accent-orange);
        border: none;
        color: white;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        font-size: 16px;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    /* Efecto hover para botón volver */
    .btn-volver:hover {
        background-color: var(--accent-gold);
        color: var(--dark-brown);
    }

    /* Estilos para el enlace dentro del botón volver */
    .btn-volver a {
        color: white;
        text-decoration: none;
        display: block;
        user-select: none; /* Evita que el texto se seleccione al hacer click */
    }

    /* Hover en enlace dentro del botón */
    .btn-volver a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Registrar Nuevo Usuario</h2>

    <!-- Mostrar mensaje solo si existe -->
    <?php if ($mensaje_registro): ?>
        <p class="mensaje"><?php echo $mensaje_registro; ?></p>
    <?php endif; ?>

    <!-- Formulario de registro -->
    <form method="POST" action="">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" placeholder="Nombre de usuario" required autofocus>

        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" placeholder="Contraseña" required>

        <button type="submit">Registrar</button>
    </form>

    <!-- Botón para volver a login -->
    <button class="btn-volver">
        <a href="login.php">Volver a Iniciar Sesión</a>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Buscar si hay mensaje de error o información
    const mensaje = document.querySelector('.mensaje');
    if (mensaje) {
        // Después de 4 segundos, hacer fade out del mensaje y eliminarlo del DOM
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
