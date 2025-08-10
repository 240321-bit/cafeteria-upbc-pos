<?php
require_once __DIR__ . "/Conexion.php";

class UsuarioModel {
  public static function verificarLogin($usuario, $contrasena) {
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $datos = $stmt->fetch(PDO::FETCH_ASSOC);
      if (password_verify($contrasena, $datos["contrasena"])) {
        return $datos;
      }
    }

    return false;
  }

  public static function agregarUsuario($nombre, $usuario, $contrasena, $rol = 'empleado') {
    $sql = "INSERT INTO usuarios (nombre, usuario, contrasena, rol) VALUES (:nombre, :usuario, :contrasena, :rol)";
    $stmt = Conexion::conectar()->prepare($sql);
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":usuario", $usuario);
    $stmt->bindParam(":contrasena", $hash);
    $stmt->bindParam(":rol", $rol);
    return $stmt->execute();
  }

  public static function obtenerTodos() {
        $pdo = Conexion::conectar();
        $stmt = $pdo->query("SELECT id, usuario, nombre, rol FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($id) {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT id, usuario, nombre, rol FROM usuarios WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function guardar($data) {
        $pdo = Conexion::conectar();
        if (empty($data['id'])) {
            $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, nombre, rol, contrasena) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['usuario'], $data['nombre'], $data['rol'], password_hash($data['contrasena'], PASSWORD_DEFAULT)]);
        } else {
            if (!empty($data['contrasena'])) {
                $stmt = $pdo->prepare("UPDATE usuarios SET usuario=?, nombre=?, rol=?, contrasena=? WHERE id=?");
                $stmt->execute([$data['usuario'], $data['nombre'], $data['rol'], password_hash($data['contrasena'], PASSWORD_DEFAULT), $data['id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET usuario=?, nombre=?, rol=? WHERE id=?");
                $stmt->execute([$data['usuario'], $data['nombre'], $data['rol'], $data['id']]);
            }
        }
    }

    public static function eliminar($id) {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->execute([$id]);
    }
}