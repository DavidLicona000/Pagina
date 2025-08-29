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
        <h1>Datos del Inventario para Registrar Equipos</h1>

        <form action="registrar_equipos2.php" method="POST">

            <label for="id_equipo">🏷️ Código del Equipo:
                <input type="number" id="id_equipo" name="id_equipo" placeholder="Ejem. 98" required autofocus>
            </label>

            <label for="tipo"> Nombre del Equipo:
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccione un tipo:</option>
                    <option value="Decodificador">Decodificador</option>
                    <option value="Router">Router</option>
                    <option value="Antena">Antena</option>
                    <option value="Switch">Switch</option>
                    <option value="ONT">ONT</option>
                </select>
            </label>

            <label for="marca"> Marca del Equipo:
                <select id="marca" name="marca" required>
                    <option value="">Seleccione un tipo:</option>
                    <option value="Arris">Arris</option>
                    <option value="Huawei">Huawei</option>
                    <option value="Ubiquiti">Ubiquiti</option>
                    <option value="TP-Link">TP-Link</option>
                    <option value="MikroTik">MikroTik</option>
                    <option value="ZTE">ZTE</option>
                </select>
            </label>

            <label for="modelo"> Modelo del Equipo:
                <select id="modelo" name="modelo" required>
                    <option value="">Seleccione un tipo:</option>
                    <option value="Arris-174">Arris-174</option>
                    <option value="Huawei-245">Huawei-245</option>
                    <option value="Arris-267">Arris-267</option>
                    <option value="Ubiquiti-274">Ubiquiti-274</option>
                    <option value="TP-Link-284">TP-Link-284</option>
                    <option value="Ubiquiti-340">Ubiquiti-340</option>
                    <option value="TP-Link-382">TP-Link-382</option>
                    <option value="MikroTik-391">MikroTik-391</option>
                    <option value="Arris-431">Arris-431</option>
                    <option value="TP-Link-489">TP-Link-489</option>
                    <option value="ZTE-512">ZTE-512</option>
                    <option value="TP-Link-541">TP-Link-541</option>
                    <option value="Huawei-655">Huawei-655</option>
                    <option value="Huawei-692">Huawei-692</option>
                    <option value="MikroTik-752">MikroTik-752</option>
                    <option value="MikroTik-772">MikroTik-772</option>
                    <option value="ZTE-854">ZTE-854</option>
                    <option value="Huawei-960">Huawei-960</option>
                </select>
            </label>

            <label for="numero_serie"> Numero de Serie:
                <input type="text" id="numero_serie" name="numero_serie" step="0.01" placeholder="SN100174" required>
            </label>

            <label for="estado"> 📑 Estado:
            <select id="estado" name="estado" required>
                <option value="">Seleccione un Estado</option>
                <option value="Disponible">Disponible</option>
                <option value="En reparacion">En reparacion</option>
                <option value="Entregado">Entregado</option>
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