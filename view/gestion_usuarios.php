<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: login.php");
    exit;
}
require_once("../model/UsuarioModel.php");
$usuarios = UsuarioModel::obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body { background: #fff8f3; }
    .container { max-width: 900px; }
    .table thead { background: #7a5235; color: #fff; }
    .btn-primary { background: #b96a5a; border: none; }
    .btn-primary:hover { background: #7a5235; }
    .btn-danger { border-radius: 8px; }
    .btn-warning { border-radius: 8px; }
    .btn-success { border-radius: 8px; }
    .modal-header { background: #7a5235; color: #fff; }
    .logo-cafe { width: 40px; margin-right: 10px; }
  </style>
</head>
<body>
<div class="container my-4">
  <div class="d-flex align-items-center mb-4">
    <img src="img/cup.png" class="logo-cafe" alt="Logo">
    <h2 class="mb-0">Gestión de Usuarios</h2>
    <a href="dashboard_gerente.php" class="btn btn-outline-secondary ms-auto"><i class="bi bi-arrow-left"></i> Volver al Dashboard</a>
  </div>
  <div class="mb-3 d-flex justify-content-between">
    <form class="d-flex" method="get">
      <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar usuario, nombre o rol..." value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
      <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="nuevoUsuario()"><i class="bi bi-person-plus"></i> Nuevo Usuario</button>
  </div>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Nombre</th>
          <th>Rol</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['usuario']) ?></td>
          <td><?= htmlspecialchars($u['nombre']) ?></td>
          <td>
            <span class="badge <?= $u['rol']=='gerente'?'bg-primary':'bg-secondary' ?>">
              <?= ucfirst($u['rol']) ?>
            </span>
          </td>
          <td>
            <button class="btn btn-warning btn-sm" onclick="editarUsuario(<?= $u['id'] ?>)"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(<?= $u['id'] ?>)"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($usuarios)): ?>
        <tr><td colspan="5" class="text-center">No hay usuarios registrados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="formUsuario" method="post" action="../controller/UsuarioController.php">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUsuarioLabel">Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="usuario_id">
        <div class="mb-3">
          <label for="usuario" class="form-label">Usuario</label>
          <input type="text" class="form-control" name="usuario" id="usuario" required>
        </div>
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre completo</label>
          <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>
        <div class="mb-3">
          <label for="rol" class="form-label">Rol</label>
          <select class="form-select" name="rol" id="rol" required>
            <option value="empleado">Empleado</option>
            <option value="gerente">Gerente</option>
          </select>
        </div>
        <div class="mb-3" id="passwordDiv">
          <label for="contrasena" class="form-label">Contraseña</label>
          <input type="password" class="form-control" name="contrasena" id="contrasena">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function nuevoUsuario() {
  document.getElementById('modalUsuarioLabel').innerText = 'Nuevo Usuario';
  document.getElementById('formUsuario').reset();
  document.getElementById('usuario_id').value = '';
  document.getElementById('passwordDiv').style.display = '';
}

function editarUsuario(id) {
  fetch('../controller/UsuarioController.php?action=get&id=' + id)
    .then(res => res.json())
    .then(u => {
      document.getElementById('modalUsuarioLabel').innerText = 'Editar Usuario';
      document.getElementById('usuario_id').value = u.id;
      document.getElementById('usuario').value = u.usuario;
      document.getElementById('nombre').value = u.nombre;
      document.getElementById('rol').value = u.rol;
      document.getElementById('contrasena').value = '';
      document.getElementById('passwordDiv').style.display = 'block';
      var modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
      modal.show();
    });
}

function eliminarUsuario(id) {
  if (confirm('¿Seguro que deseas eliminar este usuario?')) {
    fetch('../controller/UsuarioController.php?action=delete&id=' + id, {method: 'POST'})
      .then(() => location.reload());
  }
}
</script>
</body>
</html>