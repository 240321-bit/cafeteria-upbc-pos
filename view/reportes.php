<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: login.php");
    exit;
}
include "header.php";
require_once("../controller/ReporteController.php");
?>
<style>
body { background: #f9f5f1; }
.reporte-container {
    max-width: 1100px;
    margin: 40px auto 0 auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(120, 72, 32, 0.08);
    padding: 0;
}
.reporte-header {
    background: #7b4f22;
    color: #fff;
    border-radius: 16px 16px 0 0;
    padding: 1.5rem 2rem 1rem 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}
.reporte-header h2 {
    margin: 0;
    font-weight: bold;
    letter-spacing: 1px;
    font-size: 2rem;
    display: flex;
    align-items: center;
    gap: 12px;
    justify-content: center;
}
.reporte-section {
    margin: 2.5rem auto 0 auto;
    background: #f5e9dd;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(120, 72, 32, 0.06);
    padding: 2rem 2rem 1.5rem 2rem;
    max-width: 900px;
    text-align: center;
}
.reporte-titulo {
    font-size: 1.2rem;
    font-weight: bold;
    margin: 0 0 1.2rem 0;
    color: #7b4f22;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
    text-align: center;
}
.reporte-actions {
    margin-bottom: 1.2rem;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
}
.filtro-fechas {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
}
.filtro-fechas label {
    margin-bottom: 0;
    font-weight: 500;
    color: #7b4f22;
}
.table-responsive {
    margin-top: 1rem;
}
.table thead {
    background: #f9f5f1;
    color: #7b4f22;
    font-weight: bold;
}
.table {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 0;
    width: 100%;
}
.table td.cantidad,
.table th.cantidad {
    text-align: center;
    vertical-align: middle;
    min-width: 80px;
}
.table th, .table td {
    text-align: center;
    vertical-align: middle;
}
.btn-exportar {
    background: #ffc107;
    color: #7b4f22;
    border-radius: 8px;
    font-weight: 500;
    border: none;
}
.btn-exportar:hover {
    background: #ffb300;
    color: #fff;
}
.btn-recargar {
    background: #7b4f22;
    color: #fff;
    border-radius: 8px;
    font-weight: 500;
    border: none;
}
.btn-recargar:hover {
    background: #b88c5a;
    color: #fff;
}
.btn-volver {
    background: #fff;
    color: #7b4f22;
    border: 2px solid #7b4f22;
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1.5rem;
    transition: background 0.2s, color 0.2s;
    display: inline-block;
    text-decoration: none;
    margin-bottom: 1.5rem;
}
.btn-volver:hover, .btn-volver:focus {
    background: #7b4f22;
    color: #fff;
    text-decoration: none;
}
.btn-filtrar {
    background: #388e3c;
    color: #fff;
    border-radius: 8px;
    font-weight: 500;
    border: none;
    padding: 0.4rem 1.2rem;
    transition: background 0.2s;
}
.btn-filtrar:hover, .btn-filtrar:focus {
    background: #256029;
    color: #fff;
}
@media (max-width: 900px) {
    .reporte-section { padding: 1rem 0.5rem 1rem 0.5rem; }
}
</style>

<div class="container pt-4" style="text-align:center;">
    <a href="dashboard_gerente.php" class="btn btn-volver mb-3">
        <i class="bi bi-arrow-left"></i> Volver al Dashboard
    </a>
</div>

<div class="reporte-container">
    <div class="reporte-header">
        <h2><i class="bi bi-bar-chart"></i> Reportes del Sistema</h2>
    </div>
    <div class="reporte-section">
        <div class="reporte-titulo"><i class="bi bi-cash-coin"></i> Histórico de Ventas</div>
        <div class="reporte-actions">
            <form class="filtro-fechas" id="formFiltroVentas" onsubmit="filtrarReporte(event, 'ventas')">
                <label for="fecha_inicio_ventas">De:</label>
                <input type="date" id="fecha_inicio_ventas" name="fecha_inicio">
                <label for="fecha_fin_ventas">a</label>
                <input type="date" id="fecha_fin_ventas" name="fecha_fin">
                <button type="submit" class="btn btn-filtrar"><i class="bi bi-funnel"></i> Filtrar</button>
            </form>
            <button class="btn btn-exportar" onclick="exportarExcel('ventas')"><i class="bi bi-file-earmark-excel"></i> Exportar Excel</button>
            <button class="btn btn-recargar" onclick="recargarReporte('ventas')"><i class="bi bi-arrow-clockwise"></i> Recargar</button>
        </div>
        <div class="table-responsive" id="reporteVentas"><?php mostrarReporteVentas(); ?></div>
    </div>
    <div class="reporte-section">
        <div class="reporte-titulo"><i class="bi bi-box-arrow-in-down"></i> Entradas de Inventario</div>
        <div class="reporte-actions">
            <form class="filtro-fechas" id="formFiltroEntradas" onsubmit="filtrarReporte(event, 'entradas')">
                <label for="fecha_inicio_entradas">De:</label>
                <input type="date" id="fecha_inicio_entradas" name="fecha_inicio">
                <label for="fecha_fin_entradas">a</label>
                <input type="date" id="fecha_fin_entradas" name="fecha_fin">
                <button type="submit" class="btn btn-filtrar"><i class="bi bi-funnel"></i> Filtrar</button>
            </form>
            <button class="btn btn-exportar" onclick="exportarExcel('entradas')"><i class="bi bi-file-earmark-excel"></i> Exportar Excel</button>
            <button class="btn btn-recargar" onclick="recargarReporte('entradas')"><i class="bi bi-arrow-clockwise"></i> Recargar</button>
        </div>
        <div class="table-responsive" id="reporteEntradas"><?php mostrarReporteEntradas(); ?></div>
    </div>
    <div class="reporte-section">
        <div class="reporte-titulo"><i class="bi bi-box-arrow-up"></i> Salidas de Inventario</div>
        <div class="reporte-actions">
            <form class="filtro-fechas" id="formFiltroSalidas" onsubmit="filtrarReporte(event, 'salidas')">
                <label for="fecha_inicio_salidas">De:</label>
                <input type="date" id="fecha_inicio_salidas" name="fecha_inicio">
                <label for="fecha_fin_salidas">a</label>
                <input type="date" id="fecha_fin_salidas" name="fecha_fin">
                <button type="submit" class="btn btn-filtrar"><i class="bi bi-funnel"></i> Filtrar</button>
            </form>
            <button class="btn btn-exportar" onclick="exportarExcel('salidas')"><i class="bi bi-file-earmark-excel"></i> Exportar Excel</button>
            <button class="btn btn-recargar" onclick="recargarReporte('salidas')"><i class="bi bi-arrow-clockwise"></i> Recargar</button>
        </div>
        <div class="table-responsive" id="reporteSalidas"><?php mostrarReporteSalidas(); ?></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function recargarReporte(tipo) {
    fetch('../controller/ReporteController.php?ajax=1&reporte=' + tipo)
        .then(res => res.text())
        .then(html => {
            document.getElementById('reporte' + tipo.charAt(0).toUpperCase() + tipo.slice(1)).innerHTML = html;
        });
}
function exportarExcel(tipo) {
    window.open('../controller/ReporteController.php?excel=1&reporte=' + tipo, '_blank');
}
function filtrarReporte(e, tipo) {
    e.preventDefault();
    let inicio = document.getElementById('fecha_inicio_' + tipo).value;
    let fin = document.getElementById('fecha_fin_' + tipo).value;
    let params = new URLSearchParams({
        ajax: 1,
        reporte: tipo,
        fecha_inicio: inicio,
        fecha_fin: fin
    });
    fetch('../controller/ReporteController.php?' + params.toString())
        .then(res => res.text())
        .then(html => {
            document.getElementById('reporte' + tipo.charAt(0).toUpperCase() + tipo.slice(1)).innerHTML = html;
        });
}
</script>
<?php include "footer.php"; ?>