<?php
session_start();
require_once("../model/VentaModel.php");

// Solo permitir peticiones AJAX
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    http_response_code(403);
    exit('Acceso no permitido');
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'ventas_dia':
        $ventas = VentaModel::ventasDelDia();
        ?>
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Método de Pago</th>
                    <th>Descuento</th>
                    <th>ID Cliente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ventas as $venta): ?>
                <tr>
                    <td><span class="badge-id"><?= htmlspecialchars($venta['id']) ?></span></td>
                    <td><?= htmlspecialchars($venta['fecha']) ?></td>
                    <td>$<?= number_format($venta['total'], 2) ?></td>
                    <td><?= htmlspecialchars($venta['metodo_pago']) ?></td>
                    <td>$<?= number_format($venta['descuento'], 2) ?></td>
                    <td><?= htmlspecialchars($venta['cliente_id']) ?></td>
                    <td>
                        <button class="btn btn-detalle btn-sm" onclick="mostrarTicket(<?= $venta['id'] ?>)">
                            <i class="bi bi-receipt"></i> Detalle
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($ventas)): ?>
                <tr><td colspan="7" class="text-center">No hay ventas registradas hoy.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
        break;

    case 'ticket':
        $id = $_GET['id'] ?? 0;
        $venta = VentaModel::obtenerVentaPorId($id);

        // Obtener nombre del cliente
        $cliente = 'Público';
        if ($venta && $venta['cliente_id']) {
            $pdo = new PDO("mysql:host=localhost;dbname=cafeteriaupbc;charset=utf8", "root", "");
            $stmt = $pdo->prepare("SELECT nombre FROM clientes WHERE id=?");
            $stmt->execute([$venta['cliente_id']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && $row['nombre']) $cliente = $row['nombre'];
        }

        $productos = VentaModel::obtenerProductosVenta($id);

        if ($venta) {
            ?>
            <div class="ticket" style="max-width:350px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.08);padding:24px;font-family:'Courier New',Courier,monospace;">
                <div class="ticket-header" style="text-align:center;margin-bottom:12px;">
                    <img src="/CafeteriaUPBC/view/img/cup.png" alt="Logo" style="width:48px;margin-bottom:6px;">
                    <h4 style="margin:0;">CAFETERÍA UPBC</h4>
                    <div style="font-size:0.95rem;">
                        <?php
                        setlocale(LC_TIME, 'es_MX.UTF-8', 'es_MX', 'spanish', 'es_ES.UTF-8', 'es_ES');
                        date_default_timezone_set('America/Tijuana');
                        echo mb_convert_encoding(strftime('%A, %d de %B de %Y, %H:%M', strtotime($venta['fecha'])), 'UTF-8', 'UTF-8');
                        ?>
                    </div>
                    <div style="font-size:0.95rem;">
                        Cliente: <?= htmlspecialchars($cliente) ?>
                    </div>
                </div>
                <table class="table ticket-table" style="margin-bottom:0;">
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
                            <td class="text-end">$<?= number_format($prod['precio'] * $prod['cantidad'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <hr>
                <div class="ticket-total" style="font-size:1.2rem;font-weight:bold;text-align:right;">Total: $<?= number_format($venta['total'], 2) ?></div>
                <?php if ($venta['descuento'] > 0): ?>
                <div class="text-success ticket-total" style="font-size:1rem;text-align:right;">Descuento: -$<?= number_format($venta['descuento'], 2) ?></div>
                <?php endif; ?>
                <div class="ticket-total" style="font-size:1rem;text-align:right;">Método: <?= ucfirst($venta['metodo_pago']) ?></div>
                <div class="text-center mt-3" style="font-size:0.95rem;">¡Gracias por su compra!</div>
                <div class="text-center mt-2">
                    <button class="btn btn-primary" onclick="window.print()">Imprimir ticket</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                </div>
            </div>
            <?php
        } else {
            echo "<div class='text-danger'>Venta no encontrada.</div>";
        }
        break;

    default:
        http_response_code(400);
        echo "Acción no válida";
        break;
}