<?php

namespace App\Controller;

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
        $this->em = $em;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $nextGame       = $this->em->getRepository(Game::class)->findNextGame();
        $missingResults = $this->em->getRepository(Game::class)->findGamesWithNoResult();

        return $this->render('index/index.html.twig', [
            'nextGame'       => $nextGame,
            'missingResults' => $missingResults,
        ]);
    }
}
