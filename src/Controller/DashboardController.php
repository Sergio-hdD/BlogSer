<?php

namespace App\Controller;

use App\Entity\Comentarios;
use App\Entity\Posts;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    //estaba así @Route("/dashboard", name="dashboard"), pero lo cambié para que sea la homepage
    /**
     * @Route("/", name="dashboard")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();//Obtengo el user logueado
        //agrego seguridad (no recomendada por el framework) para entrar al dashbord
        if($user){//Si hay un usuario loguedo
            $em = $this->getDoctrine()->getManager();
            $queryPosts = $em->getRepository(Posts::class)->traerQueryDeTodosLosPosts();//Traigo la query de todos los posts, consulta personalizada
            //$comentarios = $em->getRepository(Comentarios::class)->findOneBy(['user'=>$user]);//Traigo uno por valor de un campo, trae todos los campos
            $comentarios = $em->getRepository(Comentarios::class)->traerComentariosDelUser($user->getId());//Trae solo los campos que voy a usar (personlizada)
            $pagination = $paginator->paginate(
                $queryPosts, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                2 /*limit per page (con este último numero le digo de a cuantos muestra en cada página */
            );
            return $this->render('dashboard/index.html.twig', [
                'pagination' => $pagination,
                'comentariosDelUser' => $comentarios
            ]);
        } else {
            return $this->redirectToRoute('app_login');//redirecciono a SecurityController para que haga el login
        }
    }
}
