<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encabezado Café</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #704d39;
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .contenedor {
            width: 100%;
            max-width: 1400px;
            height: 90px;
            margin: 0;
            padding-left: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1 {
            margin: 0;
            font-size: 80px;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <header>
<div class="contenedor">
    <div style="display: flex; flex-direction: row; align-items: center; width: 70%;">
        <div style="width:80px;height:80px;background:#d48375;border-radius:10px;margin-right:18px;"></div>
        <div style="display: flex; flex-direction: column; align-items: flex-start;">
            <h1>CAFETERÍA UPBC</h1>
            <div style="width:100%;height:6px;background:#a47054;margin-top:2px;border-radius:2px;"></div>
        </div>
    </div>
   <!-- <nav>
        <a href="#">Inicio</a>
        <a href="#">Productos</a>
        <a href="#">Contacto</a>
    </nav> -->
</div>
    </header>
</body>
</html>
