<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Game;
use App\Service\ScheduleService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    private $em;
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService, EntityManagerInterface $em)
    {
        $this->scheduleService = $scheduleService;
        $this->em              = $em;
    }

    /**
     * @Route("/game/{id}", name="game_detail")
     */
    public function index(Game $game): Response
    {
        $roundSchedule = $this->scheduleService->getRoundSchedule(
            $game->getSeason(),
            $game->getRound()
        );

        $userBet = null;
        $bets    = null;

        if (count($game->getBets()) > 0) {
            $bets = [
                'home'  => 0,
                'draw'  => 0,
                'guest' => 0,
            ];

            foreach ($game->getBets() as $bet) {
                if ($bet->getGoalsHome() > $bet->getGoalsGuest()) {
                    $bets['home']++;
                } elseif ($bet->getGoalsHome() == $bet->getGoalsGuest()) {
                    $bets['draw']++;
                } else {
                    $bets['guest']++;
                }

                if ($this->getUser() === $bet->getUser()) {
                    $userBet = $bet;
                }
            }

            $bets = [
                'home'  => number_format(($bets['home'] / count($game->getBets())) * 100, 1),
                'draw'  => number_format(($bets['draw'] / count($game->getBets())) * 100, 1),
                'guest' => number_format(($bets['guest'] / count($game->getBets())) * 100, 1),
            ];
        }

        return $this->render('game/game.html.twig', [
            'game'          => $game,
            'roundSchedule' => $roundSchedule,
            'bets'          => $bets,
            'userBet'       => $userBet,
        ]);
    }

    /**
     * @Route("/game/{id}/save-bet", name="game_save_bet", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function saveBet(Request $request, Game $game): Response
    {
        if (new \DateTime() > $game->getDate()) {
            return $this->json(['status' => 'error'], 302);
        }

        $data = json_decode($request->request->get('data'), true);

        /** @var Bet $bet */
        $bet = $this->em
            ->getRepository(Bet::class)
            ->findOneBy([
                            'game' => $game,
                            'user' => $this->getUser(),
                        ]);
        $bet = $bet ? $bet : new Bet();
        $bet->setGoalsHome($data['goalsHome']);
        $bet->setGoalsGuest($data['goalsGuest']);

        if (!$bet->getId()) {
            $bet->setUser($this->getUser());
            $bet->setGame($game);
            $bet->setCreatedAt(new \DateTimeImmutable());

            $this->em->persist($bet);
        } else {
            $bet->setUpdatedAt(new \DateTimeImmutable());
        }

        $this->em->flush();

        return $this->json([
                               'home'  => '50',
                               'draw'  => '15',
                               'guest' => '35',
                           ]);
    }

    /**
     * @Route("/game/{id}/save-result", name="game_save_result", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function saveResult(Request $request, Game $game): Response
    {
        if ($game->getGoalsHome() !== null && $game->getGoalsGuest() !== null) {
            return $this->json(['status' => 'error'], 302);
        }

        $data = json_decode($request->request->get('data'), true);

        $game->setGoalsHome(intval($data['goalsHome']));
        $game->setGoalsGuest(intval($data['goalsGuest']));
        $game->setUpdatedAt(new \DateTimeImmutable());
        $game->setUpdatedBy($this->getUser());

        $this->em->flush();

        return $this->json(['status' => 'success']);
    }
}
