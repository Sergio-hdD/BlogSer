<?php

namespace App\Controller;

use App\Entity\Posts;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $queryPosts = $em->getRepository(Posts::class)->traerQueryDeTodosLosPosts();//Traigo la query de todos los posts, consulta personalizada
        $pagination = $paginator->paginate(
            $queryPosts, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page (con este último numero le digo de a cuantos muestra en cada página */
        );
        return $this->render('dashboard/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
