<?php

namespace App\Controller;

use App\Entity\Ranking;
use App\Service\RankingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{
    private $rankingService;

    public function __construct(RankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    /**
     * @Route("/ranking/{slug}", name="ranking_detail")
     * @param Ranking $ranking
     * @return Response
     */
    public function index(Ranking $ranking): Response
    {
        return $this->render('ranking/ranking.html.twig', [
            'ranking' => $ranking,
        ]);
    }

    /**
     * @Route("/ranking/{slug}/recalculate")
     * @param Ranking $ranking
     * @return Response
     * @throws \Exception
     */
    public function recalculate(Ranking $ranking): Response
    {
        $this->rankingService->recalculate($ranking);

        return $this->redirectToRoute('ranking_detail', ['slug' => $ranking->getSlug()]);
    }
}
