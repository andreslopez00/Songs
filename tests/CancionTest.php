<?php

namespace Tests;

use App\Cancion;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CancionTest extends TestCase
{
    public function testCrearCancion()
    {
        $cancion = new Cancion('Autor Prueba', 'Título Prueba', '2023-01-01');
        $this->assertEquals('Autor Prueba', $cancion->getAutor());
        $this->assertEquals('Título Prueba', $cancion->getTitulo());
        $this->assertEquals('2023-01-01', $cancion->getFecha());
    }

    public function testEditarCancion()
    {
        $cancion = new Cancion('Autor Original', 'Título Original', '2023-01-01');
        $cancion->setAutor('Nuevo Autor');
        $cancion->setTitulo('Nuevo Título');
        $cancion->setFecha('2023-12-31');

        $this->assertEquals('Nuevo Autor', $cancion->getAutor());
        $this->assertEquals('Nuevo Título', $cancion->getTitulo());
        $this->assertEquals('2023-12-31', $cancion->getFecha());
    }

    public function testValidarFechaInvalida()
    {
        $this->expectException(InvalidArgumentException::class);
        new Cancion('Autor Prueba', 'Título Prueba', 'fecha-invalida');
    }
}
