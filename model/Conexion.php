<?php
class Conexion {
  public static function conectar() {
    try {
      $pdo = new PDO("mysql:host=localhost;dbname=cafeteriaupbc", "root", "");
      $pdo->exec("set names utf8");
      return $pdo;
    } catch (PDOException $e) {
      die("Error de conexión: " . $e->getMessage());
    }
  }
}