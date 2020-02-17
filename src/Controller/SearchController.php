<?php

namespace App\Controller;

use App\ElasticaBundle\Repository\RecipeRepository;
use App\Entity\Recipe;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /** @var RecipeRepository $recipeRepository */
    private $esRecipeRepository;

    public function __construct(RepositoryManagerInterface $esRepository)
    {
        $this->esRecipeRepository = $esRepository->getRepository(Recipe::class);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $query = $request->query->get('query', '*');


        $recipeResult = $this->esRecipeRepository->findRecipe($query);

        return $this->render('search/search.html.twig', [
            'recipeResult' => $recipeResult,
            'query' => $query
        ]);
    }

    /**
     * @Route("/search/tag/{tag}", name="search_tag")
     */
    public function tag($tag)
    {
        if (!$tag) {
            return $this->redirectToRoute('home');
        }

        $recipes = $this->esRecipeRepository->findByTag($tag);

        return $this->render('search/search_tag.html.twig', [
            'recipeResult' => $recipes,
            'tag' => $tag
        ]);
    }

    /**
     * @Route("/search/ingredient/{ingredient}", name="search_ingredient")
     */
    public function ingredient($ingredient)
    {
        if (!$ingredient) {
            return $this->redirectToRoute('home');
        }

        $recipes = $this->esRecipeRepository->findByIngredient($ingredient);

        return $this->render('search/search_ingredient.html.twig', [
            'recipeResult' => $recipes,
            'ingredient' => $ingredient
        ]);
    }
}
