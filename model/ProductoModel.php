<?php
require_once("Conexion.php");

class ProductoModel {
    // Listar/buscar productos con filtro por tipo y búsqueda por nombre
    public static function buscarTodos($tipo = '', $q = '') {
        $pdo = Conexion::conectar();
        $sql = "SELECT * FROM productos WHERE 1";
        $params = [];
        if ($tipo) {
            $sql .= " AND tipo = ?";
            $params[] = $tipo;
        }
        if ($q) {
            $sql .= " AND nombre LIKE ?";
            $params[] = "%$q%";
        }
        $sql .= " ORDER BY nombre ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los tipos de productos (postres, bebidas, etc.)
    public static function obtenerTipos() {
        $pdo = Conexion::conectar();
        $stmt = $pdo->query("SELECT DISTINCT tipo FROM productos ORDER BY tipo");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Obtener un producto por ID
    public static function obtenerPorId($id) {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Agregar un producto nuevo
    public static function agregar($data) {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, tipo, precio, stock) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['tipo'],
            $data['precio'],
            $data['stock']
        ]);
        return $pdo->lastInsertId();
    }

    // Editar un producto existente
    public static function editar($id, $data) {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("UPDATE productos SET nombre=?, tipo=?, precio=?, stock=? WHERE id=?");
        $stmt->execute([
            $data['nombre'],
            $data['tipo'],
            $data['precio'],
            $data['stock'],
            $id
        ]);
    }

    // Eliminar un producto
    public static function eliminar($id) {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id=?");
        $stmt->execute([$id]);
    }

    // Registrar movimiento de inventario (entrada/salida)
    public static function registrarMovimiento($producto_id, $tipo, $cantidad, $motivo, $usuario_id) {
        $pdo = Conexion::conectar();
        // Actualiza stock
        if ($tipo === 'entrada') {
            $pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?")->execute([$cantidad, $producto_id]);
        } else if ($tipo === 'salida') {
            $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?")->execute([$cantidad, $producto_id]);
        }
        // Registra movimiento
        $stmt = $pdo->prepare("INSERT INTO movimientos_inventario (producto_id, tipo, cantidad, motivo, fecha, usuario_id) VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt->execute([$producto_id, $tipo, $cantidad, $motivo, $usuario_id]);
    }

    // Obtener historial de movimientos de un producto
    public static function obtenerHistorial($producto_id) {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT m.*, u.nombre as usuario FROM movimientos_inventario m LEFT JOIN usuarios u ON m.usuario_id = u.id WHERE m.producto_id = ? ORDER BY m.fecha DESC");
        $stmt->execute([$producto_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}