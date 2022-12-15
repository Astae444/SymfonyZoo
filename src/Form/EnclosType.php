<?php

namespace App\Form;

use App\Entity\Enclos;
use App\Entity\Espace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnclosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Superficie')
            ->add('Capacité')
            ->add('Espace', EntityType::class, [
                'class'=>Espace::class, //choix de la classe liée
                'choice_label'=>"nom", //choix de ce qui sera affihé comme texte
                'multiple'=>false,
                'expanded'=>false
            ])
            ->add('Quarantaine')
            ->add("ok", SubmitType::class, ["label"=>"Confirmer"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enclos::class,
        ]);
    }
}