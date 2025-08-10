<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      background: #fff8f3;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card-registro {
      max-width: 400px;
      margin: 40px auto;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.08);
      border: none;
    }
    .card-header {
      background: #7a5235;
      color: #fff;
      border-radius: 16px 16px 0 0;
      text-align: center;
      padding: 24px 0 12px 0;
    }
    .form-label {
      color: #7a5235;
      font-weight: 500;
    }
    .btn-primary {
      background: #b96a5a;
      border: none;
      font-weight: bold;
      border-radius: 8px;
    }
    .btn-primary:hover {
      background: #7a5235;
    }
    .btn-outline-secondary {
      border-radius: 8px;
    }
    .logo-cafe {
      width: 48px;
      margin-bottom: 8px;
    }
  </style>
</head>
<body>
  <div class="card card-registro">
    <div class="card-header">
      <img src="img/cup.png" alt="Logo" class="logo-cafe">
      <h2 class="mb-0">Registrar nuevo usuario</h2>
    </div>
    <div class="card-body">
      <form action="../controller/RegistroController.php" method="POST" autocomplete="off">
        <div class="mb-3">
          <label for="nombre" class="form-label"><i class="bi bi-person-fill"></i> Nombre completo</label>
          <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>
        <div class="mb-3">
          <label for="usuario" class="form-label"><i class="bi bi-person-badge-fill"></i> Usuario</label>
          <input type="text" class="form-control" name="usuario" id="usuario" required>
        </div>
        <div class="mb-3">
          <label for="contrasena" class="form-label"><i class="bi bi-lock-fill"></i> Contraseña</label>
          <input type="password" class="form-control" name="contrasena" id="contrasena" required>
        </div>
        <div class="mb-3">
          <label for="rol" class="form-label"><i class="bi bi-person-gear"></i> Rol</label>
          <select class="form-select" name="rol" id="rol" required>
            <option value="empleado">Empleado</option>
            <option value="gerente">Gerente</option>
          </select>
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-primary" type="submit"><i class="bi bi-person-plus-fill"></i> Registrar</button>
          <a href="login.php" class="btn btn-outline-secondary"><i class="bi bi-box-arrow-in-left"></i> Volver al login</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>