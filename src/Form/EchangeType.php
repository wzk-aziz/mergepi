<?php

namespace App\Form;

use App\Entity\Echange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EchangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('etat', TextType::class, [
            'data' => 'non valide']) // Set default value here
            ->add('offre', TextType::class)
            ->add('image',FileType::class,[
                'mapped' => false,
                'required' => false], array('label' => 'image'))
           

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Echange::class,
        ]);
    }
}
