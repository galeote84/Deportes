<?php 
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
class DeportesController{
    public function inicio(){
        return new Response('Mi primera página en Symfony!');
    }
}
