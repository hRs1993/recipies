<?php


namespace App\Form\DataTransformers;


use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IngredientsDataTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    const SEPARATOR = ', ';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($ingredients)
    {
        if (!$ingredients || !count($ingredients)) return '';

        $ingredientNames = array_reduce($ingredients, function ($storage, $ingredient) {
            $storage[] = trim($ingredient->getName());
            return $storage;
        });

        return implode(self::SEPARATOR, $ingredientNames);
    }

    public function reverseTransform($ingredients)
    {
        if (!$ingredients) return [];

        $ingredients = explode(self::SEPARATOR, $ingredients);

        $addedNewIngredient = false;

        array_walk($ingredients, function (&$ingredientName) use (&$addedNewIngredient) {
            $ingredient = $this->entityManager
                                ->getRepository(Ingredient::class)
                                ->findOneBy(['name' => $ingredientName]);
            if (!$ingredient) {
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientName);
                $this->entityManager->persist($ingredient);
                $addedNewIngredient = true;
            }

            $ingredientName = $ingredient;
        });

        if ($addedNewIngredient) {
            $this->entityManager->flush();
        }

        return $ingredients;
    }
}