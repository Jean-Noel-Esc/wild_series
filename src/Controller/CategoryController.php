<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * Recupère toutes les catégories présentes en bdd vers une vue templates/category/index.html.twig.
     *
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        if (!$categories) {
            throw $this->createNotFoundException('No category found ! ');
        }

        return $this->render('Category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * controller du form pr add une new category.
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        // Create a new Category Object
        $category = new Category();

        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            $entityManager = $this->getDoctrine()->getManager();

            // For example : persiste & flush the entity
            $entityManager->persist($category);
            $entityManager->flush();
            // And redirect to a route that display the result
            return $this->redirectToRoute('category_index');
        }

        // Render the form
        return $this->render('Category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * selection et affichage des series d'une category.
     *
     * @Route("/{categoryName}", name="show")
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['name' => $categoryName]);

        $programs = $this->getDoctrine()->getRepository(Program::class)->findBy(['category' => 1], ['id' => 'DESC'], 3);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : '.$categoryName.' found in category\'s table.'
            );
        }

        return $this->render('Category/show.html.twig', [
            'category' => $categoryName,
            'programs' => $programs,
        ]);
    }
}
