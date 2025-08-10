<?php   
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: login.php");
    exit;
}
?>
<?php include "header.php"; ?>

<style>
    body { background: #f9f5f1; }
    .ventas-container {
        max-width: 1100px;
        margin: 40px auto 0 auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(120, 72, 32, 0.08);
        padding: 0;
    }
    .ventas-header {
        background: #7b4f22;
        color: #fff;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem 2rem 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .ventas-header h2 {
        margin: 0;
        font-weight: bold;
        letter-spacing: 1px;
        font-size: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ventas-table-area {
        padding: 2rem 2rem 1rem 2rem;
    }
    .table thead {
        background: #f5e9dd;
        color: #7b4f22;
        font-weight: bold;
    }
    .table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 0;
    }
    .badge-id {
        font-size: 1rem;
        background: #f5e9dd;
        color: #7b4f22;
        border-radius: 8px;
        padding: 0.5em 1em;
        font-weight: 500;
    }
    .btn-detalle {
        background: #7b4f22;
        color: #fff;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        padding: 0.35rem 1.2rem;
        box-shadow: none;
        transition: background 0.2s;
        outline: none;
    }
    .btn-detalle:hover, .btn-detalle:focus {
        background: #b88c5a;
        color: #fff;
        box-shadow: none;
    }
    .btn-volver {
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        background: #fff;
        color: #7b4f22;
        border: 2px solid #7b4f22;
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        transition: background 0.2s, color 0.2s;
        display: inline-block;
    }
    .btn-volver:hover {
        background: #7b4f22;
        color: #fff;
    }
    .modal-content {
        border-radius: 16px;
    }
    .modal-header {
        background: #7b4f22;
        color: #fff;
        border-radius: 16px 16px 0 0;
    }
    .modal-footer {
        background: #f9f5f1;
        border-radius: 0 0 16px 16px;
    }
    @media (max-width: 900px) {
        .ventas-table-area { padding: 1rem 1rem 0.5rem 1rem; }
        .ventas-header { padding: 1rem 1rem 1rem 1rem; }
    }
</style>

<div class="ventas-container">
    <div class="ventas-header">
        <h2><i class="bi bi-cash"></i> Ventas del Día</h2>
    </div>
    <div class="ventas-table-area">
        <div id="ventasDiaTable" class="table-responsive"></div>
    </div>
    <div style="padding: 0 2rem 2rem 2rem;">
        <a href="dashboard_gerente.php" class="btn btn-volver"><i class="bi bi-arrow-left"></i> Volver al Dashboard</a>
    </div>
</div>

<!-- Modal para el ticket -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ticketModalLabel"><i class="bi bi-receipt"></i> Ticket de Venta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="ticketContenido" style="background:#f9f5f1;">
        <!-- Aquí se carga el ticket -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="imprimirTicket()">Imprimir</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Cargar ventas del día vía AJAX
function cargarVentasDia() {
    fetch('../controller/VentasController.php?action=ventas_dia', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('ventasDiaTable').innerHTML = html;
    });
}

// Mostrar ticket en modal
function mostrarTicket(id) {
    fetch('../controller/VentasController.php?action=ticket&id=' + id, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('ticketContenido').innerHTML = html;
        var modal = new bootstrap.Modal(document.getElementById('ticketModal'));
        modal.show();
    });
}

// Imprimir ticket
function imprimirTicket() {
    var contenido = document.getElementById('ticketContenido').innerHTML;
    var ventana = window.open('', '', 'height=600,width=400');
    ventana.document.write('<html><head><title>Ticket</title>');
    ventana.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    ventana.document.write('</head><body style="background:#fff;">');
    ventana.document.write(contenido);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.print();
}

// Cargar ventas al iniciar y cada 10 segundos
cargarVentasDia();
setInterval(cargarVentasDia, 10000);
</script>

<?php include "footer.php"; ?>