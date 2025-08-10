<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../view/login.php");
    exit;
}
require_once("../model/Conexion.php");
require_once("../model/ProductoModel.php"); // Asegúrate de incluir el modelo de productos
$pdo = Conexion::conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productos = json_decode($_POST['productos'], true);
    $total = floatval($_POST['total']);
    $descuento = floatval($_POST['descuento']);
    $metodo_pago = $_POST['metodo_pago'] ?? 'efectivo';
    $cliente_id = $_POST['cliente_id'] ?? null;
    $usuario_id = $_SESSION['usuario_id'] ?? null; // Asegúrate de guardar el id del usuario en la sesión

    // Guardar venta
    $stmt = $pdo->prepare("INSERT INTO ventas (total, descuento, metodo_pago, cliente_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$total, $descuento, $metodo_pago, $cliente_id]);
    $venta_id = $pdo->lastInsertId();

    // Guardar detalle de venta y registrar salida en inventario
    $stmt = $pdo->prepare("INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    foreach ($productos as $prod) {
        $stmt->execute([
            $venta_id,
            $prod['id'],
            $prod['cantidad'],
            $prod['precio']
        ]);
        // Registrar salida automática en inventario
        ProductoModel::registrarMovimiento($prod['id'], 'salida', $prod['cantidad'], 'Venta', $usuario_id);
    }

    // Redirige al ticket HTML para impresión
    header("Location: ../view/ticket_html.php?venta_id=$venta_id");
    exit;
} else {
    header("Location: ../view/venta.php");
    exit;
}
?>