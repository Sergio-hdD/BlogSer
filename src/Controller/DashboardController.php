<?php

namespace App\Controller;

use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Posts::class)->traerTodosLosPosts();//Traigo todos los posts, consulta personalizada
        return $this->render('dashboard/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
