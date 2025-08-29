<?php
// Iniciar sesión para usar variables de sesión
session_start();

// Incluir la conexión a la base de datos
include("conectar.php");

// Variable para mostrar mensajes de error de login
$mensaje_login = "";

// Verificar si se envió el formulario por método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener datos del formulario y eliminar espacios
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Preparar consulta segura para evitar SQL Injection
    $stmt = $conexion->prepare("SELECT id_cliente, usuario, password FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $res = $stmt->get_result();

    // Si existe el usuario, verificamos la contraseña
    if ($res->num_rows === 1) {
        $cliente = $res->fetch_assoc();
        // Verificar la contraseña con password_verify()
        if (password_verify($password, $cliente['password'])) {
            // Guardar datos en sesión y redirigir al menú principal
            $_SESSION['logueado'] = true;
            $_SESSION['cliente_id'] = $cliente['id_cliente'];
            $_SESSION['cliente_nombre'] = $cliente['usuario'];
            header("Location: menuprincipal.php");
            exit();
        } else {
            // Contraseña incorrecta
            $mensaje_login = "Usuario o contraseña incorrectos.";
        }
    } else {
        // Usuario no encontrado
        $mensaje_login = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>

    <style>
        :root {
            --dark-brown: #561C24;
            --medium-brown: #b43b4bff;
            --beige-dark: #C7B7A3;
            --beige-light: #E8D8C4;
            --accent-orange: #be3300ff;
            --accent-gold: #d8b800ff;
            --white-transparent: rgba(255,255,255,0.85);
        }

        /* Configuración general del layout */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, var(--beige-light), var(--beige-dark));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Contenedor del formulario de login */
        .login-container {
            background: var(--white-transparent);
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 380px;
            animation: fadeIn 1s ease; /* Animación al aparecer */
        }

        /* Animación suave de entrada */
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--dark-brown);
        }

        /* Grupo de input + ícono */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        /* Campos de texto */
        .input-group input {
            width: 100%;
            padding: 12px 40px 12px 14px; /* espacio para el ícono */
            font-size: 15px;
            border: 1px solid var(--medium-brown);
            border-radius: 8px;
            transition: border-color 0.3s;
            background-color: var(--beige-light);
            color: var(--dark-brown);
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--accent-orange);
        }

        /* Íconos a la derecha de los inputs */
        .input-group span.icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--medium-brown);
            cursor: pointer;
        }

        /* Mensaje de error */
        .mensaje {
            text-align: center;
            color: var(--accent-orange);
            margin-bottom: 15px;
            font-weight: bold;
            animation: fadeIn 0.4s ease-in-out;
        }

        /* Botón de enviar */
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
        }

        button[type="submit"]:hover {
            background-color: var(--medium-brown);
        }

        /* Botón para ir al registro */
        .btn-registro {
            margin-top: 15px;
            background-color: var(--accent-orange);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            width: 100%;
        }

        .btn-registro a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .btn-registro:hover {
            background-color: var(--accent-gold);
            color: var(--dark-brown);
        }
    </style>
</head>
<body>

<!-- Contenedor principal -->
<div class="login-container">
    <h2>Iniciar Sesión</h2>

    <!-- Mostrar mensaje de error si existe -->
    <?php if ($mensaje_login): ?>
        <p class="mensaje"><?php echo $mensaje_login; ?></p>
    <?php endif; ?>

    <!-- Formulario de login -->
    <form method="POST" action="">
        <!-- Campo de usuario con ícono -->
        <div class="input-group">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <span class="icon">👤</span>
        </div>
        <!-- Campo de contraseña con ícono de mostrar -->
        <div class="input-group">
            <input type="password" name="password" placeholder="Contraseña" required>
            <span class="icon toggle-password">👁️</span>
        </div>
        <button type="submit">Entrar</button>
    </form>

    <!-- Botón que lleva al registro -->
    <button class="btn-registro">
        <a href="registro.php">Registrar nuevo usuario</a>
    </button>
</div>

<!-- JavaScript para funcionalidad interactiva -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mostrar/ocultar contraseña
        const toggleBtn = document.querySelector('.toggle-password');
        const passwordInput = document.querySelector('input[name="password"]');

        toggleBtn.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈'; // Cambiar ícono
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        });

        // Desvanecer mensaje de error después de 4 segundos
        const mensaje = document.querySelector('.mensaje');
        if (mensaje) {
            setTimeout(() => {
                mensaje.style.transition = 'opacity 0.8s ease';
                mensaje.style.opacity = '0';
                setTimeout(() => mensaje.remove(), 800); // Eliminar del DOM
            }, 4000);
        }
    });
</script>

</body>
</html>
A<html lang="es">
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
