<?php
require_once "../model/UsuarioModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = $_POST["nombre"];
  $usuario = $_POST["usuario"];
  $contrasena = $_POST["contrasena"];
  $rol = $_POST["rol"];

  $exito = UsuarioModel::agregarUsuario($nombre, $usuario, $contrasena, $rol);

  if ($exito) {
    header("Location: ../view/login.php?registro=1");
  } else {
    header("Location: ../view/registro.php?error=1");
  }
}
