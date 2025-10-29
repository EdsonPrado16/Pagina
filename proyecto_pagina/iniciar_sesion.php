<?php
require_once "conexion.php";

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if ($correo === "" || $contrasena === "") {
        $mensaje = "Ingresa correo y contraseña.";
    } else {
        $sql = "SELECT id, nombre, correo, contrasena_hash FROM usuarios WHERE correo = ?";
        $stmt = $conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows === 1) {
                $usuario = $res->fetch_assoc();
                if (password_verify($contrasena, $usuario["contrasena_hash"])) {
                    $_SESSION["usuario_id"] = $usuario["id"];
                    $_SESSION["usuario_nombre"] = $usuario["nombre"];
                    // redirección JS para evitar problemas de headers si hay salida accidental
                    echo "<script>window.location.href='privada.php';</script>";
                    exit;
                } else {
                    $mensaje = "Contraseña incorrecta.";
                }
            } else {
                $mensaje = "No existe cuenta con ese correo.";
            }
            $stmt->close();
        } else {
            $mensaje = "Error en la consulta: " . $conexion->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="estilo.css">
</head>
      <a href="index.php" class="back-to-top">Inicio</a>
<body>
    <div class="login-container">
  <h1>Iniciar sesión</h1>
    <?php if ($mensaje): ?><div class="mensaje err"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
    <form method="post" action="iniciar_sesion.php" novalidate>
      <label for="correo">Correo</label>
      <input id="correo" name="correo" type="email" required>

      <label for="contrasena">Contraseña</label>
      <input id="contrasena" name="contrasena" type="password" required>

      <button type="submit">Entrar</button>
    </form>

    <p style="margin-top:12px;color:var(--muted)">¿No tienes cuenta? <a href="registrar.php">Regístrate</a></p>
</div>
</body>
</html>
