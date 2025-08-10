<?php
if (isset($_GET['error'])) {
  echo "<script>alert('Usuario o contraseña incorrectos');</script>";
}
if (isset($_GET['registro'])) {
  echo "<script>alert('Usuario registrado correctamente, ahora inicia sesión.');</script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta - Cafetería</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #6d4c3d;
            min-height: 100vh;
        }
        .main-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            margin: 32px auto;
            padding: 32px 24px;
            max-width: 1200px;
            min-height: 85vh;
            position: relative;
            overflow: hidden; /* <-- Esto evita que las imágenes se salgan */
        }
        .login-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 32px 24px 16px 24px;
            margin: 0 auto;
            max-width: 400px;
            border: 4px solid #e2cfc3;
            z-index: 2;
        }
        .login-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 24px;
        }
        .brand-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #7a5235;
            margin-bottom: 0;
            border-bottom: 5px solid #c98a6c;
            display: inline-block;
        }
        .subtitle {
            font-size: 2rem;
            color: #222;
            margin-bottom: 16px;
        }
        .form-control {
            border: 2px solid #d48375;
            border-radius: 8px;
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #c98a6c;
            box-shadow: 0 0 0 0.2rem rgba(201,138,108,.25);
        }
        .btn-login {
            background: #d48375;
            color: #fff;
            font-weight: bold;
            border-radius: 10px;
            font-size: 1.1rem;
            padding: 12px 0;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: #b96a5a;
        }
        .coffee-cup {
            display: block;
            margin: 24px auto 0 auto;
            width: 80px;
            height: auto;
        }
        /* Granos de café decorativos */
        .bean {
            position: absolute;
            opacity: 0.12;
            z-index: 1;
            pointer-events: none;
        }
        .bean1 { top: 20px; left: 20px; width: 120px; }
        .bean2 { bottom: 20px; right: 20px; width: 140px; }
        .bean3 { top: 40px; right: 80px; width: 90px; }
        .bean4 { bottom: 40px; left: 80px; width: 90px; }
        @media (max-width: 768px) {
            .main-box { padding: 12px 2px; }
            .login-card { padding: 18px 8px 8px 8px; }
            .brand-title { font-size: 2rem; }
            .subtitle { font-size: 1.2rem; }
            .bean1, .bean2, .bean3, .bean4 { width: 60px !important; }
        }
    </style>
</head>
<body>
    <div class="main-box position-relative">
        <!-- Granos de café decorativos -->
        <img src="/CafeteriaUPBC/view/img/bean.png" class="bean bean1" alt="Grano de café">
        <img src="/CafeteriaUPBC/view/img/bean.png" class="bean bean2" alt="Grano de café">
        <img src="/CafeteriaUPBC/view/img/bean.png" class="bean bean3" alt="Grano de café">
        <img src="/CafeteriaUPBC/view/img/bean.png" class="bean bean4" alt="Grano de café">

        <div class="row">
            <div class="col-12 col-md-7 d-flex flex-column justify-content-center">
                <h1 class="brand-title">PUNTO DE VENTA</h1>
                <div class="subtitle mt-2 mb-4">CAFETERÍA</div>
            </div>
            <div class="col-12 col-md-5 d-flex align-items-center justify-content-center">
                <div class="login-card">
                    <div class="login-title">BIENVENIDO</div>
                    <form action="/CafeteriaUPBC/controller/LoginController.php" method="POST">
                        <div class="mb-3">
                            <input type="text" name="usuario" class="form-control" placeholder="USUARIO" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="contrasena" class="form-control" placeholder="CONTRASEÑA" required>
                        </div>
                        <button class="btn btn-login w-100" type="submit">INICIAR SESIÓN</button>
                    </form>
                    <!-- Imagen de la taza de café -->
                    <img src="/CafeteriaUPBC/view/img/cup.png" class="coffee-cup" alt="Taza de café">
                </div>
            </div>
        </div>
    </div>
</body>
</html>