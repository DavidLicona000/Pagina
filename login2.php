<?php
session_start();
include("conexion1.php");

$mensaje_login = "";

if (isset($_POST['accion']) && $_POST['accion'] === "login") {
    $id_cliente = trim($_POST['id_cliente']);  // Usamos ID de cliente ahora
    $clave  = $_POST['clave'];

    // Buscar por id_cliente en lugar de cedula_identidad
    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->bind_param("i", $id_cliente);  // Cambiamos a tipo entero "i" para ID de cliente
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (isset($usuario['clave']) && password_verify($clave, $usuario['clave'])) {
            $_SESSION['usuario_id'] = $usuario['id_cliente'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            header("Location: menuprincipal2.php");
            exit();
        } else {
            $mensaje_login = "ID de cliente o contraseña incorrecta.";
        }
    } else {
        $mensaje_login = "ID de cliente o contraseña incorrecta.";
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
            --medium-brown: #6D2932;
            --beige-dark: #C7B7A3;
            --beige-light: #E8D8C4;
            --accent-orange: #FF7F50;
            --accent-gold: #FFD700;
            --white-transparent: rgba(255,255,255,0.85);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, var(--beige-light), var(--beige-dark));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--dark-brown);
        }

        .login-container {
            background: var(--white-transparent);
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 380px;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--dark-brown);
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 40px 12px 14px;
            font-size: 16px;
            border: 1px solid var(--medium-brown);
            border-radius: 8px;
            background-color: var(--beige-light);
            color: var(--dark-brown);
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--accent-orange);
        }

        .input-group span.icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--medium-brown);
            cursor: pointer;
        }

        .mensaje {
            text-align: center;
            color: var(--accent-orange);
            margin-bottom: 15px;
            font-weight: bold;
            animation: fadeIn 0.4s ease-in-out;
        }

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
        }

        .btn-registro:hover {
            background-color: var(--accent-gold);
            color: var(--dark-brown);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 25px 20px;
            }

            .input-group input {
                font-size: 14px;
            }

            button[type="submit"] {
                font-size: 14px;
            }

            .btn-registro {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>

    <?php if ($mensaje_login): ?>
        <p class="mensaje"><?php echo $mensaje_login; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="accion" value="login">

        <div class="input-group">
            <input type="number" name="id_cliente" placeholder="ID de Cliente" required>
            <span class="icon">🆔</span>
        </div>

        <div class="input-group">
            <input type="password" name="clave" placeholder="Contraseña" required>
            <span class="icon toggle-password">👁️</span>
        </div>

        <button type="submit">Entrar</button>
    </form>

    <button class="btn-registro">
        <a href="../menu/reggistro1.php">¿No tienes cuenta? Regístrate aquí</a>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.toggle-password');
    const passwordInput = document.querySelector('input[name="clave"]');

    toggleBtn.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleBtn.textContent = '🙈';
        } else {
            passwordInput.type = 'password';
            toggleBtn.textContent = '👁️';
        }
    });

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
