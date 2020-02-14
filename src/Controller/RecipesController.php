<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipesType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
}
