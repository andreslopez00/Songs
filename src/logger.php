<?php
// Logger.php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerManager {
    public static function getLogger() {
        // Crear el logger con el canal 'CancionLogger'
        $logger = new Logger('CancionLogger');
        
        // Crear un handler para el archivo de log, con el nivel 'debug'
        $logger->pushHandler(new StreamHandler(__DIR__.'/logs/cancion.log', Logger::DEBUG));
        
        return $logger;
    }
}
