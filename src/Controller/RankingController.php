<?php

namespace App\Controller;

use App\Entity\Ranking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{
    /**
     * @Route("/ranking/{slug}", name="ranking_detail")
     */
    public function index(Ranking $ranking): Response
    {
        return $this->render('ranking/index.html.twig', [
            'ranking' => $ranking,
        ]);
    }
}
