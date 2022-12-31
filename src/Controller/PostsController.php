<?php

namespace App\Controller;

use App\Entity\Comentarios;
use App\Entity\Posts;
use App\Form\ComentariosType;
use App\Form\PostsType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/posts")
 */
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
        if($form->isSubmitted() && $form->isValid()){//Si fue enviado y es válido
            
            $fotoFile = $form->get('foto')->getData();
            if ($fotoFile) {

                $newFilename = $this->getUser()->getEmail().'_foto_'.(new \DateTime('now'))->format('d_m_Y_H_i_s').'.'.$fotoFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fotoFile->move(
                        $this->getParameter('photos_directory'), //Es el directorio dónde se van a guardar los archivos, definido en "config\services.yaml" 
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
     * @Route("/likes", name="app_likes", methods={"POST"})
     */
    public function like(Request $request){
        if($request->isXmlHttpRequest()){ //Si es una petición ajax (para probarlo desde postman se debe sacar $request->isXmlHttpRequest() y poner uin "true")
            return new JsonResponse( ['likes' => $this->modificarLikesDelPostYtraerLikes($request)] );
        }else{
            throw new \Exception("Estás tratando de hackearme?");   
        }

    }

    /**
     * @Route("/{id}", name="ver_Post", methods={"GET"})
     */
    public function verPost($id, Request $request, PaginatorInterface $paginator){
        $em = $this->getDoctrine()->getManager();
        $comentario = new Comentarios();
        $post =$em->getRepository(Posts::class)->find($id);
        //$comentarios = $em->getRepository(Comentarios::class)->findOneBy(['posts'=>$post]);//Traigo uno por valor de un campo, trayendo todos los campos
        $queryComentarios = $em->getRepository(Comentarios::class)->traerComentariosDelPost($post->getId());
        $form = $this->createForm(ComentariosType::class, $comentario);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $comentario->setPosts($post);
            $comentario->setUser($user);
            $em->persist($comentario);
            $em->flush();
            $this->addFlash('Exito', Comentarios::COMENTARIO_AGREGADO);
            return $this->redirectToRoute('ver_Post',['id'=>$post->getId()]);
        }
        $pagination = $paginator->paginate(
            $queryComentarios, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );
        return $this->render('posts/verPost.html.twig', [
            'post'=>$post,
            'formComentarios'=>$form->createView(),
            'comentariosDelPost' => $pagination
        ]);
    }

    /**
     * @Route("/postsDelUser", name="posts_Del_User")
     */
    public function postsDelUserLoguedo(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();//obtengo el user logueado
        $posts =$em->getRepository(Posts::class)->findBy(['user'=>$user]);//traigo todos los posts del user logueado
        return $this->render('posts/verPostsDelUser.html.twig',['posts'=>$posts]);
    }

    public function modificarLikesDelPostYtraerLikes(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $idPost = $request->get('id');
        $idUser = $user->getId();
        $post = $em->getRepository(Posts::class)->find($idPost);
        $likes = $post->getLikes();
        if(in_array($idUser, $likes)){
            $posicionInArray = array_search($idUser, $likes); //Obtenemos la posición
            unset($likes[$posicionInArray]);//Lo sacamos del array por su posicion
        }else{
            array_push($likes, $idUser); //a la lista de id's de usuarios que dieron like, agrego el nuevo
        }
        $post->setLikes($likes);
        $em->flush();
        return $likes;
    }

}
