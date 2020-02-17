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
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $query = $request->query->get('query', '*');


        $recipeResult = $this->recipeRepository->findByIngredients($query);

        return $this->render('recipies/search.html.twig', [
            'recipeResult' => $recipeResult,
            'query' => $query
        ]);
    }
}
