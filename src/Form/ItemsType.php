<?php

namespace App\Form;

use App\Entity\Items;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ItemsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ],
            ])
            ->add('ref', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ],
            ])
            ->add('part_condition', ChoiceType::class, [
                'choices' => [
                    'New' => 'New',
                    'Used' => 'Used',
                    'Refurbished' => 'Refurbished',
                ],
            ])
            ->add('photos', FileType::class, [
                'mapped' => false,
                'required' => false,

            ])
            ->add('quantity', ChoiceType::class, [
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'choice_translation_domain' => false,
            ])
            ->add('Inventory');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Items::class,
        ]);
    }
}
