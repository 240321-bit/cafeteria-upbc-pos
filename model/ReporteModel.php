<?php
require_once("Conexion.php");

class ReporteModel {
    public static function historialVentas($fecha_inicio = null, $fecha_fin = null) {
        $pdo = Conexion::conectar();
        $where = [];
        $params = [];
        if ($fecha_inicio) {
            $where[] = "DATE(fecha) >= ?";
            $params[] = $fecha_inicio;
        }
        if ($fecha_fin) {
            $where[] = "DATE(fecha) <= ?";
            $params[] = $fecha_fin;
        }
        $sql = "SELECT DATE(fecha) as fecha, SUM(total) as total FROM ventas";
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " GROUP BY DATE(fecha) ORDER BY fecha DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function entradasInventario($fecha_inicio = null, $fecha_fin = null) {
        $pdo = Conexion::conectar();
        $where = ["m.tipo = 'entrada'"];
        $params = [];
        if ($fecha_inicio) {
            $where[] = "DATE(m.fecha) >= ?";
            $params[] = $fecha_inicio;
        }
        if ($fecha_fin) {
            $where[] = "DATE(m.fecha) <= ?";
            $params[] = $fecha_fin;
        }
        $sql = "SELECT m.fecha, p.nombre as producto, m.cantidad, m.motivo, u.nombre as usuario 
            FROM movimientos_inventario m 
            JOIN productos p ON m.producto_id = p.id 
            LEFT JOIN usuarios u ON m.usuario_id = u.id";
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY m.fecha DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function salidasInventario($fecha_inicio = null, $fecha_fin = null) {
        $pdo = Conexion::conectar();
        $where = ["m.tipo = 'salida'"];
        $params = [];
        if ($fecha_inicio) {
            $where[] = "DATE(m.fecha) >= ?";
            $params[] = $fecha_inicio;
        }
        if ($fecha_fin) {
            $where[] = "DATE(m.fecha) <= ?";
            $params[] = $fecha_fin;
        }
        $sql = "SELECT m.fecha, p.nombre as producto, m.cantidad, m.motivo, u.nombre as usuario 
            FROM movimientos_inventario m 
            JOIN productos p ON m.producto_id = p.id 
            LEFT JOIN usuarios u ON m.usuario_id = u.id";
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY m.fecha DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}