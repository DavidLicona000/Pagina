<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../menu/login.php"); // Redirige al login si no está logueado
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="iconfarmacia.jpeg" rel="icon">
    <title>REGISTROS</title>

    <style>
        :root {
            --dark-brown: #561C24;
            --medium-brown: #6D2932;
            --beige-dark: #C7B7A3;
            --beige-light: #E8D8C4;
            --accent-orange: #FF7F50;
            --accent-gold:#f5d56d;
            --white-transparent: rgba(255, 255, 255, 0.85);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-brown);
            padding: 2rem;
            animation: fadeIn 1s ease-in;
            overflow-x: hidden;
            background: linear-gradient(270deg, var(--beige-light), var(--beige-dark), var(--accent-gold));
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            position: relative;
        }

        /* Fondo burbujas */
        .bubble {
            position: fixed;
            bottom: -100px;
            background: rgb(120, 99, 99);
            border-radius: 50%;
            animation: rise 20s infinite ease-in;
            pointer-events: none;
            z-index: 0;
        }

        .bubble:nth-child(1) { width: 60px; height: 60px; left: 10%; animation-duration: 18s; }
        .bubble:nth-child(2) { width: 40px; height: 40px; left: 30%; animation-duration: 22s; }
        .bubble:nth-child(3) { width: 80px; height: 80px; left: 50%; animation-duration: 25s; }
        .bubble:nth-child(4) { width: 50px; height: 50px; left: 70%; animation-duration: 20s; }
        .bubble:nth-child(5) { width: 100px; height: 100px; left: 90%; animation-duration: 28s; }
        

        @keyframes rise {
            0% { transform: translateY(0) scale(1); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: translateY(-110vh) scale(1.2); opacity: 0; }
        }

        /* Fondo degradado animado */
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Animaciones clave de contenido */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes bounceIn {
            0% { transform: scale(0.9); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); }
        }

        @keyframes glow {
            0% { box-shadow: 0 0 5px var(--accent-orange); }
            50% { box-shadow: 0 0 15px var(--accent-gold); }
            100% { box-shadow: 0 0 5px var(--accent-orange); }
        }

        /* Contenedor principal */
        .marco {
            background-color: var(--white-transparent);
            border: 2px solid var(--medium-brown);
            border-radius: 16px;
            max-width: 750px;
            margin: auto;
            padding: 2.5rem;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.8s ease-out, glow 3s infinite ease-in-out;
            z-index: 1;
            position: relative;
        }

        .logo {
            display: block;
            margin: 0 auto 1.5rem;
            max-width: 120px;
            height: auto;
            transition: transform 0.3s ease;
            animation: bounceIn 1s ease-out;
        }
        .logo:hover {
            transform: rotate(5deg) scale(1.1);
        }

        h1 {
            text-align: center;
            color: var(--medium-brown);
            margin-bottom: 2rem;
            font-size: 1.8rem;
            letter-spacing: 1px;
            animation: fadeIn 1s ease-in;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        label {
            display: flex;
            flex-direction: column;
            font-weight: bold;
            color: var(--dark-brown);
            transition: color 0.3s ease;
            animation: fadeIn 1s ease-in;
        }

        input,
        select {
            padding: 0.6rem;
            border: 1px solid var(--beige-dark);
            border-radius: 8px;
            background-color: white;
            color: var(--dark-brown);
            font-size: 1rem;
            transition: box-shadow 0.3s, border-color 0.3s, transform 0.2s;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 3px rgba(255, 127, 80, 0.3);
            transform: scale(1.02);
        }

        /* Botones */
        input[type="submit"],
        input[type="reset"] {
            background-color: var(--accent-orange);
            color: white;
            font-weight: bold;
            border: none;
            padding: 0.8rem;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            animation: fadeIn 1s ease-in;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: var(--accent-gold);
            color: var(--dark-brown);
            transform: scale(1.05) rotate(-1deg);
        }

        /* Botón regresar */
        .boton-regresar {
            display: block;
            text-align: center;
            margin: 2rem auto 0;
            background-color: var(--medium-brown);
            color: white;
            padding: 0.8rem 1.6rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            width: fit-content;
            transition: background-color 0.3s, transform 0.2s;
            animation: bounceIn 1.2s ease-out;
        }

        .boton-regresar:hover {
            background-color: var(--dark-brown);
            transform: scale(1.08);
        }

        /* Responsivo */
        @media (max-width: 600px) {
            .marco {
                padding: 1.5rem;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Burbujas de fondo -->
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>  




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/icono.png" rel="icon">
    <title> REGISTROS </title>
    <link rel="stylesheet" href="farmacia.css">
</head>
<body>

    <div class="marco">
        <img src="../img/icono.png" alt="Farmacia" class="logo">
        <h1>Datos del Inventario para Registrar Promociones</h1>

        <form action="registrar_promociones2.php" method="POST">

            <label for="id_promocion">🏷️ Código de promocion:
                <input type="number" id="id_promocion" name="id_promocion" placeholder="Ejem. 8" required autofocus>
            </label>

            <label for="titulo">🏷️ Titulo de Promocion:
                <input type="text" id="titulo" name="titulo" placeholder="Ejem. Instalación Gratis" required autofocus>
            </label>

            <label for="descripcion">🏷️ Descripcion de la Promocion:
                <input type="text" id="descripcion" name="descripcion" placeholder="Ejem. No pagues instalación en planes residenciales" required autofocus>
            </label>

            <label for="fecha_inicio">📅 Fecha de Inicio:
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>
            </label>

            <label for="fecha_fin">📅 Fecha Final:
                <input type="date" id="fecha_fin" name="fecha_fin" required>
            </label>

            <label for="descuento_porcentaje">🏷️ El descuento de porcentaje:
                <input type="number" id="descuento_porcentaje" name="descuento_porcentaje" placeholder="Ejem. 100.00" required autofocus>
            </label>

            <label for="aplicable_a"> La Promocion es aplicable a:
                <select id="aplicable_a" name="aplicable_a" required>
                    <option value="">Seleccione una categoria:</option>
                    <option value="Plan">Plan</option>
                    <option value="instalacion">instalacion</option>
                    <option value="Factura">Factura</option>
                </select>
            </label>

            <input type="submit" value="Enviar">
            <input type="reset" value="Borrar">
        </form>
    </div>

    <a href="menuregistrar.php" class="boton-regresar">REGRESAR AL MENU</a>
    <script>
  function cerrarSesion() {
  localStorage.removeItem("logueado");
  window.location.href="../menu/login.php";
}
</script>
</body>
</html>