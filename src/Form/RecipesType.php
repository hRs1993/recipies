<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Form\DataTransformers\IngredientsDataTransformer;
use App\Form\DataTransformers\TagsDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipesType extends AbstractType
{
    /**
     * @var IngredientsDataTransformer
     */
    private $ingredientsDataTransformer;
    /**
     * @var TagsDataTransformer
     */
    private $tagsDataTransformer;

    public function __construct(
        IngredientsDataTransformer $ingredientsDataTransformer,
        TagsDataTransformer $tagsDataTransformer)
    {
        $this->ingredientsDataTransformer = $ingredientsDataTransformer;
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('difficulty')
            ->add('ingredients', TextType::class)
            ->add('tags', TextType::class)
        ;

        $builder->get('ingredients')->addModelTransformer($this->ingredientsDataTransformer);
        $builder->get('tags')->addModelTransformer($this->tagsDataTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
