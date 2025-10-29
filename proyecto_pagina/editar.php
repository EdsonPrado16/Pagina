<?php
require_once "conexion.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die("ID inválido.");

$usuario = null;
$sql_sel = "SELECT id, nombre, correo FROM usuarios WHERE id = ?";
$stm = $conexion->prepare($sql_sel);
if (!$stm) die("Error: " . $conexion->error);
$stm->bind_param("i", $id);
$stm->execute();
$res = $stm->get_result();
if ($res && $res->num_rows === 1) $usuario = $res->fetch_assoc();
else die("Usuario no encontrado.");
$stm->close();

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre'] ?? "");
    $correo = trim($_POST['correo'] ?? "");
    $pass_n = $_POST['contrasena_nueva'] ?? "";

    if ($nombre === "" || $correo === "") $mensaje = "Nombre y correo obligatorios.";
    elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $mensaje = "Correo no válido.";
    else {
        if ($pass_n !== "") {
            $hash = password_hash($pass_n, PASSWORD_DEFAULT);
            $sql_up = "UPDATE usuarios SET nombre = ?, correo = ?, contrasena_hash = ? WHERE id = ?";
            $stm_up = $conexion->prepare($sql_up);
            $stm_up->bind_param("sssi", $nombre, $correo, $hash, $id);
        } else {
            $sql_up = "UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?";
            $stm_up = $conexion->prepare($sql_up);
            $stm_up->bind_param("ssi", $nombre, $correo, $id);
        }

        if ($stm_up->execute()) {
            $mensaje = "Usuario actualizado.";
            $usuario['nombre'] = $nombre;
            $usuario['correo'] = $correo;
        } else {
            if ($conexion->errno === 1062) $mensaje = "El correo ya está registrado.";
            else $mensaje = "Error: " . $conexion->error;
        }
        $stm_up->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar usuario</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <div class="contenedor">
    <h1>Editar usuario</h1>
    <?php if ($mensaje): ?><div class="mensaje err"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

    <form method="post" action="editar.php?id=<?= (int)$usuario['id'] ?>">
      <label>Nombre</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

      <label>Correo</label>
      <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

      <label>Contraseña nueva (opcional)</label>
      <input type="password" name="contrasena_nueva" placeholder="Déjalo vacío para no cambiarla">

      <button type="submit">Guardar cambios</button>
    </form>

    <p style="margin-top:12px"><a href="mostrar_usuarios.php">Volver</a></p>
  </div>
</body>
</html>
