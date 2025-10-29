<?php
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: iniciar_sesion.php");
    exit;
}

$nombre_usuario = htmlspecialchars($_SESSION["usuario_nombre"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Zona privada</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <div class="contenedor">
    <h1>Bienvenido a la zona privada</h1>
    <p>Hola, <strong><?= $nombre_usuario ?></strong></p>
    <p style="color:var(--muted)">Desde aquí puedes administrar usuarios registrados.</p>

    <div style="margin-top:18px;">
      <a class="boton" href="mostrar_usuarios.php">Ver usuarios</a>
      <a class="boton" href="registrar.php" style="margin-left:8px;background:#0ea5a3">Crear usuario</a>
      <a class="boton" href="salir.php" style="margin-left:8px;background:#ef4444">Cerrar sesión</a>
    </div>
  </div>
</body>
</html>
