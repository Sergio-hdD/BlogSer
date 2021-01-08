<?php

namespace App\Form;

use App\Entity\Comentarios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComentariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comentario', TextareaType::class, [
                'label' => 'Ingrese un comentario'])
//            ->add('fecha_publicacion')
//            ->add('user')
//            ->add('posts')
//Los que comento es para que no los ingrese el user
            ->add('Guardar', SubmitType::class)//agrego el botón
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comentarios::class,
        ]);
    }
}
