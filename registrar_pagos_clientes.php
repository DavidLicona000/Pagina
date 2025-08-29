<?php
include("../menu/conectar.php"); // Ajusta la ruta según tu proyecto

// Si aún no enviaron el formulario
if (!isset($_POST["id_cliente"])) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pago</title>
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
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--beige-light);
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--dark-brown);
        }
        .box {
            background: var(--white-transparent);
            padding: 30px 35px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(86, 28, 36, 0.15);
            width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 25px;
            text-shadow: 1px 1px var(--beige-dark);
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            text-align: left;
            color: var(--dark-brown);
        }
        input[type="number"],
        input[type="text"],
        select {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 20px;
            border: 2px solid var(--medium-brown);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            color: var(--dark-brown);
        }
        input[type="number"]:focus,
        input[type="text"]:focus,
        select:focus {
            border-color: var(--accent-orange);
            outline: none;
            box-shadow: 0 0 6px var(--accent-orange);
        }
        button {
            width: 100%;
            padding: 14px 0;
            background: var(--accent-gold);
            color: var(--dark-brown);
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.3s ease;
            box-shadow: 0 4px 10px rgba(255, 215, 0, 0.4);
        }
        button:hover {
            background: var(--accent-orange);
            color: var(--beige-light);
            box-shadow: 0 6px 14px rgba(255, 127, 80, 0.6);
        }
        .btn-back {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 25px;
            background: var(--medium-brown);
            color: var(--beige-light);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 3px 7px rgba(109, 41, 50, 0.5);
            transition: background 0.3s ease;
            user-select: none;
        }
        .btn-back:hover {
            background: var(--dark-brown);
            color: var(--accent-gold);
            box-shadow: 0 5px 12px rgba(86, 28, 36, 0.8);
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Registrar Pago</h2>
        <form method="POST">
            <label for="id_cliente">ID Cliente:</label>
            <input type="number" id="id_cliente" name="id_cliente" required>

            <label for="id_factura">ID Factura:</label>
            <input type="number" id="id_factura" name="id_factura" required>

            <label for="monto">Monto:</label>
            <input type="number" step="0.01" id="monto" name="monto" required>

            <label for="metodo_pago">Método de Pago:</label>
            <select id="metodo_pago" name="metodo_pago" required>
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Transferencia">Transferencia</option>
            </select>

            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia">

            <button type="submit">Registrar Pago</button>
        </form>

        <a class="btn-back" href="../menu/menuprincipal2.php">⬅ Regresar al Menú</a>
    </div>
</body>
</html>
<?php
    exit();
}

// Procesar el formulario
$id_cliente = intval($_POST["id_cliente"]);
$id_factura = intval($_POST["id_factura"]);
$monto = floatval($_POST["monto"]);
$metodo_pago = $_POST["metodo_pago"];
$referencia = $_POST["referencia"] ?? '';

// Preparar e insertar el pago
$sql = "INSERT INTO pagos (id_factura, fecha_pago, monto, metodo_pago, referencia) 
        VALUES (?, NOW(), ?, ?, ?)";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}
$stmt->bind_param("idss", $id_factura, $monto, $metodo_pago, $referencia);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Pago Registrado</title>
        <style>
            :root {
              --dark-brown: #561C24;
              --medium-brown: #6D2932;
              --beige-light: #E8D8C4;
              --accent-orange: #FF7F50;
              --accent-gold: #FFD700;
            }
            body {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                background: var(--beige-light);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                color: var(--dark-brown);
                text-align: center;
                padding: 20px;
            }
            .message-box {
                background: white;
                padding: 30px 40px;
                border-radius: 15px;
                box-shadow: 0 8px 20px rgba(86, 28, 36, 0.15);
                max-width: 450px;
            }
            h2 {
                margin-bottom: 15px;
                text-shadow: 1px 1px var(--accent-gold);
            }
            p {
                font-size: 1.2rem;
                margin-bottom: 25px;
            }
            a {
                display: inline-block;
                padding: 12px 28px;
                background: var(--accent-gold);
                color: var(--dark-brown);
                text-decoration: none;
                font-weight: 700;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(255, 215, 0, 0.4);
                transition: background 0.3s ease;
                user-select: none;
            }
            a:hover {
                background: var(--accent-orange);
                color: white;
                box-shadow: 0 6px 14px rgba(255, 127, 80, 0.6);
            }
        </style>
    </head>
    <body>
        <div class="message-box">
            <h2>¡Pago Registrado!</h2>
            <p>Pago registrado correctamente para el cliente #'.htmlspecialchars($id_cliente).'.</p>
            <a href="../menu/menuprincipal2.php">⬅ Regresar al Menú</a>
        </div>
    </body>
    </html>';
} else {
    echo '<p>Error al registrar el pago: ' . htmlspecialchars($stmt->error) . '</p>';
    echo '<a href="../menu/menuprincipal2.php">⬅ Regresar al Menú</a>';
}

$stmt->close();
$conexion->close();
?>
