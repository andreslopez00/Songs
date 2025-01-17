<?php
session_start();
require 'conexion.php';
require 'logger.php';  
require __DIR__ . '/../vendor/autoload.php';  

$logger = LoggerManager::getLogger();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    $logger->warning('Intento de acceso sin autenticar');
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

// Registrar información en el log
$logger->info('Página de listado de canciones cargada');

// Verificación si hay canciones
if (count($canciones) > 0) {
    $logger->info('Se encontraron canciones en la base de datos');
} else {
    $logger->info('No se encontraron canciones en la base de datos');
}

include 'listado_canciones.html';
?>
