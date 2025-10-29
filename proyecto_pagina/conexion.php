<?php
// conexion.php - incluye sesión y conexión mysqli para todo el proyecto
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servidor = "localhost";
$usuario  = "root";
$contrasena = "root";
$base_datos = "proyecto_pagina";
$puerto = 8889;

session_start();

$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos, $puerto);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");