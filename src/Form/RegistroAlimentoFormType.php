<?php
// src/Form/RegistroAlimentoFormType.php

namespace App\Form;

use App\Entity\RegistroAlimento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroAlimentoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreAlimento', TextType::class)
            ->add('cantidad', IntegerType::class, [
                'label' => 'Cantidad en gramos', // Personaliza la etiqueta del campo cantidad
            ]);
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegistroAlimento::class,
        ]);
    }
}

