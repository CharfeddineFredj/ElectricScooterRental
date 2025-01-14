<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model', HiddenType::class, ['attr' => ['id' => 'modelInput']])
            ->add('prix', HiddenType::class , ['attr' => ['id' => 'priceInput']]) // Champ caché pour le prix
            ->add('periode')
            ->add('qte')
            ->add('impot')
            ->add('total')
            ->add('mtotal')
            ->add('status',HiddenType::class)
            ->add('datersv', DateType::class, [
                'widget' => 'single_text', // This tells Symfony to use the HTML5 date input type
                'attr' => ['class' => 'form-control', 'placeholder' => 'YYYY-MM-DD'],
                'format' => 'yyyy-MM-dd', // Format is important if the data is not being transformed correctly
            ])
            ->add('heurersv', TimeType::class, [
                'widget' => 'single_text',  // This configuration uses a single input field for time.
                'input'  => 'string',       // Stores time as a string. Change to 'datetime' if you use a \DateTime object.
                'attr' => ['class' => 'form-control timepicker', 'placeholder' => 'HH:MM'],
            ]);
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}