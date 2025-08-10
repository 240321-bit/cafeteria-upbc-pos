<?php
require_once("../model/Conexion.php");
$pdo = Conexion::conectar();

$venta_id = $_GET['venta_id'] ?? null;
if (!$venta_id) exit('Venta no especificada.');

// Obtén datos de la venta
$stmt = $pdo->prepare("SELECT v.*, c.nombre as cliente FROM ventas v LEFT JOIN clientes c ON v.cliente_id = c.id WHERE v.id=?");
$stmt->execute([$venta_id]);
$venta = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT d.*, p.nombre FROM detalle_venta d JOIN productos p ON d.producto_id = p.id WHERE d.venta_id=?");
$stmt->execute([$venta_id]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .ticket {
            max-width: 350px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 24px;
            font-family: 'Courier New', Courier, monospace;
        }
        .ticket-header {
            text-align: center;
            margin-bottom: 12px;
        }
        .ticket-header img {
            width: 48px;
            margin-bottom: 6px;
        }
        .ticket-table th, .ticket-table td {
            font-size: 0.95rem;
            padding: 2px 4px;
        }
        .ticket-total {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: right;
        }
        @media print {
            body * { visibility: hidden; }
            .ticket, .ticket * { visibility: visible; }
            .ticket { margin: 0; box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="ticket">
    <div class="ticket-header">
        <img src="img/cup.png" alt="Logo">
        <h4>CAFETERÍA UPBC</h4>
        <div style="font-size:0.95rem;">
            <?php
            setlocale(LC_TIME, 'es_MX.UTF-8', 'es_MX', 'spanish', 'es_ES.UTF-8', 'es_ES');
            date_default_timezone_set('America/Tijuana');
            echo mb_convert_encoding(strftime('%A, %d de %B de %Y, %H:%M', strtotime($venta['fecha'])), 'UTF-8', 'UTF-8');
            ?>
        </div>
        <div style="font-size:0.95rem;">
            Cliente: <?= htmlspecialchars($venta['cliente'] ?? 'Público') ?>
        </div>
    </div>
    <table class="table ticket-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($productos as $prod): ?>
            <tr>
                <td><?= htmlspecialchars($prod['nombre']) ?></td>
                <td><?= $prod['cantidad'] ?></td>
                <td class="text-end">$<?= number_format($prod['precio_unitario'] * $prod['cantidad'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <div class="ticket-total">Total: $<?= number_format($venta['total'], 2) ?></div>
    <?php if ($venta['descuento'] > 0): ?>
    <div class="text-success ticket-total" style="font-size:1rem;">Descuento: -$<?= number_format($venta['descuento'], 2) ?></div>
    <?php endif; ?>
    <div class="ticket-total" style="font-size:1rem;">Método: <?= ucfirst($venta['metodo_pago']) ?></div>
    <div class="text-center mt-3" style="font-size:0.95rem;">¡Gracias por su compra!</div>
    <div class="text-center mt-2 no-print">
        <button class="btn btn-primary" onclick="window.print()">Imprimir ticket</button>
        <a href="venta.php" class="btn btn-secondary">Salir</a>
    </div>
</div>
</body>
</html>