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
        $posts = $em->getRepository(Posts::class)->findAll();//Traigo todos los posts
        $postId = $em->getRepository(Posts::class)->find(2);//Traigo el post con id = 2
        $postTit = $em->getRepository(Posts::class)->findOneBy(['titulo'=>'Primer foto']);//Traigo uno por valor de un campo
        $postLikes = $em->getRepository(Posts::class)->findBy(['likes'=>'']);//Traigo varios por valor de un campo, en este caso los que tengan los likes vacios
        return $this->render('dashboard/index.html.twig', [
            'posts' => $posts,
            'postPorId' => $postId,
            'postPorTit' => $postTit,
            'postsPorLikes' => $postLikes
        ]);
    }
}
