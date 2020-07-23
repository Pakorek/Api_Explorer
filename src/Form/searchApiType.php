<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class searchApiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('search', TextType::class, [
                'attr' =>[
                    'placeholder' => 'Rechercher une API : IMDB, Genius, ApiMedic ...',
                    'class' => "form-control-lg"
                ],
                'label' => 'Rechercher une API'])
            ;
    }
}
