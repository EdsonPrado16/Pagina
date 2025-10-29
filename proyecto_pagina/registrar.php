<?php
require_once "conexion.php";

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);

    if (empty($nombre) || empty($correo) || empty($_POST["contrasena"])) {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
        $tipo = "err";
    } else {
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena_hash) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $nombre, $correo, $contrasena);
            try {
                if ($stmt->execute()) {
                    $mensaje = "✅ Usuario registrado correctamente. Ya puedes iniciar sesión.";
                    $tipo = "ok";
                } else {
                    $mensaje = "❌ Error al registrar el usuario.";
                    $tipo = "err";
                }
            } catch (mysqli_sql_exception $e) {
                if ($conexion->errno === 1062) {
                    $mensaje = "⚠️ El correo ya está registrado. Intenta iniciar sesión o usa otro correo.";
                    $tipo = "warn";
                } else {
                    $mensaje = "❌ Error inesperado: " . $e->getMessage();
                    $tipo = "err";
                }
            }
            $stmt->close();
        } else {
            $mensaje = "❌ Error al preparar la consulta: " . $conexion->error;
            $tipo = "err";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar usuario</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<a href="index.php" class="back-to-top">Inicio</a>
<body class="oscuro">
    <div class="contenedor-form">
        <h1>Crear cuenta nueva 📝</h1>

        <?php if ($mensaje): ?>
            <div class="mensaje <?= $tipo ?>"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Registrar</button>
        </form>

        <p class="link">¿Ya tienes cuenta? <a href="iniciar_sesion.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>
