<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response //recibo la solicitud por parametro, asigno en una variable lo que importé de la librería para cifrar la password  
    {
        $user = new User(); //Creo un User
        $form = $this->createForm(UserType::class, $user);//Creo un formulario
        $form->handleRequest($request);//con esta linea determino si el fomulario fue enviado
        if($form->isSubmitted() && $form->isValid()){//si el formulario fue enviado y el formulario es válido
            $em = $this->getDoctrine()->getManager();//Permite persistir los datos, es decir, guardar, borrar o actualizar en la BD
            $user->setPassword($passwordEncoder->encodePassword($user, $form['password']->getData()));//seteo para cifrar la password usando la librería
            // con "$form['password']->getData()" obtengo la contraseña que el usuario ingresó a través del formulario (password es el nombre del campo en el formulario)
            $em->persist($user);//Le digo que persista el user creado y que fue cargado con los datos obttenidos a través del formulario
            $em->flush();//  ¿¿¿???
            $this->addFlash('exito',User::REGISTRO_EXITOSO);//Envío un mansaje a través de la variable (o llave de acceso) "exito" avisando que se registró
            //el mensaje anterior lo obtengo por medio de una constante (User::REGISTRO_EXITOSO), es buena práctica de programación hacerlo así cuando siempre va 
            // a ser el mismo mensaje, la constante "REGISTRO_EXITOSO" la creé en la Entity "User"
            return $this->redirectToRoute('registro');//Redirecciona a una ruta a través del "name" del "@Route...", este caso quiero que redireccion a esta misma función "index()", pero puede redirgir a una función que esté en otro lugar
        }
        return $this->render('registro/index.html.twig', [
            'formulario' => $form->createView()//paso una vista del formulario
        ]);
    }
}
