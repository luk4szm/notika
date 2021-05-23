<?php

namespace App\Controller;

use App\Entity\Ranking;
use App\Service\RankingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{
    private $em;
    private $rankingService;

    public function __construct(EntityManagerInterface $em, RankingService $rankingService)
    {
        $this->em             = $em;
        $this->rankingService = $rankingService;
    }

    /**
     * @Route("/ranking/{slug}", name="ranking_detail")
     * @param Ranking $ranking
     * @return Response
     */
    public function index(Ranking $ranking): Response
    {
        return $this->render('ranking/index.html.twig', [
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
