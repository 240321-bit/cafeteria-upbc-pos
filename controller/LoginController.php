<?php
session_start();
require_once "../model/UsuarioModel.php";

// Manejar logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../view/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    $datos = UsuarioModel::verificarLogin($usuario, $contrasena);

    if ($datos) {
        $_SESSION["id"] = $datos["id"];
        $_SESSION["usuario"] = $datos["nombre"];
        $_SESSION["rol"] = $datos["rol"];
        $_SESSION["usuario_id"] = $datos["id"]; // <-- Línea agregada

        // Redirigir según el rol
        if ($datos["rol"] === "gerente") {
            header("Location: ../view/dashboard_gerente.php");
        } else {
            header("Location: ../view/dashboard_empleado.php");
        }
        exit;
    } else {
        header("Location: ../view/login.php?error=1");
        exit;
    }
}