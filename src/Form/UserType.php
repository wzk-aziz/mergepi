<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $availableRoles = ['ROLE_ADMIN', 'ROLE_USER'];
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => $availableRoles,
                'multiple' => true,
                'expanded' => true,
                ])
            //->add('password')
            ->add('name')
            ->add('firstname')
            ->add('createdat')
            ->add('age')
            ->add('phone')
            ->add('profession')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
