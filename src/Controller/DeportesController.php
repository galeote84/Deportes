<?php 
namespace App\Controller;
use App\Entity\Noticia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class DeportesController extends Controller{

    /**
     * @Route("/deportes/usuario", name="usuario" )
     */
    public function sesionUsuario(Request $request) {
        $usuario_get = $request->query->get('nombre');
        $session = $request->getSession();
        $session->set('nombre', $usuario_get);
        return $this->redirectToRoute('usuario_session', array('nombre' => $usuario_get));
    }

    /**
     * @Route("/deportes/usuario/{nombre}", name="usuario_session" )
     */
    public function paginaUsuario() {
        $session = new Session();
        $usuario = $session->get('nombre');
        return new Response(sprintf('Sesión iniciada con el atributo nombre: %s', $usuario
        ));
    }

    /*Cargar en la base de datos*/
    /**
     * @Route("/deportes/cargarbd", name="noticia")
     */
    public function cargarBd(){
        $em=$this->getDoctrine()->getManager();
        
        $noticia=new Noticia();
        $noticia->setSeccion("Tenis");
        $noticia->setEquipo("general");
        $noticia->setFecha("16022018");
        
        $noticia->setTextoTitular("torneo-benefico-caja-mágica");
        $noticia->setTextoNoticia("La próxima semana podremos disfrutar de un torneo benéfico en la caja mágica de Madrid dónde podremos disfrutar de grandes tenistas femeninos y másculinos de todo el mundo");
        $noticia->setImagen('torneo.jpg');
        
        $em->persist($noticia);
        $em->flush();
        return new Response("Noticia guardada con éxito con id:".$noticia->getId());
    }
    
    /**
     * @Route("/deportes/actualizar", name="actualizarNoticia")
     */
    public function actualizarBd(Request $request){
        $em=$this->getDoctrine()->getManager();
        $id=$request->query->get('id');
        $noticia=$em->getRepository(Noticia::class)->find($id);
        
        $noticia->setTextoTitular("Rafa-Nadal-a-punto-de-perder-el-numero-1");
        $noticia->setTextoNoticia("El español no depende de él mismo para mantener el número uno, Roger Federer se encuentra a una sola victoria de arrebatarle al mallorquín el título...");
        $noticia->setImagen('nadal.jpg');
        
        $em->flush();
      
        return new Response("Noticia actualizada!");
    }
    
    /**
     * @Route("/deportes/eliminar", name="eliminarNotica")
     */
    public function eliminarBd(Request $request){
        $em=$this->getDoctrine()->getManager();
        $id=$request->query->get('id');
        $noticia=$em->getRepository(Noticia::class)->find($id);
        $em->remove($noticia);
        $em->flush();
        return new Response("Noticia eliminada!");
    }
    /**
     * @Route("/deportes")
     */
    public function inicio(){
        return new Response('Mi página de deportes!');
    }
    
    /**
     * @Route("/deportes/{seccion}/{pagina}", 
     *        name="lista_paginas",
     *        requirements={"pagina"="\d+"},
     *        defaults={"seccion":"tenis"})
     */
    public function lista($seccion, $pagina=1){
        $em=$this->getDoctrine()->getManager();
        $repository=$this->getDoctrine()->getRepository(Noticia::class);
        //Buscamos las noticias de una sección
        $noticiaSec=$repository->findOneBy(['seccion'=>$seccion]);
        // Si el deporte que buscamos no se encuentra lanzamos la
        // excepcion 404 deporte no encontrado
        if(!$noticiaSec) {
            throw $this->createNotFoundException('Error 404 este deporte no está en nuestra Base de Datos');
        }
        //Almacenamos todas las noticias de una seccion en una lista
        $noticias=$repository->findBy(["seccion"=>$seccion]);
        return new Response("Hay un total de ".count($noticias)." noticias de la sección de ".$seccion);
    }
    /**
     * @Route("/deportes/{seccion}/{slug}",
     *        defaults={"seccion":"tenis"})
     */
    public function noticia($slug,$seccion){
        return new Response(sprintf(
                "Noticia de %s, con url dinámica=%s",
                $seccion, $slug));
    }
    
    /**
     * @Route("/deportes/{_locale}/{fecha}/{seccion}/{equipo}/{pagina}",
     *        defaults={"slug": "1", "_format":"html", "pagina":1},
     *        requirements={"_locale":"es|en",
     *                      "_format":"html|json|xml",
     *                      "fecha":"[\d+]{8}",
     *                      "pagina":"\d+"
     *                      })
     */
    public function rutaAvanzadaListado($_locale, $fecha, $seccion, $equipo, $pagina){
        return new Response(sprintf(
                "Listado de noticias en idioma=%s, fecha=%s, deporte=%s, equipo=%s, página=%s",
                $_locale, $fecha, $seccion, $equipo, $pagina));
    }
    
    /**
     * @Route("/deportes/{_locale}/{fecha}/{seccion}/{equipo}/{slug}.{_format}",
     *        defaults={"slug":"1","_format":"html"},
     *        requirements={
     *                      "_locale":"es|en",
     *                      "_format":"html|json|xml",
     *                      "fecha":"[\d+]{8}"
     *                      }
     *          )
     */
    public function rutaAvanzada($_locale, $fecha, $seccion, $equipo, $slug){
        // Simulamos una base de datos de equipos o personas
        $sports=["valencia", "barcelona","federer", "rafa-nadal"];
        // Si el equipo o persona que buscamos no se encuentra redirigimos
         // al usuario a la página de inicio
         if(!in_array($equipo,$sports)) {
            return $this->redirectToRoute('lista_paginas');
         }

        return new Response(sprintf(
                "Mi noticia en idioma=%s, fecha=%s, deporte=%s, equipo=%s, noticia=%s",
                $_locale, $fecha, $seccion, $equipo, $slug)
                );
    }
    
}
