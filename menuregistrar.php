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
  <title>Menú Principal - Sistema Farmacia</title>
  <link rel="stylesheet" href="css/estilos.css">
  <style>
    /* 🎨 Paleta de colores */
    :root {
      --dark-brown: #561C24;
      --medium-brown: #6D2932;
      --beige-dark: #C7B7A3;
      --beige-light: #E8D8C4;
      --accent-orange: #FF7F50;
      --accent-gold: #FFD700;
    }

    /* Reset General */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Fondo elegante */
    body {
      background-color: #E8D8C4;
  background-size: cover;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      min-height: 100vh;
      flex-direction: column;
      background-size: cover;
      color: var(--dark-brown);
      overflow-x: hidden;
    }

    /* Animación del fade */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .header {
      position: absolute;
      top: 20px;
      right: 20px;
      animation: fadeIn 1s ease;
    }

    .header-links {
      list-style: none;
      display: flex;
      gap: 15px;
    }

    .header-links li a {
      color: var(--beige-light);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .header-links li a:hover {
      color: var(--accent-orange);
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: rgba(86, 28, 36, 0.85); /* Dark brown glass */
      backdrop-filter: blur(10px);
      color: white;
      height: 100vh;
      padding: 20px;
      position: fixed;
      top: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      border-right: 8px solid var(--accent-orange);
      animation: fadeIn 1.2s ease;
    }

    .sidebar h2 {
      font-size: 20px;
      margin-bottom: 20px;
      color: var(--accent-gold);
      font-weight: 700;
    }

    .sidebar a {
      display: block;
      color: var(--beige-light);
      text-decoration: none;
      margin: 8px 0;
      padding: 10px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .sidebar a:hover {
      background-color: var(--accent-orange);
      color: var(--dark-brown);
      transform: translateX(5px);
    }

    /* Main Content */
    .contenido {
      flex-grow: 1;
      padding: 40px;
      margin-left: 270px;
      background: rgba(231, 216, 196, 0.85); /* beige-light glass */
      border-radius: 20px;
      backdrop-filter: blur(12px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
      text-align: center;
      animation: fadeIn 1.5s ease;
    }

    /* Bienvenida */
    .bienvenida {
      background: rgba(255, 255, 255, 0.7);
      padding: 25px;
      text-align: center;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
      margin-bottom: 30px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      animation: fadeIn 2s ease;
    }

    .bienvenida h1 {
      font-size: 32px;
      font-weight: 700;
      color: var(--dark-brown);
    }

    .bienvenida p {
      font-size: 18px;
      color: var(--medium-brown);
    }

    /* Contenedor tipo producto */
    .juice-container {
      background: rgba(109, 41, 50, 0.15); /* medium brown glass */
      border-radius: 20px;
      padding: 40px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      width: 80%;
      max-width: 1000px;
      margin: 40px auto;
      animation: fadeIn 2.5s ease;
    }

    .juice-left h1 {
      font-size: 30px;
      font-weight: 600;
      color: var(--dark-brown);
    }

    .juice-left p {
      font-size: 16px;
      color: var(--medium-brown);
      margin-bottom: 20px;
    }

    .order-btn {
      background: var(--accent-orange);
      color: var(--dark-brown);
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .order-btn:hover {
      background: var(--accent-gold);
      transform: scale(1.05);
      box-shadow: 0 0 15px rgba(255, 215, 0, 0.6);
    }

    .juice-bottles img {
      width: 160px;
      height: 160px;
      object-fit: cover;
      border-radius: 50%;
      transition: transform 0.4s ease;
    }

    .juice-bottles img:hover {
      transform: rotate(-5deg) scale(1.1);
    }

    /* Cuadro imagen */
    .cuadro-imagen {
      padding: 20px;
      margin-top: 20px;
      animation: fadeIn 3s ease;
    }

    .cuadro-imagen img {
      width: 15%;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.25);
      transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .cuadro-imagen img:hover {
      transform: scale(1.1);
      box-shadow: 0 0 20px var(--accent-gold);
    }

    /* Footer */
    .footer {
      position: fixed;
      bottom: 20px;
      right: 20px;
    }

    .footer a {
      color: white;
      font-size: 14px;
      text-decoration: none;
      background: var(--dark-brown);
      padding: 10px 20px;
      border-radius: 5px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .footer a:hover {
      background: var(--accent-orange);
      color: var(--dark-brown);
    }
  </style>
</head>
<body>
   <div class="header">
    <ul class="header-links">
      <li><a href="../menu/inicio.php">Cerrar sesión</a></li>
      <li><a href="../menu/menuprincipal.php">Menú principal</a></li>
    </ul>
   </div>

   <div class="sidebar">
    <h2>MENU CABLE E INTERNET</h2>
    <a href="registrar_clientes.php">REGISTRAR CLIENTES</a>
    <a href="registrar_contratos.php">REGISTRAR CONTRATOS</a>
    <a href="registrar_equipos.php">REGISTRAR EQUIPOS</a>
    <a href="registrar_facturas.php">REGISTRAR FACTURAS</a>
    <a href="registrar_pagos.php">REGISTRAR PAGOS</a>
    <a href="registrar_planes.php">REGISTRAR PLANES</a>
    <a href="registrar_promociones.php">REGISTRAR PROMOCIONES</a>
    <a href="registrar_soporte.php">REGISTRAR SOPORTE</a>
    <a href="registrar_tecnicos.php">REGISTRAR TECNICOS</a>
    <a href="../menu/menuprincipal.php">Regresar</a>
   </div>
   
   <div class="contenido">
    <div class="bienvenida">
      <h1>Bienvenido al Sistema de</h1>
      <h1>REGISTROS</h1>
      <p>Selecciona una opción del panel izquierdo para comenzar.</p>
    </div>

    <div class="juice-container">
      <div class="juice-left">
        <h1>Sistema de Registros de Cable e Internet</h1>
        <p>Este sistema permite llevar un control organizado de los clientes, servicios contratados, pagos y estados de conexión de cable e internet. Facilita la gestión de nuevos registros, actualizaciones de datos, facturación y seguimiento de deudas, brindando rapidez y eficiencia en la administración de los servicios ofrecidos.</p>
        <a href="../consultar/menuconsultar.php" class="order-btn">Consulta ahora →</a>
      </div>
      <div class="juice-right">
        <div class="juice-bottles">
          <img src="../img/19.png" width="20" height="20" alt="Producto 1">
          <div class="heart">❤️</div>
          <img src="../img/router.png" alt="Producto 2">
        </div>
        <div class="details">
          <p>500 mbps | 200 mbps | 150 mbps</p>
        </div>
      </div>
    </div>

    <div class="cuadro-imagen">
      <img src="../img/31.png" alt="Imagen representativa">
    </div>

    <div class="footer">
  <a href="../menu/logout.php">Cerrar sesión</a>
</div>
  </div>
  
  <script>
    function cerrarSesion() {
      localStorage.removeItem("logueado");
      window.location.href = "../menu/login.php";
    }
  </script>
</body>
</html>