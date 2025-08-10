<?php
require_once("../model/ProductoModel.php");

// Editar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    ProductoModel::editar($_POST['id'], [
        'nombre' => $_POST['nombre'],
        'tipo' => $_POST['tipo'],
        'precio' => $_POST['precio'],
        'stock' => $_POST['stock']
    ]);
    header("Location: ../view/inventario.php");
    exit;
}

// Obtener producto por ID (para AJAX)
if (isset($_GET['accion']) && $_GET['accion'] === 'obtener' && isset($_GET['id'])) {
    $producto = ProductoModel::obtenerPorId($_GET['id']);
    header('Content-Type: application/json');
    echo json_encode($producto);
    exit;
}

// Historial de movimientos de un producto (AJAX)
if (isset($_GET['accion']) && $_GET['accion'] === 'historial' && isset($_GET['id'])) {
    $historial = ProductoModel::obtenerHistorial($_GET['id']);
    header('Content-Type: application/json');
    echo json_encode($historial);
    exit;
}

// Eliminar producto
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    try {
        ProductoModel::eliminar($_GET['id']);
        header("Location: ../view/inventario.php?msg=eliminado");
    } catch (PDOException $e) {
        // Si es un error de clave foránea, redirige con mensaje de error
        if ($e->getCode() == 23000) {
            header("Location: ../view/inventario.php?error=ventas");
        } else {
            header("Location: ../view/inventario.php?error=desconocido");
        }
    }
    exit;
}

// Agregar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    ProductoModel::agregar([
        'nombre' => $_POST['nombre'],
        'tipo' => $_POST['tipo'],
        'precio' => $_POST['precio'],
        'stock' => $_POST['stock']
    ]);
    header("Location: ../view/inventario.php");
    exit;
}