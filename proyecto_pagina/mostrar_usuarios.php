<?php
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: iniciar_sesion.php");
    exit;
}

$consulta = $conexion->query("SELECT id, nombre, correo, creado_en FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Usuarios registrados</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <div class="contenedor">
    <h1>Usuarios registrados</h1>

    <?php if ($consulta && $consulta->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Nombre</th><th>Correo</th><th>Creado</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while($f = $consulta->fetch_assoc()): ?>
            <tr>
              <td><?= (int)$f['id'] ?></td>
              <td><?= htmlspecialchars($f['nombre']) ?></td>
              <td><?= htmlspecialchars($f['correo']) ?></td>
              <td><?= htmlspecialchars($f['creado_en']) ?></td>
              <td class="actions">
                <a href="editar.php?id=<?= (int)$f['id'] ?>">Editar</a>
                <a href="eliminar.php?id=<?= (int)$f['id'] ?>" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="mensaje err">No hay usuarios registrados.</p>
    <?php endif; ?>

    <p style="margin-top:12px"><a href="privada.php">Volver</a></p>
  </div>
</body>
</html>
