<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\RecipesType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Nested;
use Elastica\Query\QueryString;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;

class RecipesController extends AbstractController
{
    /**
     * @Route("/recipe-add", name="recipe_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipesType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $entityManager->persist($recipe);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('recipies/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param RecipeRepository $recipeRepository
     * @Route("/", name="home")
     */
    public function index(RecipeRepository $recipeRepository)
    {
        $recipes = $recipeRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render('home.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, RepositoryManagerInterface $esRepository)
    {
        $query = $request->query->get('query', '*');

        $recipeRepository = $esRepository->getRepository(Recipe::class);
        $recipeResult = $recipeRepository->find($query);

        $ingredientRepository = $esRepository->getRepository(Ingredient::class);
        $ingredientsResult = $ingredientRepository->find($query);

        $ingredientsResult = array_reduce($ingredientsResult, function ($storage, $ingredient) {
           $storage = array_merge($storage, $ingredient->getRecipes()->toArray());
           return $storage;
        }, []);

        return $this->render('recipies/search.html.twig', [
            'recipeResult' => $recipeResult,
            'ingredientResult' => $ingredientsResult,
            'query' => $query
        ]);
    }

    /**
     * @param Recipe $recipe
     * @Route("/recipe/{recipeId}", name="recipe_show")
     */
    public function show(RecipeRepository $recipeRepository, $recipeId)
    {
        $recipe = $recipeRepository->find($recipeId);

        return $this->render('recipies/show.html.twig', [
            'recipe' => $recipe
        ]);
    }
}
