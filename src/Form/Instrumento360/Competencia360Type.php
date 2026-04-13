<?php

namespace App\Form\Instrumento360;

use App\Entity\Instrumento360\Competencia360;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Competencia360Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('tipo')
            ->add('createAt')
            ->add('createBy')
            ->add('updateAt')
            ->add('updateBy')
            ->add('empresa')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competencia360::class,
        ]);
    }
}
