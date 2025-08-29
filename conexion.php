<?php
session_start();
include("conectar.php");

// Verificar usuario logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../menu/login2.php");
    exit();
}

$id_cliente = $_SESSION['usuario_id'];
$mensaje = "";

// Obtener contratos del cliente para validar la existencia
$sqlContratos = "SELECT id_contrato FROM contratos WHERE id_cliente = ?";
$stmtContratos = $conexion->prepare($sqlContratos);
$stmtContratos->bind_param("i", $id_cliente);
$stmtContratos->execute();
$resultContratos = $stmtContratos->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $id_contrato = $_POST['id_contrato'];
    $fecha_emision = $_POST['fecha_emision'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $subtotal = $_POST['subtotal'];
    $impuestos = $_POST['impuestos'];

    // Calcular el total como (subtotal * impuestos / 100) + subtotal
    $total = ($subtotal * $impuestos / 100) + $subtotal;

    // Validar que el contrato pertenezca al usuario
    $sqlValida = "SELECT * FROM contratos WHERE id_contrato = ? AND id_cliente = ?";
    $stmtValida = $conexion->prepare($sqlValida);
    $stmtValida->bind_param("ii", $id_contrato, $id_cliente);
    $stmtValida->execute();
    $resValida = $stmtValida->get_result();

    if ($resValida->num_rows === 1) {
        // Insertar factura
        $estado = $_POST['estado'];
        $sqlInsertFactura = "INSERT INTO facturas (id_contrato, fecha_emision, fecha_vencimiento, subtotal, impuestos, total, estado) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertFactura = $conexion->prepare($sqlInsertFactura);
        $stmtInsertFactura->bind_param("issddds", $id_contrato, $fecha_emision, $fecha_vencimiento, $subtotal, $impuestos, $total, $estado);

        if ($stmtInsertFactura->execute()) {
            $mensaje = "Factura registrada correctamente.";

            // Actualizar estado del contrato (si es necesario)
            // Por ejemplo, cambiar el estado del contrato a "Finalizado" después de registrar la factura
            $sqlActualizarContrato = "UPDATE contratos SET estado = 'Finalizado' WHERE id_contrato = ?";
            $stmtActualizarContrato = $conexion->prepare($sqlActualizarContrato);
            $stmtActualizarContrato->bind_param("i", $id_contrato);
            $stmtActualizarContrato->execute();

        } else {
            $mensaje = "Error al registrar factura: " . $stmtInsertFactura->error;
            // Mostrar error de SQL si algo falla en la inserción
            echo "Error SQL: " . $stmtInsertFactura->error;
            exit(); // Detener ejecución para ver el error
        }
    } else {
        $mensaje = "Contrato inválido o no pertenece a este usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Factura</title>
</head>
<body>
    <h2>Registrar Nueva Factura</h2>

    <?php if ($mensaje): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="id_contrato">Contrato (ID):</label>
        <input type="number" name="id_contrato" id="id_contrato" required><br><br>

        <label for="fecha_emision">Fecha de emisión:</label>
        <input type="datetime-local" name="fecha_emision" id="fecha_emision" required><br><br>

        <label for="fecha_vencimiento">Fecha de vencimiento:</label>
        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" required><br><br>

        <label for="subtotal">Subtotal:</label>
        <input type="number" step="0.01" name="subtotal" id="subtotal" required oninput="calcularTotal()"><br><br>

        <label for="impuestos">Impuestos (porcentaje):</label>
        <input type="number" step="0.01" name="impuestos" id="impuestos" required oninput="calcularTotal()"><br><br>

        <label for="total">Total:</label>
        <input type="number" step="0.01" name="total" id="total" readonly><br><br>

        <label for="estado">Estado:</label>
        <select name="estado" id="estado" required>
            <option value="Pendiente">Pendiente</option>
            <option value="Pagada">Pagada</option>
            <option value="Vencida">Vencida</option>
        </select><br><br>

        <button type="submit">Registrar Factura</button>
    </form>

    <br>
    <a href="../consultar/consultar_facturas_clientes.php">Volver a consulta de facturas</a>

    <script>
        function calcularTotal() {
            var subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
            var impuestos = parseFloat(document.getElementById('impuestos').value) || 0;
            var total = (subtotal * impuestos / 100) + subtotal; // Calcula impuestos correctamente
            document.getElementById('total').value = total.toFixed(2);
        }
    </script>
</body>
</html>
