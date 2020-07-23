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
                    'placeholder' => 'IMDB, Genius, ApiMedic ...'
                ],
                'label' => 'Rechercher une API'])
            ;
    }
}
