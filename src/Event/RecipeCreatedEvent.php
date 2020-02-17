<?php


namespace App\Event;

use App\Entity\Recipe;
use Symfony\Contracts\EventDispatcher\Event;

class RecipeCreatedEvent extends Event
{
    public const NAME = 'recipe.created';

    protected $recipe;

    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    public function getRecipe()
    {
        return $this->recipe;
    }
}