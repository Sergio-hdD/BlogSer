<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/registrar-posts", name="registrarPosts")
     */
    public function index(Request $request): Response
    {
        $post = new Posts();//creo un posts
        $form = $this->createForm(PostsType::class, $post);//a través del formulario tomo los datos para el post
        $form->handleRequest($request);//verifico si el formulario fue enviado
        if($form->isSubmitted() && $form->isValid()){//Si fue enviado is es válido
            $user = $this->getUser();//obtengo el usuario logeado
            $post->setUser($user);//seteo el user del post con el usuario logueado
            $em = $this->getDoctrine()->getManager();//Es lo que me va a permitir hace ABM y/o consultas a la BD
            $em->persist($post);//persisto el objeto post
            $em->flush();//¿¿completa el guardado en la BD??
            return $this->redirectToRoute('dashboard');//redirecciono a DashboardController
         }
        return $this->render('posts/index.html.twig', [
            'formulario' => $form->createView()
        ]);
    }
}
