<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program's Entity.
     *
     * @Route("/", name="index")
     *
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

        return $this->render('Program/index.html.twig', ['programs' => $programs]);
    }

    /**
     * Getting a program by id.
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     */
    public function show(Program $program): Response
    {
        //$program = $this->getDoctrine()->getRepository(Program::class)->findOneBy(['id' => $id]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program->getId().' found in program\'s table.'
            );
        }
        $seasons = $program->getSeasons();

        return $this->render('Program/show.html.twig', [
            'program' => $program, 'seasons' => $seasons,
        ]);
    }

    /**
     * Show season off a program.
     *
     * @Route("/{programId}/seasons/{seasonId}", name="season_show")
     */
    public function showSeason(Program $programId, Season $seasonId)
    {
        return $this->render('Program/season_show.html.twig', [
            'program' => $programId, 'season' => $seasonId,
        ]);
    }

    /**
     * @Route("{programId}/seasons/{seasonId}/episodes/{episodeId}",
     * name="episode_show",
     * requirements={"program"="\d+", "season"="\d+", "episode"="\d+"},
     * methods={"GET"})
     */
    public function showEpisode(Program $programId, Season $seasonId, Episode $episodeId)
    {
        return $this->render(
            'Program/episode_show.html.twig',
            ['program' => $programId, 'season' => $seasonId, 'episode' => $episodeId]
        );
    }
}
