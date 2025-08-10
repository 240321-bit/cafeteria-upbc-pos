<?php
require_once("../model/Conexion.php");
$pdo = Conexion::conectar();

if (isset($_GET['q'])) {
    $q = "%".$_GET['q']."%";
    $stmt = $pdo->prepare("SELECT id, nombre, matricula, (SELECT COUNT(*) FROM ventas WHERE cliente_id = clientes.id) as compras FROM clientes WHERE nombre LIKE ? OR matricula LIKE ? LIMIT 5");
    $stmt->execute([$q, $q]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT (SELECT COUNT(*) FROM ventas WHERE cliente_id = ?) as compras FROM clientes WHERE id = ?");
    $stmt->execute([$_GET['id'], $_GET['id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['compras' => $row ? intval($row['compras']) : 0]);
    exit;
}
?>