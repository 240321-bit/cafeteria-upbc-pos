<?php
require_once("../model/UsuarioModel.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'get') {
    $usuario = UsuarioModel::obtenerPorId($_GET['id']);
    echo json_encode($usuario);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        UsuarioModel::eliminar($_GET['id']);
        exit;
    }
    // Guardar (nuevo o editar)
    $data = [
        'id' => $_POST['id'] ?? null,
        'usuario' => $_POST['usuario'],
        'nombre' => $_POST['nombre'],
        'rol' => $_POST['rol'],
        'contrasena' => $_POST['contrasena'] ?? ''
    ];
    UsuarioModel::guardar($data);
    header("Location: ../view/gestion_usuarios.php");
    exit;
}
?>