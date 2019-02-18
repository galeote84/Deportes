<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoticiaRepository")
 */
class Noticia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $seccion;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $equipo;
    /**
     * @ORM\Column(type="string", length=8)
     */
    private $fecha;
    
    /**
     * @ORM\Column(type="string", length=500)
     */
    private $textoNoticia;
    /**
     * @ORM\Column(type="string",length=500)
     */
    private $textoTitular;
    /**
     * @ORM\Column(type="string", length=25)
     */
    private $imagen;

    /*GETTERS&SETTERS*/
    public function getId()
    {
        return $this->id;
    }
    
    public function getSeccion(){
        return $this->seccion;
    }
    public function getEquipo(){
        return $this->equipo;
    }
    public function getFecha(){
        return $this->fecha;
    }
    
    public function setSeccion($seccion){
        $this->seccion=$seccion;
    }
    public function setEquipo($equipo){
        $this->equipo=$equipo;
    }
    public function setFecha($fecha){
        $this->fecha=$fecha;
    }
    
    public function getTextoNoticia(){
        return $this->textoNoticia;
    }
    public function getTextoTitular(){
        return $this->textoTitular;
    }
    public function getImagen(){
        return $this->imagen;
    }
    public function setTextoNoticia($noticia){
        $this->textoNoticia=$noticia;
    }
    public function setTextoTitular($titular){
        $this->textoTitular=$titular;
    }
    public function setImagen($imagen){
        $this->imagen=$imagen;
    }
    
}
