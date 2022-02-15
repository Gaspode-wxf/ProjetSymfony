<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltresSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('champRecherche', TextType::class, [
                'label' => false,
                'required' => false,
                'empty_data' =>"",
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]])
            ->add('orga', CheckboxType::class, [
                'label' => "Sorties dont je suis l'organisateur",
                'required' => false,
                'attr' => ['checked'=>'checked'],
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => "Sorties auxquelles je suis inscrit/e",
                'required' => false,
                'attr' => ['checked'=>'checked'],
            ])
            ->add('pasInscrit', CheckboxType::class, [
                'label' => "Sorties auxquelles je ne suis pas inscrit/e",
                'required' => false,
                'attr' => ['checked'=>'checked'],
            ])
            ->add('perime', CheckboxType::class, [
                'label' => "Sorties passées",
                'required' => false,
            ])
            ->add('campus', ChoiceType::class, [
                'choices'  => [
                    'Tous les campus' => 'Tous les campus',
                    'SAINT-HERBLAIN' => 'SAINT-HERBLAIN',
                    'CHARTRES DE BRETAGNE' => 'CHARTRES DE BRETAGNE',
                    'LA ROCHE SUR YON' => 'LA ROCHE SUR YON',
                ],])
            ->add('dateMin', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
        ->add('dateMax', DateType::class, [
            'widget' => 'single_text',
            'required' => false,
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
