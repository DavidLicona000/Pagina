<?php
session_start(); // Asegúrate de que siempre se inicie la sesión al principio del archivo.
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login.php"); // Redirige al login si no está logueado.
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú Principal - Sistema DE Cable e Internet</title>

  <link rel="stylesheet" href="css/estilos.css">
  <style>
:root {
  --dark-brown: #561C24;
  --medium-brown: #6D2932;
  --beige-dark: #C7B7A3;
  --beige-light: #E8D8C4;
  --accent-orange: #FF7F50;
  --accent-gold: #FFD700;
  --white-transparent: rgba(255, 255, 255, 0.6);
}

/* Reset General */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  color: var(--dark-brown);

  /* Fondo con imagen */
background-color: #E8D8C4;
  background-size: cover;
}

/* Header */
.principal {
  position: absolute;
  top: 20px;
  right: 20px;
}

.principal-links {
  list-style: none;
  display: flex;
  gap: 15px;
}

.principal-links li a {
  color: var(--dark-brown);
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.principal-links li a:hover {
  color: var(--accent-orange);
  text-decoration: underline;
}

/* Sidebar glass */
.secundario {
  width: 250px;
  background: rgba(86, 28, 36, 0.8);
  color: white;
  height: 100vh;
  padding: 20px;
  position: fixed;
  top: 0;
  left: 0;
  display: flex;
  flex-direction: column;
  border-right: 8px solid var(--accent-orange);
  backdrop-filter: blur(8px);
  animation: slideIn 1s ease forwards;
}

@keyframes slideIn {
  from { transform: translateX(-100%); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

.secundario h2 {
  font-size: 20px;
  margin-bottom: 20px;
  font-weight: 700;
}

.secundario a {
  display: block;
  color: white;
  text-decoration: none;
  margin: 10px 0;
  padding: 10px 10px 10px 30px;
  border-radius: 5px;
  font-size: 16px;
  font-weight: 500;
  position: relative;
  transition: all 0.3s ease;
}

.secundario a::before {
  content: "•";
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 20px;
  color: var(--accent-gold);
}

.secundario a:hover {
  background-color: var(--accent-orange);
  transform: scale(1.05);
}

/* Main Content glass */
.contenido {
  flex-grow: 1;
  padding: 30px;
  margin-left: 270px;
  background: var(--white-transparent);
  border-radius: 15px;
  backdrop-filter: blur(10px);
  box-shadow: 0 0 20px rgba(0,0,0,0.2);
  text-align: center;
  animation: fadeIn 1s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Bienvenida */
.bienvenida {
  background: rgba(232, 216, 196, 0.7);
  backdrop-filter: blur(8px);
  padding: 25px;
  border-radius: 10px;
  margin-bottom: 30px;
  max-width: 650px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  animation: bounceIn 1s ease;
  cursor: pointer;
}

@keyframes bounceIn {
  0% { transform: translateY(-20px); opacity: 0; }
  100% { transform: translateY(0); opacity: 1; }
}

.bienvenida h1 {
  font-size: 32px;
  color: var(--dark-brown);
  margin-bottom: 10px;
}

.bienvenida p {
  font-size: 18px;
  color: var(--medium-brown);
}

/* Cuadros con imágenes glass */
.cuadros {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-top: 40px;
}

.cuadro {
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(6px);
  border-radius: 10px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 6px 12px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  animation: fadeIn 1s ease;
  cursor: pointer;
}

.cuadro img {
  width: 100%;
  height: auto;
  border-radius: 8px;
}

.cuadro:hover {
  transform: translateY(-10px) scale(1.03);
  box-shadow: 0 12px 25px rgba(255, 215, 0, 0.6); /* brillo dorado */
  border: 2px solid var(--accent-gold);
}


.cuadro h3, .cuadro h4 {
  margin-top: 15px;
  font-size: 18px;
  color: var(--medium-brown);
}

/* Información medicamentos */
#informacion-medicamentos {
  display: none;
  background: rgba(255, 127, 80, 0.85);
  backdrop-filter: blur(6px);
  color: white;
  padding: 20px;
  border-radius: 10px;
  margin-top: 30px;
  box-shadow: 0 6px 12px rgba(0,0,0,0.2);
  animation: fadeIn 1s ease;
}

#informacion-medicamentos h2 {
  font-size: 28px;
}

#informacion-medicamentos p {
  font-size: 18px;
}

/* Mini bienvenida */
#mini-bienvenida {
  display: none;
  background: salmon;
  backdrop-filter: blur(5px);
  color: var(--dark-brown);
  padding: 20px;
  border-radius: 8px;
  margin-top: 30px;
  text-align: center;
  box-shadow: 0 6px 12px rgba(0,0,0,0.2);
  animation: fadeIn 1s ease;
}

#mini-bienvenida h2 {
  font-size: 28px;
}

#mini-bienvenida p {
  font-size: 18px;
}

/* Footer */
.footer {
  position: fixed;
  bottom: 20px;
  right: 20px;
}

.footer a {
  color: white;
  background-color: var(--medium-brown);
  font-size: 14px;
  text-decoration: none;
  padding: 10px 20px;
  border-radius: 5px;
  font-weight: 500;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.footer a:hover {
  background-color: var(--dark-brown);
  transform: scale(1.05);
}
</style>
</head>
<body>

  <div class="principal">
    <ul class="principal-links">
      <li><a href="login.php">Cerrar sesión</a></li>
      <li><a href="menuprincipal.php">Menú principal</a></li>
    </ul>
  </div>

  <div class="secundario">
    <h2>MENU CABLE E INTERNET</h2>
    <a href="https://davidlicona000.github.io/Registrar/">MENU REGISTROS</a>
    <a href="menuconsultar.php">MENU CONSULTAS</a>
    <a href="menueliminar.php">MENU ELIMINAR</a>
    <a href="menumodificar.php">MENU MODIFICAR</a>
    <a href="menuactualizar.php">MENU ACTUALIZAR</a>
    <a href="menureportes.php">MENU REPORTES</a>
    <a href="menulistados.php">MENU LISTADOS</a>
    <a href="login2.php">MENU CLIENTES</a>
  </div>

  <div class="contenido">
    <div class="bienvenida" onclick="mostrarMiniBienvenida()">
      <h1>Bienvenido al Sistema de Cable e Internet</h1>
      <p>Selecciona una opcion del menu izquierdo para comenzar.</p><br>
      <h5>Da click para una mini bienvenida...</h5>
    </div>

    <div id="mini-bienvenida">
      <h2>¡Bienvenido!</h2>
      <p>En F4UR INFINITY nos apasiona mantenerte siempre conectado. Ofrecemos servicios de televisión por cable e internet de alta velocidad, con planes diseñados para tu hogar o negocio.

Disfruta de contenido de calidad, conexión estable y atención personalizada, porque tu satisfacción es nuestra prioridad.

¡Conéctate con nosotros y descubre la mejor experiencia en entretenimiento y conectividad!.</p>
    </div>

    <div class="cuadros">
      <div class="cuadro" onclick="mostrarInformacion('medicamentos1')">
        <img src="p.png" alt="imagen 1">
        <h4>¿Qué es el servicio de cable?</h4>
      </div>
      <div class="cuadro" onclick="mostrarInformacion('medicamentos2')">
        <img src="19.png" alt="imagen 2">
        <h4>¿Qué es el servicio de internet?</h4>
      </div>
      <div class="cuadro" onclick="mostrarInformacion('medicamentos3')">
        <img src="iconoo.png" alt="imagen 3">
        <h4>Da click para más información...</h4>
      </div>
    </div>

    <div id="informacion-medicamentos" style="display:none;">
      <h2>Información</h2>
      <p id="informacion-texto">Selecciona una opcion para obtener más información.</p>
    </div>
  </div>

  <div class="footer">
    <a href="login.php" onclick="cerrarSesion()">Cerrar sesión</a>
  </div>

  <script>
    function mostrarMiniBienvenida() {
      const miniBienvenida = document.getElementById('mini-bienvenida');
      miniBienvenida.style.display = 'block';
    }

    function mostrarInformacion(organ) {
      const informacionPastillas = document.getElementById('informacion-medicamentos');
      const textoInformacion = document.getElementById('informacion-texto');
      informacionPastillas.style.display = 'block';

      if (organ === 'medicamentos1') {
        textoInformacion.textContent = "l servicio de televisión por cable es un sistema que transmite señales de televisión a través de cables coaxiales o de fibra óptica a los hogares. Permite acceder a Canales locales e internacionales, Contenido especializado (deportes, películas, noticias), Funciones adicionales como video bajo demanda o grabación de programas.";
      } else if (organ === 'medicamentos2') {
        textoInformacion.textContent = "El servicio de internet por cable ofrece acceso a la red mediante la misma infraestructura que la televisión por cable, o mediante fibra óptica. Permite Navegación web, correo electrónico y redes sociales, Streaming de videos y música, Juegos en línea y teletrabajo..";
      } else if (organ === 'medicamentos3') {
        textoInformacion.textContent = "Contamos con planes flexibles que se adaptan a tus necesidades, desde entretenimiento con canales variados hasta internet rápido y estable para el hogar o la oficina.";
      }
    }
    function cerrarSesion() {
      localStorage.removeItem("logueado");
      window.location.href = "../menu/logout.php";
    }
  </script>
</body>
</html>
