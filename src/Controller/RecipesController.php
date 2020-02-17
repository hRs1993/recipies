<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipesType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class RecipesController extends AbstractController
{
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
     * @param RecipeRepository $recipeRepository
     * @Route("/recipes", name="recipes")
     */
    public function recipes(RecipeRepository $recipeRepository)
    {
        $recipes = $recipeRepository->findBy([], ['id' => 'DESC']);

        return $this->render('recipies/recipes.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * @param Recipe $recipe
     * @Route("/recipe/{slug}", name="recipe_show")
     */
    public function show(RecipeRepository $recipeRepository, $slug)
    {
        $recipe = $recipeRepository->findOneBy([
            'slug' => $slug
        ]);

        return $this->render('recipies/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

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
     * @Route("/recipe-delete", name="recipe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, RecipeRepository $recipeRepository, EntityManagerInterface $entityManager)
    {
        $recipeId = $request->request->get('recipeId');
        if (!$recipeId) {
            return $this->redirectToRoute('home');
        }

        $recipe = $recipeRepository->find($recipeId);
        if (!$recipe) {
            return $this->redirectToRoute('home');
        }

        $entityManager->remove($recipe);
        $entityManager->flush();

        $this->addFlash('info', 'Recipe has been deleted');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/mail", name="mail")
     */
    public function mail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->to('arek.mackiewicz@gmail.com')
            ->from('makeit@cook.pl')
            ->text('New recipe available');

        try {
            $mailer->send($email);
        } catch (\Exception $e) {
            $this->addFlash('info', 'Cannot sent you an email');
            return $this->redirectToRoute('home');
        }

        $this->addFlash('info', 'Send email');

        return $this->redirectToRoute('home');
    }
}
