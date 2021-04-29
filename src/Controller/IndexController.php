<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em       = $em;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $nextGame    = $this->em->getRepository(Game::class)->findNextGame();
        $missingBets = null;

        if ($this->getUser()) {
            $missingBets = $this->em->getRepository(Bet::class)->countUserMissingBets();
        }

        return $this->render('index/index.html.twig', [
            'nextGame'    => $nextGame,
            'missingBets' => $missingBets,
        ]);
    }
}
