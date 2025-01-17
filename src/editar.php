<?php
require 'conexion.php';
require 'logger.php';  
require __DIR__ . '/../vendor/autoload.php';

$logger = LoggerManager::getLogger();

// Obtener ID de la canción a editar
$id = $_GET['id'] ?? null;

if (!$id) {
    $logger->warning('Intento de acceso a editar sin ID de canción');
    header("Location: index.php");
    exit;
}

// Obtener datos de la canción
$query = $pdo->prepare("SELECT * FROM canciones WHERE ID = ?");
$query->execute([$id]);
$cancion = $query->fetch(PDO::FETCH_ASSOC);

if (!$cancion) {
    $logger->warning("Canción con ID $id no encontrada");
    header("Location: index.php");
    exit;
}

// Manejar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $autor = $_POST['autor'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $fecha = $_POST['fecha'] ?? '';

    try {
        $update = $pdo->prepare("UPDATE canciones SET autor = ?, titulo = ?, fecha = ? WHERE ID = ?");
        $update->execute([$autor, $titulo, $fecha, $id]);
        
        $logger->info("Canción con ID $id actualizada correctamente");
        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        $logger->warning("Error al actualizar la canción con ID $id: " . $e->getMessage());
        echo "Error al actualizar la canción";
    }
}

include 'editar_form.html';
?>
