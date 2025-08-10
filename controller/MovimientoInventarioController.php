<?php
session_start();
require_once("../model/Conexion.php");
$pdo = Conexion::conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'];
    $tipo = $_POST['tipo_movimiento']; // 'entrada' o 'salida'
    $cantidad = intval($_POST['cantidad']);
    $motivo = $_POST['motivo'] === 'Otro' ? $_POST['otro_motivo'] : $_POST['motivo'];
    $usuario_id = $_SESSION['usuario_id'] ?? null;

    // Actualiza stock
    if ($tipo === 'entrada') {
        $pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?")->execute([$cantidad, $producto_id]);
    } else {
        $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?")->execute([$cantidad, $producto_id]);
    }

    // Guarda movimiento
    $pdo->prepare("INSERT INTO movimientos_inventario (producto_id, tipo, cantidad, motivo, usuario_id, fecha) VALUES (?, ?, ?, ?, ?, NOW())")
        ->execute([$producto_id, $tipo, $cantidad, $motivo, $usuario_id]);

    header("Location: ../view/inventario.php?mov=ok");
    exit;
}
?>