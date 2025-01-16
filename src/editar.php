<?php
require 'conexion.php';

// Obtener ID de la canción a editar
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

// Obtener datos de la canción
$query = $pdo->prepare("SELECT * FROM canciones WHERE ID = ?");
$query->execute([$id]);
$cancion = $query->fetch(PDO::FETCH_ASSOC);

if (!$cancion) {
    header("Location: index.php");
    exit;
}

// Manejar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $autor = $_POST['autor'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $fecha = $_POST['fecha'] ?? '';

    $update = $pdo->prepare("UPDATE canciones SET autor = ?, titulo = ?, fecha = ? WHERE ID = ?");
    $update->execute([$autor, $titulo, $fecha, $id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Canción</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow-lg p-4">
            <h1 class="text-center mb-4">Editar Canción</h1>
            
            <form method="POST" action="editar.php?id=<?= htmlspecialchars($id) ?>">
                <div class="mb-3">
                    <label for="autor" class="form-label">Autor</label>
                    <input type="text" id="autor" name="autor" class="form-control" value="<?= htmlspecialchars($cancion['autor']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" value="<?= htmlspecialchars($cancion['titulo']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" value="<?= htmlspecialchars($cancion['fecha']) ?>" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
