<?php

namespace App\Controller;

use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    /**
     * @Route("/season/{slug}", name="season_detail")
     * @param Season $season
     * @return Response
     */
    public function index(Season $season): Response
    {
        return $this->render('season/index.html.twig', [
            'season' => $season,
        ]);
    }
}
