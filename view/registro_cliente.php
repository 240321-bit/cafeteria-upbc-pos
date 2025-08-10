<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Alumno/Cliente</title>
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
    .btn-success {
      background: #b96a5a;
      border: none;
      font-weight: bold;
      border-radius: 8px;
    }
    .btn-success:hover {
      background: #7a5235;
    }
    .btn-secondary {
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
      <h2 class="mb-0">Registrar Alumno/Cliente</h2>
    </div>
    <div class="card-body">
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">La matrícula ya está registrada.</div>
      <?php endif; ?>
      <form action="../controller/RegistroClienteController.php" method="POST" autocomplete="off">
        <div class="mb-3">
          <label for="nombre" class="form-label"><i class="bi bi-person-fill"></i> Nombre completo</label>
          <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>
        <div class="mb-3">
          <label for="matricula" class="form-label"><i class="bi bi-card-heading"></i> Matrícula</label>
          <input type="text" class="form-control" name="matricula" id="matricula" required>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-success"><i class="bi bi-person-plus-fill"></i> Registrar</button>
          <a href="venta.php" class="btn btn-secondary"><i class="bi bi-box-arrow-in-left"></i> Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>