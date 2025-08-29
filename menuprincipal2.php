<?php
session_start();
include("conexion1.php"); // o donde sea que esté tu conexión

// Verificación de sesión:
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login2.php");
    exit();
}

$id = $_SESSION['usuario_id'];
$stmt = $conexion->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>FULL INFINITY - Portal Clientes</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>

<style>
/* ====== TU CSS ORIGINAL ====== */
:root {
  --dark-brown: #561C24;
  --medium-brown: #6D2932;
  --beige-dark: #C7B7A3;
  --beige-light: #E8D8C4;
  --accent-orange: #FF7F50;
  --accent-gold: #FFD700;
}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Arial, sans-serif;background:var(--beige-light);color:var(--dark-brown);line-height:1.6;}
header{display:flex;justify-content:space-between;align-items:center;background:var(--dark-brown);color:var(--beige-light);padding:10px 20px;position:sticky;top:0;z-index:1000;box-shadow:0 2px 10px rgba(0,0,0,0.2);}
header .logo{font-size:1.7em;font-weight:bold;color:var(--accent-gold);}
header nav ul{list-style:none;display:flex;gap:15px;}
header nav a{color:var(--beige-light);text-decoration:none;font-weight:bold;transition:.3s;}
header nav a:hover{color:var(--accent-orange);}
.btn-login{background:var(--accent-orange);color:var(--beige-light);border:none;padding:8px 15px;border-radius:5px;cursor:pointer;transition:0.3s;}
.btn-login:hover{background:var(--accent-gold);color:var(--dark-brown);}
.carousel{position:relative;width:100%;overflow:hidden;}
.carousel .slides{display:flex;transition:transform .5s ease;}
.carousel .slide{min-width:100%;}
.carousel .slide img{width:100%;height:auto;display:block;}
.carousel .controls{position:absolute;top:50%;width:100%;display:flex;justify-content:space-between;transform:translateY(-50%);}
.controls span{cursor:pointer;font-size:2em;padding:10px;background:rgba(0,0,0,0.3);color:var(--beige-light);border-radius:50%;}
.acciones{display:flex;flex-wrap:wrap;gap:20px;justify-content:center;padding:40px 20px;}
.acciones .card{background:var(--beige-light);border:1px solid var(--beige-dark);padding:20px;width:250px;text-align:center;border-radius:10px;box-shadow:0 4px 8px rgba(0,0,0,0.1);transition:.3s;}
.acciones .card:hover{transform:translateY(-5px);box-shadow:0 8px 15px rgba(0,0,0,0.2);}
.acciones button{background:var(--accent-orange);color:var(--beige-light);border:none;padding:10px 20px;margin-top:10px;border-radius:5px;cursor:pointer;font-weight:bold;}
.acciones button:hover{background:var(--accent-gold);color:var(--dark-brown);}
.beneficios{padding:60px 20px;text-align:center;background:linear-gradient(135deg,var(--beige-dark),var(--beige-light));}
.beneficios h2{margin-bottom:30px;color:var(--accent-gold);font-size:2em;}
.beneficios .cards{display:flex;flex-wrap:wrap;gap:20px;justify-content:center;}
.beneficios .card{background:var(--beige-light);padding:20px;border-radius:12px;width:250px;box-shadow:0 4px 10px rgba(0,0,0,0.15);transition:.3s;}
.beneficios .card:hover{transform:translateY(-5px);}
footer{background:var(--medium-brown);color:var(--beige-light);padding:40px 20px 20px;}
.footer-container{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;margin-bottom:30px;}
.footer-col h4{margin-bottom:15px;color:var(--accent-gold);font-size:1.2em;}
.footer-col ul{list-style:none;}
.footer-col ul li{margin-bottom:10px;}
.footer-col ul li a{color:var(--beige-light);text-decoration:none;transition:.3s;}
.footer-col ul li a:hover{color:var(--accent-orange);}
.footer-bottom{border-top:1px solid var(--beige-dark);padding-top:15px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;}
.footer-bottom .socials a img{width:24px;margin-left:10px;transition:.3s;}
.footer-bottom .socials a img:hover{transform:scale(1.2);}
.cuadros { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin-top: 30px;}
.cuadro { background: var(--beige-light); border: 2px solid var(--beige-dark); padding: 20px; width: 500px; height: 500px; text-align: center; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.15); cursor: pointer; transition: transform .3s, box-shadow .3s;}
.cuadro:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.25);}
.cuadro img { height: 400px; width: 400px; margin-bottom: 10px;}
.info { margin-top: 25px; padding: 20px; background: var(--beige-dark); color: var(--dark-brown); border-radius: 10px; max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: 0 4px 8px rgba(0,0,0,0.2);}
.full-infinity { padding: 60px 20px; background: linear-gradient(135deg, var(--beige-dark), var(--beige-light));}
.full-container { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 40px; max-width: 1100px; margin: auto;}
.full-texto { flex: 1 1 400px; color: var(--dark-brown);}
.full-texto h2 { font-size: 2.2em; color: var(--accent-gold); margin-bottom: 20px;}
.full-texto p { font-size: 1.1em; margin-bottom: 20px;}
.full-texto ul { list-style: none; margin-bottom: 20px;}
.full-texto ul li { margin-bottom: 10px; font-weight: bold;}
.full-texto button { background: var(--accent-orange); color: var(--beige-light); border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 1em; font-weight: bold; transition: 0.3s;}
.full-texto button:hover { background: var(--accent-gold); color: var(--dark-brown);}
.full-imagen { flex: 1 1 400px;}
.full-imagen img { width: 100%; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);}
.hero{padding:60px 20px;text-align:center;background:var(--dark-brown);color:var(--beige-light);}
.hero h1{font-size:2.5em;margin-bottom:20px;}
.hero span{color:var(--accent-gold);}
.stats{display:flex;justify-content:center;gap:50px;padding:60px 20px;background:var(--beige-dark);}
.stats .box{text-align:center;}
.stats .box h3{font-size:2em;color:var(--accent-orange);}
.testimonios{padding:60px 20px;background:var(--beige-light);}
.testimonios h2{text-align:center;color:var(--accent-gold);margin-bottom:30px;}
.swiper{width:100%;max-width:600px;margin:auto;}
.swiper-slide{background:var(--beige-dark);padding:20px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.2);}
</style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="logo"> FULL INFINITY</div>
  <nav>
    <ul>
      <li><a href="#" onclick="cambiarCarrusel('Personas')">Personas</a></li>
      <li><a href="#" onclick="cambiarCarrusel('Empresas')">Empresas</a></li>
      <li><a href="#" onclick="cambiarCarrusel('Institucional')">Institucional</a></li>
      <li><a href="#" onclick="cambiarCarrusel('Servicios')">Servicios</a></li>
      <li><a href="#" onclick="cambiarCarrusel('Beneficios')">Beneficios</a></li>
      <li><a href="#" onclick="cambiarCarrusel('Tienda')">Tienda</a></li>
      <li><a href="#" onclick="cambiarCarrusel('Asistencia')">Asistencia</a></li>
    </ul>
  </nav>
  <button class="btn-login" onclick="location.href='login2.php'"> CERRAR SESION </button>
</header>

<!-- HERO -->
<section class="hero">
  <h1>Bienvenido a <span>FULL INFINITY</span></h1>
  <p><span id="typed"></span></p>
</section>

<!-- CARRUSEL -->
<section class="carousel">
  <div class="slides"></div>
  <div class="controls">
    <span class="prev">&#10094;</span>
    <span class="next">&#10095;</span>
  </div>
</section>

<!-- ACCIONES -->
<section class="acciones">
  <div class="card">
    <img src="../img/18.png" width="100" alt="Facturas"><br>
    <h3>Consultar Facturas</h3>
    <p>Revisa y descarga tu factura.</p>
    <button onclick="location.href='../consultar/consultar_facturas_clientes.php'">VER FACTURAS</button>
  </div>
  <div class="card">
    <img src="../img/21.png" width="100" alt="Planes"><br>
    <h3>Pagos</h3>
    <p>Haz tu pago en linea facilmente.</p>
    <button onclick="location.href='../consultar/consultar_pagos_clientes.php'">PAGA AHORA</button>
  </div>
  <div class="card">
    <img src="../img/19.png" width="100" alt="Planes"><br>
    <h3>Planes</h3>
    <p>Explora nuestros planes disponibles.</p>
    <button onclick="location.href='../consultar/planes.php'">CONSULTAR PLANES</button>
  </div>
  <div class="card">
    <img src="../img/20.png" width="100" alt="Soporte"><br>
    <h3>Soporte</h3>
    <p>¿Problemas? Estamos para ayudarte.</p>
    <button>IR A SOPORTE</button>
  </div>
</section>

<!-- BENEFICIOS INTERACTIVOS -->
<section class="beneficios">
  <h2>Beneficios para ti</h2>
  <div class="cuadros">
    <div class="cuadro" onclick="mostrarInformacion('beneficio1')">
      <img src="../img/30.jpeg" alt="Internet rápido">
      <h4>Da click para más información...</h4>
    </div>
    <div class="cuadro" onclick="mostrarInformacion('beneficio2')">
      <img src="../img/24.jpeg" alt="Promociones">
      <h4>Da click para más información...</h4>
    </div>
    <div class="cuadro" onclick="mostrarInformacion('beneficio3')">
      <img src="../img/23.jpeg" alt="Atención 24/7">
      <h4>Da click para más información...</h4>
    </div>
  </div>
  <div id="info" class="info">
    <p>Selecciona un beneficio para ver más detalles.</p>
  </div>
</section>

<!-- STATS -->
<section class="stats">
  <div class="box"><h3 data-count="1200">0</h3><p>Clientes felices</p></div>
  <div class="box"><h3 data-count="300">0</h3><p>Proyectos</p></div>
  <div class="box"><h3 data-count="24">0</h3><p>Soporte 24/7</p></div>
</section>

<!-- TESTIMONIOS -->
<section class="testimonios">
  <h2>Lo que dicen nuestros clientes</h2>
  <div class="swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">"Excelente servicio y atención rápida."</div>
      <div class="swiper-slide">"Internet estable y promociones constantes."</div>
      <div class="swiper-slide">"El soporte técnico siempre me ayuda."</div>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</section>

<!-- FULL INFINITY -->
<section class="full-infinity">
  <div class="full-container">
    <div class="full-texto">
      <h2>FULL INFINITY</h2>
      <p>Disfruta de la mejor experiencia con todos nuestros servicios unificados en un solo plan.</p>
      <ul>
        <li>📶 Internet de alta velocidad</li>
        <li>📱 Planes móviles ilimitados</li>
        <li>📺 Televisión HD</li>
        <li>🎁 Beneficios exclusivos</li>
      </ul>
      <button>CONTRÁTALO YA</button>
    </div>
    <div class="full-imagen">
      <img src="../img/25.jpeg" alt="Full Infinity">
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-container">
    <div class="footer-col">
      <h4>Personas</h4>
      <ul>
        <li><a href="#">Planes móviles</a></li>
        <li><a href="#">Internet hogar</a></li>
        <li><a href="#">Televisión</a></li>
        <li><a href="#">Promociones</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Empresas</h4>
      <ul>
        <li><a href="#">Soluciones corporativas</a></li>
        <li><a href="#">Internet empresarial</a></li>
        <li><a href="#">Telefonía fija</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Institucional</h4>
      <ul>
        <li><a href="#">Quiénes somos</a></li>
        <li><a href="#">Sala de prensa</a></li>
        <li><a href="#">Responsabilidad social</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Ayuda</h4>
      <ul>
        <li><a href="#">Soporte técnico</a></li>
        <li><a href="#">Facturación</a></li>
        <li><a href="#">Cobertura</a></li>
        <li><a href="#">Contáctanos</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 FULL INFINITY - Todos los derechos reservados</p>
    <div class="socials">
      <a href="#"><img src="../img/26.png" alt="Facebook"></a>
      <a href="#"><img src="../img/27.png" alt="Twitter"></a>
      <a href="#"><img src="../img/28.png" alt="Instagram"></a>
      <a href="#"><img src="../img/29.png" alt="YouTube"></a>
    </div>
  </div>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>

<script>
// ====== Carruseles dinámicos ======
const carruseles = {
  "Personas": ["../img/4.jpeg","../img/9.png","../img/12.png"],
  "Empresas": ["../img/37.png","../img/39.jpeg","../img/38.jpg"],
  "Institucional": ["../img/38.jpg","../img/36.jpeg","../img/37.png"],
  "Servicios": ["../img/37.png","../img/39.jpeg","../img/38.jpg"],
  "Beneficios": ["../img/38.jpg","../img/36.jpeg","../img/37.png"],
  "Tienda": ["../img/37.png","../img/39.jpeg","../img/38.jpg"],
  "Asistencia": ["../img/38.jpg","../img/36.jpeg","../img/37.png"]
};

let currentIndex = 0;
let currentSlides = [];
const slidesContainer = document.querySelector(".slides");

function updateCarousel(){
  slidesContainer.style.transform = `translateX(-${currentIndex*100}%)`;
}

function cambiarCarrusel(seccion){
  const imagenes = carruseles[seccion];
  currentIndex = 0;
  currentSlides = imagenes;
  slidesContainer.innerHTML = imagenes.map(img=>`<div class="slide"><img src="${img}" alt="${seccion}"></div>`).join('');
  updateCarousel();
}

// Controles
document.querySelector(".next").addEventListener("click",()=>{
  currentIndex=(currentIndex+1)%currentSlides.length;
  updateCarousel();
});
document.querySelector(".prev").addEventListener("click",()=>{
  currentIndex=(currentIndex-1+currentSlides.length)%currentSlides.length;
  updateCarousel();
});

// Auto-play
setInterval(()=>{
  if(currentSlides.length>0){
    currentIndex=(currentIndex+1)%currentSlides.length;
    updateCarousel();
  }
},5000);

// Inicializar
document.addEventListener("DOMContentLoaded",()=>{cambiarCarrusel('Personas');});

// ====== Info Beneficios ======
function mostrarInformacion(b){
  const info = document.getElementById("info");
  if(b==="beneficio1") info.innerHTML="<p>Internet de alta velocidad para tu hogar y oficina, sin interrupciones.</p>";
  if(b==="beneficio2") info.innerHTML="<p>Promociones exclusivas y descuentos mensuales para nuestros clientes.</p>";
  if(b==="beneficio3") info.innerHTML="<p>Soporte técnico disponible 24/7 para resolver cualquier inconveniente.</p>";
}

// ====== Typed.js ======
new Typed('#typed',{
  strings:["Bienvenido a Full Infinity","Disfruta de nuestros servicios premium","Explora nuestros planes y beneficios"],
  typeSpeed:50,
  backSpeed:25,
  loop:true
});

// ====== Contador ======
const counters=document.querySelectorAll(".stats h3");
counters.forEach(counter=>{
  counter.innerText="0";
  const updateCounter=()=>{
    const target=parseInt(counter.getAttribute("data-count"));
    const c=parseInt(counter.innerText);
    const increment=Math.ceil(target/200);
    if(c<target){
      counter.innerText=c+increment;
      setTimeout(updateCounter,10);
    }else counter.innerText=target;
  };
  updateCounter();
});

// ====== Swiper Testimonios ======
const swiper = new Swiper('.swiper',{loop:true,pagination:{el:'.swiper-pagination',clickable:true}});
</script>

</body>
</html>