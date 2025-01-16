<?php
session_start();
require 'conexion.php';
require 'logger.php';  // Importamos el Logger
require __DIR__ . '/../vendor/autoload.php';
// Inicializamos el logger
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
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Borrado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Canciones Radio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="text-center mb-4">Confirmar Borrado de Canción</h1>

        <!-- Confirmación de eliminación -->
        <div class="alert alert-warning">
            <h4 class="alert-heading">¿Estás seguro de que deseas eliminar esta canción?</h4>
            <p><strong>Título:</strong> <?= htmlspecialchars($cancion['titulo']) ?></p>
            <p><strong>Autor:</strong> <?= htmlspecialchars($cancion['autor']) ?></p>
            <p><strong>Fecha:</strong> <?= htmlspecialchars($cancion['fecha']) ?></p>
        </div>

        <!-- Formulario de confirmación -->
        <form method="POST">
            <div class="d-flex justify-content-center">
                <button type="submit" name="confirmar" class="btn btn-danger btn-lg me-3">Eliminar</button>
                <a href="index.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
