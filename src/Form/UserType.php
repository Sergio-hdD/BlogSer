<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)// El ", EmailType::class" lo agregué a mano para especificar que el el campo es de tipo Email, ya que de lo contrario Symfony asume que es de tipo texto
//            ->add('roles')
            ->add('password', PasswordType::class)//Lo mismo que con email
//            ->add('baneado')
            ->add('nombre')//Es de tipo texto
            ->add('Registrar',SubmitType::class)//(creado a mano) para enviar los datos a través de "Registrar"
        ;
      // lo que comenté es porque no quiero que lo pueda ingresara el usuario  
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
