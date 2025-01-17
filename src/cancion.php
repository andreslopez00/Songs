<?php

namespace App;

use InvalidArgumentException;

class Cancion
{
    private $autor;
    private $titulo;
    private $fecha;

    public function __construct($autor, $titulo, $fecha)
    {
        $this->setAutor($autor);
        $this->setTitulo($titulo);
        $this->setFecha($fecha);
    }

    public function getAutor()
    {
        return $this->autor;
    }

    public function setAutor($autor)
    {
        $this->autor = $autor;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new InvalidArgumentException("Fecha inválida");
        }
        $this->fecha = $fecha;
    }
}
