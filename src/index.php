<?php
session_start();
require 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Función para obtener todas las fechas únicas
function obtenerFechas($pdo) {
    $query = $pdo->query("SELECT DISTINCT fecha FROM canciones ORDER BY fecha DESC");
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

// Obtener las fechas disponibles
$fechasDisponibles = obtenerFechas($pdo);

// Filtrar canciones por fecha (si se selecciona una)
$fechaSeleccionada = $_GET['fecha'] ?? 'Todas';
$sql = "SELECT * FROM canciones";
$params = [];

if ($fechaSeleccionada !== 'Todas') {
    $sql .= " WHERE fecha = ?";
    $params[] = $fechaSeleccionada;
}

$sql .= " ORDER BY fecha DESC";
$query = $pdo->prepare($sql);
$query->execute($params);
$canciones = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Canciones</title>
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
        <h1 class="text-center mb-4">Lista de Canciones</h1>
        
        <!-- Filtro por fechas -->
        <form method="GET" action="index.php" class="mb-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <label for="fecha" class="form-label">Filtrar por fecha:</label>
                    <select id="fecha" name="fecha" class="form-select" onchange="this.form.submit()">
                        <option value="Todas" <?= $fechaSeleccionada === 'Todas' ? 'selected' : '' ?>>Todas las fechas</option>
                        <?php foreach ($fechasDisponibles as $fecha): ?>
                            <option value="<?= htmlspecialchars($fecha) ?>" <?= $fechaSeleccionada === $fecha ? 'selected' : '' ?>>
                                <?= htmlspecialchars($fecha) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <!-- Tabla de canciones -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Autor</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($canciones) > 0): ?>
                        <?php foreach ($canciones as $cancion): ?>
                            <tr>
                                <td><?= htmlspecialchars($cancion['ID']) ?></td>
                                <td><?= htmlspecialchars($cancion['autor']) ?></td>
                                <td><?= htmlspecialchars($cancion['titulo']) ?></td>
                                <td><?= htmlspecialchars($cancion['fecha']) ?></td>
                                <td>
                                    <?php 
                                    // Calcular la diferencia de días entre la fecha de la canción y la fecha actual
                                    $fechaCancion = strtotime($cancion['fecha']);
                                    $fechaActual = strtotime("now");
                                    $diferenciaDias = ($fechaActual - $fechaCancion) / (60 * 60 * 24);

                                    // Si la canción tiene más de una semana, mostrar el botón de borrar
                                    if ($diferenciaDias > 7): ?>
                                        <a href="borrar.php?id=<?= $cancion['ID'] ?>" class="btn btn-danger btn-sm">Borrar</a>
                                    <?php endif; ?>

                                    <?php 
                                    // Si la canción es más reciente que hoy, mostrar el botón de editar
                                    if ($diferenciaDias <= 7): ?>
                                        <a href="editar.php?id=<?= $cancion['ID'] ?>" class="btn btn-primary btn-sm">Editar</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron canciones para la fecha seleccionada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
