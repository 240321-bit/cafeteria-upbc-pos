<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: login.php");
    exit;
}
?>
<?php include "header.php"; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-dark text-white p-3 vh-150">
            <h4 class="text-center mb-4">Cafetería</h4>
            <ul class="nav flex-column">
              <!--  <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="dashboard_gerente.php"><i class="bi bi-house"></i> Inicio</a> -->
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="venta.php"><i class="bi bi-cash"></i> Venta</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="inventario.php"><i class="bi bi-box"></i> Inventario</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="gestion_usuarios.php"><i class="bi bi-people"></i> Gestión de Usuarios</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="reportes.php"><i class="bi bi-bar-chart"></i> Reportes y Análisis</a>
                </li>
                <li class="nav-item mt-3">
                    <a class="btn btn-danger w-100" href="../controller/LoginController.php?action=logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4">Bienvenido Gerente, <?php echo htmlspecialchars($_SESSION['usuario']); ?></h2>
            <div class="alert alert-info">Tienes acceso total al sistema, incluyendo la gestión de usuarios y reportes avanzados.</div>
            
            <div class="row mb-4">
                <!-- Tarjeta Ventas del Día -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-primary">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-cash"></i> Ventas del Día</h5>
                            <p class="card-text">Visualiza y confirma las ventas realizadas hoy.</p>
                            <a href="ventas_dia.php" class="btn btn-primary">Ir</a>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta Inventario -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-success">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-box"></i> Inventario</h5>
                            <p class="card-text">Consulta y administra los productos disponibles.</p>
                            <a href="inventario.php" class="btn btn-success">Ir</a>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta Reportes -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-warning">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-bar-chart"></i> Reportes</h5>
                            <p class="card-text">Genera reportes de ventas, usuarios y clientes.</p>
                            <a href="reportes.php" class="btn btn-warning">Ir</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfica de ejemplo -->
            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Estadísticas de Ventas</h5>
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie'],
            datasets: [{
                label: 'Ventas ($)',
                data: [500, 700, 400, 900, 650],
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        }
    });
</script>