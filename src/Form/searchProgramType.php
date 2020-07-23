<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class searchProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('searchSerie', TextType::class, [
                'attr' =>[
                    'placeholder' => 'Mr Robot, Breaking Bad, ...'
                ],
                'label' => 'Rechercher une série'])
            ;
    }
}
