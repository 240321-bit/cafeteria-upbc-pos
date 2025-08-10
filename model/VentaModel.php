<?php
class VentaModel {
    private static function getDB() {
        $host = "localhost";
        $db = "cafeteriaupbc";
        $user = "root";
        $pass = "";
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    // Ventas del día actual
    public static function ventasDelDia() {
        $pdo = self::getDB();
        $sql = "SELECT id, fecha, total, metodo_pago, descuento, cliente_id
                FROM ventas
                WHERE DATE(fecha) = CURDATE()
                ORDER BY fecha DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener venta por ID
    public static function obtenerVentaPorId($id) {
        $pdo = self::getDB();
        $sql = "SELECT * FROM ventas WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener productos de la venta (usando precio_unitario)
    public static function obtenerProductosVenta($venta_id) {
        $pdo = self::getDB();
        $sql = "SELECT p.nombre, dv.cantidad, dv.precio_unitario AS precio
                FROM detalle_venta dv
                JOIN productos p ON dv.producto_id = p.id
                WHERE dv.venta_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$venta_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}