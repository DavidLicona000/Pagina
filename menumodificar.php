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
<title>Menú Principal - Sistema de Cable e Internet</title>
<style>

/* Ya van tus variables de color y reset... */
:root {
  --dark-brown: #561C24;
  --medium-brown: #6D2932;
  --beige-dark: #C7B7A3;
  --beige-light: #E8D8C4;
  --accent-orange: #FF7F50;
  --accent-gold: #FFD700;
}

/* Botones y enlaces con glow dorado */
.card-btn, .secundario a, .footer a, .cuadro, .rectangulo {
  transition: all 0.3s ease;
}

/* Glow dorado al pasar el mouse */
.card-btn:hover {
  background-color: var(--accent-gold);
  color: var(--dark-brown);
  box-shadow: 0 0 15px var(--accent-gold), 0 0 30px var(--accent-gold);
}

.cuadro:hover, .rectangulo:hover {
  transform: translateY(-5px);
  box-shadow: 0 0 15px var(--accent-gold), 0 0 30px var(--accent-gold);
}

.secundario a:hover {
  background-color: var(--accent-gold);
  color: var(--dark-brown);
  box-shadow: 0 0 10px var(--accent-gold);
}

.footer a:hover {
  background-color: var(--accent-gold);
  color: var(--dark-brown);
  box-shadow: 0 0 10px var(--accent-gold);
}


:root {
  --dark-brown: #561C24;
  --medium-brown: #6D2932;
  --beige-dark: #C7B7A3;
  --beige-light: #E8D8C4;
  --accent-orange: #FF7F50;
  --accent-gold: #FFD700;
}

/* Reset general */
* { margin:0; padding:0; box-sizing:border-box; }

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-height:100vh;
  background: linear-gradient(135deg, var(--beige-light), var(--beige-dark));
  display:flex;
  flex-direction:column;
}

/* Header */
.principal {
  position:absolute; top:20px; right:20px;
}
.principal-links { list-style:none; display:flex; gap:15px; }
.principal-links li a {
  color: var(--dark-brown); text-decoration:none; font-weight:600; transition:0.3s;
}
.principal-links li a:hover { color: var(--accent-orange); }

/* Sidebar */
.secundario {
  width:250px; background-color: var(--dark-brown);
  color:white; height:100vh; padding:20px; position:fixed; top:0; left:0;
  display:flex; flex-direction:column; border-right:6px solid var(--accent-gold);
}
.secundario h2 { font-size:20px; margin-bottom:20px; font-weight:700; }
.secundario a {
  display:block; color:white; text-decoration:none; margin:10px 0;
  padding:10px; border-radius:5px; font-size:16px; font-weight:500;
  position:relative; transition:0.3s;
}
.secundario a::before {
  content:"•"; position:absolute; left:10px; top:50%; transform:translateY(-50%);
  font-size:20px; color:white;
}
.secundario a:hover { background-color: var(--accent-orange); }

/* Main content */
.contenido {
  flex-grow:1; padding:30px; margin-left:270px;
  background-color: var(--beige-light); border-radius:10px;
  box-shadow:0 0 15px rgba(0,0,0,0.15); text-align:center;
}

/* Bienvenida */
.bienvenida {
  background-color: var(--beige-dark); padding:20px;
  border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.15);
  margin-bottom:30px; max-width:600px; margin-left:auto; margin-right:auto;
}
.bienvenida h1 { font-size:32px; font-weight:600; color: var(--dark-brown); }
.bienvenida p { font-size:18px; color: var(--medium-brown); }

/* Contenedor */
.contenedor { display:flex; justify-content:flex-start; gap:30px; flex-wrap:wrap; }

/* Rectángulo */
.rectangulo {
  background-color: var(--beige-dark); padding:20px;
  text-align:center; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.15);
  width:300px; min-height:300px; transition: transform 0.3s, box-shadow 0.3s;
}
.rectangulo:hover {
  transform: translateY(-5px);
  box-shadow:0 8px 20px rgba(0,0,0,0.2);
}

/* Tarjeta */
.card {
  background-color: var(--beige-light); border-radius:10px;
  box-shadow:0 4px 12px rgba(0,0,0,0.1); width:400px; padding:20px;
  text-align:center; transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover {
  transform: translateY(-5px);
  box-shadow:0 10px 25px rgba(0,0,0,0.2);
}
.card-img { width:100%; height:200px; object-fit:cover; border-radius:8px; margin-bottom:15px; }
.card-titulo { font-size:1.5rem; color: var(--dark-brown); margin-bottom:10px; }
.card-descripcion { font-size:1rem; color: var(--medium-brown); margin-bottom:15px; }
.card-btn {
  background-color: var(--dark-brown); color:white; border:none; padding:10px 20px; border-radius:8px;
  font-weight:600; cursor:pointer; text-decoration:none; transition:0.3s;
}
.card-btn:hover { background-color: var(--accent-gold); color:var(--dark-brown); }

/* Cuadros */
.cuadros { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:20px; margin-top:40px; }
.cuadro { background-color: var(--beige-dark); border-radius:10px; padding:15px; text-align:center;
  box-shadow:0 4px 12px rgba(0,0,0,0.1); transition:0.3s; cursor:pointer;
}
.cuadro img { width:100%; border-radius:8px; }
.cuadro:hover { transform:translateY(-5px); box-shadow:0 8px 20px rgba(0,0,0,0.25); }
.cuadro h3 { margin-top:10px; color: var(--dark-brown); }

/* Footer */
.footer { position:fixed; bottom:20px; right:20px; }
.footer a {
  color:white; font-size:14px; text-decoration:none;
  background-color: var(--dark-brown); padding:10px 20px; border-radius:5px;
}
.footer a:hover { background-color: var(--accent-orange); color:white; }
</style>
</head>
<body>

<!-- Header -->
<div class="principal">
  <ul class="principal-links">
    <li><a href="../menu/inicio.php">Cerrar sesión</a></li>
    <li><a href="../menu/menuprincipal.php">Menú principal</a></li>
  </ul>
</div>

<!-- Sidebar -->
<div class="secundario">
  <h2>MENU CABLE E INTERNET</h2>
   <a href="m_clientes.php">MODIFICAR CLIENTES</a>
  <a href="equiposasignados.php">MODIFICAR EQUIPOS ASIGNADOS</a>
  <a href="factura.php">MODIFICAR FACTURAS</a>
  <a href="m_beneficios.php">MODIFICAR BENEFICIOS</a>
  <a href="m_equipos.php">MODIFICAR EQUIPOS</a>
  <a href="m_planes.php">MODIFICAR PLANES</a>
  <a href="m_tecnicos.php">MODIFICAR TECNICOS</a>
  <a href="m_tiendas.php">MODIFICAR TIENDAS</a>
  <a href="m_zonascobertura.php">MODIFICAR ZONAS DE COBERTURA</a>
  <a href="promociones.php">MODIFICAR PROMOCIONES</a>
  <a href="../menu/menuprincipal.php">Regresar</a>
</div>

<!-- Contenido -->
<div class="contenido">
  <div class="bienvenida">
    <h1>Bienvenido al Sistema de</h1>
<h1>MODIFICAR</h1>
<p>Selecciona una opción del panel izquierdo para comenzar.</p>
</div>

<div class="contenedor">
  <!-- Cuadro principal -->
  <div class="cuadros">
    <div class="cuadro">
      <img src="../img/clientes.png" alt="Clientes de Internet" style="width:80px; height:auto;">
      <h3>Modificar Clientes</h3>
    </div>
  </div>

  <!-- Rectángulo informativo -->
  <div class="rectangulo">
    <h2>¡Atención!</h2>
    <p>Puedes modificar registros de clientes, planes y contratos, ajustando detalles clave como velocidad, precios y zona de cobertura, asegurando información precisa y actualizada.</p>
    <img src="../img/info.png" alt="Información importante" style="width:100px; height:auto;">
  </div>

  
    <img src="../img/pdf.png" alt="Crear PDF" class="card-img" style="width:300px; height:auto;">
    <h2 class="card-titulo">CREAR PDFS</h2>
    <p class="card-descripcion">¿Deseas generar PDF de reportes en base a las modificaciones? Da click y comienza...</p>
    <a href="../reportes/menureportes.php" class="card-btn">Ver más →</a>
  </div>

<div class="footer">
  <a href="#" onclick="cerrarSesion()">Cerrar sesión</a>
</div>

<script>
function cerrarSesion(){
  localStorage.removeItem("logueado");
  window.location.href="../menu/login.php";
}
</script>
</body>
</html>
