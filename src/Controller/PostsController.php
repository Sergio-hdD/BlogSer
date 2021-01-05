<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
            $brochureFile = $form->get('foto')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups! ha ocurrido un error, sorry :C');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setFoto($newFilename);
            }
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

    /**
     * @Route("/post/{id}", name="ver_Post")
     */
    public function verPost($id){
        $em = $this->getDoctrine()->getManager();
        $post =$em->getRepository(Posts::class)->find($id);
        return $this->render('posts/verPost.html.twig',['post'=>$post]);
    }
}




