<?php
require_once("../model/Conexion.php");
$pdo = Conexion::conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $matricula = trim($_POST['matricula']);

    // Evita duplicados por matrícula
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE matricula = ?");
    $stmt->execute([$matricula]);
    if ($stmt->fetch()) {
        header("Location: ../view/registro_cliente.php?error=1");
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO clientes (nombre, matricula) VALUES (?, ?)");
    $stmt->execute([$nombre, $matricula]);
    header("Location: ../view/venta.php?cliente_registrado=1");
    exit;
}
header("Location: ../view/registro_cliente.php");
exit;
?>