<?php

namespace Songs;

class Cancion {
    private $id;
    private $autor;
    private $titulo;
    private $fecha;

    public function __construct($id, $autor, $titulo, $fecha) {
        $this->id = $id;
        $this->autor = $autor;
        $this->titulo = $titulo;
        $this->fecha = $fecha;
    }

    public function getId() {
        return $this->id;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public static function obtenerTodas($db) {
        $stmt = $db->query("SELECT * FROM canciones");
        $result = $stmt->fetchAll();
        $canciones = [];
        foreach ($result as $row) {
            $canciones[] = new Cancion($row['ID'], $row['autor'], $row['titulo'], $row['fecha']);
        }
        return $canciones;
    }

    public static function editar($db, $id, $autor, $titulo, $fecha) {
        $stmt = $db->prepare("UPDATE canciones SET autor = ?, titulo = ?, fecha = ? WHERE ID = ?");
        return $stmt->execute([$autor, $titulo, $fecha, $id]);
    }

    public static function borrar($db, $id) {
        $stmt = $db->prepare("DELETE FROM canciones WHERE ID = ?");
        return $stmt->execute([$id]);
    }
}
