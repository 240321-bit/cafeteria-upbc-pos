<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once("../model/ProductoModel.php");

// Obtener productos por tipo (igual que en inventario)
$comidas = ProductoModel::buscarTodos('comida');
$bebidas = ProductoModel::buscarTodos('bebida');
$postres = ProductoModel::buscarTodos('postre');

// Detecta el dashboard según el rol
$dashboard = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'gerente') ? 'dashboard_gerente.php' : 'dashboard_empleado.php';
$rolUsuario = $_SESSION['rol'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta - Cafetería UPBC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #fff8f3; }
        .venta-header {
            background: #7a5235;
            color: #fff;
            padding: 16px 24px;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .venta-header .logo {
            height: 48px;
            margin-right: 16px;
        }
        .venta-header .title {
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .venta-header .fecha {
            background: #fff;
            color: #7a5235;
            border-radius: 20px;
            padding: 6px 18px;
            font-weight: bold;
            font-size: 1rem;
        }
        .venta-main {
            background: #fff;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 24px;
        }
        .venta-productos .btn-producto {
            background: #f8e6de;
            color: #7a5235;
            border: 2px solid #d48375;
            border-radius: 8px;
            margin: 6px 0;
            font-weight: bold;
            width: 100%;
        }
        .venta-resumen {
            background: #f3e6de;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .venta-total {
            font-size: 1.5rem;
            font-weight: bold;
            color: #7a5235;
        }
        .venta-descuento {
            font-size: 1.1rem;
            color: #388e3c;
            font-weight: bold;
        }
        .btn-pagar {
            background: #43a047;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            width: 100%;
            margin-bottom: 8px;
        }
        .btn-cancelar {
            background: #bdbdbd;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            width: 100%;
            margin-bottom: 8px;
        }
        .btn-quitar {
            background: #e57373;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            padding: 2px 8px;
            margin-left: 8px;
        }
        .list-group-item {
            cursor: pointer;
        }
        .alert-cancelada {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2000;
            min-width: 300px;
        }
    </style>
</head>
<body>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success text-center mt-3" id="msg-success">
        ¡Venta registrada correctamente!
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('msg-success').style.display = 'none';
            window.history.replaceState({}, document.title, window.location.pathname);
        }, 2500);
    </script>
<?php endif; ?>

<!-- Mensaje de venta cancelada -->
<div id="msg-cancelada" class="alert alert-warning text-center alert-cancelada" style="display:none;">
    ¡Venta cancelada!
</div>

<div class="container mt-3">
    <a href="<?php echo $dashboard; ?>" class="btn btn-outline-secondary" id="btnVolverDashboard">
        <i class="bi bi-arrow-left"></i> Volver al Dashboard
    </a>
</div>

<div class="container my-4">
    <div class="venta-header mb-0">
        <div class="d-flex align-items-center">
            <img src="/CafeteriaUPBC/view/img/cup.png" class="logo" alt="Logo">
            <span class="title">CAFETERÍA UPBC</span>
        </div>
        <span class="fecha">
    <?php
    setlocale(LC_TIME, 'es_MX.UTF-8', 'es_MX', 'spanish', 'es_ES.UTF-8', 'es_ES');
    date_default_timezone_set('America/Tijuana');
    echo mb_convert_encoding(strftime('%A, %d de %B de %Y, %H:%M'), 'UTF-8', 'UTF-8');
    ?>
        </span>
    </div>
    <div class="venta-main">
        <ul class="nav venta-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="#">INICIAR VENTA</a>
            </li>
        </ul>
        <div class="row">
            <!-- Resumen de venta -->
            <div class="col-md-4">
                <form id="formVenta" method="POST" action="../controller/GuardarVenta.php">
                    <!-- Registro rápido de cliente/alumno -->
                    <div class="mb-3">
                        <label class="form-label" for="cliente"><i class="bi bi-person-badge"></i> Alumno/Cliente</label>
                        <input type="text" class="form-control" name="cliente" id="cliente" placeholder="Nombre o matrícula" autocomplete="off" required>
                        <input type="hidden" name="cliente_id" id="cliente_id">
                        <div id="cliente-sugerencias" class="list-group"></div>
                        <button type="button" class="btn btn-outline-primary mt-2" id="btnRegistrarCliente">Registrar nuevo alumno</button>
                    </div>
                    <div class="venta-resumen">
                        <div id="resumen-lista"></div>
                        <div class="venta-total mt-2">Total: $<span id="resumen-total">0.00</span></div>
                        <div class="venta-descuento" id="descuento-info" style="display:none;"></div>
                        <input type="hidden" name="productos" id="productosInput">
                        <input type="hidden" name="total" id="totalInput">
                        <input type="hidden" name="descuento" id="descuentoInput">
                    </div>
                    <!-- Selector de método de pago -->
                    <div class="mb-3">
                        <label class="form-label" for="metodo_pago"><i class="bi bi-credit-card-2-front"></i> Método de pago</label>
                        <select class="form-select" name="metodo_pago" id="metodo_pago" required>
                            <option value="">Selecciona una opción</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-cancelar mb-2" id="cancelarVenta">CANCELAR VENTA</button>
                    <button type="submit" class="btn btn-pagar mb-2" id="btnPagar" disabled>PAGAR</button>
                </form>
            </div>
            <!-- Productos -->
            <div class="col-md-8">
                <div class="row g-2">
                    <div class="col-12">
                        <ul class="nav nav-tabs mb-2" id="ventaTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="comidas-tab" data-bs-toggle="tab" data-bs-target="#comidas" type="button" role="tab">COMIDAS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="bebidas-tab" data-bs-toggle="tab" data-bs-target="#bebidas" type="button" role="tab">BEBIDAS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="postres-tab" data-bs-toggle="tab" data-bs-target="#postres" type="button" role="tab">POSTRES</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="ventaTabsContent">
                            <div class="tab-pane fade show active" id="comidas" role="tabpanel">
                                <div class="row g-2 venta-productos">
                                    <?php foreach($comidas as $prod): ?>
                                    <div class="col-4">
                                        <button class="btn btn-producto" 
                                            data-id="<?= $prod['id'] ?>" 
                                            data-nombre="<?= htmlspecialchars($prod['nombre']) ?>" 
                                            data-precio="<?= $prod['precio'] ?>">
                                            <?= htmlspecialchars($prod['nombre']) ?><br>
                                            <span class="badge bg-light text-dark">$<?= $prod['precio'] ?></span>
                                        </button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="bebidas" role="tabpanel">
                                <div class="row g-2 venta-productos">
                                    <?php foreach($bebidas as $prod): ?>
                                    <div class="col-4">
                                        <button class="btn btn-producto" 
                                            data-id="<?= $prod['id'] ?>" 
                                            data-nombre="<?= htmlspecialchars($prod['nombre']) ?>" 
                                            data-precio="<?= $prod['precio'] ?>">
                                            <?= htmlspecialchars($prod['nombre']) ?><br>
                                            <span class="badge bg-light text-dark">$<?= $prod['precio'] ?></span>
                                        </button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="postres" role="tabpanel">
                                <div class="row g-2 venta-productos">
                                    <?php foreach($postres as $prod): ?>
                                    <div class="col-4">
                                        <button class="btn btn-producto" 
                                            data-id="<?= $prod['id'] ?>" 
                                            data-nombre="<?= htmlspecialchars($prod['nombre']) ?>" 
                                            data-precio="<?= $prod['precio'] ?>">
                                            <?= htmlspecialchars($prod['nombre']) ?><br>
                                            <span class="badge bg-light text-dark">$<?= $prod['precio'] ?></span>
                                        </button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de autorización de gerente -->
<div class="modal fade" id="modalAutorizacionGerente" tabindex="-1" aria-labelledby="modalAutorizacionGerenteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formAutorizacionGerente" autocomplete="off">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAutorizacionGerenteLabel">Autorización de Gerente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="usuario_gerente" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="usuario_gerente" name="usuario_gerente" required>
          </div>
          <div class="mb-3">
            <label for="password_gerente" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password_gerente" name="password_gerente" required>
          </div>
          <div id="msgAutorizacion" class="text-danger small"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Autorizar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de advertencia de venta en proceso -->
<div class="modal fade" id="modalVentaEnProceso" tabindex="-1" aria-labelledby="modalVentaEnProcesoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalVentaEnProcesoLabel">Venta en proceso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        No puedes volver al dashboard mientras hay una venta en proceso. Cancela o termina la venta primero.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Carrito en JS
let carrito = [];
let descuento = 0;
let descuentoPorcentaje = 0;

// Bloquear botón "Volver al Dashboard" si hay productos en el carrito
document.getElementById('btnVolverDashboard').addEventListener('click', function(e) {
    if (carrito.length > 0) {
        e.preventDefault();
        let modal = new bootstrap.Modal(document.getElementById('modalVentaEnProceso'));
        modal.show();
    }
});

// Buscar cliente/autocomplete
document.getElementById('cliente').addEventListener('input', function() {
    const query = this.value;
    if (query.length < 2) {
        document.getElementById('cliente-sugerencias').innerHTML = '';
        document.getElementById('cliente_id').value = '';
        actualizarDescuento();
        return;
    }
    fetch('../controller/BuscarCliente.php?q=' + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach(cliente => {
                html += `<a class="list-group-item list-group-item-action" onclick="seleccionarCliente('${cliente.id}', '${cliente.nombre}', ${cliente.compras})">${cliente.nombre} (${cliente.matricula})</a>`;
            });
            document.getElementById('cliente-sugerencias').innerHTML = html;
        });
});

function seleccionarCliente(id, nombre, compras) {
    document.getElementById('cliente').value = nombre;
    document.getElementById('cliente_id').value = id;
    document.getElementById('cliente-sugerencias').innerHTML = '';
    actualizarDescuento(compras);
}

// Registro rápido de cliente (puedes hacer que abra un modal o redirija a un formulario)
document.getElementById('btnRegistrarCliente').addEventListener('click', function() {
    window.location.href = 'registro_cliente.php';
});

// Calcular descuento según compras
function actualizarDescuento(compras = null) {
    let comprasCliente = compras;
    if (comprasCliente === null && document.getElementById('cliente_id').value) {
        // Si ya hay cliente seleccionado, pedir compras al backend
        fetch('../controller/BuscarCliente.php?id=' + encodeURIComponent(document.getElementById('cliente_id').value))
            .then(res => res.json())
            .then(data => {
                aplicarDescuento(data.compras);
            });
    } else {
        aplicarDescuento(comprasCliente);
    }
}

function aplicarDescuento(compras) {
    if (!compras) compras = 0;
    if (compras >= 10) compras = 0; // Reinicia después de 10 compras
    if (compras >= 7) {
        descuentoPorcentaje = 15;
    } else if (compras >= 4) {
        descuentoPorcentaje = 10;
    } else if (compras >= 1) {
        descuentoPorcentaje = 5;
    } else {
        descuentoPorcentaje = 0;
    }
    renderResumen();
}

function renderResumen() {
    let resumen = '';
    let total = 0;
    carrito.forEach((item, idx) => {
        resumen += `<div>
            ${item.cantidad} x ${item.nombre} <span class="float-end">$${(item.precio * item.cantidad).toFixed(2)}</span>
            <button type="button" class="btn-quitar" onclick="quitarProducto(${idx})">Quitar</button>
        </div>`;
        total += item.precio * item.cantidad;
    });

    // Aplica descuento
    descuento = (total * descuentoPorcentaje) / 100;
    let totalConDescuento = total - descuento;

    document.getElementById('resumen-lista').innerHTML = resumen || '<em>No hay productos</em>';
    document.getElementById('resumen-total').innerText = totalConDescuento.toFixed(2);
    document.getElementById('productosInput').value = JSON.stringify(carrito);
    document.getElementById('totalInput').value = totalConDescuento.toFixed(2);
    document.getElementById('descuentoInput').value = descuento.toFixed(2);

    // Mostrar info de descuento
    const descuentoInfo = document.getElementById('descuento-info');
    if (descuentoPorcentaje > 0 && total > 0) {
        descuentoInfo.style.display = '';
        descuentoInfo.innerText = `Descuento aplicado: ${descuentoPorcentaje}% (-$${descuento.toFixed(2)})`;
    } else {
        descuentoInfo.style.display = 'none';
    }

    // Habilita el botón solo si hay productos, método de pago y cliente seleccionado
    const metodo = document.getElementById('metodo_pago').value;
    const cliente = document.getElementById('cliente_id').value;
    document.getElementById('btnPagar').disabled = carrito.length === 0 || !metodo || !cliente;
}

// --- Quitar producto con autorización de gerente si es empleado ---
function quitarProducto(idx) {
    const rolUsuario = "<?php echo $rolUsuario; ?>";
    if (rolUsuario === 'empleado') {
        // Mostrar modal de autorización de gerente antes de quitar
        const modal = new bootstrap.Modal(document.getElementById('modalAutorizacionGerente'));
        document.getElementById('formAutorizacionGerente').reset();
        document.getElementById('msgAutorizacion').innerText = '';
        window.idxProductoQuitar = idx;
        window.accionAutorizada = 'quitarProducto';
        modal.show();
    } else {
        // Gerente: quitar directo
        quitarProductoAutorizado(idx);
    }
}

// Nueva función para quitar el producto después de autorización
function quitarProductoAutorizado(idx) {
    carrito.splice(idx, 1);
    renderResumen();
}

document.querySelectorAll('.btn-producto').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');
        const nombre = this.getAttribute('data-nombre');
        const precio = parseFloat(this.getAttribute('data-precio'));
        let found = carrito.find(p => p.id === id);
        if (found) {
            found.cantidad += 1;
        } else {
            carrito.push({id, nombre, precio, cantidad: 1});
        }
        renderResumen();
    });
});

// --- CANCELAR VENTA CON AUTORIZACIÓN DE GERENTE ---
document.getElementById('cancelarVenta').addEventListener('click', function(e) {
    const rolUsuario = "<?php echo $rolUsuario; ?>";
    if (rolUsuario === 'empleado') {
        e.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('modalAutorizacionGerente'));
        document.getElementById('formAutorizacionGerente').reset();
        document.getElementById('msgAutorizacion').innerText = '';
        window.accionAutorizada = 'cancelarVenta';
        modal.show();
    } else {
        // Gerente: cancelar directo
        cancelarVentaYMostrarMensaje();
    }
});

document.getElementById('formAutorizacionGerente').addEventListener('submit', function(e) {
    e.preventDefault();
    const usuario = document.getElementById('usuario_gerente').value;
    const password = document.getElementById('password_gerente').value;
    fetch('autorizar_gerente.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.autorizado) {
            bootstrap.Modal.getInstance(document.getElementById('modalAutorizacionGerente')).hide();
            // Verifica si la acción es quitar producto o cancelar venta
            if (window.accionAutorizada === 'quitarProducto') {
                quitarProductoAutorizado(window.idxProductoQuitar);
                window.accionAutorizada = null;
                window.idxProductoQuitar = null;
            } else {
                cancelarVentaYMostrarMensaje();
            }
        } else {
            document.getElementById('msgAutorizacion').innerText = 'Credenciales incorrectas o sin permisos.';
        }
    })
    .catch(() => {
        document.getElementById('msgAutorizacion').innerText = 'Error de conexión.';
    });
});

function cancelarVentaYMostrarMensaje() {
    carrito = [];
    renderResumen();
    const msg = document.getElementById('msg-cancelada');
    msg.style.display = '';
    setTimeout(() => {
        msg.style.display = 'none';
    }, 2000);
}

document.getElementById('metodo_pago').addEventListener('change', renderResumen);

// Limpia el carrito visual si la venta fue exitosa
<?php if (isset($_GET['success'])): ?>
carrito = [];
renderResumen();
<?php endif; ?>

renderResumen();
</script>
</body>
</html>