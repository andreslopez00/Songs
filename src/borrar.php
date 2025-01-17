<?php
session_start();
require 'conexion.php';
require 'logger.php';  
require __DIR__ . '/../vendor/autoload.php';

$logger = LoggerManager::getLogger();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    $logger->warning('Intento de acceso sin autenticar para borrar canción');
    header("Location: login.php");
    exit;
}

// Verificar si se ha pasado el ID de la canción
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idCancion = $_GET['id'];

    // Obtener los detalles de la canción para mostrarla en la confirmación
    $query = $pdo->prepare("SELECT * FROM canciones WHERE ID = ?");
    $query->execute([$idCancion]);
    $cancion = $query->fetch();

    // Verificar si la canción existe
    if (!$cancion) {
        $logger->warning("Canción con ID $idCancion no encontrada para borrar");
        echo "Canción no encontrada.";
        exit;
    }
} else {
    // Si no se pasa un ID válido, redirigir a la página principal
    $logger->warning('No se pasó un ID válido para borrar');
    header("Location: index.php");
    exit;
}

// Procesar la eliminación si el formulario se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
    try {
        $query = $pdo->prepare("DELETE FROM canciones WHERE ID = ?");
        $query->execute([$idCancion]);

        $logger->info("Canción con ID $idCancion eliminada exitosamente");
        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        $logger->warning("Error al intentar eliminar la canción con ID $idCancion: " . $e->getMessage());
        echo "Error al eliminar la canción";
    }
}

include 'confirmar_borrado.html';
?>
