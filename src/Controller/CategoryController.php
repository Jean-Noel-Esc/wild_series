<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
