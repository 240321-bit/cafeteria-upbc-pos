<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once("../model/ProductoModel.php");
$tipo = $_GET['tipo'] ?? '';
$q = $_GET['q'] ?? '';
$productos = ProductoModel::buscarTodos($tipo, $q);
$tipos = ['bebida' => 'Bebidas', 'comida' => 'Comidas', 'postre' => 'Postres'];
$rol = $_SESSION['rol'] ?? 'empleado'; // Por defecto empleado si no existe
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f9f5f1; }
        .inventario-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(120, 72, 32, 0.08); padding: 0; margin-top: 32px; margin-bottom: 32px; }
        .inventario-header { background: #7b4f22; color: #fff; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .inventario-header h3 { margin: 0; font-weight: bold; letter-spacing: 1px; }
        .btn-volver { margin-bottom: 1.5rem; background: #fff; color: #7b4f22; border: 2px solid #7b4f22; border-radius: 8px; font-weight: 500; }
        .btn-volver:hover { background: #7b4f22; color: #fff; }
        .table thead { background: #f5e9dd; color: #7b4f22; font-weight: bold; }
        .table { background: #fff; border-radius: 0 0 16px 16px; overflow: hidden; }
        .badge-stock { font-size: 1rem; background: #f5e9dd; color: #7b4f22; border-radius: 8px; padding: 0.5em 1em; }
        .categoria-badge { font-size: 0.95rem; background: #e7d3c1; color: #7b4f22; border-radius: 8px; padding: 0.4em 0.8em; }
        .acciones-btns .btn { margin-right: 2px; }
        .btn-success, .btn-outline-success { background: #b88c5a; border-color: #b88c5a; color: #fff; }
        .btn-success:hover, .btn-outline-success:hover { background: #7b4f22; border-color: #7b4f22; color: #fff; }
        .btn-primary { background: #7b4f22; border-color: #7b4f22; }
        .btn-primary:hover { background: #b88c5a; border-color: #b88c5a; }
        .btn-outline-secondary { border-color: #b88c5a; color: #7b4f22; }
        .btn-outline-secondary:hover { background: #b88c5a; color: #fff; }
        .shadow-card { box-shadow: 0 2px 12px rgba(120,72,32,0.08); border-radius: 10px; }
    </style>
</head>
<body>

<?php if (isset($_GET['error']) && $_GET['error'] == 'ventas'): ?>
<script>
    window.onload = function() {
        alert('No se puede eliminar este producto porque tiene ventas registradas.');
    }
</script>
<?php endif; ?>

<div class="container inventario-card">
    <a href="<?= $rol == 'gerente' ? 'dashboard_gerente.php' : 'dashboard_empleado.php' ?>" class="btn btn-volver mt-4">
        <i class="bi bi-arrow-left"></i> Volver al Dashboard
    </a>
    <div class="inventario-header shadow-card">
        <h3><i class="bi bi-box"></i> Inventario de Productos</h3>
        <?php if ($rol == 'gerente'): ?>
        <button class="btn btn-success" onclick="nuevoProducto()">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </button>
        <?php endif; ?>
    </div>
    <form class="row g-2 my-3 px-4" method="get">
        <div class="col-md-3">
            <select name="tipo" class="form-select" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>
                <?php foreach($tipos as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $tipo==$key?'selected':'' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="q" class="form-control" placeholder="Buscar producto..." value="<?= htmlspecialchars($q) ?>">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Buscar</button>
        </div>
    </form>
    <div class="table-responsive px-4 pb-4">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Entradas</th>
                    <th>Salidas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($productos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td>
                        <span class="categoria-badge"><?= ucfirst($p['tipo']) ?></span>
                    </td>
                    <td>$<?= number_format($p['precio'],2) ?></td>
                    <td>
                        <span class="badge-stock"><?= $p['stock'] ?></span>
                    </td>
                    <td>
                        <?php if ($rol == 'gerente'): ?>
                        <button class="btn btn-outline-success btn-sm" title="Registrar entrada" onclick="movimiento('entrada',<?= $p['id'] ?>, '<?= htmlspecialchars($p['nombre']) ?>')"><i class="bi bi-box-arrow-in-down"></i></button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($rol == 'gerente'): ?>
                        <button class="btn btn-outline-warning btn-sm" title="Registrar salida" onclick="movimiento('salida',<?= $p['id'] ?>, '<?= htmlspecialchars($p['nombre']) ?>')"><i class="bi bi-box-arrow-up"></i></button>
                        <?php endif; ?>
                    </td>
                    <td class="acciones-btns">
                        <?php if ($rol == 'gerente'): ?>
                        <button class="btn btn-warning btn-sm" title="Editar" onclick="editarProducto(<?= $p['id'] ?>)"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm" title="Eliminar" onclick="eliminarProducto(<?= $p['id'] ?>)"><i class="bi bi-trash"></i></button>
                        <?php endif; ?>
                        <button class="btn btn-info btn-sm" title="Historial" onclick="verHistorial(<?= $p['id'] ?>, '<?= htmlspecialchars($p['nombre']) ?>')"><i class="bi bi-clock-history"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($productos)): ?>
                <tr><td colspan="7" class="text-center">No hay productos.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-4 pb-4">
        <button class="btn btn-outline-secondary mt-3" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir Inventario</button>
    </div>
</div>

<!-- Modal para registrar movimiento de inventario -->
<?php if ($rol == 'gerente'): ?>
<div class="modal fade" id="movimientoModal" tabindex="-1" aria-labelledby="movimientoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formMovimiento" method="POST" action="../controller/MovimientoInventarioController.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="movimientoModalLabel">Registrar Movimiento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="producto_id" id="movimientoProductoId">
          <input type="hidden" name="tipo_movimiento" id="tipoMovimiento">
          <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" class="form-control" name="cantidad" id="cantidad" min="1" required>
          </div>
          <div class="mb-3">
            <label for="motivo" class="form-label">Motivo</label>
            <select class="form-select" name="motivo" id="motivo" required>
              <option value="">Selecciona un motivo</option>
              <option value="Compra a proveedor">Compra a proveedor</option>
              <option value="Ajuste por faltante">Ajuste por faltante</option>
              <option value="Merma">Merma</option>
              <option value="Otro">Otro</option>
            </select>
          </div>
          <div class="mb-3" id="otroMotivoDiv" style="display:none;">
            <label for="otro_motivo" class="form-label">Especifica el motivo</label>
            <input type="text" class="form-control" name="otro_motivo" id="otro_motivo">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar Movimiento</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Modal Editar Producto -->
<?php if ($rol == 'gerente'): ?>
<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="formEditarProducto" method="post" action="../controller/ProductoController.php">
      <div class="modal-header" style="background:#7b4f22;color:#fff;">
        <h5 class="modal-title" id="modalEditarProductoLabel">Editar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit_id">
        <div class="mb-3">
          <label for="edit_nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" id="edit_nombre" required>
        </div>
        <div class="mb-3">
          <label for="edit_precio" class="form-label">Precio</label>
          <input type="number" step="0.01" class="form-control" name="precio" id="edit_precio" required>
        </div>
        <div class="mb-3">
          <label for="edit_tipo" class="form-label">Categoría</label>
          <select class="form-select" name="tipo" id="edit_tipo" required>
            <option value="comida">Comida</option>
            <option value="bebida">Bebida</option>
            <option value="postre">Postre</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="edit_stock" class="form-label">Stock</label>
          <input type="number" class="form-control" name="stock" id="edit_stock" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
      </div>
      <input type="hidden" name="accion" value="editar">
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Modal Agregar Producto -->
<?php if ($rol == 'gerente'): ?>
<div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-labelledby="modalAgregarProductoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="formAgregarProducto" method="post" action="../controller/ProductoController.php">
      <div class="modal-header" style="background:#7b4f22;color:#fff;">
        <h5 class="modal-title" id="modalAgregarProductoLabel">Agregar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="add_nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" id="add_nombre" required>
        </div>
        <div class="mb-3">
          <label for="add_precio" class="form-label">Precio</label>
          <input type="number" step="0.01" class="form-control" name="precio" id="add_precio" required>
        </div>
        <div class="mb-3">
          <label for="add_tipo" class="form-label">Categoría</label>
          <select class="form-select" name="tipo" id="add_tipo" required>
            <option value="comida">Comida</option>
            <option value="bebida">Bebida</option>
            <option value="postre">Postre</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="add_stock" class="form-label">Stock</label>
          <input type="number" class="form-control" name="stock" id="add_stock" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Agregar Producto</button>
      </div>
      <input type="hidden" name="accion" value="agregar">
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Modal Historial de Movimientos -->
<div class="modal fade" id="modalHistorial" tabindex="-1" aria-labelledby="modalHistorialLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#7b4f22;color:#fff;">
        <h5 class="modal-title" id="modalHistorialLabel">Historial de Movimientos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <h6 id="historialProductoNombre"></h6>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Motivo</th>
                <th>Usuario</th>
              </tr>
            </thead>
            <tbody id="historialMovimientosBody">
              <tr><td colspan="5" class="text-center">Cargando...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editarProducto(id) {
    fetch('../controller/ProductoController.php?accion=obtener&id=' + id)
        .then(res => res.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_nombre').value = data.nombre;
            document.getElementById('edit_precio').value = data.precio;
            document.getElementById('edit_tipo').value = data.tipo;
            document.getElementById('edit_stock').value = data.stock;
            var modal = new bootstrap.Modal(document.getElementById('modalEditarProducto'));
            modal.show();
        });
}
function nuevoProducto() {
    document.getElementById('formAgregarProducto').reset();
    var modal = new bootstrap.Modal(document.getElementById('modalAgregarProducto'));
    modal.show();
}
function eliminarProducto(id) {
    if (confirm('¿Seguro que deseas eliminar este producto?')) {
        window.location.href = '../controller/ProductoController.php?accion=eliminar&id=' + id;
    }
}
<?php if ($rol == 'gerente'): ?>
function movimiento(tipo, id, nombre) {
    document.getElementById('movimientoProductoId').value = id;
    document.getElementById('tipoMovimiento').value = tipo;
    document.getElementById('movimientoModalLabel').innerText = tipo === 'entrada' ? 'Registrar Entrada' : 'Registrar Salida';
    document.getElementById('formMovimiento').reset();
    document.getElementById('otroMotivoDiv').style.display = 'none';
    var modal = new bootstrap.Modal(document.getElementById('movimientoModal'));
    modal.show();
}
document.getElementById('motivo').addEventListener('change', function() {
    document.getElementById('otroMotivoDiv').style.display = this.value === 'Otro' ? '' : 'none';
});
<?php endif; ?>
function verHistorial(id, nombre) {
    document.getElementById('historialProductoNombre').textContent = nombre;
    var tbody = document.getElementById('historialMovimientosBody');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Cargando...</td></tr>';
    fetch('../controller/ProductoController.php?accion=historial&id=' + id)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Sin movimientos.</td></tr>';
            } else {
                tbody.innerHTML = '';
                data.forEach(mov => {
                    let tipo = mov.tipo.charAt(0).toUpperCase() + mov.tipo.slice(1);
                    let usuario = mov.usuario ? mov.usuario : '-';
                    tbody.innerHTML += `<tr>
                        <td>${mov.fecha}</td>
                        <td>${tipo}</td>
                        <td>${mov.cantidad}</td>
                        <td>${mov.motivo}</td>
                        <td>${usuario}</td>
                    </tr>`;
                });
            }
        });
    var modal = new bootstrap.Modal(document.getElementById('modalHistorial'));
    modal.show();
}
</script>

</body>
</html>