<?php

namespace App\Form;

use App\Entity\BugReport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BugReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class, [
//                'attr' =>[
//                    'placeholder' => 'Rechercher une API : IMDB, Genius, ApiMedic ...',
//                    'class' => "form-control-lg"
//                ],
                'label' => 'Un problème est survenu ?'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BugReport::class,
        ]);
    }
}
