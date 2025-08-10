<?php
require_once("../model/Conexion.php");

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

$pdo = Conexion::conectar();
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? AND rol = 'gerente' LIMIT 1");
$stmt->execute([$usuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si tus contraseñas están hasheadas con password_hash:
if ($user && password_verify($password, $user['contrasena'])) {
    echo json_encode(['autorizado' => true]);
} else {
    echo json_encode(['autorizado' => false]);
}

// Si tus contraseñas NO están hasheadas, usa esto en vez de password_verify:
// if ($user && $password == $user['password']) { ... }