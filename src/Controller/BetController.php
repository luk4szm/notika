<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Season;
use App\Service\BetService;
use App\Service\GameService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BetController extends AbstractController
{
    private $em;
    private $betService;
    private $gameService;

    public function __construct(
        EntityManagerInterface $entityManager,
        BetService $betService,
        GameService $gameService
    )
    {
        $this->em          = $entityManager;
        $this->betService  = $betService;
        $this->gameService = $gameService;
    }

    /**
     * @Route("/bets/{season}", name="current_bets")
     * @ParamConverter("season", isOptional=true)
     * @Entity("season", expr="repository.findOneBySlug(season)")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param Season $season
     * @return Response
     * @throws \Exception
     */
    public function currentBets(Request $request, Season $season = null): Response
    {
        // save bets form multiple game form
        // check is form clicked
        $saved = false;
        if ($request->request->get('save-bets') !== null) {
            $bets = $request->request->all();

            foreach ($bets as $gameId => $bet) {
                // skip if some value is missing or element of array is not bet value (exclude save-bets element)
                if (!isset($bet['goalsHome']) ||
                    !isset($bet['goalsGuest']) ||
                    !strlen($bet['goalsHome']) ||
                    !strlen($bet['goalsGuest'])
                ) {
                    continue;
                }

                // save bet to db
                $this->gameService->saveUserBet(
                    $this->em->getRepository(Game::class)->find($gameId),
                    $this->getUser(),
                    $bets[$gameId]
                );

                $saved = true;
            }
        }

        if ($season) {
            $games = $this->em->getRepository(Game::class)->findUpcomingGames($season);
        } else {
            $games = $this->gameService->getUserMissingBets($season);
        }

        return $this->render('bet/missing_bets.html.twig', [
            'games'   => $games,
            'newBets' => $saved,
        ]);
    }

    /**
     * @Route("/bet/generate", name="bet_generate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function betGenerate(): Response
    {
        $this->betService->generateBets();

        return $this->json('ok');
    }
}
