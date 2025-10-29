<?php
require_once "conexion.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die("ID inválido.");

$sql_sel = "SELECT id FROM usuarios WHERE id = ?";
$stm = $conexion->prepare($sql_sel);
if (!$stm) die("Error: " . $conexion->error);
$stm->bind_param("i", $id);
$stm->execute();
$res = $stm->get_result();
if (!$res || $res->num_rows !== 1) die("Usuario no encontrado.");
$stm->close();

$sql_del = "DELETE FROM usuarios WHERE id = ?";
$stm2 = $conexion->prepare($sql_del);
if (!$stm2) die("Error: " . $conexion->error);
$stm2->bind_param("i", $id);
if ($stm2->execute()) {
    $stm2->close();
    header("Location: mostrar_usuarios.php");
    exit;
} else {
    $stm2->close();
    die("Error al eliminar: " . $conexion->error);
}
