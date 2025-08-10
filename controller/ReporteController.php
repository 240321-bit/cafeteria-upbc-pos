<?php
require_once("../model/ReporteModel.php");

function mostrarReporteVentas() {
    $fecha_inicio = $_GET['fecha_inicio'] ?? null;
    $fecha_fin = $_GET['fecha_fin'] ?? null;
    $ventas = ReporteModel::historialVentas($fecha_inicio, $fecha_fin);
    echo '<table class="table table-bordered"><thead><tr><th>Fecha</th><th>Total ($)</th></tr></thead><tbody>';
    foreach ($ventas as $v) {
        echo "<tr><td>{$v['fecha']}</td><td>$" . number_format($v['total'], 2) . "</td></tr>";
    }
    if (empty($ventas)) {
        echo '<tr><td colspan="2" class="text-center">Sin datos.</td></tr>';
    }
    echo '</tbody></table>';
}

function mostrarReporteEntradas() {
    $fecha_inicio = $_GET['fecha_inicio'] ?? null;
    $fecha_fin = $_GET['fecha_fin'] ?? null;
    $entradas = ReporteModel::entradasInventario($fecha_inicio, $fecha_fin);
    echo '<table class="table table-bordered"><thead><tr><th>Fecha</th><th>Producto</th><th class="cantidad">Cantidad</th><th>Motivo</th><th>Usuario</th></tr></thead><tbody>';
    foreach ($entradas as $e) {
        echo "<tr>
            <td>{$e['fecha']}</td>
            <td>{$e['producto']}</td>
            <td class=\"cantidad\">{$e['cantidad']}</td>
            <td>{$e['motivo']}</td>
            <td>{$e['usuario']}</td>
        </tr>";
    }
    if (empty($entradas)) {
        echo '<tr><td colspan="5" class="text-center">Sin datos.</td></tr>';
    }
    echo '</tbody></table>';
}

function mostrarReporteSalidas() {
    $fecha_inicio = $_GET['fecha_inicio'] ?? null;
    $fecha_fin = $_GET['fecha_fin'] ?? null;
    $salidas = ReporteModel::salidasInventario($fecha_inicio, $fecha_fin);
    echo '<table class="table table-bordered"><thead><tr><th>Fecha</th><th>Producto</th><th class="cantidad">Cantidad</th><th>Motivo</th><th>Usuario</th></tr></thead><tbody>';
    foreach ($salidas as $s) {
        echo "<tr>
            <td>{$s['fecha']}</td>
            <td>{$s['producto']}</td>
            <td class=\"cantidad\">{$s['cantidad']}</td>
            <td>{$s['motivo']}</td>
            <td>{$s['usuario']}</td>
        </tr>";
    }
    if (empty($salidas)) {
        echo '<tr><td colspan="5" class="text-center">Sin datos.</td></tr>';
    }
    echo '</tbody></table>';
}

// AJAX recarga de reportes y filtros
if (isset($_GET['ajax']) && $_GET['ajax'] == 1 && isset($_GET['reporte'])) {
    if ($_GET['reporte'] == 'ventas') mostrarReporteVentas();
    if ($_GET['reporte'] == 'entradas') mostrarReporteEntradas();
    if ($_GET['reporte'] == 'salidas') mostrarReporteSalidas();
    exit;
}

// Exportar a Excel (con filtros)
if (isset($_GET['excel']) && isset($_GET['reporte'])) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=reporte_{$_GET['reporte']}.xls");
    if ($_GET['reporte'] == 'ventas') mostrarReporteVentas();
    if ($_GET['reporte'] == 'entradas') mostrarReporteEntradas();
    if ($_GET['reporte'] == 'salidas') mostrarReporteSalidas();
    exit;
}