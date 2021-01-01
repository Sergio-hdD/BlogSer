<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     */
    public function index(Request $request): Response //recibo la solicitud por parametro  
    {
        $user = new User(); //Creo un User
        $form = $this->createForm(UserType::class, $user);//Creo un formulario
        $form->handleRequest($request);//con esta linea determino si el fomulario fue enviado
        if($form->isSubmitted() && $form->isValid()){//si el formulario fue enviado y el formulario es válido
            $em = $this->getDoctrine()->getManager();//Permite persistir los datos, es decir, guardar, borrar o actualizar en la BD
            $user->setBaneado(false);//seteo en false el baneado
            $user->setRoles(['ROLE_USER']);//Seteo un rol (se debe pasrar como array)
            //tanto el campo "baneado" como el "roles" los saqué del formulario para que no los ingrese el usuario, pero como no pueden ser null los seteo acá 
            $em->persist($user);//Le digo que persista el user creado y que fue cargado con los datos obttenidos a través del formulario
            $em->flush();//  ¿¿¿???
            $this->addFlash('exito','Se ha registrado exitosamente');//Envío un mansaje a través de la variable (o llave de acceso) "exito" avisando que se registró
            return $this->redirectToRoute('registro');//Redirecciona a una ruta a través del "name" del "@Route...", este caso quiero que redireccion a esta misma función "index()", pero puede redirgir a una función que esté en otro lugar
        }
        return $this->render('registro/index.html.twig', [
            'formulario' => $form->createView()//paso una vista del formulario
        ]);
    }
}
