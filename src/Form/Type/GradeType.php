<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of GradeType
 *
 * @author andre
 */
class GradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('horario_1', EntityType::class, [
                'required'   => true,
                'class' => \App\Entity\Materia::class,
                'choice_label' => 'nome'
            ])
            ->add('horario_2', EntityType::class, [
                'required'   => true,
                'class' => \App\Entity\Materia::class,
                'choice_label' => 'nome'
            ])
            ->add('horario_3', EntityType::class, [
                'required'   => true,
                'class' => \App\Entity\Materia::class,
                'choice_label' => 'nome'
            ])
            ->add('horario_4', EntityType::class, [
                'required'   => true,
                'class' => \App\Entity\Materia::class,
                'choice_label' => 'nome'
            ])
            ->add('save', SubmitType::class)
        ;
    }
}
