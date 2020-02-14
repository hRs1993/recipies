<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Form\DataTransformers\TransformerAppenderTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipesType extends AbstractType
{
    use TransformerAppenderTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('difficulty')
            ->add('ingredients', TextType::class)
            ->add('tags', TextType::class)
        ;

        $this->appendTransformersToFields($builder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
