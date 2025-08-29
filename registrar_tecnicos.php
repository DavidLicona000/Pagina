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
<html lang="en">
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
    <h1>Datos Del Inventario para Proveedores</h1>

    <form action="registrar_tecnicos2.php" method="POST">

        <label for="id_tecnico">
            🏷️ Código De Contrato:
            <input type="number" id="id_tecnico" name="id_tecnico" placeholder=" Ejem. 5" required autofocus>
        </label>
        <br>

        <label for="nombre">
            🏷️ Nombre Del Tecnico:
            <input type="text" id="nombre" name="nombre" placeholder=" Ejem. No se" required autofocus>
        </label>
        <br>

        <label for="apellido">
            🏷️ Apellido Del Tecnico:
            <input type="text" id="apellido" name="apellido" placeholder=" Ejem. Villatoro" required autofocus>
        </label>
        <br>

        <label for="telefono">
             Telefono Del Tecnico:
            <input type="text" id="telefono" name="telefono" placeholder=" Ejem. +50488887777" required autofocus>
        </label>
        <br>

        <label for="email">
            Correo Del Tecnico:
            <input type="email" id="email" name="email" placeholder=" Ejem. oscar.ruiz@cableshn.com" required autofocus>
        </label>
        <br>

        <label for="especialidad">
            Especialidad que desea:
            <select id="especialidad" name="especialidad" required>
                <option value="">Seleccione una especialidad</option>
                <option value="Instalacion">Instalacion</option>
                <option value="Mantenimiento">Mantenimiento</option>
                <option value="Soporte">Soporte</option>
                <option value="Mixto">Mixto</option>
            </select>
        </label>
        <br>

        <label for="zona_cobertura">
           📑 Zona de cobertura que desea:
            <select id="zona_cobertura" name="zona_cobertura" required>
                <option value="">Seleccione una zona de cobertura</option>
                <option value="San Pedro Sula">San Pedro Sula</option>
                <option value="Tegucigalpa">Tegucigalpa</option>
                <option value="La Ceiba">La Ceiba</option>
                <option value="Comayagua">Comayagua</option>
                <option value="Choluteca">Choluteca</option>
                <option value="Tela">Tela</option>
            </select>
        </label>
        <br>

        <input type="submit" id="enviar" value="Enviar">
        <input type="reset" id="borrar" value="Borrar">

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