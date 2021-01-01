<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     */
    public function index(): Response
    {
        $user = new User(); //Creo un User
        $form = $this->createForm(UserType::class, $user);//Creo un formulario
        return $this->render('registro/index.html.twig', [
            'formulario' => $form->createView()//paso una vista del formulario
        ]);
    }
}
